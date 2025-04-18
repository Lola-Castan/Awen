/**
 * Gestion du menu de navigation et du dropdown utilisateur
 */
document.addEventListener('DOMContentLoaded', function() {
    // Menu mobile toggle
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // User dropdown menu
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');
    
    if (userMenuButton && userDropdown) {
        let isDropdownOpen = false;
        
        // Fonction pour ouvrir le menu
        function openDropdown() {
            userDropdown.classList.remove('hidden');
            isDropdownOpen = true;
            // Ajouter une classe active au bouton pour indiquer visuellement que le menu est ouvert
            userMenuButton.setAttribute('aria-expanded', 'true');
            userMenuButton.classList.add('ring-2', 'ring-offset-2', 'ring-primary');
        }
        
        // Fonction pour fermer le menu
        function closeDropdown() {
            userDropdown.classList.add('hidden');
            isDropdownOpen = false;
            // Supprimer la classe active
            userMenuButton.setAttribute('aria-expanded', 'false');
            userMenuButton.classList.remove('ring-2', 'ring-offset-2', 'ring-primary');
        }
        
        // Toggle menu on button click - plus robuste maintenant
        userMenuButton.addEventListener('click', function(event) {
            event.preventDefault();
            event.stopPropagation();
            
            if (isDropdownOpen) {
                closeDropdown();
            } else {
                openDropdown();
            }
        });
        
        // Support pour clavier (accessibilité)
        userMenuButton.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                if (isDropdownOpen) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
            } else if (event.key === 'Escape' && isDropdownOpen) {
                closeDropdown();
            }
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!isDropdownOpen) return;
            
            const isClickInsideDropdown = userDropdown.contains(event.target);
            const isClickOnButton = userMenuButton.contains(event.target);
            
            if (!isClickInsideDropdown && !isClickOnButton) {
                closeDropdown();
            }
        });
        
        // Prevent menu from closing when clicking inside it
        userDropdown.addEventListener('click', function(event) {
            // Don't prevent default for links - they should still work
            if (event.target.tagName !== 'A') {
                event.stopPropagation();
            }
        });
        
        // Ajouter l'attribut d'accessibilité
        userMenuButton.setAttribute('aria-haspopup', 'true');
        userMenuButton.setAttribute('aria-expanded', 'false');
    }
});