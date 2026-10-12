/**
 * MW Showband — shared widget interactivity (event delegation, works in both
 * the live frontend and the Elementor editor preview).
 * Author: Aqib Dev
 */
( function () {
	'use strict';

	function onReady( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	function initTimeline( section ) {
		if ( section.dataset.mwsbTimelineInit ) {
			return;
		}
		var card = section.querySelector( '[data-mwsb-timeline-card]' );
		if ( ! card ) {
			return;
		}
		section.dataset.mwsbTimelineInit = '1';

		var tabs = section.querySelectorAll( '.mwsb-timeline__tab' );

		tabs.forEach( function ( tab ) {
			tab.addEventListener( 'click', function () {
				tabs.forEach( function ( t ) {
					t.classList.remove( 'is-active' );
				} );
				tab.classList.add( 'is-active' );

				card.querySelector( '[data-field="title"]' ).textContent = tab.dataset.title || '';
				card.querySelector( '[data-field="desc"]' ).textContent = tab.dataset.desc || '';
				card.querySelector( '[data-field="ensemble"]' ).textContent = tab.dataset.ensemble || '';
				card.querySelector( '[data-field="bpm"]' ).textContent = tab.dataset.bpm || '';
				card.querySelector( '[data-field="energy"]' ).textContent = tab.dataset.energy || '';

				var intensityEl = section.querySelector( '.mwsb-timeline__intensity span' );
				if ( intensityEl ) {
					intensityEl.textContent = tab.dataset.intensity || '01';
				}

				var img = card.querySelector( '[data-field="image"]' );
				if ( img && tab.dataset.image ) {
					img.src = tab.dataset.image;
					img.style.display = '';
				}
			} );
		} );
	}

	function initHeroSlider( section ) {
		if ( section.dataset.mwsbHeroInit ) {
			return;
		}
		section.dataset.mwsbHeroInit = '1';

		var slides = Array.prototype.slice.call( section.querySelectorAll( '.mwsb-hero-slide' ) );
		if ( slides.length < 2 ) {
			return;
		}

		var dots = Array.prototype.slice.call( section.querySelectorAll( '[data-mwsb-hero-dot]' ) );
		var prevBtn = section.querySelector( '[data-mwsb-hero-prev]' );
		var nextBtn = section.querySelector( '[data-mwsb-hero-next]' );
		var current = 0;
		var timer = null;
		var autoplay = '1' === section.dataset.autoplay;
		var speed = parseInt( section.dataset.autoplaySpeed, 10 ) || 6000;

		function goTo( index ) {
			slides[ current ].classList.remove( 'is-active' );
			if ( dots[ current ] ) {
				dots[ current ].classList.remove( 'is-active' );
			}
			current = ( index + slides.length ) % slides.length;
			slides[ current ].classList.add( 'is-active' );
			if ( dots[ current ] ) {
				dots[ current ].classList.add( 'is-active' );
			}
		}

		function next() {
			goTo( current + 1 );
		}

		function prev() {
			goTo( current - 1 );
		}

		function startAutoplay() {
			if ( ! autoplay ) {
				return;
			}
			stopAutoplay();
			timer = setInterval( next, speed );
		}

		function stopAutoplay() {
			if ( timer ) {
				clearInterval( timer );
				timer = null;
			}
		}

		function restartAutoplay() {
			stopAutoplay();
			startAutoplay();
		}

		if ( nextBtn ) {
			nextBtn.addEventListener( 'click', function () {
				next();
				restartAutoplay();
			} );
		}
		if ( prevBtn ) {
			prevBtn.addEventListener( 'click', function () {
				prev();
				restartAutoplay();
			} );
		}
		dots.forEach( function ( dot, i ) {
			dot.addEventListener( 'click', function () {
				goTo( i );
				restartAutoplay();
			} );
		} );

		section.addEventListener( 'mouseenter', stopAutoplay );
		section.addEventListener( 'mouseleave', startAutoplay );

		startAutoplay();
	}

	function initContactForm( form ) {
		if ( form.dataset.mwsbInit ) {
			return;
		}
		form.dataset.mwsbInit = '1';

		var submitBtn = form.querySelector( '.mwsb-contact-form__submit' );
		var notice = form.querySelector( '[data-mwsb-contact-notice]' );
		var ajaxUrl = ( window.mwsbData && window.mwsbData.ajaxUrl ) ? window.mwsbData.ajaxUrl : '/wp-admin/admin-ajax.php';

		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();

			if ( submitBtn ) {
				submitBtn.disabled = true;
				submitBtn.textContent = form.dataset.sendingText || 'SENDING…';
			}
			if ( notice ) {
				notice.className = 'mwsb-contact-form__notice';
				notice.textContent = '';
			}

			var formData = new FormData( form );

			fetch( ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				body: formData
			} )
				.then( function ( response ) {
					return response.json();
				} )
				.then( function ( data ) {
					if ( notice ) {
						notice.textContent = ( data.data && data.data.message ) ? data.data.message : '';
						notice.className = 'mwsb-contact-form__notice ' + ( data.success ? 'is-success' : 'is-error' );
					}
					if ( data.success ) {
						form.reset();
						// Send the visitor to the Thank You page (or custom URL) once the enquiry is
						// really sent. Skipped inside the Elementor editor preview so editing the form
						// never navigates away from the editor.
						if ( form.dataset.redirect && ! document.body.classList.contains( 'elementor-editor-active' ) ) {
							window.location.assign( form.dataset.redirect );
						}
					}
				} )
				.catch( function () {
					if ( notice ) {
						notice.textContent = 'Something went wrong. Please try again.';
						notice.className = 'mwsb-contact-form__notice is-error';
					}
				} )
				.finally( function () {
					if ( submitBtn ) {
						submitBtn.disabled = false;
						submitBtn.textContent = form.dataset.submitText || 'SEND ENQUIRY';
					}
				} );
		} );
	}

	function initContactFormTabs( container ) {
		if ( container.dataset.mwsbTabsInit ) {
			return;
		}
		container.dataset.mwsbTabsInit = '1';

		var tabs = container.querySelectorAll( '.mwsb-contact-form__tab' );
		var inputs = container.querySelectorAll( '.mwsb-contact-form__tab-input' );

		inputs.forEach( function ( input ) {
			input.addEventListener( 'change', function () {
				tabs.forEach( function ( t ) {
					t.classList.remove( 'is-active' );
				} );
				input.closest( '.mwsb-contact-form__tab' ).classList.add( 'is-active' );
			} );
		} );
	}

	function initHeaderMenu( header ) {
		if ( ! header.dataset.mwsbScrollInit ) {
			header.dataset.mwsbScrollInit = '1';
			var toggleScrolled = function () {
				header.classList.toggle( 'is-scrolled', window.scrollY > 10 );
			};
			window.addEventListener( 'scroll', toggleScrolled, { passive: true } );
			toggleScrolled();
		}

		var toggle = header.querySelector( '[data-mwsb-header-toggle]' );
		var panel = header.querySelector( '[data-mwsb-header-panel]' );
		if ( ! toggle || ! panel || toggle.dataset.mwsbInit ) {
			return;
		}
		toggle.dataset.mwsbInit = '1';

		toggle.addEventListener( 'click', function () {
			var isOpen = panel.classList.toggle( 'is-open' );
			toggle.classList.toggle( 'is-active', isOpen );
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		} );

		panel.querySelectorAll( 'a' ).forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				panel.classList.remove( 'is-open' );
				toggle.classList.remove( 'is-active' );
				toggle.setAttribute( 'aria-expanded', 'false' );
			} );
		} );
	}

	function initFooter( footer ) {
		var backToTop = footer.querySelector( '[data-mwsb-back-to-top]' );
		if ( backToTop && ! backToTop.dataset.mwsbInit ) {
			backToTop.dataset.mwsbInit = '1';
			backToTop.addEventListener( 'click', function () {
				window.scrollTo( { top: 0, behavior: 'smooth' } );
			} );
		}
	}

	function initArchetypes( section ) {
		if ( section.dataset.mwsbArchetypeInit ) {
			return;
		}
		section.dataset.mwsbArchetypeInit = '1';

		var cards = section.querySelectorAll( '[data-mwsb-archetype]' );
		cards.forEach( function ( card ) {
			card.addEventListener( 'click', function () {
				cards.forEach( function ( c ) {
					c.classList.remove( 'is-active' );
				} );
				card.classList.add( 'is-active' );
			} );
		} );
	}

	function initFaq( section ) {
		if ( section.dataset.mwsbFaqInit ) {
			return;
		}
		section.dataset.mwsbFaqInit = '1';

		var toggles = section.querySelectorAll( '[data-mwsb-faq-toggle]' );
		toggles.forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				var item = button.closest( '.mwsb-faq__item' );
				var answer = item.querySelector( '.mwsb-faq__answer' );
				var isOpen = item.classList.contains( 'is-open' );

				item.parentElement.querySelectorAll( '.mwsb-faq__item.is-open' ).forEach( function ( openItem ) {
					if ( openItem !== item ) {
						openItem.classList.remove( 'is-open' );
						openItem.querySelector( '.mwsb-faq__answer' ).style.maxHeight = null;
					}
				} );

				if ( isOpen ) {
					item.classList.remove( 'is-open' );
					answer.style.maxHeight = null;
				} else {
					item.classList.add( 'is-open' );
					answer.style.maxHeight = answer.scrollHeight + 'px';
				}
			} );
		} );
	}

	/**
	 * Song List: each track row with an uploaded audio clip gets a real play/pause
	 * toggle. Starting one clip pauses whichever other clip (in this list, or any
	 * other Song List widget on the page) was playing, so only one plays at a time.
	 */
	function initTrackList( section ) {
		if ( section.dataset.mwsbTracklistInit ) {
			return;
		}
		section.dataset.mwsbTracklistInit = '1';

		var tracks = section.querySelectorAll( '.mwsb-track' );
		tracks.forEach( function ( track ) {
			var audio = track.querySelector( '.mwsb-track__audio' );
			var button = track.querySelector( '.mwsb-track__play' );
			if ( ! audio || ! button ) {
				return;
			}

			button.addEventListener( 'click', function () {
				var alreadyPlaying = ! audio.paused;

				document.querySelectorAll( '.mwsb-track__audio' ).forEach( function ( other ) {
					if ( other !== audio && ! other.paused ) {
						other.pause();
						other.currentTime = 0;
						var otherTrack = other.closest( '.mwsb-track' );
						if ( otherTrack ) {
							otherTrack.classList.remove( 'is-playing' );
							otherTrack.querySelector( '.mwsb-track__play' ).innerHTML = '&#9658;';
						}
					}
				} );

				if ( alreadyPlaying ) {
					audio.pause();
					track.classList.remove( 'is-playing' );
					button.innerHTML = '&#9658;';
				} else {
					audio.play().catch( function () { /* autoplay/user-gesture restrictions: ignore */ } );
					track.classList.add( 'is-playing' );
					button.innerHTML = '&#10074;&#10074;';
				}
			} );

			audio.addEventListener( 'ended', function () {
				track.classList.remove( 'is-playing' );
				button.innerHTML = '&#9658;';
			} );
		} );
	}

	function safeEach( elements, fn ) {
		elements.forEach( function ( el ) {
			try {
				fn( el );
			} catch ( err ) {
				if ( window.console && console.error ) {
					console.error( 'MW Showband widget init error:', err );
				}
			}
		} );
	}

	function initAll( root ) {
		safeEach( root.querySelectorAll( '[data-mwsb-hero-slider]' ), initHeroSlider );
		safeEach( root.querySelectorAll( '.mwsb-timeline' ), initTimeline );
		safeEach( root.querySelectorAll( '.mwsb-planner' ), initArchetypes );
		safeEach( root.querySelectorAll( '.mwsb-faq' ), initFaq );
		safeEach( root.querySelectorAll( '.mwsb-songlist' ), initTrackList );
		safeEach( root.querySelectorAll( '[data-mwsb-contact-form]' ), initContactForm );
		safeEach( root.querySelectorAll( '[data-mwsb-contact-tabs]' ), initContactFormTabs );
		safeEach( root.querySelectorAll( '.mwsb-header' ), initHeaderMenu );
		safeEach( root.querySelectorAll( '.mwsb-footer' ), initFooter );
	}

	onReady( function () {
		initAll( document );
	} );

	if ( window.elementorFrontend ) {
		window.addEventListener( 'elementor/frontend/init', function () {
			elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function ( $scope ) {
				initAll( $scope[ 0 ] );
			} );
		} );
	}
} )();
