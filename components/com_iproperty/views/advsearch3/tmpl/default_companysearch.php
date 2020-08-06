<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

// require the IP fields
require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/companysortby.php';
require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/orderby.php';

// build filter lists from fields
$tmpsort    = new JFormFieldCompanySortBy();
$tmporder   = new JFormFieldOrderBy();
?>

<div id="ip-companysearchfilter-wrapper" class="well hidden-phone">
    <form action="<?php echo htmlspecialchars(JFactory::getURI()->toString()); ?>" method="post" name="ip_company_search" class="ip-companysearch-form form-inline">
        <div class="ip-quicksearch-optholder">
            <input type="text" class="inputbox ip-companysearch-keyword" placeholder="<?php echo JText::_('COM_IPROPERTY_SEARCH'); ?>" name="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" />
        </div>
        <div class="ip-quicksearch-sortholder pull-right">
            <select name="filter_order" class="input-medium">
                <option value=""><?php echo JText::_('COM_IPROPERTY_SORTBY'); ?></option>
                <?php echo JHTML::_('select.options', $tmpsort->getOptions(), 'value', 'text', $this->state->get('list.ordering')); ?>
            </select> 
            <select name="filter_order_Dir" class="input-medium">
                <option value=""><?php echo JText::_('COM_IPROPERTY_ORDERBY'); ?></option>
                <?php echo JHTML::_('select.options', $tmporder->getOptions(), 'value', 'text', $this->state->get('list.direction')); ?>
            </select> 
            <div class="btn-group">
                <button class="btn" onclick="clearForm(this.form);" type="button"><?php echo JText::_('COM_IPROPERTY_RESET'); ?></button>
                <button class="btn btn-primary" name="commit" type="submit"><?php echo JText::_('JSUBMIT'); ?></button>
            </div>
        </div>
    </form>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>