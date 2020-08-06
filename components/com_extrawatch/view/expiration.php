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

defined('_JEXEC') or die('Restricted access');

function renderSubscriptionForm($extraWatch) {
	if (@$extraWatch->visit->getAveragePageLoadPerDay() > 500) {
		renderSubscriptionFormHigherDataUsage();
	} else {
		renderSubscriptionFormStandardDataUsage();
	}
}


//TODO make it as $output.= ... and move it to view
function renderSubscriptionFormHigherDataUsage() { ?>
	<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
	<input type="hidden" name="cmd" value="_s-xclick">
	<input type="hidden" name="custom" value="<?php echo _EW_PROJECT_ID;?>">
	<input type="hidden" name="hosted_button_id" value="JCCMLGTGK7G4L">
    <input type="submit" value="Subscribe now for a special *intro* price - only 9,99 EUR/month" class="cta-btn-red" style="height:50px;"/>
	</form>
    <br/>
<?php
}

//TODO make it as $output.= ...
function renderSubscriptionFormStandardDataUsage() { ?>
    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
        <input type="hidden" name="cmd" value="_s-xclick">
        <input type="hidden" name="custom" value="<?php echo _EW_PROJECT_ID;?>">
        <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----">
        <input type="submit" value="Subscribe now for a special *intro* price - only 1,99 EUR/month" class="cta-btn-red" style="height:50px;"/>
    </form>
    <br/>
<?php }


echo $extraWatch->env->addStyleSheet($extraWatch->config->getLiveSiteWithSuffix()."components".DS."com_extrawatch" . DS. "src".DS."env".DS."nocms".DS."admin".DS."view".DS."css".DS."register-style.css");

$extraWatchProject = new ExtraWatchProject($extraWatch->database);

$alwaysActive = $extraWatchProject->isProjectAlwaysActive(_EW_PROJECT_ID);
$paymentActive = $extraWatchProject->isPaymentActive(_EW_PROJECT_ID);
$daysToTrialEnd = $extraWatchProject->getDaysToTrialEnd(_EW_PROJECT_ID);


if (!$paymentActive && !$alwaysActive && $daysToTrialEnd <= 0) { ?>

    This trial account has <b>expired</b>... To continue taking advantage of all great features like:
    <br/><br/>
    <li> Your user's clicks, tracking to increase sales </li>
    <li> Live visitor activity on your website, and much more! </li>
    <br/><br/>

    <?php renderSubscriptionForm($extraWatch);?>

    <table>
        <tr>
            <td valign="top" style="padding:20px;padding-left:0px;">
                <img src="<?php echo $extraWatch->config->getLiveSiteWithSuffix();?>components/com_extrawatch/src/env/nocms/admin/img/trial-visitors.png"/>
            </td>
            <td valign="top" style="padding:20px;">
                <img src="<?php echo $extraWatch->config->getLiveSiteWithSuffix();?>components/com_extrawatch/src/env/nocms/admin/img/trial-heatmap.png"/>
            </td>

        </tr>
    </table>


<?php
    die();

}

$daysToExpireStyle = "";
if ($daysToTrialEnd < 4) {
    $daysToExpireStyle = "color: #e40410; font-size: 20px; font-weight: bold;";
}

$numberOfVisitors = @sizeof($extraWatch->visit->getVisitors());
echo ("<!-- size of visitors: $numberOfVisitors -->");
if (!$paymentActive && !$alwaysActive && $numberOfVisitors > 0) { ?>
    This trial account will expire in <span style='<?php echo $daysToExpireStyle;?>'><?php echo($daysToTrialEnd);?> days</span> . To take advantage of all great features: <br/> <br/>
    <?php renderSubscriptionForm($extraWatch); ?>
<?php
}



