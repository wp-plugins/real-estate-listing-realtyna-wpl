<?php
/** no direct access * */
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path . '.scripts.css');
_wpl_import($this->tpl_path . '.scripts.js');
?>
<div class="wrap wpl-wp wpl-dashboard-wp">

    <header>
        <div class="wpl-icon-header wpl-icon-dashboard"></div>
        <h2>
            <?php echo __('WPL', WPL_TEXTDOMAIN); ?>&nbsp;<?php echo(wpl_global::check_addon('pro') ? 'PRO' : 'Basic'); ?>
            <span class="wpl-dashboard-ver">v<?php echo wpl_global::wpl_version(); ?></span>
        </h2>
    </header>

    <div id="dashboard-links-wp">
        <ul>
            <?php foreach($this->submenus as $submenu): if(!wpl_users::has_menu_access($submenu->menu_slug, wpl_users::get_cur_user_id())) continue; ?>
                <li class="link-<?php echo $submenu->id; ?>">
                    <a href="<?php echo wpl_global::get_wp_admin_url(); ?>admin.php?page=<?php echo $submenu->menu_slug; ?>">
                        <span class="box"><i></i></span>
                        <span class="title">
                            <?php echo __($submenu->menu_title, WPL_TEXTDOMAIN); ?>
                        </span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="sidebar-wp banner-side">
        <div class="side-15">
            <!-- Banner Position -->
        </div>
    </div>
    
    <?php if(wpl_users::is_super_admin()): ?>
    <div class="sidebar-wp sidebar-float">
        <div class="side-ni-addons">
            <div class="sidebar-wp sidebar-float">
                
                <div class="rt-same-height sidebar-float">
                    <!-- Generating optional addons -->
                    <?php $this->not_installed_addons(); ?>
                    
                    <!-- WPL change-log -->
                    <div class="side-6 side-changes js-full-height" data-minuse-size="56" id="wpl_dashboard_changelog">
                        <div class="panel-wp">
                            <h3><?php echo __('Changelog', WPL_TEXTDOMAIN); ?></h3>

                            <div class="panel-body">
                                <?php _wpl_import('assets.changelogs.wpl'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rt-same-height sidebar-float">
                    <!-- Generating addons -->
                    <?php $this->generate_addons(); ?>
                    
                    <!-- Generating support section -->
                    <?php $this->support(); ?>
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
    <?php endif; ?>

    <footer>
        <div class="logo"></div>
    </footer>
</div>