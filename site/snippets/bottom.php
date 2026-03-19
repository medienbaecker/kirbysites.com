<?php if (option('debug') !== true): ?>
	<script async src="https://analytics.backofen.link/js/pa-RAp2H8BK8130dvn20giJZ.js"></script>
	<script>
		window.plausible = window.plausible || function() {
			(plausible.q = plausible.q || []).push(arguments)
		}, plausible.init = plausible.init || function(i) {
			plausible.o = i || {}
		};
		plausible.init()
	</script>
<?php endif ?>

<?= js(array(
	'assets/js/main.min.js'
)) ?>

</body>

</html>