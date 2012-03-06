<?php 

	$entity = $vars["entity"];
	
	echo elgg_view("advanced_comments/add", $vars);
	echo elgg_view("advanced_comments/header", $vars);
	echo elgg_view("advanced_comments/list", $vars);

?>