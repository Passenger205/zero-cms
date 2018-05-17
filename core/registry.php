<?php

if ( !defined('_PLUGSECURE_') ) {
	die('Прямой вызов модуля запрещен!');
}
interface StorableObject
{
	public static function getClassName();
}

class Registry
{

	// имя модуля, читаемое
	private static $className = 'Реестр';

	// экземляр реестра
	private static $instance;

	// массив объектов
	private static $objects;

	public function loadCore()
	{
		$this->config 	 = './core/config.php'; 
		$this->functions = './core/functions.php';
		$this->handler 	 = './core/handler.php';
		$this->database  = './core/database.php';
		$this->modules   = './core/modules.php';
		$this->surl 	 = './core/surl.php';
		$this->template  = './core/template.php';
		$this->menus  	 = './core/menus.php';

	}

	private function __construct()
	{
		$this->loadCore();
	}


	public static function singleton()
	{
		if ( !isset(self::$instance) ) {
			$obj = __CLASS__;
			self::$instance = new $obj;
		}

		return self::$instance;
	}

	private function __clone  () {}
	private function __sleep  () {}
	private function __wakeup () {}

	// $object - путь к подключаемому объекту(классу объекта)
	// $key - ключ доступа к объекту в реестре
	public function addObject($key, $object)
	{
		if (!isset(self::$objects[$key]))
		{
		require_once($object);
		self::$objects[$key] = new $key();
		} else {
			if (isset(self::$objects['handler'])) {
				throw new coreException("Заблокирована попытка переопределения объекта");
			} else {
				die("Заблокирована попытка переопределения объекта");
			}
		}

		
	}


	// альтернативный метод через магию (необходимо только для визуализации)
	public function __set($key, $object)
	{
		$this->addObject($key, $object);
	}
	// разница в методах вызова (пример)
	// $registry->addObject('config', '/core/config.php');
	// $registry->config = '/core/config.php';

	
	// получаем объект из реестра
	// $key - ключ объекта в реестре
	public function getObject($key)
	{
		if ( is_object(self::$objects[$key]) ) {
			return self::$objects[$key];
		}
	}

	// аналогичный метод через магию
	public function __get($key) {
		if ( is_object(self::$objects[$key]) ) {
			return self::$objects[$key];
		}
	}

	public static function getClassName()
	{
		return self::$className;
	}


	// возвращает имена всех добавленных в реестр объектов (модулей), для отладки
	public function getObjectsList()
	{
		// возраващаемый массив
		$names = array();

		// получаем ИМЯ каждого объекта из массива объектов
		foreach ( self::$objects as $obj ) {
			$names[] = $obj->getClassName();
		}

		// дописываем в массив модулей, имя модуля регистра
		array_push($names, self::getClassName());


		return $names;
	}
}
?>