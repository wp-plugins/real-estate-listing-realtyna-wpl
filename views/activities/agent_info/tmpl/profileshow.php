<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$user_id = isset($params['user_id']) ? $params['user_id'] : '';
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : NULL;
$picture_width = isset($params['picture_width']) ? $params['picture_width'] : '175';
$picture_height = isset($params['picture_height']) ? $params['picture_height'] : '145';
$mailto = isset($params['mailto']) ? $params['mailto'] : 0;

$description_column = 'about';
if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, 2)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);

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
$logo_image               = isset($wpl_user['company_logo']['url']) ? $wpl_user['company_logo']['url'] : '';

$agent_name               = isset($wpl_user['materials']['first_name']['value']) ? $wpl_user['materials']['first_name']['value'] : '';
$agent_l_name             = isset($wpl_user['materials']['last_name']['value']) ? $wpl_user['materials']['last_name']['value'] : '';
$company_name             = isset($wpl_user['materials']['company_name']['value']) ? $wpl_user['materials']['company_name']['value'] : '';
$description              = stripslashes($wpl_user['raw'][$description_column]);
?>
<div class="wpl_agent_info clearfix" id="wpl_agent_info" itemscope>
	<div class="wpl_agent_details clearfix">
		<div class="wpl_agent_info_l">
			<?php
			if(isset($wpl_user['profile_picture'])) echo '<img itemprop="image" src="'.$profile_image.'" alt="'.$agent_name. ' '.$agent_l_name.'" />';
			else echo '<div class="no_image" style="width:'.$picture_width.'px;height: '.$picture_height.'px;"></div>';
			?>
		</div>
		<div class="wpl_agent_info_c col-md-7 clearfix">
			<div class="wpl_profile_container_title" itemprop="name" >
				<?php echo $agent_name. ' '.$agent_l_name; ?>
			</div>
			<ul>
				<?php if(isset($wpl_user['materials']['website']['value'])): ?>
					<li class="website"><a itemprop="url" href="<?php echo $wpl_user['materials']['website']['value']; ?>" target="_blank"><?php echo __('View website', WPL_TEXTDOMAIN) ?></a></li>
				<?php endif; ?>

				<?php if(isset($wpl_user['materials']['tel']['value'])): ?>
					<li class="tel" itemprop="telephone" ><?php echo $wpl_user['materials']['tel']['value']; ?></li>
				<?php endif; ?>

				<?php if(isset($wpl_user['materials']['mobile']['value'])): ?>
					<li class="mobile" itemprop="telephone" ><?php echo $wpl_user['materials']['mobile']['value']; ?></li>
				<?php endif; ?>

				<?php if(isset($wpl_user['materials']['fax']['value'])): ?>
					<li class="fax" itemprop="faxNumber"><?php echo $wpl_user['materials']['fax']['value']; ?></li>
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
		<div class="wpl_agent_info_r col-md-2">
			<?php
			if(isset($wpl_user['company_logo'])) echo '<img itemprop="logo" src="'.$logo_image.'" alt="'.$company_name.'" />';
			if(trim($company_name) != '') echo '<div class="company" itemprop="name">'.$company_name.'</div>';
			if(isset($wpl_user['data']['company_address'])) echo '<div class="location" itemprop="address">'.$wpl_user['data']['company_address'].'</div>';
			?>
		</div>
	</div>
	<div class="wpl_agent_about"><?php echo $description;?></div>
</div>