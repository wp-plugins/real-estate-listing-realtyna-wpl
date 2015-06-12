<?php

/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
class wpl_io_cmd_get_settings extends wpl_io_cmd_base
{
    protected $built;


    /**
     * This method is the main method of each commands
     * @return mixed
     */
    public function build()
    {
        $settings = wpl_addon_mobile_application::get_settings();
        $properties_count = wpl_property::get_properties_count();

        print_r($this->get_listing_types());die;

        foreach($settings as $key => $values)
        {
            $name = strtoupper($values['name']);
            $value = $values['value'];
            $this->built["settings"]["app_settings"][] = array(
                'name' => $name,
                'value' => $value
            );

            if($name == 'SHOW_BUBBLE_LIMITATION')
            {
                if($properties_count <= $value)
                {
                    $this->built["settings"]["app_settings"][] = array(
                        'name' => 'SHOW_BUBBLE',
                        'value' => 'true'
                    );
                }
                else
                {
                    $this->built["settings"]["app_settings"][] = array(
                        'name' => 'SHOW_BUBBLE',
                        'value' => 'false'
                    );
                }
            }
        }


        $this->built['filter_fragment'] = $this->create_filter_fragment();
        $this->built["settings"]['listing_types'] = $this->get_listing_types();
        $this->built["settings"]['update_status'] = $this->get_update_status();

    }

    /**
     * Data validation
     * @return boolean
     */
    public function validate()
    {
        if(wpl_global::check_addon('mobile_application') == false)
        {
            $this->error = "Addon mobile application not installed";
            return false;
        }
        return true;
    }

    private function create_filter_fragment()
    {
    }

    private function get_listing_types()
    {
        $listing_types = wpl_global::get_listings();
        $enabled_listing_types = array();
        foreach($listing_types as $value)
        {
            if($value['enabled_in_mobile'] == 1)
            {
                $enabled_listing_types[] = $value;
            }
        }
        return $enabled_listing_types;
    }

    private function get_update_status()
    {
    }
}