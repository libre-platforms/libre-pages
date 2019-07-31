<?php
  $asset_loader = Pages\make_asset_loader($request);
?>

<!DOCTYPE html>
<html>
	<head>
		<title>LibrePages</title>
		<link type="css" rel="stylesheet" href="<?=$asset_loader('css/global.css')?>" />
	</head>
	<body>
		LibrePages
	</body>
</html>