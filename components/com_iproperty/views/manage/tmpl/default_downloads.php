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
JHTML::_('behavior.keepalive');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.modal');
JHtml::_('dropdown.init');
JFactory::getLanguage()->load('com_iproperty', JPATH_ADMINISTRATOR);
JFactory::getLanguage()->load('', JPATH_ADMINISTRATOR);

$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$ordering   = ($listOrder == 'ordering');

$sortFields = $this->getSortFields('downloads');
?>

<script type="text/javascript">
    Joomla.orderTable = function() {
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>') {
			dirn = 'asc';
		} else {
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>

<div class="12344 ip-manage-list<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading')) : ?>
        <div class="page-header">
            <h1>
                <?php echo $this->escape($this->params->get('page_heading')); ?>
            </h1>
        </div>
    <?php endif; ?>
    <?php if ($this->params->get('show_ip_title') && $this->iptitle) : ?>
        <div class="ip-mainheader">
            <h2>
                <?php echo $this->escape($this->iptitle); ?>
            </h2>
        </div>        
    <?php endif; ?>
    <div class="clearfix"></div>
    
    <?php
    // main agent display
    if ($this->downloads)
    {
        //echo "<pre>"; print_r($this->downloads); exit;
        $this->k = 1;        
        $this->downloads_folder = 'media/com_iproperty/project_files/';
        //$this->agent_photo_width = $this->settings->agent_photo_width ? $this->settings->agent_photo_width : 90;
        // build agent display
        //echo $this->loadTemplate('agent');
    } elseif (!$this->ipauth->getAdmin()){
        // redirect to home page if not admin or agent
        JFactory::getApplication()->redirect(JURI::root);
    }
    // Build toolbar
    echo $this->loadTemplate('toolbar');
    ?>
        
    <form action="<?php echo JRoute::_('index.php?option=com_iproperty&view=manage&layout=downloads&Itemid=381'); ?>" method="post" name="adminForm" id="adminForm">
        <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label class="element-invisible" for="filter_search"><?php echo htmlentities(JText::_('JSEARCH_FILTER_LABEL'), ENT_QUOTES, 'UTF-8'); ?></label>
                <input type="text" name="filter_search" class="inputbox input-medium" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>" />
            </div>
            <div class="btn-group pull-left hidden-phone">
                <button class="btn hasTooltip" type="submit" title="<?php echo htmlentities(JText::_('JSEARCH_FILTER_SUBMIT'), ENT_QUOTES, 'UTF-8'); ?>"><i class="icon-search"></i></button>
                <button class="btn hasTooltip" type="button" onclick="document.id('filter_search').value='';" rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>"><i class="icon-remove"></i></button>
            </div>
            <div class="btn-group pull-right hidden-phone">
                <select name="directionTable" id="directionTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value=""><?php echo JText::_('COM_IPROPERTY_ORDERBY');?></option>
                    <option value="asc" <?php if ($listDirn == 'asc') echo 'selected="selected"'; ?>><?php echo JText::_('COM_IPROPERTY_ASCENDING');?></option>
                    <option value="desc" <?php if ($listDirn == 'desc') echo 'selected="selected"'; ?>><?php echo JText::_('COM_IPROPERTY_DESCENDING');?></option>
                </select>
            </div>
            <div class="btn-group pull-right">
                <select name="sortTable" id="sortTable" class="input-medium" onchange="Joomla.orderTable()">
                    <option value=""><?php echo JText::_('COM_IPROPERTY_SORTBY');?></option>
                    <?php echo JHtml::_('select.options', $sortFields, 'value', 'text', $listOrder);?>
                </select>
            </div>
        </div>
        <div class="clearfix"> </div>
        
        <div id="filter-bar" class="btn-toolbar">
            
        </div>
        <div class="clearfix"> </div>

        <h2><?php  echo JText::_('COM_IPROPERTY_DOWNLOADS'); ?></h2>    
        <table class="ptable table table-striped" id="propertyList">
            <thead>
                <tr>
                    <th width="1%" class="center hidden-phone">
                        <input type="checkbox" name="checkall-toggle" value="" title="<?php  echo htmlentities(JText::_('JGLOBAL_CHECK_ALL'), ENT_QUOTES, 'UTF-8'); ?>" onclick="Joomla.checkAll(this)" />
                    </th>                    
                    <th width="55%" class="nowrap">
                        <?php  echo JHTML::_('grid.sort', JText::_('COM_IPROPERTY_DOWNLOAD_TITLE' ), 'title', $listDirn, $listOrder ); ?>
                    </th>
                    <th width="15%" class="nowrap">
                        <?php echo JHTML::_('grid.sort', JText::_('COM_IPROPERTY_FILE_NAME' ), 'file_name', $listDirn, $listOrder ); ?>
                    </th>
                    <th width="10%" class="nowrap hidden-phone">
                        <?php echo JHTML::_('grid.sort', JText::_('COM_IPROPERTY_DOWNLOAD_STATUS' ), 'status', $listDirn, $listOrder ); ?>
                    </th>
                    <th width="10%" class="nowrap hidden-phone">
                        <?php echo JHTML::_('grid.sort', JText::_('COM_IPROPERTY_ID' ), 'id', $listDirn, $listOrder ); ?>
                    </th>
                    <!-- <th width="20%" class="nowrap center hidden-phone"><?php echo JText::_('COM_IPROPERTY_ACTION'); ?></th> -->
                    <!-- <th width="5%" class="nowrap center">
                        <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'id', $listDirn, $listOrder); ?>
                    </th> -->
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <td class="center" colspan="6">
                        <?php echo $this->pagination->getListFooter(); ?>
                    </td>
                </tr>
            </tfoot>

            <tbody>
                <?php
                if( $this->downloads ):                           
                    $k = 0;
                    foreach($this->downloads as $i => $item){
                        /*$canEdit        = $this->ipauth->canEditProp($item->id) && !$item->checked_out;*/
                        $canPublish     = $this->ipauth->canPublishProp($item->id, ($item->status == 1) ? 0 : 1) && !$item->checked_out;
                        ?>
                        <tr class="row<?php echo $i % 2; ?>">
                            <td class="center hidden-phone">
                                <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                            </td>
                            <td class="nowrap has-context">
                                <?php echo $item->title; ?>
                            </td>
                            <td class="nowrap has-context">
                                <?php echo $item->file_name.".".$item->type; ?>
                            </td>
                            <td class="center hidden-phone">
                                <?php echo JHtml::_('jgrid.published', $item->status, $i, 'downloads.', $canPublish, 'cb'); ?>
                            </td>
                            <td class="center hidden-phone">
                                <?php echo $item->id; ?>
                            </td>
                            <td class="center hidden-phone">
                                <a href="index.php?option=com_iproperty&task=PropList.download&id=<?php echo $item->id;?>">Download</a>
                            </td>
                        </tr>
                          <?php

                        $k = 1 - $k;
                    }
                else:
                    echo '<td class="center" colspan="6">'.JText::_('COM_IPROPERTY_NO_RESULTS').'</td>';
                endif;
                ?>
            </tbody>
        </table>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <input type="hidden" name="return" value="<?php echo $this->return; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>