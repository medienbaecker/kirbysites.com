# Screenshot Plugin for Kirby CMS

A modern screenshot plugin for Kirby CMS that uses Puppeteer to capture high-quality screenshots with intelligent cookie banner removal.

## Features

- 🖼️ Multiple format support (PNG, JPEG, WebP)
- 🍪 Automatic cookie banner removal
- 🎨 Color extraction from screenshots
- ⚙️ Configurable viewport, quality, and format settings
- 🚀 Simple API for easy integration
- 📱 Responsive screenshot support
- 🔍 Auto-detection of format from file extension

## Installation

1. Copy the plugin to your `site/plugins/screenshot/` directory
2. Install Node.js dependencies:

```bash
cd site/plugins/screenshot
npm install
```

## Configuration

Add configuration options to your `site/config/config.php`:

```php
return [
    'screenshot' => [
        'quality' => 85,           // Image quality (0-100, applies to JPEG/WebP)
        'delay' => 0,              // Delay before screenshot (ms)
        'format' => 'png',         // Default format: 'png', 'jpeg', 'webp'
        'viewport' => ['width' => 1920, 'height' => 1080]
    ]
];
```

## Usage

### Recommended: Screenshot Class

The Screenshot class is the recommended approach for all screenshot operations, as it handles configuration, error handling, and works regardless of plugin loading order.

```php
// Basic screenshot
$result = Screenshot::capture('https://example.com', '/path/to/screenshot.png');

// Auto-detect format from file extension
$result = Screenshot::capture('https://example.com', '/path/to/screenshot.jpg');    // JPEG
$result = Screenshot::capture('https://example.com', '/path/to/screenshot.webp');   // WebP

// Override format and options
$result = Screenshot::capture('https://example.com', '/path/to/screenshot.png', [
    'format' => 'jpeg',
    'quality' => 90,
    'delay' => 2000
]);

// For Kirby pages
$result = Screenshot::captureForPage($page, 'screenshot.jpg', [
    'format' => 'jpeg',
    'quality' => 90
]);

// Check availability before use
if (Screenshot::isAvailable()) {
    $result = Screenshot::capture($url, $filepath);
} else {
    echo "Screenshot service not available";
}

if ($result['success']) {
    echo "Screenshot saved to: " . $result['filepath'];
} else {
    echo "Error: " . $result['error'];
}
```

### Global Helper Function

```php
// Take a screenshot using the global helper (calls Screenshot::capture internally)
$result = screenshot('https://example.com', '/path/to/screenshot.png');

// Override format explicitly
$result = screenshot('https://example.com', '/path/to/screenshot.png', [
    'format' => 'jpeg',
    'quality' => 90
]);
```

### Page Method

```php
// Take a screenshot of a Kirby page (calls Screenshot::capture internally)
$page = page('home');

// PNG (default)
$result = $page->screenshot('home-screenshot.png');

// JPEG with custom quality
$result = $page->screenshot('home-screenshot.jpg', [
    'quality' => 90,
    'viewport' => ['width' => 1200, 'height' => 800]
]);

// WebP format
$result = $page->screenshot('home-screenshot.webp', [
    'format' => 'webp',
    'quality' => 80
]);
```

### Site Method

```php
// Use the site method (calls Screenshot::capture internally)
$result = site()->screenshot('https://example.com', '/path/to/screenshot.png');

// Different formats
$result = site()->screenshot('https://example.com', '/path/to/screenshot.jpg');
$result = site()->screenshot('https://example.com', '/path/to/screenshot.webp');
```

### Instance Usage (Advanced)

```php
// Direct instance usage for custom scenarios
$screenshot = Screenshot::instance();

// PNG with custom settings
$result = $screenshot->takeScreenshot('https://example.com', '/path/to/screenshot.png', [
    'delay' => 2000,
    'quality' => 95
]);

// Note: Static methods are recommended for most use cases
```

## Options

- `quality` (int): Image quality (0-100, default: 85) - applies to JPEG and WebP formats
- `delay` (int): Delay in milliseconds before taking screenshot (default: 0)
- `format` (string): Image format - 'png', 'jpeg', or 'webp' (default: 'png')
- `viewport` (array): Viewport dimensions
  - `width` (int): Viewport width (default: 1920)
  - `height` (int): Viewport height (default: 1080)

## Supported Formats

- **PNG**: Lossless compression, larger file size, supports transparency
- **JPEG**: Lossy compression, smaller file size, no transparency, quality setting applies
- **WebP**: Modern format with excellent compression, quality setting applies

## Requirements

- PHP 8.0+
- Node.js
- Puppeteer
- Kirby 4+

## License

MIT