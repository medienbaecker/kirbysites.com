<header class="header wrap">
	<div class="inner header__inner">

		<a class="logo" href="<?= $site->url() ?>">
			<?= $site->title() ?>
		</a>

		<div class="header__buttons">

			<?php snippet('mode-toggle') ?>

			<?php snippet('theme-toggle') ?>

			<button class="icon-button" data-submit-dialog>
				<?= classySvg('assets/images/add.svg', 'icon-button__icon') ?>
				<span class="icon-button__text">
					Submit
				</span>
			</button>
		</div>

	</div>
</header>

<?php snippet('submit-dialog') ?>