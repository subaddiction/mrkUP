<?php

/* CONFIGURATIONS */

$ignore = array(
	'.',	
	'..',
	'config',
	'mediadb',
	'README',	
	);

$contents = ($_GET['contents'])?$_GET['contents']:'default';
$db = scandir('db/'.$contents);
$templates = scandir('templates');

/* FUNCTIONS */

function pageName($name){
	if(is_numeric(substr($name,0,strpos($name,'_')))){
		$name = substr($name,strpos($name,'_')+1,255);
	}
	return $name;
}

require_once('lib/Markdown.php');
use \Michelf\Markdown;

function renderMarkdown($config='db/default',$model='templates/default/index.html',$text='#Test'){
	global $ignore;
	
	$html = renderModel($config,$model);
	
	$pages = scandir($config);
	$menu = '<ul id="mainMenu">';
		
		foreach($pages as $page){
			if(!in_array($page, $ignore)){
#				if(is_numeric(substr($page,0,strpos($page,'-')))){
#					$page = substr($page,strpos($page,'-')+1,255);
#				}
				$page = pageName($page);
				
				//echo $page.'<br />';
				
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

function siteRender($template=false,$rebuild=false){
	global  $ignore, $contents, $db, $templates;
	
	if($template){
	$templates = array($template);
	}
	
	foreach($templates as $template){
	
		if($rebuild){
			exec('rm -rf output/'.$template);
		}
	
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
			
				$html = false;
				$entries = array();
			
				if(!in_array($page, $ignore)){
					//echo $page.'<br />';
					if(!is_dir('db/'.$contents.'/'.$page)){
						
						$text = @file_get_contents('db/'.$contents.'/'.$page);
						
					} else {
					
					
						$text = '';
						$sectionEntries = scandir('db/'.$contents.'/'.$page, 1);
					
						foreach ($sectionEntries as $entry){
							if(!in_array($entry, $ignore)){
								$text .= '<div class="'.$page.'">';
								$entry = pageName($entry);
								$text .= '<a href="'.$page.'/'.$entry.'.html">';
								
								$entry = str_replace('-', ' ', $entry);
								$text .= $entry.'</a>';
								
								$text .= '</div>';
							}
						}
				
					}
				
					$page = pageName($page);
				
					$html = renderMarkdown('db/'.$contents, 'templates/'.$template.'/index.html', $text);
				
					@file_put_contents('output/'.$template.'/'.$page.'.html', $html);
			
					exec('chmod -R 777 output/'.$template.'/'.$page.'.html');
				
				} else if($page == 'mediadb'){
						
					exec('cp -r db/'.$contents.'/'.$page.' output/'.$template.'/');
					exec('chmod -R 777 output/'.$template.'/'.$page);
					
					//echo('cp -r db/'.$contents.'/'.$page.' output/'.$template.'/');
					
				}
			
				##################################################
				#blogging support
			
			
				if(!in_array($page, $ignore) && is_dir('db/'.$contents.'/'.$page)){			
					$pageEntries = scandir('db/'.$contents.'/'.$page);
					$entries[$page] = array();
					foreach($pageEntries as $entry){
						if(!in_array($entry, $ignore)){
							$entries[$page][] = $entry;
						}
					}		
				}
			
			
			
				if(is_array($entries)){
					#build inner pages
					foreach($entries as $section => $entry){
						if(!file_exists('output/'.$template.'/'.$section)){
							mkdir('output/'.$template.'/'.$section);
						}
						foreach($entry as $key => $value){
							$text = @file_get_contents('db/'.$contents.'/'.$section.'/'.$value);
						
							$value = pageName($value);
							$html = renderMarkdown('db/'.$contents, 'templates/'.$template.'/index.html', $text);
							@file_put_contents('output/'.$template.'/'.$section.'/'.$value.'.html', $html);
						}
						exec('chmod -R 777 output/'.$template.'/'.$section);
					
							
					}
				}
				##################################################
			
			}
		
		
			echo $contents.' rendered in <a target="_blank" href="output/'.$template.'/">'.$template.'</a> - <a target="_blank" href="?download='.$template.'">Download</a><br />';
		
		}

	}

}



?>
