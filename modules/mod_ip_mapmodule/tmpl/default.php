<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Iproperty
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');



$document 	= JFactory::getDocument();
$settings 	= ipropertyAdmin::config();
$data 		= json_encode((array) $items);
$map_js 	= "var mapData = ".$data.";";
$curr_lang  = JFactory::getLanguage();
$rtl		= $curr_lang->isRTL() ? 1 : 0;
// get currency options
$c_format	= $settings->nformat ? ',' : '.';
$c_symbol	= $settings->currency ?: '$';
$c_round	= $settings->currency_digits ?: '-2';
$c_position = $settings->currency_pos ? '%n %s' : '%s %n'; // TODO: not sure if supported by currency plugin

// load the langfile for the component
JFactory::getLanguage()->load('com_iproperty', JPATH_SITE);

// load currency format and correct language file if it exists
$document->addScript(JURI::root(true).'/components/com_iproperty/assets/js/currency/jquery.formatCurrency-1.4.0.pack.js');
$currencyformat = '{ digitGroupSymbol: "'.$c_format.'", symbol: "'.$c_symbol.'", roundToDecimalPlace: '.$c_round.', positiveFormat: "'.$c_position.'" }';

// check for template map and property preview icons
$map_house_icon         = '/components/com_iproperty/assets/images/map/icon56.png';

// load the appropriate script
$mapscript  = JURI::root(true).'/modules/mod_ip_mapmodule/';
$mapscript .= $params->get('maptype', 0) ? 'bing.js' : 'google.js'; 
$document->addScript( $mapscript );
if (!defined('IPGMAP')){
	$mapsurl = $params->get('maptype', 0) ? '//ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0&mkt='.$curr_lang->get('tag') : '//maps.googleapis.com/maps/api/js?key=AIzaSyCXb_LTi2B8Ga3HZNTF0xssQ2ipLIXKcMg&sensor=false';
	$document->addScript( $mapsurl );
	define("IPGMAP", 1);
}

// get categories
$cats = ipropertyHTML::getCatChildren();
//echo $settings->adv_default_zoom;

$map_js .="
var mapModOptions = {
	ipbaseurl: '".JURI::root()."',
	currencyFormat: ".$currencyformat.",
	marker: '".$map_house_icon."',
	zoom: ".$settings->adv_default_zoom.",
	maxZoom: ".$settings->max_zoom.",
	lat: '".$settings->adv_default_lat."',
	lng: '".$settings->adv_default_long."',
	maptype: '".$params->get('mapstyle', 'ROADMAP')."',
	credentials: '".$settings->map_credentials."'
};

var mapModLangOptions = {
	tprop:'".addslashes(JText::_('COM_IPROPERTY_RESULTS'))."',
	price:'".addslashes(JText::_('COM_IPROPERTY_PRICE'))."',
	pid: '".addslashes(JText::_('COM_IPROPERTY_PROPERTY_ID'))."',
	street: '".addslashes(JText::_('COM_IPROPERTY_STREET'))."',
	beds: '".addslashes(JText::_('COM_IPROPERTY_BEDS'))."',
	baths: '".addslashes(JText::_('COM_IPROPERTY_BATHS'))."',
	sqft: '".addslashes($munits)."',
	preview: '".addslashes(JText::_('COM_IPROPERTY_PREVIEW'))."',
	more: '".addslashes(JText::_('COM_IPROPERTY_MORE' ))."',
	inputText: '".addslashes(JText::_('COM_IPROPERTY_INPUT_TIP'))."',
	isRTL: ".$rtl."
};

jQuery(function($) {
    $(document).ready(function(){
		// set the map divs height explicitly
		$('#ip-map-mod-canvas').height('500px');
		ipMapModFunctions.buildMap(mapModOptions);
		$.each(mapData, function(index, el){
            ipMapModFunctions.addMarker(el);
		});
	});
});"."\n";

$map_js .= "var map_mod_cat_icons = [];"."\n";
      
foreach ($cats as $c){
	$map_js .= 'map_mod_cat_icons['.$c->id.'] = "'.$c->icon.'";'."\n";
}

//$document->addStyleDeclaration('.ip-map-div img{max-width: inherit;}');
$document->addScriptDeclaration( $map_js );
echo '<style>
		.ip-map-div{ margin: auto;
    width: 100%;
	height:98%;
	max-width:1152px;
    border: 2px solid #1c0d82;
	display: block;
	top: 15px;
    }
</style>';
echo '<style>
    	.ip-property-thumb-holder div.span6 a img {
			width: 100%;
		height: 190px;}
		
		#ip-map-mod-canvas .gm-style-iw{  left:0px !important; top:45px !important;} 
		.webi-mapbox{ width:290px; padding:12px 0 0 12px; line-height:20px;}
		
		#ip-map-mod-canvas .ip-bubble-window{ margin-left:0 !important;}
		
		#ip-map-mod-canvas .gm-style div{     background: none !important; box-shadow:none !important; border: none !important;  }
		
		#ip-map-mod-canvas .gm-style img[src*="mapcnt6"] {   display: none !important;}
		
		#ip-map-mod-canvas  .webi-mapbox div.span6{ width:100%; padding:0px 0 10px 0 !important; }
		#ip-map-mod-canvas  .webi-mapbox div.span6 strong{ display:inline-block !important; padding-left:10px !important;}
		
		#ip-map-mod-canvas div.ip-property-thumb-holder{background:#ffffff !important; overflow:hidden; border-radius:5px;  }
		
		}

    </style>';

echo '
    <div id="ip-map-mod-canvas" class="ip-map-div"></div>
    <div class="clearfix"></div>
    ';
?>
