<?php 

$comments_order = $vars["comments_order"];
$comments_limit = $vars["comments_limit"];
$auto_load = $vars["auto_load"];

?>
<div class="contentWrapper" id="advanced_comments_header">
	<form id="advanced_comments_form" method="post" action="<?php echo $vars["url"]; ?>pg/advanced_comments/load">
		<?php
			echo elgg_echo("advanced_comments:header:order") . ": ";
			echo elgg_view("input/pulldown", array("internalname" => "comments_order", "options_values" => array("asc" => elgg_echo("advanced_comments:header:order:asc"), "desc" => elgg_echo("advanced_comments:header:order:desc")), "value" => $comments_order));

			echo " " . elgg_echo("advanced_comments:header:limit") . ": ";				
			echo elgg_view("input/pulldown", array("internalname" => "comments_limit", "options" => array(5,10,25,50,100), "value" => $comments_limit));

			echo " " . elgg_echo("advanced_comments:header:auto_load") . ": ";				
			echo elgg_view("input/pulldown", array("internalname" => "auto_load", "options_values" => array("no" => elgg_echo("option:no"), "yes" => elgg_echo("option:yes")), "value" => $auto_load));
						
		?>
		<input type="hidden" name="guid" value="<?php echo $vars["entity"]->getGUID();?>" />
		<input type="hidden" name="comments_offset" value="0" />
		<input type="hidden" name="save_settings" value="no" />
	</form>
</div>