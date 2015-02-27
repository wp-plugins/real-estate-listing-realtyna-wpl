<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

$description_column = 'about';
if(wpl_global::check_multilingual_status() and wpl_addon_pro::get_multiligual_status_by_column($description_column, 2)) $description_column = wpl_addon_pro::get_column_lang_name($description_column, wpl_global::get_current_language(), false);

foreach($this->wpl_profiles as $key=>$profile)
{
    if($key == 'current') continue;

    /** unset previous property **/
    unset($this->wpl_profiles['current']);

    /** set current property **/
    $this->wpl_profiles['current'] = $profile;

    $agent_name   = (isset($profile['materials']['first_name']['value']) ? $profile['materials']['first_name']['value'] : '') ;
    $agent_l_name = (isset($profile['materials']['last_name']['value']) ? $profile['materials']['last_name']['value'] : '');
    
    $description = stripslashes(strip_tags($profile['raw'][$description_column]));
    ?>
    <div itemscope class="wpl_profile_container <?php echo (isset($this->property_css_class) ? $this->property_css_class : ''); ?>" id="wpl_profile_container<?php echo $profile['data']['id']; ?>">
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
                echo '<div class="title" itemprop="name">
                        <a itemprop="url" href="'.$profile['profile_link'].'" >'.$agent_name.' '.$agent_l_name.'</a>
                        <a itemprop="url" href="'.$profile['profile_link'].'>" class="view_properties">'. __('View properties', WPL_TEXTDOMAIN).'</a>
                      </div>';

                if(isset($profile['main_email_url'])) echo '<img src="'.$profile["main_email_url"].'" alt="'.$agent_name.' '.$agent_l_name.'" />';
                
                $cut_position = strrpos(substr($description, 0, 400), '.', -1);
                if(!$cut_position) $cut_position = 399;
                echo '<div class="about">'.substr($description, 0, $cut_position + 1).'</div>';
            ?>
        </div>
        <ul>
            <?php if(isset($profile['materials']['website']['value'])): ?>
            <li class="website" data-toggle="tooltip" title="<?php echo $profile['materials']['website']['value']; ?>">
                <a itemprop="url" href="<?php
                $urlStr = $profile['materials']['website']['value'];
                $parsed = parse_url($urlStr);
                
                if(empty($parsed['scheme'])) $urlStr = 'http://' . ltrim($urlStr, '/');
                echo $urlStr;
                ?>" target="_blank"><?php echo $urlStr; ?></a>
            </li>
            <?php endif; ?>
            
            <?php if(isset($profile['materials']['tel']['value'])): ?>
            <li itemprop="telephone" class="phone" data-toggle="tooltip" title="<?php echo $profile['materials']['tel']['value']; ?>"><?php echo $profile['materials']['tel']['value']; ?></li>
            <?php endif; ?>
            
            <?php if(isset($profile['materials']['mobile']['value'])): ?>
            <li itemprop="telephone" class="mobile" data-toggle="tooltip" title="<?php echo $profile['materials']['mobile']['value']; ?>"><?php echo $profile['materials']['mobile']['value']; ?></li>
            <?php endif; ?>
            
            <?php if(isset($profile['materials']['fax']['value'])): ?>
            <li itemprop="faxNumber" class="fax" data-toggle="tooltip" title="<?php echo $profile['materials']['fax']['value']; ?>"><?php echo $profile['materials']['fax']['value']; ?></li>
            <?php endif ;?>
        </ul>
    </div>
    <?php
}