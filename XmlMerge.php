<?php

require_once "phing/Task.php";

class XmlMerge extends Task
{

    /**
     * The message passed in the buildfile.
     */
    private $_source = null;
    private $_dest = null;
    private $_text = null;

    /**
     * The setter for the attribute "source"
     */
    public function setSource($str) {
        $this->_source = $str;
    }

    /**
     * The setter for the attribute "dest"
     */
    public function setDest($str) {
        $this->_dest = $str;
    }
    
     /**
     * The setter for the attribute "text"
     */
    public function setText($str) {
        $this->_text = $str;
    }
    
    /**
     * The init method: Do init steps.
     */
    public function init() {
      // nothing to do here
    }

    /**
     * The main entry point method.
     */
    public function main() {
      if(is_null($this->_text))
      	$this->log("Merging ". $this->_source . " into ".$this->_dest);
      else
      	$this->log("Appending to ".$this->_dest);
      $this->combine();
    }
    
    private function combine(){
    	$source = new DOMDocument();
    	if (is_null($this->_text)) {
    		$source->load($this->_source);
    	} else {
    		$source->loadXML($this->_text);
    	}
	    
	    $dest = new DOMDocument();
	    if (file_exists($this->_dest)) {
	    	$dest->load($this->_dest);
	    	$checkstyle = $dest->getElementsByTagName('checkstyle')->item(0);
	    } else {
	    	$checkstyle = $dest->createElement("checkstyle");
	    	$dest->appendChild($checkstyle);
	    }
	
	    // iterate over 'item' elements of document 2
	    $files = $source->getElementsByTagName('file');
	    for ($i = 0; $i < $files->length; $i++) {
	        $item = $files->item($i);
	
	        // import/copy item from document 2 to document 1
	        $copy = $dest->importNode($item, true);
	
	        // append imported item to document 1 'res' element
	        $checkstyle->appendChild($copy);
	
	    }
	    $dest->save($this->_dest);
    }
}
