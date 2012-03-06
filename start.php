<?php 

	function advanced_comments_init(){
		
		// extend css
		elgg_extend_view("css", "advanced_comments/css");
		
		// extend metatags
		elgg_extend_view("js/initialise_elgg", "advanced_comments/js");
		
		// register page handler for nice URL's
		register_page_handler("advanced_comments", "advanced_comments_page_handler");
	}
	
	function advanced_comments_page_handler($page){
		
		switch($page[0]){
			case "load":
				include(dirname(__FILE__) . "/procedures/load.php");
				break;
			default:
				return false;
				break;
		}
	}
	
	function advanced_comments_comments_hook($hook, $type, $return_value, $params){
		$result = $return_value;
		
		if(!empty($params) && is_array($params)){
			if(array_key_exists("entity", $params) && !empty($params["entity"])){
				$entity = $params["entity"];
				
				$use_default = true;
				$setting_name = "comment_settings:" . $entity->getType() . ":" . $entity->getSubtype();
				
				if(isset($_SESSION["advanced_comments"]) && isset($_SESSION["advanced_comments"][$setting_name])){
					list($order, $limit, $auto_load) = explode("|", $_SESSION["advanced_comments"][$setting_name]);
					$use_default = false;
				} elseif($user_guid = get_loggedin_userid()){
					if($setting = get_plugin_usersetting($setting_name, $user_guid, "advanced_comments")){
						list($order, $limit, $auto_load) = explode("|", $setting);
						$use_default = false;
						
						if(!isset($_SESSION["advanced_comments"])){
							$_SESSION["advanced_comments"] = array();
						}
						
						$_SESSION["advanced_comments"][$setting_name] = $setting;
					}
				}
				
				if($use_default){
					$order = "desc";
					$auto_load = "no";
					$limit = 10;
					
					if(!isset($_SESSION["advanced_comments"])){
						$_SESSION["advanced_comments"] = array();
					}
					
					$_SESSION["advanced_comments"][$setting_name] = $order . "|" . $limit . "|" . $auto_load;
				}
				
				$result = elgg_view("advanced_comments/comments", array("entity" => $entity, "comments_order" => $order, "comments_limit" => $limit, "auto_load" => $auto_load));
			}
		}
		
		return $result;
	}
	
	// register default elgg events
	register_elgg_event_handler("init", "system", "advanced_comments_init");
	
	register_plugin_hook("comments", "object", "advanced_comments_comments_hook");


?>