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
		const format = process.argv[8] || 'png';

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
		if (format === 'jpeg' || format === 'webp') {
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

/**
 * Extract colors from the rendered page
 */
async function extractColorsFromPage(page) {
	return await page.evaluate(() => {
		const colors = new Map();

		// Helper function to convert RGB to hex
		function rgbToHex(r, g, b) {
			return (
				"#" +
				[r, g, b]
					.map((x) => {
						const hex = x.toString(16);
						return hex.length === 1 ? "0" + hex : hex;
					})
					.join("")
			);
		}

		// Helper function to parse color values
		function parseColor(colorStr) {
			if (!colorStr || colorStr === "transparent" || colorStr === "none")
				return null;

			// Handle hex colors
			if (colorStr.startsWith("#")) {
				return colorStr.toLowerCase();
			}

			// Handle rgb/rgba colors
			const rgbMatch = colorStr.match(
				/rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*([\d.]+))?\)/
			);
			if (rgbMatch) {
				const [, r, g, b, a] = rgbMatch;
				// Skip transparent colors
				if (a !== undefined && parseFloat(a) < 0.1) return null;
				return rgbToHex(parseInt(r), parseInt(g), parseInt(b));
			}

			// Handle named colors by creating a temporary element
			const tempEl = document.createElement("div");
			tempEl.style.color = colorStr;
			document.body.appendChild(tempEl);
			const computed = window.getComputedStyle(tempEl).color;
			document.body.removeChild(tempEl);

			const namedRgbMatch = computed.match(/rgba?\((\d+),\s*(\d+),\s*(\d+)/);
			if (namedRgbMatch) {
				const [, r, g, b] = namedRgbMatch;
				return rgbToHex(parseInt(r), parseInt(g), parseInt(b));
			}

			return null;
		}

		// Helper function to check if color is valid (not too light/dark/gray)
		function isValidColor(hex) {
			if (!hex || hex === "#000000" || hex === "#ffffff") return false;

			const r = parseInt(hex.slice(1, 3), 16);
			const g = parseInt(hex.slice(3, 5), 16);
			const b = parseInt(hex.slice(5, 7), 16);

			const brightness = (r + g + b) / 3;
			const contrast = Math.max(r, g, b) - Math.min(r, g, b);

			// Filter out colors that are too light, dark, or have low contrast
			return brightness >= 30 && brightness <= 220 && contrast >= 30;
		}

		// Get all visible elements in the viewport
		const elements = document.querySelectorAll("*");

		elements.forEach((element) => {
			const rect = element.getBoundingClientRect();
			const isVisible =
				rect.width > 0 &&
				rect.height > 0 &&
				rect.top < window.innerHeight &&
				rect.bottom > 0 &&
				rect.left < window.innerWidth &&
				rect.right > 0;

			if (!isVisible) return;

			const styles = window.getComputedStyle(element);

			// Extract background colors
			const bgColor = parseColor(styles.backgroundColor);
			if (bgColor && isValidColor(bgColor)) {
				colors.set(
					bgColor,
					(colors.get(bgColor) || 0) + rect.width * rect.height
				);
			}

			// Extract text colors
			const textColor = parseColor(styles.color);
			if (textColor && isValidColor(textColor)) {
				colors.set(
					textColor,
					(colors.get(textColor) || 0) + rect.width * rect.height * 0.5
				);
			}

			// Extract border colors
			[
				"borderTopColor",
				"borderRightColor",
				"borderBottomColor",
				"borderLeftColor",
			].forEach((prop) => {
				const borderColor = parseColor(styles[prop]);
				if (borderColor && isValidColor(borderColor)) {
					colors.set(
						borderColor,
						(colors.get(borderColor) || 0) + rect.width * rect.height * 0.2
					);
				}
			});

			// Extract box-shadow colors
			const boxShadow = styles.boxShadow;
			if (boxShadow && boxShadow !== "none") {
				const shadowColorMatch = boxShadow.match(/rgba?\([^)]+\)/g);
				if (shadowColorMatch) {
					shadowColorMatch.forEach((shadowColor) => {
						const color = parseColor(shadowColor);
						if (color && isValidColor(color)) {
							colors.set(
								color,
								(colors.get(color) || 0) + rect.width * rect.height * 0.1
							);
						}
					});
				}
			}
		});

		// Sort colors by frequency/importance and return top 5
		const sortedColors = Array.from(colors.entries())
			.sort((a, b) => b[1] - a[1])
			.slice(0, 5)
			.map(([color]) => color);

		return sortedColors;
	});
}

// Execute the screenshot function
takeScreenshot();