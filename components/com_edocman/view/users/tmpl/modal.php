<?php
/**
 * @version         1.9.7
 * @package        Joomla
 * @subpackage     EDocman
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011 - 2018 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');

$field		= $this->state->field;
$function	= 'jSelectUser_'.$field;
$listOrder	= $this->state->filter_order;
$listDirn	= $this->state->filter_order_Dir;
?>
<form action="" method="post" name="adminForm" id="adminForm">
	<table width="100%">
	<tr>
		<td align="left">
			<label for="filter_search"><?php echo JText::_('EDOCMAN_SEARCH'); ?></label>
			<input type="text" name="filter_search" style="width: 200px !important"  id="filter_search" value="<?php echo $this->escape($this->state->filter_search); ?>" size="40" title="<?php echo JText::_('EDOCMAN_SEARCH_IN_NAME'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
			<button type="button" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('', '<?php echo JText::_('JLIB_FORM_SELECT_USER') ?>');"><?php echo JText::_('No user')?></button>
		</td>	
		<td style="float: right;">		
			<label for="filter_group_id">
				<?php echo JText::_('Filter User Group'); ?>
			</label>
			<?php echo JHtml::_('access.usergroup', 'filter_group_id', $this->state->filter_group_id, 'onchange="this.form.submit()"'); ?>
		</td>
	</tr>
	</table>
	<table class="adminlist" style="width: 100%">
		<thead>
			<tr>
				<th align="left">
					<?php echo JHtml::_('grid.sort', 'Name', 'tbl.name', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="25%" align="left">
					<?php echo JHtml::_('grid.sort', 'Username', 'tbl.username', $listDirn, $listOrder); ?>
				</th>
				<th class="nowrap" width="25%">
					<?php echo JText::_('User groups'); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3" class="pagination">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$i = 0;
			if (count($this->items))
			{
				foreach ($this->items as $item)
				{
				?>
					<tr class="row<?php echo $i % 2; ?>">
						<td>
							<a class="pointer" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->name)); ?>');">
								<?php echo $item->name; ?></a>
						</td>
						<td align="center">
							<?php echo $item->username; ?>
						</td>
						<td align="left">
							<?php echo nl2br($item->group_names); ?>
						</td>
					</tr>
				<?php
				}
			}
		?>
		</tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="field" value="<?php echo $this->escape($field); ?>" />
		<input type="hidden" name="boxchecked" value="0" />
		<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
		<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
