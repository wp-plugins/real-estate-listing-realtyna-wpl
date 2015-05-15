<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<?php if(!wpl_global::check_addon('membership')): ?>
<div class="fanc-row">
    <?php echo __('Membership Addon must be installed for this!', WPL_TEXTDOMAIN); ?>
</div>
<?php else: ?>
<div class="fanc-row">
    <div class="fanc-row">
        <label for="<?php echo $__prefix; ?>accesses"><?php echo __('Viewable by', WPL_TEXTDOMAIN); ?></label>
        <select id="<?php echo $__prefix; ?>accesses" name="<?php echo $__prefix; ?>accesses" onchange="wpl_flex_change_accesses(this.value, '<?php echo $__prefix; ?>');">
            <option value="2"><?php echo __('All Users', WPL_TEXTDOMAIN); ?></option>
            <option value="1" <?php if(isset($values->accesses) and trim($values->accesses) != '') echo 'selected="selected"'; ?>><?php echo __('Selected Users', WPL_TEXTDOMAIN); ?></option>
        </select>
    </div>
    <div class="wpl_flex_accesses_cnt" id="<?php echo $__prefix; ?>accesses_cnt" style="<?php if(!isset($values->accesses) or (isset($values->accesses) and trim($values->accesses) == '')) echo 'display: none;'; ?>">
        <div class="fanc-row" id="<?php echo $__prefix; ?>accesses_message_row">
            <label for="<?php echo $__prefix; ?>accesses_message"><?php echo __('Message', WPL_TEXTDOMAIN); ?></label>
            <input type="text" value="<?php echo (isset($values->accesses_message) ? $values->accesses_message : ''); ?>" id="<?php echo $__prefix; ?>accesses_message" name="<?php echo $__prefix; ?>accesses_message" placeholder="<?php echo __('Leave it empty for hiding!', WPL_TEXTDOMAIN); ?>" />
        </div>
        <ul id="<?php echo $__prefix ?>_accesses_ul" class="wpl_accesses_ul">
            <?php
            $accesses = isset($values->accesses) ? explode(',', $values->accesses) : array();
            foreach($memberships as $membership)
            {
                ?>
                <li><input id="wpl_flex_membership_checkbox<?php echo $membership->id; ?>" type="checkbox" value="<?php echo $membership->id; ?>" <?php if(!isset($values->accesses) or (isset($values->accesses) and trim($values->accesses) == '') or in_array($membership->id, $accesses)) echo 'checked="checked"'; ?> /><label class="wpl_specific_label" for="wpl_flex_membership_checkbox<?php echo $membership->id; ?>">&nbsp;<?php echo __($membership->membership_name, WPL_TEXTDOMAIN); ?></label></li>
                <?php
            }
            ?>
        </ul>
        
    </div>
</div>
<?php endif; ?>