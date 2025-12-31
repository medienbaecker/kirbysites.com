<!DOCTYPE html>
<html data-template="<?= $page->intendedTemplate() ?>" lang="en" data-mode="<?= $page->template()->name() ?>">

<head>
	<!-- 📌 Meta Tags -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="<?= $page->description()->or($site->description()) ?>">

	<!-- 🏷️ Page Title -->
	<title><?= $page->seoTitle() ?></title>

	<!-- 🔗 Canonical Link -->
	<link rel="canonical" href="<?= $page->url() ?>">

	<!-- 🖼️ Favicon -->
	<link rel="shortcut icon" type="image/png" href="<?= url('assets/images/favicon.png') ?>">
	<link rel="icon" type="image/svg+xml" href="<?= url('assets/images/favicon.svg') ?>">

	<!-- 📡 RSS Feed -->
	<link rel="alternate" type="application/rss+xml" title="RSS Feed" href="<?= url('rss.xml') ?>">

	<!-- 🎨 Stylesheets -->
	<?= css(array(
		'assets/css/style.min.css'
	)) ?>

	<!-- 📱 Open Graph Meta Tags -->
	<?php snippet('og') ?>
</head>

<body>

	<?php snippet('skip-to-content') ?>