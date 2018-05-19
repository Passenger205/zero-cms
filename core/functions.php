<?php  
# Проверить необходимость этого класса

if ( !defined('_PLUGSECURE_') )
	die('Прямой вызов модуля запрещен');


class functions implements StorableObject
{
	private static $className = 'Функции';

	
	public static function toContent($content, $class = null)
	{
		if ( $class )
			config::$global_cms_vars['CONTENT'] .= '<div class="' . $class . '">' . $content . '</div>';
		else 
			config::$global_cms_vars['CONTENT'] .= $content; 
	}


	public static function setTitle( $title )
	{
		config::$global_cms_vars['PAGE_TITLE'] = $title;
	}

	public static function generatePage( $type )
	{
		config::$global_cms_vars['PAGE'] = template::loadTemplate(config::$template, $type, config::$global_cms_vars);
	}

	public static function stripPost( $text )
	{
		$check_pre_tags = strpos($text, '<pre');

		if ( $check_pre_tags < config::$strip_posts && (!empty($check_pre_tags)) || $check_pre_tags != 0 ) 
		{
			return substr(strip_tags($text), 0, strpos($text, ' ', $check_pre_tags)) . '...';
		}
		else
		{
			return substr(strip_tags($text), 0, strpos($text, ' ', config::$strip_posts)) . '...';
		}
	}

	public static function dump($var, $full = false)
	{
		if ( $full )
		{
			?>
			<div style="padding: 10px; width: 80%; margin: 10px auto; background: #fff; border: 3px solid #FF0000;">
			<p style="font-size: 15px;"><b>VAR_DUMP</b></p>
				<pre>
					<?php var_dump($var) ?>
				</pre>
			</div>
			<?php
		} else {
			?>
			<div style="padding: 10px; width: 80%; margin: 10px auto; background: #fff; border: 3px solid #FF0000;">
				<p style="font-size: 15px;"><b>PRINT_R</b></p>
				<pre>
				<?=print_r($var)?>
				</pre>
			</div>
			<?php
		}
	}


	public static function getClassName()
	{
		return self::$className;
	}
}

?>