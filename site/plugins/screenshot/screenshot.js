const puppeteer = require("puppeteer");

/**
 * Screenshot Plugin for Kirby CMS
 *
 * Modern Puppeteer-based screenshot capture with intelligent
 * cookie banner removal and optimized settings.
 */

async function takeScreenshot() {
	try {
		// Parse command line arguments
		const url = process.argv[2];
		const filepath = process.argv[3];
		const delay = parseInt(process.argv[4]) || 2000;
		const quality = parseInt(process.argv[5]) || 85;
		const width = parseInt(process.argv[6]) || 1920;
		const height = parseInt(process.argv[7]) || 1080;
		const format = process.argv[8] || "png";

		// Validate arguments
		if (!url || !filepath) {
			console.error(
				"Usage: node screenshot.js <url> <filepath> [delay] [quality] [width] [height] [format]"
			);
			process.exit(1);
		}

		// Launch browser with optimized settings
		console.log("Launching browser...");
		const browser = await puppeteer.launch({
			headless: "new", // Use new headless mode
			args: [
				"--no-sandbox",
				"--disable-setuid-sandbox",
				"--disable-dev-shm-usage",
				"--disable-accelerated-2d-canvas",
				"--no-first-run",
				"--no-zygote",
				"--disable-gpu",
				"--disable-web-security",
				"--disable-features=VizDisplayCompositor",
			],
			defaultViewport: null,
			timeout: 10000, // Browser launch timeout
		});
		console.log("Browser launched successfully");

		const page = await browser.newPage();

		// Set viewport and user agent
		await page.setViewport({
			width: width,
			height: height,
			deviceScaleFactor: 1,
		});

		await page.setUserAgent(
			"Mozilla/5.0 (compatible; medienbaecker-screenshot/1.0; +https://github.com/medienbaecker/screenshot)"
		);

		// Navigate to page with timeout
		console.log(`Navigating to: ${url}`);
		await page.goto(url, {
			waitUntil: "domcontentloaded", // Even less strict - just wait for DOM
			timeout: 30000, // Increased timeout
		});
		console.log("Page loaded successfully");

		// Wait a bit more for any dynamic content
		await new Promise((resolve) => setTimeout(resolve, 1000));

		// Remove cookie banners and unwanted elements
		await removeCookieBanners(page);

		// Wait for specified delay
		if (delay > 0) {
			await new Promise((resolve) => setTimeout(resolve, delay));
		}

		// Take screenshot
		const screenshotOptions = {
			path: filepath,
			type: format,
			fullPage: false, // Viewport screenshot only
			optimizeForSpeed: false,
		};

		// Add quality option for JPEG and WebP formats
		if (format === "jpeg" || format === "webp") {
			screenshotOptions.quality = quality;
		}

		await page.screenshot(screenshotOptions);

		await browser.close();
		console.log(`Screenshot saved: ${filepath}`);

		process.exit(0);
	} catch (error) {
		console.error("Screenshot error:", error.message);
		process.exit(1);
	}
}

/**
 * Remove cookie banners and other unwanted elements
 */
async function removeCookieBanners(page) {
	await page.evaluate(() => {
		// Comprehensive list of cookie banner selectors
		const selectors = [
			// Generic patterns
			'[id*="cookie" i]',
			'[class*="cookie" i]',
			'[id*="consent" i]',
			'[class*="consent" i]',
			'[id*="gdpr" i]',
			'[class*="gdpr" i]',
			'[id*="privacy" i]',
			'[class*="privacy" i]',
			'[data-testid*="cookie" i]',
			'[data-testid*="consent" i]',

			// Specific cookie banner libraries
			".cookie-banner",
			".js-cookie-consent",
			"#onetrust-consent-sdk",
			"#CybotCookiebotDialog",
			".cc-window",
			".cc-banner",
			"#cookieChoiceInfo",
			"#usercentrics-root",
			".legalmonster-cleanslate",
			"#glowCookies-banner",
			".cookie-notice",
			".cookie-banner",
			".privacy-notice",
			".gdpr-notice",
			".cookiebar",
			"#cookie-law-info-bar",
			"#shopify-pc__banner",

			// ARIA patterns
			'[role="dialog"][aria-label*="cookie" i]',
			'[role="dialog"][aria-label*="consent" i]',
			'[role="banner"][aria-label*="cookie" i]',
			'[role="alertdialog"][aria-label*="cookie" i]',

			// Common overlay patterns
			'div[style*="position: fixed"][style*="z-index"]',
			"dialog[open]",

			// Common notification bars
			".notification-bar",
			".alert-bar",
			".banner-notification",
		];

		let removedCount = 0;

		selectors.forEach((selector) => {
			try {
				const elements = document.querySelectorAll(selector);
				elements.forEach((element) => {
					// Additional checks to avoid removing legitimate content
					const rect = element.getBoundingClientRect();
					const isVisible = rect.width > 0 && rect.height > 0;
					const hasKeywords =
						element.textContent.toLowerCase().includes("cookie") ||
						element.textContent.toLowerCase().includes("consent") ||
						element.textContent.toLowerCase().includes("privacy");

					if (
						isVisible &&
						(hasKeywords ||
							selector.includes("cookie") ||
							selector.includes("consent"))
					) {
						element.remove();
						removedCount++;
					}
				});
			} catch (e) {
				// Ignore invalid selectors
			}
		});

		if (removedCount > 0) {
			console.log(`Removed ${removedCount} cookie banner elements`);
		}
	});
}

// Execute the screenshot function
takeScreenshot();
