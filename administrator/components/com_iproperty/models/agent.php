<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.modeladmin');

class IpropertyModelAgent extends JModelAdmin
{
    protected $text_prefix = 'COM_IPROPERTY';

    public function getTable($type = 'Agent', $prefix = 'IpropertyTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_iproperty.agent', 'agent', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data)) {
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_iproperty.edit.agent.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('agent.id') == 0) {
                $settings   = ipropertyAdmin::config();

                //Set defaults according to IP config
                $data->company      = $settings->default_company;
			}
		}

		return $data;
	}

	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'company = '.(int) $table->company;

		return $condition;
	}
    
    public function publishAgent($pks, $value = 0)
	{
		// Initialise variables.
		$table	= $this->getTable();
		$pks	= (array) $pks;
        $ipauth = new ipropertyHelperAuth();

		$successful = 0;
        // Access checks.
		foreach ($pks as $i => $pk) {
			if ($table->load($pk)) {
				if (!$ipauth->canPublishAgent($pk, $value)){
					// Prune items that you can't change.
					unset($pks[$i]);
                    JError::raise(E_WARNING, 403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
				}else{
                    $successful++;
                }
			}
		}

		// Attempt to change the state of the records.
		if (!$table->publish($pks, $value)) {
			$this->setError($table->getError());
			return false;
		}

		return $successful;
	}
    
    public function featureAgent($pks, $value = 0)
	{
		// Initialise variables.
		$table	= $this->getTable();
		$pks	= (array) $pks;
        $ipauth = new ipropertyHelperAuth();

		$successful = 0;
        // Access checks.
		foreach ($pks as $i => $pk) {
			if ($table->load($pk)) {
				if (!$ipauth->canFeatureAgent($pk, $value)){
					// Prune items that you can't change.
					unset($pks[$i]);
                    JError::raise(E_WARNING, 403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
				}else{
                    // Attempt to change the state of the records.
                    if (!$table->feature($pk, $value)) {
                        $this->setError($table->getError());
                        return false;
                    }
                    $successful++;
                }
			}
		}
        return $successful;
	}
    
    public function superAgent($pks, $value = 0)
	{
		// Initialise variables.
		$table	= $this->getTable();
		$pks	= (array) $pks;
        $ipauth = new ipropertyHelperAuth();

		$successful = 0;
        // Access checks.
		foreach ($pks as $i => $pk) {
			if ($table->load($pk)) {
				if (!$ipauth->canSuperAgent()){
					// Prune items that you can't change.
					unset($pks[$i]);
                    JError::raise(E_WARNING, 403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
				}else{
                    $successful++;
                }
			}
		}

		// Attempt to change the state of the records.
		if (!$table->super($pks, $value)) {
			$this->setError($table->getError());
			return false;
		}

		return $successful;
	}
    
    public function deleteAgent($pks)
	{
		// Initialise variables.
		$table	= $this->getTable();
		$pks	= (array) $pks;
        $ipauth = new ipropertyHelperAuth();

		$successful = 0;
        // Access checks.
		foreach ($pks as $i => $pk) {
			if ($table->load($pk)) {
				if (!$ipauth->canDeleteAgent($pk)){
					// Prune items that you can't change.
					unset($pks[$i]);
                    JError::raise(E_WARNING, 403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
				}else{
                    $successful++;
                }
			}
		}

		// Attempt to change the state of the records.
		if (!$table->delete($pks)) {
			$this->setError($table->getError());
			return false;
		}

		return $successful;
	}
	public function uploadVideo(){
    	//echo "<pre>"; print_r($_REQUEST);exit;
			$db = JFactory::getDbo();
			if($_REQUEST['youtube']){
    			$file_name = $_REQUEST['youtube'];
    			//echo $file_name; exit;
	    	} else{
	    		$path = JPATH_SITE.'/media/com_iproperty/transactions';
		    	$file_name = $_FILES['file']['name'];
		    	$file_type = $_FILES['file']['type'];
		    	$file_tmp_name = $_FILES['file']['tmp_name'];
		    	$file_size = $_FILES['file']['size'];

		    	$a = mkdir($path.'/'.$_REQUEST['agent_id'], 0777, true);
		    	 move_uploaded_file($file_tmp_name,$path.'/'.$_REQUEST['agent_id'].'/'.$file_name);
	    	}
    	//$caption = $_REQUEST['caption'];
    	if($file_name){
    	//$photo_path[]=$path.'/'.$res->id.'/'.$photo_name;
	    	$video = $db->getQuery(true);
	    	//$columns = array('agent_id','upload_video','caption','from_admin','upload_date');
	    	$columns = array('agent_id','upload_video','from_admin','upload_date');
	    	//echo "<pre>"; print_r($columns); exit;
			//$values = array($db->quote($_REQUEST['agent_id']),$db->quote($file_name),$db->quote($caption),1);
			$values = array($db->quote($_REQUEST['agent_id']),$db->quote($file_name),1);
			$video
		    ->insert($db->quoteName('#__iproperty_agent_video'))
		    ->columns($db->quoteName($columns))
		    ->values(implode(',', $values).',NOW()');
		    $db->setQuery($video);
		    //echo $video; exit;
		    if ( $db->execute() !== true ) {
		    	JError::raiseError( 4711, 'A severe error occurred' );
			} else {
				JFactory::getApplication()->enqueueMessage('Successfully Uploaded');
			}
		} else{
			JError::raiseError( 4711, 'A severe error occurred' );
		}
    }
    public function getVideo($agent_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from($db->quoteName('#__iproperty_agent_video'));
		$query->where($db->quoteName('agent_id')." = ".$db->quote($agent_id));
		$db->setQuery($query);
		$result = $db->loadObjectlist();
		//echo "<pre>"; print_r($result); exit;
		return $result;
		//echo "<pre>"; print_r($res); exit;
	}
	public function editCaption($value){
		//echo "<pre>"; print_r($value); exit;
		$id = JRequest::getvar('id');
		
		$object = new stdClass();

		$object->id = $value['caption_id'];
		$object->caption = $value['caption'];
		$object->from_admin = 1;

		$result = JFactory::getDbo()->updateObject('#__iproperty_agent_video', $object, 'id');
		if($result){
			JFactory::getApplication()->enqueueMessage('Your Caption Successfully Updated..');	
		} else {
			JFactory::getApplication()->enqueueMessage('Please try again..');	
		}
		$app = JFactory::getApplication();
		//http://localhost/usmetrorealty/administrator/index.php?option=com_iproperty&view=agent&layout=edit&id=60
		$app->redirect(JURI::base().'index.php?option=com_iproperty&view=agent&layout=edit&id='.$id);
	}
	public function deleteVideo($id){
		//echo "<pre>"; print_r($_REQUEST); exit;
		$db = JFactory::getDbo();
 
		$query = $db->getQuery(true);
		$conditions = array(
		    $db->quoteName('id') . ' = '.$id, 
		);
		$query->delete($db->quoteName('#__iproperty_agent_video'));
		$query->where($conditions);
		$db->setQuery($query);
		//echo $query; exit;
		$result = $db->execute();
		if($result){
			return true;
		} else {
			return false;
		}
	}
}//class end