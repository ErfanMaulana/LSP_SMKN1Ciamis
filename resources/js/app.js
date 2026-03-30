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
let counterObserver = null;

const resetScrollRevealObserver = () => {
	if (scrollRevealObserver) {
		scrollRevealObserver.disconnect();
		scrollRevealObserver = null;
	}
};

const resetCounterObserver = () => {
	if (counterObserver) {
		counterObserver.disconnect();
		counterObserver = null;
	}
};

const initScrollReveal = () => {
	if (!isTurboEnabledPage()) {
		return;
	}

	const staggerGroups = Array.from(document.querySelectorAll('[data-reveal-stagger]'));
	staggerGroups.forEach((group) => {
		const rawStep = Number(group.dataset.revealStagger);
		const rawStart = Number(group.dataset.revealStart);
		const staggerStep = Number.isFinite(rawStep) ? Math.min(220, Math.max(20, rawStep)) : 90;
		const staggerStart = Number.isFinite(rawStart) ? Math.max(0, rawStart) : 0;
		const staggerItems = Array.from(group.querySelectorAll('[data-reveal-item]'));

		staggerItems.forEach((item, index) => {
			if (item.dataset.revealDelay) {
				return;
			}

			item.dataset.revealDelay = String(staggerStart + index * staggerStep);
		});
	});

	const revealElements = Array.from(document.querySelectorAll('[data-scroll-reveal]'));
	if (!revealElements.length) {
		return;
	}

	const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	resetScrollRevealObserver();

	revealElements.forEach((element) => {
		const revealDelay = Number(element.dataset.revealDelay);
		if (Number.isFinite(revealDelay)) {
			const normalizedDelay = Math.min(500, Math.max(0, revealDelay));
			element.style.setProperty('--reveal-delay', `${normalizedDelay}ms`);
		}

		const revealDuration = Number(element.dataset.revealDuration);
		if (Number.isFinite(revealDuration)) {
			const normalizedDuration = Math.min(1100, Math.max(260, revealDuration));
			element.style.setProperty('--reveal-duration', `${normalizedDuration}ms`);
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

const formatCounterValue = (value) => {
	const safeValue = Number.isFinite(value) ? Math.max(0, Math.floor(value)) : 0;
	return new Intl.NumberFormat('id-ID').format(safeValue);
};

const animateCounter = (counter) => {
	if (counter.dataset.counterDone === 'true' || counter.dataset.counterRunning === 'true') {
		return;
	}

	const target = Number(counter.dataset.target);
	const suffix = counter.dataset.suffix ?? '';
	const rawDuration = Number(counter.dataset.counterDuration);
	const duration = Number.isFinite(rawDuration) ? Math.min(2400, Math.max(700, rawDuration)) : 1300;

	if (!Number.isFinite(target) || target <= 0) {
		counter.textContent = `${formatCounterValue(target)}${suffix}`;
		counter.dataset.counterDone = 'true';
		counter.dataset.counterRunning = 'false';
		return;
	}

	counter.dataset.counterRunning = 'true';
	const startTime = performance.now();

	const tick = (currentTime) => {
		const progress = Math.min((currentTime - startTime) / duration, 1);
		const easedProgress = 1 - Math.pow(1 - progress, 3);
		const currentValue = Math.floor(target * easedProgress);

		counter.textContent = `${formatCounterValue(currentValue)}${suffix}`;

		if (progress < 1) {
			requestAnimationFrame(tick);
			return;
		}

		counter.textContent = `${formatCounterValue(target)}${suffix}`;
		counter.dataset.counterDone = 'true';
		counter.dataset.counterRunning = 'false';
	};

	requestAnimationFrame(tick);
};

const initCounters = () => {
	if (!isTurboEnabledPage()) {
		return;
	}

	const counters = Array.from(document.querySelectorAll('.counter[data-target]'));
	if (!counters.length) {
		return;
	}

	const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	resetCounterObserver();

	counters.forEach((counter) => {
		const suffix = counter.dataset.suffix ?? '';
		counter.dataset.counterDone = 'false';
		counter.dataset.counterRunning = 'false';
		counter.textContent = `0${suffix}`;
	});

	if (prefersReducedMotion) {
		counters.forEach((counter) => {
			const target = Number(counter.dataset.target);
			const suffix = counter.dataset.suffix ?? '';
			counter.textContent = `${formatCounterValue(target)}${suffix}`;
			counter.dataset.counterDone = 'true';
			counter.dataset.counterRunning = 'false';
		});
		return;
	}

	if (!('IntersectionObserver' in window)) {
		counters.forEach((counter) => {
			animateCounter(counter);
		});
		return;
	}

	counterObserver = new IntersectionObserver(
		(entries, observer) => {
			entries.forEach((entry) => {
				if (!entry.isIntersecting) {
					return;
				}

				animateCounter(entry.target);
				observer.unobserve(entry.target);
			});
		},
		{
			threshold: 0.45,
			rootMargin: '0px 0px -10% 0px',
		}
	);

	counters.forEach((counter) => {
		counterObserver.observe(counter);
	});
};

const initFrontPageEnhancements = () => {
	if (!document.body) {
		return;
	}

	const currentViewKey = `${window.location.pathname}${window.location.search}`;
	if (document.body.dataset.frontEnhancementsInit === currentViewKey) {
		return;
	}

	document.body.dataset.frontEnhancementsInit = currentViewKey;
	syncTurboDriveState();
	initAlpineTree();
	initNavPrefetch();
	initScrollReveal();
	initCounters();
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
