<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path.'.scripts.js');
?>
<div class="wpl_profile_show_container container" id="wpl_profile_show_container">
	<div class="wpl_profile_show_container_box">
		<?php /** load position1 **/ wpl_activity::load_position('profile_show_position1', array('user_id'=>$this->uid)); ?>
	</div>
    <?php if(is_active_sidebar('wpl-profileshow-top')) dynamic_sidebar('wpl-profileshow-top'); ?>
</div>
<?php /** loading propertylisting **/ echo wpl_global::load('property_listing'); ?>