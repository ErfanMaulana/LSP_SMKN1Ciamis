import './bootstrap';
import '@hotwired/turbo';

const turbo = window.Turbo;

const isTurboEnabledPage = () => document.body?.dataset.turboDrive === 'true';

const syncTurboDriveState = () => {
	if (turbo && turbo.session) {
		turbo.session.drive = isTurboEnabledPage();
	}
};

const initAlpineTree = () => {
	if (window.Alpine && typeof window.Alpine.initTree === 'function') {
		window.Alpine.initTree(document.body);
	}
};

let scrollRevealObserver = null;

const resetScrollRevealObserver = () => {
	if (scrollRevealObserver) {
		scrollRevealObserver.disconnect();
		scrollRevealObserver = null;
	}
};

const initScrollReveal = () => {
	if (!isTurboEnabledPage()) {
		return;
	}

	const revealElements = Array.from(document.querySelectorAll('[data-scroll-reveal]'));
	if (!revealElements.length) {
		return;
	}

	const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	resetScrollRevealObserver();

	revealElements.forEach((element) => {
		const revealDelay = Number(element.dataset.revealDelay);
		if (Number.isFinite(revealDelay)) {
			element.style.setProperty('--reveal-delay', `${Math.max(0, revealDelay)}ms`);
		}

		const revealDuration = Number(element.dataset.revealDuration);
		if (Number.isFinite(revealDuration)) {
			element.style.setProperty('--reveal-duration', `${Math.max(200, revealDuration)}ms`);
		}

		if (prefersReducedMotion) {
			element.classList.add('is-revealed');
		} else {
			element.classList.remove('is-revealed');
		}
	});

	if (prefersReducedMotion) {
		return;
	}

	if (!('IntersectionObserver' in window)) {
		revealElements.forEach((element) => {
			element.classList.add('is-revealed');
		});
		return;
	}

	scrollRevealObserver = new IntersectionObserver(
		(entries, observer) => {
			entries.forEach((entry) => {
				if (!entry.isIntersecting) {
					return;
				}

				entry.target.classList.add('is-revealed');
				observer.unobserve(entry.target);
			});
		},
		{
			threshold: 0.16,
			rootMargin: '0px 0px -12% 0px',
		}
	);

	revealElements.forEach((element) => {
		scrollRevealObserver.observe(element);
	});
};

const initFrontPageEnhancements = () => {
	syncTurboDriveState();
	initAlpineTree();
	initNavPrefetch();
	initScrollReveal();
};

syncTurboDriveState();

const prefetchedUrls = new Set();

const canUseConnectionPrefetch = () => {
	const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;

	if (!connection) {
		return true;
	}

	if (connection.saveData) {
		return false;
	}

	return connection.effectiveType !== 'slow-2g' && connection.effectiveType !== '2g';
};

const normalizeUrl = (href) => {
	try {
		const parsedUrl = new URL(href, window.location.origin);
		parsedUrl.hash = '';
		return parsedUrl.toString();
	} catch {
		return null;
	}
};

const samePageUrl = (url) => {
	const currentUrl = new URL(window.location.href);
	currentUrl.hash = '';
	return currentUrl.toString() === url;
};

const browserSupportsPrefetch = () => {
	const link = document.createElement('link');
	return Boolean(link.relList && link.relList.supports && link.relList.supports('prefetch'));
};

const prefetchUrl = (href) => {
	const normalizedUrl = normalizeUrl(href);

	if (!normalizedUrl || prefetchedUrls.has(normalizedUrl) || samePageUrl(normalizedUrl)) {
		return;
	}

	const parsedUrl = new URL(normalizedUrl);
	if (parsedUrl.origin !== window.location.origin) {
		return;
	}

	prefetchedUrls.add(normalizedUrl);

	if (browserSupportsPrefetch()) {
		const prefetchLink = document.createElement('link');
		prefetchLink.rel = 'prefetch';
		prefetchLink.as = 'document';
		prefetchLink.href = normalizedUrl;
		document.head.appendChild(prefetchLink);
		return;
	}

	fetch(normalizedUrl, {
		credentials: 'same-origin',
	}).catch(() => {
		prefetchedUrls.delete(normalizedUrl);
	});
};

const initNavPrefetch = () => {
	if (!isTurboEnabledPage()) {
		return;
	}

	const navLinks = Array.from(document.querySelectorAll('a[data-nav-prefetch="true"]'));

	if (!navLinks.length || !canUseConnectionPrefetch()) {
		return;
	}

	const uniqueUrls = [...new Set(navLinks.map((link) => link.href).filter(Boolean))];

	const runIdle = window.requestIdleCallback
		? (callback) => window.requestIdleCallback(callback, { timeout: 1200 })
		: (callback) => window.setTimeout(callback, 350);

	runIdle(() => {
		uniqueUrls.forEach((url) => prefetchUrl(url));
	});

	navLinks.forEach((link) => {
		const warmUp = () => prefetchUrl(link.href);

		link.addEventListener('mouseenter', warmUp, { once: true });
		link.addEventListener('touchstart', warmUp, { once: true, passive: true });
		link.addEventListener('focus', warmUp, { once: true });
	});
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initFrontPageEnhancements);
} else {
	initFrontPageEnhancements();
}

document.addEventListener('turbo:load', () => {
	initFrontPageEnhancements();
});
