<?php
  $asset_loader = Framework\make_asset_loader($request);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>LibrePages</title>
		<link type="css" rel="stylesheet" href="<?=$asset_loader('css/sierra.css')?>" />
		<link type="css" rel="stylesheet" href="<?=$asset_loader('css/global.css')?>" />
	</head>
	<body>
		<section class="container">
			<header class="header">
				LibrePages
			</header>
			<main>
			</main>
			<footer>
			</footer>
		</section>
	</body>
</html>