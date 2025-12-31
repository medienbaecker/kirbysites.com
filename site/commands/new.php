<?php

declare(strict_types=1);

use Kirby\CLI\CLI;
use Kirby\Toolkit\Str;

return [
	'description' => 'Create a new website page and take a screenshot',
	'args' => [],
	'command' => static function (CLI $cli): void {
		$cli->out('Enter the website URL:');
		$url = trim(fgets(STDIN));

		if (!$url) {
			$cli->error('URL is required');
			return;
		}

		// Normalize URL
		if (!Str::startsWith($url, 'http')) {
			$url = 'https://' . $url;
		}
		$url = rtrim($url, '/');

		if (!filter_var($url, FILTER_VALIDATE_URL)) {
			$cli->error('Invalid URL');
			return;
		}

		$cli->out('Enter the website title:');
		$title = trim(fgets(STDIN));

		if (!$title) {
			$cli->error('Title is required');
			return;
		}

		// Generate slug from domain
		$domain = parse_url($url, PHP_URL_HOST);
		$slug = Str::slug(str_replace('www.', '', $domain));

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

			// Publish with date prefix
			$page = $page->publish()->changeNum((int) date('Ymd'));

			$cli->success("Page created: {$page->id()}");

			// Take screenshot
			$cli->out("Taking screenshot...");

			$result = Screenshot::capture($url, $page->root() . '/frontend.webp', [
				'delay' => 2000,
				'quality' => 80,
				'format' => 'webp',
				'viewport' => ['width' => 1600, 'height' => 1200]
			]);

			if ($result['success']) {
				$cli->success("Done: {$result['filepath']}");
			} else {
				$cli->error("Screenshot failed: {$result['error']}");
			}
		} catch (Exception $e) {
			$cli->error($e->getMessage());
		}
	}
];
