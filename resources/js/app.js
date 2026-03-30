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
	document.addEventListener('DOMContentLoaded', initNavPrefetch);
} else {
	initNavPrefetch();
}

document.addEventListener('turbo:load', () => {
	syncTurboDriveState();
	initAlpineTree();
	initNavPrefetch();
});
