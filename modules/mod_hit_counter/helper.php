<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Iproperty
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');
/*require_once(JPATH_SITE.'/components/com_iproperty/helpers/html.helper.php');
require_once(JPATH_SITE.'/components/com_iproperty/helpers/property.php');
require_once(JPATH_SITE.'/components/com_iproperty/helpers/route.php');
require_once(JPATH_SITE.'/components/com_iproperty/helpers/query.php');
require_once(JPATH_ADMINISTRATOR.'/components/com_iproperty/classes/admin.class.php');*/

class modIPHitHelper1
{
    public static function getList(&$params)
	{
        $db     = JFactory::getDbo();
		//$count  = (int) $params->get('count', 5);
          
        // get items using query helper
        $page_url = $_SERVER['REQUEST_URI'];
        
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__hit_search_users'));
        $query->where($db->quoteName('page_url')." = ".$db->quote($page_url));
        $query->order('id DESC');
        $query->setLimit($count);
        $db->setQuery($query);
        $hits = $db->loadObject();
        //echo "<pre>"; print_r($hits); exit;
        //echo "<pre>"; print_r($result); exit;
        //$items = ipropertyHelperProperty::getPropertyItems($db->loadObjectList(), true, false, $hidenopic);

		return $hits;
	}
}
