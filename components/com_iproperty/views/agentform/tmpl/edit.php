<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.modal');
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', '.ipform select');

// Create shortcut to parameters.
$params = $this->state->get('params');
$settings   = ipropertyAdmin::config();
$maximgsize      = $settings->maximgsize;
//echo $maximgsize; exit;
?>

<script type="text/javascript">

	var pluploadpath	= '<?php echo JURI::root().'/components/com_iproperty/assets/js'; ?>';
	Joomla.submitbutton = function(task) {
        alert("Task in Edit...");
        alert(task);
		// hack for &*%& IE8
		if(Browser.ie8){
			if(document.getElementById('pluploadcontainer') != null){
				document.id('pluploadcontainer').destroy();
			}
		}	
	
		if (task == 'agentform.cancel'){
            <?php echo $this->form->getField('bio')->save(); ?>
			Joomla.submitform(task);
        }else if(document.formvalidator.isValid(document.id('adminForm'))) {
            
            if(jQuery( "#jform_phone" ).val() != ''){
                var regex = /^\d{3}-?\d{3}-?\d{4}$/g;
                 if(!regex.test(jQuery( "#jform_phone" ).val())){
                    	var phone_id = jQuery("#jform_phone").attr('id');
                        var phone_value = jQuery( "#jform_phone" ).val();
                        console.log(phone_value);
                        checkChar(phone_id, phone_value);
                    return false;
                }
            }if(jQuery( "#jform_mobile" ).val() != ''){
                var regex = /^\d{3}-?\d{3}-?\d{4}$/g;   
                if(!regex.test(jQuery( "#jform_mobile" ).val())){
                    	var mobile_id = jQuery("#jform_mobile").attr('id');
                        var mobile_value = jQuery( "#jform_mobile" ).val();
                        console.log(mobile_value);
                        checkChar(mobile_id, mobile_value);
                    return false;
                }
            }if(jQuery( "#jform_fax" ).val() != ''){
                var regex = /^\d{3}-?\d{3}-?\d{4}$/g;   
                if(!regex.test(jQuery( "#jform_fax" ).val())){
                        var fax_id = jQuery("#jform_fax").attr('id');
                        var fax_value = jQuery( "#jform_fax" ).val();
                        console.log(fax_value);
                        checkChar(fax_id, fax_value);
                        return false;
                }
            }if(jQuery( "#jform_postcode" ).val() != ''){
                if(!/^[0-9]+$/.test(jQuery( "#jform_postcode" ).val())){
                        var postcode_id = jQuery("#jform_postcode").attr('id');
                        var postcode_value = jQuery( "#jform_postcode" ).val();
                        console.log(postcode_value);
                        checkInt(postcode_id, postcode_value);
                        return false;
                }
                var ln = jQuery( "#jform_postcode" ).val().length;
                if((ln != 5) && (ln != 6)){
                        var postcode_id = jQuery("#jform_postcode").attr('id');
                        var postcode_value = jQuery( "#jform_postcode" ).val();
                        console.log(postcode_value);
                        checkChar(postcode_id, postcode_value);
                        return false;
                }
            }
            <?php 
            if($this->ipauth->getAdmin()): //only confirm company if admin user ?>
                if(document.id('jform_company').selectedIndex == ''){
                    alert('<?php echo $this->escape(JText::_('COM_IPROPERTY_SELECT_COMPANIES')); ?>');
                    return false;
                }

                // custom end
            <?php endif; ?>
			<?php echo $this->form->getField('bio')->save(); ?>
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

                for (i = invalid.length - 1; i >= 0; i--) {
                    //console.log(i);
                    // console.log(invalid[i]);
                    //label = jQuery.trim($(invalid[i]).data("id").text().replace("*", "").toString());
                    var inputId = jQuery(invalid[i]).attr("id");
                    console.log(inputId);
                    if (inputId) {
                        if(inputId === 'jform_fname') {                            
                            error.error.push('Please Enter First Name');
                        }if(inputId === 'jform_lname') {
                            error.error.push('Please Enter Last Name');
                        }if(inputId === 'jform_email') {                            
                            error.error.push('Please Enter Email');
                        }if(inputId === 'jform_phone') {
                            error.error.push('Please Enter Phone');
                        }if(inputId === 'jform_extension_number') {
                            error.error.push('Please Enter Extension Number');
                        }if(inputId === 'jform_alicense') {
                            error.error.push('Please Enter Licence');
                        }if(inputId === 'jform_street') {                            
                            error.error.push('Please Enter Street Address');
                        }if(inputId === 'jform_country') {                            
                            error.error.push('Please Select Country');
                        }if(inputId === 'jform_locstate') {
                            error.error.push('Please Select State');
                        }if(inputId === 'jform_city') {                            
                            error.error.push('Please Select City');
                        }if(inputId === 'jform_postcode') {
                            error.error.push('Please Enter Postcode');
                        }
                    }
                }
            }
            Joomla.renderMessages(error);
        }
			alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
		}
	}

	function checkInt(fieldid, fieldval){
        //console.log(fieldid);

        if (fieldval.match(/[^\d\.]/g)) {
            //console.log('false');
            jQuery('#'+fieldid).val('');
            jQuery('#'+fieldid).removeClass('invalid');
        	jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        	jQuery('.invalid').remove();

            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid">Only Interger is Valid</span>');


            //jQuery('#'+fieldid).attr('placeholder','Only Interger is Valid');

        } else {
            //console.log('true');
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        }
    }
    function checkChar(fieldid, fieldval){
        /*console.log(fieldid);*/
        

        if (fieldid == 'jform_postcode') {
            //console.log('false');
            /*jQuery('#'+fieldid).val('');*/
            jQuery('#'+fieldid).removeClass('invalid');
        	jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        	jQuery('.invalid').remove();

            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid">Postcode should contain only 5 - 6 characters</span>');


            //jQuery('#'+fieldid).attr('placeholder','Only Interger is Valid');

        }else if(fieldid == 'jform_phone'){
        	jQuery('#'+fieldid).removeClass('invalid');
        	jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        	jQuery('.invalid').remove();

            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid"><br/>Phone should be in this pattern xxx-xxx-xxxx</span>');
            
        } else if(fieldid == 'jform_mobile'){
        	jQuery('#'+fieldid).removeClass('invalid');
        	jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        	jQuery('.invalid').remove();

            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid"><br/>Mobile should be in this pattern xxx-xxx-xxxx</span>');
        } else if(fieldid == 'jform_fax'){
        	jQuery('#'+fieldid).removeClass('invalid');
        	jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        	jQuery('.invalid').remove();

            jQuery('#'+fieldid).addClass('invalid');
            jQuery('#'+fieldid+'-lbl').addClass('invalid');
            jQuery('#'+fieldid).after('<span class="invalid">Fax should be in this pattern xxx-xxx-xxxx</span>');
        } else {
            //console.log('true');
            jQuery('#'+fieldid).removeClass('invalid');
            jQuery('#'+fieldid+'-lbl').removeClass('invalid');
        }
    }

    checkAgentEmail = function(){
        document.id('system-message-container').set('tween');
        var checkurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_iproperty&task=ajax.checkUserEmail';
        var agentEmail = document.id('jform_email').value;
        
        req = new Request({
            method: 'post',
            url: checkurl,
            data: { 'email': agentEmail,
                    'agent_id': <?php echo (int) $this->item->id; ?>,
                    '<?php echo JSession::getFormToken(); ?>':'1',
                    'format': 'raw'},
            onRequest: function() {
                document.id('system-message-container').set('html', '');
            },
            onSuccess: function(response) {
                if(response){
                    document.id('system-message-container').highlight('#ff0000');
                    document.id('jform_email').value = '';
                    document.id('jform_email').set('class', 'inputbox invalid');
                    document.id('jform_email').focus();
                    document.id('system-message-container').set('html', '<div class="ip_warning"><?php echo JText::_('COM_IPROPERTY_AGENT_EMAIL_ALREADY_EXISTS'); ?></div>');                    
                }
            }
        }).send();
    }
    //check profile name for profile url validation//
        checkProfileName = function(){
        	document.id('system-message-container').set('tween');
        	var checkurl = '<?php echo JURI::base('true'); ?>/index.php?option=com_iproperty&task=ajax.checkProfileName';
        	var ProfileName = document.id('jform_live_profile').value;
        	// alert(ProfileName);
        	req = new Request({
            method: 'post',
            url: checkurl,
            data: { 'live_profile': ProfileName,
                    'format': 'raw'},
            dataType: 'JSON',
            	onSuccess: function(response) {
            		if(response){
                    document.id('system-message-container1').highlight('#ff0000');
                    document.id('jform_live_profile').value = '';
                    document.id('jform_live_profile').set('class', 'inputbox invalid');
                    document.id('jform_live_profile').focus();
                    document.id('system-message-container1').set('html', '<div class="ip_warning" style="color:red"><?php echo JText::_('Profile Name is already exists.'); ?></div>');      
                	}
            	}
        	}).send();
        }
</script>
<script type="text/javascript">
// custom virl
jQuery(document).ready(function(){
    setTimeout(function(){
     var countryArr = jQuery( "#jform_country" ).val();loadStates(countryArr); }, 100);
    /*jQuery('#jform_country option:eq(231)').attr('selected', 'selected');
    jQuery( ".chzn-drop ul li").find("[data-option-array-index=231]").css("display", "none");*/
    //jQuery('.chzn-drop ul li').eq(231).addClass("result-selected");
    jQuery( "#jform_country" ).change(function() { 
        console.log(jQuery(this).val());
        var countryArr = jQuery(this).val();//.filter(function(v){return v!==''});
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

    jQuery( "#jform_locstate" ).change(function() {
        //console.log(window.location);return false;
        console.log(jQuery(this).val());
        var stateArr = jQuery(this).val();//.filter(function(v){return v!==''});
        console.log(stateArr);
        loadCities(stateArr);
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

    <?php
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__iproperty_agents'));
        $query->where($db->quoteName('user_id')." = ".$user->id);
        $db->setQuery($query);
        $results = $db->loadObject();
        $type=$results->agent_type ;
    ?>
        var type='<?php echo $type;?>';
        //jQuery('#jform_password').removeClass('required');
        //jQuery('#jform_password').removeClass('validate-password');
        //jQuery('#jform_password').attr("required", false);
        ///jQuery('#jform_password').removeAttr("aria-required", false);
        //jQuery('#jform_password').val("");
        //jQuery('#jform_password').css("display", "none");
        //jQuery('#jform_password').attr("id", "jform_password_x");
        //jQuery('#jform_agent_type').attr("disabled", false);
        //jQuery('#jform_agent_type').prop('disabled', true).trigger("liszt:updated");
        if(type==1){
            jQuery('#jform_alicense').addClass('required');
            jQuery('#jform_alicense').attr('required','required');
            jQuery('#jform_alicense-lbl').addClass('required');
            jQuery('#jform_alicense-lbl').append('<span class="star">&nbsp;*</span>');

            jQuery('#jform_extension_number').addClass('required');
            jQuery('#jform_extension_number').attr('required','required');
            jQuery('#jform_extension_number-lbl').addClass('required');
            jQuery('#jform_extension_number-lbl').append('<span class="star">&nbsp;*</span>');

           jQuery('#jform_locstate').addClass('required');
            jQuery('#jform_locstate').attr('required','required');
            jQuery('#jform_locstate-lbl').addClass('required');
            jQuery('#jform_locstate-lbl').append('<span class="star">&nbsp;*</span>');

            jQuery('#jform_street').addClass('required');
            jQuery('#jform_street').attr('required','required');
            jQuery('#jform_street-lbl').addClass('required');
            jQuery('#jform_street-lbl').append('<span class="star">&nbsp;*</span>');

            jQuery('#jform_city').addClass('required');
            jQuery('#jform_city').attr('required','required');
            jQuery('#jform_city-lbl').addClass('required');
            jQuery('#jform_city-lbl').append('<span class="star">&nbsp;*</span>');

            jQuery('#jform_postcode').addClass('required');
            jQuery('#jform_postcode').attr('required','required');
            jQuery('#jform_postcode-lbl').addClass('required');
            jQuery('#jform_postcode-lbl').append('<span class="star">&nbsp;*</span>');

            jQuery('#jform_country').addClass('required');
            jQuery('#jform_country').attr('required','required');
            jQuery('#jform_country-lbl').addClass('required');
            jQuery('#jform_country-lbl').append('<span class="star">&nbsp;*</span>');

        } else {
            //alert('here');
             jQuery('#jform_locstate').addClass('required');
            jQuery('#jform_locstate').attr('required','required');
            jQuery('#jform_locstate-lbl').addClass('required');
            jQuery('#jform_locstate-lbl').append('<span class="star">&nbsp;*</span>');

            jQuery('#jform_street').addClass('required');
            jQuery('#jform_street').attr('required','required');
            jQuery('#jform_street-lbl').addClass('required');
            jQuery('#jform_street-lbl').append('<span class="star">&nbsp;*</span>');

            jQuery('#jform_city').addClass('required');
            jQuery('#jform_city').attr('required','required');
            jQuery('#jform_city-lbl').addClass('required');
            jQuery('#jform_city-lbl').append('<span class="star">&nbsp;*</span>');

            jQuery('#jform_postcode').addClass('required');
            jQuery('#jform_postcode').attr('required','required');
            jQuery('#jform_postcode-lbl').addClass('required');
            jQuery('#jform_postcode-lbl').append('<span class="star">&nbsp;*</span>');

            jQuery('#jform_country').addClass('required');
            jQuery('#jform_country').attr('required','required');
            jQuery('#jform_country-lbl').addClass('required');
            jQuery('#jform_country-lbl').append('<span class="star">&nbsp;*</span>');

        }

        jQuery("input[name='jform[phone]']").keyup(function() {
             jQuery(this).val(jQuery(this).val().replace(/^(\d{3})(\d{3})(\d)+$/, "$1-$2-$3"));
        });
        jQuery("input[name='jform[mobile]']").keyup(function() {
             jQuery(this).val(jQuery(this).val().replace(/^(\d{3})(\d{3})(\d)+$/, "$1-$2-$3"));
        });
        jQuery("input[name='jform[fax]']").keyup(function() {
             jQuery(this).val(jQuery(this).val().replace(/^(\d{3})(\d{3})(\d)+$/, "$1-$2-$3"));
        });


});
</script>
<?php echo $this->loadTemplate('toolbar'); ?>
<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading', 1)) : ?>
        <h1>
            <?php //echo $this->escape($this->params->get('page_heading')); ?>
        </h1>
    <?php endif; ?>
    <div class="ip-mainheader">
        <h2><?php echo $this->iptitle; ?></h2>
    </div>
    
    <div id="system-message-container"></div>

    <form action="<?php echo JRoute::_('index.php?option=com_iproperty&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate ipform form-horizontal">
        <div class="btn-toolbar">
			<div class="btn-group">
                <!-- <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('agentform.apply')">
                    <?php echo JText::_('COM_IPROPERTY_APPLY') ?>
                </button> -->
                <button type="button" class="btn" onclick="Joomla.submitbutton('agentform.save')">
                    <?php echo JText::_('JSAVE') ?>
                </button>
                <button type="button" class="btn" onclick="Joomla.submitbutton('agentform.cancel')">
                    <?php echo JText::_('JCANCEL') ?>
                </button>
            </div>
        </div>
        <ul class="nav nav-tabs">
            <li class="active"><a href="#agentdetails" data-toggle="tab"><?php echo JText::_('COM_IPROPERTY_DETAILS') ?></a></li>
            <?php if($type == 1){ ?>
                <li><a href="#agentbio" data-toggle="tab"><?php echo JText::_('COM_IPROPERTY_IMAGE').' / '.JText::_('COM_IPROPERTY_AGENT_BIO'); ?></a></li>
           <?php } ?>
            
            <?php if ($this->ipauth->getAdmin() || $this->ipauth->getSuper()): ?>
                <li><a href="#agentother" data-toggle="tab"><?php echo JText::_('COM_IPROPERTY_OTHER') ?></a></li>
            <?php endif; ?>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="agentdetails">
                <fieldset>
                    <legend><?php echo JText::_('COM_IPROPERTY_DETAILS'); ?></legend>
                    <!--<div class="control-group">
                        <div class="control-label">
                            <?php //echo $this->form->getLabel('agent_type'); ?>
                        </div>
                        <div class="controls">
                            <?php //echo $this->form->getInput('agent_type'); ?>
                        </div>
                    </div>-->
                    <input type="hidden" name="jform[agent_type]" id="jform_agent_type" value="<?php echo $this->form->getValue('agent_type'); ?>" />
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('fname'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('fname'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('lname'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('lname'); ?>
                        </div>
                    </div>
                    <?php if (is_null($this->item->id) || $this->ipauth->getAdmin()):?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('alias'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('alias'); ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('alias'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getValue('alias'); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php
                    if($type == 1){ // custom viral
                     if($this->ipauth->getAdmin()): //only show company if admin user ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('company'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('company'); ?>
                            </div>
                        </div>
                    <?php elseif($this->form->getValue('company')): //if not admin and company already set, leave it as a hidden field ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('company'); ?>
                            </div>
                            <div class="controls">
                                <?php echo ipropertyHTML::getCompanyName($this->form->getValue('company')); ?>
                            </div>
                        </div>
                        <input type="hidden" name="jform[company]" value="<?php echo $this->form->getValue('company'); ?>" />
                    <?php else: ?>
                        <input type="hidden" name="jform[company]" value="<?php echo $this->ipauth->getUagentCid(); ?>" />
                    <?php endif; 
                       }
                    ?>
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
                            <?php echo $this->form->getLabel('email'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('email'); ?>
                        </div>
                    </div>
                    <!--<div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('password'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('password'); ?>
                        </div>
                    </div>-->
                    <?php //echo $this->form->getInput('password'); ?>
                    <input type="hidden" name="jform[password]" id="jform_password" value="" autocomplete="off" class="" size="30" maxlength="99" style="display: none;">
                    <div class="control-group">
                    <div class="span5 phone-label">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('phone'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('phone'); ?>
                        </div>
                     </div>
                     <div class="span5 mobile-label">   
                        <div class="control-label">
                            <?php echo $this->form->getLabel('mobile'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('mobile'); ?>
                        </div>
                     </div>   
                    </div>
                    <!-- <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('mobile'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('mobile'); ?>
                        </div>
                    </div> -->
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('extension_number'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('extension_number'); ?>
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('fax'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('fax'); ?>
                        </div>
                    </div>
                    <?php if($type == 1){?>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('alicense'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('alicense'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('live_profile'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('live_profile'); ?>
                            <div id="system-message-container1"></div>
                        </div>
                        <span>Ex. <b>usmetrorealty.info/usmetrorealty/(Your name)</b>. Like kent123, john78.<p style="color:red ">Enter unique name for Profile link</p></span>
                    </div>
                    <?php } ?>
                    
                </fieldset>
                <fieldset>
                    <legend><?php echo JText::_('COM_IPROPERTY_ADDRESS'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('street'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('street'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('street2'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('street2'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('country'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('country'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('locstate'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('locstate'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('city'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('city'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('province'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('province'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('postcode'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('postcode'); ?>
                        </div>
                    </div>
                    
                </fieldset>
                <fieldset id="web_fieldset" style="display:none;">
                    <legend><?php echo JText::_('COM_IPROPERTY_AGENT'); ?></legend>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('website'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('website'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('msn'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('msn'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('skype'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('skype'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('gtalk'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('gtalk'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('twitter'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('twitter'); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('social1'); ?>
                        </div>
                        <div class="controls">
                            <?php echo $this->form->getInput('social1'); ?>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="tab-pane" id="agentbio">
                <fieldset>
                    <legend><?php echo JText::_('COM_IPROPERTY_IMAGE'); ?></legend>
                    <div class="control-group">
                        <div class="controls image_tab_gallary">
                            <?php echo $this->form->getInput('icon'); ?>
                        </div>
                    </div>
                </fieldset>
                <fieldset>
                    <legend><?php echo JText::_('COM_IPROPERTY_VIDEO'); ?></legend>
                    <div class="custom_video span12">
                        <div class="upload_custom_video span4">
                            <div class="control-group form-vertical span12">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('youtube_url'); ?>
                                </div>
                                <div class="control">
                                    <?php echo $this->form->getInput('youtube_url'); ?>
                                </div>
                            </div>
                            
                            <p>OR</p>
                            <div class="control-group form-vertical span12">
                                <div class="control-label">
                                    <?php echo $this->form->getLabel('upload_video'); ?>
                                </div>
                                <div class="control">
                                    <?php echo $this->form->getInput('upload_video'); ?>
                                </div>
                            </div> 
                            <!-- <div class="control-group form-vertical span12">
                                <div class="control-label">
                                    <?php //echo $this->form->getLabel('caption'); ?>
                                </div>
                                <div class="control">
                                    <?php //echo $this->form->getInput('caption'); ?>
                                </div>
                            </div> -->
                            <div class="btn-toolbar span12">
                                <div class="btn-group">
                                    <!-- <button type="button" class="btn btn-primary" onclick="Joomla.submitbutton('agentform.apply')">
                                        <?php echo JText::_('COM_IPROPERTY_APPLY') ?>
                                    </button> -->
                                    <button type="button" class="btn" id="upload_button">
                                        <?php echo JText::_('UPLOAD') ?>
                                    </button>
                                    <button type="button" class="btn" id="upload_cancel">
                                        <?php echo JText::_('JCANCEL') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="show_video span8">
                             <?php foreach($this->video as $video) {
                        $a = strstr($video->upload_video,"http");
                        if($a){
                            //echo $video->upload_video;
                            $v = end(explode('=',$video->upload_video));
                            ?>
                            <div class="ip-agent-thumb-holder span6">
                                <span class="caption"><?php echo $video->caption?></span>
                                {youtube}<?php echo $v;?>{/youtube}
                                <span><img class="delete_video" id="click_delete_<?php echo $video->id?>" data-id="<?php echo $video->id?>" width='50' height='50' src="<?php echo JURI::base().'media/com_iproperty/agents/delete.png'?>" alt="Usmerorealty">
                                <a href="#editModal" class="caption_id" data-id="<?php echo $video->id;?>"  data-value ="<?php echo $video->caption;?>" data-toggle="modal">Edit Caption</a>
                                </span>
                                
                             </div>
                             
                            <?php
                        } else {
                            $ext = end(explode('.',$video->upload_video));
                            $pathInfo = pathinfo($video->upload_video);
                            $video->upload_video = $pathInfo['filename']; ?>
                            <?php if($ext == 'mp4'){ ?>
                                <div class="ip-agent-thumb-holder span6">
                                <span class="caption"><?php echo $video->caption?></span>
                                    {mp4}<?php echo $video->agent_id."/".$video->upload_video;?>{/mp4}
                                    <span><img class="delete_video" id="click_delete_<?php echo $video->id?>" data-id="<?php echo $video->id?>" width='50' height='50' src="<?php echo JURI::base().'media/com_iproperty/agents/delete.png'?>" alt="Usmerorealty">
                                    <a href="#editModal" class="caption_id" data-id="<?php echo $video->id;?>"  data-value ="<?php echo $video->caption;?>" data-toggle="modal">Edit Caption</a>
                                    </span>
                               </div>
                               <?php } else if($ext == 'avi'){ ?>
                                <div class="ip-agent-thumb-holder span6">
                                <span class="caption"><?php echo $video->caption?></span>
                                    {avi}<?php echo $video->agent_id."/".$video->upload_video;?>{/avi}
                                    <span><img class="delete_video" id="click_delete_<?php echo $video->id?>" data-id="<?php echo $video->id?>" width='50' height='50' src="<?php echo JURI::base().'media/com_iproperty/agents/delete.png'?>" alt="Usmerorealty">
                                    <a href="#editModal" class="caption_id" data-id="<?php echo $video->id;?>"  data-value ="<?php echo $video->caption;?>" data-toggle="modal">Edit Caption</a>
                                </span>
                               </div>
                               <?php } else if($ext == '3gp'){ ?>
                                <div class="ip-agent-thumb-holder span6">
                                <span class="caption"><?php echo $video->caption?></span>
                                    {3gp}<?php echo $video->agent_id."/".$video->upload_video;?>{/3gp}
                                    <span><img class="delete_video" id="click_delete_<?php echo $video->id?>" data-id="<?php echo $video->id?>" width='50' height='50' src="<?php echo JURI::base().'media/com_iproperty/agents/delete.png'?>" alt="Usmerorealty">
                                    <a href="#editModal" class="caption_id" data-id="<?php echo $video->id;?>"  data-value ="<?php echo $video->caption;?>" data-toggle="modal">Edit Caption</a>
                                    </span>
                               </div>
                               <?php } else if($ext == 'wmv'){ ?>
                                <div class="ip-agent-thumb-holder span6">
                                <span class="caption"><?php echo $video->caption?></span>
                                    {wmv}<?php echo $video->agent_id."/".$video->upload_video;?>{/wmv}
                                    <span><img class="delete_video" id="click_delete_<?php echo $video->id?>" data-id="<?php echo $video->id?>" width='50' height='50' src="<?php echo JURI::base().'media/com_iproperty/agents/delete.png'?>" alt="Usmerorealty">
                                    <a href="#editModal" class="caption_id" data-id="<?php echo $video->id;?>"  data-value ="<?php echo $video->caption;?>" data-toggle="modal">Edit Caption</a>
                                    </span>
                               </div>
                               <?php } else if($ext == 'flv'){ ?>
                                <div class="ip-agent-thumb-holder span6">
                                <span class="caption"><?php echo $video->caption?></span>
                                    {flv}<?php echo $video->agent_id."/".$video->upload_video;?>{/flv}
                                    <span><img class="delete_video" id="click_delete_<?php echo $video->id?>" data-id="<?php echo $video->id?>" width='50' height='50' src="<?php echo JURI::base().'media/com_iproperty/agents/delete.png'?>" alt="Usmerorealty">
                                    <a href="#editModal" class="caption_id" data-id="<?php echo $video->id;?>"  data-value ="<?php echo $video->caption;?>" data-toggle="modal">Edit Caption</a>
                                </span>
                               </div>
                               
                            <?php } ?>

            <?php }  }?>
                        </div>
                    </div>         
                </fieldset>
                <fieldset>
                    <legend><?php echo JText::_('COM_IPROPERTY_AGENT_BIO'); ?></legend>
                    <div class="control-group form-vertical">
                        <div class="control-label">
                            <?php echo $this->form->getLabel('iphead1'); ?>
                        </div>
                        <div class="control">
                            <?php echo $this->form->getInput('bio'); ?>
                        </div>
                    </div>            
                </fieldset>
            </div>  
            <!-- super agent or admin can edit params -->
            <?php if ($this->ipauth->getAdmin() || $this->ipauth->getSuper()): ?>
                <div class="tab-pane" id="agentother">
                    <fieldset class="adminform">
                        <legend><?php echo JText::_('JPUBLISHED'); ?></legend>
                        <?php // 3.3.2 Addition - we don't want usesr to be able to unpublish own agent unless administrator ?>
                        <?php if ($this->ipauth->getAdmin() || ($this->ipauth->getSuper() && $this->item->user_id != $this->user->id)): ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('state'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('state'); ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="control-group">
                            <div class="control-label">
                                <?php echo $this->form->getLabel('featured'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('featured'); ?>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset class="adminform superagent_params">
                        <legend><?php echo JText::_('COM_IPROPERTY_AGENT_PARAMETERS'); ?></legend>
                        <?php foreach($this->form->getFieldset('superagent_params') as $field) :?>
                                <div class="control-group">
                                    <div class="control-label">
                                        <?php echo $field->label; ?>
                                    </div>
                                    <div class="controls">
                                        <?php echo $field->input; ?>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                    </fieldset>  
                </div>
            <?php endif; ?>
        </div>
        <input type="hidden" name="operation" value="edit_profile" />
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
        <?php echo JHtml::_( 'form.token'); ?>
    </form>
    <!-- Edit Caption Form-->
    <div class="modal hide fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="false" style="display: none;">   
        <form id="caption_modal" name="editcaption" action="index.php?option=com_iproperty&view=agentform&layout=edit&id=<?php echo $this->item->id;?>&01aeebb3cca0d797f9422a7190d49e8d=1&Itemid=0&return=aHR0cDovL2xvY2FsaG9zdC91c21ldHJvcmVhbHR5L2luZGV4LnBocD9vcHRpb249Y29tX2lwcm9wZXJ0eSZ2aWV3PW1hbmFnZSZsYXlvdXQ9ZGFzaGJvYXJk" method="post" class="form-horizontal ip-editmessage-form">        
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                <h3 id="editModalLabel" class="visible visible-first">Edit Caption</h3>
            </div>
            <div class="modal-body">            
                <div class="row-fluid">
                    <div class="clearfix"></div>
                    <div class="control-group">
                        <div class="control-label">Caption</div>
                        <div class="controls">
                            <input name="jform[caption_id]" id="jform_edit_id" type="hidden">
                            <textarea maxlength="30" name="jform[caption]" id="jform_edit_caption" cols="30" rows="6"></textarea>
                        </div>
                        <div class="controls">
                            <strong>NOTE::</strong><span style="color:red">Enter only 30 character</span>
                        </div>
                    </div> 
                </div> 
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button class="btn btn-primary" type="submit">Update Caption</button>
            </div>
            <input name="task" value="AgentForm.editCaption" type="hidden">
        </form>            
</div>

<script type="text/javascript">
    jQuery(document).on('click', ".caption_id", function(e){
        var a = jQuery(this).attr('data-value');
        var b = jQuery(this).attr('data-id');
        jQuery('#jform_edit_caption').val(a);
        jQuery('#jform_edit_id').val(b);

    });
</script>
    <!--End Edit Caption Form-->
</div>
<script type="text/javascript">

    jQuery(document).on('click', "#upload_button", function(e){
        e.preventDefault();
        //console.log(jQuery('#jform_upload_video').prop('files')[0]);
       
         var file_data = jQuery('#jform_upload_video').prop('files')[0]; 
         //var caption = jQuery('#jform_caption').val();

         var form_data = new FormData();
         form_data.append('file', file_data);
                console.log(form_data);
        if (typeof file_data === "undefined" && jQuery('#jform_youtube_url').val() === '') {
            jQuery('h6').remove();
            jQuery('#jform_upload_video').after('<h6 class="invalid" style="float:right;">Please Select any one option</h6>');
            return false;
        }  else if(file_data && jQuery('#jform_youtube_url').val()){
            jQuery('h6').remove();
            jQuery('#jform_upload_video').after('<h6 class="invalid" style="float:right;">Please Select any one option</h6>');
            return false;
        } else if(typeof file_data !== "undefined" && jQuery('#jform_youtube_url').val() === ''){

            var items = file_data['type'].split('/')[1];
            //alert(items);
            var maximgsize = '<?php echo $maximgsize; ?>';
            console.log(file_data['size']);
            var file_ext   = ['mp4','3gp','avi','wmv','flv'];
            var found = jQuery.inArray( items, file_ext)>-1;
            //console.log(found);

            if(found){
                if(file_data['size'] < 2000){
                    var form_data = new FormData();
                    form_data.append('file', file_data);
                    console.log(form_data);
                    if(form_data !== ''){   

                    jQuery('p').remove();
                    jQuery('h6').remove();
                    var url = "index.php?option=com_iproperty&view=agentform&task=agentform.uploadVideo";
                    jQuery.ajax({
                        type: "POST",
                        url: url,
                        data: form_data,
                        contentType: false,
                        processData: false, // serializes the form's elements.
                        success: function(data){
                            //alert('here');
                            jQuery('#upload_cancel').after('<h6 class="valid" style="float:right;margin-left:15px;color:green">Successfully Uploaded</h6>');
                        }
                    });
                    }
                } else {
                    jQuery('p').remove();
                    jQuery('#jform_upload_video').after('<p class="invalid">Only '+maximgsize+'KB maxsize upload video..</p>');   
                }
            } else {
                jQuery('p').remove();
                jQuery('#jform_upload_video').after('<p class="invalid">Only mp4,3gp,avi,wmv,flv type allowed </p>');
            }
        } else if(typeof file_data === "undefined" && jQuery('#jform_youtube_url').val() !== ''){
            var form_data = 'youtube='+jQuery('#jform_youtube_url').val();
            if(form_data !== ''){   

                jQuery('p').remove();
                jQuery('h6').remove();
                var url = "index.php?option=com_iproperty&view=agentform&task=agentform.uploadVideo";
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: form_data+'&caption='+caption,
                    success: function(data){
                        //alert('here');
                        jQuery('#upload_cancel').after('<h6 class="valid" style="float:right;margin-left:15px;color:green">Successfully Uploaded</h6>');
                    }
                });
            }
        }

        
    });

    jQuery(document).on('click', ".delete_video", function(e){
        e.preventDefault();
        id = jQuery(this).attr("id");
        delete_value = jQuery(this).attr("data-id");
        //alert(delete_value);
        var url = "index.php?option=com_iproperty&view=agentform&task=agentform.deleteVideo";
                jQuery.ajax({
                    type: "POST",
                    url: url,
                    data: 'delete_value='+delete_value,
                    success: function(data){
                       //setTimeout(function(){ location.reload(); }, 100);
                        //jQuery('li.firstItem').removeClass('active');
                        //jQuery('li.lastItem').addClass('active');
                        jQuery('#'+id).after( '<p style="float:right;margin-left:15px;color:green">Your video successfully deleted</p>' );
                        /*jQuery('.show_video').after('<h6 class="valid" style="float:right;margin-left:15px;color:green">Your video successfully deleted..Refresh your page</h6>');*/
                    }
                });
    });

</script>    
<style>
.ip-agent-thumb-holder {
    margin-top: 5px;
    overflow: hidden;
    width: 200px;
}
</style>                                                                                                                                                            
