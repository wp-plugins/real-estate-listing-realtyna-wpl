<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** Define Tabs **/
$tabs = array();
$tabs['tabs'] = array();

$content = '<h3>'.__('WPL Dashboard', WPL_TEXTDOMAIN).'</h3><p>'.__('Welcome to WPL dashboard, Here you can see some important information about WPL and its add-ons.', WPL_TEXTDOMAIN).'</p>';
$tabs['tabs'][] = array('id'=>'wpl_contextual_help_tab1', 'content'=>$content, 'title'=>'First Tab');

return $tabs;