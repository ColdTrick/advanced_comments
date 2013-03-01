<?php

$plugin = $vars["entity"];

$yesno_options = array(
		"yes" => elgg_echo("option:yes"),
		"no" => elgg_echo("option:no"),
);

echo elgg_echo("advanced_comments:settings:show_login_form");
echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[show_login_form]", "options_values" => $yesno_options, "value" => $plugin->show_login_form));


