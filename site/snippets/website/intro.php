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
			<!-- <?php if ($page->date()->isNotEmpty()): ?>
				<br>
				<time datetime="<?= $page->date()->toDate('c') ?>"><?= $page->date()->toDate('d.m.y') ?></time>
				<?php if ($page->modified('d.m.y') !== $page->date()->toDate('d.m.y')): ?>
					(updated <time datetime="<?= $page->modified('c') ?>"><?= $page->modified('d.m.y') ?></time>)
				<?php endif ?>
			<?php endif ?> -->
		</aside>

	</div>
</div>