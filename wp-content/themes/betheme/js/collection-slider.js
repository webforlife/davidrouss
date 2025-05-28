document.addEventListener('DOMContentLoaded', () => {

    const swipers = document.querySelectorAll('.js-collection-slider');

    if( swipers ) {

        swipers.forEach((swiper) => {

            const prevButton = swiper.querySelector('.swiper-button-prev') ?? false;
            const nextButton = swiper.querySelector('.swiper-button-next') ?? false;

            const swiperInstance = new Swiper(swiper, {
                navigation             : {
                    nextEl             : nextButton,
                    prevEl             : prevButton,
                },
                spaceBetween		   : 20,
                slidesPerView		   : 1,
                watchOverflow          : true
            });
        });
    }

});