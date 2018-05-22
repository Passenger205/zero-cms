<?php

if (!defined('_PLUGSECURE_'))
{
  die('Прямой вызов запрещен!');
}


$t_info = array(
		'name' 		=> 'Conversion',	//имя шаблона
		'autor'		=> 'fussraider',	//автор
		'version'	=> '0.1',			//версия
		'date'		=> '09.11.2016'		//дата создания
);

//список псевдопеременных, по которым работает шаблон
$t_files = array('HEADER','INTERESTING', 'QUOTES', 'CONTENT', 'MENU', 'FOOTER');

// true = footer
$t_scripts = array(
	'/js/jquery.min.js'				=> false,
	'/js/imagesloaded.pkgd.min.js'	=> false,
	'/js/masonry.pkgd.min.js'		=> false,
);

$t_styles = array(
	'/styles/main.css'				=> 'all',
	'/styles/large-screen.css' 		=> 'screen and (min-width: 1440px)',
	'/styles/middle-screen.css'		=> 'screen and (max-width: 1440px) and (min-width: 980px)',
	'/styles/small-screen.css'		=> 'screen and (max-width : 980px ) and (min-width : 588px)',
	'/styles/smallest-screen.css' 	=> 'screen and (max-width: 640px)',
);

?>