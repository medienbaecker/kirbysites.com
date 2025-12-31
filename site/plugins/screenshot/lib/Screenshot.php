<?php

declare(strict_types=1);

class Screenshot
{
	private static function getDefaultOptions(): array
	{
		return kirby()->option('screenshot', [
			'quality' => 85,
			'delay' => 0,
			'format' => 'png',
			'viewport' => ['width' => 1920, 'height' => 1080]
		]);
	}

	public static function capture(
		string $url,
		string $filepath,
		array $options = []
	): array {
		$options = array_merge(self::getDefaultOptions(), $options);
		return self::takeScreenshot($url, $filepath, $options);
	}

	private static function takeScreenshot(
		string $url,
		string $filepath,
		array $options
	): array {
		if (!isset($options['format'])) {
			$ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
			$options['format'] = match ($ext) {
				'jpg', 'jpeg' => 'jpeg',
				'webp' => 'webp',
				default => 'png'
			};
		}

		$url = rtrim($url, '/');
		$dir = dirname($filepath);
		
		if (!is_dir($dir)) {
			mkdir($dir, 0755, true);
		}

		$scriptPath = __DIR__ . '/../screenshot.js';
		$command = sprintf(
			'node %s %s %s %d %d %d %d %s 2>&1',
			escapeshellarg($scriptPath),
			escapeshellarg($url),
			escapeshellarg($filepath),
			$options['delay'],
			$options['quality'],
			$options['viewport']['width'],
			$options['viewport']['height'],
			escapeshellarg($options['format'])
		);

		exec($command, $output, $returnCode);

		if ($returnCode === 0 && file_exists($filepath)) {
			return [
				'success' => true,
				'filepath' => $filepath,
				'output' => $output
			];
		}

		$error = "Screenshot failed. Return code: {$returnCode}";
		error_log("Screenshot failed for {$url}: {$error}. Output: " . implode(' ', $output));
		
		return [
			'success' => false,
			'error' => $error,
			'filepath' => $filepath,
			'output' => $output
		];
	}
}
