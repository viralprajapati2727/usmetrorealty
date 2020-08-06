<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

$agent_photo_width      = ($this->settings->agent_photo_width) ? $this->settings->agent_photo_width : '90';
$available_class        = ($this->p->available && $this->p->available < JFactory::getDate()->toSql()) ? 'success' : 'error';

$saddress = ipropertyHTML::getStreetAddress($this->settings, $this->p, '', $pview = true);
$faddress = ipropertyHTML::getFullAddress($this->p, $saddress);

//set side column if any conditions apply
$sidecol = '<div class="well span6 wtpladdrs" >';        
    if($this->p->available) $sidecol .= '<div class="alert alert-'.$available_class.'"><b>'.JText::_('COM_IPROPERTY_AVAILABLE').':</b><br /><span>'.JFactory::getDate($this->p->available)->format(JText::_('COM_IPROPERTY_DATE_FORMAT_AVAILABLE')).'</span></div>';
    if($this->p->vtour)     $sidecol .= '<a class="ip-sidecol btn btn-info ip-vtour" href="'.$this->p->vtour.'" target="_blank"><span class="icon-bookmark"></span> '.JText::_('COM_IPROPERTY_VTOUR' ).'</a>';
    
    $sidecol .= '<div class="ip-sidecol ip-mainaddress span12">'.$faddress.'</div>';
    if ($this->p->subdivision) $sidecol .= '<div class="ip-sidecol"><b>'.JText::_('COM_IPROPERTY_SUBDIVISION' ).'</b>: '. $this->p->subdivision.'</div>';
    if ($this->p->county)   $sidecol .= '<div class="ip-sidecol"><b>'.JText::_('COM_IPROPERTY_COUNTY' ).'</b>: '. $this->p->county.'</div>';
    if ($this->p->region)   $sidecol .= '<div class="ip-sidecol"><b>'.JText::_('COM_IPROPERTY_REGION' ).'</b>: '. $this->p->region.'</div>';
    if ($this->p->stype)    $sidecol .= '<div class="ip-sidecol"><b>'.JText::_('COM_IPROPERTY_SALE_TYPE' ).'</b>: '. ipropertyHTML::get_stype($this->p->stype).'</div>';
    if ($this->p->mls_id)   $sidecol .= '<div class="ip-sidecol"><b>'.JText::_('COM_IPROPERTY_REF' ).'</b>: '. $this->p->mls_id.'</div>';
    if ($this->p->last_updated && $this->p->updated) $sidecol .= '<div class="ip-sidecol"><b>'.JText::_('COM_IPROPERTY_LAST_MODIFIED' ).'</b>: '. $this->p->last_updated.'</div>';

    // Display linked categories in sidebar
    if ($this->p->available_cats)
    {
        $sidecol .= '<div class="ip-sidecol ip-categories"><b>'.JText::_('COM_IPROPERTY_CATEGORY' ).':</b> ';
        $catcount = 0;
        foreach( $this->p->available_cats as $c )
        {
            $sidecol .= ipropertyHTML::getCatIcon($c, 20, false, true);
            $catcount++;
            if($catcount < count($this->p->available_cats)) $sidecol .= ', ';
        }
        $sidecol .= '</div>';
    }
    if ($this->settings->show_hits)
    {
        $sidecol .= '<div class="ip-sidecol ip-hits"><span class="label label-info">'.$this->p->hits.' '.JText::_('COM_IPROPERTY_HITS').'</span></div>';
    }
$sidecol .= '</div>';

//[[CUSTOM]] RI, to show Kalim's details by default
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query
    ->select(array('usg.group_id', 'ag . *'))
    ->from($db->quoteName('#__iproperty_agents', 'ag'))
    ->join('LEFT', $db->quoteName('#__users', 'us') . ' ON (' . $db->quoteName('us.id') . ' = ' . $db->quoteName('ag.user_id') . ')')
    ->join('LEFT', $db->quoteName('#__user_usergroup_map', 'usg') . ' ON (' . $db->quoteName('usg.user_id') . ' = ' . $db->quoteName('ag.user_id') . ')')
    ->where($db->quoteName('ag.user_id') . ' <> NULL OR '.$db->quoteName('ag.user_id').' <> 0 AND '.$db->quoteName('usg.group_id').' =8')
    ->order($db->quoteName('ag.id') . ' ASC');
 
$db->setQuery($query);
$superagent_result = $db->loadObject();
//echo '<pre>';print_r($superagent_result);exit;
$superagentlink  = JRoute::_(ipropertyHelperRoute::getAgentPropertyRoute($superagent_result->id.':'.$superagent_result->alias));
$superagentcompanylink = JRoute::_(ipropertyHelperRoute::getCompanyPropertyRoute($superagent_result->company));
$superagentcontactlink   = JRoute::_(ipropertyHelperRoute::getContactRoute('agent', $superagent_result->id.':'.$superagent_result->alias));
$superagentfullname  = $superagent_result->fname.' '.$superagent_result->lname;
$superagentfullname  = $superagent_result->fname.' '.$superagent_result->lname;
$superagentemail = $superagent_result->email;
$superagentphone = $superagent_result->phone;
$superagentmobile = $superagent_result->mobile;
$superagentfax = $superagent_result->fax;
//[[CUSTOM]] RI
//echo "WITHOUT"."<br/>";
//echo "<pre>"; print_r($this->agents);
$session = JFactory::getSession();
$AgentSessId = $session->get('AgentSessId');
//echo $AgentSessId;

if(!empty($AgentSessId)){
$db = JFactory::getDBO();
$query = "SELECT * from #__iproperty_agents WHERE `id` = ".$AgentSessId;
$db->setQuery($query);
$result = $db->loadObjectlist();
$this->agents = $result;
//echo "SESSION"."<br/>";
//echo "<pre>"; print_r($this->agents);	
}


if ($this->agents && $this->settings->show_agent && !$this->p->listing_info)
{                                
    foreach ($this->agents as $a)
    {
        $alink              = JRoute::_(ipropertyHelperRoute::getAgentPropertyRoute($a->id.':'.$a->alias));
        $colink             = JRoute::_(ipropertyHelperRoute::getCompanyPropertyRoute($a->company.':'.$a->co_alias));
        $agentcontactlink   = JRoute::_(ipropertyHelperRoute::getContactRoute('agent',$a->id.':'.$a->alias));
		if($a->live_profile){
			$website_link = 'http://'.$_SERVER['HTTP_HOST'].'/'.'usmetrorealty'.'/'.$a->live_profile;
		}
		
        if($a->agent_type == "1"){ 
           /* $sidecol .= '<div class="well span6">';
                if($a->icon && $this->settings->agent_show_image)
                {
                    $icon = ipropertyHTML::getIconpath($a->icon, 'agent');
                    $sidecol .= '<a href="'.$alink.'"><img src="'.$icon . '" width="'.$agent_photo_width.'" border="0" alt="" class="thumbnail" /></a>';
                }
                $sidecol .= '<a href="' . $alink . '"><b>' . ipropertyHTML::getAgentName($a->id) . '</b></a><br />';
                $sidecol .= '<a href="' . $colink . '">' . ipropertyHTML::getCompanyName($a->companyid) . '</a><br />';
                $sidecol .= '<div class="clearfix"></div><br />';

                // email and phone numbers
                if($a->email && $this->settings->agent_show_email) 
                    $sidecol .= '<div class="ip-sidecol sidecol-email"><b><abbr title="'.JText::_('COM_IPROPERTY_EMAIL').'">E:</abbr></b> ' . JHTML::_('email.cloak', $a->email.'?subject='.JText::_('Re').': '.rawurlencode($this->p->street_address), true, $a->email) . '</div>';
                if($a->phone && $this->settings->agent_show_phone) 
                    $sidecol .= '<div class="ip-sidecol sidecol-phone"><b><abbr title="'.JText::_('COM_IPROPERTY_PHONE').'">P:</abbr></b> ' . $a->phone . '</div>';
                if($a->mobile && $this->settings->agent_show_mobile) 
                    $sidecol .= '<div class="ip-sidecol sidecol-cell"><b><abbr title="'.JText::_('COM_IPROPERTY_MOBILE').'">M:</abbr></b> ' . $a->mobile . '</div>';
                $sidecol .= '<div class="clearfix"></div>';
                if($a->alicense && $this->settings->agent_show_license) 
                    $sidecol .= '<div class="ip-sidecol sidecol-license"><b><abbr title="'.JText::_('COM_IPROPERTY_LICENSE').'">L:</abbr></b> ' . $a->alicense .'</div>';
                
                // social media contact
                if ($this->settings->agent_show_social)
                {
                    if($a->msn) 
                        $sidecol .= '<div class="ip-sidecol sidecol-msn"><b><abbr title="'.JText::_('COM_IPROPERTY_MSN' ).'">MSN:</abbr></b> '.$a->msn.'</div>';
                    if($a->skype) 
                        $sidecol .= '<div class="ip-sidecol sidecol-skype"><b><abbr title="'.JText::_('COM_IPROPERTY_SKYPE' ).'">S:</abbr></b> <a href="skype:' . $a->skype . '?call"> ' . $a->skype . '</a></div>';
                    if($a->gtalk) 
                        $sidecol .= '<div class="ip-sidecol sidecol-gtalk"><b><abbr title="'.JText::_('COM_IPROPERTY_GTALK' ).'">G:</abbr></b> '.$a->gtalk.'</div>';
                    if($a->linkedin) 
                        $sidecol .= '<div class="ip-sidecol sidecol-linkedin"><b><abbr title="'.JText::_('COM_IPROPERTY_LINKEDIN' ).'">LI:</abbr></b> '.$a->linkedin.'</div>';
                    if($a->twitter) 
                        $sidecol .= '<div class="ip-sidecol sidecol-twitter"><b><abbr title="'.JText::_('COM_IPROPERTY_TWITTER' ).'">T:</abbr></b> '.$a->twitter.'</div>';
                    if($a->facebook) 
                        $sidecol .= '<div class="ip-sidecol sidecol-facebook"><b><abbr title="'.JText::_('COM_IPROPERTY_FACEBOOK' ).'">F:</abbr></b> '.$a->facebook.'</div>';
                    if($a->social1) 
                        $sidecol .= '<div class="ip-sidecol sidecol-facebook"><b><abbr title="'.JText::_('COM_IPROPERTY_SOCIAL1').'">O:</abbr></b> '.$a->social1.'</div>';
                }

                // show contact form button if email is set and contact agent setting is enabled
                if($a->email && $this->settings->agent_show_contact)
                    $sidecol .= '<div class="clearfix"></div><a href="'.$agentcontactlink.'" class="btn pull-right ip-agent-contact-btn">'.JText::_('COM_IPROPERTY_CONTACT_ME' ).'</a><div class="clearfix"></div>';               

                // @todo: this is kind of a hack to allow the plugin to return data in the loop. Maybe figure out a cleaner solution later
                $plugins_ext = $this->dispatcher->trigger('onAfterRenderPropertyAgent', array( &$a, &$this->settings ));
                if (count($plugins_ext)) $sidecol .= $plugins_ext[0];
                
            $sidecol .= '</div>';*/
			$sidecol .= '<div class="well span6"> <div class="row-fluid"> <div class="ip-featuredproperties-holder1 ">';
			if($a->icon && $this->settings->agent_show_image)
                {
                    $icon = ipropertyHTML::getIconpath($a->icon, 'agent');
                    $sidecol .= '<div class="ip-mod-thumb ip-featuredproperties-thumb-holder text-center" style="margin-right: 98px;"><a href="'.$alink.'"><img src="'.$icon . '" width="'.$agent_photo_width.'"   border="0" alt="" class="agent-Image" /></a> </div><div class="clearfix"></div>';
                }
			$sidecol .=	'<div class="ip-mod-desc ip-featuredproperties-desc-holder  text-center">';
			$sidecol .=	'<span class="agent-fullName">';
			$sidecol .= '<a href="' . $alink . '"><b>' . ipropertyHTML::getAgentName($a->id) . '</b></a>';
			$sidecol .=	'</span><br />';
			
			$sidecol .=	'<span class="agent-title">Broker</span><br>';
			
			$sidecol .=	'<span class="agent-phone"><em>Phone: </em>';
			if (strpos($a->phone,'-') !== false) {
                            $sidecol .=	 "(".substr($a->phone, 0, 3).") ".substr($a->phone, 3);
                        } else {
                           $sidecol .=	"(".substr($a->phone, 0, 3).") -".substr($a->phone, 3, 3)."-".substr($a->phone,6);
                        }
			$sidecol .=	'</span><br>';
			$sidecol .=	'<span class="agent-fax"><em>Fax: </em>';
			if (strpos($a->fax,'-') !== false) {
                            $sidecol .=	 "(".substr($a->fax, 0, 3).") ".substr($a->fax, 3);
                        } else {
                           $sidecol .=	"(".substr($a->fax, 0, 3).") -".substr($a->fax, 3, 3)."-".substr($a->fax,6);
                        }
			$sidecol .=	'</span><br>';
			
			$sidecol .=	'<span class="agent-mobile"><em>Mobile: </em>';
			if (strpos($a->mobile,'-') !== false) {
                            $sidecol .=	 "(".substr($a->mobile, 0, 3).") ".substr($a->mobile, 3);
                        } else {
                           $sidecol .=	"(".substr($a->mobile, 0, 3).") -".substr($a->mobile, 3, 3)."-".substr($a->mobile,6);
                        }
			$sidecol .=	'</span><br>';
			
			$sidecol .='<span class="agent-Email">';
            $sidecol .=' <a href="'.$agentcontactlink.'">Contact Agent</a>';
            $sidecol .='|';
            $sidecol .=' <a href="'.$website_link.'" target="_blank">Visit Website</a> ';
            $sidecol .=' </span><br/>';
			
			$sidecol .= '</div>';
			
			
			$sidecol .= '</div></div></div>';
        } else {

            $sidecol .= '<div class="well span6">
                <a href="'.$superagentlink.'"><b>'.$superagentfullname.'(Principle Broker)</b></a>
                <br>
                <a href="'.$superagentcompanylink.'">US Metro Realty</a>
                <br>
                <div class="clearfix">
                </div>
                <br>
                <div class="ip-sidecol sidecol-email">
                    <b><abbr title="Email">E:</abbr></b>
                    <span id="cloak10950">
                        <a href="mailto:'.$superagentemail.'">'.$superagentemail.'</a>
                    </span>
                </div>
                <div class="ip-sidecol sidecol-phone">
                    <b><abbr title="Phone">P:</abbr></b> '.$superagentphone.'<br>
                    <b><abbr title="Fax">F:</abbr></b> '.$superagentfax.'<br>
                    <b><abbr title="Cell">F:</abbr></b> '.$superagentmobile.'<br>
                </div>
                <div class="clearfix"></div>
                <div class="clearfix"></div>
                <a class="btn pull-right ip-agent-contact-btn" href="'.$superagentcontactlink.'">Contact Me</a>
                <div class="clearfix"></div></div>';

        }
    }                
} else {
    
    $sidecol .= '<div class="well span6">
        <a href="'.$superagentlink.'"><b>'.$superagentfullname.'(Principle Broker)</b></a>
        <br>
        <a href="'.$superagentcompanylink.'">US Metro Realty</a>
        <br>
        <div class="clearfix">
        </div>
        <br>
        <div class="ip-sidecol sidecol-email">
            <b><abbr title="Email">E:</abbr></b>
            <span id="cloak10950">
                <a href="mailto:'.$superagentemail.'">'.$superagentemail.'</a>
            </span>
        </div>
        <div class="ip-sidecol sidecol-phone">
            <b><abbr title="Phone">P:</abbr></b> '.$superagentphone.'<br>
            <b><abbr title="Fax">F:</abbr></b> '.$superagentfax.'<br>
            <b><abbr title="Cell">F:</abbr></b> '.$superagentmobile.'<br>
        </div>
        <div class="clearfix"></div>
        <div class="clearfix"></div>
        <a class="btn pull-right ip-agent-contact-btn" href="'.$superagentcontactlink.'">Contact Me</a>
        <div class="clearfix"></div></div>';
}

if ($this->openhouses)
{
    $sidecol .= '<div class="well">';
    foreach ($this->openhouses as $o)
    {
        $oh_title = ($o->name) ? $o->name : JText::_('COM_IPROPERTY_OPENHOUSE');
        
        $tipstart   = ($o->comments) ? '<span class="hasTooltip" title="'.JText::_('COM_IPROPERTY_OPENHOUSE' ).'::'.htmlentities($o->comments, ENT_QUOTES, 'UTF-8').'">' : '';
        $tipend     = ($o->comments) ? '</span>' : '';
        $start    	= JHTML::_('date', htmlspecialchars($o->startdate),JText::_('COM_IPROPERTY_DATE_FORMAT_IPOH'));
        $end		= JHTML::_('date', htmlspecialchars($o->enddate),JText::_('COM_IPROPERTY_DATE_FORMAT_IPOH'));
        $sidecol    .= '
            <div class="ip-sidecol oh-header"><span class="label label-info">'.$tipstart.$oh_title.$tipend.'</span></div>
            <div class="ip-sidecol oh-details">
                <div class="ip-sidecol oh-date-start alert alert-success small"><b>Start</b><br />'.$start.'</div>
                <div class="ip-sidecol oh-date-enda alert alert-error small"><b>End</b><br />'.$end.'</div>
            </div>';
    }
    $sidecol .= '</div>';
}

// @TODO: add Joomla tags
//$this->item->tagLayout = new JLayoutFile('joomla.content.tags'); 
//$sidecol .= $this->item->tagLayout->render($this->item->tags->itemTags);

// add the listing agent for pro sites
if ($this->p->listing_info){
	$listing_info = ipropertyHTML::getListingInfo($this->p, $this->params);
	$sidecol .= '<div class="ip-sidecol listing-details"><span class="ipsmall">'.$listing_info.'</span></div>';
}
echo $sidecol;
?>
<style>
.wtpladdrs{ height: 300px;}
.agent-Image {
    height: 118px !important;
}
</style>