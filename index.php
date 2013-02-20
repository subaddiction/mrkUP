<?php if($_GET['download']){ include('download.php'); } ?>

<h1><a href="?reset=true">mrkUP</a></h1>

<?php if($_GET['render']){ include('render.php'); } ?>

<?php $availableData = scandir('db'); ?>

<?php foreach ($availableData as $database){ ?>
	<?php if($database != '.' && $database != '..'){ ?>
	<br />
	<a href="?render=true&amp;contents=<?php echo $database; ?>">Render <?php echo $database; ?></a>
	<?php } ?>
<?php } ?>
