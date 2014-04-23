<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$user_id = isset($params['user_id']) ? $params['user_id'] : '';
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : NULL;
$profile_picture_width = isset($params['picture_width']) ? $params['picture_width'] : '90';
$profile_picture_height = isset($params['picture_height']) ? $params['picture_height'] : '100';

/** getting user id from current property (used in property_show and property_listing) **/
if(!trim($user_id)) $user_id = $wpl_properties['current']['data']['user_id'];

$wpl_user = wpl_users::full_render($user_id, wpl_users::get_plisting_fields());

$params                   = array();
$params['image_parentid'] = $user_id;
$params['image_name']     = isset($wpl_user['profile_picture']['name']) ? $wpl_user['profile_picture']['name'] : '';
$picture_path             = isset($wpl_user['profile_picture']['path']) ? $wpl_user['profile_picture']['path'] : '';
$profile_image            = wpl_images::create_profile_images($picture_path, $profile_picture_width, $profile_picture_height, $params);

$agent_name               = (isset($wpl_user['rendered']['900']['value']) ? $wpl_user['rendered']['900']['value'] : '') ;
$agent_l_name             = (isset($wpl_user['rendered']['901']['value']) ? $wpl_user['rendered']['901']['value'] : '');
?>
<div class="wpl_agent_info" id="wpl_agent_info<?php echo $user_id; ?>">
	<?php if($profile_image): ?>
	<div class="wpl_agent_info_l">
		<img src="<?php echo $profile_image; ?>" class="profile_image" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" />
	</div>
    <?php else: ?>
    <div class="no_image"></div>
    <?php endif; ?>
	<div class="wpl_agent_info_r">
		<ul>
			<li class="name"><?php echo $agent_name. ' '.$agent_l_name; ?></li>
			
			<?php if(isset($wpl_user['rendered']['904']['value'])): ?>
            <li class="website"><a href="<?php echo $wpl_user['rendered']['904']['value']; ?>" target="_blank"><?php echo $wpl_user['rendered']['904']['value']; ?></a></li>
			<?php endif; ?>
			
			<?php if(isset($wpl_user['rendered']['907']['value'])): ?>
			<li class="tel"><?php echo $wpl_user['rendered']['907']['value']; ?></li>
			<?php endif; ?>
            
			<?php if(isset($wpl_user['rendered']['909']['value'])): ?>
			<li class="mobile"><?php echo $wpl_user['rendered']['909']['value']; ?></li>
			<?php endif; ?>
			
			<?php if(isset($wpl_user['rendered']['908']['value'])): ?>
			<li class="fax"><?php echo $wpl_user['rendered']['908']['value']; ?></li>
			<?php endif; ?>
			
			<?php if(isset($wpl_user['main_email_url'])): ?>
			<li class="email"><img src="<?php echo $wpl_user['main_email_url']; ?>" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" /></li>
			<?php endif; ?>
			
			<?php if(isset($wpl_user['second_email_url'])): ?>
			<li class="second_email"><img src="<?php echo $wpl_user['second_email_url']; ?>" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" /></li>
			<?php endif; ?>
            
            <?php if(isset($wpl_user['data']['company_address'])): ?>
            <li class="company_address"><?php echo $wpl_user['data']['company_address']; ?></li>
			<?php endif; ?>
		</ul>
	</div>
</div>