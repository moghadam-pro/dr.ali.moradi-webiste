/**
 * Small progressive-enhancement behaviors for the theme's own markup:
 * mobile nav / language-menu toggles, scroll-reveal, the appointment
 * accordion, and the interior-cover scroll-shrink effect. Vanilla JS
 * equivalents of what the reference React site does with component state
 * (see app/site-page.tsx) -- this theme has no client-side framework, so
 * these are plain event listeners against data attributes.
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		// Mobile nav toggle.
		var menuButton = document.querySelector( '[data-menu-toggle]' );
		var mobileNav = document.querySelector( '[data-mobile-nav]' );
		if ( menuButton && mobileNav ) {
			menuButton.addEventListener( 'click', function () {
				var isOpen = ! mobileNav.hasAttribute( 'hidden' );
				if ( isOpen ) {
					mobileNav.setAttribute( 'hidden', '' );
					menuButton.setAttribute( 'aria-expanded', 'false' );
					menuButton.innerHTML = menuButton.getAttribute( 'data-icon-open' );
				} else {
					mobileNav.removeAttribute( 'hidden' );
					menuButton.setAttribute( 'aria-expanded', 'true' );
					menuButton.innerHTML = menuButton.getAttribute( 'data-icon-close' );
				}
			} );
		}

		// Language dropdown toggle.
		var langButton = document.querySelector( '[data-language-toggle]' );
		var langMenu = document.querySelector( '[data-language-menu]' );
		if ( langButton && langMenu ) {
			langButton.addEventListener( 'click', function ( event ) {
				event.stopPropagation();
				langMenu.toggleAttribute( 'hidden' );
				langButton.setAttribute( 'aria-expanded', langMenu.hasAttribute( 'hidden' ) ? 'false' : 'true' );
			} );
			document.addEventListener( 'click', function ( event ) {
				if ( ! langMenu.hasAttribute( 'hidden' ) && ! langMenu.contains( event.target ) && event.target !== langButton ) {
					langMenu.setAttribute( 'hidden', '' );
					langButton.setAttribute( 'aria-expanded', 'false' );
				}
			} );
		}

		// Appointment accordion (single-open).
		var triggers = document.querySelectorAll( '[data-appointment-trigger]' );
		triggers.forEach( function ( trigger ) {
			trigger.addEventListener( 'click', function () {
				var item = trigger.closest( '.appointment-item' );
				var wasOpen = item.classList.contains( 'is-open' );
				item.parentElement.querySelectorAll( '.appointment-item' ).forEach( function ( sibling ) {
					sibling.classList.remove( 'is-open' );
					sibling.querySelector( '[data-appointment-trigger]' ).setAttribute( 'aria-expanded', 'false' );
				} );
				if ( ! wasOpen ) {
					item.classList.add( 'is-open' );
					trigger.setAttribute( 'aria-expanded', 'true' );
				}
			} );
		} );

		// Scroll-reveal.
		var revealTargets = document.querySelectorAll( '.reveal' );
		if ( 'IntersectionObserver' in window && revealTargets.length ) {
			var observer = new IntersectionObserver(
				function ( entries ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							entry.target.classList.add( 'is-visible' );
						}
					} );
				},
				{ threshold: 0.12 }
			);
			revealTargets.forEach( function ( target ) {
				observer.observe( target );
			} );
		} else {
			revealTargets.forEach( function ( target ) {
				target.classList.add( 'is-visible' );
			} );
		}

		// Interior cover scroll-shrink.
		var cover = document.querySelector( '.interior-cover' );
		if ( cover ) {
			var frame = 0;
			var update = function () {
				cancelAnimationFrame( frame );
				frame = requestAnimationFrame( function () {
					cover.style.setProperty( '--cover-shrink', Math.min( window.scrollY * 0.42, 120 ) + 'px' );
				} );
			};
			update();
			window.addEventListener( 'scroll', update, { passive: true } );
		}
	} );
} )();
