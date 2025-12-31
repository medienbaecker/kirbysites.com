<?php if (option('debug') !== true): ?>
	<script defer data-domain="kirbysites.com" data-api="https://backofen.link/plausible/api/event" src="https://backofen.link/plausible/js/script.js"></script>
<?php endif ?>

<?= js(array(
	'assets/js/main.min.js'
)) ?>

</body>

</html>