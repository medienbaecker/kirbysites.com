<dialog class="submit-dialog" id="submit-dialog" closedby="any">

	<div class="submit-dialog__header">
		<h2 class="submit-dialog__title">Your submission</h2>
		<button class="submit-dialog__close" aria-label="Close">
			<?= classySvg('assets/images/close.svg', 'submit-dialog__close-icon') ?>
		</button>
	</div>

	<div class="submit-dialog__text rich-text">
		<?= $site->submit()->kt() ?>
	</div>

</dialog>