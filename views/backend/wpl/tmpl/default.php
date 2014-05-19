<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path . '.scripts.css');
_wpl_import($this->tpl_path . '.scripts.js');
_wpl_import('libraries.activities');
?>
<div class="wrap wpl-wp dashboard-wp">

    <header>
        <div id="icon-dashboard" class="icon48"></div>
        <h2><?php echo __('WPL', WPL_TEXTDOMAIN); ?>&nbsp;<?php echo(wpl_global::check_addon('pro') ? 'PRO' : 'Basic'); ?><span class="wpl_version">v<?php echo wpl_global::wpl_version(); ?></span></h2>
    </header>

    <div id="dashboard-links-wp">
        <ul>
            <?php foreach ($this->submenus as $submenu): ?>
                <li class="link-<?php echo $submenu->id; ?>">
                    <a href="<?php echo wpl_global::get_wp_admin_url(); ?>admin.php?page=<?php echo $submenu->menu_slug; ?>">
                        <span class="box"><i></i></span>
                        <span class="title">
                            <?php echo $submenu->page_title; ?>    
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="sidebar-wp sidebar-float">
        <div class="side-14 side-ni-addons">
            <div class="sidebar-wp sidebar-float">

                <div class="rt-same-height sidebar-float">
                    <!-- Generating not installed extensions -->
                    <?php $this->not_installed_addons(); ?>

                    <!-- Generating support section -->
                    <?php $this->support(); ?>
                </div>

                <div class="rt-same-height sidebar-float">
                    <!-- Generating addons -->
                    <?php $this->generate_addons(); ?>

                    <div class="side-4 side-changes js-full-height" data-minuse-size="56">
                        <div class="panel-wp">
                            <h3><?php echo __('Changelog', WPL_TEXTDOMAIN); ?></h3>

                            <div class="panel-body">
                                <?php _wpl_import('assets.changelogs.wpl'); ?>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Generating statistic section -->
                <?php $this->announce(); ?>

                <div class="rt-same-height sidebar-float">
                    <!-- Generating statistic section -->
                    <?php $this->statistic(); ?>
                </div>

            </div>
        </div>
    </div>

    <div class="sidebar-wp banner-side">
        <div class="side-15">
            <!--Banner Position-->
        </div>
    </div>

    <footer>
        <div class="logo"></div>
    </footer>
</div>
<script type="text/javascript">
    (function($){$(function(){isWPL();})})(jQuery);
</script>