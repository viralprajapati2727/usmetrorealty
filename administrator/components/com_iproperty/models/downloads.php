<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.modellist');

class IpropertyModelDownloads extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'p.id',
				'file_name', 'p.file_name',
                'title', 'p.title',
                'status', 'p.status'
			);
		}

		parent::__construct($config);
	}
	 
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id	.= ':'.$this->getState('filter.search');
		$id	.= ':'.$this->getState('filter.state');
        $id	.= ':'.$this->getState('filter.company_id');

		return parent::getStoreId($id);
	}

	public function getTable($type = 'Downloads', $prefix = 'IpropertyTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context.'.filter.file_name', 'filter_file_name');
		$this->setState('filter.file_name', $search);

		$state = $app->getUserStateFromRequest($this->context.'.filter.title', 'filter_stitle', '', 'string');
		$this->setState('filter.title', $state);

        $state = $app->getUserStateFromRequest($this->context.'.filter.id', 'filter_id', '', 'int');
		$this->setState('filter.id', $state);

		  $state = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search', '', 'string');
		$this->setState('filter.search', $state);

		// List state information.
		parent::populateState('id', 'asc');
	}

    protected function getListQuery()
	{
		// Initialise variables.
		$db         = $this->getDbo();
		$query      = $db->getQuery(true);
        $ipauth     = new ipropertyHelperAuth();


		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'p.*'
			)
		);

		$query->from('`#__iproperty_downloads` AS p');

        // Join over the users for the checked out user.
        // Restrict list to display only relevent agents for agent access level
        if (!$ipauth->getAdmin()) {
            switch ($ipauth->getAuthLevel()){
                case 1: //company level
                    $query->where('p.title = '.(int)$ipauth->getUagentCid());
                break;
            }
        }

		// Filter by published state
			//$query->where('p.status = '. 1);

			//echo $query; exit;
		

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('p.id = '.(int) substr($search, 3));
			}
            else if(stripos($search, 'pid:') === 0) {
				$query->where('p.id = '.(int) substr($search, 4));
			}
			else {
				$search     = JString::strtolower($search);
                $search     = explode(' ', $search);
                $searchwhere   = array();
                if (is_array($search)){ //more than one search word
                    foreach ($search as $word){
                        $searchwhere[] = 'LOWER(p.file_name) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.status) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.title) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                        $searchwhere[] = 'LOWER(p.id) LIKE '.$db->Quote( '%'.$db->escape( $word, true ).'%', false );
                    }
                } else {
                    $searchwhere[] = 'LOWER(p.file_name) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                    $searchwhere[] = 'LOWER(p.status) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                    $searchwhere[] = 'LOWER(p.title) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                    $searchwhere[] = 'LOWER(p.id) LIKE '.$db->Quote( '%'.$db->escape( $search, true ).'%', false );
                }
                $query->where('('.implode( ' OR ', $searchwhere ).')');
			}
		}        

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering');
		$orderDirn	= $this->state->get('list.direction');
        $query->group('p.id');
		$query->order($db->escape($orderCol.' '.$orderDirn));
		//echo $query; exit;
		return $query;
	}
	function download($id){
		$path = 'media/com_iproperty/project_files';
			$app   = JFactory::getApplication();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('*')
			->from($db->quoteName('#__iproperty_downloads'))
			->where($db->quoteName('id') . ' = ' . $db->quote($id));
    		$db->setQuery($query);
    		$results = $db->loadObject();
	     	$name = $results->file_name.".".$results->type;
	     	//echo $name; exit;
	     	$final_path = JURI::root().$path."/".$name;
	     	$headers  = get_headers($final_path, 1);
	     	//echo "<pre>"; print_r($headers); exit;
			$fsize    = $headers['Content-Length'];

	     	//echo filesize($final_path).'M'; exit;
    		header('Content-Description: File Transfer');
		    header('Content-Type: application/force-download');
		    header("Content-Disposition: attachment; filename=\"" . basename($name) . "\";");
		    header('Content-Transfer-Encoding: binary');
		    header('Expires: 0');
		    header('Cache-Control: must-revalidate');
		    header('Pragma: public');
		    header('Content-Length: ' . $fsize);
		    ob_clean();
		    flush();
		    readfile($final_path); //showing the path to the server where the file is to be download
		    exit;
	}
}//Class end
?>
