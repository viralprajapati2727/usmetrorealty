<?php
/**
 * @package Okey DOC 1.x
 * @copyright Copyright (c)2014 - 2017 Lucas Sanner
 * @license GNU General Public License version 3, or later
 * @contact lucas.sanner@gmail.com
 */

defined('_JEXEC') or die;

JHtml::_('formbehavior.chosen', 'select');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
?>
<script type="text/javascript">
  function clearSearch()
  {
    document.getElementById('filter-search').value = '';
    document.getElementById('adminForm').submit();
  }
</script>

<div class="blog<?php echo $this->pageclass_sfx;?>">
  <?php if ($this->params->get('show_page_heading')) : ?>
	  <h1>
	    <?php echo $this->escape($this->params->get('page_heading')); ?>
	  </h1>
  <?php endif; ?>
  <?php if($this->params->get('show_category_title', 1)) : ?>
	  <h2 class="category-title">
	      <?php echo JHtml::_('content.prepare', $this->category->title, '', $this->category->extension.'.category.title'); ?>
	  </h2>
  <?php endif; ?>
  <?php if ($this->params->get('show_tags', 1)) : ?>
	  <?php echo JLayoutHelper::render('joomla.content.tags', $this->category->tags->itemTags); ?>
  <?php endif; ?>
  <?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	  <div class="category-desc">
		  <?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
			  <img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
		  <?php endif; ?>
		  <?php if ($this->params->get('show_description') && $this->category->description) : ?>
			  <?php echo JHtml::_('content.prepare', $this->category->description, '', $this->category->extension.'.category'); ?>
		  <?php endif; ?>
		  <div class="clr"></div>
	  </div>
  <?php endif; ?>

  <form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post" name="adminForm" id="adminForm">

    <?php if($this->params->get('filter_field') != 'hide' || $this->params->get('show_pagination_limit') || $this->params->get('filter_ordering')) : ?>
    <div class="okeydoc-toolbar clearfix">
      <?php if ($this->params->get('filter_field') != 'hide') :?>
	<div class="btn-group input-append span6">
	  <input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->state->get('list.filter')); ?>"
		  class="inputbox" onchange="document.adminForm.submit();" title="<?php echo JText::_('COM_OKEYDOC_FILTER_SEARCH_DESC'); ?>"
		  placeholder="<?php echo JText::_('COM_OKEYDOC_'.$this->params->get('filter_field').'_FILTER_LABEL'); ?>" />

	    <button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>">
		    <i class="icon-search"></i>
	    </button>

	    <button type="button" onclick="clearSearch()" class="btn hasTooltip js-stools-btn-clear"
		    title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>">
		    <?php echo JText::_('JSEARCH_FILTER_CLEAR');?>
	    </button>
	</div>
      <?php endif; ?>
     
      <?php echo JLayoutHelper::render('filter_ordering', $this, JPATH_SITE.'/components/com_okeydoc/layouts/'); ?>

      <?php if($this->params->get('show_pagination_limit')) : ?>
	<div class="span1">
	    <?php echo $this->pagination->getLimitBox(); ?>
	</div>
      <?php endif; ?>

    </div>
    <?php endif; ?>

    <?php if(empty($this->items)) : //Check for items. ?>
	    <?php if($this->params->get('show_no_documents', 1)) : ?>
	    <p><?php echo JText::_('COM_OKEYDOC_NO_DOCUMENTS'); ?></p>
	    <?php endif; ?>
    <?php else : //Display items. ?>
      <?php foreach($this->items as $i => &$item) : ?>
	<?php
	      $this->item = &$item;
	      echo $this->loadTemplate('item');
	  ?>
      <?php endforeach; ?>
    <?php endif; ?>

    <?php if(($this->params->def('show_pagination', 2) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->pagesTotal > 1)) : ?>
    <div class="pagination">

	    <?php if ($this->params->def('show_pagination_results', 1)) : ?>
		    <p class="counter pull-right">
			    <?php echo $this->pagination->getPagesCounter(); ?>
		    </p>
	    <?php endif; ?>

	    <?php //Load our own pagination layout. ?>
	    <?php echo JLayoutHelper::render('document_pagination', $this->pagination, JPATH_SITE.'/components/com_okeydoc/layouts/'); ?>
    </div>
    <?php endif; ?>

    <?php if($this->get('children') && $this->maxLevel != 0) : ?>
	    <div class="cat-children">
	      <h3><?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?></h3>
	      <?php echo $this->loadTemplate('children'); ?>
	    </div>
    <?php endif; ?>

    <input type="hidden" name="limitstart" value="" />
    <input type="hidden" name="task" value="" />
  </form>
</div><!-- blog -->

