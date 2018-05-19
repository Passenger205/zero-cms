<?php  

if ( !defined('_PLUGSECURE_') ) {
	die('Прямой вызов модулей запрещен!');
}

class surl implements StorableObject
{
	private static $className = 'ЧПУ';

	public static function getClassName()
	{
		return self::$className;
	}

	/**
	* [ Основная идея парсера была в том, чтобы была возможность отключения SemanticURL и перехода к _GET параметрам, без проблем с индексацией. ]
	* [ В будущем имеет смысл переделать ЧПУ, убрав функционал отключения на основе такого принципа: 
		4 ступени адреса:
		1. страница (например, админка или вывод курсов),
		2. модуль (например, настройка чего-то в админке или вывод уроков курса),
		3. действие (например, добавить, удалить, редактировать)
		4. и дополнительный параметр (например, для передачи id того, с чем работаем).
		Поэтому после парсинга и разбиения адреса по слешам я просто вытаскиваю из него 4 переменные по порядку: $page, $module, $action, $extra. Все. Куда уж человеко-понятнее… Пример: /admin/lesson/edit/3 ]
	*/
	/**
	* @param  [bool] $type [Включение или отключение семантических ссылок]
	* @return [array] [Имена: модуля,действия и параметров]
	*/
	public static function parseUrl( $type )
	{
		$data = array();
		$request_uri = $_SERVER['REQUEST_URI']; // адрес по умолчанию

		// получаем алиас (если есть)
		$db = new database;
		$sql = "SELECT * FROM `aliases` WHERE `alias` = '" . trim($request_uri, ' /') . "'";
		$query_alias = $db->query($sql);

		$db->disconnect();

		$alias = $query_alias->fetch();
		
		if ( $alias ) {
			$request_uri = $alias['address'];
		}

		if ( $type === true )
		{
			if ( $request_uri != '/' )
			{
				$url_path = parse_url( $request_uri, PHP_URL_PATH);
				$uri_parts = explode( '/', trim($url_path, ' /') );


				// url должен содержать минимум два значения /module/action/ и N-ое кол-во пар /param1/value1, след. кол во частей должно быть четным. Если адрес не семантический то часть с параметрами (/action?param1=value1&param2=value2) не будет парситься parse_url(40) тк мы указали PHP_URL_PATH. След кол-во частей будет нечет

				// альтернативный обработчик (параметры или просто модуль)
				if ( count($uri_parts) % 2 != 0 )
				{
					if ( isset($_GET['module']) )
					{
						$data['module'] = $_GET['module'];
						unset($_GET['module']);

						if ( isset($_GET['action']) )
						{
							$data['action'] = $_GET['action'];
							unset($_GET['action']);
						}

						foreach ($_GET as $key => $value) {
							$data['params'][$key] = $value;
						}
					} else { 
						// передан только модуль
						if ( modules::getModule($uri_parts[0]) )
							$data['module'] = array_shift($uri_parts);
						else
							throw new surlException("Error getting module '".$uri_parts[0]."' or something wrong happend");
					}
				}
				else 
				{
					$data['module'] = array_shift($uri_parts);
					$data['action'] = array_shift($uri_parts);

					for ($i=0; $i < count($uri_parts); $i++) 
					{
						$data['params'][$uri_parts[$i]] = $uri_parts[++$i];
					}
				}
			}

			return $data;

		} 
		elseif ( $type === false )
		{
			if ( $request_uri != '/' )
			{
				if (iseet($_GET['module'])) 
				{
					$data['module'] = $_GET['module'];
					unset($_GET['module']);

					if (iseet($_GET['action'])) {
						$data['action'] = $_GET['action'];
						unset($_GET['action']);
					}

					foreach ($_GET as $param => $value) {
						$data['param'][$key] = $value;
					}
				} else {
					throw new surlException('Cant parse _GET "module" from URL or maybe s_url is turned off. Check config');
				}
			}

			return $data;

		} else {
			throw new surlException('Unknown mode type in "s_url", check config file');
		}
	}

	public static function genUri( $module, $action = null, $params = null )
	{
		if ( config::$s_url )
		{
			$result = '/'.$module.'/';

			if ( $action ) {
				$result .= $action.'/';
			}

			if (is_array($params)) {
				foreach ($params as $param => $value) {
					$result .= $param . '/' . $value . '/';
				}
			}
		} else {
			$result = '/?module=' . $module;
			
			if ($action) {
				$result .= '&action='.$action;
			}

			if (is_array($params)) {
				foreach ($params as $param => $value) {
					$result .= '&' . $param . '=' . $value;
				}
			}
		}

		return $result;
	}

	/**
	 * [Возвращает алиас из БД по адресу или NULL если его нет]
	 * @param  [string] $address [Адрес для поиска алиаса]
	 * @return [string]          [Алиас или NULL]
	 */
	public static function getAlias( $address )
	{	
		$db = new database;

		$sql = "SELECT `alias` FROM `aliases` WHERE `address` = ? LIMIT 1";
		$result = $db->prepareQuery( $sql, [$address] );

		$db->disconnect();

		if ( $alias = $result->fetch() )
			return $alias['alias'];
		else
			return null;
	}
}

?>