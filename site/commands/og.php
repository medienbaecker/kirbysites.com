<?php

declare(strict_types=1);

use Kirby\CLI\CLI;

return [
	'description' => 'Take a screenshot of kirbysites.test for og.png',
	'options' => [
		'delay' => ['description' => 'Screenshot delay in milliseconds (default: 0)'],
		'quality' => ['description' => 'PNG quality (0-100, default: 85)']
	],
	'command' => static function (CLI $cli): void {
		try {
			$delay = (int) ($cli->climate()->arguments->get('delay') ?? 0);
			$quality = (int) ($cli->climate()->arguments->get('quality') ?? 85);

			$url = 'http://kirbysites.test';
			$filepath = kirby()->root('content') . '/og.png';

			$cli->out("📸 Taking screenshot of: {$url}");

			$result = Screenshot::capture($url, $filepath, [
				'delay' => $delay,
				'quality' => $quality,
				'format' => 'png',
				'viewport' => ['width' => 1200, 'height' => 630]
			]);

			if ($result['success']) {
				$cli->success("🎉 OG screenshot saved: {$filepath}");
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
