<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::_('bootstrap.tooltip');
JHTML::_('behavior.keepalive');

$document       = JFactory::getDocument();

if($this->searches && $this->settings->show_savesearch){
	$searchscript = '
        var setCookieRedirect;
		(function($) {
			$(document).ready(function() {
				$.cookie.json = true;
				var ipsearchcookies = new Array();
				var ipadvsearchpath = "'.IpropertyHelperRoute::getAdvsearchRoute().'";'."\n";
			
	// build hash cookie objects for the saved search
	foreach($this->searches as $s){
		$searchscript	.= 'ipsearchcookies['.$s->id.'] = $.parseJSON(\''.$s->search_string.'\');'."\n";
	}
	
	$searchscript .= "
				setCookieRedirect = function(id) {
					var cookiedata = ipsearchcookies[id];	
                    // try to remove cookie first
                    $.removeCookie('ipadvsearch'+cookiedata.Itemid);
					var ipsearchcookie = jQuery.cookie('ipadvsearch'+cookiedata.Itemid, cookiedata);
                    window.location = ipadvsearchpath;
				}            
			});
		})(jQuery);";	

    $document->addScriptDeclaration($searchscript);
}

// we got here so it's a logged in end user, not an agent
$delete_script = "
    (function($) {
	$( document ).ready(function() {
        jQuery('.icon-trash').on('click', function(event){
            
            var row = $(this).closest('tr');
            var rowId = $(this).closest('tr').attr('id');
    
            if(confirm('".JText::_('COM_IPROPERTY_CONFIRM_DELETE' )."'))
            {
                var request = $.ajax({
                    url: 'index.php?option=com_iproperty&task=ajax.deleteSaved',
                    method: 'post',
                    data: { '".JSession::getFormToken()."':'1',
                        'format': 'raw',
                        'editid': rowId
                    },
                    beforeSend: function(){
                        row.toggleClass('alert alert-error');
                    },
                    success: function(response) {
                        if(response){
                            $('#'+rowId).fadeOut(1000, function(){
                                $('#'+rowId).remove();
                            });
                        }
                    },
                    error: function() {
                        $('.ip-favorites-maincontainer').prepend('<div id=\"ip-favorites-msg\" class=\"alert alert-error\">".addslashes(JText::_('COM_IPROPERTY_IPUSER_FAIL'))."</div>').fadeIn('slow');
                        $('#ip-favorites-msg').delay(1000).fadeOut();
                    }
                });
            }
        });
        
        $('.ipsave_eupdate').on('change', function(event){			
            var row = $(this).closest('tr');
            var rowId = $(this).closest('tr').attr('id');
            
            var request = $.ajax({
                url: 'index.php?option=com_iproperty&task=ajax.changeUpdateStatus',
                method: 'post',
                data: { '".JSession::getFormToken()."':'1',
                    'format': 'raw',
                    'editid': rowId
                },
                success: function(response) {
                    if(response){
                        $('.ip-favorites-maincontainer').prepend('<div id=\"ip-favorites-msg\" class=\"alert alert-success\">".addslashes(JText::_('JLIB_APPLICATION_SAVE_SUCCESS'))."</div>').fadeIn('slow');
                        $('#ip-favorites-msg').delay(1000).fadeOut();
                    }                    
                },
                error: function() {
                    $('.ip-favorites-maincontainer').prepend('<div id=\"ip-favorites-msg\" class=\"alert alert-error\">".addslashes(JText::_('COM_IPROPERTY_IPUSER_FAIL'))."</div>').fadeIn('slow');
                    $('#ip-favorites-msg').delay(1000).fadeOut();
                }
            });
        });
    });        
})(jQuery);";

$document->addScriptDeclaration($delete_script);
echo $this->loadTemplate('toolbar');

$user = JFactory::getUser();
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('*');
$query->from($db->quoteName('#__iproperty_agents'));
$query->where($db->quoteName('user_id')." = ".$db->quote($user->id));
//echo($query->__toString());exit;
$db->setQuery($query);
$results = $db->loadObject();

$agent_type=$results->agent_type;

?>

<div class="ip-favorites-maincontainer">
    <div class="favorites-list<?php echo $this->pageclass_sfx;?>">
        <?php if ($this->params->get('show_page_heading')) : ?>
            <div class="page-header">
                <h1>
                    <?php echo $this->escape($this->params->get('page_heading')); ?>
                </h1>
            </div>
        <?php endif; ?>
        <?php if ($this->params->get('show_ip_title') && $this->iptitle) : ?>
            <div class="ip-mainheader">
                <h2>
                    <?php echo $this->escape($this->iptitle); ?>
                </h2>
            </div>
        <?php endif; ?>

        <?php    
        $tab = JRequest::getvar('tab');
        if($tab == 'searchcriteria'){ 
            echo JHtmlBootstrap::startTabSet('ipUser', array('active' => 'ipsearch'));
        } else if($tab == 'savedfavorites'){
            echo JHtmlBootstrap::startTabSet('ipUser', array('active' => 'ipproplist'));
        }else if($tab == 'savedsearches'){
            echo JHtmlBootstrap::startTabSet('ipUser', array('active' => 'ipsearchlist'));
        }
        //echo JHtmlBootstrap::startTabSet('ipUser');
        //echo JHtmlBootstrap::startTabSet('ipUser', array('active' => 'ipproplist'));
        //echo "<pre>"; print_r($this->settings); exit;
        // load saved properties tmpl//

        $user = JFactory::getUser();
        if($user->id){
            echo JHtmlBootstrap::addTab('ipUser', 'ipsearch', JText::_('SEARCH_CRITERIA'));
                echo $this->loadTemplate('searchcriterialist'); 
            echo JHtmlBootstrap::endTab();
        }
        if($this->settings->show_saveproperty)
            {
                echo JHtmlBootstrap::addTab('ipUser', 'ipproplist', JText::_('COM_IPROPERTY_MY_SAVED_PROPERTIES'));
                    echo $this->loadTemplate('proplist'); 
                echo JHtmlBootstrap::endTab();
            }
        /*if($agent_type == 2){
            if($this->settings->show_saveproperty)
            {
                echo JHtmlBootstrap::addTab('ipUser', 'ipproplist', JText::_('COM_IPROPERTY_MY_SAVED_PROPERTIES'));
                    echo $this->loadTemplate('proplist'); 
                echo JHtmlBootstrap::endTab();
            }
        }*/
        

        // load saved searches tmpl
        if($this->settings->show_savesearch)
        {    
            echo JHtmlBootstrap::addTab('ipUser', 'ipsearchlist', JText::_('COM_IPROPERTY_MY_SAVED_SEARCHES'));
                echo $this->loadTemplate('searchlist'); 
            echo JHtmlBootstrap::endTab();
        }

        $this->dispatcher->trigger('onAfterRenderFavorites', array($this->user, $this->settings));
        echo JHtmlBootstrap::endTabSet();

        // display footer if enabled
        if ($this->settings->footer == 1) echo ipropertyHTML::buildThinkeryFooter(); 
        ?>
    </div>
</div>
