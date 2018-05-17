<?php  

if ( !defined('_PLUGSECURE_') ) {
	die('Прямой вызов модуля запрещен');
}

class database implements StorableObject
{
	private static $className = 'Класс MySQL';
	private static $link = null;
 
 	public function __construct()
 	{
 		database::connect();
 	}

	public function connect()
	{
		$data = config::$database;

		if ( is_array($data) ) {
			extract($data); // разбираем на переменные массив конфига
		}

		$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

		try {
			$pdo = new PDO($dsn, $user, $pass, $opt);
		} catch ( PDOException $e ) {
			throw new databaseException('Error conneсtion to database',$e->getCode(), $e);
			// In production
			// error_log($e->getMessage());
			// exit('Something weird happend'); Something a user can understand
		}

		self::$link = $pdo;
		unset($pdo);
		
		return self::$link;
	}

	public static function ping()
	{
		if ( self::$link != null ) {
			return 1;
		} else {
			return 0;
		}
	}

	public function disconnect()
	{
		self::$link = null;
	}


	public function query($sql)
	{
		try {		
			if ($sql)
			{
				$r_query = self::$link->query($sql);
				
				if ( $r_query ) {
					return $r_query;
				} else {
					throw new mysqlException("Ошибка при выполнение запроса в БД");
				}
			}
			else
			{
				throw new mysqlException("Ошибка, передан пустой запрос");
			}
		} catch ( mysqlException $e ) {
			throw new databaseException ($e->getMessage(), $e->getCode(), $e);
		}
	}

/**
 * [Делает подготовленный запрос к БД]
 * @param  [string] $sql        [SQL Запрос]
 * @param  [array] 	$values     [Подготовленные значения]
 * @return [PDO stmt]      		[Результат запроса]
 */
	public static function prepareQuery( $sql, $values )
	{
		try {
			try {
				$stmt = self::$link->prepare( $sql );
				$stmt->execute( $values );
			} catch (PDOException $e) {
				throw new mysqlException("Ошибка при выполнение запроса к БД", $e->getCode(),$e);
			}
		} catch ( mysqlException $e ) {
			throw new databaseException($e->getMessage(), $e->getCode(),$e);
		}

		return $stmt;
	}


	public static function getClassName()
	{
		return self::$className;
	}

}

?>