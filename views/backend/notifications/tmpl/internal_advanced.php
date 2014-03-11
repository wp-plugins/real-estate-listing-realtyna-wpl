<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div>
    <select id="wpl_from_user_include" size="10" multiple="multiple">
        <?php
        foreach($this->emails as $email)
        {
            echo '<option value="' . $email . '">' . $email . '</option>';
        }
        ?>
    </select>
    <input type="button" value="<?php echo __('Add', WPL_TEXTDOMAIN); ?>" id="wpl_btn_add_user_include" onclick="wpl_select_move('wpl_from_user_include', 'wpl_to_user_include');"/>
    <input type="button" value="<?php echo __('Remove', WPL_TEXTDOMAIN); ?>" id="wpl_btn_remove_user_include" onclick="wpl_select_move('wpl_to_user_include', 'wpl_from_user_include');"/>
    <select id="wpl_to_user_include" size="10" multiple="multiple">
        <?php
        if(trim($this->notification->include_user) != '')
        {
            foreach(explode(',', $this->notification->include_user) as $email)
            {
                echo '<option value="' . $email . '">' . $email . '</option>';
            }
        }
        ?>
    </select>
</div>
<div>
    <input id="wpl_email_include_textbox" placeholder="<?php echo __('Email', WPL_TEXTDOMAIN); ?>" type="text"/>
    <input type="button" id="wpl_btn_add_user_include" value="<?php echo __('Add', WPL_TEXTDOMAIN); ?>" onclick="wpl_add_email();"/>
    <input type="button" id="wpl_btn_remove_user_include" value="<?php echo __('Remove', WPL_TEXTDOMAIN); ?>" onclick="wpl_remove_email();"/>
    <select id="wpl_email_include" size="10" multiple="multiple">
        <?php
        if(trim($this->notification->include_email) != '')
        {
            foreach(explode(',', $this->notification->include_email) as $email)
            {
                echo '<option value="' . $email . '">' . $email . '</option>';
            }
        }
        ?>
    </select>
</div>