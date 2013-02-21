<?php

if(file_exists('output/'.$_GET['download'])){
	//echo 'DOWNLOADING '.$_GET['download'];
	exec('cd output && tar czvf '.$_GET['download'].'.tar.gz '.$_GET['download']);
	
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Length: ' . filesize('output/'.$_GET['download'].'.tar.gz'));
	header('Content-Disposition: attachment; filename='.$_GET['download'].'.tar.gz');
	readfile('output/'.$_GET['download'].'.tar.gz');
	die();
	
} else {
	echo 'Selected package does not exist.';
}

?>
