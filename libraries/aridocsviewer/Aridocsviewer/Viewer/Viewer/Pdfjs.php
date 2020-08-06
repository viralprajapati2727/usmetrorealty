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

use JText, JURI;

jimport('joomla.environment.uri');

class Pdfjs extends Iframe
{
	protected $isDownloadableDoc = true;

	function processContent($params, $content)
	{
		$url = trim($params['url']);
		if (empty($url))
			return JText::_('PLG_ARIDOCSVIEWER_ERROR_EMPTY_URL');
		
		$check = isset($params['check']) ? (bool)$params['check'] : true;//ArrayHelper::getValue($params, 'check', true, 'BOOLEAN');
		
		if (!$this->isRemoteResource($url))
		{
			if ($check && !$this->isFileExist($url))
			{
				return JText::sprintf('PLG_ARIDOCSVIEWER_ERROR_INCORRECT_URL', JURI::root() . $url);
			}

			$url = JURI::root() . $url;
		}

		$cache = isset($params['cache']) ? (bool)$params['cache'] : true;//ArrayHelper::getValue($params, 'cache', true, 'BOOLEAN');
		if (!$cache)
		{
			$url = new JURI($url);
			$url->setVar('t', time());
			$url = $url->toString();
		}

		$params['preparedUrl'] = sprintf(
			JURI::root(true) . '/media/arisoft/pdfjs/web/viewer.html?file=%1$s',
			htmlentities(urlencode($url), ENT_QUOTES)
		);

		return parent::processContent($params, $content);
	}
}