export class ViewTransitions {
	constructor() {
		this.setupEventListeners();
	}

	setupEventListeners() {
		window.addEventListener("pageswap", (event) => {
			this.handlePageTransition(event, "outgoing");
		});

		window.addEventListener("pagereveal", (event) => {
			this.handlePageTransition(event, "incoming");
		});
	}

	handlePageTransition(event, direction) {
		if (!event.viewTransition) return;

		const targetUrl = this.getTargetUrl(event, direction);
		const imageElement = this.setImageTransitionName(targetUrl);
		this.scheduleTransitionCleanup(imageElement, event.viewTransition);
	}

	getTargetUrl(event, direction) {
		if (direction === "outgoing") {
			return new URL(event.activation.entry.url);
		} else {
			return new URL(navigation.activation.from.url);
		}
	}

	setImageTransitionName(targetUrl) {
		const websiteUid = targetUrl.pathname.split("/").pop();
		const homeImageElement = document.getElementById(websiteUid);

		if (homeImageElement) {
			homeImageElement.style.viewTransitionName = websiteUid;
			return homeImageElement;
		}

		return null;
	}

	async scheduleTransitionCleanup(imageElement, viewTransition) {
		if (imageElement && viewTransition) {
			await viewTransition.finished;
			imageElement.style.viewTransitionName = "";
		}
	}
}
