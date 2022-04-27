<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Xmlparse{
				
	public function delete_empty_nodes($xml_soap)
	{
	 
  		$doc = new DOMDocument;
		$doc->preserveWhiteSpace = false;
		$doc->loadxml($xml_soap);
		$xpath = new DOMXPath($doc);

		// not(*) does not have children elements
		// not(@*) does not have attributes
		// text()[normalize-space()] nodes that include whitespace text

		while (($node_list = $xpath->query('//*[not(*) and not(@*) and not(text()[normalize-space()])]')) && $node_list->length) {
    		foreach ($node_list as $node) {
      	  $node->parentNode->removeChild($node);
    		}	
		}

		$doc->formatOutput = true;
		return $doc->savexml();
	}
	
	public function get_xml_part($xml_soap,$part_tag){
		$doc = new DOMDocument;
		$doc->preserveWhiteSpace = false;
		$doc->loadxml($xml_soap);
		$xpath = new DOMXPath($doc);
		//$part_tag = '//body/*';
		$nodeList = $xpath->query($part_tag); 	
		$doc->formatOutput = true;
		$result =  $doc->savexml($nodeList->item(0));
		return $result;
		
	}

}

?>