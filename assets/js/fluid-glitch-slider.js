/**
 * MW Showband — Fluid Glitch Slider — main orchestrator.
 *
 * Wires together (each provided by its own module — see FEATURE list):
 *   - Swiper.js                         slide engine
 *   - Typewriter (this file)            sub-heading character reveal
 *   - window.MWSBGlitchEngine           canvas glitch transition (4 profiles)
 *   - window.MWSBFluidSimulation        WebGL mouse-reactive fluid overlay
 *   - window.MWSBMotion.*               parallax, magnetic CTA, particles,
 *                                       waveform, heading scramble, timeline
 *
 * Choreography per slide change follows the project's master timeline:
 * glitch -> spotlight sweep -> image settle -> eyebrow -> heading scramble
 * -> heading settle -> typewriter -> CTA reveal -> ambient fluid/waveform.
 *
 * Multiple slider instances on one page are fully independent (state is
 * stored per-section via closures/dataset, never via bare `document.query`).
 *
 * Author: Aqib Dev
 */
( function () {
	'use strict';

	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	function prefersReducedMotion() {
		return window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	}

	function isCoarsePointer() {
		return window.matchMedia && window.matchMedia( '(pointer: coarse)' ).matches;
	}

	function hexToRgb01( hex ) {
		hex = ( hex || '#7B2CFF' ).replace( '#', '' );
		if ( hex.length === 3 ) {
			hex = hex[ 0 ] + hex[ 0 ] + hex[ 1 ] + hex[ 1 ] + hex[ 2 ] + hex[ 2 ];
		}
		var num = parseInt( hex, 16 );
		return [ ( ( num >> 16 ) & 255 ) / 255, ( ( num >> 8 ) & 255 ) / 255, ( num & 255 ) / 255 ];
	}

	/* ------------------------------------------------------------------ *
	 * Typewriter — character-by-character reveal, cancellable/restartable.
	 * ------------------------------------------------------------------ */
	function Typewriter( el, text, speedMs ) {
		this.el = el;
		this.text = text || '';
		this.speed = speedMs || 40;
		this._timer = null;
		this._index = 0;
	}

	Typewriter.prototype.start = function ( onDone ) {
		this.reset();
		this.el.classList.add( 'is-typing' );
		var self = this;

		function tick() {
			self._index++;
			self.el.textContent = self.text.slice( 0, self._index );
			if ( self._index < self.text.length ) {
				self._timer = setTimeout( tick, self.speed );
			} else {
				self.el.classList.remove( 'is-typing' );
				if ( typeof onDone === 'function' ) {
					onDone();
				}
			}
		}

		this._timer = setTimeout( tick, this.speed );
	};

	Typewriter.prototype.reset = function () {
		if ( this._timer ) {
			clearTimeout( this._timer );
			this._timer = null;
		}
		this._index = 0;
		this.el.textContent = '';
		this.el.classList.remove( 'is-typing' );
	};

	/* ------------------------------------------------------------------ *
	 * Main widget wiring
	 * ------------------------------------------------------------------ */
	function initSlider( section ) {
		if ( section.dataset.mwsbFgsInit || 'undefined' === typeof Swiper ) {
			return;
		}
		section.dataset.mwsbFgsInit = '1';

		var reducedMotion = prefersReducedMotion();
		var coarsePointer = isCoarsePointer();
		var Motion = window.MWSBMotion || {};

		var swiperEl = section.querySelector( '.mwsb-fgs__swiper' );
		var thumbs = section.querySelectorAll( '.mwsb-fgs__thumb' );
		var prevBtn = section.querySelector( '.mwsb-fgs__arrow--prev' );
		var nextBtn = section.querySelector( '.mwsb-fgs__arrow--next' );
		var progressFill = section.querySelector( '.mwsb-fgs__progress-fill' );
		var glitchCanvas = section.querySelector( '.mwsb-fgs__glitch-canvas' );
		var fluidCanvas = section.querySelector( '.mwsb-fgs__fluid-canvas' );
		var atmosphereEl = section.querySelector( '.mwsb-fgs__atmosphere' );
		var spotlightsEl = section.querySelector( '.mwsb-fgs__spotlights' );

		var cfg = {
			autoplay: '1' === section.dataset.autoplay,
			autoplayDelay: parseInt( section.dataset.autoplayDelay, 10 ) || 6000,
			pauseOnHover: '1' === section.dataset.pauseOnHover,
			typingSpeed: parseInt( section.dataset.typingSpeed, 10 ) || 40,
			enableGlitch: '1' === section.dataset.enableGlitch && ! reducedMotion,
			enableFluid: '1' === section.dataset.enableFluid && ! reducedMotion &&
				! ( '1' === section.dataset.disableFluidMobile && coarsePointer ),
			enableParallax: '1' === section.dataset.enableParallax && ! reducedMotion && ! coarsePointer,
			enableSpotlight: '1' === section.dataset.enableSpotlight && ! reducedMotion,
			enableWaveform: '1' === section.dataset.enableWaveform,
			enableParticles: '1' === section.dataset.enableParticles && ! reducedMotion && ! coarsePointer,
			enableMagnetic: '1' === section.dataset.enableMagnetic && ! reducedMotion && ! coarsePointer,
			enableScramble: '1' === section.dataset.enableScramble && ! reducedMotion,
		};

		var glitchEngine = ( cfg.enableGlitch && glitchCanvas && window.MWSBGlitchEngine ) ? new window.MWSBGlitchEngine( glitchCanvas ) : null;
		var typewriters = new WeakMap();
		var scrambles = new WeakMap();
		var waveforms = new WeakMap();
		var magneticButtons = [];

		var CIRCUMFERENCE = 2 * Math.PI * 20;
		if ( progressFill ) {
			progressFill.style.strokeDasharray = String( CIRCUMFERENCE );
			progressFill.style.strokeDashoffset = String( CIRCUMFERENCE );
			progressFill.parentElement.style.display = cfg.autoplay ? '' : 'none';
		}

		/* ---------------- per-slide helpers ---------------- */
		function getTypewriter( slideEl ) {
			var textEl = slideEl.querySelector( '.mwsb-fgs__typewriter-text' );
			if ( ! textEl ) {
				return null;
			}
			if ( ! typewriters.has( textEl ) ) {
				typewriters.set( textEl, new Typewriter( textEl, textEl.dataset.fullText || '', cfg.typingSpeed ) );
			}
			return typewriters.get( textEl );
		}

		function getScramble( slideEl ) {
			if ( ! Motion.HeadingScramble ) {
				return null;
			}
			var headingEl = slideEl.querySelector( '.mwsb-fgs__heading' );
			if ( ! headingEl ) {
				return null;
			}
			if ( ! scrambles.has( headingEl ) ) {
				scrambles.set( headingEl, new Motion.HeadingScramble( headingEl ) );
			}
			return scrambles.get( headingEl );
		}

		function getWaveform( slideEl ) {
			if ( ! Motion.WaveformController ) {
				return null;
			}
			var container = slideEl.querySelector( '.mwsb-fgs__waveform' );
			if ( ! container ) {
				return null;
			}
			if ( ! waveforms.has( container ) ) {
				waveforms.set( container, new Motion.WaveformController( container, 28 ) );
			}
			return waveforms.get( container );
		}

		/**
		 * Runs the full choreography for a slide becoming active:
		 * eyebrow -> heading scramble -> heading settle -> typewriter ->
		 * CTA reveal -> ambient waveform. Timings follow the master timeline
		 * (compressed slightly since the glitch transition already covers
		 * the first ~500ms).
		 */
		function playSlide( slideEl ) {
			slideEl.classList.add( 'mwsb-fgs__is-entering' );
			slideEl.classList.remove( 'mwsb-fgs__typing-done' );

			var timeline = new ( Motion.AnimationTimeline || function () {
				this.add = function ( d, fn ) { setTimeout( fn, d ); return this; };
				this.cancel = function () {};
			} )();
			slideEl._mwsbTimeline = timeline;

			timeline.add( 0, function () {
				slideEl.classList.add( 'mwsb-fgs__eyebrow-in' );
			} );

			timeline.add( 80, function () {
				var scramble = getScramble( slideEl );
				if ( scramble && cfg.enableScramble ) {
					scramble.play( 550 );
				}
				slideEl.classList.add( 'mwsb-fgs__heading-in' );
			} );

			timeline.add( cfg.enableScramble ? 700 : 300, function () {
				var tw = getTypewriter( slideEl );
				if ( tw ) {
					tw.start( function () {
						slideEl.classList.add( 'mwsb-fgs__typing-done' );
					} );
				} else {
					slideEl.classList.add( 'mwsb-fgs__typing-done' );
				}
			} );

			timeline.add( 900, function () {
				var wf = getWaveform( slideEl );
				if ( wf && cfg.enableWaveform ) {
					wf.setIntensity( 0.5 );
					wf.start();
				}
			} );
		}

		function resetSlide( slideEl ) {
			if ( slideEl._mwsbTimeline ) {
				slideEl._mwsbTimeline.cancel();
			}
			var tw = getTypewriter( slideEl );
			if ( tw ) {
				tw.reset();
			}
			var scramble = getScramble( slideEl );
			if ( scramble ) {
				scramble.stop();
			}
			var wf = getWaveform( slideEl );
			if ( wf ) {
				wf.stop();
			}
			slideEl.classList.remove( 'mwsb-fgs__typing-done', 'mwsb-fgs__is-entering', 'mwsb-fgs__eyebrow-in', 'mwsb-fgs__heading-in' );
		}

		function syncThumbs( swiperInstance ) {
			var realIndex = swiperInstance.realIndex;
			thumbs.forEach( function ( thumb, i ) {
				thumb.classList.toggle( 'is-active', i === realIndex );
			} );
		}

		function triggerSpotlightSweep() {
			if ( ! cfg.enableSpotlight || ! spotlightsEl ) {
				return;
			}
			spotlightsEl.classList.remove( 'is-sweeping' );
			// Force reflow so the animation can restart.
			void spotlightsEl.offsetWidth;
			spotlightsEl.classList.add( 'is-sweeping' );
		}

		function applyAccentToSlide( slideEl ) {
			var accent = slideEl.dataset.accentColor;
			if ( accent ) {
				section.style.setProperty( '--mwsb-fgs-slide-accent', accent );
			}
		}

		function triggerGlitch( slideEl ) {
			if ( ! glitchEngine ) {
				return;
			}
			var bgEl = slideEl.querySelector( '.mwsb-fgs__bg' );
			var bgImage = bgEl ? bgEl.style.backgroundImage : '';
			var match = bgImage.match( /url\(["']?(.*?)["']?\)/ );
			var url = match ? match[ 1 ] : '';
			glitchEngine.play( url, 520, slideEl.dataset.accentColor );
		}

		/* ---------------- Swiper ---------------- */
		var swiperConfig = {
			loop: true,
			speed: 700,
			effect: 'fade',
			fadeEffect: { crossFade: true },
			on: {
				slideChangeTransitionStart: function ( swiper ) {
					var activeSlide = swiper.slides[ swiper.activeIndex ];
					if ( activeSlide ) {
						resetSlide( activeSlide );
						applyAccentToSlide( activeSlide );
						triggerGlitch( activeSlide );
						triggerSpotlightSweep();
					}
				},
				slideChangeTransitionEnd: function ( swiper ) {
					var activeSlide = swiper.slides[ swiper.activeIndex ];
					if ( activeSlide ) {
						playSlide( activeSlide );
					}
					syncThumbs( swiper );
				},
				init: function ( swiper ) {
					var activeSlide = swiper.slides[ swiper.activeIndex ];
					if ( activeSlide ) {
						applyAccentToSlide( activeSlide );
						playSlide( activeSlide );
					}
					syncThumbs( swiper );
				},
				autoplayTimeLeft: function ( swiper, timeLeft, progress ) {
					if ( progressFill ) {
						progressFill.style.strokeDashoffset = String( CIRCUMFERENCE * progress );
					}
				},
			},
		};

		if ( cfg.autoplay ) {
			swiperConfig.autoplay = {
				delay: cfg.autoplayDelay,
				disableOnInteraction: false,
				pauseOnMouseEnter: cfg.pauseOnHover,
			};
		}

		var swiper = new Swiper( swiperEl, swiperConfig );

		thumbs.forEach( function ( thumb ) {
			thumb.addEventListener( 'click', function () {
				swiper.slideToLoop( parseInt( thumb.dataset.slideIndex, 10 ) || 0 );
			} );
		} );
		if ( prevBtn ) {
			prevBtn.addEventListener( 'click', function () { swiper.slidePrev(); } );
		}
		if ( nextBtn ) {
			nextBtn.addEventListener( 'click', function () { swiper.slideNext(); } );
		}

		// Pause everything expensive when the browser tab is hidden.
		document.addEventListener( 'visibilitychange', function () {
			if ( ! swiper.autoplay ) {
				return;
			}
			if ( document.hidden ) {
				swiper.autoplay.stop();
			} else if ( cfg.autoplay ) {
				swiper.autoplay.start();
			}
		} );

		/* ---------------- Parallax (3 layers) ---------------- */
		if ( cfg.enableParallax && Motion.ParallaxController ) {
			var parallax = new Motion.ParallaxController( section, [
				{ el: section.querySelector( '.mwsb-fgs__swiper' ), strength: 6, invert: false },
				{ el: atmosphereEl, strength: 14, invert: false },
				{ el: section.querySelector( '.mwsb-fgs__content' ), strength: 6, invert: true },
			] );
			parallax.start();
		}

		/* ---------------- Magnetic CTAs + particle burst ---------------- */
		section.querySelectorAll( '.mwsb-fgs__btn' ).forEach( function ( btn ) {
			if ( cfg.enableMagnetic && Motion.MagneticButton ) {
				magneticButtons.push( new Motion.MagneticButton( btn, 0.3 ) );
			}
			if ( cfg.enableParticles && Motion.particleBurst ) {
				btn.addEventListener( 'click', function () {
					var slideEl = btn.closest( '.mwsb-fgs__slide' );
					var colors = slideEl ? [
						slideEl.dataset.fluidColorA, slideEl.dataset.fluidColorB, slideEl.dataset.fluidColorC,
					].filter( Boolean ) : [ '#7B2CFF', '#D946EF', '#00F0FF' ];
					Motion.particleBurst( btn, colors, 8 );
				} );
			}
		} );

		/* ---------------- WebGL fluid overlay ---------------- */
		if ( cfg.enableFluid && fluidCanvas && 'undefined' !== typeof window.MWSBFluidSimulation ) {
			var sim = new window.MWSBFluidSimulation( fluidCanvas, {
				simResolution: 128,
				dyeResolution: coarsePointer ? 256 : 512,
				densityDissipation: 0.98,
				velocityDissipation: 0.99,
				pressureIterations: 20,
				curl: 30,
				splatRadius: 0.25,
			} );

			if ( sim.isSupported ) {
				sim.resize();
				window.addEventListener( 'resize', function () { sim.resize(); } );

				var lastX = null;
				var lastY = null;
				var idleTimer = null;
				var isIdle = false;

				function markActive() {
					isIdle = false;
					if ( idleTimer ) {
						clearTimeout( idleTimer );
					}
					idleTimer = setTimeout( function () { isIdle = true; }, 1500 );
				}

				section.addEventListener( 'mousemove', function ( e ) {
					markActive();
					var rect = section.getBoundingClientRect();
					var x = ( e.clientX - rect.left ) / rect.width;
					var y = 1.0 - ( e.clientY - rect.top ) / rect.height;

					if ( lastX === null ) {
						lastX = x;
						lastY = y;
						return;
					}

					var dx = ( x - lastX ) * 6;
					var dy = ( y - lastY ) * 6;
					lastX = x;
					lastY = y;

					var activeSlide = swiper.slides[ swiper.activeIndex ];
					var palette = [ '#7B2CFF', '#D946EF', '#00F0FF' ];
					if ( activeSlide ) {
						palette = [
							activeSlide.dataset.fluidColorA || palette[ 0 ],
							activeSlide.dataset.fluidColorB || palette[ 1 ],
							activeSlide.dataset.fluidColorC || palette[ 2 ],
						];
					}
					var color = hexToRgb01( palette[ Math.floor( Math.random() * palette.length ) ] );
					color = [ color[ 0 ] * 0.3, color[ 1 ] * 0.3, color[ 2 ] * 0.3 ];
					sim.splat( x, y, dx, dy, color );
				} );

				section.addEventListener( 'mouseleave', function () {
					lastX = null;
					lastY = null;
				} );

				// Idle ambient mode: very occasional soft splat near centre.
				setInterval( function () {
					if ( ! isIdle || document.hidden ) {
						return;
					}
					var activeSlide = swiper.slides[ swiper.activeIndex ];
					var palette = activeSlide ? [
						activeSlide.dataset.fluidColorA, activeSlide.dataset.fluidColorB, activeSlide.dataset.fluidColorC,
					].filter( Boolean ) : [ '#7B2CFF' ];
					var color = hexToRgb01( palette[ Math.floor( Math.random() * palette.length ) ] );
					color = [ color[ 0 ] * 0.08, color[ 1 ] * 0.08, color[ 2 ] * 0.08 ];
					var x = 0.4 + Math.random() * 0.2;
					var y = 0.4 + Math.random() * 0.2;
					sim.splat( x, y, ( Math.random() - 0.5 ) * 0.5, ( Math.random() - 0.5 ) * 0.5, color );
				}, 2200 );

				if ( 'IntersectionObserver' in window ) {
					var observer = new IntersectionObserver( function ( entries ) {
						entries.forEach( function ( entry ) {
							if ( entry.isIntersecting && ! document.hidden ) {
								sim.start();
							} else {
								sim.stop();
							}
						} );
					}, { threshold: 0.05 } );
					observer.observe( section );

					document.addEventListener( 'visibilitychange', function () {
						if ( document.hidden ) {
							sim.stop();
						} else {
							sim.start();
						}
					} );
				} else {
					sim.start();
				}
			}
		}
	}

	function initAll( root ) {
		root.querySelectorAll( '[data-mwsb-fluid-glitch-slider]' ).forEach( function ( section ) {
			try {
				initSlider( section );
			} catch ( err ) {
				if ( window.console && console.error ) {
					console.error( 'MW Fluid Glitch Slider init error:', err );
				}
			}
		} );
	}

	ready( function () {
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
