<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_io_cmd_get_property extends wpl_io_cmd_base
{
    private $where = array();
    private $order_by = "id";
    private $order = "DESC";
    private $built;

    /**
     * This method is the main method of each commands
     * @return mixed
     */
    public function build()
    {
        if(isset($this->params['sort_item']) && trim($this->params['sort_item']) != '')
        {
            $this->order_by = $this->params['sort_item'];
        }
        
        if(isset($this->params['sort_method']) && trim($this->params['sort_method']) != '')
        {
            $this->order = $this->params['sort_method'];
        }

        $this->where = wpl_addon_mobile_application::create_query_where($this->params);
        $this->where['sf_select_confirmed'] = '1';
        $this->where['sf_select_finalized'] = '1';
        $this->where['sf_select_deleted'] = '0';

        $model = new wpl_property();
        $model->start(0, 1, $this->order_by, $this->order, $this->where);
        $model->query();
        $result = $model->search();
        $result = wpl_property::get_property_raw_data($result[key($result)]->id);
        $user = wpl_users::get_user($result['user_id']);

        $image = $this->get_profile_image($result['user_id']);
        $this->built = array('property_show_sections'=>array(

            array(
                'section_type'=>'long_text',
                'title'=>'Description',
                'content'=>strip_tags($result['field_308']),
                'read_more_is_enabled'=>true,
                'number_of_shown_characters'=>500
            ),
            array(
                'section_type'=>'share_content',
                'content'=>$result['field_313'].'\n'.wpl_property::get_property_link($result),
            ),
            array(
                'section_type'=>'string_list',
                'title'=>'Facts',
                'content'=>array(
                    'Lot Size: '.$result['lot_area'].' Sqft',
                    //'Heating: Central',
                    //'Cooling: ' . $result['f_134'] != "" ?  $result['f_134'] : "-" ,
                    'Price/sqft: $'.$result['price'],
                    'Listing ID: '.$result['id'],
                )
            ),
            /*array(
                'section_type'=>'string_list',
                'title'=>'Furniture & Appliances',
                'content'=>array(
                    'Stove',
                    'Washing Machine',
                    'Satelite',
                    'Telephone',
                    'Internet',
                )
            ),
            array(
                'section_type'=>'string_list',
                'title'=>'Features',
                'content'=>array(
                    'Swimming Pool',
                    'Garden',
                )
            ),
			*/
            array(
                'section_type'=>'map_view',
                'content'=>array(
                    'lat'=>$result['googlemap_lt'],
                    'lng'=>$result['googlemap_ln'],
                    'zoom'=>15,
                    'overly_items'=>array(
                        array(
                            'type'=>'amenties',
                            'icon'=>'ic_maplocator',
                            'title'=>'Amenities',
                            'description'=>'Amenities not added',
                            'full_description'=>'Nearby Banks : Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vero, cupiditate.\n\nRestaurants : Lorem ipsum dolor sit amet, consectetur adipisicing elit. Laudantium, minima.\n\nOther Places : Lorem ipsum dolor sit amet, consectetur adipisicing elit. Officia, aut nam ipsam porro sit voluptatem!'
                        ),
                        array(
                            'type'=>'neighborhood',
                            'icon'=>'ic_maplocator',
                            'title'=>'Neighborhood',
                            'description'=>'Neighborhood not added'
                        ),
                    )
                )
            ),
            array(
                'section_type'=>'agent',
                'title'=>'Get More Information',
                'content'=>array(
                    array(
                        'agent_name'=>$user->data->display_name,
                        'description_1'=>$user->data->wpl_data->company_name != '' ? $user->data->wpl_data->company_name : 'No Company',
                        'description_2'=>'',
                        'image'=>$image,
                        'is_call_button_enabled'=>true,
                        'call_button_text'=>'Call',
                        'call_number'=>$user->data->wpl_data->tel
                    ),
                )
            ),
            array(
                'section_type'=>'form',
                'url'=>array($this->generate_command_url('contact_agent', wpl_request::getVar('dapikey'), wpl_request::getVar('dapisecret'), array('id'=>$result['id'], 'user_id'=>$result['user_id']))),
                'content'=>array(
                    array(
                        'field_type'=>'text',
                        'placeholder'=>'First Name',
                        'column_name'=>'first_name',
                    ),
                    array(
                        'field_type'=>'text',
                        'placeholder'=>'Last Name',
                        'column_name'=>'last_name',
                    ),
                    array(
                        'field_type'=>'number',
                        'placeholder'=>'Phone Number',
                        'column_name'=>'phone',
                    ),
                    array(
                        'field_type'=>'email',
                        'placeholder'=>'Email',
                        'column_name'=>'email',
                    ),
                    array(
                        'field_type'=>'textarea',
                        'placeholder'=>'Message',
                        'column_name'=>'message',
                    ),
                    array(
                        'field_type'=>'checkbox',
                        'placeholder'=>'I want to get pre-approved',
                        'column_name'=>'get_preapproved'
                    ),
                    array(
                        'field_type'=>'button',
                        'placeholder'=>'Submit',
                    ),
                )
            )
        ));

        return $this->built;
    }

    /**
     * Data validation
     * @return boolean
     */
    public function validate()
    {
        return true;
    }

    /**
     * Getting agent profile image
     * @param $user_id
     * @return null|string
     */
    private function get_profile_image($user_id)
    {
        $wpl_user = wpl_users::full_render($user_id, wpl_users::get_pshow_fields(), NULL, array(), true);
        $sex = $wpl_user['data']['sex'] == 0 ? 'male' : 'female';

        $params                   = array();
        $params['image_parentid'] = $user_id;
        $params['image_name']     = isset($wpl_user['profile_picture']['name']) ? $wpl_user['profile_picture']['name'] : '_' . $sex . '.png';
        $picture_path             = isset($wpl_user['profile_picture']['path']) ? $wpl_user['profile_picture']['path'] : '';
        
        if(trim($picture_path) == '')
        {
            $picture_path = WPL_ABSPATH. 'assets' .DS. 'img' .DS. 'membership' .DS. $sex .'.jpg';
        }
        
        return wpl_images::create_profile_images($picture_path, 160, 160, $params);
    }
}