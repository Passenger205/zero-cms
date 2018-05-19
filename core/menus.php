<?php 

if (!defined('_PLUGSECURE_'))
  die('Прямой вызов модуля запрещен!');

class menus implements StorableObject
{
	private static $className = 'Меню';

	public static $menus;

	public function __construct()
	{
		$db = new database;
		
		/**
		* В результате fetch(), каждый элемент массива-будет содержать 
		* 	сам элемент + данные о меню
		*/
		$sql = "SELECT m.id as menu_id, m.name as menu_name, m.title as menu_title,
				m.order as menu_order, m.container, m.separator, i.id as item_id,
				i.parent_id as item_parent_id, i.title as item_title, i.link 
						FROM menus m
						INNER JOIN menu_items i ON m.id = i.menu_id
						ORDER BY m.id
				";

		$menu_q = $db->query($sql);

		if ( $menu_q->rowCount() )
		{
			self::$menus = self::genMenusTree($menu_q);
			foreach ( self::$menus as $key => $menu ) 
			{
				$menu_data_q = database::prepareQuery("SELECT * FROM menus WHERE `order` = ? LIMIT 1", [$key]);
				$menu_data = $menu_data_q->fetch();

				config::$global_cms_vars['MENU#'.$key] = self::genMenu( self::$menus[$key]['items'], $menu_data['container'], $menu_data['separator'] );
			}
		}

		$db->disconnect();
	}


	private static function genMenusTree ( $input_array )
	{
		$cat = [];
		while ( $row = $input_array->fetch()) {
			$cat[$row['item_id']] = $row;
		}

		# Зачем ссылки??
		$tree = [];
		foreach ($cat as $id => &$node) { 
			//Если нет вложений
			if ( !$node['item_parent_id'] ) {
				$tree[$id] = &$node;
			} else {
			//Если есть потомки то перебераем массив
				$cat[ $node['item_parent_id'] ]['childs'][$id] = &$node;
			}
		}
		unset($node);

		$full_tree = [];
		foreach ($tree as $item) {
			if ( !isset($full_tree[ $item['menu_order'] ]['id'] ) )
			{
				$full_tree[ $item['menu_order'] ]['id'] = $item['menu_id'];
			}
			if ( !isset($full_tree[ $item['menu_order'] ]['menu_name']) ) 
			{
				$full_tree[ $item['menu_order'] ]['menu_name'] = $item['menu_name'];
			}
			$full_tree[ $item['menu_order'] ]['items'][ $item['item_id'] ] = $item;
		}

		
		return $full_tree;

	}


	private static function genMenu( $menu, $container = 'ul', $separator = false, $menu_html = '' ) 
	{
		

		if ( $separator ) {
			$add_separator = '<span id="separator">' . $separator . '</span>';
		} else {
			$add_separator = '';
		}

		if ( empty($container) ) {

			foreach ($menu as $el) {
				if (next($menu)) 
				{
					if ( isset($el['childs'])) {
						$menu_html .= '<a href='.$el['link'].' class="menu_link">'.$el['item_title'].'</a>'.$add_separator;
						$menu_html .= self::genMenu($el['childs'], $container, $separator,$menu_html);
					} else {
						$menu_html .= '<a href='.$el['link'].' class="menu_link">'.$el['item_title'].'</a>'.$add_separator;
					}
				} else {
					if ( isset($el['childs'])) {
						$menu_html .= '<a href='.$el['link'].' class="menu_link">'.$el['item_title'].'</a>';
						$menu_html .= self::genMenu($el['childs'], $container, $separator,$menu_html);
					} else {
						$menu_html .= '<a href='.$el['link'].' class="menu_link">'.$el['item_title'].'</a>';
					}
				}
			}

		} else {

			switch ( $container ) {
				case 'ul':
					$item_container = 'li';
					break;

				case 'ol':
					$item_container = 'li';
					break;
				
				default:
					$item_container = $container;
					break;
			}

			$menu_html = '<'.$container.'>';

			foreach ($menu as $el) 
			{
				if (isset($el['childs'])) 
				{
					$menu_html .= '<'.$item_container.' class="menu_item"><a href='.$el['link'].' class="menu_link">'.$el['item_title'].'</a>'.$add_separator.'</'.$item_container.'>';
					$menu_html .= self::genMenu($el['childs'], $container, $separator,$menu_html);
				} else {
					$menu_html .= '<'.$item_container.' class="menu_item"><a href='.$el['link'].' class="menu_link">'.$el['item_title'].'</a>'.$add_separator.'</'.$item_container.'>';
				}
			}

			$menu_html = '</'.$container.'>';
		}

		
		return $menu_html;


	}


	public static function getMenu( $menu )
	{
		if (is_array( $menu, self::$menus)) {
			return self::$menus[$menu];
		} else {
			return false;
		}
	}


	public static function getClassName()
	{
		return self::$className;
	}
}

?>