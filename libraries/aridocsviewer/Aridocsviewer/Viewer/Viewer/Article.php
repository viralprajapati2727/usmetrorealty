<?php
/*
 * ARI Docs Viewer
 *
 * @package		ARI Docs Viewer
 * @version		2.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2010 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

namespace Aridocsviewer\Viewer\Viewer;

defined('_JEXEC') or die;

use JURI, JText;

class Article extends Iframe
{
	protected $isDownloadableDoc = true;

	function processContent($params, $content)
	{
		if (empty($params['id']))
			return JText::_('PLG_ARIDOCSVIEWER_ERROR_EMPTY_ARTICLEID');
	
		$id = intval($params['id'], 10);
		if ($id < 1)
			return sprintf(JText::_('PLG_ARIDOCSVIEWER_ERROR_INCORRECT_ARTICLEID'), $params['id']);
		
		$params['preparedUrl'] = sprintf('%1$s/index.php?option=com_content&view=article&id=%2$d&tmpl=component',
			JURI::root(true),
			$id);

		return parent::processContent($params, $content);
	}
}