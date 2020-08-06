<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
//echo "<pre>"; print_r($this->video); exit;
defined( '_JEXEC' ) or die( 'Restricted access');

$agentlink          = JRoute::_(ipropertyHelperRoute::getAgentPropertyRoute($this->agent->id.':'.$this->agent->alias));
$companylink        = JRoute::_(ipropertyHelperRoute::getCompanyPropertyRoute($this->agent->companyid.':'.$this->agent->co_alias));
$agentcontactlink   = JRoute::_(ipropertyHelperRoute::getContactRoute('agent', $this->agent->id.':'.$this->agent->alias));

// check URL for agent website and add http if required
if ($this->agent->website && substr($this->agent->website, 0, 4) != 'http') $this->agent->website = 'http://'.$this->agent->website;
$agent_img  = ($this->settings->agent_show_image && $this->agent->icon && $this->agent->icon != 'nopic.png') ? ipropertyHTML::getIconpath($this->agent->icon, 'agent') : 'nopic.png';
$agent_span = ($agent_img) ? 8 : 12;
$divider = '&#160;<span class="divider">-</span>&#160;';

//echo "<pre>"; print_r($this->agent);
//echo "<pre>"; print_r($_SERVER['HTTP_HOST']);
if($this->agent->live_profile){
	$website_link = 'http://'.$_SERVER['HTTP_HOST'].'/'.usmetrorealty.'/'.$this->agent->live_profile;
}
?>

<div class="row-fluid ip-row-agent ip-row<?php echo $this->k; ?>">
    <div class="span12" itemscope itemtype="http://www.schema.org/RealEstateAgent">
        <?php if($agent_img): ?>
        <div class="span2 pull-left ip-agent-overview-img">
            <div class="ip-agent-thumb-holder">
                <div class="ip-agent-photo wtplagentpic">
                    <a href="<?php echo $agentlink; ?>">
                        <img itemprop="image" src="<?php echo $agent_img; ?>" alt="<?php echo $this->agent->name; ?>" width="<?php echo $this->agent_photo_width; ?>" class="thumbnail" />
                    </a>
                </div>
            </div>
        </div> 
        <?php endif; ?>
        <div class="span<?php echo $agent_span; ?> ip-agent-overview-desc">
            <?php if($this->ipauth->canEditAgent($this->agent->id)) echo '<div class="iplistaction">'.JHtml::_('icon.edit', $this->agent, 'agent').'</div>'; ?>
            <a href="<?php echo $agentlink; ?>"><b><span itemprop="name"><?php echo ipropertyHTML::getAgentName($this->agent->id); ?></span></b><br/></a>
            <?php if($this->agent->companyid): ?>
                <?php //echo $divider; ?><a href="<?php echo $companylink; ?>"><em><?php echo ipropertyHTML::getCompanyName($this->agent->companyid); ?></em></a>
            <?php endif; ?>
            <?php if($this->agent->title): ?>
                <br /><span class="ip-agent-title"><?php echo $this->agent->title; ?></span>
            <?php endif; ?>
            <ul class="nav nav-tabs ip-overview-tabs">
                <?php 
				if($website_link && $this->settings->agent_show_website && $this->agent->bio) echo '<li class="ip-website pull-right"><a href="'.$website_link.'" target="_blank" itemprop="url"><span class="icon-user ip-pointer hasTooltip" title="Agent Bio"></span></a></li>';	
                if($this->agent->email && $this->settings->agent_show_contact && JRequest::getVar('view') != 'contact') echo '<li class="ip-contact pull-right"><a href="' . $agentcontactlink . '"><span class="icon-envelope ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_CONTACT_AGENT' ).'"></span></a></li>';
                if(JRequest::getVar('view') != 'agentproperties') echo '<li class="ip-props pull-right"><a href="'.$agentlink.'"><span class="icon-search ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_VIEW_PROPERTIES' ).'"></span></a></li>';
               // if($this->agent->website && $this->settings->agent_show_website) echo '<li class="ip-website pull-right"><a href="'.$this->agent->website.'" target="_blank" itemprop="url"><span class="icon-home ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_VISIT').'"></span></a></li>';
			   if($website_link && $this->settings->agent_show_website) echo '<li class="ip-website pull-right"><a href="'.$website_link.'" target="_blank" itemprop="url"><span class="icon-home ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_VISIT').'"></span></a></li>';
				
				
                if($this->agent->featured) echo '<li class="ip_featured pull-right"><a href="'.$agentlink.'"><span class="icon-star ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_FEATURED').'"></span></a></li>';
                ?>
            </ul>
            <div class="row-fluid">
                <div class="span12">
                <div class="span12 pull-right wtplaccdetail"><strong><h5>Account Details</h5></strong></div>                  
                    <div class="span5 ip-agent-details wtplspan5">                
                        <ul class="agent-list-details small">
                            <?php
                            if($this->agent->phone && $this->settings->agent_show_phone) echo '<li class="ip-phone"><b><abbr title="'.JText::_('COM_IPROPERTY_PHONE').'">Phone :</abbr></b> <span class="ip-phone-container" itemprop="telephone">' . $this->agent->phone . '</span></li>';
                            if($this->agent->mobile && $this->settings->agent_show_mobile) echo '<li class="ip-cell"><b><abbr title="'.JText::_('COM_IPROPERTY_MOBILE' ).'">Mobile :</abbr></b> <span class="ip-phone-container" itemprop="telephone">' . $this->agent->mobile . '</span></li>';
                            if($this->agent->fax && $this->settings->agent_show_fax) echo '<li class="ip-fax"><b><abbr title="'.JText::_('COM_IPROPERTY_FAX' ).'">Fax :</abbr></b> <span itemprop="faxNumber">' . $this->agent->fax . '</span></li>';
                            if($this->agent->email && $this->settings->agent_show_email) echo '<li class="ip-email"><b><abbr title="'.JText::_('COM_IPROPERTY_EMAIL' ).'">Email :</abbr></b> <span itemprop="email">'.JHTML::_('email.cloak', $this->agent->email).'</span></li>';                                                
                            if($this->agent->alicense && $this->settings->agent_show_license) echo '<li class="ip-license"><b><abbr title="'.JText::_('COM_IPROPERTY_LICENSE' ).'">Licence :</abbr></b> '.$this->agent->alicense . '</li>';
                            ?>
                        </ul>
                    </div>
                    <?php if ($this->settings->agent_show_social): ?>
                    <div class="span3 ip-social">
                        <ul class="agent-list-details small">
                            <?php
                            if($this->agent->msn) {
                                if(filter_var($this->agent->msn, FILTER_VALIDATE_URL)){ 
                                    $this->agent->msn = '<a href="'.$this->agent->msn.'" target="_blank">'.$this->agent->msn.'</a>';
                                }
                                echo '<li class="ip_msn"><b><abbr title="'.JText::_('COM_IPROPERTY_MSN' ).'">MSN :</abbr></b> ' . $this->agent->msn . '</li>';
                            }
                            if($this->agent->skype) {
                                if(filter_var($this->agent->skype, FILTER_VALIDATE_URL)){ 
                                    $this->agent->skype = '<a href="'.$this->agent->skype.'" target="_blank">'.$this->agent->skype.'</a>';
                                }
                                echo '<li class="ip-skype"><b><abbr title="'.JText::_('COM_IPROPERTY_SKYPE' ).'">SKYPE :</abbr></b><a href="skype:' . $this->agent->skype . '?call"> ' . $this->agent->skype . '</a></li>';
                            }
                            if($this->agent->gtalk) {
                                if(filter_var($this->agent->gtalk, FILTER_VALIDATE_URL)){ 
                                    $this->agent->gtalk = '<a href="'.$this->agent->gtalk.'" target="_blank">'.$this->agent->gtalk.'</a>';
                                }
                                echo '<li class="ip-gtalk"><b><abbr title="'.JText::_('COM_IPROPERTY_GTALK' ).'">GTALK :</abbr></b> ' . $this->agent->gtalk . '</li>';
                            }
                            if($this->agent->linkedin) {
                                if(filter_var($this->agent->linkedin, FILTER_VALIDATE_URL)){ 
                                    $this->agent->linkedin = '<a href="'.$this->agent->linkedin.'" target="_blank">'.$this->agent->linkedin.'</a>';
                                }
                                echo '<li class="ip-linkedin"><b><abbr title="'.JText::_('COM_IPROPERTY_LINKEDIN' ).'">LINKEDIN :</abbr></b> ' . $this->agent->linkedin . '</li>';
                            }
                            if($this->agent->twitter) {
                                if(filter_var($this->agent->twitter, FILTER_VALIDATE_URL)){ 
                                    $this->agent->twitter = '<a href="'.$this->agent->twitter.'" target="_blank">'.$this->agent->twitter.'</a>';
                                }
                                echo '<li class="ip-twitter"><b><abbr title="'.JText::_('COM_IPROPERTY_TWITTER' ).'">TWITTER :</abbr></b> ' . $this->agent->twitter . '</li>';
                            }
                            if($this->agent->facebook) {
                                if(filter_var($this->agent->facebook, FILTER_VALIDATE_URL)){ 
                                    $this->agent->facebook = '<a href="'.$this->agent->facebook.'" target="_blank">'.$this->agent->facebook.'</a>';
                                }
                                echo '<li class="ip-facebook"><b><abbr title="'.JText::_('COM_IPROPERTY_FACEBOOK' ).'">FACEBOOK :</abbr></b> ' . $this->agent->facebook . '</li>';
                            }
                            if($this->agent->social1) {
                                if(filter_var($this->agent->social1, FILTER_VALIDATE_URL)){ 
                                    $this->agent->social1 = '<a href="'.$this->agent->social1.'" target="_blank">'.$this->agent->social1.'</a>';
                                }
                               // echo '<li class="ip-social1"><b><abbr title="'.JText::_('COM_IPROPERTY_SOCIAL1').'">O:</abbr></b> ' . $this->agent->social1 . '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <?php if ($this->settings->agent_show_address): ?>
                    <div class="span4 ip-agent-address">
                        <address>
                            <?php
                            if($this->agent->street || $this->agent->street2) echo '<strong><span itemprop="streetAddress">'.$this->agent->street.' '.$this->agent->street2.'</span></strong><br />';
                            if($this->agent->city && ipropertyHTML::getCityName($this->agent->city)){ echo '<span itemprop="addressLocality">'.ipropertyHTML::getCityName($this->agent->city).'</span>';}else{ echo '<span itemprop="addressLocality">'.$this->agent->city.'</span>';} 
                            if($this->agent->locstate) echo ', <span itemprop="region">'.ipropertyHTML::getStateName($this->agent->locstate) . '</span> ';
                            if($this->agent->province) echo ', <span itemprop="region">'.$this->agent->province . '</span> ';
                            if($this->agent->postcode) echo '<span itemprop="postalCode">'.$this->agent->postcode.'</span>';
                            if($this->agent->country) echo '<br /><span itemprop="country">'.ipropertyHTML::getCountryName($this->agent->country).'</span>';
                            ?>
                        </address>
                    </div> 
                    <?php endif; ?>
                </div>
            </div>
            <div class="clearfix"></div>
            
            <div class="row-fluid custom-total-items-for-buyer">
                <div class="span12"> 
                    <div class="span4 ip-agent-details">
                        <ul class="agent-list-details small">
                            <?php
                            if($this->agent->agent_type==2){
                                
                                $searchCriNum = ipropertyHTML::getBuyerSearchCriteriaNum($this->agent->id);
                                echo '<li><strong><h5>Search Criteria</h5></strong></li>';
                                if($searchCriNum){
                                    echo '<li><span itemprop="country"><b>Total Search Criteria</b> : '.$searchCriNum.'</span></li>';
                                } else {
                                    echo '<li><span itemprop="country"><b>Total Search Criteria</b> : 0</span></li>';
                                }
                                echo '<hr>';
                                $savedPropsNum = ipropertyHTML::getSavedPropsNum();
                                echo '<li><strong><h5>Properties</h5></strong></li>';
                                if($savedPropsNum){
                                    echo '<li><span itemprop="country"><b>Total Saved Properties</b> : '.$savedPropsNum.'</span></li>';
                                } else {
                                    echo '<li><span itemprop="country"><b>Total Saved Properties</b> : 0</span></li>';                                    
                                }
                                echo '<hr>';
                                $savedHelpsNum = ipropertyHTML::getBuyerHelpDeskNum($this->agent->id);
                                echo '<li><strong><h5>Help Desk</h5></strong></li>';
                                if($savedHelpsNum){
                                    echo '<li><span itemprop="country">You created <b>'.$savedHelpsNum.' tickets, '.$savedHelpsNum.' open </b>and <b>0 closed</b>.</span></li>';
                                } else {
                                    echo '<li><span itemprop="country">You created <b>0 tickets, 0 open </b>and <b>0 closed</b>.</span></li>';                                    
                                }
                            }
                            ?>
                            <?php
                            if(($this->agent->agent_type) == 3){
                                $myListingNum = ipropertyHTML::getMyListingNum();
                                echo '<strong><h5>Properties</h5></strong>';
                                if($myListingNum){
                                    echo '<li><span itemprop="country"><b>Total Properties Created</b> : '.$myListingNum.'</span></li>';
                                } else {
                                    echo '<li><span itemprop="country"><b>Total Properties Created</b> : 0</span></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            
            <?php
            if($this->agent->bio && JRequest::getVar('view') != 'agents' && JRequest::getVar('view') != 'companyagents'):
                echo '<div class="clearfix nav-tabs"></div>';
                echo '<div class="ip-agentbio">'.JHTML::_('content.prepare', $this->agent->bio).'</div>';
            endif;
            $this->dispatcher->trigger( 'onAfterRenderAgentList', array( &$this->agent, &$this->settings ) );
            ?>
        </div>
        
    </div>
    <div class="span12 main_video">
        <div class="span12 pull-left ip-agent-overview-img big_caption_video">
            <?php //echo "<pre>"; print_r($this->video); exit;
             foreach($this->video as $video) {
                if($video->agent_id == $this->agent->id){
                    $a = strstr($video->upload_video,"http");
                    if($a){
                        //echo $video->upload_video;
                        $v = end(explode('=',$video->upload_video));
                        ?>
                        <div class="ip-agent-thumb-holder span4 small_caption_video">
                        <span class="caption"><?php echo $video->caption?></span>
                            {youtube}<?php echo $v;?>{/youtube}
                         </div>
                        <?php
                    } else {
                        $ext = end(explode('.',$video->upload_video));
                        $pathInfo = pathinfo($video->upload_video);
                        $video->upload_video = $pathInfo['filename']; ?>
                        <?php if($ext == 'mp4'){ ?>
                            <div class="ip-agent-thumb-holder span4 small_caption_video">
                            <span class="caption"><?php echo $video->caption?></span>
                                {mp4}<?php echo $video->agent_id."/".$video->upload_video;?>{/mp4}

                           </div>
                           <?php } else if($ext == 'avi'){ ?>
                            <div class="ip-agent-thumb-holder span4 small_caption_video">
                            <span class="caption"><?php echo $video->caption?></span>
                                {avi}<?php echo $video->agent_id."/".$video->upload_video;?>{/avi}
                           </div>
                           <?php } else if($ext == '3gp'){ ?>
                            <div class="ip-agent-thumb-holder span4 small_caption_video">
                            <span class="caption"><?php echo $video->caption?></span>
                                {3gp}<?php echo $video->agent_id."/".$video->upload_video;?>{/3gp}
                           </div>
                           <?php } else if($ext == 'wmv'){ ?>
                            <div class="ip-agent-thumb-holder span4 small_caption_video">
                            <span class="caption"><?php echo $video->caption?></span>
                                {wmv}<?php echo $video->agent_id."/".$video->upload_video;?>{/wmv}
                           </div>
                           <?php } else if($ext == 'flv'){ ?>
                            <div class="ip-agent-thumb-holder span4 small_caption_video">
                            <span class="caption"><?php echo $video->caption?></span>
                                {flv}<?php echo $video->agent_id."/".$video->upload_video;?>{/flv}
                           </div>
                        <?php } ?>
            <?php } } }?>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<style>
.ip-agent-thumb-holder {
    margin: 0px;
    overflow: hidden;
    width: 200px;
}
.ip-agent-thumb-holder.span4 {
    margin-top: 8px;
}
.ip-row-agent {
    border: 1px solid #0b0076;
    margin-bottom: 14px;
    border-radius: 4px;
    margin-left: 0px !important;
    padding: 15px 0;
}
ul.agent-list-details li{ list-style:none;}
.ip-row-agent  .nav-tabs > li > a {

    padding-top: 8px;
    padding-bottom: 8px;
    line-height: 22px;
  
    background: #0b0076;
    color: #fff !important;

}

.span8.ip-agent-overview-desc{ width:82%; }
.nav.nav-tabs.ip-overview-tabs{margin-top:-18px;}
.wtplagentpic{
border: 1px solid #000;
margin-right: 100px;	
}
.wtplspan5 {
   margin-left: -17px !important;
}
.wtplaccdetail{ margin-left: -17px !important; }
.small {
    font-size: 14px !important;
}
</style>