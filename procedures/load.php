<?php 

	$guid = (int) get_input("guid");
	$limit = (int) max(get_input("comments_limit", 10), 0);
	$offset = (int) max(get_input("comments_offset", 0), 0);
	$order = get_input("comments_order", "desc");
	$auto_load = get_input("auto_load");
	$save_settings = get_input("save_settings");

	if(!in_array($order, array("asc", "desc"))){
		$order = "desc";
	}
	
	if(!in_array($auto_load, array("yes", "no"))){
		$auto_load = "no";
	}

	if($entity = get_entity($guid)){
		if($save_settings == "yes"){
			$setting_name = "comment_settings:" . $entity->getType() . ":" . $entity->getSubtype();
			$setting = $order . "|" . $limit . "|" . $auto_load;
			
			if(!isset($_SESSION["advanced_comments"])){
				$_SESSION["advanced_comments"] = array();
			}
			
			$_SESSION["advanced_comments"][$setting_name] = $setting;
			
			if($user_guid = get_loggedin_userid()){
				set_plugin_usersetting($setting_name, $setting, $user_guid, "advanced_comments");
			}
		}
		
		echo elgg_view("advanced_comments/list", array("entity" => $entity, "comments_limit" => $limit, "comments_offset" => $offset, "comments_order" => $order, "auto_load" => $auto_load));
	}

?>