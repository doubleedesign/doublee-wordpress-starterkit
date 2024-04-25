import { animateIntoView } from './vendor/animate-into-view.js';

document.addEventListener('DOMContentLoaded', function() {
	animateIntoView({
		selector: '.site-header, .block__page-header, .block__cover, .block__media-text, .block__copy, .block__columns-wrapper, .block__in-this-section, .block__table, .site-footer > .row',
		threshold: 0.5,
		type: 'fadeIn',
	});

	animateIntoView({
		selector: '.pseudo-block__pagination .row, .pseudo-block__article-wrapper',
		threshold: 0.25,
		type: 'fadeIn',
	});

	animateIntoView({
		selector: '.block__call-to-action .entry-content, .block__latest-posts > .row',
		threshold: 0.5,
		type: 'fadeInUp',
	});

	animateIntoView({
		selector: '.single-post-section',
		threshold: 0.1,
		type: 'fadeIn',
	});
});
