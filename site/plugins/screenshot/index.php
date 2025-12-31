<?php

use Kirby\Cms\App as Kirby;

require_once __DIR__ . '/lib/Screenshot.php';

Kirby::plugin('medienbaecker/screenshot', [
    'options' => [
        'screenshot' => [
            'quality' => 85,
            'delay' => 0,
            'format' => 'png',
            'viewport' => ['width' => 1920, 'height' => 1080]
        ]
    ],

    'pageMethods' => [
        'screenshot' => function (string $filename = 'screenshot.png', array $options = []) {
            return Screenshot::captureForPage($this, $filename, $options);
        }
    ],

    'siteMethods' => [
        'screenshot' => function (string $url, string $filepath, array $options = []) {
            return Screenshot::capture($url, $filepath, $options);
        }
    ]
]);

/**
 * Global helper function for taking screenshots
 */
function screenshot(string $url, string $filepath, array $options = []): array
{
    return Screenshot::capture($url, $filepath, $options);
}