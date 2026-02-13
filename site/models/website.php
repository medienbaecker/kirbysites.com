<?php

use Kirby\Cms\Url;

require_once kirby()->root('models') . '/default.php';

class WebsitePage extends DefaultPage
{

	/**
	 * Get the first image as the preview image
	 *
	 * @return Kirby\Cms\File|null
	 */
	public function previewImage()
	{
		return $this->images()->sorted()->first();
	}

	/**
	 * Retrieve the cover image for the page
	 *
	 * @return Kirby\Cms\File|null
	 */
	public function ogImage()
	{
		if ($image = $this->previewImage()) {
			return $image->thumb([
				'width' => 1200,
				'height' => 630,
				'crop' => 'top',
				'format' => 'png'
			]);
		}
		return site()->file('og.png');
	}

	/**
	 * Get the short URL for the page
	 *
	 * @return string
	 */
	public function shortUrl()
	{
		$url = $this->content()->url();

		return Url::short($url);
	}
}
