document.addEventListener('DOMContentLoaded', () => {

    console.log('Custom Slider JS Loaded');


	const hero = document.querySelector('.js-hero');
	const swiperModules = hero?.querySelectorAll('.js-hero-slider');
	const pagination = hero?.querySelector('.swiper-pagination');

	if (hero) {
		if (hero.offsetHeight > 0) {
			document.body.classList.add('has-hero');
		}
	}


	if( swiperModules ) {

		[].forEach.call(swiperModules, (swipeModule) => {

			const swiper = new Swiper( swipeModule, {
				pagination: {
					el				   : pagination,
					type			   : 'bullets',
					bulletClass        : 'swipe-module__bullet',
					bulletActiveClass  : 'is-bullet-active',
				},
				effect					: 'slide',
				slidesPerView			: 1,
				loop					: true,
				autoplay				: {
					delay				: 5000,
					disableOnInteraction: false,
				},

				// on : {
				// 	'slideChange' : function () {
				// 		let heroContentItems = hero.querySelectorAll('.js-hero-content');

				// 		heroContentItems.forEach(contentItem => {
				// 			contentItem.classList.remove('is-active');

				// 		});

				// 		heroContentItems[this.realIndex].classList.add('is-active');
				// 	}
				// }
			});
		});
	}
});
