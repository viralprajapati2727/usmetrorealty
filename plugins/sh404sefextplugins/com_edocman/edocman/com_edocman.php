<?php
/**
 * sh404SEF support for com_sample component.
 * Author : Dang Thuc Dam
 * contact : damdt@joomdonation.com
 *
 * This is a sample sh404SEF native plugin file
 *
 */
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
// ------------------  standard plugin initialize function - don't change ---------------------------
global $sh_LANG;
$sefConfig = Sh404sefFactory::getConfig();
$shLangName = '';
$shLangIso = '';
$title = array();
$shItemidString = '';
$dosef = shInitializePlugin($lang, $shLangName, $shLangIso, $option);
if ($dosef == false)
{
	return;
}
// ------------------  standard plugin initialize function - don't change ---------------------------

// ------------------  load language file - adjust as needed ----------------------------------------
//$shLangIso = shLoadPluginLanguage( 'com_edocman', $shLangIso, '_SEF_SAMPLE_TEXT_STRING');
// ------------------  load language file - adjust as needed ----------------------------------------

// remove common URL from GET vars list, so that they don't show up as query string in the URL
shRemoveFromGETVarsList('option');
shRemoveFromGETVarsList('lang');
if (!empty($Itemid))
{
	shRemoveFromGETVarsList('Itemid');
}
if (!empty($limit))
{
	shRemoveFromGETVarsList('limit');
}
if (isset($limitstart))
{
	shRemoveFromGETVarsList('limitstart');
} // limitstart can be zero

// start by inserting the menu element title (just an idea, this is not required at all)
$task = isset($task) ? $task : null;
$view = isset($view) ? $view : null;
$Itemid = isset($Itemid) ? $Itemid : null;
$type = isset($type) ? $type : null;
$format = isset($format) ? $type : null;
$id	= isset($id) ? $id : null;

$shEdocmanName = shGetComponentPrefix($option);
$shEdocmanName = empty($shEdocmanName) ?
	getMenuTitle($option, $task, $Itemid, null, $shLangName) : $shEdocmanName;
$shEdocmanName = (empty($shEdocmanName) || $shEdocmanName == '/') ? 'Edocman' : $shEdocmanName;

require_once JPATH_ROOT.'/components/com_edocman/helper/helper.php';
require_once JPATH_ROOT.'/components/com_edocman/helper/route.php';
$parentId = 0;
switch ($view)
{
	case 'categories':
	case 'category':
		if ($id)
		{
			$title = array_merge($title, EDocmanHelperRoute::getCategoriesPath($id, 'alias', true, $parentId));
		}
		if (!empty($format) && $format != 'html')
		{
			$title[] = $format;
			if (!empty($type))
			{
				$title[] = $type;
			}
		}
		shRemoveFromGETVarsList('id');
		$objectName = 'category';
	break;
	case 'document':
		if ($id)
		{
			$title[] = EDocmanHelperRoute::getDocumentTitle($id);
		}
		shRemoveFromGETVarsList('id');
		$config = EDocmanHelper::getConfig();
		if (($catid)  && ($config->insert_category != 2))
		{
			$title = array_merge(EDocmanHelperRoute::getCategoriesPath($catid, 'alias', true, $parentId), $title);
		}
		if (isset($layout) && $layout == 'edit')
		{
			if ($id)
			{
				$title[] = 'Edit';
			}
			else
			{
				$title[]     = 'Upload Document';
				$id = 0;
			}
			shRemoveFromGETVarsList('layout');
		}
		$objectName = 'document';
		shRemoveFromGETVarsList('catid');
	break;
	case 'license':
		if ($id)
		{
			$q->clear();
			$q->select('title')
				->from('#__edocman_licenses')
				->where('id=' . $id);
			$db->setQuery($q);
			$title[] = $db->loadResult();
		}
		$title[] = 'View License';
		shRemoveFromGETVarsList('id');
		$objectName = 'license';
		break;
	case 'search':
		$title[] = 'Search result';
		break;
	case 'documents':
		$title[] = 'Documents List';
		break;
	case 'users':
		$title[] = 'Users List';
		break;
}

// also remove task, as it is not needed
// because we can revert the SEF URL without it
shRemoveFromGETVarsList('view');


switch ($task)
{
	case 'document.download':
		if ($id)
		{
			$title[] = EDocmanHelperRoute::getDocumentTitle($id);
			shRemoveFromGETVarsList('id');
		}
		$title[] = 'Download';
		$objectName = 'document';
		shRemoveFromGETVarsList('task');
		break;
	case 'document.viewdoc':
		if ($id)
		{
			$title[] = EDocmanHelperRoute::getDocumentTitle($id);
			shRemoveFromGETVarsList('id');
		}
		$title[] = 'View-Document';
		$objectName = 'document';
		shRemoveFromGETVarsList('task');
		break;
	case 'document.edit':
		if ($id)
		{
			$title[] = EDocmanHelperRoute::getDocumentTitle($id);
			shRemoveFromGETVarsList('id');
		}
		$title[] = 'Edit Document';
		shRemoveFromGETVarsList('task');
		$objectName = 'document';
		break;
}
/*
switch ($task)
{
	case 'task1':
	case 'task2' :
		$dosef = false;  // these tasks do not require SEF URL
		break;

	default:
		$title[] = $sh_LANG[$shLangIso]['COM_SH404SEF_VIEW_SAMPLE'];// insert a 'View sample' string,
		// according to language
		// only if you have defined the
		if (!empty($sampleId))
		{
			// fetch some data about the content
			// If this data may be used several times within a single page load, make sure
			// to store it into memory so as to not read it twice from the database
			$db = JFactory::getDbo();
			$q = $db->getQuery(true)
				->select($db->qn('title'))
				->from($db->qn('#__samplenames'))
				->where($db->qn('id') . ' = ' . $db->q($sampleId));
			$db->setQuery($q);
			$sampleTitle = $database->loadResult();

			if ($sampleTitle)
			{                                                   // if we found a title for this element
				$title[] = $sampleTitle->title;                 // insert it in URL array
				shRemoveFromGETVarsList('sampleId');            // remove sampleId var from GET vars list
				// as we have found a text equivalent
				shMustCreatePageId('set', true);                // ask sh404sef to create a short URL for this SEF URL (pageId)
			}
		}
		// also remove task, as it is not needed
		// because we can revert the SEF URL without it
		shRemoveFromGETVarsList('task');
}
*/
// ------------------  standard plugin finalize function - don't change ---------------------------  
if ($dosef)
{
	$string = shFinalizePlugin($string, $title, $shAppendString, $shItemidString,
		(isset($limit) ? @$limit : null), (isset($limitstart) ? @$limitstart : null),
		(isset($shLangName) ? @$shLangName : null));
}
// ------------------  standard plugin finalize function - don't change ---------------------------
  