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

		// Shared patient lightbox; strings are supported for legacy gallery markup.
        var modal = document.querySelector('[data-gallery-modal]');
        var activeImages = [], activeIndex = 0, returnFocus, previousOverflow, revealedImages = new Set();
        function mediaItem(item) { return typeof item === 'string' ? {url: item, preview: item, type: 'image', title: '', description: ''} : item; }
        function safeUrl(url) { try { var parsed = new URL(url, location.href); return /^https?:$/.test(parsed.protocol) ? parsed.href : ''; } catch (e) { return ''; } }
        function showModalImage() {
            if (!modal || !activeImages.length) return;
            var item = mediaItem(activeImages[activeIndex]);
            var image = modal.querySelector('[data-gallery-modal-image]');
            var video = modal.querySelector('[data-gallery-modal-video]');
            var link = modal.querySelector('[data-gallery-modal-video-link]');
            var sensitive = Boolean(item.sensitive) && !revealedImages.has(activeIndex);
            var warning = modal.querySelector('[data-gallery-modal-sensitive]');
            video.pause(); video.removeAttribute('src'); video.load();
            image.hidden = item.type !== 'image'; video.hidden = item.type !== 'video'; link.hidden = item.type !== 'link';
            image.removeAttribute('src');
            if (item.type === 'image') { image.src = safeUrl(item.url); image.alt = item.title || ''; }
            image.classList.toggle('is-sensitive', sensitive);
            if (item.type === 'video' && !sensitive) { video.src = safeUrl(item.url); }
            if (item.type === 'link') { link.href = safeUrl(item.url); }
            if (warning) warning.hidden = !sensitive;
            modal.querySelector('[data-gallery-modal-title]').textContent = item.title || '';
            modal.querySelector('[data-gallery-modal-description]').textContent = item.description || '';
            var patientLink = modal.querySelector('[data-gallery-modal-patient]');
            patientLink.hidden = !safeUrl(item.patientUrl); patientLink.href = safeUrl(item.patientUrl);
            modal.querySelector('[data-gallery-modal-count]').textContent = (activeIndex + 1) + ' / ' + activeImages.length;
        }
        function openModal(images, index, trigger) {
            if (!modal || !images.length) return;
            activeImages = images; activeIndex = index; revealedImages = new Set(); returnFocus = trigger; previousOverflow = document.body.style.overflow;
            showModalImage(); modal.hidden = false; document.body.style.overflow = 'hidden'; modal.querySelector('[data-gallery-close]').focus();
        }
        function closeModal() {
            if (!modal) return;
            var video = modal.querySelector('video'); video.pause(); video.removeAttribute('src'); video.load();
            modal.hidden = true; document.body.style.overflow = previousOverflow || ''; if (returnFocus) returnFocus.focus();
        }
        function stepModal(direction) { if (!activeImages.length) return; activeIndex = (activeIndex + direction + activeImages.length) % activeImages.length; showModalImage(); }
        if (modal) {
            modal.querySelector('[data-gallery-modal-reveal]').addEventListener('click', function () { revealedImages.add(activeIndex); showModalImage(); modal.querySelector('[data-gallery-close]').focus(); });
            modal.querySelector('[data-gallery-close]').addEventListener('click', closeModal);
            modal.querySelector('[data-gallery-modal-prev]').addEventListener('click', function () { stepModal(-1); });
            modal.querySelector('[data-gallery-modal-next]').addEventListener('click', function () { stepModal(1); });
            modal.addEventListener('click', function (event) { if (event.target === modal) closeModal(); });
            document.addEventListener('keydown', function (event) {
                if (modal.hidden) return;
                if (event.key === 'Escape') closeModal();
                else if (event.key === 'ArrowLeft') stepModal(-1);
                else if (event.key === 'ArrowRight') stepModal(1);
                else if (event.key === 'Tab') {
                    var focusable = Array.from(modal.querySelectorAll('button,a[href],video[controls]')).filter(function (el) { return el.getClientRects().length && !el.disabled; });
                    var first = focusable[0], last = focusable[focusable.length-1];
                    if (event.shiftKey && document.activeElement === first) { event.preventDefault(); last.focus(); }
                    else if (!event.shiftKey && document.activeElement === last) { event.preventDefault(); first.focus(); }
                }
            });
        }
        document.querySelectorAll('[data-gallery-strip]').forEach(function (strip) {
            var images; try { images = JSON.parse(strip.getAttribute('data-images') || '[]'); } catch (e) { return; }
            if (!images.length) return;
            var thumbs = strip.querySelectorAll('[data-gallery-thumb]'), offset = 0;
            function renderThumbs() {
                thumbs.forEach(function (thumb, slot) {
                    var index = (offset + slot) % images.length, item = mediaItem(images[index]);
                    var img = thumb.querySelector('img');
                    if (!img && item.preview) { img = document.createElement('img'); img.className = 'fill-img'; thumb.prepend(img); }
                    if (img) { img.hidden = !item.preview; if(item.preview) img.src = safeUrl(item.preview); img.alt = item.title || ''; }
                    thumb.classList.toggle('is-sensitive', Boolean(item.sensitive));
                    var badge = thumb.querySelector('span:last-child'); if (badge) badge.textContent = (item.type === 'image' ? '' : '▶ ') + (item.title || String(index+1));
                    thumb.setAttribute('aria-label', item.title || String(index+1)); thumb.setAttribute('data-index', String(index));
                });
            }
            var prev = strip.querySelector('[data-gallery-prev]'), next = strip.querySelector('[data-gallery-next]');
            if (prev) prev.addEventListener('click', function () { offset = (offset-1+images.length)%images.length; renderThumbs(); });
            if (next) next.addEventListener('click', function () { offset = (offset+1)%images.length; renderThumbs(); });
            thumbs.forEach(function (thumb) { thumb.addEventListener('click', function () { openModal(images, parseInt(thumb.getAttribute('data-index'),10)||0, thumb); }); });
        });

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
