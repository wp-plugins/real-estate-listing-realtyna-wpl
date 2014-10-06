<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$this->_wpl_import($this->tpl_path.'.scripts.js', true, true);
?>
<div id="wpl_profile_listing_main_container">
	<div class="wpl_sort_options_container">
        <div class="wpl_sort_options_container_title"><?php echo __("Sort Option", WPL_TEXTDOMAIN) ?></div>
        <?php echo $this->model->generate_sorts(); ?>
    </div>
    <div class="wpl_profile_listing_container" id="wpl_profile_listing_container">
		<?php
        foreach($this->wpl_profiles as $key=>$profile)
        {
			if($key == 'current') continue;
			
			/** unset previous property **/
			unset($this->wpl_profiles['current']);
			
			/** set current property **/
			$this->wpl_profiles['current'] = $profile;

            $agent_name   = (isset($profile['materials']['first_name']['value']) ? $profile['materials']['first_name']['value'] : '') ;
            $agent_l_name = (isset($profile['materials']['last_name']['value']) ? $profile['materials']['last_name']['value'] : '');
        ?>
        <div itemscope  class="wpl_profile_container" id="wpl_profile_container<?php echo $profile['data']['id']; ?>">
            <div class="wpl_profile_picture">
                <div class="front">
                    <?php
                        if(isset($profile['profile_picture']['url'])) echo '<img itemprop="image" src="'.$profile['profile_picture']['url'].'" alt="'.$agent_name.' '.$agent_l_name.'" />';
                        else echo '<div class="no_image"></div>';
                    ?>
                </div>
                <div class="back">
                    <a itemprop="url" href="<?php echo $profile['profile_link']; ?>" class="view_properties"><?php echo __('View properties', WPL_TEXTDOMAIN); ?></a>
                </div>
            </div>

            <div class="wpl_profile_container_title">
                <?php
                    echo '<div class="title" itemprop="name">'.$agent_name.' '.$agent_l_name.'</div>';
                    if(isset($profile['main_email_url'])) echo '<img src="'.$profile["main_email_url"].'" alt="'.$agent_name.' '.$agent_l_name.'" />';
                ?>
            </div>
            <ul>
                <?php if(isset($profile['materials']['website']['value'])): ?>
                <li class="website" data-toggle="tooltip" title="<?php echo $profile['materials']['website']['value']; ?>">
                    <a itemprop="url" href="<?php
                    $urlStr = $profile['materials']['website']['value'];
                    $parsed = parse_url($urlStr);
                    if (empty($parsed['scheme'])) {
                        $urlStr = 'http://' . ltrim($urlStr, '/');
                    }
                    echo $urlStr;
                    ?>" target="_blank"><?php echo $agent_name.' '.$agent_l_name; ?></a>
                </li>
                <?php endif; ?>
                <?php if(isset($profile['materials']['tel']['value'])): ?>
                    <li itemprop="telephone" class="phone" data-toggle="tooltip" title="<?php echo $profile['materials']['tel']['value']; ?>"></li>
                <?php endif; ?>
                <?php if(isset($profile['materials']['mobile']['value'])): ?>
                    <li itemprop="telephone" class="mobile" data-toggle="tooltip" title="<?php echo $profile['materials']['mobile']['value']; ?>"></li>
                <?php endif; ?>
                <?php if(isset($profile['materials']['fax']['value'])): ?>
                    <li itemprop="faxNumber" class="fax" data-toggle="tooltip" title="<?php echo $profile['materials']['fax']['value']; ?>"></li>
                <?php endif ;?>
            </ul>
        </div>
        <?php
        }
        ?>
        <div class="wpl_pagination_container">
            <?php echo $this->pagination->show(); ?>
        </div>
	</div>
</div>
