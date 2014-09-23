<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="noti-advance-wp">
    <?php if(wpl_global::check_addon('pro')): ?>
    <section class="wpl-outer">
        <header>
            <aside class="wpl-left">
                <?php echo __('Available memberships', WPL_TEXTDOMAIN); ?>
            </aside>
            <aside class="wpl-center">
                <div id="loading_membership_recipients"></div>
            </aside>
            <aside class="wpl-right">
                <?php echo __('Included memberships', WPL_TEXTDOMAIN); ?>
            </aside>
        </header>
        <section>
            <aside class="wpl-left">
                <select name="memberships" id="memberships" multiple="multiple">
                    <?php
                    foreach($this->memberships_array as $membership)
                        echo '<option value="'.$membership->id.'">'.$membership->membership_name.'</option>';
                    ?>
                </select>
            </aside>
            <aside class="wpl-center">
                <a id="add_memberships" class="button button-primary wpl-add" name="add_memberships" onclick="add_recipients('memberships','additional_memberships','');" />
                <?php echo __('Add', WPL_TEXTDOMAIN); ?>
                </a>
                <a id="remove_memberships" class="button wpl-remove" name="remove_memberships" onclick="remove_recipients('memberships','additional_memberships','');" >
                    <?php echo __('Remove', WPL_TEXTDOMAIN); ?>
                </a>
            </aside>
            <aside class="wpl-right">
                <select name="additional_memberships" id="additional_memberships" multiple>
                    <?php
                    foreach($this->additional_memberships as $membership_id)
                    {
                        if(trim($membership_id) == '') continue;
                        echo '<option value="'.$this->memberships[$membership_id]->id.'">'.$this->memberships[$membership_id]->membership_name.'</option>';
                    }
                    ?>
                </select>
            </aside>
        </section>
    </section>
    <?php endif; ?>
    <section class="wpl-outer">
        <header>
            <aside class="wpl-left">
                <?php echo __('Available users', WPL_TEXTDOMAIN); ?>
            </aside>
            <aside class="wpl-center">
                <div id="loading_additional_recipients"></div>
            </aside>
            <aside class="wpl-right">
                <?php echo __('Included users', WPL_TEXTDOMAIN); ?>
            </aside>
        </header>
        <section>
            <aside class="wpl-left">
                <select name="users" id="users" multiple="multiple">
                    <?php
                    foreach($this->users_array as $user)
                        echo '<option value="'.$user->id.'">'.$user->user_login.'</option>';
                    ?>
                </select>
            </aside>
            <aside class="wpl-center">
                <a id="add_recipient" class="button button-primary wpl-add" name="add_memberships" onclick="add_recipients('users','additional_users','');" />
                    <?php echo __('Add', WPL_TEXTDOMAIN); ?>
                </a>
                <a id="remove_recipient" class="button wpl-remove" name="remove_memberships" onclick="remove_recipients('users','additional_users','');" >
                    <?php echo __('Remove', WPL_TEXTDOMAIN); ?>
                </a>
            </aside>
            <aside class="wpl-right">
                <select name="additional_users" id="additional_users" multiple>
                    <?php
                    foreach($this->additional_users as $user_id)
                    {
                        if(trim($user_id) == '') continue;
                        echo '<option value="'.$this->users[$user_id]->id.'">'.$this->users[$user_id]->user_login.'</option>';
                    }
                    ?>
                </select>
            </aside>
        </section>
    </section>
    <section class="wpl-outer">
        <header>
            <aside class="wpl-left">
                <?php echo __('Email address', WPL_TEXTDOMAIN); ?>
            </aside>
            <aside class="wpl-center">
                <div id="loading_email_recipients"></div>
            </aside>
            <aside class="wpl-right">
                <?php echo __('Included emails', WPL_TEXTDOMAIN); ?>
            </aside>
        </header>
        <section>
            <aside class="wpl-left">
                <input type="text" name="email_address" id="email_address" />
            </aside>
            <aside class="wpl-center">
                <a id="add_email" class="button button-primary wpl-add" name="add_memberships" onclick="add_recipients('emails','additional_emails','email_recipients');" />
                    <?php echo __('Add', WPL_TEXTDOMAIN); ?>
                </a>
                <a id="remove_email" class="button wpl-remove" name="remove_memberships" onclick="remove_recipients('emails','additional_emails','email_recipients');" >
                    <?php echo __('Remove', WPL_TEXTDOMAIN); ?>
                </a>
            </aside>
            <aside class="wpl-right">
                <select name="additional_emails" id="additional_emails" multiple>
                    <?php
                    foreach($this->additional_emails as $email)
                    {
                        if(trim($email) == '') continue;
                        echo '<option value="'.$email.'">'.$email.'</option>';
                    }
                    ?>
                </select>
            </aside>
        </section>
    </section>
</div>