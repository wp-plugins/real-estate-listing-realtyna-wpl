<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$user_id = isset($params['user_id']) ? $params['user_id'] : '';
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : NULL;
$profile_picture_width = isset($params['picture_width']) ? $params['picture_width'] : '175';
$profile_picture_height = isset($params['picture_height']) ? $params['picture_height'] : '145';

/** getting user id from current property (used in property_show and property_listing) **/
if(!trim($user_id)) $user_id = $wpl_properties['current']['data']['user_id'];

$wpl_user = wpl_users::full_render($user_id, wpl_users::get_plisting_fields());

$params = array();
$params['image_parentid'] = $user_id;
$params['image_name'] = isset($wpl_user['profile_picture']['name']) ? $wpl_user['profile_picture']['name'] : '';
$picture_path = isset($wpl_user['profile_picture']['path']) ? $wpl_user['profile_picture']['path'] : '';
$profile_image = wpl_images::create_profile_images($picture_path, $profile_picture_width, $profile_picture_height, $params);
?>
<div class="wpl_agent_info" id="wpl_agent_info">
	<div class="wpl_agent_info_l">
        <?php
			if(isset($wpl_user['profile_picture'])) echo '<img src="'.$profile_image.'" />';
			else echo '<div class="no_image"></div>';
        ?>
	</div>
	<div class="wpl_agent_info_c col-md-7 clearfix">
        <div class="wpl_profile_container_title">
            <?php echo (isset($wpl_user['rendered']['900']['value']) ? $wpl_user['rendered']['900']['value'] : '').' '.(isset($wpl_user['rendered']['901']['value']) ? $wpl_user['rendered']['901']['value'] : ''); ?>
        </div>
		<ul>
			<?php if(isset($wpl_user['rendered']['904']['value'])){ ?>
			<li class="website"><?php echo $wpl_user['rendered']['904']['value']; ?></li>
			<?php
			}
			if(isset($wpl_user['rendered']['907']['value'])){?>
			<li class="tel"><?php echo $wpl_user['rendered']['907']['value']; ?></li>
			<?php
			}
			if(isset($wpl_user['rendered']['909']['value'])){?>
			<li class="mobile"><?php echo $wpl_user['rendered']['909']['value']; ?></li>
			<?php
			}
			if(isset($wpl_user['rendered']['908']['value'])){?>
			<li class="fax"><?php echo $wpl_user['rendered']['908']['value']; ?></li>
			<?php
			}
			if(isset($wpl_user['main_email_url'])){?>
			<li class="email"><img src="<?php echo $wpl_user['main_email_url']; ?>" /></li>
			<?php
			}
			if(isset($wpl_user['second_email_url'])){?>
			<li class="second_email"><img src="<?php echo $wpl_user['second_email_url']; ?>" /></li>
			<?php } ?>
		</ul>
	</div>
	<div class="wpl_agent_info_r col-md-2">
		<?php
		if(isset($wpl_user['company_logo'])) echo '<img src="'.$wpl_user['company_logo']['url'].'" />';
		if(isset($wpl_user['rendered']['902']['value'])) echo '<div class="company">'.$wpl_user['rendered']['902']['value'].'</div>';
        if(isset($wpl_user['location_text'])) echo '<div class="location">'.$wpl_user['location_text'].'</div>';
        ?>
	</div>
</div>