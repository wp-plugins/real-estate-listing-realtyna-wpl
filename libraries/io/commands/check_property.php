<?php
// no direct access
defined('_WPLEXEC') or die('Restricted access');


class wpl_io_cmd_check_property extends wpl_io_cmd_base
{
	private $pid;

    private $is_exists; // property existance?
    private $status;
	private $model;

	
	public function build()
	{


        /** validations */
        if(trim($this->params['pid']) != "")
        {
            $this->pid = $this->params['pid'];
        }
        else
        {
            $this->error = "Property id is empty";
        }


		$this->model = new wpl_property;
		$property = (array) $this->model->get_property_raw_data($this->pid);
		
		$this->status    = ($property) ? 1 : 0;
		$this->is_exists = ($property) ? 'Exists' : 'Not Exists';
		
		$built = array('response'=>array('status'=>$this->status, 'message'=>$this->is_exists));
		return $built;
	}

    /**
     * Data validation
     * @return boolean
     */
    public function validate()
    {
        // TODO: Implement validate() method.
    }
}