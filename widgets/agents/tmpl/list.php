<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

include _wpl_import('widgets.agents.scripts.js', true, true);
?>
<ul class="wpl_agents_widget_container list <?php echo $this->css_class; ?>">
    <?php
    foreach($wpl_profiles as $key=>$profile)
    {
        $agent_name   = (isset($profile['materials']['first_name']['value']) ? $profile['materials']['first_name']['value'] : '') ;
        $agent_l_name = (isset($profile['materials']['last_name']['value']) ? $profile['materials']['last_name']['value'] : '');
        ?>
        <li class="wpl_profile_box" id="wpl_profile_container<?php echo $profile['data']['id']; ?>" itemscope>
            <div class="profile_left">
                <a class="more_info" href="<?php echo $profile['profile_link']; ?>" class="view_properties">
                    <span 
                        <?php 
                        echo 'style="'.(isset($profile['profile_picture']['image_width']) ? 'width:'.$profile['profile_picture']['image_width'].'px;' : '').(isset($profile['profile_picture']['image_height']) ? 'height:'.$profile['profile_picture']['image_height'].'px;' : '').'"'; ?>>
                        <?php
                        if(isset($profile['profile_picture']['url']))
                        {
                            echo '<img itemprop="image" src="'.$profile['profile_picture']['url'].'" alt="'.$agent_name.' '.$agent_l_name.'" />';
                        }
                        else
                        {
                            echo '<div class="no_image"></div>';
                        }
                        ?>
                    </span>
                </a>
            </div>
            <div class="profile_right">
                 <ul>
                    <?php
                    echo '<li class="title" itemprop="name">'.$agent_name.' '.$agent_l_name.'</li>';
                    if(isset($profile['main_email_url'])) echo '<li class="email"><img src="'.$profile["main_email_url"].'" alt="'.$agent_name.' '.$agent_l_name.'" /></li>';

                    if(isset($profile['materials']['website']['value'])): ?>
                        <li class="website">
                            <a itemprop="url" href="<?php
                            $urlStr = $profile['materials']['website']['value'];
                            $parsed = parse_url($urlStr);
                            if (empty($parsed['scheme'])) {
                                $urlStr = 'http://' . ltrim($urlStr, '/');
                            }
                            echo $urlStr;
                            ?>" target="_blank"><?php echo __('View website', WPL_TEXTDOMAIN) ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if(isset($profile['materials']['tel']['value'])): ?>
                        <li itemprop="telephone" class="phone"><?php echo $profile['materials']['tel']['value']; ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </li>
    <?php
    }
    ?>
</ul>
