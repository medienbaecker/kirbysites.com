<div class="website-links wrap">
	<ul class="columns inner">
		<?php foreach ($pages->listed()->sortBy('date', 'desc') as $website): ?>
			<li class="website-links__item website-link">
				<?php if ($image = $website->frontendImage()): ?>
					<a class="website-link__image-link" href="<?= $website->url() ?>" id="<?= $website->uid() ?>">
						<img class="website-link__image" src="<?= $image->resize(800, 600)->url() ?>" alt="<?= $image->alt()->or('Screenshot of ' . $website->shortUrl())->html() ?>" loading="lazy" width="<?= $image->width() ?>" height="<?= $image->height() ?>">
					</a>
				<?php endif ?>
				<div class="website-link__text">
					<a class="website-link__url link-decoration" href="<?= $website->content()->url() ?>" aria-label="<?= $website->shortUrl() ?>, Frontend">
						<?= $website->shortUrl() ?>
					</a>
					<a class="website-link__panel" href="<?= $website->url() ?>#backend-1" aria-label="<?= $website->shortUrl() ?>, Backend">
						/panel
					</a>
				</div>
			</li>
		<?php endforeach ?>
	</ul>
</div>