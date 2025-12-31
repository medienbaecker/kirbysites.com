<div class="website-images wrap">

	<div class="website-images__inner inner">

		<?php if ($image = $page->frontendImage()): ?>
			<figure id="frontend">
				<img class="website-images__item website-image website-image--frontend" src="<?= $image->url() ?>" alt="<?= $image->alt()->or('Frontend screenshot of ' . $page->shortUrl())->html() ?>" width="<?= $image->width() ?>" height="<?= $image->height() ?>" style="view-transition-name: <?= $page->uid() ?>;">
				<?php if ($image->caption()->isNotEmpty()): ?>
					<figcaption class="website-image__caption rich-text"><?= $image->caption()->kt() ?></figcaption>
				<?php endif ?>
			</figure>
		<?php endif ?>

		<?php
		$i = 0;
		foreach ($page->backendImages() as $image): $i++;
		?>
			<figure id="backend-<?= $i ?>">
				<img class="website-images__item website-image website-image--backend" src="<?= $image->url() ?>" alt="<?= $image->alt()->or('Backend screenshot of ' . $page->shortUrl())->html() ?>" loading="lazy" width="<?= $image->width() ?>" height="<?= $image->height() ?>" id="<?= $page->uid() ?>-backend-<?= $i ?>">
				<?php if ($image->caption()->isNotEmpty()): ?>
					<figcaption class="website-image__caption rich-text"><?= $image->caption()->kt() ?></figcaption>
				<?php endif ?>
			</figure>
		<?php endforeach ?>

	</div>

</div>