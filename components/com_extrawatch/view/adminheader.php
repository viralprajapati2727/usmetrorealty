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


<?php
if (@get_class($extraWatch->env) == "ExtraWatchJoomlaEnv") { //only Joomla
    if (@defined('JVERSION') && function_exists('version_compare') && version_compare(JVERSION, '3.0.0', '>')) {
        JHtml::_('jquery.framework');   //this should make sure that jQuery is loaded first
    } else if(!JFactory::getApplication()->get('jquery')) { // if JHtml::_('jquery.framework'); is not supported
        JFactory::getApplication()->set('jquery',true);
        $doc = JFactory::getDocument();
        $doc->addScript('http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
    }
}
?>

<?php echo $extraWatch->env->addScript($extraWatch->config->getLiveSiteWithSuffix()."components/com_extrawatch/js/ajax-dynamic-content.js");?>
<?php echo $extraWatch->env->addScript($extraWatch->config->getLiveSiteWithSuffix()."components/com_extrawatch/js/ajax.js");?>

<?php echo $extraWatch->env->addScript($extraWatch->config->getLiveSiteWithSuffix().$extraWatch->env->renderBackendAjaxLink($extraWatch->config, 'js','extrawatch.js')."&time=".time()."&env=".($extraWatch->config->getEnvironment())."&projectId="._EW_PROJECT_ID);?>

<?php echo $extraWatch->env->addScript($extraWatch->config->getLiveSiteWithSuffix()."components/com_extrawatch/js/fade.js");?>

<?php
	if (defined('JVERSION') && function_exists('version_compare') && version_compare(JVERSION, '1.6.0', '<')) {
		//do nothing.. menu fix for joomla 1.5
	} else {
?>

<?php 
if (get_class($extraWatch->env) != "ExtraWatchWordpressEnv" &&  	//query is already initialized for wordpress and would cause conflict otherwise
	!((get_class($extraWatch->env) == "ExtraWatchJoomlaEnv") && (defined('JVERSION') && function_exists('version_compare') && version_compare(JVERSION, '3.0.0', '>')))	//Joomla 3.0 and above has jquery by default
	) { 	
	echo $extraWatch->env->addScript($extraWatch->config->getLiveSiteWithSuffix()."components/com_extrawatch/js/jquery-1.11.0.min.js");
}
    //explicitely loading JQuery on Joomla >= 3.x
    if (@get_class($extraWatch->env) == "ExtraWatchJoomlaEnv" && (defined('JVERSION') && function_exists('version_compare') && version_compare(JVERSION, '3.0.0', '>='))) {
            //joomla 3.x loads own jquery, no need to load it again: //echo $extraWatch->env->addScript($extraWatch->config->getLiveSiteWithSuffix()."components/com_extrawatch/js/jquery-1.11.0.min.js");
        }

?>


<?php } ?>

<?php echo $extraWatch->env->addScript($extraWatch->config->getLiveSiteWithSuffix()."components/com_extrawatch/js/jquery.tablesorter.js");?>


<?php
$liveSite = $extraWatch->config->getLiveSiteWithSuffix();

if (get_class($extraWatch->env) != "ExtraWatchWordpressEnv") {  //for wordpress we're loading this in module so it gets to header section
    echo $extraWatch->env->addStyleSheet($extraWatch->config->getLiveSiteWithSuffix().$extraWatch->env->renderBackendAjaxLink($extraWatch->config, 'ajax','dashboard.css')."&env=".get_class($extraWatch->env));
}
?>



<?php echo $extraWatch->env->addScript($extraWatch->config->getLiveSiteWithSuffix()."components/com_extrawatch/js/morris/raphael-2.1.0.min.js");?>
<?php echo $extraWatch->env->addScript($extraWatch->config->getLiveSiteWithSuffix()."components/com_extrawatch/js/morris/morris.min.js");?>



<div class="ew-navbar ew-navbar-inverse ew-navbar-fixed-top" role="navigation">
        <div class="ew-navbar-header">
		  <img src="<?php echo $extraWatch->config->getLiveSiteWithSuffix();?>components/com_extrawatch/img/logo.png" alt="" width="155" height="55">
            <?php if (!_EW_CLOUD_MODE && !$extraWatch->config->isAdFree()) { ?>
                <div style="display: block; float: right">
                                <?php require_once JPATH_BASE2 . DS . "components" . DS . "com_extrawatch" . DS . "view" . DS . "frame.php";?>
                </div>
            <?php } else if (@_EW_CLOUD_MODE){ ?>
            <div style="display: block; color: white; width: 100%; text-align: right">
            Welcome, <?php echo (@$_SESSION['email']);?>. <a href="?action=logout" style="color: white">Logout</a>
            <?php } ?>
            </div>
        </div>
    </div>


<div class="container-fluid">
    <?php require_once JPATH_BASE2 . DS . "components" . DS . "com_extrawatch" . DS . "view" . DS . "navigation.php"; ?>

<!-- body start -->
    <div class="col-md-offset-2 main" id="mainContent" style='overflow: hidden'>
		<br/>
		&nbsp;
		<br/>
        <?php
        if (@_EW_CLOUD_MODE) {
            include(realpath(dirname(__FILE__)).DS."expiration.php");
        }
        ?>

