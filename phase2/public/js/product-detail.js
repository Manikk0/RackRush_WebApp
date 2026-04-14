// Product detail gallery interactions.
document.addEventListener('DOMContentLoaded', function () {
    var galleryTrack = document.getElementById('gallery-track');
    var slides = document.querySelectorAll('.gallery-slide-img');
    var prevBtn = document.getElementById('gallery-prev');
    var nextBtn = document.getElementById('gallery-next');
    var zoomBtn = document.getElementById('gallery-zoom');
    var zoomModal = document.getElementById('zoom-modal');
    var zoomModalImg = document.getElementById('zoom-modal-img');
    var zoomModalClose = document.getElementById('zoom-modal-close');
    var currentIndex = 0;
    var totalSlides = slides.length;

    if (totalSlides === 0) {
        return;
    }

    // Move slider to currently selected image index.
    function updateGallery() {
        if (galleryTrack) {
            galleryTrack.style.transform = 'translateX(-' + (currentIndex * 100) + '%)';
        }
    }

    // Previous slide button.
    if (prevBtn) {
        prevBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateGallery();
        });
    }

    // Next slide button.
    if (nextBtn) {
        nextBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            currentIndex = (currentIndex + 1) % totalSlides;
            updateGallery();
        });
    }

    // Open zoom modal with current image.
    if (zoomBtn && zoomModal && zoomModalImg) {
        zoomBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            var currentImg = slides[currentIndex];
            if (currentImg) {
                zoomModalImg.src = currentImg.src;
                zoomModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        });
    }

    // Close zoom modal and restore body scroll.
    function closeModal() {
        if (zoomModal) {
            zoomModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (zoomModalClose) {
        zoomModalClose.addEventListener('click', closeModal);
    }

    if (zoomModal) {
        zoomModal.addEventListener('click', function (e) {
            if (e.target === zoomModal) {
                closeModal();
            }
        });
    }

    // Close on ESC key.
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Small wishlist click animation feedback.
    var wishlistBtn = document.querySelector('.gallery-wishlist-btn');
    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', function () {
            var self = this;
            self.style.transform = 'scale(1.3)';
            setTimeout(function () {
                self.style.transform = 'scale(1.15)';
            }, 200);
        });
    }
});
