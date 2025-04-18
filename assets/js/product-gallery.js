/**
 * Gestion de la galerie d'images pour la page détail produit
 */
class ProductGallery {
    constructor(options = {}) {
        // Éléments du DOM
        this.mainImageContainer = document.getElementById(options.mainImageId || 'main-image');
        this.thumbnailsSelector = options.thumbnailsSelector || '.thumbnail-image';
        this.thumbnails = document.querySelectorAll(this.thumbnailsSelector);
        this.lightboxEnabled = options.lightboxEnabled !== undefined ? options.lightboxEnabled : true;
        this.showNavigation = options.showNavigation !== undefined ? options.showNavigation : true;
        this.currentIndex = 0;

        // Options
        this.options = {
            animationDuration: options.animationDuration || 300,
            activeClass: options.activeClass || 'ring-2 ring-primary',
        };

        // Lightbox elements
        this.lightbox = null;
        this.lightboxImage = null;

        this.init();
    }

    /**
     * Initialisation de la galerie
     */
    init() {
        if (!this.mainImageContainer || this.thumbnails.length === 0) {
            return;
        }

        // Ajouter la classe active au premier thumbnail
        if (this.thumbnails[0]) {
            this.setActiveThumb(this.thumbnails[0]);
        }

        // Attacher les événements aux thumbnails
        this.attachThumbnailEvents();

        // Initialiser la lightbox si activée
        if (this.lightboxEnabled) {
            this.initLightbox();
        }

        // Ajouter les contrôles de navigation si besoin
        if (this.showNavigation && this.thumbnails.length > 1) {
            this.addNavigationControls();
        }
    }

    /**
     * Attacher les événements de clic aux vignettes
     */
    attachThumbnailEvents() {
        this.thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', (event) => {
                event.preventDefault();
                this.changeMainImage(thumbnail);
                this.setActiveThumb(thumbnail);
                this.currentIndex = index;
            });
        });
    }

    /**
     * Changer l'image principale
     */
    changeMainImage(thumbnail) {
        if (!this.mainImageContainer) return;

        const imgElement = this.mainImageContainer.querySelector('img');
        if (!imgElement) return;

        const src = thumbnail.getAttribute('data-src');
        const alt = thumbnail.getAttribute('data-alt');

        // Effet de transition
        imgElement.style.opacity = '0';
        
        setTimeout(() => {
            imgElement.src = src;
            if (alt) imgElement.alt = alt;
            imgElement.style.opacity = '1';
        }, 150);
    }

    /**
     * Marquer la vignette active
     */
    setActiveThumb(activeThumb) {
        // Supprimer la classe active de toutes les vignettes
        this.thumbnails.forEach(thumb => {
            thumb.classList.remove(...this.options.activeClass.split(' '));
        });

        // Ajouter la classe active à la vignette sélectionnée
        activeThumb.classList.add(...this.options.activeClass.split(' '));
    }

    /**
     * Initialiser la lightbox
     */
    initLightbox() {
        if (!this.mainImageContainer) return;

        // Créer la structure de la lightbox
        this.createLightboxStructure();

        // Ajouter un événement de clic sur l'image principale pour ouvrir la lightbox
        const mainImg = this.mainImageContainer.querySelector('img');
        if (mainImg) {
            mainImg.style.cursor = 'pointer';
            mainImg.addEventListener('click', () => this.openLightbox());
        }

        // Ajouter des événements pour fermer la lightbox
        this.lightbox.addEventListener('click', (event) => {
            if (event.target === this.lightbox) {
                this.closeLightbox();
            }
        });

        const closeBtn = this.lightbox.querySelector('.lightbox-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', (event) => {
                event.stopPropagation();
                this.closeLightbox();
            });
        }

        // Touches du clavier pour la navigation
        document.addEventListener('keydown', (event) => {
            if (!this.lightbox.classList.contains('active')) return;

            if (event.key === 'Escape') {
                this.closeLightbox();
            } else if (event.key === 'ArrowRight') {
                this.nextLightboxImage();
            } else if (event.key === 'ArrowLeft') {
                this.prevLightboxImage();
            }
        });

        // Boutons de navigation dans la lightbox
        const prevBtn = this.lightbox.querySelector('.lightbox-prev');
        const nextBtn = this.lightbox.querySelector('.lightbox-next');

        if (prevBtn) {
            prevBtn.addEventListener('click', (event) => {
                event.stopPropagation();
                this.prevLightboxImage();
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', (event) => {
                event.stopPropagation();
                this.nextLightboxImage();
            });
        }
    }

    /**
     * Créer la structure HTML de la lightbox
     */
    createLightboxStructure() {
        // Créer les éléments de la lightbox
        this.lightbox = document.createElement('div');
        this.lightbox.className = 'fixed inset-0 bg-black bg-opacity-90 z-50 flex flex-col items-center justify-center hidden';
        this.lightbox.style.opacity = '0';
        this.lightbox.style.transition = `opacity ${this.options.animationDuration}ms ease`;

        // Bouton de fermeture
        const closeButton = document.createElement('button');
        closeButton.className = 'lightbox-close absolute top-4 right-4 text-white p-2 z-10';
        closeButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        `;

        // Container pour l'image
        const imageContainer = document.createElement('div');
        imageContainer.className = 'relative flex-1 flex items-center justify-center w-full max-h-full p-4 md:p-8';

        // L'image
        this.lightboxImage = document.createElement('img');
        this.lightboxImage.className = 'max-h-full max-w-full object-contain';
        this.lightboxImage.style.transition = 'transform 0.3s ease';

        // Boutons de navigation
        const prevButton = document.createElement('button');
        prevButton.className = 'lightbox-prev absolute left-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-50 rounded-full p-2';
        prevButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        `;

        const nextButton = document.createElement('button');
        nextButton.className = 'lightbox-next absolute right-4 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-50 rounded-full p-2';
        nextButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        `;

        // Assembler la lightbox
        imageContainer.appendChild(this.lightboxImage);
        if (this.thumbnails.length > 1) {
            imageContainer.appendChild(prevButton);
            imageContainer.appendChild(nextButton);
        }
        
        this.lightbox.appendChild(closeButton);
        this.lightbox.appendChild(imageContainer);
        
        document.body.appendChild(this.lightbox);
    }

    /**
     * Ouvrir la lightbox
     */
    openLightbox() {
        if (!this.lightbox) return;

        const currentThumb = this.thumbnails[this.currentIndex];
        const src = currentThumb.getAttribute('data-src');
        const alt = currentThumb.getAttribute('data-alt');

        this.lightboxImage.src = src;
        if (alt) this.lightboxImage.alt = alt;

        this.lightbox.classList.remove('hidden');
        setTimeout(() => {
            this.lightbox.style.opacity = '1';
            this.lightbox.classList.add('active');
            document.body.classList.add('overflow-hidden'); // Empêcher le défilement
        }, 10);
    }

    /**
     * Fermer la lightbox
     */
    closeLightbox() {
        if (!this.lightbox) return;

        this.lightbox.style.opacity = '0';
        document.body.classList.remove('overflow-hidden');
        
        setTimeout(() => {
            this.lightbox.classList.add('hidden');
            this.lightbox.classList.remove('active');
        }, this.options.animationDuration);
    }

    /**
     * Passer à l'image suivante dans la lightbox
     */
    nextLightboxImage() {
        this.goToLightboxImage((this.currentIndex + 1) % this.thumbnails.length);
    }

    /**
     * Passer à l'image précédente dans la lightbox
     */
    prevLightboxImage() {
        this.goToLightboxImage((this.currentIndex - 1 + this.thumbnails.length) % this.thumbnails.length);
    }

    /**
     * Aller à une image spécifique dans la lightbox
     */
    goToLightboxImage(index) {
        if (index < 0 || index >= this.thumbnails.length) return;
        
        this.currentIndex = index;
        const currentThumb = this.thumbnails[index];
        
        // Changer l'image dans la lightbox avec une transition
        this.lightboxImage.style.opacity = '0';
        
        setTimeout(() => {
            this.lightboxImage.src = currentThumb.getAttribute('data-src');
            this.lightboxImage.alt = currentThumb.getAttribute('data-alt') || '';
            this.lightboxImage.style.opacity = '1';
            
            // Mettre à jour la vignette active dans la page principale
            this.setActiveThumb(currentThumb);
            
            // Mettre à jour l'image principale également
            this.changeMainImage(currentThumb);
        }, 200);
    }

    /**
     * Passer à l'image suivante
     */
    nextImage() {
        if (this.thumbnails.length <= 1) return;
        
        this.currentIndex = (this.currentIndex + 1) % this.thumbnails.length;
        const nextThumb = this.thumbnails[this.currentIndex];
        this.changeMainImage(nextThumb);
        this.setActiveThumb(nextThumb);
    }

    /**
     * Passer à l'image précédente
     */
    prevImage() {
        if (this.thumbnails.length <= 1) return;
        
        this.currentIndex = (this.currentIndex - 1 + this.thumbnails.length) % this.thumbnails.length;
        const prevThumb = this.thumbnails[this.currentIndex];
        this.changeMainImage(prevThumb);
        this.setActiveThumb(prevThumb);
    }

    /**
     * Ajouter les boutons de navigation précédent/suivant
     */
    addNavigationControls() {
        if (!this.mainImageContainer) return;

        // Créer les boutons de navigation
        const prevButton = document.createElement('button');
        prevButton.className = 'absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-75 rounded-full p-2 shadow hover:bg-opacity-100 transition-all';
        prevButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>';
        
        const nextButton = document.createElement('button');
        nextButton.className = 'absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-75 rounded-full p-2 shadow hover:bg-opacity-100 transition-all';
        nextButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';

        // Ajouter les événements aux boutons
        prevButton.addEventListener('click', () => this.prevImage());
        nextButton.addEventListener('click', () => this.nextImage());

        // S'assurer que le conteneur est en position relative pour le positionnement absolu des boutons
        this.mainImageContainer.style.position = 'relative';
        
        // Ajouter les boutons au conteneur
        this.mainImageContainer.appendChild(prevButton);
        this.mainImageContainer.appendChild(nextButton);
    }
}

// Initialiser la galerie au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    new ProductGallery({
        mainImageId: 'main-image',
        thumbnailsSelector: '.thumbnail-image',
        lightboxEnabled: true,
        showNavigation: true
    });
});

export default ProductGallery;