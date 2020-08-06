<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

// Base this model on the allproperties model.
require_once __DIR__ . '/allproperties.php';

class IpropertyModelAgentProperties extends IpropertyModelAllProperties
{    
    protected function getWhere()
    {        
        $where = parent::getWhere();
        
        $app                = JFactory::getApplication();
        $where['agents']    = $app->input->get('id', '', 'uint');  
        
        return $where;
    }
    public function testimonials(){
    	$app   = JFactory::getApplication();
		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$query1 = $db->getQuery(true);
		$query1->select('*');
		$query1->from($db->quoteName('#__rsmonials'));
		//$query1->where($db->quoteName('email')." = ".$db->quote($user->email));
		$query1->where($db->quoteName('status')." = ". 1);
		$query1->order('id DESC');
		$db->setQuery($query1);
		$testi = $db->loadObjectlist();
		return $testi;
		//echo "<pre>"; print_r($res); exit;


    }
    public function getAgentId($agent_name){
    	//$app   = JFactory::getApplication();
		//$db = JFactory::getDbo();
		//$query1 = $db->getQuery(true);
		//$query1->select('id');
		//$query1->from($db->quoteName('#__iproperty_agents'));
		//$query1->where($db->quoteName('email')." = ".$db->quote($user->email));
		//$query1->where($db->quoteName('live_profile')." = ". $agent_name);
		//$query = " SELECT id FROM #__iproperty_agents WHERE `live_profile` = 'kalim'";
		//$db->setQuery($query1);
		//echo $query1;
		//$id = $db->loadObject();
     //echo "<pre>"; print_r($id); exit;
		//return $id;
        // echo "<pre>"; print_r($testi); exit;
		
		$db = JFactory::getDBO();
		$query = "SELECT id from #__iproperty_agents WHERE `live_profile` = ".$agent_name;
		$db->setQuery($query);
		$result = $db->loadObject();

		$session = JFactory::getSession();
		$session->set('AgentSessId', $result->id);
		
		//echo "<pre>"; print_r($result); //exit;
		return $result->id;	
		
    }
}

?>