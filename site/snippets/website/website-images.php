<div class="website-images wrap">

	<div class="website-images__inner inner">

		<?php
		$i = 0;
		foreach ($page->images()->sorted() as $image): $i++;
		?>
			<figure id="screenshot-<?= $i ?>">
				<img class="website-images__item website-image <?= $i === 1 ? 'website-image--preview' : '' ?>" src="<?= $image->url() ?>" alt="<?= $image->alt()->or('Panel screenshot of ' . $page->shortUrl())->html() ?>" <?= $i > 1 ? 'loading="lazy"' : '' ?> width="<?= $image->width() ?>" height="<?= $image->height() ?>" <?= $i === 1 ? 'style="view-transition-name: ' . $page->uid() . ';"' : '' ?>>
				<?php if ($image->caption()->isNotEmpty()): ?>
					<figcaption class="website-image__caption rich-text"><?= $image->caption()->kt() ?></figcaption>
				<?php endif ?>
			</figure>
		<?php endforeach ?>

	</div>

</div>