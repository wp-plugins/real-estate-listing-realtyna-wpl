<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div id="wpl_membership_top_tabs_container">
    <ul class="wpl-tabs">
        <?php foreach($this->kinds as $kind): ?>
        <li class="<?php echo ($this->kind == $kind['id'] ? 'wpl-selected-tab' : ''); ?>">
            <a href="<?php echo wpl_global::add_qs_var('kind', $kind['id']); ?>"><?php echo __($kind['name'], WPL_TEXTDOMAIN); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</div>