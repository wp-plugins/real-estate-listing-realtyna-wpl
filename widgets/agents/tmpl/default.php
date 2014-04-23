<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import("widgets.agents.scripts.js", true, true);
?>
<div class="wpl_agents_widget_container <?php echo ( (isset($instance['data']['style']) and $instance['data']['style'] == '2') ? 'vertical' : '') ?>">
    <?php
    foreach($wpl_profiles as $key=>$profile)
    {
        $agent_name   = (isset($profile['rendered'][900]['value']) ? $profile['rendered'][900]['value'] : '') ;
        $agent_l_name = (isset($profile['rendered'][901]['value']) ? $profile['rendered'][901]['value'] : '');
        ?>
        <div class="wpl_profile_container" id="wpl_profile_container<?php echo $profile['data']['id']; ?>">
            <div class="wpl_profile_picture">
                <div class="front">
                    <?php


                    if(isset($profile['profile_picture']['url']))
                    {
                        echo '<img src="'.$profile['profile_picture']['url'].'" alt="'.$agent_name.' '.$agent_l_name.'" />';
                    }
                    else
                    {
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
                    
                    echo '<div class="title">'.$agent_name.' '.$agent_l_name.'</div>';
                    if(isset($profile['main_email_url'])) echo '<img src="'.$profile["main_email_url"].'" alt="'.$agent_name.' '.$agent_l_name.'" />';
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
                <?php endif; ?>
                <?php if(isset($profile['rendered'][907]['value'])): ?>
                    <li class="phone" data-toggle="tooltip" title="<?php echo $profile['rendered'][907]['value']; ?>"></li>
                <?php endif; ?>
                <?php if(isset($profile['rendered'][909]['value'])): ?>
                    <li class="mobile" data-toggle="tooltip" title="<?php echo $profile['rendered'][909]['value']; ?>"></li>
                <?php endif; ?>
                <?php if(isset($profile['rendered'][908]['value'])): ?>
                    <li class="fax" data-toggle="tooltip" title="<?php echo $profile['rendered'][908]['value']; ?>"></li>
                <?php endif ;?>
            </ul>
        </div>
    <?php
    }
    ?>
</div>
