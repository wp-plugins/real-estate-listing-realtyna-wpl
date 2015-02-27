<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if((isset($values->specificable) and $values->specificable) or !$dbst_id)
{
?>
<div class="fanc-row">
    <label for="<?php echo $__prefix; ?>specificable"><?php echo __('Specificable', WPL_TEXTDOMAIN); ?></label>
    <select id="<?php echo $__prefix; ?>specificable" name="<?php echo $__prefix; ?>specificable" onchange="wpl_flex_change_specificable(this.value, '<?php echo $__prefix; ?>');">
        <option value="0"><?php echo __('No', WPL_TEXTDOMAIN); ?></option>
        <option value="3" <?php if(isset($values->user_specific) and trim($values->user_specific) != '') echo 'selected="selected"'; ?>><?php echo __('User type specific', WPL_TEXTDOMAIN); ?></option>
    </select>
    <div class="wpl_flex_specificable_cnt" id="<?php echo $__prefix; ?>specificable3" style="<?php if(!isset($values->user_specific) or (isset($values->user_specific) and trim($values->user_specific) == '')) echo 'display: none;'; ?>">
        <?php if(!$dbst_id or (isset($values->specificable) and ($values->specificable == 1))): ?>
        <ul id="<?php echo $__prefix ?>_user_specific" class="wpl_user_specific_ul">
            <li><input id="wpl_flex_user_checkbox_all" type="checkbox" onclick="wpl_user_specific_all(this.checked);" <?php if(!isset($values->user_specific) or (isset($values->user_specific) and trim($values->user_specific) == '')) echo 'checked="checked"'; ?> /><label class="wpl_specific_label" for="wpl_flex_user_checkbox_all">&nbsp;<?php echo __('All', WPL_TEXTDOMAIN); ?></label></li>
            <?php
            $user_specific = isset($values->user_specific) ? explode(',', $values->user_specific) : array();
            foreach($user_types as $user_type)
            {
                ?>
            <li><input id="wpl_flex_user_checkbox<?php echo $user_type['id']; ?>" type="checkbox" value="<?php echo $user_type['id']; ?>" <?php if(!isset($values->user_specific) or (isset($values->user_specific) and trim($values->user_specific) == '') or in_array($user_type['id'], $user_specific)) echo 'checked="checked"'; if(!isset($values->user_specific) or (isset($values->user_specific) and trim($values->user_specific) == '')) echo 'disabled="disabled"'; ?> /><label class="wpl_specific_label" for="wpl_flex_user_checkbox<?php echo $user_type['id']; ?>">&nbsp;<?php echo __($user_type['name'], WPL_TEXTDOMAIN); ?></label></li>
                <?php
            }
            ?>
        </ul>
        <?php endif; ?>
    </div>
</div>
<?php
}
?>