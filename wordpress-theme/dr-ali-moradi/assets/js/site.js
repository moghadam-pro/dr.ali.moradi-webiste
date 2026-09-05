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

		// Gallery rows (rotating 4-thumbnail preview + full grids) and
		// their shared lightbox modal.
		var modal = document.querySelector( '[data-gallery-modal]' );
		var modalImage = modal && modal.querySelector( '[data-gallery-modal-image]' );
		var modalCount = modal && modal.querySelector( '[data-gallery-modal-count]' );
		var activeImages = [];
		var activeIndex = 0;

		function openModal( images, index ) {
			if ( ! modal ) {
				return;
			}
			activeImages = images;
			activeIndex = index;
			showModalImage();
			modal.removeAttribute( 'hidden' );
			document.body.style.overflow = 'hidden';
		}

		function showModalImage() {
			if ( ! modalImage || ! activeImages.length ) {
				return;
			}
			modalImage.src = activeImages[ activeIndex ];
			if ( modalCount ) {
				modalCount.textContent = ( activeIndex + 1 ) + ' / ' + activeImages.length;
			}
		}

		function closeModal() {
			if ( ! modal ) {
				return;
			}
			modal.setAttribute( 'hidden', '' );
			document.body.style.overflow = '';
		}

		function stepModal( direction ) {
			if ( ! activeImages.length ) {
				return;
			}
			activeIndex = ( activeIndex + direction + activeImages.length ) % activeImages.length;
			showModalImage();
		}

		if ( modal ) {
			var modalClose = modal.querySelector( '[data-gallery-close]' );
			var modalPrev = modal.querySelector( '[data-gallery-modal-prev]' );
			var modalNext = modal.querySelector( '[data-gallery-modal-next]' );
			if ( modalClose ) {
				modalClose.addEventListener( 'click', closeModal );
			}
			if ( modalPrev ) {
				modalPrev.addEventListener( 'click', function () { stepModal( -1 ); } );
			}
			if ( modalNext ) {
				modalNext.addEventListener( 'click', function () { stepModal( 1 ); } );
			}
			modal.addEventListener( 'click', function ( event ) {
				if ( event.target === modal ) {
					closeModal();
				}
			} );
			document.addEventListener( 'keydown', function ( event ) {
				if ( modal.hasAttribute( 'hidden' ) ) {
					return;
				}
				if ( event.key === 'Escape' ) {
					closeModal();
				} else if ( event.key === 'ArrowLeft' ) {
					stepModal( -1 );
				} else if ( event.key === 'ArrowRight' ) {
					stepModal( 1 );
				}
			} );
		}

		document.querySelectorAll( '[data-gallery-strip]' ).forEach( function ( strip ) {
			var images = JSON.parse( strip.getAttribute( 'data-images' ) || '[]' );
			var thumbs = strip.querySelectorAll( '[data-gallery-thumb]' );
			var offset = 0;

			function renderThumbs() {
				thumbs.forEach( function ( thumb, slot ) {
					var index = ( offset + slot ) % images.length;
					var img = thumb.querySelector( 'img' );
					var badge = thumb.querySelector( 'span' );
					if ( img ) {
						img.src = images[ index ];
					}
					if ( badge ) {
						badge.textContent = String( index + 1 ).padStart( 2, '0' );
					}
					thumb.setAttribute( 'data-index', String( index ) );
				} );
			}

			var prevButton = strip.querySelector( '[data-gallery-prev]' );
			var nextButton = strip.querySelector( '[data-gallery-next]' );
			if ( prevButton ) {
				prevButton.addEventListener( 'click', function () {
					offset = ( offset - 1 + images.length ) % images.length;
					renderThumbs();
				} );
			}
			if ( nextButton ) {
				nextButton.addEventListener( 'click', function () {
					offset = ( offset + 1 ) % images.length;
					renderThumbs();
				} );
			}

			thumbs.forEach( function ( thumb ) {
				thumb.addEventListener( 'click', function () {
					openModal( images, parseInt( thumb.getAttribute( 'data-index' ), 10 ) || 0 );
				} );
			} );
		} );

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
