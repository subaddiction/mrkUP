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
	global $ignore;
	
	$html = renderModel($config,$model);
	
	$pages = scandir($config);
	$menu = '<ul id="mainMenu">';
		foreach($pages as $page){
			if(!in_array($page, $ignore)){
				$pageName = ($page == 'index')?'home':$page;
				$menu .= '<li><a href="{URL}/'.$page.'.html">'.$pageName.'</a></li>';
			}
		}
	$menu .= '</ul>';
	
	$html = str_replace('{MENU}', $menu, $html);
	
	//re-render to put urls in menu
	$html = renderModel($config,$html,false);
	
	//echo htmlentities($html);
	
	$content = Markdown::defaultTransform($text);
	
	//echo htmlentities($content);
	$html = str_replace('{CONTENT}', $content, $html);
	
	return $html;
}


function renderModel($config='db/default',$model='templates/default/index.html',$modelIsPath=true){
	
	$fields = array();
	$config = file($config.'/config');
	
	foreach($config as $field){
		$field = explode('|:|', $field);
		$field_key = $field[0];
		$field_value = $field[1];
		
		$fields[$field_key] = $field_value;
	}
	
	if($modelIsPath){
	$model = @file_get_contents($model);
	}
	
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
		
		exec('chmod -R 777 output/'.$template);
		
		
		$static_assets = scandir('templates/'.$template);
		
		foreach($static_assets as $asset){
			if(!in_array($asset, $ignore)){
			
				exec('cp -r templates/'.$template.'/'.$asset.' output/'.$template.'/');
				
				exec('chmod -R 777 output/'.$template.'/'.$asset);
				
			}
		}
		
		
		
		foreach($db as $page){
			if(!in_array($page, $ignore)){
				//echo $page.'<br />';
				$text = @file_get_contents('db/'.$contents.'/'.$page);
				
				$html = renderMarkdown('db/'.$contents, 'templates/'.$template.'/index.html', $text);
				
				@file_put_contents('output/'.$template.'/'.$page.'.html', $html);
				
				exec('chmod -R 777 output/'.$template.'/'.$page.'.html');
				
			}
		}
		
		
		echo $contents.' rendered in <a target="_blank" href="output/'.$template.'/">'.$template.'</a> - <a target="_blank" href="?download='.$template.'">Download</a><br />';
		
	}

}



?>
