/**
 * MW Showband — WebGL Fluid Simulation.
 *
 * A from-scratch implementation of the classic "Stable Fluids" technique
 * (semi-Lagrangian advection + Jacobi-iteration pressure projection,
 * ping-pong framebuffers for velocity/dye/pressure/curl). Exposed as a
 * self-contained class with no external dependencies.
 *
 * Usage:
 *   var sim = new MWSBFluidSimulation(canvasEl, { simResolution: 128, dyeResolution: 512 });
 *   sim.start();
 *   sim.splat(x, y, dx, dy, [r, g, b]);   // x/y and dx/dy in 0..1 normalized canvas space
 *   sim.stop();
 *   sim.destroy();
 *
 * If WebGL is unavailable or a shader fails to compile, the simulation
 * silently disables itself (isSupported = false) rather than throwing —
 * callers should check `sim.isSupported` before relying on it.
 *
 * Author: Aqib Dev
 */
( function ( global ) {
	'use strict';

	function compileShader( gl, type, source ) {
		var shader = gl.createShader( type );
		gl.shaderSource( shader, source );
		gl.compileShader( shader );
		if ( ! gl.getShaderParameter( shader, gl.COMPILE_STATUS ) ) {
			var log = gl.getShaderInfoLog( shader );
			gl.deleteShader( shader );
			throw new Error( 'Shader compile error: ' + log );
		}
		return shader;
	}

	function createProgram( gl, vsSource, fsSource ) {
		var vs = compileShader( gl, gl.VERTEX_SHADER, vsSource );
		var fs = compileShader( gl, gl.FRAGMENT_SHADER, fsSource );
		var program = gl.createProgram();
		gl.attachShader( program, vs );
		gl.attachShader( program, fs );
		gl.linkProgram( program );
		if ( ! gl.getProgramParameter( program, gl.LINK_STATUS ) ) {
			var log = gl.getProgramInfoLog( program );
			throw new Error( 'Program link error: ' + log );
		}
		var uniforms = {};
		var uniformCount = gl.getProgramParameter( program, gl.ACTIVE_UNIFORMS );
		for ( var i = 0; i < uniformCount; i++ ) {
			var info = gl.getActiveUniform( program, i );
			uniforms[ info.name ] = gl.getUniformLocation( program, info.name );
		}
		return { program: program, uniforms: uniforms };
	}

	var BASE_VERTEX = [
		'precision highp float;',
		'attribute vec2 aPosition;',
		'varying vec2 vUv;',
		'varying vec2 vL;',
		'varying vec2 vR;',
		'varying vec2 vT;',
		'varying vec2 vB;',
		'uniform vec2 texelSize;',
		'void main () {',
		'  vUv = aPosition * 0.5 + 0.5;',
		'  vL = vUv - vec2(texelSize.x, 0.0);',
		'  vR = vUv + vec2(texelSize.x, 0.0);',
		'  vT = vUv + vec2(0.0, texelSize.y);',
		'  vB = vUv - vec2(0.0, texelSize.y);',
		'  gl_Position = vec4(aPosition, 0.0, 1.0);',
		'}',
	].join( '\n' );

	var COPY_FRAG = [
		'precision mediump float;',
		'varying vec2 vUv;',
		'uniform sampler2D uTexture;',
		'void main () { gl_FragColor = texture2D(uTexture, vUv); }',
	].join( '\n' );

	var CLEAR_FRAG = [
		'precision mediump float;',
		'varying vec2 vUv;',
		'uniform sampler2D uTexture;',
		'uniform float value;',
		'void main () { gl_FragColor = value * texture2D(uTexture, vUv); }',
	].join( '\n' );

	var SPLAT_FRAG = [
		'precision highp float;',
		'varying vec2 vUv;',
		'uniform sampler2D uTarget;',
		'uniform float aspectRatio;',
		'uniform vec3 color;',
		'uniform vec2 point;',
		'uniform float radius;',
		'void main () {',
		'  vec2 p = vUv - point.xy;',
		'  p.x *= aspectRatio;',
		'  vec3 splat = exp(-dot(p, p) / radius) * color;',
		'  vec3 base = texture2D(uTarget, vUv).xyz;',
		'  gl_FragColor = vec4(base + splat, 1.0);',
		'}',
	].join( '\n' );

	var ADVECTION_FRAG = [
		'precision highp float;',
		'varying vec2 vUv;',
		'uniform sampler2D uVelocity;',
		'uniform sampler2D uSource;',
		'uniform vec2 texelSize;',
		'uniform float dt;',
		'uniform float dissipation;',
		'void main () {',
		'  vec2 coord = vUv - dt * texture2D(uVelocity, vUv).xy * texelSize;',
		'  gl_FragColor = dissipation * texture2D(uSource, coord);',
		'  gl_FragColor.a = 1.0;',
		'}',
	].join( '\n' );

	var DIVERGENCE_FRAG = [
		'precision highp float;',
		'varying vec2 vUv; varying vec2 vL; varying vec2 vR; varying vec2 vT; varying vec2 vB;',
		'uniform sampler2D uVelocity;',
		'void main () {',
		'  float L = texture2D(uVelocity, vL).x;',
		'  float R = texture2D(uVelocity, vR).x;',
		'  float T = texture2D(uVelocity, vT).y;',
		'  float B = texture2D(uVelocity, vB).y;',
		'  float div = 0.5 * (R - L + T - B);',
		'  gl_FragColor = vec4(div, 0.0, 0.0, 1.0);',
		'}',
	].join( '\n' );

	var CURL_FRAG = [
		'precision highp float;',
		'varying vec2 vUv; varying vec2 vL; varying vec2 vR; varying vec2 vT; varying vec2 vB;',
		'uniform sampler2D uVelocity;',
		'void main () {',
		'  float L = texture2D(uVelocity, vL).y;',
		'  float R = texture2D(uVelocity, vR).y;',
		'  float T = texture2D(uVelocity, vT).x;',
		'  float B = texture2D(uVelocity, vB).x;',
		'  float vorticity = R - L - T + B;',
		'  gl_FragColor = vec4(0.5 * vorticity, 0.0, 0.0, 1.0);',
		'}',
	].join( '\n' );

	var VORTICITY_FRAG = [
		'precision highp float;',
		'varying vec2 vUv; varying vec2 vL; varying vec2 vR; varying vec2 vT; varying vec2 vB;',
		'uniform sampler2D uVelocity;',
		'uniform sampler2D uCurl;',
		'uniform float curl;',
		'uniform float dt;',
		'void main () {',
		'  float L = texture2D(uCurl, vL).x;',
		'  float R = texture2D(uCurl, vR).x;',
		'  float T = texture2D(uCurl, vT).x;',
		'  float B = texture2D(uCurl, vB).x;',
		'  float C = texture2D(uCurl, vUv).x;',
		'  vec2 force = 0.5 * vec2(abs(T) - abs(B), abs(R) - abs(L));',
		'  force /= length(force) + 0.0001;',
		'  force *= curl * C;',
		'  force.y *= -1.0;',
		'  vec2 vel = texture2D(uVelocity, vUv).xy;',
		'  gl_FragColor = vec4(vel + force * dt, 0.0, 1.0);',
		'}',
	].join( '\n' );

	var PRESSURE_FRAG = [
		'precision highp float;',
		'varying vec2 vUv; varying vec2 vL; varying vec2 vR; varying vec2 vT; varying vec2 vB;',
		'uniform sampler2D uPressure;',
		'uniform sampler2D uDivergence;',
		'void main () {',
		'  float L = texture2D(uPressure, vL).x;',
		'  float R = texture2D(uPressure, vR).x;',
		'  float T = texture2D(uPressure, vT).x;',
		'  float B = texture2D(uPressure, vB).x;',
		'  float divergence = texture2D(uDivergence, vUv).x;',
		'  float pressure = (L + R + B + T - divergence) * 0.25;',
		'  gl_FragColor = vec4(pressure, 0.0, 0.0, 1.0);',
		'}',
	].join( '\n' );

	var GRADIENT_SUBTRACT_FRAG = [
		'precision highp float;',
		'varying vec2 vUv; varying vec2 vL; varying vec2 vR; varying vec2 vT; varying vec2 vB;',
		'uniform sampler2D uPressure;',
		'uniform sampler2D uVelocity;',
		'void main () {',
		'  float L = texture2D(uPressure, vL).x;',
		'  float R = texture2D(uPressure, vR).x;',
		'  float T = texture2D(uPressure, vT).x;',
		'  float B = texture2D(uPressure, vB).x;',
		'  vec2 vel = texture2D(uVelocity, vUv).xy;',
		'  vel.xy -= vec2(R - L, T - B);',
		'  gl_FragColor = vec4(vel, 0.0, 1.0);',
		'}',
	].join( '\n' );

	var DISPLAY_FRAG = [
		'precision highp float;',
		'varying vec2 vUv;',
		'uniform sampler2D uTexture;',
		'void main () {',
		'  vec3 c = texture2D(uTexture, vUv).rgb;',
		'  gl_FragColor = vec4(c, max(max(c.r, c.g), c.b));',
		'}',
	].join( '\n' );

	function MWSBFluidSimulation( canvas, options ) {
		this.canvas = canvas;
		this.options = Object.assign( {
			simResolution: 128,
			dyeResolution: 512,
			densityDissipation: 0.98,
			velocityDissipation: 0.99,
			pressureIterations: 20,
			curl: 30,
			splatRadius: 0.25,
		}, options || {} );

		this.isSupported = false;
		this._running = false;
		this._rafId = null;
		this._lastTime = 0;

		try {
			this._initGL();
			this._initPrograms();
			this._initFramebuffers();
			this.isSupported = true;
		} catch ( err ) {
			if ( global.console && console.warn ) {
				console.warn( 'MWSBFluidSimulation disabled:', err.message );
			}
			this.isSupported = false;
		}
	}

	MWSBFluidSimulation.prototype._initGL = function () {
		var gl = this.canvas.getContext( 'webgl', { alpha: true, premultipliedAlpha: false } ) ||
			this.canvas.getContext( 'experimental-webgl', { alpha: true, premultipliedAlpha: false } );
		if ( ! gl ) {
			throw new Error( 'WebGL not available' );
		}
		this.gl = gl;

		this.halfFloat = gl.getExtension( 'OES_texture_half_float' );
		gl.getExtension( 'OES_texture_half_float_linear' );
		this.texType = this.halfFloat ? this.halfFloat.HALF_FLOAT_OES : gl.UNSIGNED_BYTE;

		var quadVerts = new Float32Array( [ -1, -1, -1, 1, 1, 1, 1, -1 ] );
		this.quadBuffer = gl.createBuffer();
		gl.bindBuffer( gl.ARRAY_BUFFER, this.quadBuffer );
		gl.bufferData( gl.ARRAY_BUFFER, quadVerts, gl.STATIC_DRAW );

		this.elemBuffer = gl.createBuffer();
		gl.bindBuffer( gl.ELEMENT_ARRAY_BUFFER, this.elemBuffer );
		gl.bufferData( gl.ELEMENT_ARRAY_BUFFER, new Uint16Array( [ 0, 1, 2, 0, 2, 3 ] ), gl.STATIC_DRAW );
	};

	MWSBFluidSimulation.prototype._initPrograms = function () {
		var gl = this.gl;
		this.programs = {
			copy: createProgram( gl, BASE_VERTEX, COPY_FRAG ),
			clear: createProgram( gl, BASE_VERTEX, CLEAR_FRAG ),
			splat: createProgram( gl, BASE_VERTEX, SPLAT_FRAG ),
			advection: createProgram( gl, BASE_VERTEX, ADVECTION_FRAG ),
			divergence: createProgram( gl, BASE_VERTEX, DIVERGENCE_FRAG ),
			curl: createProgram( gl, BASE_VERTEX, CURL_FRAG ),
			vorticity: createProgram( gl, BASE_VERTEX, VORTICITY_FRAG ),
			pressure: createProgram( gl, BASE_VERTEX, PRESSURE_FRAG ),
			gradientSubtract: createProgram( gl, BASE_VERTEX, GRADIENT_SUBTRACT_FRAG ),
			display: createProgram( gl, BASE_VERTEX, DISPLAY_FRAG ),
		};
	};

	MWSBFluidSimulation.prototype._createFBO = function ( w, h ) {
		var gl = this.gl;
		gl.activeTexture( gl.TEXTURE0 );
		var texture = gl.createTexture();
		gl.bindTexture( gl.TEXTURE_2D, texture );
		gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR );
		gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR );
		gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE );
		gl.texParameteri( gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE );
		gl.texImage2D( gl.TEXTURE_2D, 0, gl.RGBA, w, h, 0, gl.RGBA, this.texType, null );

		var fbo = gl.createFramebuffer();
		gl.bindFramebuffer( gl.FRAMEBUFFER, fbo );
		gl.framebufferTexture2D( gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0, gl.TEXTURE_2D, texture, 0 );
		gl.viewport( 0, 0, w, h );
		gl.clear( gl.COLOR_BUFFER_BIT );

		return { texture: texture, fbo: fbo, width: w, height: h,
			attach: function ( id ) {
				gl.activeTexture( gl.TEXTURE0 + id );
				gl.bindTexture( gl.TEXTURE_2D, texture );
				return id;
			}
		};
	};

	MWSBFluidSimulation.prototype._createDoubleFBO = function ( w, h ) {
		var fbo1 = this._createFBO( w, h );
		var fbo2 = this._createFBO( w, h );
		return {
			width: w, height: h,
			get read() { return fbo1; },
			get write() { return fbo2; },
			swap: function () {
				var tmp = fbo1; fbo1 = fbo2; fbo2 = tmp;
			},
		};
	};

	MWSBFluidSimulation.prototype._initFramebuffers = function () {
		var simRes = this.options.simResolution;
		var dyeRes = this.options.dyeResolution;

		this.velocity = this._createDoubleFBO( simRes, simRes );
		this.dye = this._createDoubleFBO( dyeRes, dyeRes );
		this.divergenceFBO = this._createFBO( simRes, simRes );
		this.curlFBO = this._createFBO( simRes, simRes );
		this.pressureFBO = this._createDoubleFBO( simRes, simRes );
	};

	MWSBFluidSimulation.prototype._drawQuad = function ( program ) {
		var gl = this.gl;
		gl.useProgram( program.program );
		gl.bindBuffer( gl.ARRAY_BUFFER, this.quadBuffer );
		gl.vertexAttribPointer( 0, 2, gl.FLOAT, false, 0, 0 );
		gl.enableVertexAttribArray( 0 );
		gl.bindBuffer( gl.ELEMENT_ARRAY_BUFFER, this.elemBuffer );
		gl.drawElements( gl.TRIANGLES, 6, gl.UNSIGNED_SHORT, 0 );
	};

	MWSBFluidSimulation.prototype._blit = function ( target ) {
		var gl = this.gl;
		if ( target == null ) {
			gl.viewport( 0, 0, gl.drawingBufferWidth, gl.drawingBufferHeight );
			gl.bindFramebuffer( gl.FRAMEBUFFER, null );
		} else {
			gl.viewport( 0, 0, target.width, target.height );
			gl.bindFramebuffer( gl.FRAMEBUFFER, target.fbo );
		}
	};

	MWSBFluidSimulation.prototype.resize = function () {
		var canvas = this.canvas;
		var dpr = Math.min( window.devicePixelRatio || 1, 1.5 );
		var w = Math.floor( canvas.clientWidth * dpr );
		var h = Math.floor( canvas.clientHeight * dpr );
		if ( canvas.width !== w || canvas.height !== h ) {
			canvas.width = w;
			canvas.height = h;
		}
	};

	MWSBFluidSimulation.prototype.splat = function ( x, y, dx, dy, color ) {
		if ( ! this.isSupported ) {
			return;
		}
		var gl = this.gl;

		this._blit( this.velocity.write );
		var p = this.programs.splat;
		gl.useProgram( p.program );
		this.velocity.read.attach( 0 );
		gl.uniform1i( p.uniforms.uTarget, 0 );
		gl.uniform1f( p.uniforms.aspectRatio, this.canvas.width / this.canvas.height );
		gl.uniform2f( p.uniforms.point, x, y );
		gl.uniform3f( p.uniforms.color, dx, dy, 0.0 );
		gl.uniform1f( p.uniforms.radius, this.options.splatRadius / 100.0 );
		this._drawQuad( p );
		this.velocity.swap();

		this._blit( this.dye.write );
		this.dye.read.attach( 0 );
		gl.uniform1i( p.uniforms.uTarget, 0 );
		gl.uniform3f( p.uniforms.color, color[ 0 ], color[ 1 ], color[ 2 ] );
		this._drawQuad( p );
		this.dye.swap();
	};

	MWSBFluidSimulation.prototype._step = function ( dt ) {
		var gl = this.gl;
		var opt = this.options;
		gl.disable( gl.BLEND );

		// Curl
		this._blit( this.curlFBO );
		var pCurl = this.programs.curl;
		gl.useProgram( pCurl.program );
		gl.uniform2f( pCurl.uniforms.texelSize, 1.0 / this.velocity.width, 1.0 / this.velocity.height );
		this.velocity.read.attach( 0 );
		gl.uniform1i( pCurl.uniforms.uVelocity, 0 );
		this._drawQuad( pCurl );

		// Vorticity confinement
		this._blit( this.velocity.write );
		var pVort = this.programs.vorticity;
		gl.useProgram( pVort.program );
		gl.uniform2f( pVort.uniforms.texelSize, 1.0 / this.velocity.width, 1.0 / this.velocity.height );
		this.velocity.read.attach( 0 );
		gl.uniform1i( pVort.uniforms.uVelocity, 0 );
		this.curlFBO.attach( 1 );
		gl.uniform1i( pVort.uniforms.uCurl, 1 );
		gl.uniform1f( pVort.uniforms.curl, opt.curl );
		gl.uniform1f( pVort.uniforms.dt, dt );
		this._drawQuad( pVort );
		this.velocity.swap();

		// Divergence
		this._blit( this.divergenceFBO );
		var pDiv = this.programs.divergence;
		gl.useProgram( pDiv.program );
		gl.uniform2f( pDiv.uniforms.texelSize, 1.0 / this.velocity.width, 1.0 / this.velocity.height );
		this.velocity.read.attach( 0 );
		gl.uniform1i( pDiv.uniforms.uVelocity, 0 );
		this._drawQuad( pDiv );

		// Clear pressure
		this._blit( this.pressureFBO.write );
		var pClear = this.programs.clear;
		gl.useProgram( pClear.program );
		this.pressureFBO.read.attach( 0 );
		gl.uniform1i( pClear.uniforms.uTexture, 0 );
		gl.uniform1f( pClear.uniforms.value, 0.8 );
		this._drawQuad( pClear );
		this.pressureFBO.swap();

		// Pressure Jacobi iterations
		var pPressure = this.programs.pressure;
		gl.useProgram( pPressure.program );
		gl.uniform2f( pPressure.uniforms.texelSize, 1.0 / this.velocity.width, 1.0 / this.velocity.height );
		this.divergenceFBO.attach( 0 );
		gl.uniform1i( pPressure.uniforms.uDivergence, 0 );
		for ( var i = 0; i < opt.pressureIterations; i++ ) {
			this._blit( this.pressureFBO.write );
			this.pressureFBO.read.attach( 1 );
			gl.uniform1i( pPressure.uniforms.uPressure, 1 );
			this._drawQuad( pPressure );
			this.pressureFBO.swap();
		}

		// Gradient subtract
		this._blit( this.velocity.write );
		var pGrad = this.programs.gradientSubtract;
		gl.useProgram( pGrad.program );
		gl.uniform2f( pGrad.uniforms.texelSize, 1.0 / this.velocity.width, 1.0 / this.velocity.height );
		this.pressureFBO.read.attach( 0 );
		gl.uniform1i( pGrad.uniforms.uPressure, 0 );
		this.velocity.read.attach( 1 );
		gl.uniform1i( pGrad.uniforms.uVelocity, 1 );
		this._drawQuad( pGrad );
		this.velocity.swap();

		// Advect velocity
		this._blit( this.velocity.write );
		var pAdv = this.programs.advection;
		gl.useProgram( pAdv.program );
		gl.uniform2f( pAdv.uniforms.texelSize, 1.0 / this.velocity.width, 1.0 / this.velocity.height );
		this.velocity.read.attach( 0 );
		gl.uniform1i( pAdv.uniforms.uVelocity, 0 );
		gl.uniform1i( pAdv.uniforms.uSource, 0 );
		gl.uniform1f( pAdv.uniforms.dt, dt );
		gl.uniform1f( pAdv.uniforms.dissipation, opt.velocityDissipation );
		this._drawQuad( pAdv );
		this.velocity.swap();

		// Advect dye
		this._blit( this.dye.write );
		gl.uniform2f( pAdv.uniforms.texelSize, 1.0 / this.velocity.width, 1.0 / this.velocity.height );
		this.velocity.read.attach( 0 );
		gl.uniform1i( pAdv.uniforms.uVelocity, 0 );
		this.dye.read.attach( 1 );
		gl.uniform1i( pAdv.uniforms.uSource, 1 );
		gl.uniform1f( pAdv.uniforms.dissipation, opt.densityDissipation );
		this._drawQuad( pAdv );
		this.dye.swap();
	};

	MWSBFluidSimulation.prototype._render = function () {
		var gl = this.gl;
		gl.disable( gl.BLEND );
		this._blit( null );
		var p = this.programs.display;
		gl.useProgram( p.program );
		this.dye.read.attach( 0 );
		gl.uniform1i( p.uniforms.uTexture, 0 );
		this._drawQuad( p );
	};

	MWSBFluidSimulation.prototype._loop = function ( now ) {
		if ( ! this._running ) {
			return;
		}
		var dt = Math.min( ( now - this._lastTime ) / 1000, 1 / 30 ) || 1 / 60;
		this._lastTime = now;

		try {
			this._step( dt );
			this._render();
		} catch ( err ) {
			this.stop();
			if ( global.console && console.warn ) {
				console.warn( 'MWSBFluidSimulation runtime error, stopping:', err.message );
			}
			return;
		}

		this._rafId = requestAnimationFrame( this._loop.bind( this ) );
	};

	MWSBFluidSimulation.prototype.start = function () {
		if ( ! this.isSupported || this._running ) {
			return;
		}
		this._running = true;
		this._lastTime = performance.now();
		this._rafId = requestAnimationFrame( this._loop.bind( this ) );
	};

	MWSBFluidSimulation.prototype.stop = function () {
		this._running = false;
		if ( this._rafId ) {
			cancelAnimationFrame( this._rafId );
			this._rafId = null;
		}
	};

	MWSBFluidSimulation.prototype.destroy = function () {
		this.stop();
		if ( this.gl && this.gl.getExtension( 'WEBGL_lose_context' ) ) {
			this.gl.getExtension( 'WEBGL_lose_context' ).loseContext();
		}
	};

	global.MWSBFluidSimulation = MWSBFluidSimulation;
} )( window );
