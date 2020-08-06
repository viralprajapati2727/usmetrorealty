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


</div>

<script defer="defer" type="text/javascript">
    window.addEventListener('DOMContentLoaded',function(){
		ewMakeNavDivSameHeight();
    });
</script>


<audio id='ewVisitSound'>
	<source src="<?php echo $extraWatch->config->getLiveSiteWithSuffix();?>components/com_extrawatch/data/mp3/click.mp3" autoplay="false" hidden="true" muted="muted" />
</audio>

</div>

<footer class="panel-footer" id="ew-footer">
    <div class="ew-container">
        <span class="col-md-5">ExtraWatch 2.3.2650 Copyright &copy;2006-<?php echo(date("Y"));?> by CodeGravity.com</span>
        <span class="col-md-7 text-right">
            <a href='http://www.extrawatch.com/support/' target='_blank'><?php echo _EW_MENU_FAQ;?></a> &nbsp;&nbsp;&nbsp;&nbsp;
            <a href='http://www.extrawatch.com/support/' target='_blank'><?php echo _EW_MENU_REPORT_BUG;?></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href='http://www.extrawatch.com/demos/' target='_blank' title='<?php echo _EW_WATCH_INSTALLATION_DEMO;?>'><?php echo _EW_WATCH_INSTALLATION_DEMO;?></a> &nbsp;&nbsp;&nbsp;&nbsp;
            <?php if ($extraWatch->env->getVoteLink()) { ?>
                <a href="<?php echo $extraWatch->env->getReviewLink(); ?>"><?php echo _EW_ADMINHEADER_REVIEW;?></a> &nbsp;&nbsp;&nbsp;&nbsp;
            <?php } ?>
            <?php if ($extraWatch->env->getVoteLink()) { ?>
                <a href="<?php echo $extraWatch->env->getVoteLink(); ?>"><?php echo _EW_HEADER_CAST_YOUR."&nbsp;"._EW_HEADER_VOTE;?></a>
            <?php } ?>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<?php echo $extraWatch->config->renderLink('credits');?>"><?php echo _EW_CREDITS_TITLE;?></a>
    </span>
</footer>