<div class="website-links wrap">
	<ul class="columns inner">
		<?php foreach ($pages->listed()->sortBy('date', 'desc') as $website): ?>
			<li class="website-links__item website-link">
				<a class="website-link__image-link" href="<?= $website->url() ?>" id="<?= $website->uid() ?>">
					<?php if ($image = $website->previewImage()): ?>
						<img class="website-link__image" src="<?= $image->resize(800)->url() ?>" alt="<?= $image->alt()->or('Panel screenshot of ' . $website->shortUrl())->html() ?>" loading="lazy" width="<?= $image->width() ?>" height="<?= $image->height() ?>" style="aspect-ratio: <?= $image->width() ?> / <?= $image->height() ?>">
					<?php endif ?>
				</a>
				<div class="website-link__text">
					<a class="website-link__url link-decoration" href="<?= $website->content()->url() ?>"><?= $website->shortUrl() ?></a>
					<a class="website-link__panel" href="<?= $website->url() ?>">/panel</a>
				</div>
			</li>
		<?php endforeach ?>
	</ul>
</div>