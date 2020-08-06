<?php
/*
 * ARI Docs Viewer Joomla! plugin
 *
 * @package		ARI Docs Viewer
 * @version		2.0.0
 * @author		ARI Soft
 * @copyright	Copyright (c) 2009 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 *   
*/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemAridocsviewer extends JPlugin
{
	private $executed = false;

	function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config); 
	}
	
	function onAfterRender()
	{
		$app = JFactory::getApplication();
		$jInput = $app->input;

		$option = $jInput->request->getString('option');
		$view = $jInput->request->getString('view');
		$task = $jInput->request->getString('task');
		if (
			($option == 'com_content' && $view == 'form' && $jInput->request->getString('layout') == 'edit')
			||
			($option == 'com_k2' && $view == 'item' && $task == 'edit')
			||
			($option == 'com_k2' && $view == 'item' && $task == 'save')
			||
			($option == 'com_comprofiler' && $task == 'userDetails')
			)
			return ;
			
		$this->prepareContent();
	}

	function prepareContent()
	{
		if ($this->executed)
			return ;
			
		$this->executed = true;
		$app = JFactory::getApplication();

		$doc = JFactory::getDocument();
		$docType = $doc->getType();

		if ($app->isAdmin() || $docType !== 'html') 
			return ;
		
		$content = $app->getBody();

		$bodyPos = stripos($content, '<body');
		$preContent = '';
		if ($bodyPos > -1)
		{
			$preContent = substr($content, 0, $bodyPos);
			$content = substr($content, $bodyPos);
		}
		
		if (strpos($content, '{aridoc') === false)
			return ;
			
		require_once JPATH_ROOT . '/libraries/aridocsviewer/loader.php';

		$params = $this->params;
			
		$this->loadLanguage('', JPATH_ADMINISTRATOR);
		$uri = JURI::root(true) . '/media/aridocsviewer/';

		$includesManager = new Arisoft\Joomla\Document\Includesmanager;

		if ((bool)$params->get('loadJQuery', true))
		{
			$jqNoConflict = (bool)$params->get('callNC', true);

			JHtml::_('jquery.framework', $jqNoConflict);
		}
		
		$versionKey = bin2hex(ARIDOCSVIEWER_VERSION);
		
		$doc->addStyleSheet($uri . 'css/styles.css?v=' . $versionKey);
		$doc->addScript($uri . 'js/aridocsviewer.js?v=' . $versionKey);

		$plg = new Aridocsviewer\Plugin\Content($params);
		$content = $plg->parse($content);

		$app->setBody(
			preg_replace('/<\/head\s*>/i', '$0', $preContent . $content, 1)
		);

		$includes = $includesManager->getDifferences();
		Arisoft\Joomla\Document\Helper::addCustomTagsToDocument($includes);
	}
}