<?php
/**
 * @package		VINAORA VISITORS COUNTER
 * @subpackage	hit_counter
 *
 * @copyright	Copyright (C) 2007-2015 VINAORA. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @website		http://vinaora.com
 * @twitter		http://twitter.com/vinaora
 * @facebook	https://www.facebook.com/pages/Vinaora/290796031029819
 * @google+		https://plus.google.com/111142324019789502653
 */

// no direct access
defined('_JEXEC') or die;

require_once dirname(__FILE__) . '/helper/hit_counter.php';

class plgSystemhit_Counter extends JPlugin
{
	public function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );
	}
	
	public function onAfterInitialise()
	{
		// Don't run on back-end
		$onbackend = (int) $this->params->get('onbackend', 0);
		if ( !$onbackend && (JPATH_BASE !== JPATH_ROOT) ) return;

		$visit_type	= plghitCounterHelper::visitType();
		$requests = JRequest::get();
		//echo $_SERVER['REQUEST_URI']; exit;
		/*if(($requests['ipquicksearch'] == 1 || $requests['view'] == 'advsearch' || isset($requests['filter_keyword']) || isset($requests['filter_cat']) || isset($requests['filter_stype']) || isset($requests['filter_order']) || isset($requests['filter_order_Dir'])) && $visit_type == "guests"){
			self::_insertSearchRecord($lastlog, $visit_type);		
		}*/
		$page_url = $_SERVER['REQUEST_URI'];

		if($page_url){
			self::_insertSearchRecord($lastlog, $visit_type, $page_url);		
		}

		$now		= time();
		$session	= JFactory::getSession();
		$lastlog	= (int) $session->get('hit_counter.lastlog');
		if ( $session->isNew() || ($now > $lastlog) )
		{
			
			$lifetime	= (int) $session->getExpire();			
			$lastlog	= ( floor($now/$lifetime)+1 ) * $lifetime;
			
			self::_insertRecord($lastlog, $visit_type);
			$session->set('hit_counter.lastlog', $lastlog);
			return;
		}
		return ;
	}

	public function onAfterDispatch(){
		//echo JURI::current();exit;
	}

	/*
	 * Insert a new Record
	 */
	private static function _insertRecord($time=0, $visit_type='guests')
	{
		$time	= (int) $time;
		
		// Get a db connection.
		$db = JFactory::getDbo();
		
		// Create a new query object.
		$query = $db->getQuery(true);
		
		// Insert columns.
		$columns = array('time', 'visits', $visit_type);
		
		// Insert values.
		$values	= array($time, 1, 1);

		// Prepare the insert query.
		$query
			->insert('#__hit_counter')
			->columns($columns)
			->values(implode(',', $values));
		
		// Try to update if has more than one visitor who has visited the site
		if(self::_updateRecord($time, $visit_type)) return 1;
		
		// Set the query using our newly populated query object and execute it.
		$db->setQuery($query);
		$db->execute();
		
		return $db->getAffectedRows();
	}

	private static function _insertSearchRecord($time=0, $visit_type='guests', $page_url)
	{
		$ip_addr = $_SERVER['REMOTE_ADDR'];


		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		//echo "viral".$page_url; exit;
		$query->select($db->quoteName(array('ip_address', 'count_num', 'page_url')));
		$query->from($db->quoteName('#__hit_search_users'));
		$query->where($db->quoteName('page_url') . ' = '. $db->quote($page_url));
		$db->setQuery($query);
		$url_results = $db->loadObject();
		//var_dump($url_results); exit;

		if(empty($url_results)){
			//var_dump($url_results); exit;
			$insquery = $db->getQuery(true);
			$columns = array('ip_address', 'count_num', 'page_url');
			$values	= array($db->quote($ip_addr), 1, $db->quote($page_url));
			//var_dump($values); exit;
			$insquery
				->insert('#__hit_search_users')
				->columns($columns)
				->values(implode(',', $values));
			$db->setQuery($insquery);
			//echo "<pre>"; print_r($insquery); exit;
			$db->execute();
		} else {
			$guest_visit_count = $url_results->count_num;
			self::_updateSearchRecord($guest_visit_count, $page_url);
			/*$app = JFactory::getApplication();
			$link=JRoute::_('index.php?option=com_iproperty&view=ipuser&Itemid=143');
			$app->redirect($link, 'Please login or register yourself to make more use of our site.');*/
		}
		// Try to update if has more than one visitor who has visited the site
		//if(self::_updateRecord($time, $visit_type)) return 1;
		
		// Set the query using our newly populated query object and execute it.
		
		
		return $db->getAffectedRows();
	}

	/*
	 * Update the last Record
	 */
	private static function _updateRecord($time=0, $visit_type='guests')
	{
		$time	= (int) $time;
		
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);
		
		// Fields to update.
		$fields = array("visits=visits+1", "$visit_type=$visit_type+1");
		 
		// Conditions for which records should be updated.
		$where = "time=$time";
		 
		$query
			->update('#__hit_counter')
			->set($fields)
			->where($where);
		 
		$db->setQuery($query);
		$db->execute();
		
		return $db->getAffectedRows();
	}


	private static function _updateSearchRecord($guest_visit_count, $page_url)
	{
		$time	= (int) $time;
		
		// Get a db connection.
		$db = JFactory::getDbo();
		 
		// Create a new query object.
		$query = $db->getQuery(true);

		$ip_addr = $_SERVER['REMOTE_ADDR'];
		$guest_visit_count += 1;
		
		// Fields to update.
		$fields = array("count_num=$guest_visit_count");
		 
		// Conditions for which records should be updated.
		$where = "page_url='$page_url'";
		 
		$query
			->update('#__hit_search_users')
			->set($fields)
			->where($where);
		 
		$db->setQuery($query);
		//echo $query->__toString();exit;
		$db->execute();
		
		return $db->getAffectedRows();
	}

}
