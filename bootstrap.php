<?php

if ( !defined('_PLUGSECURE_') )
	die('Прямой вызов модуля запрещен!');

require_once 'core/registry.php'; // подключили регистр
$registry = Registry::singleton(); // создали экземпляр - синглтон регистра

// try catch и в ЛОГ
try {
	$url_data = surl::parseUrl(config::$s_url);
} catch (surlException $e) {
	handler::httpError(404);
}

// действие по умолчанию
if (empty($url_data))
{
	$request_module = 'posts';
	$request_template = 'index';
}
else
{
	$request_module = $url_data['module'];
	$request_template = 'page';	
}

$try_module = modules::getModule($request_module);


if ( $try_module ) {
	require_once $try_module;
} else {
	if ( $request_module != 'error' ) {
		handler::httpError(404);
	} else {
		throw new coreException("Unknown exception 'error' module not found: ");
		//log , header(Location: main..)
	}
}

// выводим сформированнаый контент
config::$global_cms_vars['PAGE'] = template::loadTemplate($request_template, config::$global_cms_vars);

echo config::$global_cms_vars['PAGE'];


if ( database::ping() === 1 ) {
	handler::showError('WARNING MYSQL CONNECTED');
}
?>