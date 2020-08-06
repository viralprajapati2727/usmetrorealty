<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');

$document   = JFactory::getDocument();
$url        = '//maps.google.com/maps/api/js?key=AIzaSyCXb_LTi2B8Ga3HZNTF0xssQ2ipLIXKcMg&sensor=false';

// set locale
$url .= $this->settings->map_locale ? '&language='.$this->settings->map_locale : '';

if(JPluginHelper::isEnabled('iproperty', 'googleplaces')) {
	$url .= '&libraries=places';
}

// add the Google API script
$this->document->addScript($url);
$document->addScript($this->ipbaseurl.'/components/com_iproperty/assets/js/property_gmap.js');
if (!defined('IPGMAP')){
	define("IPGMAP", 1);
}

$map_script  = "jQuery(window).load(function($){    
                    ipPropertyMap.buildMap();
                });";
$document->addScriptDeclaration($map_script);

echo '<div id="ip-map-canvas" class="ip-map-div"></div>';
?>
