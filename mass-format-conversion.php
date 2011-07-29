<?php
/*
Plugin Name: Mass Format Conversion
Plugin URI: http://sillybean.net/code/
Description: Applies all content filters to posts and comments and saves them back to the database. This is useful if you have been using Textile or Markdown (for example) and you want to switch to plain HTML.
Version: 1.2
Author: Stephanie Leary
Author URI: http://sillybean.net/

Changelog:
= 1.2 = 
* get rid of deprecated notices
* localized strings
= 1.1 =
* Added user capability check (August 3, 2009)  
= 1.0 =  
* First release (February 1, 2009)

Copyright 2008  Stephanie Leary  (email : steph@sillybean.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Hook for adding admin menus
//add_action('admin_menu', 'assign_missing_categories_add_pages');
add_action('admin_menu', 'mass_format_add_pages');

// action function for above hook
function mass_format_add_pages() {
    // Add a new submenu under Options:
	add_submenu_page('edit.php', 'Mass Format Conversion', 'Mass Format Conversion', 'manage_options', __FILE__, 'mass_format_options');
}

// displays the options page content
function mass_format_options() {
	
	// variables for the field and option names 
		$hidden_field_name = 'mass_format_submit_hidden';
	
		// See if the user has posted us some information
		// If they did, this hidden field will be set to 'Y'
		if( $_POST[ $hidden_field_name ] == 'Y' ) {
			mass_format_conversion();
			// Put an options updated message on the screen ?>
			<div class="updated"><p><strong><?php _e('<p>Conversion complete. See below for any reported problems that might need your attention.</p>
													 <p>You may now remove this plugin as well as the text formatting plugin(s) you were using. If you deactivated any plugins that use shortcodes, you should now reactivate them.</p>'); ?></strong></p></div>
		<?php } // Now display the options editing screen ?>
	
    <div class="wrap">
    <?php if( $_POST[ $hidden_field_name ] != 'Y' ) { ?>
	<form method="post" id="mass_format_form">
    <h2><?php _e( 'Mass Format Conversion'); ?></h2>
	<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">
<p><?php _e("This tool applies all content filters to posts and comments and saves them back to the database. This is useful if you have been using Textile or Markdown (for example) and you want to switch to plain HTML."); ?></p>
<p><?php _e("If you are using a plugin that uses short tags -- e.g. [thing] -- <em>and you want to keep using it</em>, you should deactivate it before running this conversion. Otherwise, that too will be converted to its full HTML equivalent."); ?></p>
<p><?php _e("Press the button below to convert the format of all your posts."); ?></p>

	<p class="submit">
	<input type="submit" name="submit" value="<?php _e('Mass Format Posts and Comments'); ?>" class="button-primary" />
	</p>
	</form>
    <?php } // if ?>
    
	<p><?php printf(__("%d queries. "), get_num_queries()); ?><?php printf__(" seconds."), timer_stop(1)); ?></p>
    </div>
    
<?php } // end function mass_format_options() 

function mass_format_conversion() {
	if ( current_user_can('import') ) {  
	?><div class="wrap">
	<h2><?php _e( 'Converting All Posts:'); ?></h2><?php
	flush();
	global $wpdb;
	if (function_exists('remove_all_shortcodes')) remove_all_shortcodes();
	$allposts = $wpdb->get_results("SELECT ID, post_content FROM $wpdb->posts ORDER BY ID");	
	foreach ($allposts as $thispost) {
		// clear from previous iteration
		$my_post = array();
		$my_post['ID'] = $thispost->ID;
		$my_post['post_content'] = apply_filters('the_content', $thispost->post_content);
		if (!empty($my_post['post_content'])) {
			wp_update_post( $my_post );
			printf(__( " Converted post #%d.<br />"), $my_post['ID']);
		}
		else printf(__( " Problem with post #%d. You should check it manually.<br />"), $my_post['ID']); 
		flush();
	}
	echo '<h2>'.__( 'Converting All Comments:').'</h2>'; 
	flush();
	$allcomments = $wpdb->get_results("SELECT comment_ID, comment_content FROM $wpdb->comments ORDER BY comment_ID");	
	foreach ($allcomments as $thiscomment) {
		// clear from previous iteration
		$my_post = array();
		$my_post['comment_ID'] = $thiscomment->comment_ID;
		$my_post['comment_content'] = apply_filters('the_content', $thiscomment->comment_content);
		if (!empty($my_post['comment_content'])) {
			wp_update_comment( $my_post );
			printf(__( " Converted comment #%d.<br />"), $my_post['comment_ID']);
		}
		else printf(__( " Problem with comment #%d. You should check it manually.<br />"), $my_post['comment_ID']);
		flush();
	}
	?> </div> <?php
	}
} 
?>