<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Iproperty
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');
require_once(JPATH_SITE.'/components/com_iproperty/helpers/html.helper.php');
require_once(JPATH_SITE.'/components/com_iproperty/helpers/property.php');
require_once(JPATH_SITE.'/components/com_iproperty/helpers/route.php');
require_once(JPATH_SITE.'/components/com_iproperty/helpers/query.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_iproperty/classes/admin.class.php');

class modActiveAgentHelper
{
    public static function getList(&$params)
	{
        $db     = JFactory::getDbo();
		$count  = (int) $params->get('count', 5);
        
        //echo "<pre>"; print_r($params); exit;
        // Ordering

        $agentlist = $params->get('agent');
        $countAgent =  count($agentlist);
        // /echo "<pre>"; print_r($agentlist);exit;

       if($countAgent > 1){
           unset($agentlist[0]);
           $ImpAgent =  implode(', ', $agentlist);
        } else {
            $ImpAgent = 46;
        }
        $app   = JFactory::getApplication();
        $db = JFactory::getDbo();

        $agent_query = 'SELECT * FROM `#__iproperty_agents` WHERE `id` IN ('.$ImpAgent.')';
        $db->setQuery($agent_query);
        $items = $db->loadObjectlist();
        // echo "<pre>"; print_r($agent_res); exit;
		return $items;
	}
}
