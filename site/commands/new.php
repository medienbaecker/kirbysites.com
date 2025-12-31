<?php

declare(strict_types=1);

use Kirby\CLI\CLI;

return [
	'description' => 'Create a new website page and take a screenshot',
	'args' => [
		'url' => [
			'description' => 'URL of the website',
			'required' => false
		],
		'title' => [
			'description' => 'Title of the website',
			'required' => false
		]
	],
	'options' => [
		'delay' => ['description' => 'Screenshot delay in milliseconds (default: 2000)'],
		'quality' => ['description' => 'WebP quality (0-100, default: 80)']
	],
	'command' => static function (CLI $cli): void {
		// Get URL
		$url = $cli->arg('url');
		if (!$url) {
			$cli->out("Enter the website URL:");
			$url = trim(fgets(STDIN));
			if (!$url) {
				$cli->error("URL is required. Exiting.");
				return;
			}
		}

		// Get title
		$title = $cli->arg('title');
		if (!$title) {
			$cli->out("Enter the website title:");
			$title = trim(fgets(STDIN));
			if (!$title) {
				$cli->error("Title is required. Exiting.");
				return;
			}
		}

		// Normalize URL
		$url = normalizeUrl($url);
		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			$cli->error("Invalid URL format. Exiting.");
			return;
		}

		// Generate slug
		$slug = generateSlug($url);

		// Prepare options
		$delay = (int) ($cli->climate()->arguments->get('delay') ?? 2000);
		$quality = (int) ($cli->climate()->arguments->get('quality') ?? 80);

		try {
			kirby()->impersonate('kirby');

			$page = site()->createChild([
				'slug' => $slug,
				'template' => 'website',
				'content' => [
					'title' => $title,
					'url' => $url,
					'text' => 'by (link: https://medienbaecker.com/ text: Thomas Günther)',
					'date' => date('Y-m-d H:i:s')
				]
			]);

			if (!$page) {
				$cli->error("Failed to create page");
				return;
			}

			$cli->success("🎉 Page created: {$page->slug()}");

			// Take screenshot
			$cli->out("📸 Taking screenshot of: {$url}");

			$result = Screenshot::capture($url, $page->root() . '/frontend.webp', [
				'delay' => $delay,
				'quality' => $quality,
				'format' => 'webp',
				'viewport' => ['width' => 1600, 'height' => 1200]
			]);

			if ($result['success']) {
				$cli->success("📸 Screenshot saved: {$result['filepath']}");
			} else {
				$cli->error("❌ Screenshot failed: {$result['error']}");
				if (!empty($result['output'])) {
					$cli->out("Output: " . implode("\n", $result['output']));
				}
			}
		} catch (Exception $e) {
			$cli->error("Error creating page: " . $e->getMessage());
		}
	}
];

function normalizeUrl(string $url): string
{
	if (!preg_match('/^https?:\/\//', $url)) {
		$url = 'https://' . $url;
	}
	return rtrim($url, '/');
}

function generateSlug(string $url): string
{
	$domain = parse_url($url, PHP_URL_HOST);
	$cleanDomain = strtolower(str_replace('www.', '', $domain));
	$cleanDomain = preg_replace('/[^a-z0-9\-]/', '-', $cleanDomain);
	$cleanDomain = trim($cleanDomain, '-');

	$slug = $cleanDomain;
	$counter = 1;

	while (site()->childrenAndDrafts()->find($slug)) {
		$slug = "{$cleanDomain}-{$counter}";
		$counter++;
	}

	return $slug;
}
