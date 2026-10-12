/**
 * MW Showband — Motion Engine.
 *
 * Reusable, dependency-free classes used by the Fluid Glitch Slider's
 * cinematic choreography:
 *   - AnimationTimeline  — a cancellable sequence of timed steps
 *   - HeadingScramble    — brief character-resolve text reveal
 *   - ParallaxController — 3-layer lerped mouse parallax (rAF-driven)
 *   - MagneticButton     — cursor-attraction effect for CTAs
 *   - ParticleBurst      — small DOM-based spark burst on CTA interaction
 *   - WaveformController — decorative animated equalizer bars
 *
 * All classes are self-contained (no globals beyond `window.MWSB*`) so a
 * page with multiple slider instances gets one independent instance per
 * slider (see FEATURE 41 — multiple widget instances).
 *
 * Author: Aqib Dev
 */
( function ( global ) {
	'use strict';

	/* ------------------------------------------------------------------ *
	 * AnimationTimeline — central choreography clock for one transition.
	 * Steps are { delay, fn } pairs; all pending timers are cancelled if
	 * `.cancel()` is called (e.g. a new transition starts before the last
	 * one finished), preventing overlapping/duplicate animations.
	 * ------------------------------------------------------------------ */
	function AnimationTimeline() {
		this._timers = [];
	}

	AnimationTimeline.prototype.add = function ( delay, fn ) {
		var id = setTimeout( fn, delay );
		this._timers.push( id );
		return this;
	};

	AnimationTimeline.prototype.cancel = function () {
		this._timers.forEach( clearTimeout );
		this._timers = [];
	};

	/* ------------------------------------------------------------------ *
	 * HeadingScramble — resolves scrambled characters into the final text.
	 * ------------------------------------------------------------------ */
	function HeadingScramble( el ) {
		this.el = el;
		this.finalText = el.textContent;
		this._raf = null;
		this._chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
	}

	HeadingScramble.prototype.play = function ( durationMs ) {
		this.stop();
		durationMs = durationMs || 550;
		var self = this;
		var text = this.finalText;
		var start = performance.now();

		function frame( now ) {
			var progress = Math.min( ( now - start ) / durationMs, 1 );
			var resolveCount = Math.floor( progress * text.length );
			var out = '';
			for ( var i = 0; i < text.length; i++ ) {
				if ( text[ i ] === ' ' || i < resolveCount ) {
					out += text[ i ];
				} else {
					out += self._chars[ Math.floor( Math.random() * self._chars.length ) ];
				}
			}
			self.el.textContent = out;

			if ( progress < 1 ) {
				self._raf = requestAnimationFrame( frame );
			} else {
				self.el.textContent = text;
				self._raf = null;
			}
		}

		this._raf = requestAnimationFrame( frame );
	};

	HeadingScramble.prototype.stop = function () {
		if ( this._raf ) {
			cancelAnimationFrame( this._raf );
			this._raf = null;
		}
		this.el.textContent = this.finalText;
	};

	/* ------------------------------------------------------------------ *
	 * ParallaxController — three layers move at different, opposite
	 * strengths, smoothly interpolated (lerp) toward the mouse target
	 * rather than snapping directly to it. transform-only, rAF-driven.
	 * ------------------------------------------------------------------ */
	function ParallaxController( container, layers ) {
		// layers: [{ el, strength, invert }]
		this.container = container;
		this.layers = layers.filter( function ( l ) { return !! l.el; } );
		this.targetX = 0;
		this.targetY = 0;
		this.currentX = 0;
		this.currentY = 0;
		this._raf = null;
		this._running = false;
		this._enabled = true;

		this._onMove = this._onMove.bind( this );
		this._onLeave = this._onLeave.bind( this );
		this._tick = this._tick.bind( this );
	}

	ParallaxController.prototype._onMove = function ( e ) {
		if ( ! this._enabled ) {
			return;
		}
		var rect = this.container.getBoundingClientRect();
		this.targetX = ( ( e.clientX - rect.left ) / rect.width - 0.5 ) * 2;
		this.targetY = ( ( e.clientY - rect.top ) / rect.height - 0.5 ) * 2;
	};

	ParallaxController.prototype._onLeave = function () {
		this.targetX = 0;
		this.targetY = 0;
	};

	ParallaxController.prototype._tick = function () {
		if ( ! this._running ) {
			return;
		}
		this.currentX += ( this.targetX - this.currentX ) * 0.06;
		this.currentY += ( this.targetY - this.currentY ) * 0.06;

		this.layers.forEach( function ( layer ) {
			var dir = layer.invert ? -1 : 1;
			var x = this.currentX * layer.strength * dir;
			var y = this.currentY * layer.strength * dir;
			layer.el.style.transform = 'translate3d(' + x.toFixed( 2 ) + 'px,' + y.toFixed( 2 ) + 'px,0)';
		}, this );

		this._raf = requestAnimationFrame( this._tick );
	};

	ParallaxController.prototype.start = function () {
		if ( this._running ) {
			return;
		}
		this._running = true;
		this.container.addEventListener( 'mousemove', this._onMove );
		this.container.addEventListener( 'mouseleave', this._onLeave );
		this._raf = requestAnimationFrame( this._tick );
	};

	ParallaxController.prototype.stop = function () {
		this._running = false;
		if ( this._raf ) {
			cancelAnimationFrame( this._raf );
			this._raf = null;
		}
		this.container.removeEventListener( 'mousemove', this._onMove );
		this.container.removeEventListener( 'mouseleave', this._onLeave );
	};

	ParallaxController.prototype.setEnabled = function ( enabled ) {
		this._enabled = enabled;
		if ( ! enabled ) {
			this.targetX = 0;
			this.targetY = 0;
		}
	};

	/* ------------------------------------------------------------------ *
	 * MagneticButton — subtle cursor-attraction; disabled on touch.
	 * ------------------------------------------------------------------ */
	function MagneticButton( el, strength ) {
		this.el = el;
		this.strength = strength || 0.35;
		this._raf = null;
		this._targetX = 0;
		this._targetY = 0;
		this._curX = 0;
		this._curY = 0;
		this._active = false;

		this._onMove = this._onMove.bind( this );
		this._onLeave = this._onLeave.bind( this );
		this._tick = this._tick.bind( this );

		el.addEventListener( 'mousemove', this._onMove );
		el.addEventListener( 'mouseleave', this._onLeave );
	}

	MagneticButton.prototype._onMove = function ( e ) {
		var rect = this.el.getBoundingClientRect();
		var relX = e.clientX - ( rect.left + rect.width / 2 );
		var relY = e.clientY - ( rect.top + rect.height / 2 );
		this._targetX = relX * this.strength;
		this._targetY = relY * this.strength;
		if ( ! this._active ) {
			this._active = true;
			this._raf = requestAnimationFrame( this._tick );
		}
	};

	MagneticButton.prototype._onLeave = function () {
		this._targetX = 0;
		this._targetY = 0;
	};

	MagneticButton.prototype._tick = function () {
		this._curX += ( this._targetX - this._curX ) * 0.2;
		this._curY += ( this._targetY - this._curY ) * 0.2;
		this.el.style.transform = 'translate3d(' + this._curX.toFixed( 1 ) + 'px,' + this._curY.toFixed( 1 ) + 'px,0)';

		if ( Math.abs( this._targetX - this._curX ) < 0.1 && Math.abs( this._targetY - this._curY ) < 0.1 && this._targetX === 0 && this._targetY === 0 ) {
			this.el.style.transform = '';
			this._active = false;
			return;
		}
		this._raf = requestAnimationFrame( this._tick );
	};

	MagneticButton.prototype.destroy = function () {
		if ( this._raf ) {
			cancelAnimationFrame( this._raf );
		}
		this.el.removeEventListener( 'mousemove', this._onMove );
		this.el.removeEventListener( 'mouseleave', this._onLeave );
		this.el.style.transform = '';
	};

	/* ------------------------------------------------------------------ *
	 * ParticleBurst — a handful of small accent-coloured sparks that fade
	 * out quickly. DOM-based (cheap, no canvas needed for ~8 particles).
	 * ------------------------------------------------------------------ */
	function particleBurst( originEl, colors, count ) {
		var rect = originEl.getBoundingClientRect();
		var parent = originEl.closest( '.mwsb-fgs' ) || document.body;
		var parentRect = parent.getBoundingClientRect();
		var originX = rect.left + rect.width / 2 - parentRect.left;
		var originY = rect.top + rect.height / 2 - parentRect.top;

		count = count || 8;
		for ( var i = 0; i < count; i++ ) {
			var particle = document.createElement( 'span' );
			particle.className = 'mwsb-fgs__particle';
			particle.style.left = originX + 'px';
			particle.style.top = originY + 'px';
			particle.style.background = colors[ i % colors.length ];

			var angle = ( Math.PI * 2 * i ) / count + Math.random() * 0.5;
			var distance = 24 + Math.random() * 28;
			var dx = Math.cos( angle ) * distance;
			var dy = Math.sin( angle ) * distance;

			parent.appendChild( particle );

			/* eslint-disable no-loop-func */
			requestAnimationFrame( function ( p, tx, ty ) {
				return function () {
					p.style.transform = 'translate(' + tx + 'px,' + ty + 'px) scale(0.3)';
					p.style.opacity = '0';
				};
			}( particle, dx, dy ) );
			/* eslint-enable no-loop-func */

			( function ( p ) {
				setTimeout( function () {
					if ( p.parentNode ) {
						p.parentNode.removeChild( p );
					}
				}, 650 );
			}( particle ) );
		}
	}

	/* ------------------------------------------------------------------ *
	 * WaveformController — decorative animated equalizer bars. Purely
	 * cosmetic (not tied to a real audio source — never claims to be).
	 * ------------------------------------------------------------------ */
	function WaveformController( container, barCount ) {
		this.container = container;
		this.bars = [];
		this._raf = null;
		this._running = false;
		this._intensity = 0.4;

		barCount = barCount || 28;
		for ( var i = 0; i < barCount; i++ ) {
			var bar = document.createElement( 'span' );
			bar.className = 'mwsb-fgs__wave-bar';
			container.appendChild( bar );
			this.bars.push( { el: bar, phase: Math.random() * Math.PI * 2, speed: 0.02 + Math.random() * 0.03 } );
		}

		this._tick = this._tick.bind( this );
	}

	WaveformController.prototype.setIntensity = function ( intensity ) {
		this._intensity = intensity;
	};

	WaveformController.prototype._tick = function () {
		if ( ! this._running ) {
			return;
		}
		this.bars.forEach( function ( bar ) {
			bar.phase += bar.speed;
			var h = ( Math.sin( bar.phase ) * 0.5 + 0.5 ) * this._intensity;
			bar.el.style.transform = 'scaleY(' + Math.max( 0.08, h ).toFixed( 2 ) + ')';
		}, this );
		this._raf = requestAnimationFrame( this._tick );
	};

	WaveformController.prototype.start = function () {
		if ( this._running ) {
			return;
		}
		this._running = true;
		this._raf = requestAnimationFrame( this._tick );
	};

	WaveformController.prototype.stop = function () {
		this._running = false;
		if ( this._raf ) {
			cancelAnimationFrame( this._raf );
			this._raf = null;
		}
	};

	global.MWSBMotion = {
		AnimationTimeline: AnimationTimeline,
		HeadingScramble: HeadingScramble,
		ParallaxController: ParallaxController,
		MagneticButton: MagneticButton,
		particleBurst: particleBurst,
		WaveformController: WaveformController,
	};
} )( window );
