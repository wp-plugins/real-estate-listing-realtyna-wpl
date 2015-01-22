<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** define tips **/
$tips = array();

$content = '<h3>'.__('Filter Activities', WPL_TEXTDOMAIN).'</h3><p>'.__('You can filter activities here. Lets search for "Google"!', WPL_TEXTDOMAIN).'</p>';
$tips[] = array('id'=>1, 'selector'=>'#activity_manager_filter', 'content'=>$content, 'position'=>array('edge'=>'left', 'align'=>'center'), 'buttons'=>array(2=>array('label'=>__('Next', WPL_TEXTDOMAIN), 'code'=>'wplj("#activity_manager_filter").val("Google");wplj("#activity_manager_filter").trigger("keyup");')));

$content = '<h3>'.__('Manage Activities', WPL_TEXTDOMAIN).'</h3><p>'.__('By clicking on action icons you can simply manage the activities.', WPL_TEXTDOMAIN).'</p>';
$tips[] = array('id'=>2, 'selector'=>'#wpl_actions_td_thead', 'content'=>$content, 'position'=>array('edge'=>'right', 'align'=>'center'), 'buttons'=>array(2=>array('label'=>__('Next', WPL_TEXTDOMAIN)), 3=>array('label'=>__('Previous', WPL_TEXTDOMAIN))));

$content = '<h3>'.__('Add New Activities', WPL_TEXTDOMAIN).'</h3><p>'.__('You can add new activities if needed using this form.', WPL_TEXTDOMAIN).'</p>';
$tips[] = array('id'=>3, 'selector'=>'#wpl_activity_add_chosen', 'content'=>$content, 'position'=>array('edge'=>'right', 'align'=>'center'), 'buttons'=>array(3=>array('label'=>__('Previous', WPL_TEXTDOMAIN))));

return $tips;