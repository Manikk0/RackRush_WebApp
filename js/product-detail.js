// Product Detail JavaScript

document.addEventListener('DOMContentLoaded', () => {
    // Gallery Navigation Logic
    const galleryTrack = document.getElementById('gallery-track');
    const slides = document.querySelectorAll('.gallery-slide-img');
    const prevBtn = document.getElementById('gallery-prev');
    const nextBtn = document.getElementById('gallery-next');
    
    // Zoom Modal Elements
    const zoomBtn = document.getElementById('gallery-zoom');
    const zoomModal = document.getElementById('zoom-modal');
    const zoomModalImg = document.getElementById('zoom-modal-img');
    const zoomModalClose = document.getElementById('zoom-modal-close');
    
    let currentIndex = 0;
    const totalSlides = slides.length;

    // Gallery Sliding
    function updateGallery() {
        if (galleryTrack) {
            galleryTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
        }
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateGallery();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            currentIndex = (currentIndex + 1) % totalSlides;
            updateGallery();
        });
    }

    // Modal Control
    if (zoomBtn && zoomModal && zoomModalImg) {
        zoomBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            // Get the src of the currently active slide image
            const currentImg = slides[currentIndex];
            if (currentImg) {
                zoomModalImg.src = currentImg.src;
                zoomModal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent scroll
            }
        });
    }

    function closeModal() {
        if (zoomModal) {
            zoomModal.classList.remove('active');
            document.body.style.overflow = ''; // Restore scroll
        }
    }

    if (zoomModalClose) {
        zoomModalClose.addEventListener('click', closeModal);
    }

    if (zoomModal) {
        zoomModal.addEventListener('click', (e) => {
            if (e.target === zoomModal) {
                closeModal();
            }
        });
    }

    // ESC key to close modal
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Heart icon toggle (simple visual state change)
    const wishlistBtn = document.querySelector('.gallery-wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function () {
            this.style.transform = 'scale(1.3)';
            setTimeout(() => {
                this.style.transform = 'scale(1.15)';
            }, 200);
        });
    }
});
