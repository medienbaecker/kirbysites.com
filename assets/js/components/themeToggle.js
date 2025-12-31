export class ThemeToggle {
	constructor() {
		this.init();
	}

	init() {
		this.applyInitialTheme();
		this.bindEvents();
	}

	applyInitialTheme() {
		const savedTheme = localStorage.getItem('theme');
		
		if (savedTheme) {
			document.documentElement.setAttribute('data-theme', savedTheme);
		}
	}

	getSystemPreference() {
		return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
	}

	getCurrentTheme() {
		const dataTheme = document.documentElement.getAttribute('data-theme');
		return dataTheme || this.getSystemPreference();
	}

	setTheme(theme) {
		document.documentElement.setAttribute('data-theme', theme);
		localStorage.setItem('theme', theme);
		this.updateToggleButton();
	}

	toggleTheme() {
		const currentTheme = this.getCurrentTheme();
		const newTheme = currentTheme === 'light' ? 'dark' : 'light';
		this.setTheme(newTheme);
	}

	updateToggleButton() {
		const button = document.querySelector('[data-theme-toggle]');
		if (!button) return;

		const currentTheme = this.getCurrentTheme();
		const isLight = currentTheme === 'light';
		
		button.setAttribute('aria-pressed', isLight.toString());
		button.setAttribute('aria-label', `Switch to ${isLight ? 'dark' : 'light'} mode`);
	}

	bindEvents() {
		document.addEventListener('click', (e) => {
			if (e.target.closest('[data-theme-toggle]')) {
				e.preventDefault();
				this.toggleTheme();
			}
		});

		window.matchMedia('(prefers-color-scheme: light)').addEventListener('change', () => {
			if (!localStorage.getItem('theme')) {
				this.updateToggleButton();
			}
		});

		this.updateToggleButton();
	}
}