export class OldBrowserChecker {
	constructor() {
		this.bannerKey = "oldBrowserBanner";

		this.init();
	}

	/**
	 * 🚀 Initialize the old browser checker
	 * @returns {void}
	 */
	init() {
		if (
			typeof CSSLayerBlockRule !== "function" &&
			!localStorage.getItem(this.bannerKey)
		) {
			this.showBanner();
		}
	}

	/**
	 * 🚩 Create and show the banner
	 * @private
	 */
	showBanner() {
		const banner = document.createElement("a");
		banner.style =
			"position:fixed;top:0;left:0;width:100%;padding:1rem;background:red;text-align:center;color:#fff;z-index:9999999;cursor:pointer";

		banner.innerHTML = this.getBannerMessage();

		banner.addEventListener("click", () => this.dismissBanner(banner));

		document.body.appendChild(banner);
	}

	/**
	 * 🌐 Get the appropriate banner message based on language
	 * @private
	 * @returns {string} The banner message
	 */
	getBannerMessage() {
		const lang = navigator.language || navigator.userLanguage;
		return lang.startsWith("de")
			? "Sie verwenden einen veralteten Browser. Die Website kann nicht korrekt angezeigt werden."
			: "You are using an outdated browser. The website can not be displayed correctly.";
	}

	/**
	 * 🖱️ Dismiss the banner
	 * @private
	 * @param {HTMLElement} banner - The banner element to remove
	 */
	dismissBanner(banner) {
		banner.remove();
		localStorage.setItem(this.bannerKey, "dismissed");
	}
}
