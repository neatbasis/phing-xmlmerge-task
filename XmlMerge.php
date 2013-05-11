<?php

require_once "phing/Task.php";

class XmlMerge extends Task {

    /**
     * The message passed in the buildfile.
     */
    private $source = null;
    private $dest = null;
    private $text = null;

    /**
     * The setter for the attribute "source"
     */
    public function setSource($str) {
        $this->source = $str;
    }

    /**
     * The setter for the attribute "dest"
     */
    public function setDest($str) {
        $this->dest = $str;
    }
    
     /**
     * The setter for the attribute "text"
     */
    public function setText($str) {
        $this->text = $str;
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
      if(is_null($this->text))
      	print("Merging ". $this->source . " into ".$this->dest);
      else
      	print("Appending to ".$this->dest);
      $this->combine();
    }
    
    private function combine(){
    	$source = new DOMDocument();
    	if(is_null($this->text)){
    		$source->load($this->source);
    	}else{
    		$source->loadXML($this->text);
    	}
	    
	    $dest = new DOMDocument();
	    if(file_exists ($this->dest)){
	    	$dest->load($this->dest);
	    	$checkstyle = $dest->getElementsByTagName('checkstyle')->item(0);
	    }else{
	    	$checkstyle = $dest->createElement("checkstyle");
	    	$dest->appendChild( $checkstyle );
	    }
	
	    // iterate over 'item' elements of document 2
	    $items2 = $source->getElementsByTagName('file');
	    for ($i = 0; $i < $items2->length; $i ++) {
	        $item2 = $items2->item($i);
	
	        // import/copy item from document 2 to document 1
	        $item1 = $dest->importNode($item2, true);
	
	        // append imported item to document 1 'res' element
	        $checkstyle->appendChild($item1);
	
	    }
	    $dest->save($this->dest);
    }
}

?>