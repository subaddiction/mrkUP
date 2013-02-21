<?php if($_GET['download']){ include('download.php'); } ?><!DOCTYPE html>
<html>
	<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>mrkUP</title>
	<meta name="description" content="mrkUP - static website generator" />
	<meta name="keywords" content="mrkUP - static website generator" />
	<meta name="author" content="" />
	<meta name="robots" content="index, follow" />
	<meta name="viewport" content="width=device-width" />
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="templates/default/css/fonts.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="templates/default/css/style.css" type="text/css" media="screen" />
	<!--[if IE]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<![endif]-->
	<link rel="stylesheet" href="templates/default/css/print.css" type="text/css" media="print" />
	<link rel="stylesheet" href="templates/default/css/desktop.css" type="text/css" media="screen and (min-width:1024px)" />
	<link rel="stylesheet" href="templates/default/css/mobile.css" type="text/css" media="screen and (max-width:1023px)" />
	<script type="text/javascript" src="templates/default/js/frontend.js"></script>
	</head>
	<body>
	<!--[if lte IE 7]>
	Outdated browser!
	<![endif]-->
	<header>
		<h1><a href="?reset=true">mrkUP <small>DASHBOARD</small></a></h1>
	</header>
	
	<div id="content">

		<?php

		if($_GET['render']){ include('render.php');

		siteRender($_GET['template'],$_GET['rebuild']);

		} ?>


		<h2>Available data</h2>
		<?php $availableData = scandir('db'); ?>
		<?php foreach ($availableData as $database){ ?>
			<?php if($database != '.' && $database != '..'){ ?>
			<b><?php echo $database; ?></b> <a href="?render=true&amp;contents=<?php echo $database; ?>">Render/Update </a>
			-
			<a href="?render=true&amp;contents=<?php echo $database; ?>&amp;rebuild=true">Rebuild</a>
			<br />
			<?php } ?>
		<?php } ?>
	</div>
	
	<footer>
		mrkUP - default theme by <a href="http://bquery.com">bquery.com</a>
	</footer>
		
	</body>
</html>







