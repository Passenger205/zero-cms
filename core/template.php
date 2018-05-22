<?php  

if ( !defined('_PLUGSECURE_') )
	die('Direct call permited');

class template implements StorableObject
{
	private static $className = 'Шаблоны';

	private static $template;					// окончательный HTML собранного шаблона
	private static $template_dir;
	private static $template_page;				// тип страницы (index | page)
	private static $template_parts = array(); 	// список всех подключаемых шаблонов
	private static $template_vars  = array();	// набор ключей и значений для отобр. в шабл


	private static function getTemplate( $fast = false )
	{
		if ( $fast )
			$template_file = self::$template_dir . self::$template_page . '.tpl';
		else
			$template_file = self::$template_dir . self::$template_page . '_template.tpl';

		if ( !file_exists($template_file) ) {
			throw new tmplException('Error: template not found ( '.$template_file.');');
		} else {
			self::$template = file_get_contents($template_file);
		}
	}


	private static function getTemplatePart( $part )
	{
		$template_part = '';

		$part_file = self::$template_dir . '__' . $part . '.tpl';

		if ( !file_exists($part_file) ) {
			throw new tmplException('Error: template_part not found');
		} else {
			$template_part = file_get_contents($part_file);
		}

		return $template_part;
	}

	private static function loadStyles( $styles )
	{
		$tmpl = config::$template;

		if ( count($styles) > 0 ) 
		{
			foreach ($styles as $style => $media) 
			{
				$system_styles['/' . $tmpl . $style] = $media;
			}
		}

		self::$template_vars['STYLES'] = null;
		foreach ($styles as $style => $media)  
		{
			self::$template_vars['STYLES'] .= '<link type="text/css" rel="stylesheet" '.(!empty($media) ? 'media="'.trim($media).'"' : null).' href="'.config::getPath('templates',true) . '/' . $tmpl . $style .'">';
		}
	}


	private static function loadScripts( $scripts ) 
	{
		$tmpl = config::$template;

		if ( count($scripts) > 0 )
		{
			foreach ($scripts as $script => $in_footer) {
				$system_scripts['/' . $tmpl . $script] = $in_footer;
			}
		}

		self::$template_vars['HEADER_SCRIPTS'] = null;
		self::$template_vars['FOOTER_SCRIPTS'] = null;

		foreach ($system_scripts as $script => $in_footer) {
			if ( $in_footer ) {
				self::$template_vars['FOOTER_SCRIPTS'] .= '<script type="text/javascript" src="'.config::getPath('templates',true).$script.'"></script>';
			} else {
				self::$template_vars['HEADER_SCRIPTS'] .= '<script type="text/javascript" src="'.config::getPath('templates',true).$script.'"></script>';
			}
		}
	}


	private static function parseTemplate()
	{
		// обрабатываем теги частей шаблона {*TAG_NAME*}
		foreach ( self::$template_parts as $replace )
		{	
			if ( substr_count(self::$template , '{*' . $replace . '*}') > 0 )
			{
				self::$template = str_replace('{*' . $replace . '*}', self::getTemplatePart($replace), self::$template);
			}
		}

		// парсим переменные самой системы {:TAG_NAME:}
		foreach (self::$template_vars as $find => $replace) 
		{
			self::$template = str_replace('{:' . $find . ':}', $replace, self::$template);
		}

		return self::$template;
	}	

	# модифицировать загрузку шаблона (Сделать единый файл с информацией по шаблону, вместо Conversion.php и убрать чувствительность к регистру)
	/**
	 * [Собирает шаблон и возвращает его]
	 * @param  [string] $page 		[тип странцицы(page/index)]
	 * @param  [array]  $vars 		[Переменные CMS(Название страницы итд)]
	 * @return [template(HTML)]		[Собранный шаблон]
	 */
	public static function loadTemplate( $page, $vars, $fast = false )
	{
		$tmpl_name = config::$template;
		if ( !empty($tmpl_name) )
		{
			if ( $fast )
			{
				if ( file_exists(config::getPath('templates') . '/' . $tmpl_name . '/' . $page . '.tpl') )

				{

					// require self::$template_dir . $data['tmpl_name'] . '.php';
					self::$template_dir = config::getPath('templates') . '/' . $tmpl_name . '/';
					self::$template_page  = $page;
					self::$template_vars  = $vars;
					self::getTemplate($fast);
					self::parseTemplate();
					

					return self::$template;

				} else {
					throw new tmplException(
						'Error template not found ( ' . config::getPath('templates') . '/' . $tmpl_name . '/' . $page . '.tpl );<br>'. __FILE__ .' ( '. __LINE__ . ' )'
					);
				}
			}
			else
			{
				if ( file_exists(config::getPath('templates') . '/' . $tmpl_name . '/' . $tmpl_name . '.php') )
				{

					self::$template_dir = config::getPath('templates') . '/' . $tmpl_name . '/';
					require self::$template_dir . $tmpl_name . '.php';
					self::$template_page  = $page;
					self::$template_parts = $t_files;
					self::$template_vars  = $vars;
					self::getTemplate($fast);
					self::loadStyles($t_styles);
					self::loadScripts($t_scripts);
					self::parseTemplate();
					
					return self::$template;

				} else {
					throw new tmplException(
						'Error template not found ( ' . config::getPath('templates') . '/' . $tmpl_name . '/' . $tmpl_name . '.php );<br>'. __FILE__ .' ( '. __LINE__ . ' )'
					);
				}
			}

		} else {
			throw new tmplException("Error with template configuration");
		}
	}



	public static function getClassName()
	{
		return self::$className;
	}
}

?>