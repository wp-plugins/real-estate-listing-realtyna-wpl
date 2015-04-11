<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<div class="side-12 side-announce" id="wpl_dashboard_side_announce">
    <div class="panel-wp">
        <h3><?php echo __('KB Articles', WPL_TEXTDOMAIN); ?></h3>
        <div class="panel-body">
            <iframe src="//support.realtyna.com/api/kb2.php?wplv=<?php echo wpl_global::wpl_version(); ?>&wpv=<?php echo wpl_global::wp_version(); ?>&phpv=<?php echo phpversion(); ?>" width="100%" height="312px"></iframe>
        </div>
    </div>
</div>