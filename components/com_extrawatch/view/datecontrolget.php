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
defined('_JEXEC') or die('Restricted access'); ?>  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  

<a name="ewDateControl"></a>
<br/>
<table width='100%'>  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
  <tr>
    <td align='left'><?php echo("<a href='" . $this->extraWatch->config->renderLink($task, "&day=$prev#ewDateControl") . "' id='$prev'>&lt;" . ExtraWatchDate::date("d.m.Y", $prev * 3600 * 24) . "<img src='" . $this->extraWatch->config->getLiveSiteWithSuffix() . "components/com_extrawatch/img/icons/calendar.gif' border='0' align='center' /></a>"); ?></td>
    <td class='ewCentered'><?php if ($day != $today) echo("<a href='" . $this->extraWatch->config->renderLink($task, "&day=$today#ewDateControl") . "' id='$today'>" . _EW_STATS_TODAY . "</a>"); ?></td>
    <td align='right'><?php if ($next <= $today) echo("<a href='" . $this->extraWatch->config->renderLink($task, "&day=$next#ewDateControl") . "' id='$next'><img src='" . $this->extraWatch->config->getLiveSiteWithSuffix() . "components/com_extrawatch/img/icons/calendar.gif' border='0' align='center' />" . ExtraWatchDate::date("d.m.Y", $next * 3600 * 24) . "&gt;</a>"); ?></td>
  </tr>
</table>
