<?php

declare(strict_types=1);

use Kirby\CLI\CLI;

return [
	'description' => 'Take a screenshot of an existing page',
	'args' => [
		'page' => [
			'description' => 'Page slug (e.g., noranim-fr, google-com)',
			'required' => false
		]
	],
	'options' => [
		'delay' => ['description' => 'Screenshot delay in milliseconds (default: 2000)'],
		'quality' => ['description' => 'Image quality (0-100, default: 85)'],
		'filename' => ['description' => 'Screenshot filename (default: frontend.webp)']
	],
	'command' => static function (CLI $cli): void {
		// Get page slug
		$pageSlug = $cli->arg('page');
		if (!$pageSlug) {
			$cli->out("Enter page slug to screenshot:");
			$pageSlug = trim(fgets(STDIN));
			if (!$pageSlug) {
				$cli->error("Page slug is required. Exiting.");
				return;
			}
		}

		// Get delay
		$delay = (int) ($cli->climate()->arguments->get('delay') ?? 0);
		if (!$delay) {
			$cli->out("Enter delay (default: 2):");
			$delayInput = trim(fgets(STDIN));
			$delay = $delayInput ? (int)($delayInput * 1000) : 2000; // Convert to milliseconds
		}

		$quality = (int) ($cli->climate()->arguments->get('quality') ?? 85);
		$filename = $cli->climate()->arguments->get('filename') ?? 'frontend.webp';

		try {
			kirby()->impersonate('kirby');

			// Find the page
			$page = site()->childrenAndDrafts()->find($pageSlug);
			if (!$page) {
				$cli->error("❌ Page not found: {$pageSlug}");
				return;
			}

			$url = $page->content()->url()->value();
			$filepath = $page->root() . '/' . $filename;

			$cli->out("📸 Taking screenshot of: {$url}");

			$result = Screenshot::capture($url, $filepath, [
				'delay' => $delay,
				'quality' => $quality,
				'format' => pathinfo($filename, PATHINFO_EXTENSION),
				'viewport' => ['width' => 1600, 'height' => 1200]
			]);

			if ($result['success']) {
				$cli->success("🎉 Screenshot saved: {$filepath}");
			} else {
				$cli->error("❌ Screenshot failed: {$result['error']}");
				if (!empty($result['output'])) {
					$cli->out("Output: " . implode("\n", $result['output']));
				}
			}
		} catch (Exception $e) {
			$cli->error("Error taking screenshot: " . $e->getMessage());
		}
	}
];
