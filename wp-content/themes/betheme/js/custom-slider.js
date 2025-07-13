document.addEventListener('DOMContentLoaded', () => {

	const hero = document.querySelector('.js-hero');
	const swiperModules = hero?.querySelectorAll('.js-hero-slider');

	if (hero) {
		if (hero.offsetHeight > 0) {
			document.body.classList.add('has-hero');
		}
	}

	if( swiperModules ) {

		[].forEach.call(swiperModules, (swipeModule) => {

			const swiper = new Swiper( swipeModule, {
				navigation: {
					nextEl: hero.querySelector('.swiper-button-next'),
					prevEl: hero.querySelector('.swiper-button-prev'),
				},
				effect					: 'slide',
				slidesPerView			: 1,
				loop					: true,
				autoplay				: {
					delay				: 8000,
					disableOnInteraction: false,
				}
			});
		});
	}
});
