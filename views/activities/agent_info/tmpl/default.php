<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$user_id = isset($params['user_id']) ? $params['user_id'] : '';
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : NULL;
$picture_width = isset($params['picture_width']) ? $params['picture_width'] : '90';
$picture_height = isset($params['picture_height']) ? $params['picture_height'] : '100';
$mailto = isset($params['mailto']) ? $params['mailto'] : 0;

/** getting user id from current property (used in property_show and property_listing) **/
if(!trim($user_id)) $user_id = $wpl_properties['current']['data']['user_id'];

$wpl_user = wpl_users::full_render($user_id, wpl_users::get_pshow_fields(), NULL, array(), true);

/** resizing profile image **/
$params                   = array();
$params['image_parentid'] = $user_id;
$params['image_name']     = isset($wpl_user['profile_picture']['name']) ? $wpl_user['profile_picture']['name'] : '';
$profile_path             = isset($wpl_user['profile_picture']['path']) ? $wpl_user['profile_picture']['path'] : '';
$profile_image            = wpl_images::create_profile_images($profile_path, $picture_width, $picture_height, $params);

/** resizing company logo **/
$params                   = array();
$params['image_parentid'] = $user_id;
$params['image_name']     = isset($wpl_user['company_logo']['name']) ? $wpl_user['company_logo']['name'] : '';
$logo_path                = isset($wpl_user['company_logo']['path']) ? $wpl_user['company_logo']['path'] : '';
//$logo_image               = isset($wpl_user['company_logo']['url']) ? $wpl_user['company_logo']['url'] : '';
$logo_image               = wpl_images::create_profile_images($logo_path, $picture_width, $picture_height, $params);

$agent_name               = isset($wpl_user['materials']['first_name']['value']) ? $wpl_user['materials']['first_name']['value'] : '';
$agent_l_name             = isset($wpl_user['materials']['last_name']['value']) ? $wpl_user['materials']['last_name']['value'] : '';
$company_name             = isset($wpl_user['materials']['company_name']['value']) ? $wpl_user['materials']['company_name']['value'] : '';
?>
<div class="wpl_agent_info" id="wpl_agent_info<?php echo $user_id; ?>" itemscope >
	
	<div class="wpl_agent_info_l">
		<div class="image_container">
			<div class="front <?php if($logo_image) echo 'has_logo'; ?>">
				<?php if($profile_image): ?>
					<img itemprop="image" src="<?php echo $profile_image; ?>" class="profile_image" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" />
				<?php else: ?>
			    	<div class="no_image" style="width:<?php $picture_width; ?>px;height:<?php $picture_height; ?>px;"></div>
			    <?php endif; ?>			
			</div>
			<?php if($logo_image): ?>
            <div class="back">
                <img itemprop="image" src="<?php echo $logo_image; ?>" class="logo" alt="<?php echo $company_name; ?>" />
            </div>
		    <?php endif; ?>
		</div>
		<div class="company_details">
			<div itemprop="name" class="company_name"><?php echo $company_name; ?></div>
			<?php if(isset($wpl_user['data']['company_address'])): ?>
	            <div itemprop="address" class="company_address"><?php echo $wpl_user['data']['company_address']; ?></div>
			<?php endif; ?>
		</div>
	</div>
    
	<div class="wpl_agent_info_r">
		<ul>
			<li class="name" itemprop="name" ><a href="<?php echo wpl_users::get_profile_link($user_id); ?>"><?php echo $agent_name.' '.$agent_l_name; ?></a></li>
			
			<?php if(isset($wpl_user['materials']['website']['value'])): ?>
            <li class="website"><a itemprop="url"  href="<?php echo $wpl_user['materials']['website']['value']; ?>" target="_blank"><?php echo __('View website', WPL_TEXTDOMAIN) ?></a></li>
			<?php endif; ?>
			
			<?php if(isset($wpl_user['materials']['tel']['value'])): ?>
			<li itemprop="telephone" class="tel"><?php echo $wpl_user['materials']['tel']['value']; ?></li>
			<?php endif; ?>
            
			<?php if(isset($wpl_user['materials']['mobile']['value'])): ?>
			<li itemprop="telephone" class="mobile"><?php echo $wpl_user['materials']['mobile']['value']; ?></li>
			<?php endif; ?>
			
			<?php if(isset($wpl_user['materials']['fax']['value'])): ?>
			<li itemprop="faxNumber" class="fax"><?php echo $wpl_user['materials']['fax']['value']; ?></li>
			<?php endif; ?>
			
			<?php if(isset($wpl_user['main_email_url'])): ?>
			<li class="email">
                <?php if($mailto): ?>
                <a itemprop="email" href="mailto:<?php echo $wpl_user['materials']['main_email']['value']; ?>"><img src="<?php echo $wpl_user['main_email_url']; ?>" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" /></a>
                <?php else: ?>
                <img src="<?php echo $wpl_user['main_email_url']; ?>" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" />
                <?php endif; ?>
            </li>
			<?php endif; ?>
			
			<?php if(isset($wpl_user['second_email_url'])): ?>
			<li class="second_email">
                <?php if($mailto): ?>
                <a itemprop="email" href="mailto:<?php echo $wpl_user['materials']['secondary_email']['value']; ?>"><img src="<?php echo $wpl_user['second_email_url']; ?>" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" /></a>
                <?php else: ?>
                <img src="<?php echo $wpl_user['second_email_url']; ?>" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" />
                <?php endif; ?>
            </li>
			<?php endif; ?>
		</ul>
	</div>
</div>