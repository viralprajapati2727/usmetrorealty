<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
JHtml::_('bootstrap.tooltip');
//echo "<pre>"; print_r($this->items);
//echo "<pre>"; print_r($this->agentProfile); 
//echo "<pre>"; print_r($this->video); exit;

defined( '_JEXEC' ) or die( 'Restricted access');

$agentlink          = JRoute::_(ipropertyHelperRoute::getAgentPropertyRoute($this->agentProfile->id.':'.$this->agentProfile->alias));
$companylink        = JRoute::_(ipropertyHelperRoute::getCompanyPropertyRoute($this->agentProfile->companyid.':'.$this->agentProfile->co_alias));
$agentcontactlink   = JRoute::_(ipropertyHelperRoute::getContactRoute('agent', $this->agentProfile->id.':'.$this->agentProfile->alias));
$TitleName = JRequest::getVar("agentname");
// check URL for agent website and add http if required
if ($this->agentProfile->website && substr($this->agentProfile->website, 0, 4) != 'http') $this->agentProfile->website = 'http://'.$this->agentProfile->website;
$agent_img  = ($this->settings->agent_show_image && $this->agentProfile->icon && $this->agentProfile->icon != 'nopic.png') ? ipropertyHTML::getIconpath($this->agentProfile->icon, 'agent') : 'nopic.png';
$agent_span = ($agent_img) ? 8 : 12;
$divider = '&#160;<span class="divider">-</span>&#160;';

if($this->agentProfile->live_profile){
	$website_link = 'http://'.$_SERVER['HTTP_HOST'].'/'.usmetrorealty.'/'.$this->agentProfile->live_profile;
}
?>

<div class="row-fluid ip-row-agent ip-row<?php echo $this->k; ?>">
    <div class="span12" itemscope itemtype="http://www.schema.org/RealEstateAgent">
        <?php if($agent_img): ?>
        <div class="span2 pull-left ip-agent-overview-img">
            <div class="ip-agent-thumb-holder">
                <div class="ip-agent-photo wtplagentpic">
                    <a href="<?php echo $agentlink; ?>">
                        <img itemprop="image" src="<?php echo $agent_img; ?>" alt="<?php echo $this->agentProfile->name; ?>" width="<?php echo $this->agent_photo_width; ?>" class="thumbnail" />
                    </a>
                </div>
            </div>
        </div> 
        <?php endif; ?>
        <div class="span<?php echo $agent_span; ?> ip-agent-overview-desc">
            <?php if($this->ipauth->canEditAgent($this->agentProfile->id)) //echo '<div class="iplistaction">'.JHtml::_('icon.edit', $this->agentProfile, 'agent').'</div>'; ?>
            <a href="<?php echo $agentlink; ?>"><b><span itemprop="name"><?php echo ipropertyHTML::getAgentName($this->agentProfile->id); ?></span></b><br/></a>
            <?php if($this->agentProfile->companyid): ?>
                <?php //echo $divider; ?><a href="<?php echo $companylink; ?>"><em><?php echo ipropertyHTML::getCompanyName($this->agentProfile->companyid); ?></em></a>
            <?php endif; ?>
            <?php if($this->agentProfile->title): ?>
                <br /><span class="ip-agent-title"><?php echo $this->agentProfile->title; ?></span>
            <?php endif; ?>
            <ul class="nav nav-tabs ip-overview-tabs">
                <?php
				if($website_link && $this->settings->agent_show_website && $this->agentProfile->bio) echo '<li class="ip-website pull-right"><a href="'.$website_link.'" target="_blank" itemprop="url"><span class="icon-user ip-pointer hasTooltip" title="Agent Bio"></span></a></li>';
				
                if($this->agentProfile->email && $this->settings->agent_show_contact && JRequest::getVar('view') != 'contact') echo '<li class="ip-contact pull-right"><a href="' . $agentcontactlink . '"><span class="icon-envelope ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_CONTACT_AGENT' ).'"></span></a></li>';
                if(JRequest::getVar('view') != 'agentproperties') echo '<li class="ip-props pull-right"><a href="'.$agentlink.'"><span class="icon-search ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_VIEW_PROPERTIES' ).'"></span></a></li>';
               // if($this->agentProfile->website && $this->settings->agent_show_website) echo '<li class="ip-website pull-right"><a href="'.$this->agentProfile->website.'" target="_blank" itemprop="url"><span class="icon-home ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_VISIT').'"></span></a></li>';
				if($website_link && $this->settings->agent_show_website) echo '<li class="ip-website pull-right"><a href="'.$website_link.'" target="_blank" itemprop="url"><span class="icon-home ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_VISIT').'"></span></a></li>';
				
                if($this->agentProfile->featured) echo '<li class="ip_featured pull-right"><a href="'.$agentlink.'"><span class="icon-star ip-pointer hasTooltip" title="'.JText::_('COM_IPROPERTY_FEATURED').'"></span></a></li>';
                ?>
            </ul>
            <div class="row-fluid">
                <div class="span12">
                <div class="span12 pull-right wtplaccdetail"><strong><h5>Account Details</h5></strong></div>                  
                    <div class="span5 ip-agent-details wtplspan5">                
                        <ul class="agent-list-details small">
                            <?php

                            if($this->agentProfile->phone && $this->settings->agent_show_phone) echo '<li class="ip-phone"><b><abbr title="'.JText::_('COM_IPROPERTY_PHONE').'">Phone :</abbr></b> <span class="ip-phone-container" itemprop="telephone">' . $this->agentProfile->phone . '</span></li>';
                            if($this->agentProfile->mobile && $this->settings->agent_show_mobile) echo '<li class="ip-cell"><b><abbr title="'.JText::_('COM_IPROPERTY_MOBILE' ).'">Mobile :</abbr></b> <span class="ip-phone-container" itemprop="telephone">' . $this->agentProfile->mobile . '</span></li>';
                            if($this->agentProfile->fax && $this->settings->agent_show_fax) echo '<li class="ip-fax"><b><abbr title="'.JText::_('COM_IPROPERTY_FAX' ).'">Fax :</abbr></b> <span itemprop="faxNumber">' . $this->agentProfile->fax . '</span></li>';
                            if($this->agentProfile->email && $this->settings->agent_show_email) echo '<li class="ip-email"><b><abbr title="'.JText::_('COM_IPROPERTY_EMAIL' ).'">Email :</abbr></b> <span itemprop="email">'.JHTML::_('email.cloak', $this->agentProfile->email).'</span></li>';                                                
                            if($this->agentProfile->alicense && $this->settings->agent_show_license) echo '<li class="ip-license"><b><abbr title="'.JText::_('COM_IPROPERTY_LICENSE' ).'">Licence :</abbr></b> '.$this->agentProfile->alicense . '</li>';
                            ?>
                        </ul>
                    </div>
                    <?php if ($this->settings->agent_show_social): ?>
                    <div class="span3 ip-social">
                        <ul class="agent-list-details small">
                            <?php

                            if($this->agentProfile->msn) {
                                if(filter_var($this->agentProfile->msn, FILTER_VALIDATE_URL)){ 
                                    $this->agentProfile->msn = '<a href="'.$this->agentProfile->msn.'" target="_blank">'.$this->agentProfile->msn.'</a>';
                                }
                                echo '<li class="ip_msn"><b><abbr title="'.JText::_('COM_IPROPERTY_MSN' ).'">MSN :</abbr></b> ' . $this->agentProfile->msn . '</li>';
                            }
                            if($this->agentProfile->skype) {
                                if(filter_var($this->agentProfile->skype, FILTER_VALIDATE_URL)){ 
                                    $this->agentProfile->skype = '<a href="'.$this->agentProfile->skype.'" target="_blank">'.$this->agentProfile->skype.'</a>';
                                }
                                echo '<li class="ip-skype"><b><abbr title="'.JText::_('COM_IPROPERTY_SKYPE' ).'">SKYPE :</abbr></b><a href="skype:' . $this->agentProfile->skype . '?call"> ' . $this->agentProfile->skype . '</a></li>';
                            }
                            if($this->agentProfile->gtalk) {
                                if(filter_var($this->agentProfile->gtalk, FILTER_VALIDATE_URL)){ 
                                    $this->agentProfile->gtalk = '<a href="'.$this->agentProfile->gtalk.'" target="_blank">'.$this->agentProfile->gtalk.'</a>';
                                }
                                echo '<li class="ip-gtalk"><b><abbr title="'.JText::_('COM_IPROPERTY_GTALK' ).'">GTALK :</abbr></b> ' . $this->agentProfile->gtalk . '</li>';
                            }
                            if($this->agentProfile->linkedin) {
                                if(filter_var($this->agentProfile->linkedin, FILTER_VALIDATE_URL)){ 
                                    $this->agentProfile->linkedin = '<a href="'.$this->agentProfile->linkedin.'" target="_blank">'.$this->agentProfile->linkedin.'</a>';
                                }
                                echo '<li class="ip-linkedin"><b><abbr title="'.JText::_('COM_IPROPERTY_LINKEDIN' ).'">LINKEDIN :</abbr></b> ' . $this->agentProfile->linkedin . '</li>';
                            }
                            if($this->agentProfile->twitter) {
                                if(filter_var($this->agentProfile->twitter, FILTER_VALIDATE_URL)){ 
                                    $this->agentProfile->twitter = '<a href="'.$this->agentProfile->twitter.'" target="_blank">'.$this->agentProfile->twitter.'</a>';
                                }
                                echo '<li class="ip-twitter"><b><abbr title="'.JText::_('COM_IPROPERTY_TWITTER' ).'">TWITTER :</abbr></b> ' . $this->agentProfile->twitter . '</li>';
                            }
                            if($this->agentProfile->facebook) {
                                if(filter_var($this->agentProfile->facebook, FILTER_VALIDATE_URL)){ 
                                    $this->agentProfile->facebook = '<a href="'.$this->agentProfile->facebook.'" target="_blank">'.$this->agentProfile->facebook.'</a>';
                                }
                                echo '<li class="ip-facebook"><b><abbr title="'.JText::_('COM_IPROPERTY_FACEBOOK' ).'">FACEBOOK :</abbr></b> ' . $this->agentProfile->facebook . '</li>';
                            }
                            if($this->agentProfile->social1) {
                                if(filter_var($this->agentProfile->social1, FILTER_VALIDATE_URL)){ 
                                    $this->agentProfile->social1 = '<a href="'.$this->agentProfile->social1.'" target="_blank">'.$this->agentProfile->social1.'</a>';
                                }
                                //echo '<li class="ip-social1"><b><abbr title="'.JText::_('COM_IPROPERTY_SOCIAL1').'">O:</abbr></b> ' . $this->agentProfile->social1 . '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                    <?php if ($this->settings->agent_show_address): ?>
                    <div class="span4 ip-agent-address">
                        <address>
                            <?php
                            if($this->agentProfile->street || $this->agentProfile->street2) echo '<strong><span itemprop="streetAddress">'.$this->agentProfile->street.' '.$this->agentProfile->street2.'</span></strong><br />';
                            if($this->agentProfile->city && ipropertyHTML::getCityName($this->agentProfile->city)){ echo '<span itemprop="addressLocality">'.ipropertyHTML::getCityName($this->agentProfile->city).'</span>'; } else { echo '<span itemprop="addressLocality">'.$this->agentProfile->city.'</span>';}
                            if($this->agentProfile->locstate) echo ', <span itemprop="region">'.ipropertyHTML::getStateName($this->agentProfile->locstate) . '</span> ';
                            if($this->agentProfile->province) echo ', <span itemprop="region">'.$this->agentProfile->province . '</span> ';
                            if($this->agentProfile->postcode) echo '<span itemprop="postalCode">'.$this->agentProfile->postcode.'</span>';
                            if($this->agentProfile->country) echo '<br /><span itemprop="country">'.ipropertyHTML::getCountryName($this->agentProfile->country).'</span>';
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
                            if($this->agentProfile->agent_type==2){
                                
                                $searchCriNum = ipropertyHTML::getBuyerSearchCriteriaNum($this->agentProfile->id);
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
                                $savedHelpsNum = ipropertyHTML::getBuyerHelpDeskNum($this->agentProfile->id);
                                echo '<li><strong><h5>Help Desk</h5></strong></li>';
                                if($savedHelpsNum){
                                    echo '<li><span itemprop="country">You created <b>'.$savedHelpsNum.' tickets, '.$savedHelpsNum.' open </b>and <b>0 closed</b>.</span></li>';
                                } else {
                                    echo '<li><span itemprop="country">You created <b>0 tickets, 0 open </b>and <b>0 closed</b>.</span></li>';                                    
                                }
                            }
                            ?>
                            <?php
                            if(($this->agentProfile->agent_type) == 3){
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
            if($this->agentProfile->bio && JRequest::getVar('view') != 'agents' && JRequest::getVar('view') != 'companyagents'):
                echo '<div class="clearfix nav-tabs"></div>';
                echo '<div class="ip-agentbio"><div class="span12 pull-right wtplaccdetail"><strong><h5 class="visible visible-first">Agent Bio</h5></strong></div>'.JHTML::_('content.prepare', $this->agentProfile->bio).'</div>';
            endif;
            $this->dispatcher->trigger( 'onAfterRenderAgentList', array( &$this->agent, &$this->settings ) );
            ?>
        </div>
        
    </div>
    <div class="span12 main_video">
        <div class="span12 pull-left ip-agent-overview-img big_caption_video">
            <?php //echo "<pre>"; print_r($this->video); exit;

             foreach($this->video as $video) {
                if($video->agent_id == $this->agentProfile->id){
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
                        $video->upload_video = $pathInfo['filename']; 
                        $extra = array();
                        ?>

                        <?php if($ext == 'mp4'){ 
                            ?>
                            <div class="ip-agent-thumb-holder span4 small_caption_video">
                            <span class="caption"><?php echo $video->caption?></span>
                                {mp4}<?php echo $video->agent_id."/".$video->upload_video;?>{/mp4}
                           </div>
                           <?php }  else if($ext == 'avi'){ ?>
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
    <div class="span12 main_testimonials">
        <?php
        // /var_dump($this->testimonials); exit;
        foreach($this->testimonials as $testimonials) {
            if($testimonials->agent_id == $this->agentProfile->id){
        ?>
        <div class="agent_testimonials">
            <div class="agent_test_image">
            <?php 
                if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$testimonials->id.'.png')){
                  echo  '<img class="RSWS_testi_img" src="'.JURI::root().'images/com_rsmonials/'.$testimonials->id.'.png">';
                } else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$testimonials->id.'.jpg')){
                   echo '<img class="RSWS_testi_img" src="'.JURI::root().'images/com_rsmonials/'.$testimonials->id.'.jpg">';
                } else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$testimonials->id.'.jpeg')){
                    '<img class="RSWS_testi_img" src="'.JURI::root().'images/com_rsmonials/'.$testimonials->id.'.jpeg">';
                } else if(file_exists(JPATH_ROOT.DS.'images'.DS.'com_rsmonials'.DS.$testimonials->id.'.gif')){
                    '<img class="RSWS_testi_img" src="'.JURI::root().'images/com_rsmonials/'.$testimonials->id.'.gif">';
                } 
            ?>
            </div>
            <div class="agent_testimonial">
            <p><?php echo $testimonials->comment;?></p>
        </div>
        <div class="agent_test_date">
            <em>
                <span>Date of Posting:<?php echo $testimonials->date;?></span><br/>
                <span>Posted By:<?php echo $testimonials->fname." ".$testimonials->lname;?></span><br/>
                <span><?php echo $testimonials->fname." ".$testimonials->lname."  ".$testimonials->location;?></span><br/>
                <span><?php echo $testimonials->website;?></span><br/>
            </em>
        </div>
        </div>
        <?php } } ?>
    </div>

</div>

<div class="clearfix"></div>
<?php

    // featured properties top position
    if( $this->featured && $this->enable_featured && $this->settings->featured_pos == 0 ){
        echo '
        <h2 class="ip-property-header">'.JText::_( 'COM_IPROPERTY_FEATURED_PROPERTIES' ).'</h2>';
        $this->k = 0;
        foreach( $this->featured as $f ){
            $this->p = $f;
            echo $this->loadTemplate('property');
            $this->k = 1 - $this->k;
        }
    }
    
    // load quick search tmpl
    if ($this->params->get('qs_show_quicksearch', 1)){
        echo $this->loadTemplate('quicksearch');
    }

    // display results for properties
    if ($this->items)
    {

        echo 
            '<h2 class="ip-property-header">'.JText::_('COM_IPROPERTY_PROPERTIES_HANDLED_BY' ).' '.ipropertyHTML::getAgentName($this->agentProfile->id).' </h2><span class="pull-right small ip-pagination-results">'.$this->pagination->getResultsCounter().'</span><div class="clearfix"></div>';
            $this->k = 0;
            foreach($this->items as $p) :
                $this->p = $p;
                echo $this->loadTemplate('property');
                $this->k = 1 - $this->k;
            endforeach;
        echo

            '<div class="pagination">
                '.$this->pagination->getPagesLinks().'<br />'.$this->pagination->getPagesCounter().'
             </div>';
    } else { // no results tmpl
        echo $this->loadTemplate('noresult');
    }

    // featured properties bottom position
    if( $this->featured && $this->enable_featured && $this->settings->featured_pos == 1 ){
        echo '
        <h2 class="ip-property-header">'.JText::_( 'COM_IPROPERTY_FEATURED_PROPERTIES' ).'</h2>';
        $this->k = 0;
        foreach( $this->featured as $f ){
            $this->p = $f;
            echo $this->loadTemplate('property');
            $this->k = 1 - $this->k;
        }
    }
    // display disclaimer if set in params
    if ($this->params->get('show_ip_disclaimer') && $this->settings->disclaimer)
    {
        echo '<div class="well well-small" id="ip-disclaimer">'.$this->settings->disclaimer.'</div>';
    }
    // display footer if enabled
    if ($this->settings->footer == 1) echo ipropertyHTML::buildThinkeryFooter(); 
    ?>
	
<style>
.ip-agent-thumb-holder {
    margin: 0px;
    overflow: hidden;
    width: 200px;
}
.ip-agent-thumb-holder.span4 {
    margin-top: 8px;
}
</style>
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
<script type="text/javascript">

jQuery(document).ready(function ()
{
    var TitleName = '<?php echo $this->agentProfile->fname." ".$this->agentProfile->lname; ?>';
    jQuery('title').text(TitleName);
});
</script>
