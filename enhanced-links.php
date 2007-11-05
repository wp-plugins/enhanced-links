<?php
/*
Plugin Name: enhanced links
Version: 3.0.3
Plugin URI: http://enhanced-links.vincentprat.info
Description: Allows to get better control over the links listing. Please make a donation if you are satisfied.
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

// Version of the plugin
define('ENHANCED_LINKS_CURRENT_VERSION', '3.0.3' );

// i18n plugin domain 
define('ENHANCED_LINKS_I18N_DOMAIN', 'enhanced-links');

// Options
define('ENHANCED_LINKS_VERSION_OPTION', 'enh_links_version');
define('ENHANCED_LINKS_SHOW_SYMBOL_OPTION', 'enh_links_show_symbol');
define('ENHANCED_LINKS_HIDE_SYMBOL_OPTION', 'enh_links_hide_symbol');
define('ENHANCED_LINKS_IS_SYMBOL_BEFORE_OPTION', 'enh_links_is_symbol_before');
define('ENHANCED_LINKS_SHOW_LINK_DESCRIPTION_OPTION', 'enh_links_show_link_description');
define('ENHANCED_LINKS_EFFECT_OPTION', 'enh_links_effect');

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
			ENHANCED_LINKS_SHOW_SYMBOL_OPTION,
			'&raquo;',
			'The symbol to display when the category can be shown.' );
		add_option(
			ENHANCED_LINKS_HIDE_SYMBOL_OPTION,
			'&laquo;',
			'The symbol to display when the category can be hidden.' );
		add_option(
			ENHANCED_LINKS_IS_SYMBOL_BEFORE_OPTION,
			true,
			'Set this to true if you want to show the symbol before the category name.' );
		add_option(
			ENHANCED_LINKS_SHOW_LINK_DESCRIPTION_OPTION,
			false,
			'Set this to true if you want to show the link description.' );
		add_option(
			ENHANCED_LINKS_EFFECT_OPTION,
			'none',
			'The effect used to show/hide the links.' );
		add_option( 
			ENHANCED_LINKS_VERSION_OPTION, 
			enh_links_get_current_version(),
			'Enhanced links version number');	
	}
	
	// Update version number
	update_option( ENHANCED_LINKS_VERSION_OPTION, enh_links_get_current_version() );	
}

/**
 * Add options page
 */
add_action( 'admin_menu', 'enh_links_add_pages' );
function enh_links_add_pages() {
	enh_links_init_i18n();

	add_options_page( __('Enhanced Links', ENHANCED_LINKS_I18N_DOMAIN), 
		__('Enhanced Links', ENHANCED_LINKS_I18N_DOMAIN), 
		8, 
		'options-general.php?page=enhanced-links/enhanced-links_options_page.php' );
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

/**
 * Wrapper for the option 'ENHANCED_LINKS_SHOW_SYMBOL_OPTION'
 */
function enh_links_get_show_symbol() {
	return get_option( ENHANCED_LINKS_SHOW_SYMBOL_OPTION );
}

/**
 * Wrapper for the option 'ENHANCED_LINKS_SHOW_SYMBOL_OPTION'
 */
function enh_links_set_show_symbol($value) {
	return update_option( ENHANCED_LINKS_SHOW_SYMBOL_OPTION, $value );
}

/**
 * Wrapper for the option 'ENHANCED_LINKS_HIDE_SYMBOL_OPTION'
 */
function enh_links_get_hide_symbol() {
	return get_option( ENHANCED_LINKS_HIDE_SYMBOL_OPTION );
}

/**
 * Wrapper for the option 'ENHANCED_LINKS_HIDE_SYMBOL_OPTION'
 */
function enh_links_set_hide_symbol($value) {
	return update_option( ENHANCED_LINKS_HIDE_SYMBOL_OPTION, $value );
}

/**
 * Wrapper for the option 'ENHANCED_LINKS_IS_SYMBOL_BEFORE_OPTION'
 */
function enh_links_get_is_symbol_before() {
	return get_option( ENHANCED_LINKS_IS_SYMBOL_BEFORE_OPTION );
}

/**
 * Wrapper for the option 'ENHANCED_LINKS_IS_SYMBOL_BEFORE_OPTION'
 */
function enh_links_set_is_symbol_before($value) {
	return update_option( ENHANCED_LINKS_IS_SYMBOL_BEFORE_OPTION, $value );
}

/**
 * Wrapper for the option 'ENHANCED_LINKS_SHOW_LINK_DESCRIPTION_OPTION'
 */
function enh_links_get_show_link_description() {
	return get_option( ENHANCED_LINKS_SHOW_LINK_DESCRIPTION_OPTION );
}

/**
 * Wrapper for the option 'ENHANCED_LINKS_SHOW_LINK_DESCRIPTION_OPTION'
 */
function enh_links_set_show_link_description($value) {
	return update_option( ENHANCED_LINKS_SHOW_LINK_DESCRIPTION_OPTION, $value );
}

/**
 * Wrapper for the option 'ENHANCED_LINKS_EFFECT_OPTION'
 */
function enh_links_get_effect() {
	return get_option( ENHANCED_LINKS_EFFECT_OPTION );
}

/**
 * Wrapper for the option 'ENHANCED_LINKS_EFFECT_OPTION'
 */
function enh_links_set_effect($value) {
	return update_option( ENHANCED_LINKS_EFFECT_OPTION, $value );
}

/**
 * Get the list of categories 
 */ 
function enh_links_get_categories() {
	global $wpdb;
	
	if (isset($wpdb->linkcategories)) {
		// For wordpress 1.5.x and 2.0.x
		return $wpdb->get_results(
			"SELECT cat_id, cat_name 
			 FROM $wpdb->linkcategories");
	} else if (isset($wpdb->terms)) {
		// For wordpress v2.3+
		return $wpdb->get_results(
			"SELECT 
				$wpdb->terms.term_id AS cat_id, $wpdb->terms.name AS cat_name
			FROM 
				$wpdb->terms JOIN $wpdb->term_taxonomy 
					ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
			WHERE 
				($wpdb->term_taxonomy.taxonomy = 'link_category')
				AND ($wpdb->term_taxonomy.count > 0)");
	} else {
		// For wordpress v2.1+
		return $wpdb->get_results(
			"SELECT cat_id, cat_name 
			 FROM $wpdb->categories 
			 WHERE link_count>0 
			 ORDER BY cat_name");
	} 
}

/**
 * Get a category given its ID
 */ 
function enh_links_get_category($id) {
	global $wpdb;
	
	if (isset($wpdb->linkcategories)) {
		// For wordpress 1.5.x and 2.0.x
		return $wpdb->get_results(
			"SELECT cat_id, cat_name 
			 FROM $wpdb->linkcategories
			 WHERE cat_id = $id 
			 ORDER BY cat_name");
	} else if (isset($wpdb->terms)) {
		// For wordpress v2.3+
		return $wpdb->get_results(
			"SELECT 
				$wpdb->terms.term_id AS cat_id, $wpdb->terms.name AS cat_name
			FROM 
				$wpdb->terms JOIN $wpdb->term_taxonomy 
					ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
			WHERE 
				($wpdb->term_taxonomy.taxonomy = 'link_category')
				AND ($wpdb->term_taxonomy.count > 0)
				AND $wpdb->terms.term_id = $id");
	} else {
		// For wordpress v2.1+
		return $wpdb->get_results(
			"SELECT cat_id, cat_name 
			 FROM $wpdb->categories 
			 WHERE link_count > 0 
				AND cat_id=$id 
			 ORDER BY cat_name");
	} 
}

/**
 * Function to insert the javascript in the page header
 */
add_action('wp_head', 'enh_links_insert_javascript');
$enh_links_global_category_cache = enh_links_get_categories();

function enh_links_insert_javascript() {
	global $enh_links_global_category_cache;
?>
<!-- Start Of Script Generated By Enhanced Links -->
<script type="text/javascript">
	var enh_links_js_categories = new Array(<?php echo count($enh_links_global_category_cache); ?>);
<?php
	// Declare the JS variables
	$i = 0;
	foreach ($enh_links_global_category_cache as $cat) {	
		$clean_cat_name = str_replace("'","\'",$cat->cat_name);
		echo "\tenh_links_js_categories[$i] = new Array($cat->cat_id, '$clean_cat_name', true);\n";
		$i++;
	}
?>
	function enh_links_showContent(content, effect) {
		if (effect=='jQuery') {
			jQuery(content).slideDown();
		} else if (effect=='scriptaculous') {
			new Effect.BlindDown(content);
		} else {
			content.style.display = 'block';
		}
	}
	
	function enh_links_hideContent(content, effect) {
		if (effect=='jQuery') {
			jQuery(content).slideUp();
		} else if (effect=='scriptaculous') {
			new Effect.BlindUp(content);
		} else {
			content.style.display = 'none';
		}
	}

	function enh_links_expandCategory(categoryId) {
		var symbolBeforeCatName = <?php if (enh_links_get_is_symbol_before()) echo "true"; else echo "false"; ?>;
		var showSymbol = <?php echo "'" . enh_links_get_show_symbol() . "'"; ?>;
		var hideSymbol = <?php echo "'" . enh_links_get_hide_symbol() . "'"; ?>;
		var effect = <?php echo "'" . enh_links_get_effect() . "'"; ?>;
		
		for (var i=0; i<enh_links_js_categories.length; i++) {
			var currentId = enh_links_js_categories[i][0];
			var currentName = enh_links_js_categories[i][1];
			var currentlyHidden = enh_links_js_categories[i][2];
			var currentButton = document.getElementById('categoryButton' + currentId);
			var currentContent = document.getElementById('categoryContent' + currentId);
			if (currentId==categoryId) {
				// Expand this category	or contract it if it was expanded before
				if (currentlyHidden) {
					enh_links_showContent(currentContent, effect);
					currentButton.innerHTML = hideSymbol;
					enh_links_js_categories[i][2] = false;
				} else {
					enh_links_hideContent(currentContent, effect);
					currentButton.innerHTML = showSymbol;
					enh_links_js_categories[i][2] = true;
				} 
			} else {
				// Contract this category								
				if ((typeof currentButton!='undefined') 
						&& (currentButton!=null) 
						&& (currentButton.innerHTML!=showSymbol)) {
					enh_links_hideContent(currentContent, effect);
					currentButton.innerHTML = showSymbol;
					enh_links_js_categories[i][2] = true;
				} 
			}
		}
	}
</script>
<!-- End Of Script Generated By Enhanced Links -->
<?php
}

/**
 * Function to insert the html code for the link categories
 */
function enh_links_insert_categories() {
	global $enh_links_global_category_cache;
	
	foreach ($enh_links_global_category_cache as $cat) {	
?>
		<h2><?php 
			if (enh_links_get_is_symbol_before()) {
				echo '<a id="categoryButton'. $cat->cat_id . '">' . enh_links_get_show_symbol() . '</a> &nbsp; ';
			}
		?><a href='javascript:enh_links_expandCategory(<?php echo $cat->cat_id; ?>);' 
			title='<?php _e("Show/Hide links", ENHANCED_LINKS_I18N_DOMAIN); ?>'><?php echo $cat->cat_name ?></a><?php 
			if (!enh_links_get_is_symbol_before()) {
				echo '&nbsp;<a id="categoryButton'. $cat->cat_id . '">' . enh_links_get_show_symbol() . '</a>';
			}
		?>
		</h2>
		<div id="categoryContent<?php echo $cat->cat_id; ?>" style="display: none;">
			<ul>
				<?php get_links( 
					$cat->cat_id, 
					'<li>',       
					'</li>',      
					'<br/>',  
					false, 'name', 
					enh_links_get_show_link_description(), 
					false, -1, 1, true ); ?>
			</ul>
		</div>  	
<?php
	} 	
}

/**
 * Function to insert the html code for a particular link category
 */
function enh_links_insert_category($id) {
	$cat = enh_links_get_category($id);
	if (!isset($cat) || $cat==null) {
		return;
	}
	$cat = $cat[0];
?>
	<h2><?php 
		if (enh_links_get_is_symbol_before()) {
			echo '<a id="categoryButton'. $cat->cat_id . '">' . enh_links_get_show_symbol() . '</a> &nbsp; ';
		}
	?><a href='javascript:enh_links_expandCategory(<?php echo $cat->cat_id; ?>);' 
		title='<?php _e("Show/Hide links", ENHANCED_LINKS_I18N_DOMAIN); ?>'><?php echo $cat->cat_name ?></a><?php 
		if (!enh_links_get_is_symbol_before()) {
			echo '&nbsp;<a id="categoryButton'. $cat->cat_id . '">' . enh_links_get_show_symbol() . '</a>';
		}
	?>
	</h2>
	<div id="categoryContent<?php echo $cat->cat_id; ?>" style="display: none;">
		<ul>
			<?php get_links( 
				$cat->cat_id, 
				'<li>',       
				'</li>',      
				'<br/>',  
				false, 'name', 
				enh_links_get_show_link_description(), 
				false, -1, 1, true ); ?>
		</ul>
	</div>  	
<?php
}
?>