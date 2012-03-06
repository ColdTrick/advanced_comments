<?php ?>

function advanced_comments_load(comments_offset, save_settings){
	
	$load_form = $("#advanced_comments_form");
	
	if(save_settings !== ""){
		$load_form.find("input[name='save_settings']").val(save_settings);
	}
	
	$load_form.find("input[name='comments_offset']").val(comments_offset);
	var post_data = $load_form.serialize();
	$("#advanced_comments_more").addClass("loading");
	$.post($load_form.attr("action"), post_data, function(return_data){
		if(return_data){
			if(save_settings == "yes"){
				$("#advanced_comments_container").html(return_data);
			} else {
				$("#advanced_comments_more").before(return_data).remove();
			}
		}
	}); 
}

$(document).ready(function(){
	$("#advanced_comments_form select").change(function(){
		advanced_comments_load(0, "yes");
	});
});