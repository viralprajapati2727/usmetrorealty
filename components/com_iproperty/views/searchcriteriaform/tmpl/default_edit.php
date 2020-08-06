<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', '.ipform select');
JHtml::script(Juri::base() . 'media/system/js/jquery.formatCurrency.js');
$data=$this->result;

//getamenities
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('amen_id');
$query->from($db->quoteName('#__iproperty_searchcritmid'));
$query->where($db->quoteName('criteria_id')." = ".JRequest::getInt('id'));
$db->setQuery($query);
$ame_results = $db->loadObjectList();
//var_dump($ame_results);
$selected_amenities = array();
foreach ($ame_results as $ame) {
    array_push($selected_amenities, $ame->amen_id);
}
//getamenities
?>
<script type="text/javascript">

jQuery(document).ready(function(){

    Joomla.submitbutton = function(task) {
        if(document.formvalidator.isValid(document.id('adminForm'))){
            if(jQuery('#jform_country').val() == '' || jQuery('#jform_country').val() == null || jQuery('#jform_country').val() == undefined){
            jQuery('#jform_country').removeClass('invalid');
            jQuery('#jform_country-lbl').removeClass('invalid');
            jQuery('.jform_country_message').remove();

            jQuery('#jform_country').addClass('invalid');
            jQuery('#jform_country-lbl').addClass('invalid');
            jQuery('#jform_country_chzn').after('<span class="jform_country_message invalid">Please select Country/Countries</span>');
            var datatabe = jQuery("#jform_country").attr("data-tab");
            jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                jQuery( "div#"+datatabe ).removeClass( "active" );
                jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                jQuery( "#ip-propviewContent div" ).removeClass( "active" );
            jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
            jQuery( "div#"+datatabe ).addClass( "active" );
            //console.log(jQuery('#jform_country').val());
            return false;
        } else {
            jQuery('#jform_country').removeClass('invalid');
            jQuery('#jform_country-lbl').removeClass('invalid');
            jQuery('.jform_country_message').remove();

           if(jQuery('#jform_locstate').val() == '' || jQuery('#jform_locstate').val() == null || jQuery('#jform_locstate').val() == undefined){
                jQuery('#jform_locstate').removeClass('invalid');
                jQuery('#jform_locstate-lbl').removeClass('invalid');
                jQuery('.jform_country_message').remove();

               jQuery('#jform_locstate').addClass('invalid');
               jQuery('#jform_locstate-lbl').addClass('invalid');
               jQuery('#jform_locstate_chzn').after('<span class="jform_country_message invalid">Please select State/States</span>');
               var datatabe = jQuery("#jform_locstate").attr("data-tab");
               jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                jQuery( "div#"+datatabe ).removeClass( "active" );
                jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                jQuery( "#ip-propviewContent div" ).removeClass( "active" );
               jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
               jQuery( "div#"+datatabe ).addClass( "active" );
                return false;
            } else {
                jQuery('#jform_locstate').removeClass('invalid');
                jQuery('#jform_locstate-lbl').removeClass('invalid');
                jQuery('.jform_country_message').remove();

               if(jQuery('#jform_city').val() == '' || jQuery('#jform_city').val() == null || jQuery('#jform_city').val() == undefined){
                jQuery('#jform_city').removeClass('invalid');
                jQuery('#jform_city-lbl').removeClass('invalid');
                jQuery('.jform_city_message').remove();

                jQuery('#jform_city').addClass('invalid');
                jQuery('#jform_city-lbl').addClass('invalid');
                jQuery('#jform_city_chzn').after('<span class="jform_country_message invalid">Please select Cities/City</span>');

                var datatabe = jQuery("#jform_city").attr("data-tab");
                jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                jQuery( "div#"+datatabe ).removeClass( "active" );
                jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                jQuery( "div#"+datatabe ).addClass( "active" );
                return false;
               }    
            }
        }
            
            if(jQuery( "#jform_beds" ).val() != ''){
                    var regex = /^[0-9]+$/;   
                    if(!regex.test(jQuery( "#jform_beds" ).val())){
                        var bed_id = jQuery("#jform_beds").attr('id');
                        var bed_value = jQuery( "#jform_beds" ).val();
                        //console.log(kitchen_value);
                        checkInt(bed_id, bed_value);
                        var datatabe = jQuery("#"+bed_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                        jQuery( "div#"+datatabe ).addClass( "active" );
                        return false;
                    }
            }
            if(jQuery( "#jform_baths" ).val() != ''){
                    var regex = /^[0-9]+$/;   
                    if(!regex.test(jQuery( "#jform_baths" ).val())){
                        var bath_id = jQuery("#jform_baths").attr('id');
                        var bath_value = jQuery( "#jform_baths" ).val();
                        console.log(bath_value);
                        checkInt(bath_id, bath_value);

                        var datatabe = jQuery("#"+bed_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                        jQuery( "div#"+datatabe ).addClass( "active" );
                        return false;
                    }
            }
            if(jQuery( "#jform_kitchen" ).val() != ''){
                    var regex = /^[a-z]+$/;   
                    if(!regex.test(jQuery( "#jform_kitchen" ).val())){
                        var kitchen_id = jQuery("#jform_kitchen").attr('id');
                        var kitchen_value = jQuery( "#jform_kitchen" ).val();
                        //console.log(kitchen_value);
                        checkAlpha(kitchen_id, kitchen_value);

                        var datatabe = jQuery("#"+kitchen_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                        jQuery( "div#"+datatabe ).addClass( "active" );
                        return false;
                    }
            }
            if(jQuery( "#jform_garage" ).val() != ''){
                    var regex = /^[0-9]+$/;   
                    if(!regex.test(jQuery( "#jform_garage" ).val())){
                        var garage_id = jQuery("#jform_garage").attr('id');
                        var garage_value = jQuery("#jform_garage").val();
                        console.log(garage_value);
                        checkInt(garage_id, garage_value);

                        var datatabe = jQuery("#"+garage_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                        jQuery( "div#"+datatabe ).addClass( "active" );
                        return false;
                    }
            }
            Joomla.submitform(task);
        } else {
            var fields, invalid = [], valid = true, label, error, i, l;
        fields = jQuery('form.form-validate').find('input, textarea, select');
        //console.log(fields);
        if (!document.formvalidator.isValid(jQuery('form'))) {
            for (i = 0, l = fields.length; i < l; i++) {
                if (document.formvalidator.validate(fields[i]) === false) {
                    valid = false;
                    invalid.push(fields[i]);
                }
            }

            // Run custom form validators if present
            jQuery.each(document.formvalidator.custom, function (key, validator) {
                if (validator.exec() !== true) {
                    valid = false;
                }
            });

            if (!valid && invalid.length > 0) {
                error = {"error": []};

                for (i = invalid.length - 2; i >= 0; i--) {
                    //console.log(i);
                    // console.log(invalid[i]);
                    //label = jQuery.trim($(invalid[i]).data("id").text().replace("*", "").toString());
                    var inputId = jQuery(invalid[i]).attr("id");
                    //console.log(inputId);
                    if (inputId) {
                        if(inputId === 'jform_title') {                            
                            error.error.push('Please Enter Title');
                        }
                        if(inputId === 'jform_minprice' || inputId === 'jform_maxprice') {
                            error.error.push('Please Enter Max-Min price');
                        }
                    }
                }
            }
            Joomla.renderMessages(error);
        }
            //window.location.href+="#propgeneral";
            jQuery('#ip-propviewTabs li:first a').trigger('click');
        }
    }

    jQuery("#jform_minprice").formatCurrency();
    jQuery("#jform_maxprice").formatCurrency();

    jQuery("#jform_minprice").blur(function () {
        jQuery("#jform_minprice").formatCurrency();
    });

    jQuery("#jform_maxprice").blur(function () {
        jQuery("#jform_maxprice").formatCurrency();
    });

    jQuery( "#jform_country" ).change(function() { 
        console.log(jQuery(this).val());
        var countryArr = jQuery(this).val().filter(function(v){return v!==''});
        console.log(countryArr);
        //console.log(window.location);return false;
        loadStates(countryArr);
    });


var lia = jQuery('#ip-propviewTabs li a');
    console.log(lia);
    jQuery.each( lia, function( key, value ) {
      console.log( lia[key] );
      var the_href = jQuery(lia[key]).attr('href').substring(1);
      console.log( the_href );
      console.log(key);
      cuskey = key+1;
      jQuery( "#ip-propviewTabs li:nth-child("+cuskey+")" ).addClass(the_href);

      jQuery('#'+the_href+' input').attr('data-tab', the_href);
      jQuery('#'+the_href+' select').attr('data-tab', the_href);
    });
    
//remove state 
/*jQuery( ".search-choice-close" ).click(function() {
  jQuery( this+"li" ).remove();
});*/
jQuery(".chosen-select").chosen({disable_search_threshold: 10});
///
    function loadStates(val){

        jQuery.ajax({
            type:"GET",
            url : "index.php?option=com_iproperty&task=searchcriteriaform.getStates",
            data : "countries="+val,
            async: false,
            success : function(data) {
               var obj = JSON.parse(data);

                    jQuery(".region").html("<option value=''>Select States</option>");
                    jQuery.each(obj, function(i, m){
                        
                        jQuery(".region").append("<option value='"+m.value+"'>"+m.text+"</option>").trigger( "liszt:updated" );
                        
                    });
             
            },
            error: function() {
                alert('Error occured');
            }
        });
    }

    jQuery( "#jform_locstate" ).change(function() {
        //console.log(window.location);return false;
        if(jQuery(this).val()){
            var stateArr = jQuery(this).val().filter(function(v){return v!==''});
            console.log(stateArr);
            //console.log(window.location);return false;
            loadCities(stateArr);
        }
    });

    function loadCities(val){

        jQuery.ajax({
            type:"GET",
            url : "index.php?option=com_iproperty&task=searchcriteriaform.getCities",
            data : "states="+val,
            async: false,
            success : function(data) {
               var obj = JSON.parse(data);

                    jQuery(".city").html("<option value=''>Select City</option>");
                    jQuery.each(obj, function(i, m){
                        
                        jQuery(".city").append("<option value='"+m.value+"'>"+m.text+"</option>").trigger( "liszt:updated" );
                        
                    });

             
            },
            error: function() {
                alert('Error occured');
            }
        });
    }

    /*jQuery('.checkint').on('blur', function(){
        //console.log('sdfsdfgdfgdf');
        checkInt(this.id, this.value);
    });*/
    //
    var lia = jQuery('#ip-propviewTabs li a');
    console.log(lia);
    jQuery.each( lia, function( key, value ) {
      console.log( lia[key] );
      var the_href = jQuery(lia[key]).attr('href').substring(1);
      console.log( the_href );
      console.log(key);
      cuskey = key+1;
      jQuery( "#ip-propviewTabs li:nth-child("+cuskey+")" ).addClass(the_href);

      jQuery('#'+the_href+' input').attr('data-tab', the_href);
      jQuery('#'+the_href+' select').attr('data-tab', the_href);
    });
    //
    
    function checkInt(fieldid, fieldval){
        if (fieldval.match(/[^\d\.]/g)) {
            //console.log('false');
            jQuery('#int_val').remove();
            jQuery('#'+fieldid).val('');
            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid" id="int_val">Only Interger is Valid</span>');
            //jQuery('#'+fieldid).attr('placeholder','Only Interger is Valid');
        } else {
            //console.log('true');
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        }
    }

    function checkAlpha(fieldid, fieldval){
        if (fieldval.match(/^[0-9]+$/)) {
            //console.log('false');
            jQuery('#int_val').remove();
            jQuery('#'+fieldid).val('');
            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid" id="int_val">Only Interger is Valid</span>');
            //jQuery('#'+fieldid).attr('placeholder','Only Alpha character is Valid');
        } else {
            //console.log('true');
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        }
    }

    /*jQuery('#ip-propviewTabs li a').click(function(){
        alert('You will lose all data that you entered until you send them by clicking SEND button !!');
    });*/
});
</script>

<?php echo $this->loadTemplate('toolbar'); ?>
<h4 class="search-criteria-header"><?php echo $this->msg; ?></h4>

<form action="index.php?option=com_iproperty&view=searchcriteriaform" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal" enctype="multipart/form-data">
        <div class="btn-toolbar" id="btns_bar">
            <div class="btn-group">

                <button type="button" class="btn" value="update" name="update" onclick="Joomla.submitbutton('SearchcriteriaForm.update')">
                    <i class="icon-ok"></i><?php echo JText::_('JSAVE') ?>
                </button>
                <button type="button" class="btn" value="cancel" name="save" onclick="window.location.href='index.php?option=com_iproperty&view=ipuser&Itemid=319&tab=searchcriteria';">
                    <i class="icon-cancel"></i> <?php echo JText::_('JCANCEL') ?>
                </button>
            </div>
       </div>
        <?php 
        echo JHtmlBootstrap::startTabSet('ip-propview', array('active' => 'propgeneral'));
        echo JHtmlBootstrap::addTab('ip-propview', 'propgeneral', JText::_('COM_IPROPERTY_DESCRIPTION')); ?>
        <fieldset>
                <legend><?php echo JText::_('COM_IPROPERTY_BASIC_DETAILS'); ?></legend>
                <div class="control-group">
                    <div class="control-label">
                        <label class="required" for="jform_title" id="jform_title-lbl">Title<span class="star">&nbsp;*</span></label>
                    </div>
                    <div class="controls">
                        <input type="text" aria-required="true" required="" size="50" class="inputbox required" value="<?php echo $data->title; ?>" id="jform_title" name="jform[title]">
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label class="" for="jform_description" id="jform_description-lbl"><?php echo JText::_('COM_IPROPERTY_DESC'); ?></label>
                    </div>
                    <div class="controls">
                        <textarea rows="50" cols="10" id="jform_description" name="jform[description]"><?php echo $data->description; ?></textarea>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                       <label>Home Type</label>
                    </div>
                    <div class="controls">
                     <input type="hidden" name="jform[buyer_id]" value="<?php echo $this->ipauth->getAgentId(); ?>" />
                     <?php //var_dump($data); ?>
                     <select id="jform_hometype" name="jform[hometype]" class="inputbox required" size="1" required="" aria-required="true">
                        <option value="">Sale Type</option>
                        <option <?php if($data->hometype == "2") { echo 'selected="selected"'; } ?>value="2">For Lease</option>
                        <option <?php if($data->hometype == "4") { echo 'selected="selected"'; } ?> value="4">For Rent</option>
                        <option <?php if($data->hometype == "1") { echo 'selected="selected"'; } ?> value="1">For Sale</option>
                        <option <?php if($data->hometype == "3") { echo 'selected="selected"'; } ?> value="3">For Sale or Lease</option>
                        <option <?php if($data->hometype == "6") { echo 'selected="selected"'; } ?> value="6">Pending</option>
                        <option <?php if($data->hometype == "5") { echo 'selected="selected"'; } ?> value="5">Sold</option>
                    </select>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label>Min-Price to Max-Price</label>
                    </div>
                    <div class="controls">
                         <input type="text" name="jform[minprice]" id="jform_minprice" class="inputbox" required="required" size="50" value="<?php echo $data->minprice?>">
                        &nbsp;<?php echo "To"; ?>&nbsp;
                         <input type="text" name="jform[maxprice]" required="required" id="jform_maxprice" class="inputbox" size="50" value="<?php echo $data->maxprice?>">
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label>Country</label>
                    </div>
                    <div class="controls">
                        <!--<input type="text" name="jform[city]" class="inputbox" required="required" size="50" value="<?php echo $data->city?>">-->
                        <?php $countries = explode(',', $data->country); /*echo "<pre>"; print_r($locstates); echo '</pre>'; */ ?>
                        <select  multiple="" size="50" class="inputbox chzn-done" name="jform[country][]" id="jform_country">
                            <option value="">Select Countries</option>
                            <?php foreach ($this->Countries as $value) { ?> 
                               <option <?php if(in_array($value->id, $countries)) { echo 'selected="selected"'; } ?> value="<?php echo $value->id;?>"><?php echo $value->title; ?></option>
                            <?php } ?>
                        </select>

                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                       <label>State</label>
                    </div>
                    <div class="controls">
                        <?php $locstates = explode(',', $data->locstate); /*echo "<pre>"; print_r($locstates); echo '</pre>'; */ ?>
                        <select aria-required="true" multiple="" size="10" class="inputbox region" name="jform[locstate][]" id="jform_locstate">
                        <!--<select id="jform_locstate" name="jform[locstate][]" class="inputbox required" size="1" required="" aria-required="true" multiple="">-->
                                <option value="">Select States</option>
                                <?php foreach ($this->States as $value) { ?> 
                                   <option <?php if(in_array($value->id, $locstates)) { echo 'selected="selected"'; } ?> value="<?php echo $value->id;?>"><?php echo $value->title; ?></option>
                                <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label>City</label>
                    </div>
                    <div class="controls">
                        <!--<input type="text" name="jform[city]" class="inputbox" required="required" size="50" value="<?php echo $data->city?>">-->
                        <?php $cities = explode(',', $data->city); /*echo "<pre>"; print_r($locstates); echo '</pre>'; */ ?>
                        <select aria-required="true" multiple="" size="50" class="inputbox city chzn-done" name="jform[city][]" id="jform_city">
                            <option value="">Select Cities</option>
                            <?php foreach ($this->Cities as $value) { ?> 
                               <option <?php if(in_array($value->id, $cities)) { echo 'selected="selected"'; } ?> value="<?php echo $value->id;?>"><?php echo $value->title; ?></option>
                            <?php } ?>
                        </select>

                    </div>
                </div>
            </fieldset>
            <?php
        echo JHtmlBootstrap::endTab();
        echo JHtmlBootstrap::addTab('ip-propview', 'propdetails', JText::_('COM_IPROPERTY_DETAILS'));
        ?>
            <fieldset>
                <legend><?php echo JText::_('COM_IPROPERTY_DETAILS'); ?></legend>
                <div class="row-fluid">
                    <div class="span4 pull-left form-vertical">
                        <div class="control-group">
                            <div class="control-label">
                                <label class="" for="jform_beds" id="jform_beds-lbl"><?php echo JText::_('COM_IPROPERTY_BEDS_DESIRED'); ?></label>
                            </div>
                            <div class="controls">
                                <!-- <select id="jform_beds" name="jform[beds]" class="chzn-done">
                                    <option value="" selected="selected">Beds</option>
                                    <option <?php if($data->beds == "0") { echo 'selected="selected"'; } ?> value="0">0</option>
                                    <option <?php if($data->beds == "1") { echo 'selected="selected"'; } ?> value="1">1</option>
                                    <option <?php if($data->beds == "2") { echo 'selected="selected"'; } ?> value="2">2</option>
                                    <option <?php if($data->beds == "3") { echo 'selected="selected"'; } ?> value="3">3</option>
                                    <option <?php if($data->beds == "4") { echo 'selected="selected"'; } ?> value="4">4</option>
                                    <option <?php if($data->beds == "5") { echo 'selected="selected"'; } ?> value="5">5</option>
                                    <option <?php if($data->beds == "6") { echo 'selected="selected"'; } ?> value="6">6</option>
                                    <option <?php if($data->beds == "7") { echo 'selected="selected"'; } ?> value="7">7</option>
                                    <option <?php if($data->beds == "8") { echo 'selected="selected"'; } ?> value="8">8</option>
                                    <option <?php if($data->beds == "9") { echo 'selected="selected"'; } ?> value="9">9</option>
                                    <option <?php if($data->beds == "10") { echo 'selected="selected"'; } ?> value="10">10</option>
                                </select> -->
                                <input type="text" id="jform_beds" name="jform[beds]" class="checkint" value="<?php echo $data->beds; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label class="" for="jform_baths" id="jform_baths-lbl"><?php echo JText::_('COM_IPROPERTY_BATHS_DESIRED'); ?></label>
                            </div>
                            <div class="controls">
                               <!-- <select id="jform_baths" name="jform[baths]" class="chzn-done">
                                    <option value="" selected="selected"><?php echo JText::_('COM_IPROPERTY_BATHS'); ?></option>
                                    <option <?php if($data->baths == "0.00") { echo 'selected="selected"'; } ?> value="0">0</option>
                                    <option <?php if($data->baths == "0.25") { echo 'selected="selected"'; } ?> value="0.25">0.25</option>
                                    <option <?php if($data->baths == "0.50") { echo 'selected="selected"'; } ?> value="0.5">0.5</option>
                                    <option <?php if($data->baths == "0.75") { echo 'selected="selected"'; } ?> value="0.75">0.75</option>
                                    <option <?php if($data->baths == "1.00") { echo 'selected="selected"'; } ?> value="1">1</option>
                                    <option <?php if($data->baths == "1.25") { echo 'selected="selected"'; } ?> value="1.25">1.25</option>
                                    <option <?php if($data->baths == "1.50") { echo 'selected="selected"'; } ?> value="1.5">1.5</option>
                                    <option <?php if($data->baths == "1.75") { echo 'selected="selected"'; } ?> value="1.75">1.75</option>
                                    <option <?php if($data->baths == "2.00") { echo 'selected="selected"'; } ?> value="2">2</option>
                                    <option <?php if($data->baths == "2.25") { echo 'selected="selected"'; } ?> value="2.25">2.25</option>
                                    <option <?php if($data->baths == "2.50") { echo 'selected="selected"'; } ?> value="2.5">2.5</option>
                                    <option <?php if($data->baths == "2.75") { echo 'selected="selected"'; } ?> value="2.75">2.75</option>
                                    <option <?php if($data->baths == "3.00") { echo 'selected="selected"'; } ?> value="3">3</option>
                                    <option <?php if($data->baths == "3.25") { echo 'selected="selected"'; } ?> value="3.25">3.25</option>
                                    <option <?php if($data->baths == "3.50") { echo 'selected="selected"'; } ?> value="3.5">3.5</option>
                                    <option <?php if($data->baths == "3.75") { echo 'selected="selected"'; } ?> value="3.75">3.75</option>
                                    <option <?php if($data->baths == "4.00") { echo 'selected="selected"'; } ?> value="4">4</option>
                                    <option <?php if($data->baths == "4.25") { echo 'selected="selected"'; } ?> value="4.25">4.25</option>
                                    <option <?php if($data->baths == "4.50") { echo 'selected="selected"'; } ?> value="4.5">4.5</option>
                                    <option <?php if($data->baths == "4.75") { echo 'selected="selected"'; } ?> value="4.75">4.75</option>
                                    <option <?php if($data->baths == "5.00") { echo 'selected="selected"'; } ?> value="5">5</option>
                                    <option <?php if($data->baths == "5.25") { echo 'selected="selected"'; } ?> value="5.25">5.25</option>
                                    <option <?php if($data->baths == "5.50") { echo 'selected="selected"'; } ?> value="5.5">5.5</option>
                                    <option <?php if($data->baths == "5.75") { echo 'selected="selected"'; } ?> value="5.75">5.75</option>
                                    <option <?php if($data->baths == "6.00") { echo 'selected="selected"'; } ?> value="6">6</option>
                                    <option <?php if($data->baths == "6.25") { echo 'selected="selected"'; } ?> value="6.25">6.25</option>
                                    <option <?php if($data->baths == "6.50") { echo 'selected="selected"'; } ?> value="6.5">6.5</option>
                                    <option <?php if($data->baths == "6.75") { echo 'selected="selected"'; } ?> value="6.75">6.75</option>
                                    <option <?php if($data->baths == "7.00") { echo 'selected="selected"'; } ?> value="7">7</option>
                                    <option <?php if($data->baths == "7.25") { echo 'selected="selected"'; } ?> value="7.25">7.25</option>
                                    <option <?php if($data->baths == "7.50") { echo 'selected="selected"'; } ?> value="7.5">7.5</option>
                                    <option <?php if($data->baths == "7.75") { echo 'selected="selected"'; } ?> value="7.75">7.75</option>
                                    <option <?php if($data->baths == "8.00") { echo 'selected="selected"'; } ?> value="8">8</option>
                                    <option <?php if($data->baths == "8.25") { echo 'selected="selected"'; } ?> value="8.25">8.25</option>
                                    <option <?php if($data->baths == "8.50") { echo 'selected="selected"'; } ?> value="8.5">8.5</option>
                                    <option <?php if($data->baths == "8.75") { echo 'selected="selected"'; } ?> value="8.75">8.75</option>
                                    <option <?php if($data->baths == "9.00") { echo 'selected="selected"'; } ?> value="9">9</option>
                                    <option <?php if($data->baths == "9.25") { echo 'selected="selected"'; } ?> value="9.25">9.25</option>
                                    <option <?php if($data->baths == "9.50") { echo 'selected="selected"'; } ?> value="9.5">9.5</option>
                                    <option <?php if($data->baths == "9.75") { echo 'selected="selected"'; } ?> value="9.75">9.75</option>
                                    <option <?php if($data->baths == "10.00") { echo 'selected="selected"'; } ?> value="10">10</option>
                                </select> -->
                                <input type="text" id="jform_baths" name="jform[baths]" class="checkint" value="<?php echo $data->baths; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Total-Units</label>
                            </div>
                            <div class="controls">
                                <select id="jform_total_units" name="jform[total_units'" class="chzn-done">
                                    <option value="1">1</option>
                                    <option <?php if($data->total_units == "2") { echo 'selected="selected"'; } ?> value="2">2</option>
                                    <option <?php if($data->total_units == "3") { echo 'selected="selected"'; } ?> value="3">3</option>
                                    <option <?php if($data->total_units == "4") { echo 'selected="selected"'; } ?> value="4">4</option>
                                    <option <?php if($data->total_units == "5") { echo 'selected="selected"'; } ?> value="5">5</option>
                                    <option <?php if($data->total_units == "6") { echo 'selected="selected"'; } ?> value="6">6</option>
                                    <option <?php if($data->total_units == "7") { echo 'selected="selected"'; } ?> value="7">7</option>
                                    <option <?php if($data->total_units == "8") { echo 'selected="selected"'; } ?> value="8">8</option>
                                    <option <?php if($data->total_units == "9") { echo 'selected="selected"'; } ?> value="9">9</option>
                                    <option <?php if($data->total_units == "10") { echo 'selected="selected"'; } ?> value="10">10</option>
                                </select>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <label class="" for="jform_sleeps" id="jform_sleeps-lbl">Sleeps</label>
                            </div>
                            <div class="controls">
                                <input type="number" min="0" step="1" max="50" id="jform_sleeps" name="jform[sleeps]" value="<?php echo $data->sleeps; ?>">
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <label class="" for="jform_kitchen" id="jform_kitchen-lbl"><?php echo JText::_('COM_IPROPERTY_KITCHEN_DESIRED'); ?></label>
                            </div>
                            <div class="controls">
                                <input type="text" id="jform_kitchen" class="checkint" name="jform[kitchen]" value="<?php echo $data->kitchen; ?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label><?php echo JText::_('COM_IPROPERTY_SQFT_DESIRED'); ?></label>
                            </div>
                            <div class="controls">
                                <input type="text" name="jform[sqft]" class="inputbox checkint" size="50" value="<?php echo $data->sqft?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Lotsize</label>
                            </div>
                            <div class="controls">
                                <input type="text" name="jform[lotsize]" class="inputbox" size="50" value="<?php echo $data->lotsize?>">
                            </div>
                        </div>
                        
                    </div>
                   <div class="span4 pull-right form-vertical">
                        <div class="control-group">
                            <div class="control-label">
                               <label>Lot-Acres</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[lot_acres]" class="inputbox" size="50" value="<?php echo $data->lot_acres?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Lot-Type</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[lot_type]" class="inputbox" size="50" value="<?php echo $data->lot_type?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Heat</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[heat]" class="inputbox" size="50" value="<?php echo $data->heat?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label>Cool</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[cool]" class="inputbox" size="50" value="<?php echo $data->cool?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Fuel</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[fuel]" class="inputbox" size="50" value="<?php echo $data->fuel?>">
                                <?php //echo $this->form->getInput('fuel'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <label>Garage-Type</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[garage_type]" class="inputbox" size="50" value="<?php echo $data->garage_type?>">
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <label id="jform_garage-lbl" name="jform[garage_size]"><?php echo JText::_('COM_IPROPERTY_GARAGE_SIZE'); ?></label>
                            </div>
                            <div class="controls">
                            <input type="text" id="jform_garage" name="jform[garage_size]" class="inputbox" size="50" value="<?php echo $data->garage_size?>">
                            </div>
                        </div>
                         <div class="control-group">
                            <div class="control-label">
                                <label>Siding</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[siding]" class="inputbox" size="50" value="<?php echo $data->siding?>">
                            </div>
                        </div>
                        
                    </div>
                    <div class="span4 pull-right form-vertical">
                           
                        <div class="control-group">
                            <div class="control-label">
                                <label><?php echo JText::_('COM_IPROPERTY_ROOF_DESIRED'); ?></label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[roof]" class="inputbox" size="50" value="<?php echo $data->roof?>">
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <label>Reception</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[reception]" class="inputbox" size="50" value="<?php echo $data->reception?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label>Tax</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[tax]" class="inputbox" size="50" value="<?php echo $data->tax?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                               <label>Income</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[income]" class="inputbox" size="50" value="<?php echo $data->income?>">
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <label><?php echo JText::_('COM_IPROPERTY_YEAR_BUILT'); ?></label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[yearbuilt]" class="inputbox" size="50" value="<?php echo $data->yearbuilt?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>Zoning</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[zoning]" class="inputbox" size="50" value="<?php echo $data->zoning?>">
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <label>Propview</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[propview]" class="inputbox" size="50" value="<?php echo $data->propview?>">
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                               <label>School-district</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[school_district]" class="inputbox" size="50" value="<?php echo $data->school_district?>">
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>House Style</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[style]" class="inputbox" size="50" value="<?php echo $data->style?>">
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <label class="" for="jform_shared_own" id="jform_shared_own-lbl">Shared Own</label>
                            </div>
                            <div class="controls">
                                <fieldset class="btn-group btn-group-yesno radio" id="jform_shared_own">
                                    <input type="radio" <?php if($data->shared_own == "1"){ echo 'checked="checked"'; } ?> value="1" name="jform[shared_own]" id="jform_shared_own0">
                                    <label for="jform_shared_own0">Yes</label>
                                    <input type="radio" <?php if($data->shared_own == "0"){ echo 'checked="checked"'; } ?> value="0" name="jform[shared_own]" id="jform_shared_own1">
                                    <label for="jform_shared_own1">No</label>
                                </fieldset>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label class="" for="jform_lease_hold" id="jform_lease_hold-lbl">Lease Hold</label>
                            </div>
                            <div class="controls">
                                <fieldset class="btn-group btn-group-yesno radio" id="jform_lease_hold">
                                    <input type="radio" <?php if($data->lease_hold == "1"){ echo 'checked="checked"'; } ?> value="1" name="jform[lease_hold]" id="jform_lease_hold0">
                                    <label for="jform_lease_hold0">Yes</label>
                                    <input type="radio" <?php if($data->lease_hold == "0"){ echo 'checked="checked"'; } ?> value="0" name="jform[lease_hold]" id="jform_lease_hold1">
                                    <label for="jform_lease_hold1">No</label>
                                </fieldset>
                            </div>
                        </div> -->
                        <!-- <div class="control-group">
                            <div class="control-label">
                               <label>Frontage</label>
                            </div>
                            <div class="controls">
                            <input type="text" name="jform[frontage]" class="inputbox" size="50" value="<?php echo $data->frontage?>">
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <label>Bank Owned or Short-Sale</label>
                            </div>
                            <div class="controls">
                                <div class="btn-group btn-group-yesno radio span10" id="jform_reo">
                                <div class="span5">
                                    <input type="radio" <?php if($data->reo == "1"){ echo 'checked="checked"'; } ?> value="1" name="jform[reo]" id="jform_reo0">
                                    <label for="jform_reo0">Yes</label>
                                    </div>
                                    <div class="span5">
                                    <input type="radio" <?php if($data->reo == "0"){ echo 'checked="checked"'; } ?> value="0" name="jform[reo]" id="jform_reo1">
                                    <label for="jform_reo1">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <label>HOA</label>
                            </div>
                            <div class="controls">
                                <div class="btn-group btn-group-yesno radio span10" id="jform_hoa">
                                <div class="span5">
                                    <input type="radio" <?php if($data->hoa == "1"){ echo 'checked="checked"'; } ?> value="1" name="jform[hoa]" id="jform_hoa0">
                                    <label for="jform_hoa0">Yes</label>
                                    </div>
                                    <div class="span5">
                                    <input type="radio" <?php if($data->hoa == "0"){ echo 'checked="checked"'; } ?> value="0" name="jform[hoa]" id="jform_hoa1">
                                    <label for="jform_hoa1">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            <?php
            echo JHtmlBootstrap::endTab();
            echo JHtmlBootstrap::addTab('ip-propview', 'propamens', JText::_('COM_IPROPERTY_AMENITIES'));
            ?>
            
            <div class="row-fluid">
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-amenities">
                            <h4 id="jform_general_amen_header-lbl" class="visible-first visible">General Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_general_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_general_amens0" name="jform[general_amens][]" <?php if(in_array("14", $selected_amenities)){ echo 'checked="checked"'; } ?> value="14" class="inputbox" type="checkbox"> Cable Internet
                            </label>
                            <label class="checkbox">
                                <input id="jform_general_amens1" name="jform[general_amens][]" <?php if(in_array("13", $selected_amenities)){ echo 'checked="checked"'; } ?> value="13" class="inputbox" type="checkbox"> Cable TV
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="80" name="jform[general_amens][]" <?php if(in_array("80", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_general_amens2"> Club House
                            </label>
                            <label class="checkbox">
                                <input id="jform_general_amens3" name="jform[general_amens][]" <?php if(in_array("39", $selected_amenities)){ echo 'checked="checked"'; } ?> value="39" class="inputbox" type="checkbox"> Electric Hot Water
                            </label>
                            <label class="checkbox">
                                <input id="jform_general_amens4" name="jform[general_amens][]" <?php if(in_array("29", $selected_amenities)){ echo 'checked="checked"'; } ?> value="29" class="inputbox" type="checkbox"> Satellite Dish
                            </label>
                            <label class="checkbox">
                                <input id="jform_general_amens5" name="jform[general_amens][]" <?php if(in_array("17", $selected_amenities)){ echo 'checked="checked"'; } ?> value="17" class="inputbox" type="checkbox"> Skylights
                            </label>
                            <label class="checkbox">
                                <input id="jform_general_amens6" name="jform[general_amens][]" <?php if(in_array("43", $selected_amenities)){ echo 'checked="checked"'; } ?> value="43" class="inputbox" type="checkbox"> Water Softener
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-amenities">
                            <h4 id="jform_interior_amen_header-lbl" class="visible-first visible">Interior Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_interior_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_interior_amens0" name="jform[interior_amens][]" <?php if(in_array("9", $selected_amenities)){ echo 'checked="checked"'; } ?> value="9" class="inputbox" type="checkbox"> Carpet Throughout
                            </label>
                            <label class="checkbox">
                                <input id="jform_interior_amens1" name="jform[interior_amens][]" <?php if(in_array("8", $selected_amenities)){ echo 'checked="checked"'; } ?> value="8" class="inputbox" type="checkbox"> Central Air
                            </label>
                            <label class="checkbox">
                                <input id="jform_interior_amens2" name="jform[interior_amens][]" <?php if(in_array("21", $selected_amenities)){ echo 'checked="checked"'; } ?> value="21" class="inputbox" type="checkbox"> Central Vac
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="85" name="jform[interior_amens][]" <?php if(in_array("85", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_interior_amens3"> Furnished
                            </label>
                            <label class="checkbox">
                                <input id="jform_interior_amens4" name="jform[interior_amens][]" <?php if(in_array("16", $selected_amenities)){ echo 'checked="checked"'; } ?> value="16" class="inputbox" type="checkbox"> Jacuzi Tub
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-amenities">
                            <h4 id="jform_exterior_amen_header-lbl" class="visible-first visible">Exterior Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_exterior_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_exterior_amens0" name="jform[exterior_amens][]" <?php if(in_array("32", $selected_amenities)){ echo 'checked="checked"'; } ?> value="32" class="inputbox" type="checkbox"> Boat Slip
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens1" name="jform[exterior_amens][]" <?php if(in_array("31", $selected_amenities)){ echo 'checked="checked"'; } ?> value="31" class="inputbox" type="checkbox"> Covered Patio
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens2" name="jform[exterior_amens][]" <?php if(in_array("33", $selected_amenities)){ echo 'checked="checked"'; } ?> value="33" class="inputbox" type="checkbox"> Exterior Lighting
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens3" name="jform[exterior_amens][]" <?php if(in_array("25", $selected_amenities)){ echo 'checked="checked"'; } ?> value="25" class="inputbox" type="checkbox"> Fence
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens4" name="jform[exterior_amens][]" <?php if(in_array("28", $selected_amenities)){ echo 'checked="checked"'; } ?> value="28" class="inputbox" type="checkbox"> Fruit Trees
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens5" name="jform[exterior_amens][]" <?php if(in_array("4", $selected_amenities)){ echo 'checked="checked"'; } ?> value="4" class="inputbox" type="checkbox"> Garage
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens6" name="jform[exterior_amens][]" <?php if(in_array("35", $selected_amenities)){ echo 'checked="checked"'; } ?> value="35" class="inputbox" type="checkbox"> Gazebo
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens7" name="jform[exterior_amens][]" <?php if(in_array("24", $selected_amenities)){ echo 'checked="checked"'; } ?> value="24" class="inputbox" type="checkbox"> Open Deck
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens8" name="jform[exterior_amens][]" <?php if(in_array("27", $selected_amenities)){ echo 'checked="checked"'; } ?> value="27" class="inputbox" type="checkbox"> Pasture
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens9" name="jform[exterior_amens][]" <?php if(in_array("26", $selected_amenities)){ echo 'checked="checked"'; } ?> value="26" class="inputbox" type="checkbox"> RV Parking
                            </label>
                            <label class="checkbox">
                                <input id="jform_exterior_amens10" name="jform[exterior_amens][]" <?php if(in_array("34", $selected_amenities)){ echo 'checked="checked"'; } ?> value="34" class="inputbox" type="checkbox"> Spa/Hot Tub
                            </label>
                        </ul>
                    </fieldset>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-amenities">
                            <h4 id="jform_accessibility_amen_header-lbl" class="visible-first">Accessibility Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_accessibility_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_accessibility_amens0" name="jform[accessibility_amens][]" <?php if(in_array("19", $selected_amenities)){ echo 'checked="checked"'; } ?> value="19" class="inputbox" type="checkbox"> Han
                                dicap Facilities
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="86" name="jform[accessibility_amens][]" <?php if(in_array("86", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_accessibility_amens1"> Pets Allowed
                            </label>
                            <label class="checkbox">
                                <input id="jform_accessibility_amens2" name="jform[accessibility_amens][]" <?php if(in_array("50", $selected_amenities)){ echo 'checked="checked"'; } ?> value="50" class="inputbox" type="checkbox"> Wheelchair Ramp
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-amenities">
                            <h4 id="jform_green_amen_header-lbl" class="visible-first">Energy Savings Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_green_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_green_amens0" name="jform[green_amens][]" <?php if(in_array("5", $selected_amenities)){ echo 'checked="checked"'; } ?> value="5" class="inputbox" type="checkbox"> Fireplace
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens1" name="jform[green_amens][]" <?php if(in_array("11", $selected_amenities)){ echo 'checked="checked"'; } ?> value="11" class="inputbox" type="checkbox"> Gas Fireplace
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens2" name="jform[green_amens][]" <?php if(in_array("45", $selected_amenities)){ echo 'checked="checked"'; } ?> value="45" class="inputbox" type="checkbox"> Gas Hot Water
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens3" name="jform[green_amens][]" <?php if(in_array("12", $selected_amenities)){ echo 'checked="checked"'; } ?> value="12" class="inputbox" type="checkbox"> Gas Stove
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens4" name="jform[green_amens][]" <?php if(in_array("20", $selected_amenities)){ echo 'checked="checked"'; } ?> value="20" class="inputbox" type="checkbox"> Pellet Stove
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens5" name="jform[green_amens][]" <?php if(in_array("46", $selected_amenities)){ echo 'checked="checked"'; } ?> value="46" class="inputbox" type="checkbox"> Propane Hot Water
                            </label>
                            <label class="checkbox">
                                <input id="jform_green_amens6" name="jform[green_amens][]" <?php if(in_array("15", $selected_amenities)){ echo 'checked="checked"'; } ?> value="15" class="inputbox" type="checkbox"> Wood Stove
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-amenities">
                            <h4 id="jform_security_amen_header-lbl" class="visible-first">Security Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_security_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_security_amens0" name="jform[security_amens][]" <?php if(in_array("18", $selected_amenities)){ echo 'checked="checked"'; } ?> value="18" class="inputbox" type="checkbox"> Burglar Alarm
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="81" name="jform[security_amens][]" <?php if(in_array("81", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_security_amens1"> Concierge
                            </label>
                            <label class="checkbox">
                                <input id="jform_security_amens2" name="jform[security_amens][]" <?php if(in_array("30", $selected_amenities)){ echo 'checked="checked"'; } ?> value="30" class="inputbox" type="checkbox"> Sprinkler System
                            </label>
                        </ul>
                    </fieldset>
                </div>
            </div>
            <div class="row-fluid">
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-amenities">
                            <h4 id="jform_landscape_amen_header-lbl" class="visible-first">Landscape Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_landscape_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_landscape_amens0" name="jform[landscape_amens][]" <?php if(in_array("23", $selected_amenities)){ echo 'checked="checked"'; } ?> value="23" class="inputbox" type="checkbox"> Landscaping
                            </label>
                            <label class="checkbox">
                                <input id="jform_landscape_amens1" name="jform[landscape_amens][]" <?php if(in_array("22", $selected_amenities)){ echo 'checked="checked"'; } ?> value="22" class="inputbox" type="checkbox"> Lawn
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-amenities">
                            <h4 id="jform_community_amen_header-lbl" class="visible-first">Community Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_community_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input id="jform_community_amens0" name="jform[community_amens][]" <?php if(in_array("3", $selected_amenities)){ echo 'checked="checked"'; } ?> value="3" class="inputbox" type="checkbox"> Swimming Pool
                            </label>
                            <label class="checkbox">
                                <input id="jform_community_amens1" name="jform[community_amens][]" <?php if(in_array("1", $selected_amenities)){ echo 'checked="checked"'; } ?> value="1" class="inputbox" type="checkbox"> Tennis Court
                            </label>
                        </ul>
                    </fieldset>
                </div>
                <div class="span4 pull-left">
                    <span class="spacer">
                        <span class="before"></span>
                        <div class="alert alert-amenities">
                            <h4 id="jform_appliance_amen_header-lbl" class="visible-first">Appliance Amenities</h4>
                        </div>
                        <span class="after"></span>
                    </span>
                    <fieldset id="jform_appliance_amens" class="checkboxes inputbox">
                        <ul style="list-style: none;">
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="82" name="jform[appliance_amens][]" <?php if(in_array("82", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_appliance_amens0"> Daily Maid Service
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens0" name="jform[appliance_amens][]" <?php if(in_array("6", $selected_amenities)){ echo 'checked="checked"'; } ?> value="6" class="inputbox" type="checkbox"> Dishwasher
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="83" name="jform[appliance_amens][]" <?php if(in_array("83", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_appliance_amens2"> Double glazing
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens1" name="jform[appliance_amens][]" <?php if(in_array("42", $selected_amenities)){ echo 'checked="checked"'; } ?> value="42" class="inputbox" type="checkbox"> Dryer
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens2" name="jform[appliance_amens][]" <?php if(in_array("49", $selected_amenities)){ echo 'checked="checked"'; } ?> value="49" class="inputbox" type="checkbox"> Freezer
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens3" name="jform[appliance_amens][]" <?php if(in_array("7", $selected_amenities)){ echo 'checked="checked"'; } ?> value="7" class="inputbox" type="checkbox"> Garbage Disposal
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens4" name="jform[appliance_amens][]" <?php if(in_array("47", $selected_amenities)){ echo 'checked="checked"'; } ?> value="47" class="inputbox" type="checkbox"> Grill Top
                            </label>
                            <label class="checkbox">
                                <input type="checkbox" class="inputbox" value="84" name="jform[appliance_amens][]" <?php if(in_array("84", $selected_amenities)){ echo 'checked="checked"'; } ?> id="jform_appliance_amens7"> Heating
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens5" name="jform[appliance_amens][]" <?php if(in_array("40", $selected_amenities)){ echo 'checked="checked"'; } ?> value="40" class="inputbox" type="checkbox"> Microwave
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens6" name="jform[appliance_amens][]" <?php if(in_array("36", $selected_amenities)){ echo 'checked="checked"'; } ?> value="36" class="inputbox" type="checkbox"> Range/Oven
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens7" name="jform[appliance_amens][]" <?php if(in_array("37", $selected_amenities)){ echo 'checked="checked"'; } ?> value="37" class="inputbox" type="checkbox"> Refrigerator
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens8" name="jform[appliance_amens][]" <?php if(in_array("44", $selected_amenities)){ echo 'checked="checked"'; } ?> value="44" class="inputbox" type="checkbox"> RO Combo Gas/Electric
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens9" name="jform[appliance_amens][]" <?php if(in_array("48", $selected_amenities)){ echo 'checked="checked"'; } ?> value="48" class="inputbox" type="checkbox"> Trash Compactor
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens10" name="jform[appliance_amens][]" <?php if(in_array("41", $selected_amenities)){ echo 'checked="checked"'; } ?> value="41" class="inputbox" type="checkbox"> Washer
                            </label>
                            <label class="checkbox">
                                <input id="jform_appliance_amens11" name="jform[appliance_amens][]" <?php if(in_array("10", $selected_amenities)){ echo 'checked="checked"'; } ?> value="10" class="inputbox" type="checkbox"> Washer/Dryer
                            </label>
                        </ul>
                    </fieldset>
                </div>
            </div>
            <?php
            echo JHtmlBootstrap::endTab();
            echo JHtmlBootstrap::addTab('ip-propview', 'propnotes', JText::_('COM_IPROPERTY_NOTES'));
            ?>
                <fieldset>
                    <div class="row-fluid">
                        <div class="span12 pull-left">
                            <label class="" for="jform_notes" id="jform_notes-lbl">Notes</label>
                            <textarea rows="7" cols="40" id="jform_notes" name="jform[notes]"><?php echo $data->notes; ?></textarea>
                        </div>
                    </div>
                </fieldset>
            <?php
            echo JHtmlBootstrap::endTab();
            ?>
            <input type="hidden" name="task" value="searchcriteriaform.update">
            <input type="hidden" name="id" value="<?php echo $data->id?>">

</form>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery(window).scroll(function(){
        var window_top = jQuery(window).scrollTop() + 12; // the "12" should equal the margin-top value for nav.stick
        var div_top = jQuery('#ip-propviewContent').offset().top;
        if (window_top > div_top) {
            jQuery('#btns_bar').addClass('stickybar');
        } else {
            jQuery('#btns_bar').removeClass('stickybar');
        }
    });
});
</script>
<style>#jform_notes{height:auto;width:auto;}
#jform_total_units_chzn{width:220px !important;}</style>