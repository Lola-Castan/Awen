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
        // Toggle menu on button click
        userMenuButton.addEventListener('click', function(event) {
            event.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const isClickInsideDropdown = userDropdown.contains(event.target);
            const isClickOnButton = userMenuButton.contains(event.target);
            
            if (!isClickInsideDropdown && !isClickOnButton && !userDropdown.classList.contains('hidden')) {
                userDropdown.classList.add('hidden');
            }
        });
        
        // Prevent menu from closing when clicking inside it
        userDropdown.addEventListener('click', function(event) {
            // Don't prevent default for links - they should still work
            if (event.target.tagName !== 'A') {
                event.stopPropagation();
            }
        });
    }
});