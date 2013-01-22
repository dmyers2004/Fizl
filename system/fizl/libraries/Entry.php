<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class entry {
	
	function process($content) {
		if ($this->instr($content,'<entry>') == 0) {
			return array('content'=>$content,'fields'=>array());
		}
	
		$fields = array();
		$remove = $entry = $this->between('<entry>','</entry>',$content);
			
		while (strlen($entry) > 1) {
			$tag = $this->getTag($entry);
			$starttag = '<'.$tag.'>';
			$endtag = '</'.$tag.'>';
			$fields[$tag] = $this->between($starttag,$endtag,$entry);
			$entry = $this->removeUpTo($endtag,$entry);
		}
	
		$content = str_replace('<entry>'.$remove.'</entry>','',$content);
		
		return array('content'=>$content,'fields'=>$fields);
	}
	
	function removeUpTo($tag,$content) {
		$txt = $this->before($tag,$content);
		return str_replace($txt.$tag,'',$content);
	}
	
	function getTag($entry) {
		$start = $this->instr($entry,'<');
		$end = $this->instr($entry,'>')-1;
		$tag = substr($entry,$start,($end-$start));
		return $tag;
	}
	
	function instr($source,$find,$startat=0) {
		 $x = strpos($source,$find,$startat);
		 if ($x === false) $x = 0;
		 else $x++;
		 return $x;
	}
	
	function after($tag,$searchthis) {
		if (!is_bool(strpos($searchthis,$tag)))
		return substr($searchthis,strpos($searchthis,$tag)+strlen($tag));
	}
	
	function before($tag,$searchthis) {
		return substr($searchthis,0,strpos($searchthis, $tag));
	}
	
	function between($tag,$that,$searchthis) {
		return $this->before($that,$this->after($tag,$searchthis));
	}

} /* end entry */