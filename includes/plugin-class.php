<?php
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


//#################################################################
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { 
	die('You are not allowed to call this page directly.'); 
}
//#################################################################

//#################################################################
// Some constants 
//#################################################################

//#################################################################
// The plugin class
if (!class_exists("EnhancedLinksPlugin")) {

class EnhancedLinksPlugin {
	var $current_version = '4.2.3';
	var $options;
	var $ul_index = 0;
	
	/**
	* Constructor
	*/
	function EnhancedLinksPlugin() {
		$this->load_options();
	}
	
	/**
	* Function to be called when the plugin is activated
	*/
	function activate() {
		global $enh_links_widget;
		
		$active_version = $this->options['active_version'];
		if (!isset($active_version) || $active_version=='') {
			$active_version = get_option('enh_links_version');
		}
		
		if ($active_version==$this->current_version) {
			// do nothing
		} else {
			if ($active_version=='') {			
				add_option(ENHANCED_LINKS_PLUGIN_OPTIONS, 
					$this->options, 
					'Enhanced Links plugin options');
				add_option(ENHANCED_LINKS_WIDGET_OPTIONS, 
					$enh_links_widget->options, 
					'Enhanced Links widget options');
			} else if ($active_version<='3.1') {
				delete_option('enh_links_show_symbol');
				delete_option('enh_links_hide_symbol');
				delete_option('enh_links_is_symbol_before');
				delete_option('enh_links_show_link_description');
				delete_option('enh_links_effect');
				delete_option('enh_links_version');
				
				add_option(ENHANCED_LINKS_PLUGIN_OPTIONS, 
					$this->options, 
					'Enhanced Links plugin options');
				add_option(ENHANCED_LINKS_WIDGET_OPTIONS, 
					$enh_links_widget->options, 
					'Enhanced Links widget options');
			} else if ($active_version<='4.0.0') {
				delete_option('enh_links_version');

				add_option(ENHANCED_LINKS_PLUGIN_OPTIONS, 
					$this->options, 
					'Enhanced Links plugin options');
			} else if ($active_version<'4.2.0') {
				add_option(ENHANCED_LINKS_WIDGET_OPTIONS,
					get_option(ENHANCED_LINKS_options),
					'Enhanced Categories widget options');
				delete_option(ENHANCED_LINKS_options);
				
				$enh_links_widget->load_options();
			}
			
		}
		
		// Update version number & save new options
		$this->options['active_version'] = $this->current_version;
		$this->save_options();
	}
	
	/**
	* Enqueue the necessary javascript
	*/
	function add_javascript() {	
		wp_enqueue_script('jquery');
	}
	
	/**
	* Function to render the plugin's HEAD elements in a blog page
	*/
	function render_page_head() {
?>
<script src="<?php echo WP_PLUGIN_URL; ?>/enhanced-links/js/enhanced-links.js" type="text/javascript" ></script>
<?php
	}
	
	/**
	* Function that echoes the links in the form of a list
	*/
	function list_links($args = '') {	
		$this->ul_index++;
		
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
			'expand_image'		=> '',
			'contract_image'	=> '',
			'leaf_image'		=> '',
			'contract_children'	=> 1,
			'is_button_after' 	=> 0,

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
<!-- Enhanced Links -->
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('ul.enhanced-links-<?php echo $this->ul_index; ?>').enhancedLinks({
			// Override here the default settings for the plugin
			expandText		: '<?php echo $r['expand_text']; ?>',
			contractText	: '<?php echo $r['contract_text']; ?>',
			leafText		: '<?php echo $r['leaf_text']; ?>',
			expandImage		: '<?php echo $r['expand_image']; ?>',
			contractImage	: '<?php echo $r['contract_image']; ?>',
			leafImage		: '<?php echo $r['leaf_image']; ?>',
			isButtonAfter	: <?php echo $r['is_button_after']; ?>,
			buttonColor		: '<?php echo $r['button_color']; ?>',
			contractChildren: <?php echo $r['contract_children']; ?>
		});
	});
</script>
<?php	
		}

		echo '<ul class="enhanced-links-' . $this->ul_index . '">';
		wp_list_bookmarks($r);
		echo '</ul>';
		
?>
<!-- Enhanced Links -->
<?php	
	}
	
	/**
	* Load the options from database (set default values in case options are not set)
	*/
	function load_options() {
		$this->options = get_option(ENHANCED_LINKS_PLUGIN_OPTIONS);
		
		if ( !is_array($this->options) ) {
			$this->options = array(
				'active_version'		=> ''
			);
		}
	}
	
	/**
	* Save options to database
	*/
	function save_options() {
		update_option(ENHANCED_LINKS_PLUGIN_OPTIONS, $this->options);
	}
	
} // class EnhancedLinksPlugin
} // if (!class_exists("EnhancedLinksPlugin"))
	
?>