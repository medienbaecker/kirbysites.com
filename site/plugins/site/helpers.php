<?php

use Kirby\Content\Field;
use Kirby\Cms\Html;
use Kirby\Toolkit\Str;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;

/**
 * Generate preload link tags for all font files in a directory
 *
 * @param string $directory Directory path relative to site root (default: 'assets/fonts')
 * @param string $fileType Font file type to include (default: 'woff2')
 * @return string HTML string with all preload link tags
 */
function preloadFonts(string $directory = 'assets/fonts', string $fileType = 'woff2'): string
{

	$fontDir = kirby()->root() . '/' . $directory;
	$output = '';

	// Check if directory exists
	if (!Dir::exists($fontDir)) {
		return $output;
	}

	// Get all files in the directory
	$fontFiles = Dir::files($fontDir);

	// Filter for the specified file type
	foreach ($fontFiles as $file) {
		if (F::extension($file) === $fileType) {
			$fontUrl = $directory . '/' . basename($file);
			$output .= '<link rel="preload" href="' . url($fontUrl) . '" as="font" type="font/' . $fileType . '" crossorigin>' . PHP_EOL;
		}
	}

	return $output;
}


/**
 * Checks if at least one of the given fields has content
 *
 * @param Kirby\Content\Field ...$fields One or more Field objects to check
 * @return bool True if any field has content, false if all are empty
 */
function anyNotEmpty(...$fields): bool
{
	return array_reduce($fields, function (bool $hasContent, $field) {
		if (!($field instanceof Field)) {
			return $hasContent;
		}

		return $hasContent || !$field->isEmpty();
	}, false);
}

/**
 * Generate an SVG with custom class and aria attributes
 *
 * @param string $filepath  Path to the SVG file
 * @param string $class     CSS class to add to the SVG (optional)
 * @param string $ariaLabel Aria label for accessibility (optional)
 * @return string|null      Modified SVG string or null if SVG not found
 */
function classySvg(string $filepath, string $class = '', string $ariaLabel = ''): ?string
{
	if ($svg = svg($filepath)) {
		$attributes = [];

		// Set aria attributes
		if ($ariaLabel !== '') {
			$attributes['role'] = 'img';
			$attributes['aria-label'] = $ariaLabel;
		} else {
			$attributes['aria-hidden'] = 'true';
		}

		// Add class attribute if provided
		if ($class !== '') {
			$attributes['class'] = $class;
		}

		// Generate attribute string
		$attrString = implode(' ', array_map(
			fn($key, $value) => Html::attr($key, $value),
			array_keys($attributes),
			$attributes
		));

		// Inject custom attributes into the SVG tag
		return Str::replace(
			$svg,
			'<svg',
			"<svg {$attrString}"
		);
	}

	return null;
}
