<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Iproperty
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

// Include the helper functions only once
require_once (dirname(__FILE__).'/helper.php');

// Get module data
$items = modIpFeaturedAgentsHelper::getList($params);

if (!$items && $params->get('hide_mod', 1)){ // hide module if possible with template
    return false;
}else if(!$items){ // display no data message
    $params->def('layout', 'default_nodata');
}else{
    // include iproperty css if set in parameters
    if ($params->get('include_ipcss', 1) && !defined('_IPMODCSS')){
        define('_IPMODCSS', true);
        ipropertyHTML::includeIpScripts();
    }
}
require(JModuleHelper::getLayoutPath('mod_ip_featuredagents', $params->get('layout', 'default')));
