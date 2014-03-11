<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div>
    <label for="wpl_subject"><?php echo __('Subject', WPL_TEXTDOMAIN); ?>:</label>
    <input type="text" name="info[subject]" id="wpl_subject" value="<?php echo $this->notification->subject; ?>" />
</div>
<div>
    <label for="wpl_description"><?php echo __('Description', WPL_TEXTDOMAIN); ?>:</label>
    <input type="text" name="info[description]" id="wpl_description" value="<?php echo $this->notification->description; ?>" />
</div>
<div>
    <label for="wpl_template_path"><?php echo __('Template Path', WPL_TEXTDOMAIN); ?>:</label>
    <input type="text" name="info[template_path]" id="wpl_template_path" value="<?php echo $this->notification->template; ?>" />
</div>
<div>
    <?php wp_editor($this->template, 'wpl_template', array('textarea_name' => 'info[template]')); ?>
</div>