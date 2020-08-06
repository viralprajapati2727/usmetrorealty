<?php
/**
 * @version        1.12.2
 * @package        Joomla
 * @subpackage     EDocman
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class plgEdocmanEDocmanActionLog extends JPlugin
{
	public function __construct(& $subject, $config)
	{
        require_once JPATH_ROOT . '/components/com_edocman/helper/route.php';
        require_once JPATH_ROOT . '/components/com_edocman/helper/helper.php';
        EDocmanHelper::loadLanguage();
		parent::__construct($subject, $config);
	}

    public function onDocumentBeforeDelete($task, $row)
    {
        EDocmanHelper::recordActionLog(JFactory::getUser(), $row->id, 2, 'document');
    }

    public function onCategoryBeforeDelete($task, $row)
    {
        EDocmanHelper::recordActionLog(JFactory::getUser(), $row->id, 2, 'category');
    }

    public function onDocumentDownload($row)
    {
        EDocmanHelper::recordActionLog(JFactory::getUser(), $row->id, 0, 'download');
    }

	public function onDocumentAfterSave($context, $row, $isNew)
	{
        if($isNew)
        {
            $new = 1;
        }
        else
        {
            $new = 0;
        }
		EDocmanHelper::recordActionLog(JFactory::getUser(), $row->id, $new, 'document');
	}

    public function onCategoryAfterSave($context, $row, $isNew)
    {
        if($isNew)
        {
            $new = 1;
        }
        else
        {
            $new = 0;
        }
        EDocmanHelper::recordActionLog(JFactory::getUser(), $row->id, $new, 'category');
    }
}