<?php  

if ( !defined('_PLUGSECURE_') ) {
	die('Прямой вызов запрещен');
}

class modules implements StorableObject
{
	private static $className = 'Модули';


	/**
	* @return [string] Возвращает путь к модули или false
	**/
	public static function getModule( $module )
	{
		if ( file_exists('./includes/modules/custom/'.$module.'/'.$module.'.inc') )
			return './includes/modules/custom/'.$module.'/'.$module.'.inc';
		elseif ( file_exists('./includes/modules/stock/'.$module.'/'.$module.'.inc') )
			return './includes/modules/stock/'.$module.'/'.$module.'.inc';
		else
			return false;
	}


	/**
	 * Проверяет отключен ли данный модуль(список из конфига)
	 */
	public static function ignoreList( $module )
	{
	}

	public static function getClassName()
	{
		return self::$className;
	}
}

?>