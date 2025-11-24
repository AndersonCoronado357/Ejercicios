<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Question;
use App\Models\Response;
use App\Models\Answer;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::withCount(['responses', 'questions'])->latest()->get();
        return view('surveys.index', compact('surveys'));
    }

    public function create()
    {
        return view('surveys.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'questions' => 'required|array|min:1',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'required|in:text,multiple_choice,checkbox,rating',
            'questions.*.options' => 'nullable|array',
            'questions.*.is_required' => 'boolean'
        ]);

        $survey = Survey::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'is_active' => true
        ]);

        foreach ($validated['questions'] as $index => $questionData) {
            Question::create([
                'survey_id' => $survey->id,
                'question_text' => $questionData['question_text'],
                'question_type' => $questionData['question_type'],
                'options' => $questionData['options'] ?? null,
                'is_required' => $questionData['is_required'] ?? false,
                'order' => $index
            ]);
        }

        return redirect()->route('surveys.show', $survey)->with('success', 'Encuesta creada exitosamente');
    }

    public function show(Survey $survey)
    {
        $survey->load('questions');
        return view('surveys.show', compact('survey'));
    }

    public function respond(Request $request, Survey $survey)
    {
        if (!$survey->is_active) {
            return redirect()->route('surveys.index')->with('error', 'Esta encuesta ya no está activa');
        }

        $rules = [
            'respondent_name' => 'nullable|string|max:255',
            'respondent_email' => 'nullable|email|max:255'
        ];

        foreach ($survey->questions as $question) {
            $fieldName = 'question_' . $question->id;

            if ($question->is_required) {
                $rules[$fieldName] = 'required';
            }

            if ($question->question_type === 'rating') {
                $rules[$fieldName] = ($question->is_required ? 'required|' : 'nullable|') . 'integer|min:1|max:5';
            }
        }

        $validated = $request->validate($rules);

        $response = Response::create([
            'survey_id' => $survey->id,
            'respondent_name' => $validated['respondent_name'] ?? null,
            'respondent_email' => $validated['respondent_email'] ?? null
        ]);

        foreach ($survey->questions as $question) {
            $fieldName = 'question_' . $question->id;
            $answerValue = $request->input($fieldName);

            if ($answerValue !== null) {
                Answer::create([
                    'response_id' => $response->id,
                    'question_id' => $question->id,
                    'answer_text' => in_array($question->question_type, ['text']) ? $answerValue : null,
                    'answer_options' => in_array($question->question_type, ['multiple_choice', 'checkbox']) ? (is_array($answerValue) ? $answerValue : [$answerValue]) : null,
                    'rating' => $question->question_type === 'rating' ? $answerValue : null
                ]);
            }
        }

        return redirect()->route('surveys.index')->with('success', '¡Gracias por completar la encuesta!');
    }

    public function results(Survey $survey)
    {
        $survey->load(['questions', 'responses.answers']);

        $results = [];
        foreach ($survey->questions as $question) {
            $answers = Answer::where('question_id', $question->id)->get();

            $questionResults = [
                'question' => $question->question_text,
                'type' => $question->question_type,
                'total_responses' => $answers->count()
            ];

            switch ($question->question_type) {
                case 'text':
                    $questionResults['responses'] = $answers->pluck('answer_text')->filter()->toArray();
                    break;

                case 'multiple_choice':
                    $optionCounts = [];
                    foreach ($question->options as $option) {
                        $optionCounts[$option] = $answers->filter(function($answer) use ($option) {
                            return in_array($option, $answer->answer_options ?? []);
                        })->count();
                    }
                    $questionResults['options'] = $optionCounts;
                    break;

                case 'checkbox':
                    $optionCounts = [];
                    foreach ($question->options as $option) {
                        $optionCounts[$option] = $answers->filter(function($answer) use ($option) {
                            return in_array($option, $answer->answer_options ?? []);
                        })->count();
                    }
                    $questionResults['options'] = $optionCounts;
                    break;

                case 'rating':
                    $ratings = $answers->pluck('rating')->filter();
                    $questionResults['average'] = $ratings->count() > 0 ? round($ratings->avg(), 2) : 0;
                    $questionResults['distribution'] = [
                        1 => $ratings->filter(fn($r) => $r == 1)->count(),
                        2 => $ratings->filter(fn($r) => $r == 2)->count(),
                        3 => $ratings->filter(fn($r) => $r == 3)->count(),
                        4 => $ratings->filter(fn($r) => $r == 4)->count(),
                        5 => $ratings->filter(fn($r) => $r == 5)->count(),
                    ];
                    break;
            }

            $results[] = $questionResults;
        }

        return view('surveys.results', compact('survey', 'results'));
    }
}
