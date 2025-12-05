import './bootstrap';

/**** Script para abrir/fechar o dropdown ****/
const dropdownButton = document.getElementById('userDropdownButton');
const dropdownContent = document.getElementById('dropdownContent');

dropdownButton.addEventListener('click', function () {
    const isOpen = dropdownContent.classList.contains('hidden');
    if (isOpen) {
        dropdownContent.classList.remove('hidden');
    } else {
        dropdownContent.classList.add('hidden');
    }
});

// Fechar o dropdown se clicar fora dele
window.addEventListener('click', function (event) {
    if (!dropdownButton.contains(event.target) && !dropdownContent.contains(event.target)) {
        dropdownContent.classList.add('hidden');
    }
});

/**** Apresentar e ocultar sidebar ****/
document.getElementById('toggleSidebar').addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('sidebar-open');
});

document.getElementById('closeSidebar').addEventListener('click', function () {
    document.getElementById('sidebar').classList.remove('sidebar-open');
});

/**** Alterna entre tema claro e escuro ****/
document.addEventListener("DOMContentLoaded", function () {

    // Obter o elemento <html> para manipular a classe dark
    const htmlElement = document.documentElement;

    // Obter o id do botão tema claro e escuro
    const themeToggle = document.getElementById("themeToggle");

    // Obter o id do ícone escuro
    const iconMoon = document.getElementById("iconMoon");

    // Obter o id do ícone claro
    const iconSun = document.getElementById("iconSun");

    // Função para alternar os ícones claro e escuro
    function updateIcons() {
        if (htmlElement.classList.contains("dark")) {
            iconMoon.classList.remove("hidden");
            iconSun.classList.add("hidden");
        } else {
            iconMoon.classList.add("hidden");
            iconSun.classList.remove("hidden");
        }
    }

    // Aplicar o tema salvo no localStorage ou a preferência do sistema
    const isDarkMode = localStorage.theme === "dark" || // Se o localStorage.theme for "dark", ativa o modo escuro
        (!("theme" in localStorage) && window.matchMedia("(prefers-color-scheme: dark)").matches);
    // Se NÃO houver um tema salvo no localStorage, verifica se o sistema está em dark mode

    htmlElement.classList.toggle("dark", isDarkMode);
    updateIcons(); // Atualiza os ícones na inicialização

    // Evento de clique para alternar o tema e os ícones
    themeToggle.addEventListener("click", function () {
        htmlElement.classList.toggle("dark");
        localStorage.theme = htmlElement.classList.contains("dark") ? "dark" : "light";
        updateIcons(); // Atualiza os ícones após alterar o tema
    });
});

// Função para apresentar o SweetAlert2 para confirmar a exclusão
window.confirmDelete = function (id, prefix = '') {
    const formId = prefix ? 'delete-' + prefix + '-form-' + id : 'delete-form-' + id;
    Swal.fire({
        title: "Tem certeza?",
        text: "Essa ação não pode ser desfeita!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sim, excluir!",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

// Mascara para o campo CPF
const cpfInput = document.getElementById("cpf");

function aplicarMascaraCPF(valor) {
    let value = valor.replace(/\D/g, ''); // Remove tudo que não é número
    if (value.length > 11) value = value.slice(0, 11); // Limita a 11 dígitos

    value = value.replace(/(\d{3})(\d)/, "$1.$2");
    value = value.replace(/(\d{3})(\d)/, "$1.$2");
    value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");

    return value;
}

if (cpfInput) {
    // Aplica a máscara no carregamento da página
    cpfInput.value = aplicarMascaraCPF(cpfInput.value);

    // Aplica a máscara enquanto digita
    cpfInput.addEventListener("input", function (e) {
        e.target.value = aplicarMascaraCPF(e.target.value);
    });
}

// Função para validação de senha
document.addEventListener("DOMContentLoaded", function () {
    // Função genérica para validar senha
    function setupPasswordValidation(passwordInputId, requirementsPrefix) {
        const passwordInput = document.getElementById(passwordInputId);
        if (!passwordInput) return;

        passwordInput.addEventListener("input", function () {
            const value = this.value;

            const requirements = [{
                id: `req-uppercase${requirementsPrefix}`,
                regex: /[A-Z]/ // Letra maiúscula
            },
            {
                id: `req-lowercase${requirementsPrefix}`,
                regex: /[a-z]/ // Letra minúscula
            },
            {
                id: `req-number${requirementsPrefix}`,
                regex: /[0-9]/ // Número
            },
            {
                id: `req-special${requirementsPrefix}`,
                test: val => /^[A-Za-z0-9#%+:$@&]*$/.test(val) && /[#%+:$@&]/.test(val)
                // Apenas símbolos permitidos e pelo menos um deles presente
            },
            {
                id: `req-length${requirementsPrefix}`,
                test: val => val.length >= 8 && val.length <= 50
                // Comprimento entre 8 e 50
            },
            {
                id: `req-latin${requirementsPrefix}`,
                test: val => /^[A-Za-z0-9#%+:$@&]*$/.test(val)
                // Apenas alfabeto latino e símbolos permitidos
            }
            ];

            requirements.forEach(req => {
                const element = document.getElementById(req.id);
                if (!element) return;

                const passed = req.regex ? req.regex.test(value) : req.test(value);

                const dot = element.querySelector('span');
                element.classList.toggle('text-green-600', passed);
                element.classList.toggle('text-gray-500', !passed);
                dot.classList.toggle('bg-green-500', passed);
                dot.classList.toggle('bg-gray-400', !passed);
            });
        });
    }

    // Configurar validação para ambas as páginas
    setupPasswordValidation('password', ''); // Página edit_password.blade.php
    setupPasswordValidation('password_main', '-main'); // Seção na página edit.blade.php
});

// Função para alternar visibilidade da senha
document.addEventListener("DOMContentLoaded", function () {
    window.togglePassword = function (fieldId, btn) {
        const input = document.getElementById(fieldId);
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';

        btn.innerHTML = isPassword
            ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.005-3.368M9.88 9.88a3 3 0 104.24 4.24M6.1 6.1l11.8 11.8" />
               </svg>`
            : `<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
               </svg>`;
    };
});

/**** Função para preview de imagem ****/
window.previewImageUpload = function () {
    const input = document.getElementById('image');
    const preview = document.getElementById('preview-image');

    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = function (e) {
            // Se o preview é uma imagem
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                // Se o preview é uma div (placeholder), substitui por imagem
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Preview';
                img.id = 'preview-image';
                img.className = 'w-32 h-32 rounded-full object-cover shadow-lg ring-4 ring-gray-200 dark:ring-gray-600';
                preview.parentNode.replaceChild(img, preview);
            }
        }

        reader.readAsDataURL(input.files[0]);
    }
};
