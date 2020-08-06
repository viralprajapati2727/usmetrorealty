<?php

/**
 * @file
 * ExtraWatch - A real-time ajax monitor and live stats  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @package ExtraWatch  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @version 2.3  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @revision 2650  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @copyright (C) 2016 by CodeGravity.com - All rights reserved!  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @website http://www.codegravity.com  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 */

$cloudConfigFile = @realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."..").DIRECTORY_SEPARATOR."config.php";

if (file_exists($cloudConfigFile)) {  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
    define("_JEXEC",1);  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
    require_once($cloudConfigFile);  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
}

defined('_JEXEC') or  die('Restricted access');

if (@EXTRAWATCH_PROFILING_ENABLED) {
	$t1 = round(microtime(true) * 1000);
}

require_once realpath(dirname(__FILE__).DIRECTORY_SEPARATOR."..".DIRECTORY_SEPARATOR."includes.php");

$extraWatch = new ExtraWatchMain();
$extraWatch->helper->setNoindexHttpHeaders();   //setting explicitly for ajax requests
$extraWatchHTML = new ExtraWatchHTML();

$referer = ExtraWatchInput::validate(_EW_INPUT_REFERRER, ExtraWatchHelper::requestGet("ref"));///
$title = ExtraWatchHelper::requestGet("title");///
$uri = ExtraWatchInput::validate(_EW_INPUT_URI, ExtraWatchHelper::requestGet("uri"));///
$queryString = ExtraWatchHelper::requestGet("referringQuery");///
if ($queryString) {
    $uri .= "?".$queryString;
}
$params = ExtraWatchHelper::requestGet("getParams");///

$uri = ExtraWatchInput::validate(_EW_INPUT_URI, ExtraWatchHelper::unescapeSlash($uri));///
$title = ExtraWatchHelper::unescapeSlash($title);///
$referer = ExtraWatchInput::validate(_EW_INPUT_REFERRER, ExtraWatchHelper::unescapeSlash($referer));///
$params = ExtraWatchHelper::unescapeSlash($params);///
$uri = $uri.$params;    //adding params to uri


$env = ExtraWatchEnvFactory::getEnvironment();  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
$modulePath = realpath(dirname(__FILE__).DS."..".DS."..".DS."..".DS);  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  

ExtraWatchLog::debug("img.php - referer: $referer title: $title uri: $uri prams: ".print_r($params, true));


if (@_EW_CLOUD_MODE) {

    $projectId = (int) @$_REQUEST['projectId'];///
    $projectInitialized = $extraWatch->visit->isProjectInitialized($projectId);  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  

    if ($projectInitialized) {  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
        $extraWatch->visit->updateVisitByBrowser($uri, $referer, $title, $params);  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
    } else {
        $extraWatch->setup->initializeDB(TRUE);  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
    }
} else {
    $extraWatch->visit->updateVisitByBrowser($uri, $referer, $title, $params);  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
}

if (@EXTRAWATCH_PROFILING_ENABLED) {
	$time = round(microtime(true) * 1000) - $t1;
	ExtraWatchLog::debug("($time ms) img.php duration ");
}

//die();

