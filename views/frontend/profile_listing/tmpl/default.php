<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

_wpl_import($this->tpl_path.'.scripts.js');
?>
<div id="wpl_profile_listing_main_container" class="container">
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
        ?>
        <div class="wpl_profile_container" id="wpl_profile_container<?php echo $profile['data']['id']; ?>">
            <div class="wpl_profile_picture">
                <div class="front">
                    <?php if(isset($profile['profile_picture']['url'])){
                        echo '<img src="'.$profile['profile_picture']['url'].'" />';
                    }else{
                        echo '<div class="no_image"></div>';
                    }
                    ?>
                </div>
                <div class="back">
                    <a href="<?php echo $profile['profile_link']; ?>" class="view_properties"><?php echo __('View properties', WPL_TEXTDOMAIN); ?></a>
                </div>
            </div>

            <div class="wpl_profile_container_title">
                <?php
                $agent_name = (isset($profile['rendered'][900]['value']) ? $profile['rendered'][900]['value'] : '') ;
                $agent_l_name = (isset($profile['rendered'][901]['value']) ? $profile['rendered'][901]['value'] : '');
                echo '<div class="title">'.$agent_name.' '.$agent_l_name.'</div>';
                if(isset($profile['main_email_url'])) echo '<img src="'.$profile["main_email_url"].'" />';
                ?>
            </div>
            <ul>
                <?php if(isset($profile['rendered'][904]['value'])): ?>
                    <li class="website" data-toggle="tooltip" title="<?php echo $profile['rendered'][904]['value']; ?>">
                        <a href="<?php
                        $urlStr = $profile['rendered'][904]['value'];
                        $parsed = parse_url($urlStr);
                        if (empty($parsed['scheme'])) {
                            $urlStr = 'http://' . ltrim($urlStr, '/');
                        }
                        echo $urlStr;
                        ?>" target="_blank"><?php echo $agent_name.' '.$agent_l_name; ?></a>

                    </li>
                <?php endif;
                if(isset($profile['rendered'][907]['value'])): ?>
                    <li class="phone" data-toggle="tooltip" title="<?php echo $profile['rendered'][907]['value']; ?>"></li>
                <?php endif;
                if(isset($profile['rendered'][909]['value'])): ?>
                    <li class="mobile" data-toggle="tooltip" title="<?php echo $profile['rendered'][909]['value']; ?>"></li>
                <?php endif;
                if(isset($profile['rendered'][908]['value'])): ?>
                    <li class="fax" data-toggle="tooltip" title="<?php echo $profile['rendered'][908]['value']; ?>"></li>
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
