<div class="website-list wrap">
	<ul class="website-list__list inner">
		<?php foreach ($pages->listed()->sortBy('date', 'desc') as $website): ?>
			<li class="website-list__item website-list-item">

				<div class="website-list-item__header rich-text">
					<h1 class="website-list-item__title"><?= $website->title() ?></h1>

					<div class="website-list-item__text">
						<?= $website->text()->kt() ?>
					</div>

					<a class="website-list-item__link" href="<?= $website->content()->url() ?>"><?= $website->shortUrl() ?></a>

				</div>

				<ul class="website-list-item__list">
					<?php if ($image = $website->frontendImage()): ?>
						<a href="<?= $website->url() ?>#frontend" class="website-list-item__image-wrap" id="<?= $website->uid() ?>">
							<figure class="website-list-item__figure">
								<img class="website-list-item__image" src="<?= $image->resize(800, 600)->url() ?>" alt="<?= $image->alt()->or('Backend screenshot of ' . $website->shortUrl())->html() ?>" loading="lazy" width="<?= $image->width() ?>" height="<?= $image->height() ?>">
							</figure>
						</a>
					<?php endif ?>

					<?php
					$i = 0;
					foreach ($website->backendImages() as $image): $i++;
					?>
						<a href="<?= $website->url() ?>#backend-<?= $i ?>" class="website-list-item__image-wrap" id="<?= $website->uid() ?>-backend-<?= $i ?>">
							<figure class=" website-list-item__figure">
								<img class="website-list-item__image" src="<?= $image->resize(800, null)->url() ?>" alt="<?= $image->alt()->or('Backend screenshot of ' . $website->shortUrl())->html() ?>" loading="lazy" width="<?= $image->width() ?>" height="<?= $image->height() ?>">
							</figure>
						</a>
					<?php endforeach ?>

				</ul>

			</li>
		<?php endforeach ?>
	</ul>
</div>