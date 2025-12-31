<!-- 🖼️ Open Graph Image -->
<meta property="og:image" content="<?= $page->ogImage()->url() ?>">

<!-- 🐦 Twitter Card Type -->
<meta name="twitter:card" content="summary_large_image">

<!-- 📢 Open Graph Title -->
<meta property="og:title" content="<?= $page->seoTitle() ?>">

<!-- 📝 Open Graph Description -->
<meta property="og:description" content="<?= $page->description()->or($site->description()) ?>">