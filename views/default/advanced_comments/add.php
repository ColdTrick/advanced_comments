<?php 
	if(isloggedin()){
		echo elgg_view("comments/forms/edit", $vars);
	}

?>