<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$user_id        = isset($params['user_id']) ? $params['user_id'] : '';
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : NULL;
$picture_width  = isset($params['picture_width']) ? $params['picture_width'] : '90';
$picture_height = isset($params['picture_height']) ? $params['picture_height'] : '100';

/** getting user id from current property (used in property_show and property_listing) **/
if(!trim($user_id)) $user_id = $wpl_properties['current']['data']['user_id'];

$wpl_user = wpl_users::full_render($user_id, wpl_users::get_plisting_fields());

/** resizing profile image **/
$params                   = array();
$params['image_parentid'] = $user_id;
$params['image_name']     = isset($wpl_user['profile_picture']['name']) ? $wpl_user['profile_picture']['name'] : '';
$picture_path             = isset($wpl_user['profile_picture']['path']) ? $wpl_user['profile_picture']['path'] : '';
$profile_image            = wpl_images::create_profile_images($picture_path, $picture_width, $picture_height, $params);

/** resizing company logo **/
$params                   = array();
$params['image_parentid'] = $user_id;
$params['image_name']     = isset($wpl_user['company_logo']['name']) ? $wpl_user['company_logo']['name'] : '';
$logo_path                = isset($wpl_user['company_logo']['path']) ? $wpl_user['company_logo']['path'] : '';
$logo_image               = wpl_images::create_profile_images($logo_path, $picture_width, $picture_height, $params);

$agent_name               = (isset($wpl_user['rendered']['900']['value']) ? $wpl_user['rendered']['900']['value'] : '');
$agent_l_name             = (isset($wpl_user['rendered']['901']['value']) ? $wpl_user['rendered']['901']['value'] : '');
$company_name             = (isset($wpl_user['rendered']['902']['value']) ? $wpl_user['rendered']['902']['value'] : '');
?>
<div class="wpl_agent_info" id="wpl_agent_info<?php echo $user_id; ?>">
	
	<div class="wpl_agent_info_l">
		<div class="image_container">
			<div class="front <?php if($logo_image) echo 'has_logo'; ?>">
				<?php if($profile_image): ?>
					<img src="<?php echo $profile_image; ?>" class="profile_image" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" />	
				<?php else: ?>
			    	<div class="no_image"></div>
			    <?php endif; ?>			
			</div>
			<?php if($logo_image): ?>
				<div class="back">
					<img src="<?php echo $logo_image; ?>" class="logo" alt="<?php echo $company_name; ?>" />
				</div>
		    <?php endif; ?>
		</div>
		<div class="company_details">
			<div class="company_name"><?php echo $company_name; ?></div>

			<?php if(isset($wpl_user['data']['company_address'])): ?>
	            <div class="company_address"><?php echo $wpl_user['data']['company_address']; ?></div>
			<?php endif; ?>
		</div>
	</div>
    
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
		</ul>
	</div>
</div>