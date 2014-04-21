<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="side-14 side-announce">
    <div class="panel-wp">
        <h3><?php echo __('KB Articles', WPL_TEXTDOMAIN); ?></h3>
        <div class="panel-body">
            <iframe src="//support.realtyna.com/api/kb.php?wplv=<?php echo wpl_global::wpl_version(); ?>&wpv=<?php echo wpl_global::wp_version(); ?>&phpv=<?php echo phpversion(); ?>" width="100%" height="100%"></iframe>
        </div>
    </div>
</div>