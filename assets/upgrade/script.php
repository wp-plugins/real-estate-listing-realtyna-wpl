<?php
/** -- CUSTOM CODES SHOULD BE REMOVED IN NEXT UPDATE PACKAGE -- **/
_wpl_import('libraries.property');
_wpl_import('libraries.users');
_wpl_import('libraries.flex');

/** Server Configurations **/
@ini_set('memory_limit', '-1');
@ini_set('max_execution_time', 0);
@set_time_limit(0);

/** Clean textsearch Column **/
wpl_db::q("UPDATE `#__wpl_users` SET `textsearch`=''");

$users = wpl_users::get_wpl_users();
foreach($users as $user) wpl_users::update_text_search_field($user->id);

/** Regenerate textsearch Column **/
if(wpl_db::num('', 'wpl_properties') <= 3000)
{
    /** Clean Textsearch Field **/
    wpl_db::q("UPDATE `#__wpl_properties` SET `textsearch`=''");

    $listings = wpl_property::select_active_properties('', '`id`');
    foreach($listings as $listing) wpl_property::update_text_search_field($listing['id']);
}