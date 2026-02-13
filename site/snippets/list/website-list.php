<div class="website-list wrap">
	<ul class="website-list__list inner">
		<?php foreach ($pages->listed()->sortBy('date', 'desc') as $website): ?>
			<li class="website-list__item website-list-item">

				<div class="website-list-item__header rich-text">
					<h1 class="website-list-item__title"><?= $website->title() ?></h1>

					<div class="website-list-item__text">
						<?= $website->text()->kt() ?>
					</div>

					<a class="website-list-item__link" href="<?= $website->content()->url() ?>"><?= $website->shortUrl() ?>/panel</a>

				</div>

				<ul class="website-list-item__list">
					<?php
					$i = 0;
					foreach ($website->images()->sorted() as $image): $i++;
					?>
						<a href="<?= $website->url() ?>#screenshot-<?= $i ?>" class="website-list-item__image-wrap" id="<?= $website->uid() ?>-screenshot-<?= $i ?>">
							<figure class="website-list-item__figure">
								<img class="website-list-item__image" src="<?= $image->resize(800, null)->url() ?>" alt="<?= $image->alt()->or('Panel screenshot of ' . $website->shortUrl())->html() ?>" loading="lazy" width="<?= $image->width() ?>" height="<?= $image->height() ?>">
							</figure>
						</a>
					<?php endforeach ?>

				</ul>

			</li>
		<?php endforeach ?>
	</ul>
</div>