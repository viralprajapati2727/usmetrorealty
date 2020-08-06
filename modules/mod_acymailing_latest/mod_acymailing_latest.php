<?php
/**
 * @copyright	Copyright (C) 2009-2014 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
if(!include_once(rtrim(JPATH_ADMINISTRATOR,DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_acymailing'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php')){
	echo 'This module can not work without the AcyMailing Component';
	return;
};

$config = acymailing_config();

//Check version...
if(version_compare($config->get('version'),'4.0.0','<')){
	echo 'Please update AcyMailing first, this module is not compatible with your current version of AcyMailing';
	return;
}

$nbnews = intval($params->get('nbnews',5));

$acyItemid = $params->get('itemid');
if(empty($acyItemid)){
	$acyItemid = $config->get('itemid');
}
$acyItem = empty($acyItemid) ? '' : '&Itemid='.$acyItemid;

if($params->get('popup')){
	JHTML::_('behavior.modal','a.modal');
	$acyItem .= '&tmpl=component';
}

$db = JFactory::getDBO();

$my = JFactory::getUser();
if(!empty($my->id) && $params->get('ownnews',0)){
	//We display newsletters already sent to the user regardless to the fact the newsletter is published or not.
	$subscriberClass = acymailing_get('class.subscriber');
	//We do it based on the e-mail address just to be safer
	$subid = $subscriberClass->subid($my->email);
	if(empty($subid)){
		echo 'No subscriber found attached to your user account ('.$my->email.')';
		return;
	}

	$ordering = ($params->get('ordering') == 'mailid') ? 'm.mailid' : 's.senddate';

	$query = 'SELECT "" AS body, "" AS altbody, m.html AS sendHTML,m.key, s.senddate, m.alias, m.subject, m.mailid FROM #__acymailing_userstats as s ';
	$query .= 'JOIN #__acymailing_mail as m on s.mailid = m.mailid ';
	$query .= "WHERE m.published = 1 AND m.visible = 1 AND s.subid = ".intval($subid);
	$query .= " ORDER BY ".$ordering." DESC ";
	if(!empty($nbnews)) $query .= "LIMIT ".$nbnews;

	$db->setQuery($query);
	$newsletters = $db->loadObjectList();

	if(empty($newsletters)){
		echo (JText::_('NO_LATEST_NEWSLETTER') != 'NO_LATEST_NEWSLETTER') ? JText::_('NO_LATEST_NEWSLETTER') : "You will soon see the e-mails you received there...";
		return;
	}

}else{
	$lists = strtolower($params->get('lists','all'));
	if($lists == 'none'){
		echo 'Please select one or several lists in the module parameters';
		return;
	}

	$listsClass = acymailing_get('class.list');

	//SELECT THE LISTS FIRST
	$queryList = "SELECT * FROM #__acymailing_list WHERE type = 'list' AND published = 1 AND visible = 1 ";
	if($lists != 'all'){
		$listids = explode(',',$lists);
		$queryList .= "AND listid IN ('".implode("','",$listids)."') ";
	}
	$db->setQuery($queryList);
	$allLists = $db->loadObjectList('listid');

	if(empty($allLists)){
		echo 'Please make sure the list you selected in the module is published and visible';
		return;
	}

	if(acymailing_level(1)){
		$allLists = $listsClass->onlyCurrentLanguage($allLists);
	}

	if(empty($allLists)){
		echo 'There is no list configured to be displayed with the current language';
		return;
	}

	//Select only allowed lists based on the user subscription
	if(acymailing_level(3)){
		$allLists = $listsClass->onlyAllowedLists($allLists);
	}

	if(empty($allLists)){
		echo 'Users are not allowed to view Newsletters attached to this list, please check the access level on your lists';
		return;
	}

	$ordering = ($params->get('ordering') == 'mailid') ? 'm.mailid' : 'm.senddate';

	$query = 'SELECT "" AS body, "" AS altbody, m.html AS sendHTML, m.senddate, m.alias, m.subject, m.mailid, l.listid, l.alias as listalias FROM #__acymailing_listmail as b ';
	$query .= 'JOIN #__acymailing_list as l on l.listid = b.listid ';
	$query .= 'JOIN #__acymailing_mail as m on b.mailid = m.mailid ';
	$query .= "WHERE m.type = 'news' AND m.published = 1 AND m.visible = 1 AND l.listid IN (".implode(',',array_keys($allLists)).")";
	$query .= "GROUP BY m.mailid ORDER BY ".$ordering." DESC ";
	if(!empty($nbnews)) $query .= "LIMIT ".$nbnews;

	$db->setQuery($query);
	$newsletters = $db->loadObjectList();

	if(empty($newsletters)){
		echo (JText::_('NO_LATEST_NEWSLETTER') != 'NO_LATEST_NEWSLETTER') ? JText::_('NO_LATEST_NEWSLETTER') : "Please make sure you have a published and visible Newsletter attached to your list";
		return;
	}
}

// Trigger to replace tags in subject
if(!empty($my->email)){
	$userClass = acymailing_get('class.subscriber');
	$receiver = $userClass->get($my->email);
}
if(empty($receiver)){
	$receiver = new stdClass();
	$receiver->name = JText::_('VISITOR');
}
JPluginHelper::importPlugin('acymailing');
$dispatcher = JDispatcher::getInstance();
foreach($newsletters as $mail){
	if(strpos($mail->subject, "{") !== false){
		$dispatcher->trigger('acymailing_replacetags',array(&$mail, false));
		$dispatcher->trigger('acymailing_replaceusertags',array(&$mail,&$receiver, false));
	}
}

require(JModuleHelper::getLayoutPath('mod_acymailing_latest'));