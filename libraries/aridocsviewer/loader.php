<?php
/*
 *
 * @package		ARI Libraries
 * @author		ARI Soft
 * @copyright	Copyright (c) 2016 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('_JEXEC') or die;

define('ARIDOCSVIEWER_VERSION', '2.1.0');

$sysLibPath = dirname(__FILE__) . '/../arisoft/loader.php';

if (!file_exists($sysLibPath))
	JFactory::getApplication()->enqueueMessage('ARI Docs Viewer: "ARI Soft" library is not installed.', 'warning');
else
	require_once $sysLibPath;

JLoader::registerNamespace('Aridocsviewer', dirname(__FILE__));