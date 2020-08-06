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
$items = modIPFeaturedHelper1::getList($params);

if (!$items && $params->get('hide_mod', 1) ){ // hide module if possible with template
    return false;
}else if(!$items){ // display no data message
    $params->def('layout', 'default_nodata');
}
require(JModuleHelper::getLayoutPath('mod_video_property', $params->get('layout', 'default')));