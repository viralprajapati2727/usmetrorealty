<?php
/**
 * @version        1.10.0
 * @package        Joomla
 * @subpackage     EDocman
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011-2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();

class EDocmanViewManagecategoriesHtml extends OSViewList
{
	protected function prepareView()
	{
        if (!JFactory::getUser()->get('id'))
        {
            // Allow users to login
            $return = base64_encode(JUri::getInstance()->toString());
            JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return=' . $return);
        }
        else
        {
            if (!JFactory::getUser()->authorise('edocman.manage_categories', 'com_edocman'))
            {
                $msg = JText::_('EDOCMAN_YOU_DO_NOT_HAVE_PERMISSION_TO_MANAGE_CATEGORIES');
                JFactory::getApplication()->enqueueMessage($msg);
                JFactory::getApplication()->redirect(JUri::root());
            }
        }

        parent::prepareView();

        /**
         * Level filter
         */
        $options   = array();
        $options[] = JHtml::_('select.option', '0', JText::_('EDOCMAN_SELECT_LEVEL'));
        $options[] = JHtml::_('select.option', '1', JText::_('J1'));
        $options[] = JHtml::_('select.option', '2', JText::_('J2'));
        $options[] = JHtml::_('select.option', '3', JText::_('J3'));
        $options[] = JHtml::_('select.option', '4', JText::_('J4'));
        $options[] = JHtml::_('select.option', '5', JText::_('J5'));
        $options[] = JHtml::_('select.option', '6', JText::_('J6'));
        $options[] = JHtml::_('select.option', '7', JText::_('J7'));
        $options[] = JHtml::_('select.option', '8', JText::_('J8'));
        $options[] = JHtml::_('select.option', '9', JText::_('J9'));
        $options[] = JHtml::_('select.option', '10', JText::_('J10'));

        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('MAX(level)')
            ->from('#__edocman_categories');
        $db->setQuery($query);
        $maxLevel = (int) $db->loadResult();
        $maxLevel = min($maxLevel, 10);
        $options  = array_slice($options, 0, $maxLevel + 1);

        $this->lists['filter_level'] = JHtml::_('select.genericlist', $options, 'filter_level', ' class="input-medium" onchange="submit();" ', 'value', 'text', $this->state->filter_level);

        $query->clear();
        $query->select('id, title, parent_id');
        $query->from('#__edocman_categories');
        $query->where('published=1');
        $db->setQuery($query);
        $rows     = $db->loadObjectList();
        $children = array();
        // first pass - collect children
        if (count($rows))
        {
            foreach ($rows as $v)
            {
                $pt   = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }
        }
        $list      = JHtml::_('menu.treerecurse', 0, '', array(), $children, 9999, 0);
        $options   = array();
        $options[] = JHtml::_('select.option', 0, JText::_('EDOCMAN_SELECT_PARENT_CATEGORY'));
        if (count($list))
        {
            foreach ($list as $row)
            {
                $options[] = JHtml::_('select.option', $row->id, $row->treename);
            }
        }
        $this->lists['filter_category_id'] = JHtml::_('select.genericlist', $options, 'filter_category_id',
            array(
                'option.text.toHtml' => false,
                'list.attr'          => 'class="inputbox" onchange="submit();"  ',
                'option.text'        => 'text',
                'option.key'         => 'value',
                'list.select'        => (int) $this->state->filter_category_id));

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('EDOCMAN_MANAGE_CATEGORIES'));
		$config = EDocmanHelper::getConfig();
		$this->bootstrapHelper = new EDocmanHelperBootstrap($config->twitter_bootstrap_version);
		$this->config     = $config;
		$this->params = JFactory::getApplication()->getParams();
	}
}