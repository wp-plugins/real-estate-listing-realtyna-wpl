<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();

$content = '<h3>'.__('WPL Dashboard', WPL_TEXTDOMAIN).'</h3><p>'.__('Welcome to WPL dashboard, Here you can see some important information about WPL and its add-ons.', WPL_TEXTDOMAIN).'</p>';
$tips[] = array('id'=>1, 'selector'=>'.wrap.wpl-wp h2', 'content'=>$content, 'position'=>array('edge'=>'top', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>__('Next', WPL_TEXTDOMAIN))));

$content = '<h3>'.__('Optional add-ons', WPL_TEXTDOMAIN).'</h3><p>'.__('WPL has some optional add-ons for extending its functionality. You can download and install if you need.', WPL_TEXTDOMAIN).'</p>';
$tips[] = array('id'=>2, 'selector'=>'#wpl_dashboard_ni_addons h3', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>__('Next', WPL_TEXTDOMAIN)), 3=>array('label'=>__('Previous', WPL_TEXTDOMAIN))));

$content = '<h3>'.__('WPL Change-log', WPL_TEXTDOMAIN).'</h3><p>'.__('Browse WPL change-log.', WPL_TEXTDOMAIN).'</p>';
$tips[] = array('id'=>3, 'selector'=>'#wpl_dashboard_changelog h3', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>__('Next', WPL_TEXTDOMAIN)), 3=>array('label'=>__('Previous', WPL_TEXTDOMAIN))));

$content = '<h3>'.__('Update WPL PRO and its add-ons', WPL_TEXTDOMAIN).'</h3><p>'.__('Here you can update your WPL PRO and its addons.', WPL_TEXTDOMAIN).'</p>';
$tips[] = array('id'=>4, 'selector'=>'#wpl_dashboard_side_addons h3', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>__('Next', WPL_TEXTDOMAIN)), 3=>array('label'=>__('Previous', WPL_TEXTDOMAIN))));

$content = '<h3>'.__('WPL manual and Support', WPL_TEXTDOMAIN).'</h3><p>'.__('Here you can download WPL manual and check its KB articles. You can find answer of your questions here. Please feel free to open a support ticket if you couldn\'t find an answer to your question in WPL manual and KB articles.', WPL_TEXTDOMAIN).'</p>';
$tips[] = array('id'=>5, 'selector'=>'#wpl_dashboard_side_support h3', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(2=>array('label'=>__('Next', WPL_TEXTDOMAIN)), 3=>array('label'=>__('Previous', WPL_TEXTDOMAIN))));

$content = '<h3>'.__('Browse KB articles', WPL_TEXTDOMAIN).'</h3><p>'.__('You can find all of WPL KB articles here. Even you can search on them. If you have any questions, please search on KB articles first. In most of times you will find your answer in minutes.', WPL_TEXTDOMAIN).'</p>';
$tips[] = array('id'=>6, 'selector'=>'#wpl_dashboard_side_announce h3', 'content'=>$content, 'position'=>array('edge'=>'bottom', 'align'=>'left'), 'buttons'=>array(3=>array('label'=>__('Previous', WPL_TEXTDOMAIN))));

return $tips;