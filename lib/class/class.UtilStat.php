<?php
class Stat {
	var $arrMaster;
	var $objDBH;
	var $arrQuery = array();

	function __construct($obj) {
		$this->objDBH = $obj;
	}

}
?>