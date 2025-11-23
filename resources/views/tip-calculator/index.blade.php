@extends('layouts.app')

@section('title', 'Calculadora de Propinas')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Calculadora de Propinas</h1>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <form id="tipCalculatorForm" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-dollar-sign mr-2"></i>Monto de la Cuenta
                        </label>
                        <input type="number"
                               id="billAmount"
                               name="bill_amount"
                               step="0.01"
                               min="0.01"
                               placeholder="0.00"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                               required>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-percentage mr-2"></i>Porcentaje de Propina
                        </label>
                        <div class="flex items-center space-x-4">
                            <input type="range"
                                   id="tipPercentage"
                                   name="tip_percentage"
                                   min="0"
                                   max="30"
                                   value="15"
                                   class="flex-1"
                                   oninput="updatePercentageDisplay(this.value)">
                            <span id="percentageDisplay" class="text-2xl font-bold text-green-600 w-16 text-right">15%</span>
                        </div>
                        <div class="flex justify-between mt-2">
                            <button type="button" onclick="setTipPercentage(10)" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 transition">10%</button>
                            <button type="button" onclick="setTipPercentage(15)" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 transition">15%</button>
                            <button type="button" onclick="setTipPercentage(18)" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 transition">18%</button>
                            <button type="button" onclick="setTipPercentage(20)" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 transition">20%</button>
                            <button type="button" onclick="setTipPercentage(25)" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300 transition">25%</button>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            <i class="fas fa-users mr-2"></i>Número de Personas
                        </label>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="adjustPeople(-1)" class="w-10 h-10 bg-red-500 text-white rounded-full hover:bg-red-600 transition">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number"
                                   id="peopleCount"
                                   name="people_count"
                                   min="1"
                                   value="1"
                                   class="w-20 text-center px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                                   onchange="calculateTip()"
                                   required>
                            <button type="button" onclick="adjustPeople(1)" class="w-10 h-10 bg-green-500 text-white rounded-full hover:bg-green-600 transition">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold text-lg">
                        <i class="fas fa-calculator mr-2"></i>Calcular
                    </button>
                </form>
            </div>

            <div id="resultPanel" class="bg-gray-50 rounded-lg p-6 hidden">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Resultado</h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-300">
                        <span class="text-gray-600">Monto de la Cuenta:</span>
                        <span class="text-xl font-semibold">$<span id="resultBill">0.00</span></span>
                    </div>

                    <div class="flex justify-between items-center pb-3 border-b border-gray-300">
                        <span class="text-gray-600">Propina (<span id="resultTipPercent">0</span>%):</span>
                        <span class="text-xl font-semibold text-green-600">+$<span id="resultTip">0.00</span></span>
                    </div>

                    <div class="flex justify-between items-center pb-3 border-b-2 border-gray-400">
                        <span class="text-gray-800 font-bold">Total:</span>
                        <span class="text-2xl font-bold text-gray-800">$<span id="resultTotal">0.00</span></span>
                    </div>

                    <div id="perPersonSection" class="pt-3 hidden">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="text-lg font-bold text-blue-800 mb-3">División por Persona</h3>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Cantidad por persona:</span>
                                <span class="text-xl font-bold text-blue-600">$<span id="resultPerPerson">0.00</span></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Propina por persona:</span>
                                <span class="text-lg font-semibold text-green-600">$<span id="resultTipPerPerson">0.00</span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" onclick="resetCalculator()" class="w-full mt-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                    <i class="fas fa-redo mr-2"></i>Nuevo Cálculo
                </button>
            </div>

            <div id="initialMessage" class="bg-gray-50 rounded-lg p-6 flex items-center justify-center">
                <div class="text-center text-gray-500">
                    <i class="fas fa-receipt text-6xl mb-4"></i>
                    <p class="text-lg">Ingresa el monto de tu cuenta para comenzar</p>
                </div>
            </div>
        </div>

        <div class="mt-8 grid md:grid-cols-3 gap-4">
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <i class="fas fa-utensils text-3xl text-blue-600 mb-2"></i>
                <h3 class="font-bold text-gray-800 mb-1">Restaurante</h3>
                <p class="text-gray-600 text-sm">15-20% recomendado</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <i class="fas fa-taxi text-3xl text-green-600 mb-2"></i>
                <h3 class="font-bold text-gray-800 mb-1">Taxi/Delivery</h3>
                <p class="text-gray-600 text-sm">10-15% recomendado</p>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <i class="fas fa-cut text-3xl text-purple-600 mb-2"></i>
                <h3 class="font-bold text-gray-800 mb-1">Servicios</h3>
                <p class="text-gray-600 text-sm">15-25% recomendado</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('tipCalculatorForm').addEventListener('submit', function(e) {
        e.preventDefault();
        calculateTip();
    });

    function calculateTip() {
        const billAmount = parseFloat(document.getElementById('billAmount').value);
        const tipPercentage = parseFloat(document.getElementById('tipPercentage').value);
        const peopleCount = parseInt(document.getElementById('peopleCount').value);

        if (isNaN(billAmount) || billAmount <= 0) {
            return;
        }

        const formData = new FormData();
        formData.append('bill_amount', billAmount);
        formData.append('tip_percentage', tipPercentage);
        formData.append('people_count', peopleCount);
        formData.append('_token', document.querySelector('input[name="_token"]').value);

        fetch('{{ route("tip-calculator.calculate") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('resultBill').textContent = data.bill_amount;
            document.getElementById('resultTipPercent').textContent = data.tip_percentage;
            document.getElementById('resultTip').textContent = data.tip_amount;
            document.getElementById('resultTotal').textContent = data.total_amount;
            document.getElementById('resultPerPerson').textContent = data.per_person;
            document.getElementById('resultTipPerPerson').textContent = data.tip_per_person;

            document.getElementById('initialMessage').classList.add('hidden');
            document.getElementById('resultPanel').classList.remove('hidden');

            if (peopleCount > 1) {
                document.getElementById('perPersonSection').classList.remove('hidden');
            } else {
                document.getElementById('perPersonSection').classList.add('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function updatePercentageDisplay(value) {
        document.getElementById('percentageDisplay').textContent = value + '%';
        if (document.getElementById('billAmount').value) {
            calculateTip();
        }
    }

    function setTipPercentage(percentage) {
        document.getElementById('tipPercentage').value = percentage;
        updatePercentageDisplay(percentage);
    }

    function adjustPeople(change) {
        const input = document.getElementById('peopleCount');
        let currentValue = parseInt(input.value) || 1;
        let newValue = currentValue + change;

        if (newValue < 1) newValue = 1;

        input.value = newValue;
        if (document.getElementById('billAmount').value) {
            calculateTip();
        }
    }

    function resetCalculator() {
        document.getElementById('tipCalculatorForm').reset();
        document.getElementById('tipPercentage').value = 15;
        updatePercentageDisplay(15);
        document.getElementById('resultPanel').classList.add('hidden');
        document.getElementById('initialMessage').classList.remove('hidden');
    }

    document.getElementById('billAmount').addEventListener('input', function() {
        if (this.value) {
            calculateTip();
        }
    });
</script>
@endpush
