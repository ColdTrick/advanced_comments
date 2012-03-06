<?php 

	$entity = $vars["entity"];
	
	if(!empty($entity)){
		$comments_order = $vars["comments_order"];
		
		if(($comments_order !== "desc") && ($comments_order !== "asc")){
			$comments_order = "desc";
		}
		
		$comments_limit = $vars["comments_limit"];
		if(empty($comments_limit)){
			$comments_limit = 10;
		}
		
		$comments_offset = $vars["comments_offset"];
		if(!($comments_offset > 0)){
			$comments_offset = 0;
		}
		
		$auto_load = $vars["auto_load"];
		
		$max_comments = count_annotations($entity->getGUID(), "", "", "generic_comment");
		$comments_annotations = get_annotations($entity->getGUID(), "", "", "generic_comment", "", "", $comments_limit, $comments_offset, $comments_order);
		
		if (is_array($comments_annotations) && sizeof($comments_annotations) > 0) {
			foreach($comments_annotations as $comment) {
				$html .= elgg_view_annotation($comment, "", false);
			}
		}
		echo "<div id='advanced_comments_container'>";
		echo $html;
		
		
		// more button needed?
		if(($comments_offset + $comments_limit) < $max_comments){
			?>
			<div title="<?php echo $max_comments - $comments_offset - $comments_limit . " " . elgg_echo("more"); ?>" class="contentWrapper" id="advanced_comments_more" onclick="advanced_comments_load(<?php echo $comments_offset + $comments_limit;?>, 'no');">
				<span><?php echo elgg_echo("more"); ?> ...</span>
				<img src="<?php echo $vars["url"]; ?>_graphics/ajax_loader.gif" />
			</div>
			<?php if($auto_load == "yes"){ ?>
			<script type="text/javascript">
				$(document).ready(function(){
					$(window).unbind('scroll.advanced_comments').bind('scroll.advanced_comments', function(){
						if(isScrolledIntoView($("#advanced_comments_more>span:visible"))){
							$("#advanced_comments_more").click();
						}
					});
				});

				function isScrolledIntoView(elem)
				{
				    var docViewTop = $(window).scrollTop();
				    var docViewBottom = docViewTop + $(window).height();

				    var elemTop = $(elem).offset().top;
				    var elemBottom = elemTop + $(elem).height();

				    return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom));
				}								
			</script>
			<?php
			} else {
				?>
				<script type="text/javascript">
					$(document).ready(function(){
						$(window).unbind('scroll.advanced_comments');
					});
				</script>
				<?php 	
			}
		}
		
		echo "</div>";
	}
?>

