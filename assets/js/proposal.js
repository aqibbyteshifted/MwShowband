/*
 * MW Showband — proposal page interactivity: clicking a package's "Select
 * This Lineup" button recalculates and updates the Investment & Payment
 * Schedule widget elsewhere on the page (base fee, VAT, total, and the
 * three milestone amounts), then scrolls the investment section into view.
 * Entirely self-contained to the proposal page's two widgets — it does
 * nothing on any page that doesn't have both on it.
 * Author: Aqib Dev
 */
(function () {
	'use strict';

	function fmtGBP( n ) {
		return '£' + n.toLocaleString( 'en-GB', { minimumFractionDigits: 2, maximumFractionDigits: 2 } );
	}

	function fmtVat( n ) {
		return '£' + n.toLocaleString( 'en-GB', { maximumFractionDigits: 0 } ) + ' + VAT';
	}

	function initPackages( root ) {
		var buttons = ( root || document ).querySelectorAll( '[data-mwsb-package-select]' );
		if ( ! buttons.length ) {
			return;
		}

		buttons.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var base  = parseFloat( btn.getAttribute( 'data-price' ) ) || 0;
				var name  = btn.getAttribute( 'data-tier-name' ) || btn.getAttribute( 'data-tier' ) || '';
				var vat   = base * 0.2;
				var total = base + vat;
				var m1    = base * 0.4;
				var m2    = base * 0.4;
				var m3    = base * 0.2;

				var tag = document.querySelector( '[data-mwsb-active-tier]' );
				if ( tag ) {
					tag.textContent = name.toUpperCase() + ' (SELECTED)';
				}

				var map = {
					'[data-mwsb-breakdown-base]':  fmtGBP( base ),
					'[data-mwsb-breakdown-vat]':   fmtGBP( vat ),
					'[data-mwsb-breakdown-total]': fmtGBP( total ),
					'[data-mwsb-milestone-1]':     fmtVat( m1 ),
					'[data-mwsb-milestone-2]':     fmtVat( m2 ),
					'[data-mwsb-milestone-3]':     fmtVat( m3 ),
				};
				Object.keys( map ).forEach( function ( sel ) {
					var el = document.querySelector( sel );
					if ( el ) {
						el.textContent = map[ sel ];
					}
				} );

				buttons.forEach( function ( b ) {
					b.classList.remove( 'is-selected' );
					var original = b.getAttribute( 'data-original-text' );
					if ( original ) {
						b.textContent = original;
					}
				} );
				if ( ! btn.getAttribute( 'data-original-text' ) ) {
					btn.setAttribute( 'data-original-text', btn.textContent );
				}
				btn.classList.add( 'is-selected' );
				btn.textContent = 'LINEUP SELECTED ✓';

				var investSection = document.querySelector( '.mwsb-prop-invest' );
				if ( investSection ) {
					investSection.scrollIntoView( { behavior: 'smooth', block: 'start' } );
				}
			} );
		} );
	}

	function init( root ) {
		initPackages( root );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			init();
		} );
	} else {
		init();
	}

	// Elementor editor: re-initialise when a proposal widget is (re)rendered.
	window.addEventListener( 'elementor/frontend/init', function () {
		if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
			window.elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function ( $scope ) {
				init( $scope && $scope[ 0 ] ? $scope[ 0 ] : document );
			} );
		}
	} );
})();
