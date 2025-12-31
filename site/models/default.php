<?php

use Kirby\Cms\File;
use Kirby\Cms\Page;

class DefaultPage extends Page
{
	/**
	 * Generate the SEO title for the page
	 *
	 * @return string
	 */
	public function seoTitle(): string
	{
		$siteTitle = $this->site()->title();
		$pageTitle = $this->title_seo()->or($this->title());

		if ($this->isHomePage()) {
			return $siteTitle;
		}

		return "{$pageTitle} · {$siteTitle}";
	}

	/**
	 * Retrieve the cover image for the page
	 *
	 * @return File|null
	 */
	public function ogImage()
	{
		return site()->file('og.png');
	}
}
