<?php
/**
 * @package Okey DOC 1.x
 * @copyright Copyright (c)2014 - 2017 Lucas Sanner
 * @license GNU General Public License version 3, or later
 * @contact lucas.sanner@gmail.com
 */

defined('_JEXEC') or die;

// Create a shortcut for params.
$params = $displayData->params;
$canEdit = $displayData->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.framework');
?>

    <?php if($canEdit) : 
	    //First check if the document is checked out by a different user
	    if($displayData->checked_out > 0 && $displayData->checked_out != $displayData->user_id) :
	      $checkoutUser = JFactory::getUser($displayData->checked_out);
	      $button = JHtml::_('image', 'com_okeydoc/checked-out.png', null, null, true);
	      $date = JHtml::_('date', $displayData->checked_out_time);
	      $tooltip = JText::_('JLIB_HTML_CHECKED_OUT').' :: '.
			 JText::sprintf('COM_OKEYDOC_CHECKED_OUT_BY', $checkoutUser->name).' <br /> '.$date;
	    ?>
	      <div class="checked-out-icon">
		 <span class="hasTooltip" title="<?php echo JHtml::tooltipText($tooltip.'', 0); ?>"><?php echo $button; ?></span>
	      </div>
	   <?php else : 
	      //Build the edit link and display the edit button. 
	      $url = 'index.php?option=com_okeydoc&task=document.edit&d_id='.$displayData->id.'&return='.base64_encode($displayData->uri);
	      ?>
	      <p class="button-edit"><a class="btn btn-primary" href="<?php echo JRoute::_($url); ?>"><span class="icon-edit"></span>
		<?php echo JText::_('COM_OKEYDOC_EDIT'); ?>
	      </a></p>
	  <?php endif; ?>
   <?php endif; ?>
