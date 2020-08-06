<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
require_once JPATH_COMPONENT.'/models/list.php';
jimport('joomla.application.component.model');

class IpropertyModelAllProperties extends IpropertyModelList
{    
    protected $ipsettings;
    public $hotsheet;
    
    public function __construct($config = array())
	{
        $app = JFactory::getApplication();
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
                'keyword'           => 'property',
                'country'           => 'property',
                'locstate'          => 'property', 
                'province'          => 'property',
                'county'            => 'property',
                'region'            => 'property',
                'city'              => 'property',
                'subdivision'		=> 'property',
                'listing_office'    => 'property', 
                'stype'             => 'property',                 
                'beds'              => 'sliders', 
                'baths'             => 'sliders', 
                'approved'          => 'property',
                'featured'          => 'property',
                'price_low'         => 'sliders',
                'price_high'        => 'sliders',
                'sqft_low'          => 'sliders',
                'sqft_high'         => 'sliders',
                'lotsize_low'       => 'sliders',
                'lotsize_high'      => 'sliders',
                'acres_low'			=> 'sliders',
                'acres_high'		=> 'sliders',
                'cat'               => 'categories', 
                'agent_id'          => 'agents',
                'hoa'               => 'property',
                'reo'               => 'property',
                'waterfront'        => 'property');
		}
        
        $this->ipsettings   = ipropertyAdmin::config();
        $this->hotsheet 	= $app->input->get('hotsheet', '', 'uint');

		parent::__construct($config);
	}

	protected function getStoreId($id = '')
	{
		// Compile the store id.
        foreach ($this->filter_fields as $k => $v){
            $id	.= ':'.$this->getState('filter.'.$k);
        }

		return parent::getStoreId($id);
	}
    
    protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.        
        $app        = JFactory::getApplication();
        $params     = $app->getParams();
        
        // Adjust the context to support unique and multiple quick search results
        $uri            = JFactory::getURI();
        $query_string   = $uri->getQuery();
		if ($app->input->get('ipquicksearch') && !$app->input->get('limitstart') && !strpos($query_string, 'limitstart'))
		{
            $app->setUserState('ipqs', '.ipqs:'.rand());            
		}
        if(JRequest::getVar('ipquicksearch', '', 'get', 'int')) $this->context .= $app->getUserState('ipqs');
        if($this->hotsheet) $this->context .= '.iphs:'.$this->hotsheet;
        $this->context .= '.ipItemid:'.$app->input->get('Itemid', '', 'unint');
        
        $this->setState('params', $params);

		// List state information
		$value = $app->input->get('limit', $this->ipsettings->perpage, 'uint');
		$this->setState('list.limit', $value);

		$value = $app->input->get('limitstart', 0, 'uint');
		$this->setState('list.start', $value);
        
        // Handle default sort and order from request, params, or global settings
        if($request_sort    = $app->input->post->getCmd('filter_order')) $app->setUserState($this->context.'.filter_order', $request_sort);
        if($request_order   = $app->input->post->getWord('filter_order_Dir')) $app->setUserState($this->context.'.filter_order_Dir', $request_order);
        // End sort and order
        
        // Empty the user state to clear filters on initial search if 'use sticky' param is disabled
        if(!$params->get('use_sticky', true) && !$app->input->get('limitstart') && !strpos($query_string, 'limitstart')){
            foreach ($this->filter_fields as $k => $v){
                $app->setUserState($this->context.'.filter.'.$k, '');
            }
        }

		// Load the filter state.
        foreach ($this->filter_fields as $k => $v){
            if($params->get($k)){ // pre-set menu parameter filter 
                $search = $params->get($k);
            }else{ // filter submitted by form or request
                $search = $app->getUserStateFromRequest($this->context.'.filter.'.$k, 'filter_'.$k);
            }
            $this->setState('filter.'.$k, $search);
        }

		$this->setState('layout', $app->input->get('layout'));
	}
    
    protected function getListQuery($featured = false)
	{
        $where  = $this->getWhere();          
        $sort   = ($featured) ? 'RAND()' : $this->getState('list.ordering');
		$order  = ($sort == 'RAND()') ? '' : ' '.$this->getState('list.direction');
        if($featured) $where['property']['featured'] = 1;
        
        $pquery = new IpropertyHelperQuery($this->_db, $sort, $order);
        $query  = $pquery->buildPropertyQuery($where, 'properties'); 

		return $query;
	}

	public function getItems($items = null)
	{
		$items	= ($items) ? $items : parent::getItems();
        $items = ipropertyHelperProperty::getPropertyItems($items);
        return $items;
    }
            
    
    protected function getWhere()
    {
        $where                  = array();
        $where['searchfields']  = array('title','street_num','street','street2','city','county','region','subdivision','postcode','short_description','description','mls_id');
        $where['hotsheet']		= $this->hotsheet;
        
        foreach($this->filter_fields as $field => $type)
        {
            if($type == 'property') {
                $where[$type][$field]  = $this->getState('filter.'.$field);
            } else if($type == 'sliders') {
                // handle min and max values
                $queryfield     = explode('_', $field);
                
                if ($queryfield[0] == 'price' || $queryfield[0] == 'sqft' || $queryfield[0] == 'acres' || $queryfield[0] == 'lotsize') {
					// hack since lot_acres has underscore
					if($queryfield[0] == 'acres') $queryfield[0] = 'lot_acres';
                    if($queryfield[1] == 'low') {
                        $where[$type][$queryfield[0]]['min'] = $this->getState('filter.'.$field);
                    } else if ($queryfield[1] == 'high') {
                        $where[$type][$queryfield[0]]['max'] = $this->getState('filter.'.$field);
                    }
                } else { // only need minimum beds/baths
                    $where[$type][$queryfield[0]]['min'] = $this->getState('filter.'.$field);
                    $where[$type][$queryfield[0]]['max'] = '';
                }                
            } else if($type == 'categories') {
                $child_query    = $this->_db->setQuery(IpropertyHelperQuery::getCategories($this->getState('filter.cat')));
                $children       = $this->_db->loadObjectList();
                if ($children){
                    $cat_array = array($this->getState('filter.cat'));
                    foreach ( $children as $c ) {
                        $cat_array[] = $c->id;
                    }
                    $where[$type] = $cat_array;
                } else {
                    $where[$type] = $this->getState('filter.cat');
                }               
            } else {
                $where[$type]          = $this->getState('filter.'.$field);
            }
        }
        
        // Handle default sort and order from request, params, or global settings
        $app    = JFactory::getApplication();
        
        // Check for default values in menu params or global IP settings
        $sort   = ($this->state->params->get('filter_order')) ? $this->state->params->get('filter_order') : $this->ipsettings->default_p_sort;
        $order  = ($this->state->params->get('filter_order_Dir')) ? $this->state->params->get('filter_order_Dir') : $this->ipsettings->default_p_order;
        
        // Validate sort and order values and if they fail default to price DESC
        $prop_sort_fields = array('p.title', 'p.street', 'p.beds', 'p.baths', 'p.sqft', 'p.price', 'p.created', 'p.modified');
        if (!$sort || !in_array($sort, $prop_sort_fields)) $sort = 'p.price';
        if (!$order || !in_array(strtoupper($order), array('ASC', 'DESC'))) $order = 'DESC';
        
        // Set sort and order for query depending on user state, default to predefined values or very basic price DESC
        $this->setState('list.ordering', $app->getUserState($this->context.'.filter_order', $sort));
        $this->setState('list.direction', $app->getUserState($this->context.'.filter_order_Dir', $order));
        // End sort and order         
        return $where;
    }
    
    public function getFeatured()
    {
        if(!$this->ipsettings->show_featured) return false;
        
        $query      = self::getListQuery(true);        
        $featured   = $this->_getList($query, 0, $this->ipsettings->num_featured);
        
        if($featured) return $this->getItems($featured);
    }
    
    public function getStart()
	{
		return $this->getState('list.start');
	}

    public function getPropertiesMarkers(){

        $user = JFactory::getUser();
        $id = $user->id;
        if(!$id){

            $db = JFactory::getDbo();
            
            $query = $db->getQuery(true);
            $query->select($db->quoteName(array('id', 'street_num', 'street', 'street2', 'latitude', 'longitude')));
            $query->from($db->quoteName('#__iproperty'));
            $query->where($db->quoteName('longitude') . ' <> "" AND '. $db->quoteName('latitude').' <> "" AND '. $db->quoteName('access') . ' = 1 AND ' .$db->quoteName('approved') . ' = 1 ');
            $query->order('id ASC');
            $db->setQuery($query);
            //echo $query->__toString();
            $markerpros = $db->loadObjectList();

            $markers = "";
            if(count($markerpros) > 0){
                $markers .= "[";
                $countmarker = 0;
                foreach ($markerpros as $marker) {
                    $countmarker++;
                    $markers .= "['".$marker->street_num.' '.$marker->street.' '.$marker->street2."', ".$marker->latitude.", ".$marker->longitude.", ".$countmarker."]";    
                    if($countmarker < count($markerpros)) $markers .= ",";                  
                }
                $markers .= "]";
            }

            //var_dump($markers);exit;
            return $markers;

        }
    }
}

?>
