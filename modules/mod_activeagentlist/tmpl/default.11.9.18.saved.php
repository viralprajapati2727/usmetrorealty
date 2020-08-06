<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Iproperty
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Initialize variables
$usethumb           = $params->get('usethumb', 1);
$iplayout           = $params->get('iplayout', 'rows');
$rowspan            = ($iplayout == 'rows') ? 12 : (12 / $params->get('columns', '12'));
$moduleclass_sfx    = ($params->get('moduleclass_sfx')) ? ' '.htmlspecialchars($params->get('moduleclass_sfx')) : '';
//echo "<pre>"; print_r($items); exit;

$session = JFactory::getSession();
$AgentSessId = $session->get('AgentSessId');
//echo $AgentSessId;

if(!empty($AgentSessId)){
$db = JFactory::getDBO();
$query = "SELECT * from #__iproperty_agents WHERE `id` = ".$AgentSessId;
$db->setQuery($query);
$result = $db->loadObjectlist();
$items = $result;
//echo "SESSION"."<br/>";
//echo "<pre>"; print_r($items);	
}


?>

<div class="row-fluid<?php echo $moduleclass_sfx; ?>">
    <?php
    $colcount = 0;
    foreach($items as $item)
    {
		//echo "<pre>"; print_r($item);
		
		
        $item->proplink = JRoute::_(ipropertyHelperRoute::getPropertyRoute($item->id.':'.$item->alias, '', true));
        ?>
        <div class="ip-featuredproperties-holder ">
            <?php if($iplayout == 'rows') echo '<div class="span12">'; ?>
                <div class="ip-mod-thumb ip-featuredproperties-thumb-holder">
                <?php $icon = ipropertyHTML::getIconpath($item->icon, 'agent');
                if (getimagesize($icon) !== false) {
                    $image_src = $icon;
                } else {
                    $image_src = 'media/com_iproperty/agents/agent.png';
                }
                ?>
            <img class="agent-Image" src="<?php echo $image_src ;?>" width="100px" height="150px" />
        </div>
            <?php 
            if($iplayout == 'rows'){
                echo '</div>';
            }else{
                echo '<div class="clearfix"></div>';
            }
            ?>
            <div class="ip-mod-desc ip-featuredproperties-desc-holder  text-center">
                <span class="agent-fullName">
                    <a href="index.php?option=com_iproperty&view=agentproperties&id=<?php echo $item->id; ?>&Itemid=390"><?php echo $item->fname." ".$item->lname; ?></a>
                </span><br/>
				<?php if(!empty($item->company)){ ?>
                    <span class="agent-title"><a href="index.php?option=com_iproperty&view=companyproperties&id=<?php echo $item->company; ?>&Itemid=323"><?php echo ipropertyHTML::getCompanyName($item->company); ?></a></span><br/>
                <?php } ?>
                <?php if(!empty($item->title)){ ?>
                    <span class="agent-title"><?php echo $item->title;?></span><br/>
                <?php } ?>
                <?php if(!empty($item->phone)){ ?>
                <span class="agent-phone">
                    <em>Phone: </em>
                    <?php
                        if (strpos($item->phone,'-') !== false) {
                            echo "(".substr($item->phone, 0, 3).") ".substr($item->phone, 3);
                        } else {
                           echo "(".substr($item->phone, 0, 3).") -".substr($item->phone, 3, 3)."-".substr($item->phone,6);
                        }
                    ?>
                </span><br/>
                <?php } ?>
                <?php if(!empty($item->fax)){ ?>
                    <span class="agent-fax">
                        <em>Fax: </em>
                        <?php
                        if (strpos($item->fax,'-') !== false) {
                            echo "(".substr($item->fax, 0, 3).") ".substr($item->fax, 3);
                        } else {
                           echo "(".substr($item->fax, 0, 3).") -".substr($item->fax, 3, 3)."-".substr($item->fax,6);
                        }
                    ?>
                    </span><br/>
                <?php } ?>
                <?php if(!empty($item->mobile)){ ?>
                    <span class="agent-mobile">
                        <em>Mobile: </em>
                        <?php
                        if (strpos($item->mobile,'-') !== false) {
                            echo "(".substr($item->mobile, 0, 3).") ".substr($item->mobile, 3);
                        } else {
                           echo "(".substr($item->mobile, 0, 3).") -".substr($item->mobile, 3, 3)."-".substr($item->mobile,6);
                        }
                    ?>
                    </span><br/>
                <?php } ?>
				<?php if(!empty($item->email)){ ?>
                    <span class="agent-mobile">
                        <em>Email: </em>
                        <a href="mailto:<?php echo $item->email; ?>" target="_blank"><?php echo $item->email; ?></a>
                    </span><br/>
                <?php } ?>
                <?php if(!empty($item->mobile)){ ?>
                    <span class="agent-Email">
                        <a href="index.php?option=com_iproperty&view=contact&id=<?php echo $item->id; ?>:<?php echo $item->alias;?>&layout=agent&Itemid=390">Contact Agent</a>
                            <?php if(!empty($item->live_profile)){ 
                                //if($item->id == '46'){
								$website_link = 'http://'.$_SERVER['HTTP_HOST'].'/usmrmainsaved_/'.$item->live_profile;
								} else {
								$website_link = 'http://'.$_SERVER['HTTP_HOST'].'/usmrmainsaved_';	
								}	
                            ?>
                            |
                            <a href="<?php echo $website_link ;?>" target="_blank">Visit Website</a> 
                            <?php //} //}?>
                    </span><br/>
                <?php } ?>
				<?php if(!empty($item->bio)){ ?>
					<span class="agent-Email"><a href="<?php echo $website_link ;?>" target="_blank">Agent Bio</a> </span>
				<?php } ?>
            </div>
        </div>
        <?php
        $colcount++;
        
        // we want to end div with row fluid class and start a new one if:
        // a) we are using the row layout - each row should be new
        // b) if using the column layout - if the column count has been reached
        if($iplayout == 'rows' || ($iplayout == 'columns' && $colcount == $params->get('columns')))
        {
            $colcount = 0;
            echo '</div><div class="row-fluid'.$moduleclass_sfx.'">';
        }
    }
    ?>
</div>
<style>

.agent-Image {
    height: 118px !important;
}
</style>
