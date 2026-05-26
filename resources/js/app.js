import './bootstrap';

import Alpine from 'alpinejs';

import { initDashboardCharts } from './dashboard-charts';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
	Alpine.store('theme', {
		dark: false,
		init() {
			const stored = localStorage.getItem('theme');
			this.dark = stored === 'dark';
			document.documentElement.classList.toggle('dark', this.dark);
		},
		toggle() {
			this.dark = !this.dark;
			localStorage.setItem('theme', this.dark ? 'dark' : 'light');
			document.documentElement.classList.toggle('dark', this.dark);
			window.dispatchEvent(new CustomEvent('theme:changed'));
		},
	});
});

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
	initDashboardCharts();
});

window.addEventListener('theme:changed', () => {
	initDashboardCharts();
});
