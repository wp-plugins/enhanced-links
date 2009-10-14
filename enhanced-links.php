<?php
/*
Plugin Name: Enhanced Links
Version: 4.0.0
Plugin URI: http://enhanced-links.vincentprat.info
Description: Allows to get better control over the links listing. Also provides a widget view of the links. Please make a donation if you are satisfied.
Author: Vincent Prat
Author URI: http://www.vincentprat.info
*/

/*  Copyright 2006 Vincent Prat

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
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Version of the plugin
define('ENHANCED_LINKS_CURRENT_VERSION', '4.0.0' );

// i18n plugin domain 
define('ENHANCED_LINKS_I18N_DOMAIN', 'enhanced-links');

// Options
define('ENHANCED_LINKS_VERSION_OPTION', 'enh_links_version');

// you can deactivate the javascript effect by setting the next variable to false
define('ENHANCED_LINKS_USE_JAVASCRIPT', true);

/**
 * Initialise the internationalisation domain
 */
$enh_links_is_i18n_setup = false;
function enh_links_init_i18n() {
	global $enh_links_is_i18n_setup;

	if ($enh_links_is_i18n_setup == false) {
		load_plugin_textdomain(ENHANCED_LINKS_I18N_DOMAIN, 'wp-content/plugins/enhanced-links');
		$enh_links_is_i18n_setup = true;
	}
}

/**
 * Plugin activation
 */
add_action('activate_enhanced-links/enhanced-links.php','enh_links_plugin_activation');
function enh_links_plugin_activation() {
	$installed_version = enh_links_get_installed_version();
	
	if ( $installed_version==enh_links_get_current_version() ) {
		// do nothing
	} else if ( $installed_version=='' ) {
		// Add all options, nothing already installed
		add_option( 
			ENHANCED_LINKS_VERSION_OPTION, 
			enh_links_get_current_version(),
			'Enhanced links version number');			
	} else if ( $installed_version<='3.1' ) {
		remove_option(ENHANCED_LINKS_SHOW_SYMBOL_OPTION);
		remove_option(ENHANCED_LINKS_HIDE_SYMBOL_OPTION);
		remove_option(ENHANCED_LINKS_IS_SYMBOL_BEFORE_OPTION);
		remove_option(ENHANCED_LINKS_SHOW_LINK_DESCRIPTION_OPTION);
		remove_option(ENHANCED_LINKS_EFFECT_OPTION);
	}
	
	// Update version number
	update_option( ENHANCED_LINKS_VERSION_OPTION, enh_links_get_current_version() );	
}

/**
 * Wrapper for the option 'enhanced_links_version'
 */
function enh_links_get_installed_version() {
	return get_option( ENHANCED_LINKS_VERSION_OPTION );
}

/**
 * Wrapper for the defined constant ENHANCED_LINKS_CURRENT_VERSION
 */
function enh_links_get_current_version() {	
	return ENHANCED_LINKS_CURRENT_VERSION;
}

if (ENHANCED_LINKS_USE_JAVASCRIPT) {
/**
 * Function to insert the javascript in the page header
 */
add_action('wp_head', 'enh_links_insert_javascript');
function enh_links_insert_javascript() {
?>
<script src="<?php echo get_bloginfo('wpurl'); ?>/wp-content/plugins/enhanced-links/enhanced-links.js" type="text/javascript" ></script>
<?php
}
}

/**
* Function to list the links in a template
*/
global $global_ul_index;
$global_ul_index = 0;

function enh_links_list_links($args = '') {	
	global $global_ul_index;
	$global_ul_index++;
	
	$defaults = array(
		// Options of the plugin
		'hide_invisible' 	=> 1,
		'show_description' 	=> 0,
		'show_images' 		=> 1,	
		'show_rating'		=> 0,
		'button_color' 		=> '#AA0000',
		'expand_text' 		=> '&raquo;',
		'leaf_text' 		=> '-',
		'contract_text' 	=> '&laquo;',
		'contract_children' => 1,

		// Those are not set by the options
		'title_before' 		=> '<span class="link-cat-title" style="cursor: pointer;">', 
		'title_after' 		=> '</span>',
		'before' 			=> '<li>',
		'after' 			=> '</li>',
		'between' 			=> '&nbsp;<br/>',
		'wp_list_bookmarks' => 1,
		'orderby' 			=> 'name',
		'order' 			=> 'ASC',
		'limit'				=> -1,
		'category'			=> ''
	);
	
	$r = wp_parse_args( $args, $defaults );

	if (ENHANCED_LINKS_USE_JAVASCRIPT) {
?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('ul.enhanced-links-<?php echo $global_ul_index; ?>').enhancedLinks({
					// Override here the default settings for the plugin
					expandText	: '<?php echo $r['expand_text']; ?>',
					contractText: '<?php echo $r['contract_text']; ?>',
					leafText	: '<?php echo $r['leaf_text']; ?>',
					buttonColor	: '<?php echo $r['button_color']; ?>',
					contractChildren: <?php echo $r['contract_children']; ?>
				});
			});
		</script>
<?php	
	}

	echo '<ul class="enhanced-links-' . $global_ul_index . '">';
	wp_list_bookmarks($r);
	echo '</ul>';
}

/**
* Function to list the links in a widget
*/
function enh_links_widget($args) {
	extract($args, EXTR_SKIP);
	$options = get_option('enh_links_widget');
	$title = empty($options['widget_title']) ? __('Links', ENHANCED_LINKS_I18N_DOMAIN) : $options['widget_title'];

	echo $before_widget; 
		echo $before_title . $title . $after_title;
		enh_links_list_links($options); 
	echo $after_widget;
}

/**
* Widget settings window
*/
function enh_links_widget_control() {
	// Get our options
	//--
	$options = get_option('enh_links_widget');
	if ( !is_array($options) ) {
		$options = array(
			'widget_title'		=> '', 
			'hide_invisible' 	=> 1,
			'show_description' 	=> 0,
			'show_images' 		=> 1,	
			'show_rating'		=> 0,
			'contract_children' => 1,
			'button_color' 		=> '#AA0000',
			'expand_text' 		=> '&raquo;',
			'contract_text' 	=> '&laquo;',
			'leaf_text' 		=> '-'
		);
	}
	
	// See if we're handling a form submission
	//--
	if ( $_POST['enh_links-submit'] ) {
		$options['widget_title'] 	= strip_tags(stripslashes($_POST['enh_links-widget_title']));
		$options['hide_invisible'] 	= $_POST['enh_links-hide_invisible'] ? 1 : 0;
		$options['show_description']= $_POST['enh_links-show_description'] ? 1 : 0;
		$options['show_images']		= $_POST['enh_links-show_images'] 	? 1 : 0;
		$options['show_rating']		= $_POST['enh_links-show_rating'] 	? 1 : 0;
		$options['contract_children']= $_POST['enh_links-contract_children'] ? 1 : 0;
		$options['button_color']	= strip_tags(stripslashes($_POST['enh_links-button_color']));
		$options['expand_text']		= strip_tags(stripslashes($_POST['enh_links-expand_text']));
		$options['contract_text']	= strip_tags(stripslashes($_POST['enh_links-contract_text']));
		$options['leaf_text']		= strip_tags(stripslashes($_POST['enh_links-leaf_text']));
		
		update_option('enh_links_widget', $options);
	}

	// The widget control
	//--
	$widget_title = htmlspecialchars($options['widget_title'], ENT_QUOTES);
?>
	<p>
		<label><?php _e('Title:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/>
		<input style="width: 250px;" id="enh_links-widget_title" name="enh_links-widget_title" type="text" value="<?php echo $widget_title; ?>" /></label>
	</p>
	
	<br/>
	<p><strong>&raquo; <?php _e('Links listing options', ENHANCED_LINKS_I18N_DOMAIN); ?></strong></p>
	<p>
		<label><input class="checkbox" <?php enh_links_checked($options['hide_invisible']); ?> id="enh_links-hide_invisible" name="enh_links-hide_invisible" type="checkbox"> 
		<?php _e('Hide links marked as "invisible"', ENHANCED_LINKS_I18N_DOMAIN); ?></label>
	</p>
	<p>
		<label><input class="checkbox" <?php enh_links_checked($options['show_description']); ?> id="enh_links-show_description" name="enh_links-show_description" type="checkbox"> 
		<?php _e('Show link description', ENHANCED_LINKS_I18N_DOMAIN); ?></label>
	</p>
	<p>
		<label><input class="checkbox" <?php enh_links_checked($options['show_images']); ?> id="enh_links-show_images" name="enh_links-show_images" type="checkbox"> 
		<?php _e('Show link image', ENHANCED_LINKS_I18N_DOMAIN); ?></label>
	</p>
	<p>
		<label><input class="checkbox" <?php enh_links_checked($options['show_rating']); ?> id="enh_links-show_rating" name="enh_links-show_rating" type="checkbox"> 
		<?php _e('Show link rating', ENHANCED_LINKS_I18N_DOMAIN); ?></label>
	</p>
	
<?php 	if (ENHANCED_LINKS_USE_JAVASCRIPT) { ?>
	<br/>
	<p><strong>&raquo; <?php _e('Javascript options', ENHANCED_LINKS_I18N_DOMAIN); ?></strong></p>
	<p>
		<label><input class="checkbox" <?php enh_links_checked($options['contract_children']); ?> id="enh_links-contract_children" name="enh_links-contract_children" type="checkbox"> 
		<?php _e('Contract link categories', ENHANCED_LINKS_I18N_DOMAIN); ?></label>
	</p>
	<p>
		<label><?php _e('Button color:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/>
		<input style="width: 250px;" id="enh_links-button_color" name="enh_links-button_color" type="text" value="<?php echo $options['button_color']; ?>" /></label>
	</p>
	<p>
		<label><?php _e('Expand button text:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/> 
		<input style="width: 250px;" id="enh_links-expand_text" name="enh_links-expand_text" type="text" value="<?php echo $options['expand_text']; ?>" /></label>
	</p>
	<p>
		<label><?php _e('Contract button text:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/>
		<input style="width: 250px;" id="enh_links-contract_text" name="enh_links-contract_text" type="text" value="<?php echo $options['contract_text']; ?>" /></label>
	</p>
	<p>
		<label><?php _e('Text when category has no child:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/>
		<input style="width: 250px;" id="enh_links-leaf_text" name="enh_links-leaf_text" type="text" value="<?php echo $options['leaf_text']; ?>" /></label>
	</p>
<?php } ?>
	
	<input type="hidden" id="enh_links-submit" name="enh_links-submit" value="1" />
<?php 	
}

/**
* Register the widget
*/
add_action('widgets_init', 'enh_links_widget_init');
function enh_links_widget_init() {
	register_sidebar_widget(__('Enhanced Links', ENHANCED_LINKS_I18N_DOMAIN), 'enh_links_widget');
	register_widget_control(__('Enhanced Links', ENHANCED_LINKS_I18N_DOMAIN), 'enh_links_widget_control', 400, 250);
}

/**
* Helper function to output the checked attribute of a checkbox
*/
function enh_links_checked($var) {
	if ($var==1 || $var==true)
		echo 'checked="checked"';
}
?>