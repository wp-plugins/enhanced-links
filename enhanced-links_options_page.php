<?php
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
?>

<?php	
	if ( enh_links_get_installed_version() != enh_links_get_current_version() ) {
?>
<div class="wrap">
	<p style="color:red;">
		<?php _e('It looks like you have an old version of the plugin activated. Please deactivate the plugin and activate it again to complete the installation of the new version.', ENHANCED_LINKS_I18N_DOMAIN); ?>
	</p>
	<p>
		<?php _e('Installed version:', ENHANCED_LINKS_I18N_DOMAIN); ?> <?php echo enh_links_get_installed_version(); ?><br/>
		<?php _e('Current version:', ENHANCED_LINKS_I18N_DOMAIN); ?> <?php echo enh_links_get_current_version(); ?>
	</p>
</div>
<?php
	}
?>

<?php
	if (isset($_GET['msg']) && $_GET['msg']=='set') {
?>
	<div id="message" class="updated fade">
		<p><?php _e('Options set successfully.', ENHANCED_LINKS_I18N_DOMAIN); ?></p>
	</div>
<?php 
	}
?>

<div class="wrap">
	<h2><?php _e('Enhanced Links', ENHANCED_LINKS_I18N_DOMAIN); ?> <?php echo enh_links_get_installed_version(); ?></h2>

	<div align="center">
		<a href="http://enhanced-links.vincentprat.info" target="_blank"><?php _e("Plugin's home page", ENHANCED_LINKS_I18N_DOMAIN); ?></a>
		<br/><br/>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="vpratfr@yahoo.fr">
			<input type="hidden" name="item_name" value="Email Users - Wordpress Plugin">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="currency_code" value="EUR">
			<input type="hidden" name="tax" value="0">
			<input type="hidden" name="lc" value="<?php _e('EN', ENHANCED_LINKS_I18N_DOMAIN); ?>">
			<input type="hidden" name="bn" value="PP-DonationsBF">
			<input type="image" src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" name="submit" alt="PayPal">
			<img alt="" border="0" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>
	
	<br/>
	<br/>
		
	<form name="EnhancedLinksOptions" action="options-general.php?page=enhanced-links/enhanced-links_set_options.php" method="post">	
		<fieldset class="options">
			<legend><?php _e('Show symbol (displayed when a category can be expanded)', ENHANCED_LINKS_I18N_DOMAIN); ?></legend>
			<div><input type="text" name="show_symbol" 
					value="<?php echo enh_links_get_show_symbol(); ?>"/></div>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Hide symbol (displayed when a category can be contracted)', ENHANCED_LINKS_I18N_DOMAIN); ?></legend>
			<div><input type="text" name="hide_symbol" 
					value="<?php echo enh_links_get_hide_symbol(); ?>"/></div>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Symbol position', ENHANCED_LINKS_I18N_DOMAIN); ?></legend>
			<div><input type="checkbox" name="is_symbol_before" <?php if (enh_links_get_is_symbol_before()) echo 'checked="yes"'; ?> /> 
				<?php _e('Check this if you want the symbol to be placed before the category name.', ENHANCED_LINKS_I18N_DOMAIN); ?></div>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Link description', ENHANCED_LINKS_I18N_DOMAIN); ?></legend>
			<div><label for="show_link_description">
				<input type="checkbox" name="show_link_description" <?php if (enh_links_get_show_link_description()) echo 'checked="yes"'; ?> /> 
				<?php _e('Check this if you want to show the link description.', ENHANCED_LINKS_I18N_DOMAIN); ?>
				</label>
			</div>
		</fieldset>
		<fieldset class="options">
			<legend><?php _e('Show/Hide effect', ENHANCED_LINKS_I18N_DOMAIN); ?></legend>
			<div><select name="effect">
				<option value="none" <?php if (enh_links_get_effect()=='none') echo 'selected="true"'; ?>>
					<?php _e('none', ENHANCED_LINKS_I18N_DOMAIN); ?></option>
				<option value="jQuery" <?php if (enh_links_get_effect()=='jQuery') echo 'selected="true"'; ?>>
					<?php _e('jQuery', ENHANCED_LINKS_I18N_DOMAIN); ?></option>
				<option value="scriptaculous" <?php if (enh_links_get_effect()=='scriptaculous') echo 'selected="true"'; ?>>
					<?php _e('scriptaculous', ENHANCED_LINKS_I18N_DOMAIN); ?></option>
			</select></div>
		</fieldset>
		<p class="submit">
			<input type="submit" name="Submit" value="<?php _e('Set options', ENHANCED_LINKS_I18N_DOMAIN); ?> &raquo;" />
		</p>
	</form>	
</div>
