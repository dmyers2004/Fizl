<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Link Plugin
 *
 * Allows you to generate links
 *
 * @package		Fizl
 * @author		Adam Fairholm (@adamfairholm)
 * @copyright	Copyright (c) 2011-2012, Parse19
 * @license		http://parse19.com/fizl/docs/license.html
 * @link		http://parse19.com/fizl
 */
class Plugin_link extends Plugin {

	/**
	 * Simple anchor link
	 */
	public function link()
	{
		// Get additional link parameters and write to array
		$params = array(
			'id' => $this->get_param('id'),
			'class' => $this->get_param('class'),
			'title' => $this->get_param('title'),
			'rel' => $this->get_param('rel'),
			'uri' => $this->get_param('uri'),
			'content' => $this->get_param('content')
		);

		// Start creating the link
		$link = '<a href="'.site_url($params['uri']).'"';

		// Is their an ID?
		if(!empty($params['id'])) {
			$link .= ' id="'.$params['id'].'"';
		}
		// Is their a class?
		if(!empty($params['class'])) {
			$link .= ' class="'.$params['class'].'"';
		}
		// Is their a title?
		if(!empty($params['title'])) {
			$link .= ' title="'.$params['title'].'"';
		}
		// Is their a rel?
		if(!empty($params['rel'])) {
			$link .= ' rel="'.$params['rel'].'"';
		}

		// Add the title and close the tag
		$link .= '>'.$params['content'];
		$link .= '</a>';
		// Return/output link
		return $link;
	}

}