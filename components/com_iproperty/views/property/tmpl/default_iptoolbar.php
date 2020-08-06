<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('bootstrap.modal');

$show_calc = ($this->p->price && $this->p->price != '0.00' && $this->settings->show_mtgcalc && !$this->p->call_for_price) ? true : false;

$user = JFactory::getUser();
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('#__iproperty_agents'));
$query->where($db->quoteName('user_id')." = ".$user->id);
$db->setQuery($query);
$results = $db->loadObject();
$type=$results->agent_type ;

?>
<ul class="nav nav-tabs ip-actions hidden-tablet hidden-phone pull-right">
    <?php if ($this->ipauth->canEditProp($this->p->id)): ?>           
        <li> <?php echo JHtml::_('icon.edit', $this->p, 'property', true,  array('class'=>'btn hasTooltip')); ?></li>
    <?php endif; ?>
    <?php if ($this->settings->show_print): ?> 
        <li> <?php echo JHtml::_('icon.print_popup', $this->p, array('class'=>'btn hasTooltip'), 820, 480); ?></li>
    <?php endif; ?>
    <?php if ($show_calc): ?>
        <li> <?php echo JHtml::_('icon.generic_button', '#calcModal', 'icon-search', array('title'=>JText::_('COM_IPROPERTY_MTG_CALCULATOR'), 'role'=>'button', 'data-toggle'=>'modal', 'class'=>'btn hasTooltip btn-fade')); ?></li>
    <?php endif; ?>
    <?php if ($this->settings->show_saveproperty): ?>
        <?php if($type == 2 || $type == 3){ ?>
            <li> <?php echo JHtml::_('icon.generic_button', '#saveModal', 'icon-save', array('title'=>JText::_('COM_IPROPERTY_SAVE'), 'role'=>'button', 'data-toggle'=>'modal', 'class'=>'btn hasTooltip btn-fade')); ?></li>
            <li> <?php echo JHtml::_('icon.generic_button', JRoute::_('index.php?option=com_iproperty&view=ipuser&Itemid=319&tab=searchcriteria'), 'icon-list', array('title'=>JText::_('COM_IPROPERTY_MY_FAVORITES').'::'.JText::_( 'COM_IPROPERTY_MY_FAVORITES_TIP'), 'class'=>'btn hasTooltip')); ?></li>
        <?php } ?>
    <?php endif; ?>
</ul>
<?php if ($show_calc) echo $this->loadTemplate('calculator'); ?>
<?php if ($this->settings->show_saveproperty) echo $this->loadTemplate('usersave'); ?> 
<div class="clearfix"></div>
