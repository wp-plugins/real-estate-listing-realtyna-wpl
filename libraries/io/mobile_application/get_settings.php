<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_io_cmd_get_settings extends wpl_io_cmd_base
{

    protected $built;

    /**
     * Building the command
     * @return mixed
     */
    public function build()
    {
        $settings = wpl_settings::get_settings();
        $property = new wpl_property();
        $where = array(
            'sf_select_confirmed'=>1,
            'sf_select_finalized'=>1,
            'sf_select_deleted'=>0,
        );

        $property->start(0, 10000000, $settings['default_orderby'], $settings['default_order'], $where);

        $settings = wpl_addon_mobile_application::get_settings();
        $properties_count = $property->get_properties_count();

        foreach($settings as $key=>$values)
        {
            $name = strtoupper($values['name']);
            $value = $values['value'];
            $this->built["settings"]["app_settings"][] = array(
                'name'=>$name,
                'value'=>$value
            );

            if($name == 'SHOW_BUBBLE_LIMITATION')
            {
                if($properties_count <= $value)
                {
                    $this->built["settings"]["app_settings"][] = array(
                        'name'=>'SHOW_BUBBLE',
                        'value'=>'true'
                    );
                }
                else
                {
                    $this->built["settings"]["app_settings"][] = array(
                        'name'=>'SHOW_BUBBLE',
                        'value'=>'false'
                    );
                }
            }
        }

        $this->built['filter_fragment'] = $this->create_filter_fragment();
        $this->built['settings']['listing_types'] = $this->get_listing_types();
        $this->built['settings']['update_status'] = $this->get_update_status();
        $this->built['settings']['listing_sorts'] = $this->get_listing_sorts();
        
        return $this->built;
    }

    /**
     * validation params
     * @return bool
     */
    public function validate()
    {
        if(wpl_global::check_addon('mobile_application') == false)
        {
            $this->error = "Addon mobile application is not installed";
            return false;
        }
        
        return true;
    }

    /**
     * Creating filter section
     * @return array|mixed
     */
    private function create_filter_fragment()
    {
        $filter = array(
            'tabs'=>array(
                'tabs_column_name'=>'sf_select_listing',
                'inner_tabs_column_name'=>'sf_ptcategory',
                'content'=>array(
                    array(
                        'title'=>'For Sale',
                        'tab_id'=>'1',
                        'inner_tabs'=>array(
                            'title'=>'Search Type',
                            'tabs'=>array(
                                array(
                                    'title'=>'Residential',
                                    'id'=>'1',
                                    'icon'=>'ic_residential_property_show_inactive',
                                    'icon_active'=>'ic_residential_property_show_active',
                                    'content'=>array(
                                        array(
                                            'section_type'=>'spinner',
                                            'column_name'=>'sf_select_property_type',
                                            'title'=>'Property Type',
                                            'items'=>array(
                                                array(
                                                    'value'=>'-1',
                                                    'text'=>'Any',
                                                ),
                                                array(
                                                    'value'=>'6',
                                                    'text'=>'Apartment',
                                                ),
                                                array(
                                                    'value'=>'7',
                                                    'text'=>'Residential',
                                                ),
                                                array(
                                                    'value'=>'14',
                                                    'text'=>'Condo',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'Price Range',
                                            'column_name'=>'price',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'$', // Use empty string if don't need to unit
                                            'unit_position'=>'left',
                                            'min_value'=>0,
                                            'max_value'=>10000000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000000,
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bedrooms',
                                            'column_name'=>'bedrooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'0', // Minimum available value
                                                    'max'=>'10', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bathrooms',
                                            'column_name'=>'bathrooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'0', // Minimum available value
                                                    'max'=>'10', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'Living Area',
                                            'column_name'=>'living_area',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'Sqft', // Use empty string if don't need unit
                                            'min_value'=>0,
                                            'max_value'=>10000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000,
                                        ),
                                        array(
                                            'section_type'=>'edit_text',
                                            'title'=>'Keywords',
                                            'column_name'=>'sf_textsearchmeta_keywords',
                                            'placeholder'=>'',
                                            'default_text'=>'',
                                            'add_on_more_options'=>true,
                                        ),
                                        array(
                                            'section_type'=>'checkbox_group',
                                            'title'=>' ',
                                            'groups'=>array(
                                                array(
                                                    'title'=>'Appliances',
                                                    'items'=>array(
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_155',
                                                            'value'=>'Microwave',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_154',
                                                            'value'=>'Stove',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_153',
                                                            'value'=>'Refrigerator',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_156',
                                                            'value'=>'Washing Machine',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_163',
                                                            'value'=>'Dish washer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_157',
                                                            'value'=>'TV',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_159',
                                                            'value'=>'Internet',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_165',
                                                            'value'=>'Satellite',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_160',
                                                            'value'=>'Hair dryer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_164',
                                                            'value'=>'Dishes',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_168',
                                                            'value'=>'Hot Tub',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_169',
                                                            'value'=>'Iron',
                                                        ),

                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Neighborhood',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectf_100',
                                                            'value'=>'Shopping center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_113',
                                                            'value'=>'Town center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_101',
                                                            'value'=>'Hospital',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_112',
                                                            'value'=>'Police station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_109',
                                                            'value'=>'Train station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_108',
                                                            'value'=>'Bus station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_107',
                                                            'value'=>'Airport',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_106',
                                                            'value'=>'Coffee shop',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_105',
                                                            'value'=>'Beach',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_102',
                                                            'value'=>'Cinema',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_103',
                                                            'value'=>'Park',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_110',
                                                            'value'=>'School',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_111',
                                                            'value'=>'University',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_115',
                                                            'value'=>'Tourist site',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_114',
                                                            'value'=>'Exhibition',
                                                        ),
                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Property Tags',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectsp_featured',
                                                            'value'=>'Featured',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_hot',
                                                            'value'=>'Hot offer',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_openhouse',
                                                            'value'=>'Open house',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_forclosure',
                                                            'value'=>'Forclosure',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_call',
                                                            'value'=>'Call',
                                                        ),
                                                    ),
                                                ),
                                            ),
                                            'add_on_more_options'=>true,
                                        ),
                                    ),
                                ),
                                array(
                                    'title'=>'Commercial',
                                    'id'=>'2',
                                    'icon'=>'ic_commercial_property_show_inactive',
                                    'icon_active'=>'ic_commercial_property_show_active',
                                    'content'=>array(
                                        array(
                                            'section_type'=>'spinner',
                                            'column_name'=>'category',
                                            'title'=>'Property Type',
                                            'items'=>array(
                                                array(
                                                    'value'=>'-1',
                                                    'text'=>'Any',
                                                ),
                                                array(
                                                    'value'=>'13',
                                                    'text'=>'Office',
                                                ),
                                                array(
                                                    'value'=>'15',
                                                    'text'=>'Commercial',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'Price Range',
                                            'column_name'=>'price',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'$', // Use empty string if don't need to unit
                                            'unit_position'=>'left',
                                            'min_value'=>0,
                                            'max_value'=>10000000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000000,
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Rooms',
                                            'column_name'=>'bedroooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'-1', // Minimum available value
                                                    'max'=>'-1', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'Buildup Area',
                                            'column_name'=>'living_area',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'Sqft', // Use empty string if don't need unit
                                            'unit_position'=>'left',
                                            'min_value'=>0,
                                            'max_value'=>10000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000,
                                        ),
                                        array(
                                            'section_type'=>'edit_text',
                                            'title'=>'Keywords',
                                            'column_name'=>'sf_textsearchmeta_keywords',
                                            'placeholder'=>'',
                                            'default_text'=>'',
                                        ),
                                    ),
                                ),
                                array(
                                    'title'=>'Land',
                                    'id'=>'3',
                                    'icon'=>'ic_land_property_show_inactive',
                                    'icon_active'=>'ic_land_property_show_active',
                                    'content'=>array(
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'Price Range',
                                            'column_name'=>'price',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'$', // Use empty string if don't need unit
                                            'unit_position'=>'left',
                                            'min_value'=>0,
                                            'max_value'=>10000000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000000,
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'Lot Size',
                                            'column_name'=>'living_area',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'sqft', // Use empty string if don't need unit
                                            'unit_position'=>'right',
                                            'min_value'=>0,
                                            'max_value'=>10000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000,
                                        ),
                                    ),
                                ),
                            )
                        )
                    ),
                    array(
                        'title'=>'For Rent',
                        'tab_id'=>'2',
                        'inner_tabs'=>array(
                            'title'=>'Search Type',
                            'tabs'=>array(
                                array(
                                    'title'=>'Residential',
                                    'id'=>'1',
                                    'icon'=>'ic_residential_property_show_inactive',
                                    'icon_active'=>'ic_residential_property_show_active',
                                    'content'=>array(
                                        array(
                                            'section_type'=>'spinner',
                                            'column_name'=>'category',
                                            'title'=>'Property Type',
                                            'items'=>array(
                                                array(
                                                    'value'=>'-1',
                                                    'text'=>'Any',
                                                ),
                                                array(
                                                    'value'=>'6',
                                                    'text'=>'Apartment',
                                                ),
                                                array(
                                                    'value'=>'7',
                                                    'text'=>'Residential',
                                                ),
                                                array(
                                                    'value'=>'14',
                                                    'text'=>'Condo',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'Price Range',
                                            'column_name'=>'price',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'$', // Use empty string if don't need to unit
                                            'min_value'=>0,
                                            'max_value'=>10000000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000000,
                                        ),
                                        array(
                                            'section_type'=>'spinner',
                                            'column_name'=>'sf_select_price_period',
                                            'title'=>'Price Type',
                                            'items'=>array(
                                                array(
                                                    'value'=>'-1',
                                                    'text'=>'Any',
                                                ),
                                                array(
                                                    'value'=>'30',
                                                    'text'=>'Month',
                                                ),
                                                array(
                                                    'value'=>'7',
                                                    'text'=>'Week',
                                                ),
                                                array(
                                                    'value'=>'365',
                                                    'text'=>'Year',
                                                ),
                                                array(
                                                    'value'=>'1',
                                                    'text'=>'Day',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bedrooms',
                                            'column_name'=>'bedrooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'0', // Minimum available value
                                                    'max'=>'10', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bathrooms',
                                            'column_name'=>'bathrooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'0', // Minimum available value
                                                    'max'=>'10', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'Buildup Area',
                                            'column_name'=>'living_area',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'Sqft', // Use empty string if don't need unit
                                            'min_value'=>0,
                                            'max_value'=>10000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000,
                                        ),
                                        array(
                                            'section_type'=>'edit_text',
                                            'title'=>'Keywords',
                                            'column_name'=>'sf_textsearchmeta_keywords',
                                            'placeholder'=>'',
                                            'default_text'=>'',
                                            'add_on_more_options'=>true,
                                        ),
                                        array(
                                            'section_type'=>'checkbox_group',
                                            'title'=>'',
                                            'groups'=>array(
                                                array(
                                                    'title'=>'Appliances',
                                                    'items'=>array(
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_155',
                                                            'value'=>'Microwave',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_154',
                                                            'value'=>'Stove',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_153',
                                                            'value'=>'Refrigerator',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_156',
                                                            'value'=>'Washing Machine',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_163',
                                                            'value'=>'Dish washer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_157',
                                                            'value'=>'TV',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_159',
                                                            'value'=>'Internet',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_165',
                                                            'value'=>'Satellite',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_160',
                                                            'value'=>'Hair dryer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_164',
                                                            'value'=>'Dishes',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_168',
                                                            'value'=>'Hot Tub',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_169',
                                                            'value'=>'Iron',
                                                        ),

                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Neighborhood',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectf_100',
                                                            'value'=>'Shopping center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_113',
                                                            'value'=>'Town center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_101',
                                                            'value'=>'Hospital',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_112',
                                                            'value'=>'Police station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_109',
                                                            'value'=>'Train station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_108',
                                                            'value'=>'Bus station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_107',
                                                            'value'=>'Airport',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_106',
                                                            'value'=>'Coffee shop',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_105',
                                                            'value'=>'Beach',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_102',
                                                            'value'=>'Cinema',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_103',
                                                            'value'=>'Park',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_110',
                                                            'value'=>'School',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_111',
                                                            'value'=>'University',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_115',
                                                            'value'=>'Tourist site',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_114',
                                                            'value'=>'Exhibition',
                                                        ),
                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Property Tags',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectsp_featured',
                                                            'value'=>'Featured',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_hot',
                                                            'value'=>'Hot offer',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_openhouse',
                                                            'value'=>'Open house',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_forclosure',
                                                            'value'=>'Forclosure',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_call',
                                                            'value'=>'Call',
                                                        ),
                                                    ),
                                                ),
                                            ),
                                            'add_on_more_options'=>true,
                                        ),
                                    ),
                                ),
                                array(
                                    'title'=>'Commercial',
                                    'id'=>'2',
                                    'icon'=>'ic_commercial_property_show_inactive',
                                    'icon_active'=>'ic_commercial_property_show_active',
                                    'content'=>array(
                                        array(
                                            'section_type'=>'spinner',
                                            'column_name'=>'sf_search_type',
                                            'title'=>'Property Type',
                                            'items'=>array(
                                                array(
                                                    'value'=>'-1',
                                                    'text'=>'Any',
                                                ),
                                                array(
                                                    'value'=>'13',
                                                    'text'=>'Office',
                                                ),
                                                array(
                                                    'value'=>'15',
                                                    'text'=>'Commercial',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'Price Range',
                                            'column_name'=>'price',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'$', // Use empty string if don't need to unit
                                            'unit_position'=>'left',
                                            'min_value'=>0,
                                            'max_value'=>10000000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000000,
                                        ),
                                        array(
                                            'section_type'=>'spinner',
                                            'column_name'=>'sf_select_price_period',
                                            'title'=>'Price Type',
                                            'items'=>array(
                                                array(
                                                    'value'=>'-1',
                                                    'text'=>'Any',
                                                ),
                                                array(
                                                    'value'=>'30',
                                                    'text'=>'Month',
                                                ),
                                                array(
                                                    'value'=>'7',
                                                    'text'=>'Week',
                                                ),
                                                array(
                                                    'value'=>'365',
                                                    'text'=>'Year',
                                                ),
                                                array(
                                                    'value'=>'1',
                                                    'text'=>'Day',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bedrooms',
                                            'column_name'=>'bedrooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'-1', // Minimum available value
                                                    'max'=>'-1', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bathrooms',
                                            'column_name'=>'bedroooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'0', // Minimum available value
                                                    'max'=>'10', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'Buildup Area',
                                            'column_name'=>'living_area',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'Sqft', // Use empty string if don't need unit
                                            'unit_position'=>'left',
                                            'min_value'=>0,
                                            'max_value'=>10000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000,
                                        ),
                                        array(
                                            'section_type'=>'edit_text',
                                            'title'=>'Keywords',
                                            'column_name'=>'sf_textsearchmeta_keywords',
                                            'placeholder'=>'',
                                            'default_text'=>'',
                                            'add_on_more_options'=>true,
                                        ),
                                        array(
                                            'section_type'=>'checkbox_group',
                                            'title'=>'',
                                            'groups'=>array(
                                                array(
                                                    'title'=>'Appliances',
                                                    'items'=>array(
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_155',
                                                            'value'=>'Microwave',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_154',
                                                            'value'=>'Stove',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_153',
                                                            'value'=>'Refrigerator',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_156',
                                                            'value'=>'Washing Machine',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_163',
                                                            'value'=>'Dish washer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_157',
                                                            'value'=>'TV',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_159',
                                                            'value'=>'Internet',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_165',
                                                            'value'=>'Satellite',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_160',
                                                            'value'=>'Hair dryer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_164',
                                                            'value'=>'Dishes',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_168',
                                                            'value'=>'Hot Tub',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_169',
                                                            'value'=>'Iron',
                                                        ),

                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Neighborhood',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectf_100',
                                                            'value'=>'Shopping center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_113',
                                                            'value'=>'Town center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_101',
                                                            'value'=>'Hospital',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_112',
                                                            'value'=>'Police station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_109',
                                                            'value'=>'Train station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_108',
                                                            'value'=>'Bus station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_107',
                                                            'value'=>'Airport',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_106',
                                                            'value'=>'Coffee shop',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_105',
                                                            'value'=>'Beach',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_102',
                                                            'value'=>'Cinema',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_103',
                                                            'value'=>'Park',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_110',
                                                            'value'=>'School',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_111',
                                                            'value'=>'University',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_115',
                                                            'value'=>'Tourist site',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_114',
                                                            'value'=>'Exhibition',
                                                        ),
                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Property Tags',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectsp_featured',
                                                            'value'=>'Featured',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_hot',
                                                            'value'=>'Hot offer',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_openhouse',
                                                            'value'=>'Open house',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_forclosure',
                                                            'value'=>'Forclosure',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_call',
                                                            'value'=>'Call',
                                                        ),
                                                    ),
                                                ),
                                            ),
                                            'add_on_more_options'=>true,
                                        ),
                                    ),
                                ),
                            )
                        )
                    ),
                    array(
                        'title'=>'Vacation rental',
                        'tab_id'=>'4',
                        'inner_tabs'=>array(
                            'title'=>'Rooom Type',
                            'tabs'=>array(
                                array(
                                    'title'=>'Entire Place',
                                    'id'=>'-1',
                                    'icon'=>'ic_entireplace_inactive',
                                    'icon_active'=>'ic_entireplace_active',
                                    'content'=>array(
                                        array(
                                            'section_type'=>'date_picker',
                                            'column_name'=>'sf_selectadd_date',
                                            'title'=>'Add date',
                                        ),
                                        array(
                                            'section_type'=>'spinner',
                                            'column_name'=>'sf_search_type',
                                            'title'=>'Property Type',
                                            'items'=>array(
                                                array(
                                                    'value'=>'-1',
                                                    'text'=>'Any',
                                                ),
                                                array(
                                                    'value'=>'6',
                                                    'text'=>'Apartment',
                                                ),
                                                array(
                                                    'value'=>'7',
                                                    'text'=>'Residential',
                                                ),
                                                array(
                                                    'value'=>'13',
                                                    'text'=>'Office',
                                                ),
                                                array(
                                                    'value'=>'14',
                                                    'text'=>'Condo',
                                                ),
                                                array(
                                                    'value'=>'15',
                                                    'text'=>'Commercial',
                                                ),
                                                array(
                                                    'value'=>'16',
                                                    'text'=>'Land',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'',
                                            'column_name'=>'price',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'$', // Use empty string if don't need unit
                                            'unit_position'=>'left',
                                            'min_value'=>0,
                                            'max_value'=>10000000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000000,
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bathrooms',
                                            'column_name'=>'bathrooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'0', // Minimum available value
                                                    'max'=>'10', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bathrooms',
                                            'column_name'=>'bathrooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'0', // Minimum available value
                                                    'max'=>'10', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),

                                        array(
                                            'section_type'=>'checkbox_group',
                                            'title'=>'',
                                            'groups'=>array(
                                                array(
                                                    'title'=>'Appliances',
                                                    'items'=>array(
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_155',
                                                            'value'=>'Microwave',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_154',
                                                            'value'=>'Stove',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_153',
                                                            'value'=>'Refrigerator',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_156',
                                                            'value'=>'Washing Machine',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_163',
                                                            'value'=>'Dish washer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_157',
                                                            'value'=>'TV',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_159',
                                                            'value'=>'Internet',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_165',
                                                            'value'=>'Satellite',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_160',
                                                            'value'=>'Hair dryer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_164',
                                                            'value'=>'Dishes',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_168',
                                                            'value'=>'Hot Tub',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_169',
                                                            'value'=>'Iron',
                                                        ),

                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Neighborhood',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectf_100',
                                                            'value'=>'Shopping center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_113',
                                                            'value'=>'Town center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_101',
                                                            'value'=>'Hospital',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_112',
                                                            'value'=>'Police station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_109',
                                                            'value'=>'Train station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_108',
                                                            'value'=>'Bus station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_107',
                                                            'value'=>'Airport',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_106',
                                                            'value'=>'Coffee shop',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_105',
                                                            'value'=>'Beach',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_102',
                                                            'value'=>'Cinema',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_103',
                                                            'value'=>'Park',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_110',
                                                            'value'=>'School',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_111',
                                                            'value'=>'University',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_115',
                                                            'value'=>'Tourist site',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_114',
                                                            'value'=>'Exhibition',
                                                        ),
                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Property Tags',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectsp_featured',
                                                            'value'=>'Featured',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_hot',
                                                            'value'=>'Hot offer',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_openhouse',
                                                            'value'=>'Open house',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_forclosure',
                                                            'value'=>'Forclosure',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_call',
                                                            'value'=>'Call',
                                                        ),
                                                    ),
                                                ),
                                            ),
                                            'add_on_more_options'=>true,
                                        ),
                                    ),
                                ),
                                array(
                                    'title'=>'Private Room',
                                    'id'=>'-1',
                                    'icon'=>'ic_privateroom_inactive',
                                    'icon_active'=>'ic_privateroom_active',
                                    'content'=>array(
                                        array(
                                            'section_type'=>'date_picker',
                                            'column_name'=>'sf_selectadd_date',
                                            'title'=>'Add date',
                                        ),
                                        array(
                                            'section_type'=>'spinner',
                                            'column_name'=>'sf_search_type',
                                            'title'=>'Property Type',
                                            'items'=>array(
                                                array(
                                                    'value'=>'-1',
                                                    'text'=>'Any',
                                                ),
                                                array(
                                                    'value'=>'6',
                                                    'text'=>'Apartment',
                                                ),
                                                array(
                                                    'value'=>'7',
                                                    'text'=>'Residential',
                                                ),
                                                array(
                                                    'value'=>'13',
                                                    'text'=>'Office',
                                                ),
                                                array(
                                                    'value'=>'14',
                                                    'text'=>'Condo',
                                                ),
                                                array(
                                                    'value'=>'15',
                                                    'text'=>'Commercial',
                                                ),
                                                array(
                                                    'value'=>'16',
                                                    'text'=>'Land',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'',
                                            'column_name'=>'price',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'$', // Use empty string if don't need unit
                                            'unit_position'=>'left',
                                            'min_value'=>0,
                                            'max_value'=>10000000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000000,
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bathrooms',
                                            'column_name'=>'bathrooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'0', // Minimum available value
                                                    'max'=>'10', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bathrooms',
                                            'column_name'=>'bathrooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'0', // Minimum available value
                                                    'max'=>'10', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),

                                        array(
                                            'section_type'=>'checkbox_group',
                                            'title'=>'',
                                            'groups'=>array(
                                                array(
                                                    'title'=>'Appliances',
                                                    'items'=>array(
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_155',
                                                            'value'=>'Microwave',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_154',
                                                            'value'=>'Stove',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_153',
                                                            'value'=>'Refrigerator',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_156',
                                                            'value'=>'Washing Machine',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_163',
                                                            'value'=>'Dish washer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_157',
                                                            'value'=>'TV',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_159',
                                                            'value'=>'Internet',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_165',
                                                            'value'=>'Satellite',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_160',
                                                            'value'=>'Hair dryer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_164',
                                                            'value'=>'Dishes',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_168',
                                                            'value'=>'Hot Tub',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_169',
                                                            'value'=>'Iron',
                                                        ),

                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Neighborhood',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectf_100',
                                                            'value'=>'Shopping center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_113',
                                                            'value'=>'Town center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_101',
                                                            'value'=>'Hospital',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_112',
                                                            'value'=>'Police station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_109',
                                                            'value'=>'Train station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_108',
                                                            'value'=>'Bus station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_107',
                                                            'value'=>'Airport',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_106',
                                                            'value'=>'Coffee shop',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_105',
                                                            'value'=>'Beach',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_102',
                                                            'value'=>'Cinema',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_103',
                                                            'value'=>'Park',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_110',
                                                            'value'=>'School',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_111',
                                                            'value'=>'University',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_115',
                                                            'value'=>'Tourist site',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_114',
                                                            'value'=>'Exhibition',
                                                        ),
                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Property Tags',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectsp_featured',
                                                            'value'=>'Featured',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_hot',
                                                            'value'=>'Hot offer',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_openhouse',
                                                            'value'=>'Open house',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_forclosure',
                                                            'value'=>'Forclosure',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_call',
                                                            'value'=>'Call',
                                                        ),
                                                    ),
                                                ),
                                            ),
                                            'add_on_more_options'=>true,
                                        ),
                                    ),
                                ),
                                array(
                                    'title'=>'Shared Room',
                                    'id'=>'-1',
                                    'icon'=>'ic_sharedroom_inactive',
                                    'icon_active'=>'ic_sharedroom_active',
                                    'content'=>array(
                                        array(
                                            'section_type'=>'date_picker',
                                            'column_name'=>'sf_selectadd_date',
                                            'title'=>'Add date',
                                        ),
                                        array(
                                            'section_type'=>'spinner',
                                            'column_name'=>'sf_search_type',
                                            'title'=>'Property Type',
                                            'items'=>array(
                                                array(
                                                    'value'=>'-1',
                                                    'text'=>'Any',
                                                ),
                                                array(
                                                    'value'=>'6',
                                                    'text'=>'Apartment',
                                                ),
                                                array(
                                                    'value'=>'7',
                                                    'text'=>'Residential',
                                                ),
                                                array(
                                                    'value'=>'13',
                                                    'text'=>'Office',
                                                ),
                                                array(
                                                    'value'=>'14',
                                                    'text'=>'Condo',
                                                ),
                                                array(
                                                    'value'=>'15',
                                                    'text'=>'Commercial',
                                                ),
                                                array(
                                                    'value'=>'16',
                                                    'text'=>'Land',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'seekbar',
                                            'title'=>'',
                                            'column_name'=>'price',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'unit'=>'$', // Use empty string if don't need unit
                                            'unit_position'=>'left',
                                            'min_value'=>0,
                                            'max_value'=>10000000,
                                            'default_min_value'=>0,
                                            'default_max_value'=>10000000,
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bathrooms',
                                            'column_name'=>'bathrooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'0', // Minimum available value
                                                    'max'=>'10', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),
                                        array(
                                            'section_type'=>'range_buttons',
                                            'title'=>'Bathrooms',
                                            'column_name'=>'bathrooms',
                                            'min_prefix'=>'sf_tmin',
                                            'max_prefix'=>'sf_tmax',
                                            'buttons'=>array(
                                                array(
                                                    'text'=>'Any',
                                                    'min'=>'0', // Minimum available value
                                                    'max'=>'10', // Maximum available value
                                                    'selected'=>true,
                                                ),
                                                array(
                                                    'text'=>'+1',
                                                    'min'=>'1',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+2',
                                                    'min'=>'2',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+3',
                                                    'min'=>'3',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+4',
                                                    'min'=>'4',
                                                    'max'=>'10',
                                                ),
                                                array(
                                                    'text'=>'+5',
                                                    'min'=>'5',
                                                    'max'=>'10',
                                                ),
                                            ),
                                        ),

                                        array(
                                            'section_type'=>'checkbox_group',
                                            'title'=>'',
                                            'groups'=>array(
                                                array(
                                                    'title'=>'Appliances',
                                                    'items'=>array(
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_155',
                                                            'value'=>'Microwave',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_154',
                                                            'value'=>'Stove',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_153',
                                                            'value'=>'Refrigerator',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_156',
                                                            'value'=>'Washing Machine',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_163',
                                                            'value'=>'Dish washer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_157',
                                                            'value'=>'TV',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_159',
                                                            'value'=>'Internet',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_165',
                                                            'value'=>'Satellite',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_160',
                                                            'value'=>'Hair dryer',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_164',
                                                            'value'=>'Dishes',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_168',
                                                            'value'=>'Hot Tub',
                                                        ),
                                                        array(
                                                            // 'id'=>'1',
                                                            'column_name'=>'sf_selectf_169',
                                                            'value'=>'Iron',
                                                        ),
                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Neighborhood',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectf_100',
                                                            'value'=>'Shopping center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_113',
                                                            'value'=>'Town center',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_101',
                                                            'value'=>'Hospital',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_112',
                                                            'value'=>'Police station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_109',
                                                            'value'=>'Train station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_108',
                                                            'value'=>'Bus station',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_107',
                                                            'value'=>'Airport',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_106',
                                                            'value'=>'Coffee shop',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_105',
                                                            'value'=>'Beach',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_102',
                                                            'value'=>'Cinema',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_103',
                                                            'value'=>'Park',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_110',
                                                            'value'=>'School',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_111',
                                                            'value'=>'University',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_115',
                                                            'value'=>'Tourist site',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectf_114',
                                                            'value'=>'Exhibition',
                                                        ),
                                                    ),
                                                ),
                                                array(
                                                    'title'=>'Property Tags',
                                                    'items'=>array(
                                                        array(
                                                            'column_name'=>'sf_selectsp_featured',
                                                            'value'=>'Featured',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_hot',
                                                            'value'=>'Hot offer',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_openhouse',
                                                            'value'=>'Open house',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_forclosure',
                                                            'value'=>'Forclosure',
                                                        ),
                                                        array(
                                                            'column_name'=>'sf_selectsp_call',
                                                            'value'=>'Call',
                                                        ),
                                                    ),
                                                ),
                                            ),
                                            'add_on_more_options'=>true,
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                )
            )
        );

        $json_filter = json_encode($filter);
        $json_filter = wpl_addon_mobile_application::create_query_where($json_filter, true);
        
        return json_decode($json_filter);
    }


    /**
     * Getting listing types
     * @return array
     */
    private function get_listing_types()
    {
        $listing_types = wpl_addon_mobile_application::get_listing_types_with_icons();
        $enabled_listing_types = array();
        
        foreach($listing_types as $list)
        {
            if($list['enabled_in_mobile'] == 1)
            {
                $enabled_listing_types[] = array(
                    'id'=> $list['parent_id'],
                    'name'=>$list['name'],
                    'selected'=>$list['selected'],
                    'marker_name'=>$list['addon_mobile_application_marker_name'],
                    'bubble_name'=>$list['addon_mobile_application_bubble_name'],
                    'most_densely_bubble_name'=>$list['addon_mobile_application_most_densely_bubble_name'],
                    'notification_icon_name'=>$list['addon_mobile_application_notification_icon_name'],
                );
            }
        }
        
        return array('listings'=>$enabled_listing_types);
    }

    /**
     * Getting listing sorts option
     * @return array
     */
    private function get_listing_sorts()
    {
        $sorts = wpl_addon_mobile_application::get_listing_sorts();
        return array(
            'listing_sorts'=>$sorts
        );
    }

    /**
     * Getting  status of commands
     * @return array
     */
    private function get_update_status()
    {
        $updates = wpl_addon_mobile_application::get_updates();
        return array(
            'update_status'=>$updates
        );
    }
}