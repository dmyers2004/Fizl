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
class Plugin_php extends Plugin {

	/**
	 * Executes PHP code
	 */
	public function php()
	{

		$this->CI = get_instance();

		// Prep our content
		$content = "<?php";
		$content .= $this->tag_content;
		$content .= "?>";

		// Run our content through the parser
		$parser = new Lex_Parser();
		$parser->scope_glue(':');

		$content = $parser->parse($content, $this->CI->vars, array($this->CI->parse, 'callback'), true);

		return $content;

	}

}