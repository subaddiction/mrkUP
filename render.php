<?php

$ignore = array(
	'.',	
	'..',
	'config',	
	);

$contents = ($_GET['contents'])?$_GET['contents']:'default';
$db = scandir('db/'.$contents);
$templates = scandir('templates');

/* FUNCTIONS */

require_once('lib/Markdown.php');
use \Michelf\Markdown;

function renderMarkdown($config='db/default/config',$model='templates/default/index.html',$text='#Test'){
	
	$html = renderModel($config,$model);
	
	//echo htmlentities($html);
	
	$content = Markdown::defaultTransform($text);
	
	//echo htmlentities($content);
	
	$html = str_replace('{CONTENT}', $content, $html);
	
	return $html;
}


function renderModel($config='db/default/config',$model='templates/default/index.html'){
	
	$fields = array();
	$config = file($config);
	
	foreach($config as $field){
		$field = explode('|:|', $field);
		$field_key = $field[0];
		$field_value = $field[1];
		
		$fields[$field_key] = $field_value;
	}
	
	$model = @file_get_contents($model);
	
	foreach($fields as $key => $value){
		$model = str_replace('{'.$key.'}', trim($value),  $model);
	}
	
	return $model;
	
}


/* RENDER */


foreach($templates as $template){
	
	if(!in_array($template, $ignore)){
		//echo $template.'<br />';
		
		@mkdir('output/'.$template);
		
		chmod('output/'.$template, 0775);
		
		foreach($db as $page){
			if(!in_array($page, $ignore)){
				//echo $page.'<br />';
				$text = @file_get_contents('db/'.$contents.'/'.$page);
				
				$html = renderMarkdown('db/'.$contents.'/config', 'templates/'.$template.'/index.html', $text);
				
				@file_put_contents('output/'.$template.'/'.$page.'.html', $html);
				
				chmod('output/'.$template.'/'.$page.'.html', 0775);
				
			}
		}
		
		
		echo $contents.' rendered in <a target="_blank" href="output/'.$template.'/">'.$template.'</a> - <a target="_blank" href="?download='.$template.'">Download</a><br />';
		
	}

}



?>
