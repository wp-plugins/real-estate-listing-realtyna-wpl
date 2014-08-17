<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

if($type == 'locations' and !$done_this)
{
    /**
        Howard: for better performance and speed of query I didn't include location fields here.
        Because Locations are rendered before and we don't need them right now.
    **/
	$done_this = true;
}
