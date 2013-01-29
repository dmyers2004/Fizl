<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PHP Plugin
 *
 * Allows you to execute PHP code
 *
 * @package		Fizl
 * @author		Adam Fairholm (@adamfairholm)
 * @copyright	Copyright (c) 2011-2012, Parse19
 * @license		http://parse19.com/fizl/docs/license.html
 * @link		http://parse19.com/fizl
 */
class Plugin_form extends Plugin {

	/**
	 * Executes PHP code
	 */
	public function form()
	{

		$this->CI = get_instance();

		$content = $this->tag_content;

		$success = $this->get_param('success','');
		$fail = $this->get_param('fail','');
		$name = $this->get_param('name',str_replace('/',' ',$this->CI->uri->uri_string()));
		$to = $this->get_param('to','');
		
		if (isset($_POST['Form__Name'])) {

			$subject = 'Form submitted from "'.$this->CI->config->item('site_title').'"';
			$body = $subject.chr(10);

			foreach ($_POST as $key => $value) {
				$key = str_replace('__', ' ', $key);
				$body .= $key.': "'.$value.'"'.chr(10);
			}

			if (!empty($to)) {
				mail($to,$subject,$body);
			}
			
			redirect($success);
		}
		
		$pre  = '<form id="'.url_title($this->CI->uri->uri_string(),'-',true).'" method="post" action="/'.$this->CI->uri->uri_string().'" enctype="multipart/form-data">';
		$pre .= '<input type="hidden" name="Form__Name" value="'.$name.'">';
		
		return $pre.$content.'</form>';

	}

}