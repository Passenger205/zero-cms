<?php  

if ( !defined('_PLUGSECURE_') ) {
	die('Прямой вызов модуля запрещен!');
}

class config implements StorableObject
{
	private static $className = 'Конфиг';


	public static $site_name = 'Zero-Cms';


	public static $database = array (
		'host' 	 => '127.0.0.1',
		'dbname' => 'zero-cms',
		'user'	 => 'root',
		'pass'	 => '',
		'charset'=> 'utf8',
		'opt' 	 => array(
		        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		        // PDO::ATTR_EMULATE_PREPARES   => false,
		),
	);


	public static $template = [
				'tmpl_dir'  => 'templates',
				'tmpl_name' => 'Conversion',
	];


	public static $s_url = true; // Семантические ссылки [true/false]


	public static $global_cms_vars = array();


	public static $strip_posts = 200;

	function __construct()
	{
		self::$global_cms_vars['SITE_NAME']  = self::$site_name;
		self::$global_cms_vars['PAGE_TITLE'] = '';
		self::$global_cms_vars['CONTENT'] 	 = '';
		self::$global_cms_vars['YEAR'] 		 = date('Y');
		self::$global_cms_vars['TIME'] 		 = date('H:i:s');
	}


	public static function getClassName()
	{
		return self::$className;
	}
}

?>