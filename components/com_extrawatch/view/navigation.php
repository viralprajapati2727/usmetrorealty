<?php

/**
 * @file
 * ExtraWatch - A real-time ajax monitor and live stats
 * @package ExtraWatch
 * @version 2.3
 * @revision 2650
 * @license http://www.gnu.org/licenses/gpl-3.0.txt     GNU General Public License v3
 * @copyright (C) 2016 by CodeGravity.com - All rights reserved!
 * @website http://www.codegravity.com
 */

defined('_JEXEC') or die('Restricted access'); ?>


<div class="col-md-2 sidebar" id="sidebar">
    <ul class="nav nav-sidebar" role="navigation">
        <li><a href="<?php echo $extraWatch->config->renderLink('');?>"><i class="glyphicons group"></i><?php echo _EW_NAVIGATION_VISITORS;?><?php echo $extraWatchHTML->renderBadge('visitors');?></a></li>
        <li><a href="<?php echo $extraWatch->config->renderLink('stats');?>"><i class="glyphicons stats"></i><?php echo _EW_NAVIGATION_STATISTICS;?><?php echo $extraWatchHTML->renderBadge('stats');?></a></li>

        

        <li><a href="<?php echo $extraWatch->config->renderLink('goals');?>"><i class="glyphicons soccer_ball"></i><?php echo _EW_MENU_GOALS;?><?php echo $extraWatchHTML->renderBadge('goals');?></a></li>
        <li><a href="<?php echo $extraWatch->config->renderLink('antiSpam');?>"><i class="glyphicons ban"></i><?php echo _EW_NAVIGATION_BLOCKING;?><?php echo $extraWatchHTML->renderBadge('antiSpam');?></a></li>
        <li><a href="<?php echo $extraWatch->config->renderLink('graphs');?>"><i class="glyphicons charts"></i><?php echo _EW_ADMINHEADER_GRAPHS;?></a></li>
        <li><a href="<?php echo $extraWatch->config->renderLink('history');?>"><i class="glyphicons book"></i><?php echo _EW_MENU_HISTORY;?></a></li>
        <li><a href="<?php echo $extraWatch->config->renderLink('emails');?>"><i class="glyphicons envelope"></i><?php echo _EW_MENU_EMAILS;?></a></li>

        

        <li><a href="<?php echo $extraWatch->config->renderLink('license');?>"><i class="glyphicons certificate"></i><?php echo _EW_MENU_LICENSE;?></a></li>
        <li><a href="<?php echo $extraWatch->config->renderLink('settings');?>"><i class="glyphicons settings"></i><?php echo _EW_MENU_SETTINGS;?></a></li>

        
        <?php if (!_EW_CLOUD_MODE) { ?>
            <li><a href="<?php echo $extraWatch->config->renderLink('location');?>" title='<?php echo "("._EW_AVAILABLE_IN_FULL_VERSION.") "._EW_MENU_AVAILABLE_IN_PRO_LOCATION;?>' class="linkDisabled"><i class="glyphicons google_maps linkDisabled"></i><?php echo _EW_NAVIGATION_LOCATION;?></a></li>
            <li><a href="<?php echo $extraWatch->config->renderLink('heatmap');?>" title='<?php echo "("._EW_AVAILABLE_IN_FULL_VERSION.") "._EW_MENU_AVAILABLE_IN_PRO_HEATMAP;?>' class="linkDisabled"><i class="glyphicons fire linkDisabled"></i><?php echo _EW_ADMINHEADER_HEATMAP;?><?php echo $extraWatchHTML->renderBadge('heatmap');?></a></li>
            <li><a href="<?php echo $extraWatch->config->renderLink('clicks');?>" title='<?php echo "("._EW_AVAILABLE_IN_FULL_VERSION.") "._EW_MENU_AVAILABLE_IN_PRO_CLICK_AREAS;?>' class="linkDisabled"><i class="glyphicons hand_up linkDisabled"></i><?php echo _EW_NAVIGATION_CLICKS;?><?php echo $extraWatchHTML->renderBadge('clicks');?></a></li>
            <li><a href="<?php echo $extraWatch->config->renderLink('seo');?>" title='<?php echo "("._EW_AVAILABLE_IN_FULL_VERSION.") "._EW_MENU_AVAILABLE_IN_PRO_SEO_REPORT;?>' class="linkDisabled"><i class="glyphicons search linkDisabled"></i><?php echo _EW_EMAIL_SEO_REPORTS;?><?php echo $extraWatchHTML->renderBadge('seo');?></a></li>
            <li><a href="<?php echo $extraWatch->config->renderLink('users');?>" title='<?php echo "("._EW_AVAILABLE_IN_FULL_VERSION.") "._EW_MENU_AVAILABLE_IN_PRO_USERS;?>' class="linkDisabled"><i class="glyphicons user linkDisabled"></i><?php echo _EW_MENU_USERS;?><?php echo $extraWatchHTML->renderBadge('users');?></a></li>
            <li><a href="<?php echo $extraWatch->config->renderLink('flow');?>" title='<?php echo "("._EW_AVAILABLE_IN_FULL_VERSION.") "._EW_MENU_AVAILABLE_IN_PRO_FLOW;?>' class="linkDisabled"><i class="glyphicons move linkDisabled"></i><?php echo _EW_ADMINHEADER_FLOW;?></a></li>
            <li><a href="<?php echo $extraWatch->config->renderLink('downloads');?>" title='<?php echo "("._EW_AVAILABLE_IN_FULL_VERSION.") "._EW_MENU_AVAILABLE_IN_PRO_DOWNLOADS;?>' class="linkDisabled"><i class="glyphicons download_alt linkDisabled"></i><?php echo _EW_MENU_DOWNLOADS;?><?php echo $extraWatchHTML->renderBadge('downloads');?></a></li>
            <li><a href="<?php echo $extraWatch->config->renderLink('status');?>" title='<?php echo "("._EW_AVAILABLE_IN_FULL_VERSION.") "._EW_MENU_AVAILABLE_IN_PRO_STATUS;?>' class="linkDisabled"><i class="glyphicons database_plus linkDisabled"></i><?php echo _EW_MENU_STATUS;?></a></li>
            <li><a href="<?php echo $extraWatch->config->renderLink('sizes');?>" title='<?php echo "("._EW_AVAILABLE_IN_FULL_VERSION.") "._EW_MENU_AVAILABLE_IN_PRO_COMPONENTS;?>' class="linkDisabled"><i class="glyphicons folder_open linkDisabled"></i><?php echo _EW_ADMINHEADER_COMPONENTS  ;?></a></li>
        <?php } ?>
      

    </ul>
</div>
