<header class="header wrap">
	<div class="inner header__inner">

		<a class="logo" href="<?= $site->url() ?>">
			<?= $site->title() ?>
		</a>

		<div class="header__buttons">

			<?php snippet('mode-toggle') ?>

			<?php snippet('theme-toggle') ?>

			<a href="mailto:mail@medienbaecker.com?Subject=Kirbysites&Body=Please%20include%20the%20URL%20to%20your%20project%20and%20attach%20a%20few%20panel%20screenshots.%0A%0AThank%20you%21" class="icon-button submit-link" aria-label="Submit your website">
				<?= classySvg('assets/images/add.svg', 'icon-button__icon') ?>
				<span class="icon-button__text">
					Submit
				</span>
			</a>
		</div>

	</div>
</header>