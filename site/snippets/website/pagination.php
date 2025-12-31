<div class="wrap">
	<div class="inner">
		<nav class="pagination" aria-label="Next/previous">
			<?php if ($prev = $page->prevListed($pages->listed()->sortBy('date', 'desc'))): ?>
				<a class="pagination__link pagination__link--prev" href="<?= $prev->url() ?>" rel="prev">
					← <?= $prev->title() ?>
				</a>
			<?php endif ?>
			<?php if ($next = $page->nextListed($pages->listed()->sortBy('date', 'desc'))): ?>
				<a class="pagination__link pagination__link--next" href="<?= $next->url() ?>" rel="next">
					<?= $next->title() ?> →
				</a>
			<?php endif ?>
		</nav>
	</div>
</div>