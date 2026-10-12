/**
 * MW Showband — Glitch Engine.
 *
 * Canvas2D transition effects played briefly between slides. Four distinct
 * "profiles" (per FEATURE 27) are rotated/weighted so consecutive
 * transitions don't look identical, while staying visually coherent with
 * the brand (controlled randomness, not chaotic noise).
 *
 * Profiles:
 *   A — RGB glitch + sliced horizontal displacement
 *   B — Directional wipe distortion + brightness flash
 *   C — Chromatic wave + soft zoom punch
 *   D — Signal-scan noise + vertical displacement
 *
 * Author: Aqib Dev
 */
( function ( global ) {
	'use strict';

	function GlitchEngine( canvas ) {
		this.canvas = canvas;
		this.ctx = canvas.getContext( '2d' );
		this._imgCache = {};
		this._noisePattern = null;
		this._lastProfile = -1;
	}

	GlitchEngine.PROFILES = [ 'rgb-slice', 'wipe-flash', 'chromatic-wave', 'signal-scan' ];

	GlitchEngine.prototype._loadImage = function ( url, cb ) {
		if ( ! url ) {
			cb( null );
			return;
		}
		if ( this._imgCache[ url ] ) {
			cb( this._imgCache[ url ] );
			return;
		}
		var img = new Image();
		img.crossOrigin = 'anonymous';
		var self = this;
		img.onload = function () { self._imgCache[ url ] = img; cb( img ); };
		img.onerror = function () { cb( null ); };
		img.src = url;
	};

	GlitchEngine.prototype._makeNoisePattern = function () {
		var size = 64;
		var off = document.createElement( 'canvas' );
		off.width = size;
		off.height = size;
		var octx = off.getContext( '2d' );
		var imageData = octx.createImageData( size, size );
		for ( var i = 0; i < imageData.data.length; i += 4 ) {
			var v = Math.random() * 255;
			imageData.data[ i ] = v;
			imageData.data[ i + 1 ] = v;
			imageData.data[ i + 2 ] = v;
			imageData.data[ i + 3 ] = 255;
		}
		octx.putImageData( imageData, 0, 0 );
		return this.ctx.createPattern( off, 'repeat' );
	};

	/**
	 * Picks the next profile, avoiding an immediate repeat of the last one
	 * so back-to-back transitions read as varied without ever looking
	 * accidental/random-broken.
	 */
	GlitchEngine.prototype._pickProfile = function ( forced ) {
		if ( forced && GlitchEngine.PROFILES.indexOf( forced ) !== -1 ) {
			return forced;
		}
		var idx;
		do {
			idx = Math.floor( Math.random() * GlitchEngine.PROFILES.length );
		} while ( idx === this._lastProfile && GlitchEngine.PROFILES.length > 1 );
		this._lastProfile = idx;
		return GlitchEngine.PROFILES[ idx ];
	};

	/**
	 * Plays one transition. `accentColor` is used to tint the flash/wave
	 * profiles so the effect feels tied to the incoming slide's palette.
	 */
	GlitchEngine.prototype.play = function ( imageUrl, durationMs, accentColor, forcedProfile ) {
		var canvas = this.canvas;
		var ctx = this.ctx;
		var w = canvas.clientWidth;
		var h = canvas.clientHeight;
		canvas.width = w;
		canvas.height = h;
		durationMs = durationMs || 550;
		this._noisePattern = this._noisePattern || this._makeNoisePattern();

		var profile = this._pickProfile( forcedProfile );
		var self = this;

		this._loadImage( imageUrl, function ( img ) {
			canvas.style.opacity = '1';
			var start = performance.now();
			var seed = Math.random();

			function drawCover() {
				if ( ! img ) {
					ctx.fillStyle = '#09091B';
					ctx.fillRect( 0, 0, w, h );
					return;
				}
				var scale = Math.max( w / img.width, h / img.height );
				var iw = img.width * scale;
				var ih = img.height * scale;
				ctx.drawImage( img, ( w - iw ) / 2, ( h - ih ) / 2, iw, ih );
			}

			function frame( now ) {
				var progress = Math.min( ( now - start ) / durationMs, 1 );
				ctx.clearRect( 0, 0, w, h );
				ctx.globalCompositeOperation = 'source-over';
				ctx.globalAlpha = 1;

				if ( 'rgb-slice' === profile ) {
					self._frameRgbSlice( ctx, w, h, progress, drawCover, seed );
				} else if ( 'wipe-flash' === profile ) {
					self._frameWipeFlash( ctx, w, h, progress, drawCover, accentColor );
				} else if ( 'chromatic-wave' === profile ) {
					self._frameChromaticWave( ctx, w, h, progress, drawCover, img, seed );
				} else {
					self._frameSignalScan( ctx, w, h, progress, drawCover, seed );
				}

				canvas.style.opacity = String( Math.max( 0, 1 - progress * 1.05 ) );

				if ( progress < 1 ) {
					requestAnimationFrame( frame );
				} else {
					ctx.clearRect( 0, 0, w, h );
					canvas.style.opacity = '0';
				}
			}

			requestAnimationFrame( frame );
		} );

		return profile;
	};

	/* Profile A — RGB channel split + horizontal slice displacement. */
	GlitchEngine.prototype._frameRgbSlice = function ( ctx, w, h, progress, drawCover, seed ) {
		drawCover();

		var maxShift = 16 * ( 1 - progress ) + 2;
		var shiftX = ( Math.sin( seed * 40 + progress * 30 ) ) * maxShift;

		ctx.globalCompositeOperation = 'screen';
		ctx.save();
		ctx.translate( shiftX, 0 );
		ctx.fillStyle = 'rgba(255,0,60,0.32)';
		drawCover();
		ctx.fillRect( 0, 0, w, h );
		ctx.restore();

		ctx.save();
		ctx.translate( -shiftX, 0 );
		ctx.fillStyle = 'rgba(0,220,255,0.28)';
		drawCover();
		ctx.fillRect( 0, 0, w, h );
		ctx.restore();

		// Horizontal slice displacement: redraw thin strips with an x-offset.
		ctx.globalCompositeOperation = 'source-over';
		if ( progress < 0.6 ) {
			var sliceCount = 5;
			for ( var i = 0; i < sliceCount; i++ ) {
				var sy = ( h / sliceCount ) * i;
				var sh = h / sliceCount;
				var sx = ( Math.random() - 0.5 ) * 30 * ( 1 - progress / 0.6 );
				ctx.drawImage( ctx.canvas, 0, sy, w, sh, sx, sy, w, sh );
			}
		}

		this._noiseOverlay( ctx, w, h, progress );
	};

	/* Profile B — directional wipe + a quick brightness flash. */
	GlitchEngine.prototype._frameWipeFlash = function ( ctx, w, h, progress, drawCover, accentColor ) {
		drawCover();

		// Flash spikes early then fades.
		var flash = Math.max( 0, 1 - progress * 4 );
		if ( flash > 0 ) {
			ctx.globalCompositeOperation = 'screen';
			ctx.fillStyle = accentColor || '#ffffff';
			ctx.globalAlpha = flash * 0.5;
			ctx.fillRect( 0, 0, w, h );
			ctx.globalAlpha = 1;
		}

		// Directional wipe: a hard-edged band sweeps across.
		ctx.globalCompositeOperation = 'source-over';
		var wipeX = w * progress * 1.3 - w * 0.15;
		ctx.save();
		ctx.beginPath();
		ctx.rect( wipeX - 40, 0, 80, h );
		ctx.clip();
		ctx.globalCompositeOperation = 'difference';
		ctx.fillStyle = '#ffffff';
		ctx.fillRect( 0, 0, w, h );
		ctx.restore();

		this._noiseOverlay( ctx, w, h, progress );
	};

	/* Profile C — chromatic wave ripple + soft zoom punch on the incoming image. */
	GlitchEngine.prototype._frameChromaticWave = function ( ctx, w, h, progress, drawCover, img, seed ) {
		if ( ! img ) {
			drawCover();
			this._noiseOverlay( ctx, w, h, progress );
			return;
		}
		var scale = Math.max( w / img.width, h / img.height ) * ( 1.08 - progress * 0.08 );
		var iw = img.width * scale;
		var ih = img.height * scale;

		var waveStrips = 24;
		for ( var i = 0; i < waveStrips; i++ ) {
			var sy = ( h / waveStrips ) * i;
			var sh = h / waveStrips + 1;
			var wave = Math.sin( ( i / waveStrips ) * Math.PI * 4 + seed * 10 + progress * 12 );
			var dx = wave * 10 * ( 1 - progress );
			ctx.drawImage( img, 0, 0, img.width, img.height, ( w - iw ) / 2 + dx, ( h - ih ) / 2 + sy * ( ih / h ) - sy, iw, sh * ( ih / h ) + 2 );
		}

		ctx.globalCompositeOperation = 'screen';
		ctx.fillStyle = 'rgba(217,70,239,0.18)';
		ctx.fillRect( 0, 0, w, h );

		this._noiseOverlay( ctx, w, h, progress );
	};

	/* Profile D — broadcast signal-scan noise + vertical displacement. */
	GlitchEngine.prototype._frameSignalScan = function ( ctx, w, h, progress, drawCover, seed ) {
		drawCover();

		ctx.globalCompositeOperation = 'source-over';
		if ( progress < 0.65 ) {
			var bands = 4;
			for ( var i = 0; i < bands; i++ ) {
				var bx = ( w / bands ) * i;
				var bw = w / bands;
				var dy = ( Math.random() - 0.5 ) * 24 * ( 1 - progress / 0.65 );
				ctx.drawImage( ctx.canvas, bx, 0, bw, h, bx, dy, bw, h );
			}
		}

		// Horizontal scan line sweeping down.
		ctx.globalCompositeOperation = 'overlay';
		var scanY = h * progress;
		var grad = ctx.createLinearGradient( 0, scanY - 30, 0, scanY + 30 );
		grad.addColorStop( 0, 'rgba(0,240,255,0)' );
		grad.addColorStop( 0.5, 'rgba(0,240,255,0.35)' );
		grad.addColorStop( 1, 'rgba(0,240,255,0)' );
		ctx.fillStyle = grad;
		ctx.fillRect( 0, scanY - 30, w, 60 );

		this._noiseOverlay( ctx, w, h, progress );
	};

	GlitchEngine.prototype._noiseOverlay = function ( ctx, w, h, progress ) {
		ctx.globalCompositeOperation = 'overlay';
		ctx.globalAlpha = 0.12 * ( 1 - progress ) + 0.04;
		ctx.fillStyle = this._noisePattern;
		ctx.fillRect( 0, 0, w, h );
		ctx.globalAlpha = 1;
		ctx.globalCompositeOperation = 'source-over';
	};

	global.MWSBGlitchEngine = GlitchEngine;
} )( window );
