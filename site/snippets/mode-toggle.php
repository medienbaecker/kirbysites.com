<?php
$mode = $page->template()->name();
$otherMode = $mode === 'home' ? 'list' : '/';
$linkToOtherMode = url($otherMode);
?>

<a
	href="<?= $linkToOtherMode ?>"
	aria-label="Switch mode"
	class="icon-button mode-toggle">
	<span class="mode-toggle__icon mode-toggle__icon--grid">
		<?= classySvg('assets/images/grid.svg', 'icon-button__icon') ?>
	</span>
	<span class="mode-toggle__icon mode-toggle__icon--list">
		<?= classySvg('assets/images/list.svg', 'icon-button__icon') ?>
	</span>
	<span class="icon-button__text">
		Mode
	</span>
</a>