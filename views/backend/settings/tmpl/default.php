<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path.'.scripts.js');
_wpl_import($this->tpl_path.'.scripts.css');
?>
<div class="wrap wpl-wp settings-wp">
    <header>
        <div id="icon-settings" class="icon48">
        </div>
        <h2><?php echo __('WPL Settings', WPL_TEXTDOMAIN); ?></h2>
    </header>
    <div class="wpl_settings_list"><div class="wpl_show_message"></div></div>
    <div class="sidebar-wp">
        <div class="side-2 side-tabs-wp">
            <ul>
                <?php foreach ($this->setting_categories as $category): ?>
                    <li>
                        <a href="#<?php echo __($category->name, WPL_TEXTDOMAIN); ?>" class="wpl_slide_label wpl_slide_label_id<?php echo $category->id; ?>" 
                           id="wpl_slide_label_id<?php echo $category->id; ?>" 
                           onclick="rta.internal.slides.open('<?php echo $category->id; ?>', '.side-tabs-wp', '.wpl_slide_container', 'currentTab');"><?php echo __($category->name, WPL_TEXTDOMAIN); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="side-12 side-content-wp">
            <?php foreach ($this->setting_categories as $category): ?>
                <div class="pwizard-panel settings-wp wpl_slide_container wpl_slide_container<?php echo $category->id; ?>" id="wpl_slide_container_id<?php echo $category->id; ?>">
                    <?php $this->generate_slide($category); ?>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="clearit"></div>

        <div class="wpl-bottom-nav">
        	
            <div class="side-9 side-maintenance">
                <div class="panel-wp">
                    <h3><?php echo __('Maintenance', WPL_TEXTDOMAIN); ?></h3>
                    <div class="panel-body">
	                    <?php $this->generate_internal('maintenance'); ?>
                    </div>
                </div>
            </div>
            <div class="side-5 side-requirements">
                <div class="panel-wp">
                    <h3><?php echo __('Server requirements', WPL_TEXTDOMAIN); ?></h3>
                    <div class="panel-body">
                    	<?php $this->generate_internal('requirements'); ?>
                    </div>
                </div>
            </div>
		</div>
    </div>
    <footer>
        <div class="logo"></div>
    </footer>
</div>
<script type="text/javascript">
    (function($){$(function(){isWPL();})})(jQuery);
</script>