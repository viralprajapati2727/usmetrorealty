<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');

class IpropertyTableAgent extends JTable
{
	public function __construct(&$_db)
	{
		parent::__construct('#__iproperty_agents', 'id', $_db);
	}

	public function check()
	{
		jimport('joomla.filter.output');
        $ipauth = new IpropertyHelperAuth(array('msg'=>false));

		// Set name
		$this->fname = htmlspecialchars_decode($this->fname, ENT_QUOTES);
        $this->lname = htmlspecialchars_decode($this->lname, ENT_QUOTES);
        
        // Set alias
		$this->alias = JFilterOutput::stringURLSafe($this->alias);
		if (empty($this->alias)) {
            $ptitle         = $this->fname.' '.$this->lname;
			$this->alias    = JFilterOutput::stringURLSafe($ptitle);
		}

		// Set ordering
		if (empty($this->ordering)) {
			// Set ordering to last if ordering was 0
			$this->ordering = self::getNextOrder('`company`=' . $this->_db->Quote($this->company));
		}
        if(!$ipauth->canFeatureAgent($this->id, $this->featured)){
            unset($this->featured);
        }
        if(!$ipauth->canPublishAgent($this->id, $this->state)){
            unset($this->state);
        }

		return true;
	}

	public function bind($array, $ignore = array())
	{       
        if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}       
        
		return parent::bind($array, $ignore);
	}

	public function store($updateNulls = false)
	{
		// Transform the params field
		if (is_array($this->params)) {
			$registry = new JRegistry;
			$registry->loadArray($this->params);
			$this->params = (string) $registry;
		}
        
        // Verify that the alias is unique
		$table = JTable::getInstance('Agent', 'IpropertyTable');
		if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(JText::_('COM_IPROPERTY_ERROR_UNIQUE_ALIAS'));
			return false;
		}

		/*
		jos_usergroups - Contains the User groups, you can create more groups from admin area such as Registered, Author, Super Users (etc..)
		jos_users - Contains main user information such as name, username, email, password, block etc..
		jos_user_usergroup_map - contains mapping to user_id (from jos_users) and group_id (jos_usergrops)

		Code to insert data into these tables using the save function in the custom component model..
		*/

		$data = JRequest::get('post');
		$userTable = JTable::getInstance('User', 'JTable', $config = array());
		$userData = array();
		$params = JComponentHelper::getParams('com_users');
		$useractivation = $params->get('useractivation');

		jimport('joomla.user.helper');
		$salt = JUserHelper:: genRandomPassword(32);
		//echo 'in helper store';
		//echo '<pre>';print_r($data);
		$pswrd = $data['jform']['password'];
		//var_dump($pswrd);exit;
		//$cryptpswrd = JUserHelper::getCryptedPassword($pswrd, $salt);
		//$dbpassword = $cryptpswrd . ':' . $salt;
		$dbpassword = JUserHelper::hashPassword($pswrd);
		$date = date('Y-m-d H:i:s');

		if ($this->id) {
			$userData['id'] = $this->id;
		}

		$userData['name'] = $this->fname . ' ' . $this->lname;
		//$userData['username'] = strtolower(str_replace(' ', '', $userData['name']));
		$userData['username'] = $userData['email'] = $this->email;

		if (!empty($pswrd)) {
			$userData['password'] = $dbpassword;
			//$userData['password'] = $pswrd;
		}
		$userData['block'] = 0;
		$userData['registerDate'] = $date;

		if (empty($this->id)) {
			// Check if the user needs to activate their account.
			if (($useractivation == 1) || ($useractivation == 2)) {
				$userData['activation'] = JApplication::getHash( JUserHelper:: genRandomPassword());
				$userData['block'] = 1;
			}
		}
		//echo "<pre>"; print_r($data); print_r($userData); exit ;

		// Inserting Data into Users Table
		//print_r($userData);exit;
		if (!$userTable->bind($userData)) {
			$this->setError($userTable->getError());
			return false;
		}

		// Check the data.
		if (!$userTable->check()) {
			$this->setError($userTable->getError());
			return false;
		}
		
		// Store the data.
		if (!$userTable->store()) {
			$this->setError($userTable->getError());
			return false;
		}
		if (empty($data['id'])) {
			$userid = $userTable->get('id');
		} else {
			$userid = $data['customer_id'];
		}
		// Inserting Data into Users Group Table
		if (empty($data['id'])) {

			$sql = "INSERT INTO `#__user_usergroup_map` (`user_id`, `group_id`) VALUES('" . $userid . "','2')";
			$this->_db->setQuery($sql);
			if (!$this->_db->query()) {
				JError::raiseError(500, $this->_db->getErrorMsg());
			}
		}
		// some other code to ..
		
		// Attempt to store the data.
		return parent::store($updateNulls);
	}

	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;       

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$state      = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Get an instance of the table
		$table = JTable::getInstance('Agent','IpropertyTable');

		// For all keys
		foreach ($pks as $pk)
		{
            // Load the banner
            if(!$table->load($pk)){
                $this->setError($table->getError());
            }

            // Change the state
            $table->state = $state;

            // Check the row
            $table->check();

            // Store the row
            if (!$table->store()){
                $this->setError($table->getError());
            }
		}
		return count($this->getErrors())==0;
	}
    
    public function feature($pks = null, $state = 0)
	{
		// Initialise variables.
		$k = $this->_tbl_key;      

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$state      = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Get an instance of the table
		$table = JTable::getInstance('Agent','IpropertyTable');

		// For all keys
		foreach ($pks as $pk)
		{
            // Load the banner
            if(!$table->load($pk)){
                $this->setError($table->getError());
            }

            // Change the state
            $table->featured = $state;

            // Check the row
            $table->check();

            // Store the row
            if (!$table->store()){
                $this->setError($table->getError());
            }
		}
		return count($this->getErrors())==0;
	}
    
    public function super($pks = null, $state = 1)
	{
		// Initialise variables.
		$k = $this->_tbl_key;      

		// Sanitize input.
		JArrayHelper::toInteger($pks);
		$state      = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Get an instance of the table
		$table = JTable::getInstance('Agent','IpropertyTable');

		// For all keys
		foreach ($pks as $pk)
		{
            // Load the banner
            if(!$table->load($pk)){
                $this->setError($table->getError());
            }

            // Change the state
            $table->agent_type = $state;

            // Check the row
            $table->check();

            // Store the row
            if (!$table->store()){
                $this->setError($table->getError());
            }
		}
		return count($this->getErrors())==0;
	}
    
    public function delete($pks = null)
	{
        // Initialise variables.
		$k = $this->_tbl_key;      

		// Sanitize input.
		JArrayHelper::toInteger($pks);

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks))
		{
			if ($this->$k) {
				$pks = array($this->$k);
			}
			// Nothing to set publishing state on, return false.
			else {
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		try
		{			
			// delete from agents table
            $query = $this->_db->getQuery(true);
            $query->delete();
            $query->from('#__iproperty_agents');
            $query->where('id IN ('.implode(',', $pks).')');
            $this->_db->setQuery($query);
            
			// Check for a database error.
            if (!$this->_db->execute()) {
				throw new Exception($this->_db->getErrorMsg());
			}
            
            // delete from agent mid table
            $query = $this->_db->getQuery(true);
            $query->delete();
            $query->from('#__iproperty_agentmid');
            $query->where('agent_id IN ('.implode(',', $pks).')');
            $this->_db->setQuery($query);

			// Check for a database error.
            if (!$this->_db->execute()) {
				throw new Exception($this->_db->getErrorMsg());
			}
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());
			return false;
		}
		return count($this->getErrors())==0;
	}
}
?>