<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);
$this->_wpl_import($this->tpl_path.'.scripts.css', true, true);
?>
<div class="wrap">
    <div class="wpl_message_container" id="wpl_message_container">
        <?php echo $this->message; ?>
	</div>
</div>
