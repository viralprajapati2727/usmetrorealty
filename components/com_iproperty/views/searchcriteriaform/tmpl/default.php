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
JHtml::_('formbehavior.chosen', '.ipform select');
JHtml::_('behavior.formvalidator');

JHtml::script(Juri::base() . 'media/system/js/jquery.formatCurrency.js');
?>
<script type="text/javascript">
jQuery(document).ready(function(){
Joomla.submitbutton = function(task) {
    if(document.formvalidator.isValid(document.adminForm)){
        //console.log('success.');

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



        /* else {
            console.log(jQuery('#jform_country').val());
            jQuery('#jform_country').removeClass('invalid');
            jQuery('#jform_country-lbl').removeClass('invalid');
            jQuery('#jform_country_chzn span.invalid').remove();
        }*/

        /*if(jQuery('#jform_locstate').val() == ""){
            // console.log(jQuery('#jform_locstate').val());
           //// alert("Please select State/States");
            //jQuery('#jform_locstate_message').text('Please select State/States');
           jQuery('#jform_locstate').addClass('invalid');
           jQuery('#jform_locstate-lbl').addClass('invalid');
           jQuery('#jform_locstate').after('<span class="invalid">Please select State/States</span>');
            return false;
        }*//* else {
            console.log(jQuery('#jform_locstate').val());
        }*/

        /*if(jQuery('#jform_city').val() == ""){
            /*console.log(jQuery('#jform_city').val());
            alert("Please select Cities/City");*/
            /*jQuery('#jform_city_message').text('Please select Cities/City');*/
            /*jQuery('#jform_city').addClass('invalid');
            jQuery('#jform_city-lbl').addClass('invalid');
            jQuery('#jform_city').after('<span class="invalid">Please select Cities/City</span>');*/
            //return false;
        /*} else {
            console.log(jQuery('#jform_city').val());
        }*/

        if(/[0-9]/.test(jQuery('#jform_garage_size').val())){
            //alert("# of Garage should be numeric only");
            return false;
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
                        jQuery( "div#"+datatabe ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                        jQuery( "div#"+datatabe ).addClass( "active" );
                        return false;
                    }
            } if(jQuery( "#jform_baths" ).val() != ''){
                    var regex = /^[0-9]+$/;   
                    if(!regex.test(jQuery( "#jform_baths" ).val())){
                        var bath_id = jQuery("#jform_baths").attr('id');
                        var bath_value = jQuery( "#jform_baths" ).val();
                        console.log(bath_value);
                        checkInt(bath_id, bath_value);

                        var datatabe = jQuery("#"+bed_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "div#"+datatabe ).removeClass( "active" );
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

                        var datatabe = jQuery("#"+bed_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "div#"+datatabe ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "#ip-propviewContent div" ).removeClass( "active" );
                        jQuery( "#ip-propviewTabs li."+datatabe ).addClass( "active" );
                        jQuery( "div#"+datatabe ).addClass( "active" );
                        return false;
                    }
            }
            if(jQuery( "#jform_garage_size" ).val() != ''){
                    var regex = /^[0-9]+$/;   
                    if(!regex.test(jQuery( "#jform_garage_size" ).val())){
                        var garage_id = jQuery("#jform_garage_size").attr('id');
                        var garage_value = jQuery("#jform_garage_size").val();
                        console.log(garage_value);
                        checkInt(garage_id, garage_value);

                        var datatabe = jQuery("#"+bed_id).attr("data-tab");
                        console.log(datatabe);
                        jQuery( "#ip-propviewTabs li" ).removeClass( "active" );
                        jQuery( "div#"+datatabe ).removeClass( "active" );
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
        jQuery('#ip-propviewTabs li:first a').trigger('click');
    }
}
    jQuery("#jform_country-lbl").append('<span class="star">&nbsp;*</span>');
    jQuery("#jform_locstate-lbl").append('<span class="star">&nbsp;*</span>');
    jQuery("#jform_city-lbl").append('<span class="star">&nbsp;*</span>');

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
//remove state 
/*jQuery( ".search-choice-close" ).click(function() {
  jQuery(".search-choice-close").remove();
});-*/
jQuery(".search-choice-close").chosen({disable_search_threshold: 10});

///
    jQuery( "#jform_country" ).change(function() { 
        console.log(jQuery(this).val());
        var countryArr = jQuery(this).val().filter(function(v){return v!==''});
        console.log(countryArr);
        //console.log(window.location);return false;
        loadStates(countryArr);
    });

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

    jQuery("#jform_minprice").blur(function () {
        jQuery("#jform_minprice").formatCurrency();
    });

    jQuery("#jform_maxprice").blur(function () {
        jQuery("#jform_maxprice").formatCurrency();
    });

    /*jQuery('#jform_minprice')on('keydown', function() {
        var formatted_price = format2(jQuery(this).val(), "$");
        console.log(formatted_price);
        jQuery(this).val(formatted_price);
    });

    function format2(n, currency) {
        n = parseFloat(n);
        return currency + " " + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    }*/

    jQuery( "#jform_locstate" ).change(function() {
        //console.log(window.location);return false;
        //console.log(jQuery(this).val());
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
/*
    jQuery('.checkint').on('blur', function(){
        //console.log('sdfsdfgdfgdf');
        checkInt(this.id, this.value);
    });*/
    
    function checkInt(fieldid, fieldval){
        //console.log(fieldid);
        if (fieldval.match(/[^\d\.]/g)) {
            //console.log('false');
            jQuery('#int_val').remove();
            jQuery('#'+fieldid).val('');
            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid" id="int_val"><br/>Only Interger is Valid</span>');


            //jQuery('#'+fieldid).attr('placeholder','Only Interger is Valid');

        } else {
            //console.log('true');
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        }
    }
function checkAlpha(fieldid, fieldval){
        if (fieldval.match(/^[0-9]+$/)) {
            //propdetails
            //console.log('false');
            jQuery('#int_val').remove();
            jQuery('#'+fieldid).val('');
            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid" id="int_val"><br/>Only Interger is Valid</span>');
            
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

                <button type="button" class="btn" value="save" name="save" onclick="Joomla.submitbutton('SearchcriteriaForm.save')">
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
                        <?php echo $this->form->getLabel('title'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('title'); ?>
                    </div>
                </div>

                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('description'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('description'); ?>
                    </div>
                </div>
                        
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('hometype'); ?>
                    </div>
                    <div class="controls">
                        <input type="hidden" name="jform[buyer_id]" value="<?php echo $this->ipauth->getAgentId(); ?>" />
                        <?php echo $this->form->getInput('hometype'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('minprice'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('minprice'); ?>
                        &nbsp;<?php echo "To"; ?>&nbsp;
                        <?php echo $this->form->getInput('maxprice'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('country'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('country'); ?>
                        <span id="jform_country_message"></span>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('locstate'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('locstate'); ?>
                        <span id="jform_locstate_message"></span>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <?php echo $this->form->getLabel('city'); ?>
                    </div>
                    <div class="controls">
                        <?php echo $this->form->getInput('city'); ?>
                        <span id="jform_city_message"></span>
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
                                <?php echo $this->form->getLabel('beds'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('beds'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('baths'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('baths'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('sleeps'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('sleeps'); ?>
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('kitchen'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('kitchen'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('total_units'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('total_units'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('sqft'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('sqft'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lotsize'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lotsize'); ?>
                            </div>
                        </div>
                        
                    </div>
                    <div class="span4 pull-right form-vertical">
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lot_acres'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lot_acres'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lot_type'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lot_type'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('heat'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('heat'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('cool'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('cool'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('fuel'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('fuel'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('garage_type'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('garage_type'); ?>
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('garage_size'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('garage_size'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="span4 pull-right form-vertical">
                        
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('siding'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('siding'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('roof'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('roof'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('reception'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('reception'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('tax'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('tax'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('income'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('income'); ?>
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('yearbuilt'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('yearbuilt'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('zoning'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('zoning'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('propview'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('propview'); ?>
                            </div>
                        </div> -->
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('school_district'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('school_district'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('style'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('style'); ?>
                            </div>
                        </div>
                        <!-- <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('shared_own'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('shared_own'); ?>
                            </div>
                        </div>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('lease_hold'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('lease_hold'); ?>
                            </div>
                        </div> -->
                        <?php /*if($this->settings->adv_show_wf): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('frontage'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('frontage'); ?>
                            </div>
                        </div>
                        <?php endif;*/ ?>
                        <?php if($this->settings->adv_show_reo): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('reo'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('reo'); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if($this->settings->adv_show_hoa): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('hoa'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('hoa'); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </fieldset>
        <?php
            echo JHtmlBootstrap::endTab();
            echo JHtmlBootstrap::addTab('ip-propview', 'propamens', JText::_('COM_IPROPERTY_AMENITIES'));
        ?>
            <div class="row-fluid">
                <div class="span4 pull-left">
                    <?php echo $this->form->getLabel('general_amen_header'); ?>
                    <?php echo $this->form->getInput('general_amens'); ?>
                </div>
                <div class="span4 pull-left">
                    <?php echo $this->form->getLabel('interior_amen_header'); ?>
                    <?php echo $this->form->getInput('interior_amens'); ?>
                </div>
                <div class="span4 pull-left">
                    <?php echo $this->form->getLabel('exterior_amen_header'); ?>
                    <?php echo $this->form->getInput('exterior_amens'); ?>
                </div>
            </div>
            <div class="row-fluid">
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('accessibility_amen_header'); ?>
                        <?php echo $this->form->getInput('accessibility_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('green_amen_header'); ?>
                        <?php echo $this->form->getInput('green_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('security_amen_header'); ?>
                        <?php echo $this->form->getInput('security_amens'); ?>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('landscape_amen_header'); ?>
                        <?php echo $this->form->getInput('landscape_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('community_amen_header'); ?>
                        <?php echo $this->form->getInput('community_amens'); ?>
                    </div>
                    <div class="span4 pull-left">
                        <?php echo $this->form->getLabel('appliance_amen_header'); ?>
                        <?php echo $this->form->getInput('appliance_amens'); ?>
                    </div>
                </div>
        <?php
        echo JHtmlBootstrap::endTab();
        echo JHtmlBootstrap::addTab('ip-propview', 'propnotes', JText::_('COM_IPROPERTY_NOTES'));
        ?>
            <fieldset>
                <div class="row-fluid">
                    <div class="span12 pull-left">
                        <?php echo $this->form->getLabel('notes'); ?>
                        <?php echo $this->form->getInput('notes'); ?>
                    </div>
                </div>
            </fieldset>
        <?php
        echo JHtmlBootstrap::endTab();
        ?>
            <input type="hidden" name="task" value="searchcriteriaform.save">

</form>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery(window).scroll(function(){
        var window_top = jQuery(window).scrollTop() + 12; // the "12" should equal the margin-top value for nav.stick
        var div_top = jQuery('#ip-propviewContent').offset().top;
        //console.log('win'+window_top);
        //console.log('div'+div_top);
        if (window_top > div_top) {
            jQuery('#btns_bar').addClass('stickybar');
        } else {
            jQuery('#btns_bar').removeClass('stickybar');
        }
    });
});
</script>
<style>#jform_notes{height:auto;width:auto;}
#jform_total_units_chzn{width:220px !important;}
</style>