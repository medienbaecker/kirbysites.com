<?php

declare(strict_types=1);

use Kirby\CLI\CLI;

return [
	'description' => '🔗 Check if panel URLs are accessible',
	'options' => [
		'timeout' => [
			'description' => 'Request timeout in seconds'
		]
	],
	'command' => static function (CLI $cli): void {
		try {
			kirby()->impersonate('kirby');
			
			$timeout = (int) ($cli->climate()->arguments->get('timeout') ?? 10);

			$pages = site()->pages()->index();
			$cli->out("🔍 Checking panel access for {$pages->count()} pages...");

			$accessible = 0;
			$failed = 0;
			$errors = [];

			foreach ($pages as $page) {
				$url = $page->content()->url()->value();

			// Remove trailing slash if present
			if (substr($url, -1) === '/') {
				$url = rtrim($url, '/');
			}

			// Append /panel
			$url .= '/panel';

			$cli->out("Checking: {$url}");

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Kirby Panel Checker');

			curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$error = curl_error($ch);
			curl_close($ch);

			if ($error) {
				$cli->error("✗ {$page->uid()}: cURL error - {$error}");
				$failed++;
				$errors[] = "{$page->uid()}: {$error}";
			} elseif (in_array($httpCode, [200, 302, 401, 403])) {
				$status = $httpCode === 200 ? 'accessible' : 'found (auth/redirect)';
				$cli->success("✓ {$page->uid()}: HTTP {$httpCode} - {$status}");
				$accessible++;
			} else {
				$cli->error("✗ {$page->uid()}: HTTP {$httpCode}");
				$failed++;
				$errors[] = "{$page->uid()}: HTTP {$httpCode}";
			}
		}

			$cli->out("\n📊 Summary:");
			$cli->success("✓ Accessible: {$accessible}");
			$cli->error("✗ Failed: {$failed}");

			if (!empty($errors)) {
				$cli->out("\n⚠️ Errors:");
				foreach ($errors as $error) {
					$cli->out($error);
				}
			}
		} catch (Exception $e) {
			$cli->error("Error checking panels: " . $e->getMessage());
		}
	}
];
