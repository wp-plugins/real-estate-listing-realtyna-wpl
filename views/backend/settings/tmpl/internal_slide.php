<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="pwizard-section">
	<?php
	wpl_settings::generate_setting_forms($this->settings);
	
	/** including a custom file **/
	$this->_wpl_import($this->tpl_path.'.custom.settings'.$this->setting_category->id);
    ?>
</div>