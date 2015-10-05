<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>category"><?php echo __('Data category', WPL_TEXTDOMAIN); ?></label>
    <select id="<?php echo $__prefix; ?>category" name="<?php echo $__prefix; ?>data_category">
        <?php foreach ($dbcats as $dbcat): ?>
        <option value="<?php echo $dbcat->id; ?>" <?php if (isset($values->category) and $dbcat->id == $values->category) echo 'selected="selected"'; ?>><?php echo $dbcat->name; ?></option>
        <?php endforeach; ?>
    </select>
    <!-- hidden fields -->
    <input type="hidden" name="<?php echo $__prefix; ?>type" id="<?php echo $__prefix; ?>type" value="<?php echo $type; ?>" />
    <input type="hidden" name="<?php echo $__prefix; ?>kind" id="<?php echo $__prefix; ?>kind" value="<?php echo $kind; ?>" />
    <input type="hidden" name="<?php echo $__prefix; ?>table_name" id="<?php echo $__prefix; ?>table_name" value="<?php echo wpl_flex::get_kind_table($kind); ?>" />
</div>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>name"><?php echo __('Name', WPL_TEXTDOMAIN); ?></label>
    <input type="text" name="<?php echo $__prefix; ?>name" id="<?php echo $__prefix; ?>name" value="<?php echo (isset($values->name) ? $values->name : ''); ?>" />
</div>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>text_search"><?php echo __('Text Search', WPL_TEXTDOMAIN); ?></label>
    <select name="<?php echo $__prefix; ?>text_search" id="<?php echo $__prefix; ?>text_search">
        <option value="1" <?php if (isset($values->text_search) and $values->text_search == '1') echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
        <option value="0" <?php if (isset($values->text_search) and $values->text_search == '0') echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
    </select>
</div>
<?php if(wpl_global::check_multilingual_status() and (in_array($type, array('text', 'textarea', 'meta_key', 'meta_desc')) or (isset($values->type) and in_array($values->type, array('text', 'textarea', 'meta_key', 'meta_desc'))))): ?>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>multilingual"><?php echo __('Multilingual', WPL_TEXTDOMAIN); ?></label>
    <select name="<?php echo $__prefix; ?>multilingual" id="<?php echo $__prefix; ?>multilingual">
        <option value="0" <?php if (isset($values->multilingual) and $values->multilingual == '0') echo 'selected="selected"'; ?>><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
        <option value="1" <?php if (isset($values->multilingual) and $values->multilingual == '1') echo 'selected="selected"'; ?>><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
    </select>
</div>
<?php endif; ?>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>pshow"><?php echo __('Detail Page', WPL_TEXTDOMAIN); ?></label>
    <select name="<?php echo $__prefix; ?>pshow" id="<?php echo $__prefix; ?>pshow">
        <option value="1" <?php if (isset($values->pshow) and $values->pshow == '1') echo 'selected="selected"'; ?>><?php echo __('Show', WPL_TEXTDOMAIN); ?></option>
        <option value="0" <?php if (isset($values->pshow) and $values->pshow == '0') echo 'selected="selected"'; ?>><?php echo __('Hide', WPL_TEXTDOMAIN); ?></option>
    </select>
</div>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>searchmod"><?php echo __('Search Widget', WPL_TEXTDOMAIN); ?></label>
    <select name="<?php echo $__prefix; ?>searchmod" id="<?php echo $__prefix; ?>searchmod">
        <option value="1" <?php if (isset($values->searchmod) and $values->searchmod == '1') echo 'selected="selected"'; ?>><?php echo __('Show', WPL_TEXTDOMAIN); ?></option>
        <option value="0" <?php if (isset($values->searchmod) and $values->searchmod == '0') echo 'selected="selected"'; ?>><?php echo __('Hide', WPL_TEXTDOMAIN); ?></option>
    </select>
</div>
<?php if(wpl_global::check_addon('pro')): ?>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>pdf"><?php echo __('PDF Flyer', WPL_TEXTDOMAIN); ?></label>
    <select name="<?php echo $__prefix; ?>pdf" id="<?php echo $__prefix; ?>pdf">
        <option value="1" <?php if (isset($values->pdf) and $values->pdf == '1') echo 'selected="selected"'; ?>><?php echo __('Show', WPL_TEXTDOMAIN); ?></option>
        <option value="0" <?php if (isset($values->pdf) and $values->pdf == '0') echo 'selected="selected"'; ?>><?php echo __('Hide', WPL_TEXTDOMAIN); ?></option>
    </select>
</div>
<?php endif; ?>

<?php if(wpl_global::is_multisite() and wpl_users::is_super_admin()): ?>
<div class="fanc-row" id="multisite_modify_status_container">
    <label for="multisite_modify_status"><?php echo __('Network Apply', WPL_TEXTDOMAIN); ?></label>
    <select name="multisite_modify_status" id="multisite_modify_status">
        <option value="0"><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
        <option value="1"><?php echo __('Yes', WPL_TEXTDOMAIN); ?></option>
    </select>
</div>
<?php endif; ?>