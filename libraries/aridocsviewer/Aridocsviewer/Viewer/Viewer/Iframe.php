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

use Aridocsviewer\Viewer\Base as ViewerBase, JText;

class Iframe extends ViewerBase
{
	protected $isDownloadableDoc = false;

	function processContent($params, $content)
	{
		$url = trim($params['url']);
		$preparedUrl = !empty($params['preparedUrl']) ? $params['preparedUrl'] : $url;//trim(ArrayHelper::getValue($params, 'preparedUrl', $url));

		if (empty($preparedUrl))
			return JText::_('PLG_ARIDOCSVIEWER_ERROR_EMPTY_URL');

		$showDownloadLink = isset($params['showDownloadLink']) ? (bool)$params['showDownloadLink'] : false;//ArrayHelper::getValue($params, 'showDownloadLink', false, 'BOOLEAN');
		$downloadLink = JText::_('PLG_ARIDOCSVIEWER_MESSAGE_DOWNLOADLINK');
		$showLoading = (bool)$params['loadingPane'];
		$loadingMessage = $showLoading ? JText::_('PLG_ARIDOCSVIEWER_MESSAGE_LOADING') : null;
		$width = !empty($params['width']) ? $params['width'] : 500;//ArrayHelper::getValue($params, 'width', 500);
		$height = !empty($params['width']) ? $params['height'] : 350;//ArrayHelper::getValue($params, 'height', 350);
		$frameId = !empty($params['id']) ? $params['id'] : uniqid('aridoc_', false);

		return sprintf('<div class="aridoc-container %6$s %5$s">%4$s<iframe id="%8$s" class="aridoc-frame" src="%1$s" width="%2$s" height="%3$s" frameBorder="0" allowTransparency="true"%9$s></iframe>%7$s</div>',
			$preparedUrl,
			$width,
			$height,
			$loadingMessage ? '<div class="aridoc-loading-message">' . $loadingMessage . '</div>' : '',
			!empty($params['class']) ? $params['class'] : '',//ArrayHelper::getValue($params, 'class'),
			$showLoading ? 'aridoc-loading' : '',
			$this->isDownloadableDoc && $url && $showDownloadLink && $downloadLink 
				? sprintf('<div class="aridoc-dl-container" style="width:%3$s"><a class="aridoc-dl" href="%1$s" target="_blank">%2$s</a></div>',
					$url,
					$downloadLink,
					strpos($width, '%') === false ? $width . 'px' : $width
				  )
				: '',
			$frameId,
			!empty($params['onload']) ? ' onload="' . $params['onload'] . '"' : '');
	}
	
	protected function isRemoteResource($link)
	{
		if (empty($link))
			return false;
			
		return preg_match('/(https?|ftp):\/\/.+/', $link);
	}
	
	protected function isFileExist($uri)
	{
		$path = JPATH_ROOT . '/' . $uri;
		
		return @file_exists($path);
	}
}