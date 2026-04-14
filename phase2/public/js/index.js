// Homepage interactions.
document.addEventListener('DOMContentLoaded', function () {
    // Horizontal category slider buttons.
    var scrollWrapper = document.querySelector('.categories-scroll-wrapper');
    var prevBtn = document.getElementById('categories-prev');
    var nextBtn = document.getElementById('categories-next');

    if (scrollWrapper) {
        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                scrollWrapper.scrollBy({
                    left: -300,
                    behavior: 'smooth',
                });
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                scrollWrapper.scrollBy({
                    left: 300,
                    behavior: 'smooth',
                });
            });
        }
    }
});
