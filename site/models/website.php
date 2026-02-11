<?php

use Kirby\Cms\Url;

require_once kirby()->root('models') . '/default.php';

class WebsitePage extends DefaultPage
{

	/**
	 * Get the first backend image as the preview image
	 *
	 * @return Kirby\Cms\File|null
	 */
	public function previewImage()
	{
		return $this->backendImages()->first();
	}

	/**
	 * Get all the images of a page except the one named "frontend"
	 *
	 * @return Kirby\Cms\Files
	 */
	public function backendImages()
	{
		return $this->images()->filter(function ($file) {
			return $file->name() !== 'frontend';
		});
	}
	/**
	 * Retrieve the cover image for the page
	 *
	 * @return Kirby\Cms\File|null
	 */
	public function ogImage()
	{
		if ($this->backendImages()->count()) {
			return $this->backendImages()->first()->thumb([
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
