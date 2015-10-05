<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');

class wpl_io_cmd_gallery extends wpl_io_cmd_base
{
    private $built;

    /**
     * This method is the main method of each commands
     * @return mixed
     */
    public function build()
    {
        $property = wpl_property::get_property_raw_data($this->params['id']);
        $unit = wpl_units::get_unit($property['price_unit']);
        $unit = $unit['name'];

        $location = $property['location_text'];
        if(strlen($property['location_text']) > 25)
        {
            $location = substr($property['location_text'], 0, 25);
            $location .= '...';
        }

        return $this->built = array(
            'property_show_gallery_section'=>array(
                array(
                    'section_type'=>'header',
                    'text'=>$unit . $property['price']
                ),
                array(
                    'section_type'=>'inline_section',
                    'items'=>array(
                        array(
                            'item_type'=>'text',
                            'position'=>'left',
                            'text'=>$location
                        ),
                        array(
                            'item_type'=>'image',
                            'position'=>'left',
                            'url'=>'ic_bedroom'
                        ),
                        array(
                            'item_type'=>'text',
                            'position'=>'left',
                            'text'=>$property['bedrooms']
                        ),
                        array(
                            'item_type'=>'image',
                            'position'=>'left',
                            'url'=>'ic_bathroom'
                        ),
                        array(
                            'item_type'=>'text',
                            'position'=>'left',
                            'text'=>$property['bathrooms']
                        ),
                    )
                )
            ),
        );
    }

    /**
     * Data validation
     * @return boolean
     */
    public function validate()
    {
        if(trim($this->params['id']) == '' || is_numeric($this->params['id']) == false)
        {
            return false;
        }
        
        return true;
    }
}
	