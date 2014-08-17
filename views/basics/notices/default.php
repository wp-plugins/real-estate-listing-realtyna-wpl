<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$rendered .= '<div class="wpl_notice_row" id="wpl_notice_row'.$notice['id'].'">';
$rendered .= $show_counter ? '<div class="wpl_notice_counter">'.$i.'</div>' : '';
$rendered .= trim($notice['title']) != '' ? '<div class="wpl_notice_title" id="wpl_notice_title'.$notice['id'].'">'.$notice['title'].'</div>' : '';
$rendered .= '<div class=wpl_notice_body"" id="wpl_notice_body'.$notice['id'].'">'.$notice['body'].'</div>';
$rendered .= '</div>';
?>