<?php 

if (!defined('_PLUGSECURE_'))
  die('Прямой вызов модуля запрещен!');


if (empty($url_data['action'])) 
{
	functions::setTitle('Новости');

	$db = new database;
	# на главной будет выводиться 5 постов, а в полном списке 10(все с ПАГИНАЦИЕЙ)
	if ( isset($url_data['module']) ) {
		$posts_q = $db->query("SELECT * FROM `posts` ORDER BY `date` LIMIT 10");
	} else {
		$posts_q = $db->query("SELECT * FROM `posts` ORDER BY `date` LIMIT 5");
	}
	$db->disconnect();

	$posts_grid = '';

	while ( $post = $posts_q->fetch() )
	{
		$uri = surl::genUri('posts','show',[ 'id'=>$post['id'] ]);
		
		if ( config::$s_url ) 
		{
			$try_alias = surl::getAlias($uri);
			
			if ( $try_alias )
			{
				$post['id'] = $try_alias;
			} else {
				$post['id'] = $uri;
			}
		} else {
			$post['id'] = $uri;
		}

		$post['text'] = functions::stripPost($post['text']);

		$posts_grid .= template::loadTemplate('_POSTS', $post, true);
	}

	functions::toContent($posts_grid, 'posts');
	unset($posts_grid, $post, $posts_q);

} elseif ( $url_data['action'] == 'show' && !empty($url_data['params']['id']) ) {

	$db = new database;
	$post_q = $db->prepareQuery("SELECT * FROM `posts` WHERE `id` = :id LIMIT 1", [':id' => $url_data['params']['id']]);
	$post = $post_q->fetch();
	$db->disconnect();

	functions::setTitle( $post['title'] );
	functions::toContent( template::loadTemplate('_SINGLE_POST_' . $post['class'], $post, true), 'single_post' );

	unset($post_q,$post);

} else {
	throw new postsException("Error Processing module");
}


?>