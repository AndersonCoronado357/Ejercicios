@extends('layouts.app')

@section('title', 'Generador de Contraseñas')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Generador de Contraseñas Seguras</h1>

        <div class="mb-6">
            <div class="bg-gray-50 rounded-lg p-4 border-2 border-gray-200">
                <div class="flex items-center justify-between mb-2">
                    <input type="text"
                           id="passwordOutput"
                           readonly
                           placeholder="La contraseña generada aparecerá aquí"
                           class="flex-1 text-lg font-mono bg-transparent border-none focus:outline-none">
                    <button onclick="copyPassword()"
                            id="copyButton"
                            class="ml-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        <i class="fas fa-copy mr-2"></i>Copiar
                    </button>
                </div>
                <div id="strengthIndicator" class="hidden">
                    <div class="flex items-center mt-3">
                        <span class="text-sm text-gray-600 mr-2">Fortaleza:</span>
                        <div class="flex-1 bg-gray-200 rounded-full h-2">
                            <div id="strengthBar" class="h-2 rounded-full transition-all duration-300"></div>
                        </div>
                        <span id="strengthText" class="ml-2 text-sm font-semibold"></span>
                    </div>
                    <div class="mt-2">
                        <span class="text-xs text-gray-500">Entropía: <span id="entropyValue">0</span> bits</span>
                    </div>
                </div>
            </div>
        </div>

        <form id="passwordForm" class="space-y-6">
            @csrf

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Longitud de la Contraseña: <span id="lengthDisplay" class="text-purple-600">12</span>
                </label>
                <input type="range"
                       id="passwordLength"
                       name="length"
                       min="4"
                       max="128"
                       value="12"
                       class="w-full"
                       oninput="updateLengthDisplay(this.value)">
                <div class="flex justify-between text-xs text-gray-500 mt-1">
                    <span>4</span>
                    <span>32</span>
                    <span>64</span>
                    <span>96</span>
                    <span>128</span>
                </div>
            </div>

            <div>
                <h3 class="text-gray-700 font-bold mb-3">Tipos de Caracteres</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="checkbox" name="uppercase" checked class="mr-3 w-4 h-4 text-purple-600">
                        <div class="flex-1">
                            <span class="font-semibold">Mayúsculas</span>
                            <span class="text-gray-500 text-sm ml-2">(A-Z)</span>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="checkbox" name="lowercase" checked class="mr-3 w-4 h-4 text-purple-600">
                        <div class="flex-1">
                            <span class="font-semibold">Minúsculas</span>
                            <span class="text-gray-500 text-sm ml-2">(a-z)</span>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="checkbox" name="numbers" checked class="mr-3 w-4 h-4 text-purple-600">
                        <div class="flex-1">
                            <span class="font-semibold">Números</span>
                            <span class="text-gray-500 text-sm ml-2">(0-9)</span>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="checkbox" name="symbols" checked class="mr-3 w-4 h-4 text-purple-600">
                        <div class="flex-1">
                            <span class="font-semibold">Símbolos</span>
                            <span class="text-gray-500 text-sm ml-2">(!@#$%^&*)</span>
                        </div>
                    </label>
                </div>
            </div>

            <div>
                <h3 class="text-gray-700 font-bold mb-3">Opciones Adicionales</h3>
                <div class="space-y-3">
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="checkbox" name="exclude_similar" class="mr-3 w-4 h-4 text-purple-600">
                        <div>
                            <span class="font-semibold">Excluir caracteres similares</span>
                            <span class="text-gray-500 text-sm block">No incluir: i, l, 1, L, o, 0, O</span>
                        </div>
                    </label>

                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition">
                        <input type="checkbox" name="exclude_ambiguous" class="mr-3 w-4 h-4 text-purple-600">
                        <div>
                            <span class="font-semibold">Excluir caracteres ambiguos</span>
                            <span class="text-gray-500 text-sm block">Solo usar símbolos básicos: !@#$%^&*</span>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-bold text-lg">
                <i class="fas fa-key mr-2"></i>Generar Contraseña
            </button>
        </form>

        <div class="mt-8 grid md:grid-cols-2 gap-4">
            <div class="bg-blue-50 rounded-lg p-4">
                <h3 class="font-bold text-gray-800 mb-2">
                    <i class="fas fa-history text-blue-600 mr-2"></i>Historial Reciente
                </h3>
                <div id="passwordHistory" class="space-y-2 max-h-40 overflow-y-auto">
                    <p class="text-gray-500 text-sm">No hay contraseñas generadas aún</p>
                </div>
            </div>

            <div class="bg-green-50 rounded-lg p-4">
                <h3 class="font-bold text-gray-800 mb-2">
                    <i class="fas fa-lightbulb text-green-600 mr-2"></i>Consejos de Seguridad
                </h3>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Usa contraseñas de al menos 12 caracteres</li>
                    <li>• Incluye todos los tipos de caracteres</li>
                    <li>• Evita palabras del diccionario</li>
                    <li>• Usa una contraseña única para cada cuenta</li>
                    <li>• Considera usar un gestor de contraseñas</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="copyNotification" class="fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-20 transition-transform duration-300">
    <i class="fas fa-check mr-2"></i>Contraseña copiada al portapapeles
</div>
@endsection

@push('scripts')
<script>
    let passwordHistory = [];

    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        generatePassword();
    });

    function generatePassword() {
        const formData = new FormData(document.getElementById('passwordForm'));

        const checkboxes = ['uppercase', 'lowercase', 'numbers', 'symbols', 'exclude_similar', 'exclude_ambiguous'];
        checkboxes.forEach(name => {
            const checkbox = document.querySelector(`input[name="${name}"]`);
            formData.append(name, checkbox.checked ? '1' : '0');
        });

        fetch('{{ route("password-generator.generate") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById('passwordOutput').value = data.password;
            updateStrengthIndicator(data.strength, data.entropy);
            addToHistory(data.password);
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function updateLengthDisplay(value) {
        document.getElementById('lengthDisplay').textContent = value;
    }

    function updateStrengthIndicator(strength, entropy) {
        const indicator = document.getElementById('strengthIndicator');
        const bar = document.getElementById('strengthBar');
        const text = document.getElementById('strengthText');
        const entropyValue = document.getElementById('entropyValue');

        indicator.classList.remove('hidden');
        entropyValue.textContent = entropy;

        bar.className = 'h-2 rounded-full transition-all duration-300';
        text.textContent = strength.text;

        const widthMap = {
            'weak': 'w-1/5',
            'fair': 'w-2/5',
            'good': 'w-3/5',
            'strong': 'w-4/5',
            'very-strong': 'w-full'
        };

        const colorMap = {
            'red': 'bg-red-500',
            'orange': 'bg-orange-500',
            'yellow': 'bg-yellow-500',
            'green': 'bg-green-500'
        };

        bar.classList.add(widthMap[strength.level], colorMap[strength.color]);
        text.className = `ml-2 text-sm font-semibold text-${strength.color}-600`;
    }

    function copyPassword() {
        const passwordField = document.getElementById('passwordOutput');
        if (!passwordField.value) return;

        navigator.clipboard.writeText(passwordField.value).then(() => {
            const notification = document.getElementById('copyNotification');
            notification.style.transform = 'translateY(0)';

            setTimeout(() => {
                notification.style.transform = 'translateY(5rem)';
            }, 2000);
        });
    }

    function addToHistory(password) {
        passwordHistory.unshift({
            password: password,
            time: new Date().toLocaleTimeString()
        });

        if (passwordHistory.length > 5) {
            passwordHistory = passwordHistory.slice(0, 5);
        }

        updateHistoryDisplay();
    }

    function updateHistoryDisplay() {
        const historyDiv = document.getElementById('passwordHistory');

        if (passwordHistory.length === 0) {
            historyDiv.innerHTML = '<p class="text-gray-500 text-sm">No hay contraseñas generadas aún</p>';
            return;
        }

        historyDiv.innerHTML = passwordHistory.map((item, index) => `
            <div class="flex items-center justify-between text-sm">
                <span class="font-mono truncate flex-1">${maskPassword(item.password)}</span>
                <button onclick="copyHistoryPassword(${index})" class="ml-2 text-blue-600 hover:text-blue-800">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        `).join('');
    }

    function maskPassword(password) {
        if (password.length <= 8) {
            return password;
        }
        return password.substring(0, 4) + '****' + password.substring(password.length - 4);
    }

    function copyHistoryPassword(index) {
        const password = passwordHistory[index].password;
        navigator.clipboard.writeText(password).then(() => {
            const notification = document.getElementById('copyNotification');
            notification.style.transform = 'translateY(0)';

            setTimeout(() => {
                notification.style.transform = 'translateY(5rem)';
            }, 2000);
        });
    }

    window.onload = function() {
        generatePassword();
    };
</script>
@endpush
