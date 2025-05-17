document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    function switchTab(tabId) {
        // Cacher tous les contenus
        tabContents.forEach(content => {
            content.classList.add('hidden');
        });

        // Réinitialiser tous les boutons
        tabButtons.forEach(button => {
            button.classList.remove('border-primary', 'text-primary');
            button.classList.add('border-transparent', 'text-gray-500');
        });

        // Afficher le contenu sélectionné
        const selectedContent = document.getElementById(tabId + '-content');
        if (selectedContent) {
            selectedContent.classList.remove('hidden');
        }

        // Activer le bouton sélectionné
        const selectedButton = document.querySelector(`[data-tab="${tabId}"]`);
        if (selectedButton) {
            selectedButton.classList.remove('border-transparent', 'text-gray-500');
            selectedButton.classList.add('border-primary', 'text-primary');
        }
    }

    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            const tabId = button.getAttribute('data-tab');
            switchTab(tabId);
        });
    });
});
