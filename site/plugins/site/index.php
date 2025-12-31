<?php

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Url;
use Kirby\Filesystem\F;

require_once __DIR__ . '/helpers.php';

Kirby::plugin('my/site', [

	/* -------------------------------------------------- */
	/* Cachebuster */
	/* -------------------------------------------------- */

	'components' => [
		/**
		 * Custom CSS component with cache busting
		 */
		'css' => function ($kirby, $url, $options) {
			if ($url === '@auto') {
				if (!$url = Url::toTemplateAsset('styles/templates', 'css')) {
					return null;
				}
			}
			return $url . '?v=' . F::modified($url);
		},

		/**
		 * Custom JS component with cache busting
		 */
		'js' => function ($kirby, $url, $options) {
			if ($url === '@auto') {
				if (!$url = Url::toTemplateAsset('scripts/templates', 'js')) {
					return null;
				}
			}
			return $url . '?v=' . F::modified($url);
		}
	],

	/* -------------------------------------------------- */
	/* 📃 Site methods */
	/* -------------------------------------------------- */

	'siteMethods' => [
		/**
		 * Get the current year.
		 *
		 * @return string The current year in 'YYYY' format.
		 */
		'currentYear' => function (): string {
			return date('Y');
		}
	],

	/* -------------------------------------------------- */
	/* 📃 Page methods */
	/* -------------------------------------------------- */

	'pageMethods' => [],
]);
