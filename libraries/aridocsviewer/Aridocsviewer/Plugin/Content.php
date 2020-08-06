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

namespace Aridocsviewer\Plugin;

defined('_JEXEC') or die;

use Arisoft\Plugin\Content as ContentPlugin;
use Arisoft\Utilities\ObjectFactory as ObjectFactory;

class Content extends ContentPlugin
{
	private $params;
	
	function __construct($params)
	{
		$this->params = $params;
		
		parent::__construct('aridoc');
	}

	protected function contentHandler($params, $content, $sourceContent) 
	{
		$params = $this->getParams($params, $content);
		$viewerType = $params['engine'];
		
		$viewer = ObjectFactory::getObject($viewerType, 'Aridocsviewer\\Viewer\\Viewer');

		return $viewer ? $viewer->processContent($params, $content) : '';
	}
	
	private function getParams($attrs, $content)
	{
		if (!is_array($attrs))
			$attrs = array();

		$params = $this->params->toArray();
		foreach ($params as $key => $value)
		{
			$cleanKey = $key;
			if (strpos($key, 'opt_') === 0)
			{
				$cleanKey = substr($key, 4);
			}
			
			if (!isset($attrs[$cleanKey]))
				$attrs[$cleanKey] = $value;
		}

		if (!isset($attrs['url']))
			$attrs['url'] = $content;

		return $attrs;
	}
}