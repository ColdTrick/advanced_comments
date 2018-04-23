<?php

if (!elgg_extract('advanced_comments_show_list_header', $vars)) {
	return;
}

echo elgg_view_form('advanced_comments/header', [], $vars);
