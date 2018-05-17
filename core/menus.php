<?php 

if (!defined('_PLUGSECURE_'))
  die('Прямой вызов модуля запрещен!');

class menus implements StorableObject
{
	private static $className = 'Меню';

	public function __construct()
	{
		$db = new database;
		
		$sql = 
			"SELECT m.id as menu_id, m.name as menu_name, m.title as menu_title,
			m.order as menu_order, m.container, m.separator, i.id as item_id,
			i.parent_id as item_parent_id, i.title as item_title, i.link 
					FROM menus m
					INNER JOIN menu_items i ON m.id = i.menu_id
					ORDER BY m.id
			";
		$menu_q = $db->query($sql);

		if ( $menu_q->fetchColumn() )
		{
			self::$menus = self::getMenusTree($menu_q);
			foreach ( self::$menus as $key => $menu ) 
			{
				
				$menu_data = database::prepareQuery("SELECT * FROM menus WHERE order = ? LIMIT 1", [$key])->fetch();

				config::global_cms_vars['MENU#'.$key] = self::genMenu(self::$menus[$key]['items'], $menu_data['container'], $menu_data['separator']);
			}
		}

		$db->disconnect();
	}


	private static function genMenusTree ( $input_array )
	{
		
	}



	public static function getClassName()
	{
		return self::$className;
	}
}

?>