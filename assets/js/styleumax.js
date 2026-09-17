/**
 * Styleumax front-end behaviours.
 * Vanilla JS only - depends on Bootstrap bundle for collapse/carousel.
 * v1.1.0: adds the single-post reading progress bar.
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var nav = document.querySelector( '.sumx-navbar' );
		var backToTop = document.getElementById( 'sumx-backtotop' );

		/* ---------------------------------------------------------------
		 * Reading progress bar (single posts).
		 * Fills .sumx-progress span from 0 to 100% while the article body
		 * (.sumx-entry) travels through the viewport. rAF-throttled.
		 * ------------------------------------------------------------ */
		var progress = document.querySelector( '.sumx-progress' );
		var progressFill = progress ? progress.querySelector( 'span' ) : null;
		var article = document.querySelector( '.sumx-entry' );

		if ( progress && progressFill && article ) {
			var progressTicking = false;

			var updateProgress = function () {
				progressTicking = false;

				var start = article.getBoundingClientRect().top + window.scrollY - 120;
				var end = start + article.offsetHeight - window.innerHeight + 120;

				if ( end <= start ) {
					progressFill.style.width = '100%';
					return;
				}

				var ratio = ( window.scrollY - start ) / ( end - start );
				ratio = Math.min( 1, Math.max( 0, ratio ) );

				progressFill.style.width = ( ratio * 100 ).toFixed( 2 ) + '%';
			};

			var onProgressScroll = function () {
				if ( ! progressTicking ) {
					progressTicking = true;
					window.requestAnimationFrame( updateProgress );
				}
			};

			window.addEventListener( 'scroll', onProgressScroll, { passive: true } );
			window.addEventListener( 'resize', onProgressScroll, { passive: true } );
			updateProgress();
		}

		/* Navbar shadow + back-to-top visibility on scroll. */
		var onScroll = function () {
			var scrolled = window.scrollY > 10;

			if ( nav ) {
				nav.classList.toggle( 'is-scrolled', scrolled );
			}
			if ( backToTop ) {
				backToTop.classList.toggle( 'is-visible', window.scrollY > 480 );
			}
		};

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();

		/* Smooth scroll for back to top. */
		if ( backToTop ) {
			backToTop.addEventListener( 'click', function () {
				window.scrollTo( { top: 0, behavior: 'smooth' } );
			} );
		}

		/* Dark / light mode toggle. */
		var toggle = document.getElementById( 'sumx-theme-toggle' );
		var root = document.documentElement;
		var settings = window.styleumaxSettings || {};

		var stored = null;
		try {
			stored = window.localStorage.getItem( 'styleumax-theme' );
		} catch ( e ) {
			stored = null; // localStorage unavailable (privacy mode).
		}

		// Initial attribute may already be set by the inline head script.
		var applyMode = function ( mode ) {
			root.setAttribute( 'data-bs-theme', mode );

			var icon = toggle ? toggle.querySelector( 'i' ) : null;
			if ( icon ) {
				icon.className = 'fa-solid ' + ( 'dark' === mode ? 'fa-sun' : 'fa-moon' );
			}
		};

		var initial = stored || ( 'dark' === settings.themeMode ? 'dark' : root.getAttribute( 'data-bs-theme' ) || 'light' );
		if ( toggle ) {
			applyMode( initial );

			toggle.addEventListener( 'click', function () {
				var next = 'dark' === root.getAttribute( 'data-bs-theme' ) ? 'light' : 'dark';
				applyMode( next );

				try {
					window.localStorage.setItem( 'styleumax-theme', next );
				} catch ( e ) {
					// Ignore write failures.
				}
			} );
		}

		/* Close the nav search collapse after a submit (mobile nicety). */
		var searchCollapse = document.getElementById( 'sumx-search-collapse' );
		if ( searchCollapse && window.bootstrap && window.bootstrap.Collapse ) {
			searchCollapse.addEventListener( 'submit', function () {
				var instance = window.bootstrap.Collapse.getInstance( searchCollapse );
				if ( instance ) {
					instance.hide();
				}
			} );
		}
	} );
} )();
