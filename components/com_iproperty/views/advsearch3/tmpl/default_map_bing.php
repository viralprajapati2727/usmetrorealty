<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

$curr_lang  = JFactory::getLanguage();

// get URL scheme
$scheme				= JURI::getInstance()->getScheme();

// Include map scripts
$this->document->addScript($scheme.'://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0&mkt='.$curr_lang->get('tag') );

if (!defined('IPGMAP')){
	define("IPGMAP", 1);
}

$this->document->addScript(JURI::root(true).'/components/com_iproperty/assets/advsearch/bing.js');
if ($this->params->get('adv_show_shapetools')) $this->document->addScript(JURI::root(true).'/components/com_iproperty/assets/advsearch/bingtools.js');

echo '<div id="ip-map-canvas" class="ip-map-div"></div>';
?>
