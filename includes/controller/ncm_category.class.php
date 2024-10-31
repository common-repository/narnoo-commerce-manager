<?php

/*
* Return category id.
*/


if( !class_exists ( 'NCM_Category' ) ) {



	class NCM_Category {

    

	    function __construct(){

	    }

	

	    public function ncm_get_category ( $id = '' ) {

	        $category = array();

	        return $category;

	    }

	

	}

	

	global $ncm_category;

	$ncm_category = new NCM_Category();

}

?>