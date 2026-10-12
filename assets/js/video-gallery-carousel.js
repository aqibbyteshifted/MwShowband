( function () {
	'use strict';

	function init() {
		var containers = document.querySelectorAll( '.mwsb-videos__carousel' );
		if ( ! containers.length || typeof Swiper === 'undefined' ) {
			return;
		}

		containers.forEach( function ( el ) {
			if ( el.dataset.mwsbInit ) {
				return;
			}
			el.dataset.mwsbInit = '1';

			var wrapper = el.parentNode;
			var prev    = wrapper.querySelector( '.mwsb-videos__nav--prev' );
			var next    = wrapper.querySelector( '.mwsb-videos__nav--next' );

			new Swiper( el, {
				slidesPerView: 1.15,
				spaceBetween: 20,
				navigation: { prevEl: prev, nextEl: next },
				breakpoints: {
					560: { slidesPerView: 1.6 },
					900: { slidesPerView: 2.4 },
					1200: { slidesPerView: 3.2 },
				},
			} );
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}

	// Elementor's editor re-renders a widget's markup on every control change, which drops the
	// Swiper instance; re-run init (guarded by data-mwsb-init) so the arrows keep working there too.
	window.addEventListener( 'elementor/frontend/init', function () {
		if ( window.elementorFrontend && elementorFrontend.hooks ) {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/mwsb-video-gallery.default', init );
		}
	} );
} )();
