<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

/** set params **/
$user_id = isset($params['user_id']) ? $params['user_id'] : '';
$wpl_properties = isset($params['wpl_properties']) ? $params['wpl_properties'] : NULL;
$picture_width = isset($params['picture_width']) ? $params['picture_width'] : '175';
$picture_height = isset($params['picture_height']) ? $params['picture_height'] : '145';
$mailto = isset($params['mailto']) ? $params['mailto'] : 0;

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
<div class="wpl_agent_info" id="wpl_agent_info">
	<div class="wpl_agent_info_l">
        <?php
			if(isset($wpl_user['profile_picture'])) echo '<img src="'.$profile_image.'" alt="'.$agent_name. ' '.$agent_l_name.'" />';
			else echo '<div class="no_image"></div>';
        ?>
	</div>
	<div class="wpl_agent_info_c col-md-7 clearfix">
        <div class="wpl_profile_container_title">
            <?php echo $agent_name. ' '.$agent_l_name; ?>
        </div>
		<ul>
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
			<li class="email">
                <?php if($mailto): ?>
                <a href="mailto:<?php echo $wpl_user['rendered'][914]['value']; ?>"><img src="<?php echo $wpl_user['main_email_url']; ?>" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" /></a>
                <?php else: ?>
                <img src="<?php echo $wpl_user['main_email_url']; ?>" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" />
                <?php endif; ?>
            </li>
			<?php endif; ?>
            
			<?php if(isset($wpl_user['second_email_url'])): ?>
			<li class="second_email">
                <?php if($mailto): ?>
                <a href="mailto:<?php echo $wpl_user['rendered'][905]['value']; ?>"><img src="<?php echo $wpl_user['second_email_url']; ?>" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" /></a>
                <?php else: ?>
                <img src="<?php echo $wpl_user['second_email_url']; ?>" alt="<?php echo $agent_name. ' '.$agent_l_name; ?>" />
                <?php endif; ?>
            </li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="wpl_agent_info_r col-md-2">
		<?php
		if(isset($wpl_user['company_logo'])) echo '<img src="'.$logo_image.'" alt="'.$company_name.'" />';
		if(trim($company_name) != '') echo '<div class="company">'.$company_name.'</div>';
        if(isset($wpl_user['data']['company_address'])) echo '<div class="location">'.$wpl_user['data']['company_address'].'</div>';
        ?>
	</div>
</div>