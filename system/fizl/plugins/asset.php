<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Asset Plugin
 *
 * Create asset links.
 *
 * @package		Fizl
 * @author		Adam Fairholm (@adamfairholm)
 * @copyright	Copyright (c) 2011-2012, Parse19
 * @license		http://parse19.com/fizl/docs/license.html
 * @link		http://parse19.com/fizl
 */
class Plugin_asset extends Plugin {

	function __construct()
	{
		parent::__construct();

		$this->CI->load->helper('html');
	}

	// --------------------------------------------------------------------------

	/**
	 * Image link
	 */
	public function img()
	{
		$image_properties['src'] = $this->CI->config->item('base_url').
					$this->CI->config->item('assets_folder').
					'/img/'.$this->get_param('file');

		$properties = array('alt', 'id', 'class', 'width', 'height', 'title', 'rel');

		foreach($properties as $prop):

			if($this->get_param($prop) != ''):

				$image_properties[$prop] = $this->get_param($prop);

			endif;

		endforeach;
		
		/* is width or height set? */
		if ((int)@$image_properties['width'] > 0 || (int)@$image_properties['height'] > 0) {
			$original = FCPATH.$this->CI->config->item('assets_folder').'/img/'.$this->get_param('file');
			$image_properties['src'] = $this->resize($original,(int)@$image_properties['width'],(int)@$image_properties['height']);
		}

		return img($image_properties);
	}

	private function resize($filename,$width,$height) {
		/* is the image even there? */
		if (!file_exists($filename)) {
			return  $this->CI->config->item('base_url').$this->CI->config->item('assets_folder').'/img/img-not-found.gif';
		}

		/* does this image already exist? then just return it */
		$path_info = pathinfo($filename);
		
		$new_name = $path_info['filename'].$width.'x'.$height.'.'.$path_info['extension'];
		$final_name = $this->CI->config->item('base_url').$this->CI->config->item('assets_folder').'/img/'.$new_name;

		if (file_exists($final_name)) return $final_name;
		
		$config['source_image']	= $filename;
		$config['create_thumb'] = TRUE;
		$config['maintain_ratio'] = TRUE;
		if ($height > 0 && $width == 0) {
			$config['master_dim'] = 'height';
			$config['height']	= $height;
			$config['width'] = 1;
		}
		if ($width > 0 && $height == 0) {
			$config['master_dim'] = 'width';
			$config['width'] = $width;
			$config['height'] = 1;
		}
		if ($height > 0 && $width > 0) {
			$config['width'] = $width;
			$config['height']	= $height;
			$config['master_dim'] = 'auto';
		}
		
		$config['thumb_marker'] = $width.'x'.$height;

		$new_image = $path_info['dirname'].'/'.$new_name;

		$this->CI->load->library('image_lib', $config);

		$this->CI->image_lib->resize();
		
		if ( ! $this->CI->image_lib->resize())
		{
			log_message('error', $this->CI->image_lib->display_errors());
		}
		
		return $final_name;
	}

	// --------------------------------------------------------------------------

	/**
	 * CSS link
	 */
	public function css()
	{
		$src = 		$this->CI->config->item('base_url').
					$this->CI->config->item('assets_folder').
					'/css/'.$this->get_param('file');

		// Get HTML5 param or leave as it is
		$html5 = $this->get_param('html5', 'false');

		// Do it the HTML5 way?
		if ($html5 == 'true') {

			return '<link rel="stylesheet" href="'.$src.'">';

		} else {

			return '<link rel="stylesheet" type="text/css" href="'.$src.'" />';

		}
	}

	// --------------------------------------------------------------------------

	/**
	 * JS link
	 */
	public function js()
	{
		$src = 		$this->CI->config->item('base_url').
					$this->CI->config->item('assets_folder').
					'/js/'.$this->get_param('file');

		// Get HTML5 param or leave as it is
		$html5 = $this->get_param('html5', 'false');

		// Do it the HTML5 way?
		if ($html5 == 'true') {

			return '<script src="'.$src.'"></script>';

		} else {

			return '<script type="text/javascript" src="'.$src.'"></script>';

		}
	}

}

/* End of file asset.php */