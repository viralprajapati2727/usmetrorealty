<?php

/**
 * @file
 * ExtraWatch - A real-time ajax monitor and live stats  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @package ExtraWatch  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @version 2.3  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @revision 2650  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @copyright (C) 2016 by CodeGravity.com - All rights reserved!  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 * @website http://www.extrawatch.com  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
 */

class modExtraWatchAgentHelper {

static function getAjax()  {

    if (@$_REQUEST["env"] == "ExtraWatchJoomlaEnv") {///
        defined('_JEXEC') or die('Restricted access');
    }

    if (!defined("_JEXEC")) {
        define("_JEXEC", 1);
    }

    if (!defined('DS')) {
        define('DS', DIRECTORY_SEPARATOR);
    }

    $modulePath = realpath(dirname(__FILE__).DS.'..'.DS.'..');

    if (!defined("JPATH_BASE2")) {
        define("JPATH_BASE2", $modulePath);
    }

    if (@EXTRAWATCH_PROFILING_ENABLED) $t1 = round(microtime(true) * 1000);

    require_once($modulePath.DS.'administrator'.DS.'components'.DS.'com_extrawatch'.DS.'admin.extrawatch.php');

    if (@_EW_CLOUD_MODE) {
        $queryParams = ExtraWatchHelper::getUrlQueryParams();
        if (@!$task) {
            $task = @$queryParams['task'];///
        }
        echo @extrawatch_mainController(@$task);
    }

    if (@EXTRAWATCH_PROFILING_ENABLED) $time = round(microtime(true) * 1000) - $t1;
    if (@EXTRAWATCH_PROFILING_ENABLED) ExtraWatchLog::debug("($time ms) controller call ");
    die();

}

}

?>