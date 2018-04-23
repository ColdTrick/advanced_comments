define(function(require) {
	
	var $ = require('jquery');
	var Ajax = require('elgg/Ajax');
	var elgg = require('elgg');
	
	var comments_loading = false;
	
	/**
	 * Helper function to see if an element is in the viewport of a user
	 */
	var advanced_comments_is_scrolled_into_view = function(elem){
	    var docViewTop = $(window).scrollTop();
	    var docViewBottom = docViewTop + $(window).height();

	    var elemTop = $(elem).offset().top;
	    var elemBottom = elemTop + $(elem).height();

	    return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom));
	};

	/**
	 * General function to load a new set of comments
	 */
	var advanced_comment_load_comments = function(data) {
		
		if (comments_loading) {
			return;
		}
		comments_loading = true;
		
		$more_button = $('#advanced-comments-more');
		$more_button.hide();
		
		var ajax = new Ajax();
		ajax.view('advanced_comments/comments', {
			data: data,
			success: function(result) {
				//if (data.auto_load === 'yes' && parseInt(data.offset) !== 0) {
					
					// add comments to the list
					$('.comments-list').append($(result).filter('.comments-list').html());
					
					// replace more button
					$more_button.replaceWith($(result).filter('#advanced-comments-more'));
				//} else {
				//	$('#advanced-comment-list').html(result).focus();
				//}
			},
			complete: function() {
				comments_loading = false;
			}
		});
	};
	
	/**
	 * Save new display preferences
	 */
	var advanced_comments_change = function() {
		
		var ajax = new Ajax();
		var $form = $(this).closest('form');
		var data = ajax.objectify($form);
		
		advanced_comment_load_comments(data);
	};
	
	/**
	 * Ajaxify the comments pagination
	 */
	var advanced_comments_pagination = function() {
		
		var ajax = new Ajax();
		var $form = $('#advanced-comments-form');
		
		var data = ajax.objectify($form);
		var query = elgg.parse_url($(this).prop('href'), 'query', true);
		
		data.offset = query.offset;
		data.save_settings = null;
		
		advanced_comment_load_comments(data);
		
		return false;
	};
	
	/**
	 * Check if the autoload more button is scrolled into view
	 */
	var advanced_comments_check_autoload = function() {
		
		if (!$('#advanced-comments-more').length) {
			// no autoload placeholder found
			return;
		}
		
		if (!advanced_comments_is_scrolled_into_view('#advanced-comments-more')) {
			// placeholder not in view
			return;
		}
		
		$('#advanced-comments-more a').click();
	};
	
	/**
	 * Used when the autoload more button is clicked
	 */
	var advanced_comments_load_more = function() {
		
		var data = elgg.parse_url($(this).prop('href'), 'query', true);

		advanced_comment_load_comments(data);
		
		return false;
	};

	$(document).on('change', '#advanced-comments-form select', advanced_comments_change);
	$(document).on('click', '#advanced-comment-list .elgg-pagination a', advanced_comments_pagination);
	$(document).on('click', '#advanced-comments-more a', advanced_comments_load_more);
	$(window).on('scroll', advanced_comments_check_autoload);
});
