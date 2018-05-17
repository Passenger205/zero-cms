<?php  

if (!defined('_PLUGSECURE_')) {
	die('Direct call permited');
}

# по умолчанию ошибка 404 
if (isset($url_data['action']))
{
	$db = new database;

	$sql = "SELECT * FROM `errors` WHERE `code` = :code LIMIT 1";
	$get_error = $db->prepareQuery( $sql, [':code'=>$url_data['action']] );

	$db->disconnect();

	if ( $error = $get_error->fetch() )
	{
		functions::setTitle($error['title']);
		functions::toContent('<div class="error">');
		functions::toContent(template::loadTemplate('_ERROR', $error, true));
		functions::toContent('</div>');
	} else {
		handler::httpError(404);
	}
} else {
	handler::httpError(404);
}

?>