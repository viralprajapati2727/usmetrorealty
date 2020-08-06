<?php
/**
 * @version        1.11.1
 * @package        Joomla
 * @subpackage     EDocman
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011 - 2018 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;
if (count($categories))
{
	if ($categoryId)
	{
	?>
		<h2 class="edocman-heading"><i class="edicon edicon-folder-open"></i>&nbsp;<?php echo JText::_('EDOCMAN_SUB_CATEGORIES'); ?></h2>
	<?php
	}
	$rowFluidClass = $bootstrapHelper->getClassMapping('row-fluid');
	?>
    <table class="table table-condensed table-document" id="table-document">
        <tbody>
            <?php
            for ($i = 0 , $n = count($categories) ; $i < $n ; $i++)
            {
                $category = $categories[$i];
                ?>
                <tr>
                    <td class="edocman-category-title-td" data-label="">
						<i class="edicon edicon-folder-open"></i>
                        <a href="<?php echo JRoute::_(EDocmanHelperRoute::getCategoryRoute($category->id, $Itemid)); ?>" class="edocman-category-title-link" style="display: inline-block;">
                            <?php
                            echo $category->title;
                            if ($config->show_number_documents)
                            {
                                ?>
                                <span class="number_documents">(<?php echo $category->total_documents ;?> <?php echo $category->total_documents > 1 ? JText::_('EDOCMAN_DOCUMENTS') :  JText::_('EDOCMAN_DOCUMENT') ; ?>)</span>
                                <?php
                            }
                            ?>
                        </a>
                        <?php
                        if ($config->enable_rss)
                        {
                            ?>
                            <span class="edocman-rss-icon">
								<a href="<?php echo JRoute::_('index.php?option=com_edocman&view=category&id='.$category->id.'&format=feed&type=rss'); ?>"><img src="<?php echo JUri::root().'/components/com_edocman/assets/images/rss.png' ?>" /></a>
							</span>
                            <?php
                        }
                        ?>
                    </td>
                </tr>
                <?php
                if(count($category->documents))
                {
					if ($config->show_view_button && count($category->documents))
					{
						$extsForView = explode(',', $config->exts_for_view);
						for ($j = 0, $n = count($extsForView); $j < $n; $j++)
						{
							$extsForView[$j] = strtolower(trim($extsForView[$j]));
						}
						foreach ($category->documents as $item)
						{
							$fileName = $item->filename;
							$fileExt  = strtolower(JFile::getExt($fileName));
							if (in_array($fileExt, $extsForView) || ($item->view_url != ''))
							{
								$item->canView = 1;
							}
							else
							{
								$item->canView = 0;
							}
						}
					}
                    ?>
                    <tr>
                        <td width="100%">
                            <?php
                            echo EDocmanHelperHtml::loadCommonLayout('common/documents_table.php', array('items' => $category->documents, 'config' => $config, 'Itemid' => $Itemid, 'category' => $category, 'categoryId' => (int) $category->id, 'bootstrapHelper' => $bootstrapHelper));
                            ?>
                        </td>
                    </tr>
                    <?php
                }
            }
            ?>
        </tbody>
    </table>
<?php
}