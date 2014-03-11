<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>

<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>category"><?php echo __('Data category'); ?></label>
    <select id="<?php echo $__prefix; ?>category" name="<?php echo $__prefix; ?>data_category">
        <?php foreach ($dbcats as $dbcat): ?>
            <option value="<?php echo $dbcat->id; ?>" <?php if ($dbcat->id == $values->category) echo 'selected="selected"'; ?>><?php echo $dbcat->name; ?></option>
        <?php endforeach; ?>
    </select>
    <!-- hidden fields -->
    <input type="hidden" name="<?php echo $__prefix; ?>type" id="<?php echo $__prefix; ?>type" value="<?php echo $type; ?>" />
    <input type="hidden" name="<?php echo $__prefix; ?>kind" id="<?php echo $__prefix; ?>kind" value="<?php echo $kind; ?>" />
    <input type="hidden" name="<?php echo $__prefix; ?>table_name" id="<?php echo $__prefix; ?>table_name" value="<?php echo wpl_flex::get_kind_table($kind); ?>" />
</div>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>name"><?php echo __('Name'); ?></label>
    <input type="text" name="<?php echo $__prefix; ?>name" id="<?php echo $__prefix; ?>name" value="<?php echo $values->name; ?>" />
</div>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>css"><?php echo __('CSS Class'); ?></label>
    <input type="text" name="<?php echo $__prefix; ?>css" id="<?php echo $__prefix; ?>css" value="<?php echo $values->css; ?>" />
</div>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>style"><?php echo __('CSS Style'); ?></label>
    <input type="text" name="<?php echo $__prefix; ?>style" id="<?php echo $__prefix; ?>style" value="<?php echo $values->style; ?>" />
</div>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>text_search"><?php echo __('Text Search'); ?></label>
    <select name="<?php echo $__prefix; ?>text_search" id="<?php echo $__prefix; ?>text_search">
        <option value="1" <?php if ($values->text_search == '1') echo 'selected="selected"'; ?>><?php echo __('Yes'); ?></option>
        <option value="0" <?php if ($values->text_search == '0') echo 'selected="selected"'; ?>><?php echo __('No'); ?></option>
    </select>
</div>