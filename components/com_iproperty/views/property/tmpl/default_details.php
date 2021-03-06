<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
$colspan    = ($this->print) ? 12 : 12;
//echo "<pre>"; print_r($this->p); exit;
?>


<div class="row-fluid">
    <div class="span12">
        <div class="span<?php echo $colspan; ?> pull-left ip_details_wrapper">
            <div class="row-fluid">
                <div class="span6">
				<!-- #1st-->
                <?php if ($this->p->property_type): 
                        if($this->p->property_type == 'A' || $this->p->property_type == 'At'){
                            $property_type = 'Residential';
                        }else if($this->p->property_type == 'B'){
                            $property_type = 'Residential Rental';
                        }else if($this->p->property_type == 'C' || $this->p->property_type == 'Si'){
                            $property_type = 'Land and Lots';
                        } else if($this->p->property_type == 'D' || $this->p->property_type == 'In' || $this->p->property_type == 'Co'){
                            $property_type = 'Comm/Industry Sale';
                        } else if($this->p->property_type == 'E'){
                            $property_type = 'Comm/Industry Lease';
                        } else if($this->p->property_type == 'F'){
                            $property_type = 'Multiple Dwellings';
                        }
                ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_PROPERTY_TYPE'); ?></strong></dt>
                        <dd><?php echo $property_type; ?></dd>
                    </dl>
                    <?php endif; ?>
                    <?php if ($this->p->dwelling_type): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_DWELLING_TYPE'); ?></strong></dt>
                        <dd><?php echo $this->p->dwelling_type; ?></dd>
                    </dl>
                    <?php endif; ?>
					
					<?php if($this->p->subdivision): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_SUB_DIV'); ?></strong></dt>
                        <dd><?php echo $this->p->subdivision; ?></dd>
                    </dl>
                    <?php endif; ?>
					
					<?php 
                    $misc_details_right = array('yearbuilt','zoning');
                    foreach ($misc_details_right as $mdr)
                    {
                        if ($this->p->$mdr)
                        {
							if($mdr == 'yearbuilt'){
								$tag = 'year_built';
							} else {
								$tag = $mdr;
							}
                            ?>
                            <dl class="dl-horizontal">
                                <dt><strong><?php echo JText::_('COM_IPROPERTY_'.strtoupper($tag)); ?></strong></dt>
                                <dd><?php echo $this->p->$mdr; ?></dd>
                            </dl>
                            <?php
                        }
                    }
                    ?>
					<?php if ($this->p->county): ?>
						<dl class="dl-horizontal">
							<dt><strong><?php echo JText::_('COM_IPROPERTY_COUNTY'); ?></strong></dt>
							<dd><?php echo $this->p->county; ?></dd>
						</dl>
					<?php endif; ?>
					
					<?php if ($this->p->region): ?>
						<dl class="dl-horizontal">
							<dt><strong><?php echo JText::_('COM_IPROPERTY_REGION'); ?></strong></dt>
							<dd><?php echo $this->p->region; ?></dd>
						</dl>
					<?php endif; ?>
					
					
					<?php if($this->p->auction): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_AUCTION'); ?></strong></dt>
                        <dd><?php echo $this->p->auction; ?></dd>
                    </dl>
                    <?php endif; ?>
					
					<?php if ($this->p->current_use): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_CURRENT_USE' ); ?></strong></dt>
                        <dd><?php echo $this->p->current_use; ?></dd>
                    </dl>
                    <?php endif; ?>
					
					<?php if ($this->p->style): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_STYLE' ); ?></strong></dt>
                        <dd><?php echo $this->p->style; ?></dd>
                    </dl>
                    <?php endif; ?>
					<!-- End #1st-->
					
					<!-- #2nd -->
                    <?php if ($this->p->street_num): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_STREET_NUM'); ?></strong></dt>
                        <dd><?php echo $this->p->street_num; ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if ($this->p->street_compass): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_COMPASS'); ?></strong></dt>
                        <dd><?php echo $this->p->street_compass; ?></dd>
                    </dl>
                    <?php endif; ?>
                    <?php if ($this->p->street): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_STREET_NAME'); ?></strong></dt>
                        <dd><?php echo $this->p->street; ?></dd>
                    </dl>
                    <?php endif; ?>
					
					<?php if ($this->p->street_suffix): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_SUFFIX'); ?></strong></dt>
                        <dd><?php echo $this->p->street_suffix; ?></dd>
                    </dl>
                    <?php endif; ?>
					
					<?php if ($this->p->city): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_CITY'); ?></strong></dt>
                        <dd><?php echo ipropertyHTML::getCityName($this->p->city); ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if ($this->p->locstate): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_STATE'); ?></strong></dt>
                        <dd><?php echo ipropertyHTML::getStateName($this->p->locstate); ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if ($this->p->postcode): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_ZIPCODE'); ?></strong></dt>
                        <dd><?php echo $this->p->postcode; ?></dd>
                    </dl>
                    <?php endif; ?>
					<!--End #2nd-->
					
					
					<!-- #3rd-->
					<?php if ($this->p->beds): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_BEDS'); ?></strong></dt>
                        <dd><?php echo $this->p->beds; ?></dd>
                    </dl>
                    <?php endif; ?>
                    <?php if ($this->p->baths && $this->p->baths != '0.00'): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_BATHS'); ?></strong></dt>
                        <dd><?php echo $this->p->baths; ?></dd>
                    </dl>
                    <?php endif; ?>
					
					<?php if ($this->p->sqft): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo (!$this->settings->measurement_units) ? JText::_('COM_IPROPERTY_SQFT' ) : JText::_('COM_IPROPERTY_SQM'); ?></strong></dt>
                        <dd><?php echo $this->p->formattedsqft; ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if ($this->p->interior_levels): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_INTERIOR_LEVEL'); ?></strong></dt>
                        <dd><?php echo $this->p->interior_levels; ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if ($this->p->stories): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_STORIES' ); ?></strong></dt>
                        <dd><?php echo $this->p->stories; ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if ($this->p->lotsize): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_LOT_SIZE' ); ?></strong></dt>
                        <dd><?php echo $this->p->lotsize; ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php 
                    
                    $misc_details_left = array('lot_acres','lot_type');
                    
                    foreach ($misc_details_left as $mdl)
                    {
                        if ($this->p->$mdl)
                        {
                            ?>
                            <dl class="dl-horizontal">
                                <dt><strong><?php echo JText::_('COM_IPROPERTY_'.strtoupper($mdl)); ?></strong></dt>
                                <dd><?php echo $this->p->$mdl; ?></dd>
                            </dl>
                            <?php
                        }
                    }
                    ?>
					<!-- End #3rd-->
					
					<!-- #4th-->
					<?php 
                    $misc_details_right = array('garage_size','garage_type');
                    foreach ($misc_details_right as $mdr)
                    {
                        if ($this->p->$mdr)
                        {
							if($mdr == 'yearbuilt'){
								$tag = 'year_built';
							} else {
								$tag = $mdr;
							}
                            ?>
                            <dl class="dl-horizontal">
                                <dt><strong><?php echo JText::_('COM_IPROPERTY_'.strtoupper($tag)); ?></strong></dt>
                                <dd><?php echo $this->p->$mdr; ?></dd>
                            </dl>
                            <?php
                        }
                    }
                    ?>
					
					<?php if ($this->p->total_cover_space): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_TOTAL_PARKING_SPACES' ); ?></strong></dt>
                        <dd><?php echo $this->p->total_cover_space; ?></dd>
                    </dl>
                    <?php endif; ?>
					<!-- End #4rth-->
					
					
					<!-- #5th -->
					<?php if ($this->p->fireplace): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_FIREPLACE' ); ?></strong></dt>
                        <dd><?php echo $this->p->fireplace; ?></dd>
                    </dl>
                    <?php endif; ?>
                    <?php if ($this->p->kitchen_features): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_KITCHEN_FEATURE' ); ?></strong></dt>
                        <dd><?php echo $this->p->kitchen_features; ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if ($this->p->pool): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_POOL'); ?></strong></dt>
                        <dd><?php echo $this->p->pool; ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if ($this->p->building_style): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_BUILDING_STYLE' ); ?></strong></dt>
                        <dd><?php echo $this->p->building_style; ?></dd>
                    </dl>
                    <?php endif; ?>
					<!-- End #5th-->
					
					<!-- #6th -->
					<?php 
                    $misc_details_right2 = array('heat','cool','fuel','roof','siding');
                    foreach ($misc_details_right2 as $mdr2)
                    {
                        if ($this->p->$mdr2)
                        {
							$tag2 = $mdr2;
							
                            ?>
                            <dl class="dl-horizontal">
                                <dt><strong><?php echo JText::_('COM_IPROPERTY_'.strtoupper($tag2)); ?></strong></dt>
                                <dd><?php echo $this->p->$mdr2; ?></dd>
                            </dl>
                            <?php
                        }
                    }
                    ?>
					<!-- END #6th-->
					
					
                    
                </div>
                <div class="span6">
				
					<!-- #7th -->
					<?php 
                    $misc_details_right3 = array('tax','assessor_number','builder_name','technology','special_listing_cond','income' );
                    foreach ($misc_details_right3 as $mdr3)
                    {
                        if ($this->p->$mdr3)
                        {
							
							if($mdr3 == 'assessor_number'){
								$tag3 = 'assessor_no';
							} else if($mdr3 == 'special_listing_cond') {
								$tag3 = 'SPECIAL_LISTING';
							}else{	
								$tag3 = $mdr3;
							}
                            ?>
                            <dl class="dl-horizontal">
                                <dt><strong><?php echo JText::_('COM_IPROPERTY_'.strtoupper($tag3)); ?></strong></dt>
                                <dd><?php echo $this->p->$mdr3; ?></dd>
                            </dl>
                            <?php
                        }
                    }
                    ?>
					<!-- END #7th-->
				
					<!-- #8th -->
					<?php if($this->p->legal_township): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_LEGAL_TOWNSHIP'); ?></strong></dt>
                        <dd><?php echo $this->p->legal_township; ?></dd>
                    </dl>
                    <?php endif; ?>
                    <?php if($this->p->legal_range): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_LEGAL_RANGE'); ?></strong></dt>
                        <dd><?php echo $this->p->legal_range; ?></dd>
                    </dl>
                    <?php endif; ?>
                    <?php if($this->p->legal_section): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_LEGAL_SECTION'); ?></strong></dt>
                        <dd><?php echo $this->p->legal_section; ?></dd>
                    </dl>
                    <?php endif; ?>
                     <?php if($this->p->legal_lot_num): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_LOT_NUM'); ?></strong></dt>
                        <dd><?php echo $this->p->legal_lot_num; ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if($this->p->reo): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_REO'); ?></strong></dt>
                        <dd><?php echo JText::_('COM_IPROPERTY_YES'); ?></dd>
                    </dl>
                    <?php endif; ?>
					<!-- END #8th-->
					
					<!-- #9th -->
					<?php if($this->p->hoa): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_HOA'); ?></strong></dt>
                        <dd><?php echo JText::_('COM_IPROPERTY_YES'); ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if($this->p->association_hoa_fee): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_HOA_FEE'); ?></strong></dt>
                        <dd><?php echo $this->p->association_hoa_fee; ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if($this->p->association_hoa2_fee): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_HOA2_FEE'); ?></strong></dt>
                        <dd><?php echo $this->p->association_hoa2_fee; ?></dd>
                    </dl>
                    <?php endif; ?>
					<!-- END #9th-->
					
					
					<!-- #10th -->
					<?php if($this->p->elementary_school): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_ELEMENTRY_SCHOOL'); ?></strong></dt>
                        <dd><?php echo $this->p->elementary_school; ?></dd>
                    </dl>
                    <?php endif; ?>
                    <?php if($this->p->middle_school): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_MIDDLE_SCHOOL'); ?></strong></dt>
                        <dd><?php echo $this->p->middle_school; ?></dd>
                    </dl>
                    <?php endif; ?>
                    <?php if($this->p->high_school): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_HIGH_SCHOOL'); ?></strong></dt>
                        <dd><?php echo $this->p->high_school; ?></dd>
                    </dl>
                    <?php endif; ?>
					<?php if($this->p->school_district): ?>
                    <dl class="dl-horizontal">
                        <dt><strong><?php echo JText::_('COM_IPROPERTY_SCHOOL_DISTRICT'); ?></strong></dt>
                        <dd><?php echo $this->p->school_district; ?></dd>
                    </dl>
                    <?php endif; ?>
					<!--END #10th -->
					
					<!-- Amenities -->
					
					<?php
                
					if($this->amenities)
					{
					   
						foreach ($this->amenities as $amen)
						{
							switch ($amen->cat)
							{
								case 0:
									$amenities['general'][] = $amen;
									break;
								case 1:
									$amenities['interior'][] = $amen;
									break;
								case 2:
									$amenities['exterior'][] = $amen;
									break;
								
							}
						}

						foreach($amenities as $k => $a)
						{
							$amen_n     = (count($a));
							if($amen_n > 0) 
							{
								switch($k)
								{
									case 'general':
										$amen_label = JText::_('COM_IPROPERTY_GENERAL_AMENITIES');
										break;
									case 'interior':
										$amen_label = JText::_('COM_IPROPERTY_INTERIOR_AMENITIES');
										break;
									case 'exterior':
										$amen_label = JText::_('COM_IPROPERTY_EXTERIOR_AMENITIES');
										break;
									
								}
								$amen_left  = '';
								$amen_right = '';

								for ($i = 0; $i < $amen_n; $i++)
								{
								   
									 $amen_left  = $amen_left.'<ul class="span12"><li class="ip-bg-white1"><span class="1wtpl"></span> '.$a[$i]->title.'</li></ul>';
								}


								echo '
									<dl class="dl-horizontal">
										<dt><strong>'.$amen_label.'</strong></dt>
										<dd>                                        
										   
											   '.$amen_left.'
										   
										</dd>
									</dl>';
							}
						}
					}
            ?>
			
			<!-- END Amenities -->
			
			<!-- Public Remarks -->	
					
                    
				<?php if($this->p->public_remark): ?>
				<dl class="dl-horizontal">
					<dt><strong><?php echo JText::_('COM_IPROPERTY_PUBLIC_REMARK'); ?></strong></dt>
					<dd><?php echo $this->p->public_remark; ?></dd>
				</dl>
				<?php endif; ?>
			<!-- END public remarks -->
				
                </div>
            </div>
        </div>
        <?php if(!$this->print): ?>
        <?php endif; ?>
		<div class="span12 ip-summary-sidecol">
            <?php echo $this->loadTemplate('sidebar'); ?>
        </div>
    </div>
</div>
<?php if($this->p->listing_office_name): ?>
<?php echo '<div class="wtplwell" id="ip-disclaimer">Listing is courtesy of '.$this->p->listing_office_name.'</div>'; ?>
<?php endif; ?>