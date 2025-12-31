<div class="intro wrap">
	<div class="columns inner">

		<div class="columns__item columns__item--1 rich-text">
			<h1><?= $page->title() ?></h1>
			<div class="rich-text">
				<?= $page->text()->kt() ?>
			</div>
		</div>

		<aside class="intro__aside columns__item columns__item--2 rich-text">
			<a href="<?= $page->content()->url() ?>"><?= $page->shortUrl() ?></a>
		</aside>

	</div>
</div>