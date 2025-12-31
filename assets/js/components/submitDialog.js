export class SubmitDialog {
	constructor() {
		this.dialog = document.getElementById("submit-dialog");
		this.openButton = document.querySelector("[data-submit-dialog]");
		this.closeButton = document.querySelector(".submit-dialog__close");

		if (!this.dialog || !this.openButton) return;

		this.openButton.addEventListener("click", () => this.open());
		this.closeButton?.addEventListener("click", () => this.close());
	}

	open() {
		this.dialog.showModal();
	}

	close() {
		this.dialog.close();
	}
}
