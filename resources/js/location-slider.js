import Swiper from 'swiper';
import { A11y, Keyboard, Navigation, Pagination } from 'swiper/modules';

import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

export function initLocationSlider() {
    const root = document.querySelector('.location-swiper');
    if (!root) {
        return;
    }

    const slides = root.querySelectorAll('.swiper-slide');
    if (slides.length === 0) {
        return;
    }

    new Swiper(root, {
        modules: [Navigation, Pagination, Keyboard, A11y],
        slidesPerView: 1,
        spaceBetween: 0,
        speed: 550,
        loop: slides.length > 2,
        grabCursor: true,
        keyboard: {
            enabled: true,
            onlyInViewport: true,
        },
        a11y: {
            prevSlideMessage: 'Предыдущее фото',
            nextSlideMessage: 'Следующее фото',
        },
        pagination: {
            el: root.querySelector('.location-swiper-pagination'),
            clickable: true,
            dynamicBullets: slides.length > 4,
        },
        navigation: {
            nextEl: root.querySelector('.location-swiper-button-next'),
            prevEl: root.querySelector('.location-swiper-button-prev'),
        },
        watchOverflow: true,
    });
}
