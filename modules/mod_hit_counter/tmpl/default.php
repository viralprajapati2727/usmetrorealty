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

$document = JFactory::getDocument();
$document->addStyleSheet(JURI::base().'modules/mod_hit_counter/assets/css/style.css');

// Initialize variables
$usethumb           = $params->get('usethumb', 1);
$iplayout           = $params->get('iplayout', 'rows');
$rowspan            = ($iplayout == 'rows') ? 12 : (12 / $params->get('columns', '12'));
$moduleclass_sfx    = ($params->get('moduleclass_sfx')) ? ' '.htmlspecialchars($params->get('moduleclass_sfx')) : '';

?>

<div class="row-fluid<?php echo $moduleclass_sfx; ?>">
    <div class="hit_counters">
        <?php if($iplayout == 'rows') echo '<div class="hits_sub">'; ?>
        <div class="check_hit_counter">
               <span><?php echo $hits->count_num ; ?></span> 
        </div>
        <?php 
            if($iplayout == 'rows'){
                echo '</div>';
            }else{
                echo '<div class="clearfix"></div>';
            }
            ?>
    </div>
</div>
