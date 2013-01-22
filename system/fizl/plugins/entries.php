<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plugin_entries extends Plugin {

	public function entries() {
		$folder = $this->get_param('folder');
		$levels = $this->get_param('levels',1);
		$show = $this->get_param('show','html');
		$hide = $this->get_param('hide','index');
		$orderby = $this->get_param('orderby','');
		$dir = $this->get_param('dir','a');

		$hide = explode(',',$hide);
		$show = explode(',',$show);

		$entries = $this->get_list($folder,$hide,$show);

//print_r($entries);
/*
$entries[] = array(
						'path'       => 'url_1', 
						'title'     => 'First Title',
					);
					
$entries[] = array(
						'path'       => 'url_2', 
						'title'     => 'Second Title',
					);
$entries[] = array(
						'path'       => 'url_3', 
						'title'     => 'Thrid Title',
					);
*/
//print_r($entries);

		// Run our content through the parser
		$parser = new Lex_Parser();
		$parser->scope_glue(':');

		return $parser->parse($this->tag_content, array('records'=>$entries), array($this->CI->parse, 'callback'), true);
	}

	public function get_entry($file) {
		if (file_exists(SITE_FOLDER.$file)) {
			$content = file_get_contents(SITE_FOLDER.$file);
			$CI = get_instance();
			$CI->load->library('Entry');
			$entry = $CI->entry->process($content);
			return $entry['fields'];
		}
		return array();
	}

	public function get_list($folder,$hide,$show) {
	
		$folder = SITE_FOLDER.'/'.trim($folder,'/').'/';
		$folders = glob($folder.'*');
		
		$html = '';
		$entries = $all_entries = array();
		foreach ($folders as $file) {
			$file = str_replace(SITE_FOLDER,'',$file);
			$parts = pathinfo($file);
			
			if (!in_array($parts['filename'],$hide) && substr($parts['basename'],0,1) != '#') {
				$entry = array();
				$entry['dirname'] = $parts['dirname'];
				$entry['basename'] = $parts['basename'];
				$entry['extension'] = $parts['extension'];
				$entry['filename'] = $parts['filename'];
				$entry['path'] = $parts['dirname'].'/'.$parts['filename'];
				$entry['dir'] = (is_dir(SITE_FOLDER.$file)) ? 1 : 0;
				$entry['file'] = (is_file(SITE_FOLDER.$file)) ? 1 : 0;
		
				$entry = array_merge($entry,$this->get_entry($file));
			
				$entries[] = $entry;
			}
		}
		
		$all_entries = array_merge($all_entries,$entries);
	
		$orderby = $this->get_param('orderby','');
	
		if ($orderby) {
			$this->orderby = $orderby;
			$this->dir = $dir;
			usort($all_entries, 'cmp_by_order');
		}
	
		return $all_entries;
	}

} /* end plugin */

function cmp_by_order($a, $b) {
	if ($this->dir == 'a')
		return $a[$this->orderby] - $b[$this->orderby];
	else
		return $b[$this->orderby] + $a[$this->orderby];
}
