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
// The Widget class
if (!class_exists("EnhancedLinksWidget")) {

class EnhancedLinksWidget {
	var $options;
	
	/**
	* Constructor
	*/
	function EnhancedLinksWidget() {
		$this->load_options();
	}
		
	/**
	* Function to register the Widget functions
	*/
	function register_widget() {
		register_sidebar_widget(
			__('Enhanced Links', ENHANCED_LINKS_I18N_DOMAIN), 
			array(&$this, 'render_widget'));
		
		if (is_admin()) {
			register_widget_control(
				__('Enhanced Links', ENHANCED_LINKS_I18N_DOMAIN), 
				array(&$this, 'render_control_panel'), 
				400, 250);
		}
	}
	
	/**
	* Function to render the widget control panel
	*/
	function render_control_panel() {
		// See if we're handling a form submission
		//--
		if ( $_POST['enh_links-submit'] ) {
			$this->options['widget_title'] 		= strip_tags(stripslashes($_POST['enh_links-widget_title']));
			$this->options['hide_invisible'] 	= $_POST['enh_links-hide_invisible'] ? 1 : 0;
			$this->options['show_description']	= $_POST['enh_links-show_description'] ? 1 : 0;
			$this->options['show_images']		= $_POST['enh_links-show_images'] 	? 1 : 0;
			$this->options['show_rating']		= $_POST['enh_links-show_rating'] 	? 1 : 0;
			$this->options['is_button_after']	= $_POST['enh_links-is_button_after'] ? 1 : 0;
			$this->options['contract_children']	= $_POST['enh_links-contract_children'] ? 1 : 0;
			$this->options['button_color']		= strip_tags(stripslashes($_POST['enh_links-button_color']));
			$this->options['expand_text']		= strip_tags(stripslashes($_POST['enh_links-expand_text']));
			$this->options['contract_text']		= strip_tags(stripslashes($_POST['enh_links-contract_text']));
			$this->options['leaf_text']			= strip_tags(stripslashes($_POST['enh_links-leaf_text']));
			$this->options['expand_image']		= $_POST['enh_links-expand_image'];
			$this->options['contract_image']	= $_POST['enh_links-contract_image'];
			$this->options['leaf_image']		= $_POST['enh_links-leaf_image'];
			
			$this->save_options();
		}

		// The widget control
		//--
		$widget_title = htmlspecialchars($this->options['widget_title'], ENT_QUOTES);
	?>
	
<input type="hidden" id="enh_links-submit" name="enh_links-submit" value="1" />
<p>
	<label><?php _e('Title:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/>
	<input style="width: 250px;" id="enh_links-widget_title" name="enh_links-widget_title" type="text" value="<?php echo $widget_title; ?>" /></label>
</p>

<br/>
<p><strong>&raquo; <?php _e('Links listing options', ENHANCED_LINKS_I18N_DOMAIN); ?></strong></p>
<p>
	<label><input class="checkbox" <?php $this->render_checked($this->options['hide_invisible']); ?> id="enh_links-hide_invisible" name="enh_links-hide_invisible" type="checkbox"> 
	<?php _e('Hide links marked as "invisible"', ENHANCED_LINKS_I18N_DOMAIN); ?></label>
</p>
<p>
	<label><input class="checkbox" <?php $this->render_checked($this->options['show_description']); ?> id="enh_links-show_description" name="enh_links-show_description" type="checkbox"> 
	<?php _e('Show link description', ENHANCED_LINKS_I18N_DOMAIN); ?></label>
</p>
<p>
	<label><input class="checkbox" <?php $this->render_checked($this->options['show_images']); ?> id="enh_links-show_images" name="enh_links-show_images" type="checkbox"> 
	<?php _e('Show link image', ENHANCED_LINKS_I18N_DOMAIN); ?></label>
</p>
<p>
	<label><input class="checkbox" <?php $this->render_checked($this->options['show_rating']); ?> id="enh_links-show_rating" name="enh_links-show_rating" type="checkbox"> 
	<?php _e('Show link rating', ENHANCED_LINKS_I18N_DOMAIN); ?></label>
</p>
		
	<?php 
		if (ENHANCED_LINKS_USE_JAVASCRIPT) { 
	?>
	
<br/>
<p><strong>&raquo; <?php _e('Javascript options', ENHANCED_LINKS_I18N_DOMAIN); ?></strong></p>
<p>
	<label><input class="checkbox" <?php $this->render_checked($this->options['contract_children']); ?> id="enh_links-contract_children" name="enh_links-contract_children" type="checkbox"> 
	<?php _e('Contract link categories', ENHANCED_LINKS_I18N_DOMAIN); ?></label>
</p>
<p>
	<label><input class="checkbox" <?php $this->render_checked($this->options['is_button_after']); ?> id="enh_links-is_button_after" name="enh_links-is_button_after" type="checkbox"> 
	<?php _e('Place expand/contract button after the category', ENHANCED_LINKS_I18N_DOMAIN); ?></label>
</p>
<p>
	<label><?php _e('Button color:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/>
	<input style="width: 250px;" id="enh_links-button_color" name="enh_links-button_color" type="text" value="<?php echo $this->options['button_color']; ?>" /></label>
</p>
<p>
	<label><?php _e('Expand button text:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/> 
	<input style="width: 250px;" id="enh_links-expand_text" name="enh_links-expand_text" type="text" value="<?php echo $this->options['expand_text']; ?>" /></label>
</p>
<p>
	<label><?php _e('Contract button text:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/>
	<input style="width: 250px;" id="enh_links-contract_text" name="enh_links-contract_text" type="text" value="<?php echo $this->options['contract_text']; ?>" /></label>
</p>
<p>
	<label><?php _e('Text when category has no child:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/>
	<input style="width: 250px;" id="enh_links-leaf_text" name="enh_links-leaf_text" type="text" value="<?php echo $this->options['leaf_text']; ?>" /></label>
</p>
<p>
	<label><?php _e('Expand button image:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/> 
	<input style="width: 250px;" id="enh_links-expand_image" name="enh_links-expand_image" type="text" value="<?php echo $this->options['expand_image']; ?>" /></label>
</p>
<p>
	<label><?php _e('Contract button image:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/>
	<input style="width: 250px;" id="enh_links-contract_image" name="enh_links-contract_image" type="text" value="<?php echo $this->options['contract_image']; ?>" /></label>
</p>
<p>
	<label><?php _e('Image when category has no child:', ENHANCED_LINKS_I18N_DOMAIN); ?><br/>
	<input style="width: 250px;" id="enh_links-leaf_image" name="enh_links-leaf_image" type="text" value="<?php echo $this->options['leaf_image']; ?>" /></label>
</p>
	<?php 
		} // if (ENHANCED_LINKS_USE_JAVASCRIPT)
	}
	
	/**
	* Function to render the widget
	*/
	function render_widget($args) {
		global $enh_links_plugin;
		
		extract($args, EXTR_SKIP);
		$title = empty($this->options['widget_title']) ? __('Links', ENHANCED_LINKS_I18N_DOMAIN) : $this->options['widget_title'];

		echo '<!-- Enhanced Links ' . $enh_links_plugin->options['active_version'] . ' -->';	
		
		echo $before_widget; 
			echo $before_title . $title . $after_title;
			$enh_links_plugin->list_links($this->options); 
		echo $after_widget;
		
		echo '<!-- Enhanced Links ' . $enh_links_plugin->current_version . ' -->';
	}
	
	/**
	* Load the options from database (set default values in case options are not set)
	*/
	function load_options() {
		$this->options = get_option(ENHANCED_LINKS_WIDGET_OPTIONS);
		
		if ( !is_array($this->options) ) {
			$this->options = array(
				'widget_title'		=> '', 
				'hide_invisible' 	=> 1,
				'show_description' 	=> 0,
				'show_images' 		=> 1,	
				'show_rating'		=> 0,
				'contract_children' => 1,
				'button_color' 		=> '#AA0000',
				'expand_text' 		=> '&raquo;',
				'contract_text' 	=> '&laquo;',
				'leaf_text' 		=> '-',
				'expand_image'		=> WP_PLUGIN_URL . '/enhanced-links/images/expand.gif',
				'contract_image'	=> WP_PLUGIN_URL . '/enhanced-links/images/contract.gif',
				'leaf_image'		=> WP_PLUGIN_URL . '/enhanced-links/images/leaf.gif',
				'is_button_after' 	=> 0
			);
		}
	}
	
	/**
	* Save options to database
	*/
	function save_options() {
		update_option(ENHANCED_LINKS_WIDGET_OPTIONS, $this->options);
	}
	
	/**
	* Helper function to output the checked attribute of a checkbox
	*/
	function render_checked($var) {
		if ($var==1 || $var==true) {
			echo 'checked="checked"';
		}
	}
} // class EnhancedLinksWidget

} // if (!class_exists("EnhancedLinksWidget"))
//#################################################################
?>