<?php

return [
	'debug' => false,
	'panel' => false,
	'cache' => [
		'pages' => [
			'active' => true,
		]
	],
	'home' => 'rss',
	'url' => '*',
	'content' => [
		'extension' => 'md'
	],
	'kirbytext' => [
		'image' => [
			'width' => 'auto',
			'height' => 'auto',
		]
	],
	'thumbs' => [
		'format' => 'webp',
		'quality' => 80,
	],
	'smartypants' => true,
	'routes' => [
		[
			'pattern' => '(:all)',
			'action'  => function (string $path) {
				$base = 'https://madewith.getkirby.com';
				$renamed = [
					'hicks'             => 'hicksdesign',
					'erlacher-hohe'     => 'erlacher-hoehe'
				];

				if ($path === 'rss.xml') {
					go($base . '/feed.rss', 301);
				}

				$page = $path !== '' ? page($path) : null;

				if ($page?->template()->name() === 'website') {
					$slug = $page->slug();
					go($base . '/cases/' . ($renamed[$slug] ?? $slug), 301);
				}

				go($base, 301);
			}
		]
	],
];
