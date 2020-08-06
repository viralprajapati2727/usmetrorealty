<?php 
 /** 
  * @version     1.0 
  * @package     Edocman
  * @copyright   Copyright (C) 2018. All rights reserved.
  * @license     GNU General Public License version 2 or later; see LICENSE.txt 
  * @author      <your_name> http://www.joellipman.com 
  */ 
  
defined('JPATH_BASE') or die; 
jimport('joomla.html.html');
jimport('joomla.filesystem.folder');
jimport('joomla.form.formfield');
jimport('joomla.form.helper');
  
 /** 
  * Supports an HTML select list of options driven by SQL 
  */ 
 class JFormFieldGetToken extends JFormField
 { 
     /** 
      * The form field type. 
      */ 
     public $type = 'gettoken';
  
     /** 
      * Overrides parent's getinput method 
      */ 
     protected function getInput() 
     {
         //require_once JPATH_PLUGINS.'/edocman/googledrive/vendor/autoload.php';
         require_once JPATH_PLUGINS.'/edocman/googledrive/Google.php';

         JLoader::register('DropfilesGoogle', JPATH_PLUGINS.'/edocman/googledrive/Google.php');

         $google = new EdocmanGoogle();

         // Initialize variables.
         /*
         $html[] = '<strong>To integrate with Dropbox API, you should fill below inputboxes.</strong>';
		 $html[] = '<BR /><strong>1.</strong>&nbsp;API Key';
		 $html[] = '<BR /><strong>2.</strong>&nbsp;API Secret';
		 $html[] = '<BR /><strong>3.</strong>&nbsp;Access Token';
		 $html[] = '<BR />To create Dropbox Application, please read <a href=\'https://www.dropbox.com/developers/apps/create\' target=\'_blank\'>https://www.dropbox.com/developers/apps/create</a>';
         */

         ob_start();
         echo "<BR />";
		 $plugin = JPluginHelper::getPlugin('edocman','googledrive');
		 if($plugin){
		     $pluginParams = new JRegistry($plugin->params);
		     $client_id = $pluginParams->get('google_client_id','');
		     $client_secret = $pluginParams->get('google_client_secret','');
		     if($client_id != '' && $client_secret != ''){
                 if (!$google->checkAuth()) {
                     $url = $google->getAuthorisationUrl();
                     ?>
                     <p><?php echo JText::_('EDOCMAN_GOOGLEDRIVE_CONNECT_PART2'); ?></p>
                     <p><a id="ggconnect" class="btn btn-primary btn-google" href="#"
                           onclick="window.open('<?php echo $url; ?>','foo','width=600,height=600');return false;"><img
                                 src="<?php echo JURI::root(); ?>/components/com_edocman/assets/images/drive-icon-white.png"
                                 alt="" width="13"/> <?php echo JText::_('EDOCMAN_GOOGLEDRIVE_CONNECT_PART2_CONNECT'); ?>
                         </a></p>
                 <?php } else { ?>
                     <?php echo JText::_('EDOCMAN_GOOGLEDRIVE_CONNECT_PART3'); ?>
                     <a class="btn btn-primary btn-google"
                        href="index.php?option=com_edocman&task=googledrivelogout">
                         <?php echo JText::_('EDOCMAN_GOOGLEDRIVE_CONNECT_PART3_DISCONNECT'); ?></a>
                 <?php }
             }
         }

		 $body = ob_get_contents();
		 ob_end_clean();
		 $html[] = $body;
         return implode($html); 
     } 
 } 
 ?> 