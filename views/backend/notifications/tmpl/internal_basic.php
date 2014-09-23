<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="pwizard-panel">
    <div class="pwizard-section noti-wp">
        <div class="prow">
            <label for="wpl_subject"><?php echo __('Subject', WPL_TEXTDOMAIN); ?>:</label>
            <input type="text" name="info[subject]" id="wpl_subject" value="<?php echo $this->notification->subject; ?>" />
        </div>
        <div class="prow">
            <label for="wpl_description"><?php echo __('Description', WPL_TEXTDOMAIN); ?>:</label>
            <textarea name="info[description]" id="wpl_description" rows="5"><?php echo $this->notification->description; ?></textarea>
            <input type="hidden" name="info[template_path]" value="<?php echo $this->notification->template;?>" />
        </div>
        <div class="prow">
            <label for="wpl_template"><?php echo __('Template', WPL_TEXTDOMAIN); ?>:</label>
            <?php wp_editor($this->template, 'wpl_template', array('textarea_name'=>'info[template]', 'teeny'=>true)); ?>
        </div>
    </div>
</div>