<?php  

if ( !defined('_PLUGSECURE_') ) {
	die('Прямой вызов запрещен');
}
/***********************/
/*** Блок исключений ***/
/***********************/

// базовое исключение
class baseException extends Exception{}

// исключения ядра
class coreException extends baseException {}

class databaseException extends coreException{}
class MySQLException extends databaseException{}

class tmplException extends coreException{}

class surlException extends coreException{}

// исключения модулей
class moduleException extends baseException{}

class postsException extends moduleException{}

/******************/
/*** обработчик ***/
/******************/

class handler implements StorableObject
{
	private static $className = 'Обработчик исключений(Пустой)';

	public static function getClassName() {
		return self::$className;
	}

	// выводит сообщение об ошибке 
	public static function showError( $message ) {
		echo '<div style="padding: 10px; width: 80%; margin: 10px auto; background: #fff; border: 3px solid #FF0000;"> <b>ERROR:</b><br>' . $message . '</div>';
	}

	// Обработчик по умолчанию
	public static function engineError( $ex )
	{
		echo '<div style="padding: 10px; width: 80%; margin: 10px auto; background: #fff; border: 3px solid #FF0000;"><i><b>Uncaught exception:</i></b></div>';
		$ex->xdebug_message = null; // убираем сообщение от xdebug
		functions::dump($ex);
		// echo '<pre>'.$ex->xdebug_message;
	}

	public static function Logger() 
	{
		//TODO или вынести в отдельный класс
	}

	public static function httpError($error_code)
	{
		$descript = [
			'400' 	=> 	'Bad Request',
			'400' 	=> 	'Unauthorized',
			'402'	=>	'Payment Required',
			'403'	=>	'Forbidden',
			'404'	=>	'Not Found',
			'405'	=>	'Method Not Allowed',
			'406'	=>	'Not Acceptable',
			'407'	=>	'Proxy Authentication Required',
			'408'	=>	'Request Timeout',
			'409'	=>	'Conflict',
			'410'	=>	'Gone',
			'413'	=>	'Request Entity Too Large ',
			'429'	=>	'Too Many Requests',
			'444'	=>	'',
			'000'	=>	'Undefinded Error'
		];
		
		header($_SERVER['SERVER_PROTOCOL'] . $error_code . $descript[$error_code]);
		header('Location: ' . surl::genUri('error', $error_code));
	}
}

set_exception_handler( 'handler::engineError' );

?>