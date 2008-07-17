<?php
/*
Plugin Name: Enhanced Links
Version: 4.2.3
Plugin URI: http://enhanced-links.vincentprat.info
Description: Allows to get better control over the links listing. Also provides a widget view of the links. Please make a donation if you are satisfied.
Author: Vincent Prat
Author URI: http://www.vincentprat.info
*/

/*  Copyright 2006 Vincent Prat  (email : vpratfr@yahoo.fr)

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


//############################################################################
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { 
	die('You are not allowed to call this page directly.'); 
}
//############################################################################

//############################################################################
// you can deactivate the javascript effect by setting the next variable to false
define('ENHANCED_LINKS_USE_JAVASCRIPT', true);					
//############################################################################

//############################################################################
// plugin directory
define('ENHANCED_LINKS_DIR', dirname (__FILE__));	

// i18n plugin domain 
define('ENHANCED_LINKS_I18N_DOMAIN', 'enhanced-links');

// The options of the plugin
define('ENHANCED_LINKS_PLUGIN_OPTIONS', 'enh_links_plugin_options');	
define('ENHANCED_LINKS_WIDGET_OPTIONS', 'enh_links_widget_options');	

// For WordPress 2.5 compatibility
if (!defined('WP_PLUGIN_URL')) {
	define(WP_PLUGIN_URL, get_option('siteurl') . '/wp-content/plugins');
}
//############################################################################

//############################################################################
// Include the plugin files
require_once(ENHANCED_LINKS_DIR . '/includes/plugin-class.php');
require_once(ENHANCED_LINKS_DIR . '/includes/widget-class.php');
//############################################################################

//############################################################################
// Init the plugin classes
global $enh_links_plugin, $enh_links_widget;

$enh_links_plugin = new EnhancedLinksPlugin();
$enh_links_widget = new EnhancedLinksWidget();
//############################################################################

//############################################################################
// Load the plugin text domain for internationalisation
if (!function_exists('enh_links_init_i18n')) {
	function enh_links_init_i18n() {
		load_plugin_textdomain(ENHANCED_LINKS_I18N_DOMAIN, 'wp-content/plugins/enhanced-links');
	} // function enh_links_init_i18n()

	enh_links_init_i18n();
} // if (!function_exists('enh_links_init_i18n'))
//############################################################################

//############################################################################
// Add filters and actions
add_action('widgets_init', array(&$enh_links_widget, 'register_widget'));

if (is_admin()) {
	add_action(
		'activate_enhanced-links/enhanced-links.php',
		array(&$enh_links_plugin, 'activate'));
	add_action(
		'admin_menu', 
		array(&$enh_links_plugin, 'add_javascript'), 1);
} else {
	if (ENHANCED_LINKS_USE_JAVASCRIPT) {	
		add_action(
			'wp_head', 
			array(&$enh_links_plugin, 'add_javascript'), 1);
		
		add_action(
			'wp_head', 
			array(&$enh_links_plugin, 'render_page_head'));
	}
}
	
//############################################################################

//############################################################################
// Template functions for direct use in themes
if (!function_exists('enh_links_list_links')) {
function enh_links_list_links($args = '') {
	global $enh_links_plugin;
	$enh_links_plugin->list_links($args);
} // function enh_links_list_links
} // if (!function_exists('enh_links_list_links'))
//############################################################################

?>