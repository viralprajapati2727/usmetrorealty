<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
//echo $this->loadTemplate('toolbar');
defined( '_JEXEC' ) or die( 'Restricted access');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
jimport('joomla.filesystem.file');

// get category data
$cats = ipropertyHTML::getAllCats();

// check for template map icons
$templatepath       = JFactory::getApplication()->getTemplate();
$map_house_icon     = JURI::root(true).'/components/com_iproperty/assets/images/map/icon56.png';
if(JFile::exists('templates/'.$templatepath.'/images/iproperty/map/icon56.png')) $map_house_icon = JURI::root(true).'/templates/'.$templatepath.'/images/iproperty/map/icon56.png';

// get URL scheme
$scheme				= JURI::getInstance()->getScheme();
//maps.googleapis.com/maps/api/js?key={123456ABCDEFG}&sensor=false"
//src="https://maps.googleapis.com/maps/api/js?key=123456ABCDEFG&sensor=false" 
$this->document->addScript($scheme.'://maps.google.com/maps/api/js?key=AIzaSyCXb_LTi2B8Ga3HZNTF0xssQ2ipLIXKcMg&sensor=false');

$map_js = 'var locations = [];';
//echo "<pre>"; print_r($this->items); exit;
foreach($this->items as $item){
	$doc = new DOMDocument();
	@$doc->loadHTML($item->thumb);

	$tags = $doc->getElementsByTagName('img');
	$imagePro = $tags[0]->getAttribute('src');
	/*foreach ($tags as $tag) {
	       echo "<br/>".$tag->getAttribute('src');
	}*/
	// echo $item->thumb;
	/*$html = str_get_html($item->thumb);
	echo $html[0]->find('img');*/
	/*foreach($html->find('img') as $element)
       echo $element->src . '<br>';*/


	$cat_item = $cats[$item->cat_id];
	if ( !is_null($cat_item) && !empty($cat_item['icon']) && $cat_item['icon'] !== 'nopic.png' ){
		$icon = JURI::root(true).'/media/com_iproperty/categories/'.$cat_item['icon'];
	} else {
		$icon = $map_house_icon;
	}
	
	/*if($item->lat_pos && $item->long_pos){
		$map_js .= 'locations.push(['.$item->lat_pos.','.$item->long_pos.','.$item->id.',"'.$icon.'","'.$item->proplink.'","'.$imagePro.'",'.$item->stype.',"'.$item->yearbuilt.'","'.$item->formattedprice.'","'.$item->city.'","'.$item->statename.'"]);';	
	}*/
	if($item->lat_pos && $item->long_pos){
		$map_js .= 'locations.push(['.$item->lat_pos.','.$item->long_pos.','.$item->id.',"'.$icon.'","'.$item->proplink.'","'.$imagePro.'",'.$item->stype.',"'.$item->yearbuilt.'","'.$item->city.'","'.$item->statename.'"]);';	
	}
}
//$_SERVER['REMOTE_ADDR']
$ip = '68.178.213.203';
$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
$keyword = JRequest::getvar('filter_keyword');
//$this->results = '';
if(empty($this->results)){
	//$this->results = $this->results;
	$this->results = $details->region;
} else {
	$this->results = $this->results;
}
$prepAddr = str_replace(' ','+',$this->results);
$geocode = @file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
$output = @json_decode($geocode);
$latitude = @$output->results[0]->geometry->location->lat;
$longitude = @$output->results[0]->geometry->location->lng;

//before this code for lattitude and longitude center(120920165)
//var location = new google.maps.LatLng('.$this->settings->adv_default_lat.','.$this->settings->adv_default_long.');
//this code fopr zoom echo $this->settings->adv_default_zoom;
//before this code for max-zoom->maxZoom:$this->settings->max_zoom(13092016)

$map_js .='
jQuery(function($) {
    $(window).load(function(){ 
    	var infoWindows = [];
    	var width = $("#ip_mainheader").css("width");
        var height = '.$this->params->get('map_height', 300).';
        var location = new google.maps.LatLng('.$latitude.','.$longitude.');
    	var mapoptions = {
            zoom: '.$this->settings->adv_default_zoom.',
            center: location,
            scaleControl: false,
            scrollwheel: false,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        if(locations.length){
			var bounds = new google.maps.LatLngBounds();
			$("#ip-map-canvas").css({"width": width, "height": height});
			var filters = $("#filter_keyword").val();
			var map = new google.maps.Map(document.getElementById("ip-map-canvas"), mapoptions);
			google.maps.event.trigger(map, "resize");
			map.setCenter(location);

			$.each(locations, function(i, el){
                if (el[0] == 0 || el[1] == 0) return;
				var position = new google.maps.LatLng(el[0],el[1]);
				if(filters == ""){
					bounds.extend(position);
				} else {
					bounds.extend(position);
				}
				map.fitBounds(bounds);

				var marker = new google.maps.Marker({
					position: position,
				    scaleControl: false,
				    scrollwheel: false,
					map: map,
					draggable: false,
					icon: el[3]
				});

				if(el[6] == 1){ el[6] = \'For Sale\'; }
		        if(el[6] == 2){ el[6]= \'For Lease\'; }
		        if(el[6] == 3){ el[6] = \'For Sale or Lease\'; }
		        if(el[6] == 4){ el[6] = \'For Rent\'; }
		        if(el[6] == 5){ el[6] = \'Sold\'; }
		        if(el[6] == 6){ el[6] = \'Pending\'; }

		        var contentString =\'<div class="row-fluid ip-bubble-window">\' +
                    \'<div class="ip-overview-img 123"><div class="ip-property-thumb-holder"><div class="span6"><a href="\'+el[4]+\'"><img src=\'+el[5]+\'></a></div><div class="span6"><strong>Sale Type: </strong><span>\'
                    +el[6]+\'</span><br/><strong>Yearbuilt: </strong><span>\'+el[7]+
                    \'</span><br/><strong>Price: </strong>\'+el[7]+
                    \'<br/><strong>City: </strong><span>\'+el[8]
                    +\'</span><br/><strong>State: </strong><span>\'+el[9]+\'</span><br/></div></div></div>\';

                var infoWindow = new google.maps.InfoWindow(), marker, i;

                google.maps.event.addListener(marker, \'mouseover\', (function(marker, i) {
		            return function() {
		                console.log(infoWindows);

		                for (var i=0;i<infoWindows.length;i++) {
		                	infoWindows[i].close();
					  	}

					  	infoWindows.push(infoWindow);
		                infoWindow.setContent(contentString);
		                infoWindow.open(map, this);
		            }
		        })(marker, i));

			});
		}
    });
})';

$this->document->addScriptDeclaration( $map_js );
echo '
    <div id="ip-map-canvas" class="ip-map-div"></div>
    <div class="clearfix"></div>
    <style>
    	.ip-property-thumb-holder div.span6 a img {
    width: 100%;
    height: 190px;

    </style>
    ';
?>
