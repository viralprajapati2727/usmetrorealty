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



<?php
if (!$extraWatch->config->checkLiveSite()) {  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
  ?>
<div style="border: 1px solid red; width:40%; padding: 10px;">  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
  <?php echo sprintf("" . _EW_ADMINBODY_LIVE_SITE . "", "<b>" . ExtraWatchEnvFactory::getEnvironment()->getRootSite() . "</b>", "<b>" . $extraWatch->config->getLiveSiteWithSuffix() . "</b>"); ?>  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
  <br/><br/>
  <a href="<?php echo ExtraWatchEnvFactory::getEnvironment()->renderLink("resetLiveSite","&rand=".$extraWatch->config->getBackendToken());?>"><?php echo sprintf("" . _EW_ADMINBODY_SET_LIVE_SITE . "", ExtraWatchEnvFactory::getEnvironment()->getRootSite()); ?></a>
</div>
<?php
} ?>

<?php echo $extraWatch->block->checkBlocked(ExtraWatchVisit::getRemoteIPAddress()); ?>
	<div <?php echo $extraWatch->helper->getTooltipOnEventHide(); ?> >  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
      
		<div id="visits">
			<table class="table">
              <span style="color: #888"><?php echo (ExtraWatchDate::getActualDateTime()); ?></span>
                <h2><?php echo _EW_VISITS_PANE_LOADING;?></h2>
				<?php
					echo $extraWatchVisitHTML->getVisitorsCached(FALSE);  	 	    	    		  	 	  	 	  		 	 		    	 			 	   		  	 	 		 	 	   	      	  	 		 		 				 			 		  		    	 		 		  
					echo $extraWatchVisitHTML->getVisitorsCached(TRUE); ?>
			</table>
		</div>

		<!--
            <div id="stats" valign='top' align='left'>
                <?php echo _EW_STATS_PANE_LOADING; ?>
            </div>
		-->	
    </div>

  

<script type="text/javascript">
    window.addEventListener('load',function(){
        extrawatch_sendLastIdReq();
    });
</script>


