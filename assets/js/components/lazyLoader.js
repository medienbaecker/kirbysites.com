export class LazyLoader {
	constructor() {
		this.init();
	}

	init() {
		document.querySelectorAll('[loading="lazy"]').forEach((element) => {
			const animateIn = () => {
				element.style.opacity = "1";
			};

			if (element.complete) {
				animateIn();
			} else {
				element.addEventListener("load", animateIn);
			}
		});
	}
}
