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

$language = JFactory::getLanguage();
$language->load('com_iproperty', JPATH_SITE, 'en-GB', true);
$language->load('com_iproperty', JPATH_SITE, null, true);

// Initialize variables
$usethumb           = $params->get('usethumb', 1);
$iplayout           = $params->get('iplayout', 'rows');
$rowspan            = ($iplayout == 'rows') ? 12 : (12 / $params->get('columns', '12'));
$moduleclass_sfx    = ($params->get('moduleclass_sfx')) ? ' '.htmlspecialchars($params->get('moduleclass_sfx')) : '';

?>
<style>
.wtplthumb {
    padding-right: 4px !important;
}
</style>
<div class="row-fluid<?php echo $moduleclass_sfx; ?>">
    <?php
    $colcount = 0;
    foreach($items as $item)
    {
        $item->proplink = JRoute::_(ipropertyHelperRoute::getPropertyRoute($item->id.':'.$item->alias, '', true));
        ?>
        <div class="ip-featuredproperties-holder span<?php echo $rowspan; ?>">
            <?php if($iplayout == 'rows') echo '<div class="span12">'; ?>
                <div class="ip-mod-thumb ip-featuredproperties-thumb-holder">
                    <?php echo ipropertyHTML::getThumbnail($item->id, $item->proplink, $item->street_address, '', 'class="ip-featured-img thumbnail wtplthumb"', '', $usethumb); ?>
                    <?php if($params->get('show_banners', 1)) echo $item->banner; ?>
                </div>
            <?php 
            if($iplayout == 'rows'){
                echo '</div>';
            }else{
                echo '<div class="clearfix"></div>';
            }
            ?>
            <div class="ip-mod-desc ip-featuredproperties-desc-holder span12 text-center">
            	<strong>
                    <?php
                    //custom viral
                  // echo "<pre>"; print_r($items);exit;
                    if($item->sqft && $item->sqft != '0') echo $item->sqft." ".JText::_('COM_IPROPERTY_SQFTDD'); ?>
                     </strong>
                <em>
                <strong>
                    <?php
                    if($item->beds && $item->beds != '0') echo $item->beds ." ".JText::_('COM_IPROPERTY_BEDS');
                    /*if($item->locstate) echo ', '.ipropertyHTML::getStateName($item->locstate);*/
                    if($item->baths && $item->baths != '0.00') echo '  '.$item->baths." ".JText::_('COM_IPROPERTY_BATHS');
                    ?>
					<?php 
					if(($item->beds == '0') && ($item->baths == '0.00') ){ echo '  '.$item->short_description." <br/>"; }
					?>
                    </strong>
                </em>
                <strong>
                <?php
                	if($item->yearbuilt) echo '  '.$item->yearbuilt." ".JText::_('COM_IPROPERTY_BUILD');
                ?>
                </strong>
                <p>
                <?php
					 if($item->call_for_price==0){ 
						if($item->price2==0){
							echo JText::_('COM_IPROPERTY_PRICE');
							echo " ".$item->formattedprice."<br/>";  
						} else {
							echo JText::_('COM_IPROPERTY_PRICE');
							echo " ".'$'.number_format($item->price2,0)."<br/>";
						} 
					} else {
						echo JText::_('COM_IPROPERTY_PRICE');
						echo " ".$item->formattedprice."<br/>"; 
					} 
                	/*if($item->formattedprice){
						echo JText::_('COM_IPROPERTY_PRICE');
						echo " ". $item->formattedprice."<br/>"	;
					}*/
               
                	if($item->mls_id) echo JText::_('COM_IPROPERTY_MLS');
                	echo " ". $item->mls_id;
                ?>
                </p>
               <?php  if($item->short_description && $params->get('show_desc', 1)): ?>
                    <?php //echo ipropertyHTML::snippet($item->short_description, $params->get('preview_count', 200)) ?>
                <?php endif; ?>
            </div>
            <?php if($params->get('show_readmore', 1)): ?>
                <div class="ip-mod-readmore ip-featuredproperties-readmore span12 text-center">
                    <a href="<?php echo $item->proplink; ;?>" class="btn btn-primary readon"><?php echo JText::_('MOD_IP_FEATURED_READ_MORE'); ?></a>
                </div>
            <?php endif; ?>
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

