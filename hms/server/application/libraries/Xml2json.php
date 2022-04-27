<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Xml2json {
		
/**
 * Create plain PHP associative array from XML.
 *
 * Example usage:
 *   $xmlNode = simplexml_load_file('example.xml');
 *   $arrayData = xmlToArray($xmlNode);
 *   echo json_encode($arrayData);
 *
 * @param SimpleXMLElement $xml The root node
 * @param array $options Associative array of options
 * @return array
 * @link http://outlandishideas.co.uk/blog/2012/08/xml-to-json/ More info
 * @author Tamlyn Rhodes <http://tamlyn.org>
 * @forkedby John OBrien <http://twitter.com/jobrieniii> to include recursive namespace and derive alwaysArray tags/keys
 * @thisgist update allows for full path to be defined to force nodes to array - https://gist.github.com/jobrieniii/92df79811f62b444b39f
 * @license http://creativecommons.org/publicdomain/mark/1.0/ Public Domain
 */
function xmlToArray($xml, $path, $options = array()) {
    $defaults = array(
		  'namespaceRecursive' => true, //set to true to get namespaces recursively, false if only namespaces in root
        'namespaceSeparator' => ':',//you may want this to be something other than a colon
        'removeNamespace' => false, //set to true if you want to remove the namespace from resulting keys
        'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
        'alwaysArray' => array(),   //array of xml tag names which should always become arrays
        'autoArray' => true,        //only create arrays for tags which appear more than once
        'textContent' => 'value',       //key used for the text content of elements
        'autoText' => true,         //skip textContent key if node has no attributes or child nodes
        'keySearch' => false,       //optional search and replace on tag and attribute names
        'keyReplace' => false       //replace values for above search values (as passed to str_replace())
    );
    $options = array_merge($defaults, $options);
    $namespaces = $xml->getDocNamespaces($options['namespaceRecursive']);
    $namespaces[''] = null; //add base (empty) namespace
    //get attributes from all namespaces
    $attributesArray = array();
    foreach ($namespaces as $prefix => $namespace) {
        if ($options['removeNamespace']) $prefix = "";
        foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
            //replace characters in attribute name
            if ($options['keySearch']) $attributeName =
                    str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
            $attributeKey = $options['attributePrefix']
                    . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                    . $attributeName;
            $attributesArray[$attributeKey] = (string)$attribute;
        }
    }
    //get child nodes from all namespaces
    $tagsArray = array();
    foreach ($namespaces as $prefix => $namespace) {
         if ($options['removeNamespace']) $prefix = "";
        foreach ($xml->children($namespace) as $currentChildName=>$childXml) {
            //recurse into child nodes
            //list($childTagName, $childProperties) = each($childArray);
            $childArray = $this->xmlToArray($childXml, $path.".".$currentChildName, $options);
            list($childTagName, $childProperties) = each($childArray);
            //replace characters in tag name
            if ($options['keySearch']) $childTagName =
                    str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
            //add namespace prefix, if any
            if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
            if (!isset($tagsArray[$childTagName])) {
                //only entry with this key
                //test if tags of this type should always be arrays, no matter the element count
                if (in_array($path.".".$currentChildName, $options['alwaysArray']) || !$options['autoArray']) {
                  $tagsArray[$childTagName] = array($childProperties);
                } else {
                  $tagsArray[$childTagName] = $childProperties;
                }
            } elseif (
                is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
                === range(0, count($tagsArray[$childTagName]) - 1)
            ) {
                //key already exists and is integer indexed array
                $tagsArray[$childTagName][] = $childProperties;
            } else {
                //key exists so convert to integer indexed array with previous value in position 0
                $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
            }
        }
    }
    //get text content of node
    $textContentArray = array();
    $plainText = trim((string)$xml);
    if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
    //stick it all together
    $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
            ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
    //return node as array
    return array(
        $xml->getName() => $propertiesArray
    );
}
	
	/**
	* function array2xml Name Space ($array, $xml = false){	
	* Convert an array to XML
 	* @param array $array
 	* 
 	*/
	function array2xml_NS($array, $xml = false){

    if($xml === false){
        $xml = new SimpleXMLElement('<root/>');
    }
	 $nsv = null;
    foreach($array as $key => $value){    
    	  $ns = explode(':',$key);
    	  if(isset($ns[0])){
    	  	$nsv = $ns[0];
    	  }
    	  
        if(is_array($value)){
            $this->array2xml_NS($value, $xml->addChild($key));
        } else {        	 
    				$xml->addChild($key, $value, $nsv);       
        }
    }

    //return $xml->asXML();
     $result = $xml->xpath('/root/*');
	  $str ='';
	  while(list( , $node) = each($result)) {
    		$str .=  $node->asXML();
	  }
	  
    return $str;
	}
	
	/**
	* function array_to_xml($array, $xmldata){	
	* Convert an array to XML
 	* @param array $array
 	* 
 	*/
 	
	/**
	* function array2xml($array, $xml = false){	
	* Convert an array to XML
 	* @param array $array
 	* 
 	*/
	function array2xml($array, $xml = false){

    if($xml === false){
        $xml = new SimpleXMLElement('<root/>');
    }
    foreach($array as $key => $value){    
        if(is_array($value)){
        	$keyT = is_numeric($key) ? 'a':''; // if interger error will come on getTagData fn
        	$keyT = $keyT.$key;
            $this->array2xml($value, $xml->addChild($keyT));
        } else {        	 
    				$xml->addChild($key, $value);       
        }
    }

    //return $xml->asXML();
     $result = $xml->xpath('/root/*');
	  $str ='';
	  while(list( , $node) = each($result)) {
    		$str .=  $node->asXML();
	  }
	  
    return $str;
	}
	
	/**
	* public get Tag Data from $xml_data 	
	* get Tag Data from xml and tag name
 	* @param array $array
 	* 
 	*/

	public function getTagData($xmlstr,$tag){
	 
		$xmlstr = str_replace('<@', '<', $xmlstr);
		$xmlstr = str_replace('</@', '</', $xmlstr);
		
		$dom = new DOMDocument;
		$dom->loadXML($xmlstr);
		$books = $dom->getElementsByTagName($tag);
		$ret = '';
		foreach ($books as $book) {
   	 $ret = $book->nodeValue;
		}
		return $ret;
 
	}
	
	public function getTagData_1($xml,$tag){
 
		$dom = new DOMDocument;
		$dom->loadXML($xml);
		$books = $dom->getElementsByTagName($tag);
		$ret = '';
		foreach ($books as $book) {
   	 $ret = $book->nodeValue;
		}
		return $ret;
	}

	/**
	* public function xml to array($xml_data) 	
	* Convert an array to XML
 	* @param array $array
 	* 
 	*/
	public function xml_to_array($xml_data) 
	{
				
    $result = array();

    if ($xml_data->hasAttributes()) {
        $attrs = $xml_data->attributes;
        foreach ($attrs as $attr) {
            $result['@attributes'][$attr->name] = $attr->value;
        }
    }

    if ($xml_data->hasChildNodes()) {
        $children = $xml_data->childNodes;
        if ($children->length == 1) {
            $child = $children->item(0);
            if ($child->nodeType == XML_TEXT_NODE) {
                $result['_value'] = $child->nodeValue;
                return count($result) == 1
                    ? $result['_value']
                    : $result;
            }
        }
        $groups = array();
        foreach ($children as $child) {
 
        	if($child->nodeType == XML_TEXT_NODE ) {
        		continue;
        	}
            if (!isset($result[$child->nodeName])) {
                $result[$child->nodeName] = $this->xml_to_array($child);
            } else {
                if (!isset($groups[$child->nodeName])) {
                    $result[$child->nodeName] = array($result[$child->nodeName]);
                    $groups[$child->nodeName] = 1;
                }
                $result[$child->nodeName][] = $this->xml_to_array($child);
            }
        }
     }

     return $result;
	}
	
	public function JsonStrToXML($JsonStr){
		$json = preg_replace('/\s+/', '', $JsonStr);
		$varpos=0;
		$start=0;
		$i=0;
		$xmlVars=array();
		$xmlValues=array();

		for(;;){
		  $varpos=strpos($json, '":"',$varpos);
		  if($varpos==0) break;
		  $varposEnd=strpos($json,'"',$varpos+3);
		  $len=strlen($json);
		  $varStart=strrpos($json,'"', -1 *($len-$varpos+1))+1;
		  $xmlVars[$i] = substr($json,$varStart,$varpos-$varStart); 
		  
		  $xmlValues[$i]=substr($json,$varpos+3,$varposEnd-($varpos+3));
		  $replStr='<'.$xmlVars[$i].'>'.$xmlValues[$i].'</'.$xmlVars[$i].'>';
		  $json=str_replace('"'.$xmlVars[$i].'":"'.$xmlValues[$i].'"',$replStr,$json);
		  $varpos=strpos($json, $replStr,$start)+strlen($replStr);
		  $i=$i+1;
		}	
		$varpos=0;
		$xmlKeys= array();
		$keysPos=array();
		$varStart=0;
		$keyStart=0;
		$i=0;
		$innerKey=array();
		for(;;){
			$varpos=strpos($json, '":{',$varpos);
			if($varpos==0) break;
			$len=strlen($json);
			$innerloop=false;
			$keyStartTemp=	$keyStart;
			$keyStart=strpos($json,'{"', $keyStart);	
			if( (string)$keyStart=='' || $keyStart>$varpos) {			
				$keyStart=strpos($json,',"', $keyStartTemp);
				$innerloop=true;	
			}
			$xmlKeys[$i] = substr($json,$keyStart+2,$varpos-$keyStart-2);
			$keysPos[$i]=$varpos;
			$varpos=$varpos+3;
			$keyStart=$keyStart+2;
			$i=$i+1;
		}
		
		$i=$i-1;
		for($j=$i;$j>=0;$j--){
			$replStr='<'.$xmlKeys[$j].'>';
			if(strpos($json,',"'.$xmlKeys[$j].'":'))
			{$json=str_replace(',"'.$xmlKeys[$j].'":',$replStr,$json);
			}elseif(strpos($json,'{"'.$xmlKeys[$j].'":')){
				$json=str_replace('{"'.$xmlKeys[$j].'":',$replStr,$json);
			}elseif(strpos($json,'"'.$xmlKeys[$j].'":')){
				$json=str_replace('"'.$xmlKeys[$j].'":',$replStr,$json);
			}		
			$replPos=strpos($json,'}', $keysPos[$j]);
			$json=substr_replace($json,'</'.$xmlKeys[$j].'>',$replPos,1);
		}
		$json=str_replace('{','',$json);
		$json=str_replace('}','',$json);
		$json=str_replace(',','',$json);
		$newxml=$json;
		return $newxml;
	}

	function str_lreplace($search, $replace, $subject)
	{
	 $pos = strrpos($subject, $search);
    if($pos !== false)
    {$subject = substr_replace($subject, $replace, $pos, strlen($search));}
    return $subject;
	}
	
}
?>
