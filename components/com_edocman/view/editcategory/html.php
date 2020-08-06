<?php
/**
 * @version        1.14.0
 * @package        Joomla
 * @subpackage     EDocman
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011-2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die();

class EDocmanViewEditcategoryHtml extends OSViewItem
{
	function prepareView()
	{
        parent::prepareView();
        $app                        = JFactory::getApplication();
        $this->config               = EDocmanHelper::getConfig();
        if (!JFactory::getUser()->get('id'))
        {
            // Allow users to login
            $return                 = base64_encode(JUri::getInstance()->toString());
            JFactory::getApplication()->redirect('index.php?option=com_users&view=login&return=' . $return);
        }
        $id = $this->model->getState()->id;
        if (!$id)
        {
            //new campaign
            $ret                    = JFactory::getUser()->authorise('core.create', 'com_edocman');
            if(! $ret)
            {
                $app->enqueueMessage(JText::_('EDOCMAN_NOT_ALLOWED_ACTION'));
                $url = JRoute::_(JUri::root());
                $app->redirect($url);
            }
        }
        else
        {
            //edit campaign
            $ret                    = JFactory::getUser()->authorise('core.edit', 'com_edocman');
            //only allow edit campaign when user has permission and is owner of campaign
            if(! $ret )
            {
                $app->enqueueMessage(JText::_('EDOCMAN_NOT_ALLOWED_ACTION'));
                $url = JRoute::_(JUri::root());
                $app->redirect($url);
            }
        }

        $document = JFactory::getDocument();
        if($this->item->id > 0)
        {
            $headerText = str_replace('CATEGORY_TITLE', $this->item->title, JText::_('EDOCMAN_EDIT_CATEGORY'));
            $document->setTitle($headerText);
        }
        else
        {
            $headerText = JText::_('EDOCMAN_ADD_CATEGORY');
            $document->setTitle($headerText);
        }
        $this->form = $this->model->getForm();
        $this->bootstrapHelper = new EDocmanHelperBootstrap($this->config->twitter_bootstrap_version);
	}
}