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

class IpropertyModelDownload extends JModelAdmin
{
    protected $text_prefix = 'COM_IPROPERTY';

	public function getTable($type = 'Download', $prefix = 'IpropertyTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
    public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_iproperty.download', 'download', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data)) {
			// Disable fields for display.
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}
    
    protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_iproperty.edit.download.data', array());

		if (empty($data)) {
			$data = $this->getItem();

			// Prime some default values.
			if ($this->getState('download.id') == 0) {
                $settings   = ipropertyAdmin::config();

                //Set defaults according to IP config
                //$data->company      = $settings->default_company;
			}
		}

		return $data;
	}   
	public function save($data = array(), $key = 'id')
    {
    	$path = JPATH_SITE.'/media/com_iproperty/project_files';
    	$file_ext   = array('jpeg','png','gif','jpg','pdf','doc','docx','xlsx','xls','txt');
    	
		$photo_name = $_FILES['project_file']['name'];
		$photo_size = $_FILES['project_file']['size'];
		$photo_tmp = $_FILES['project_file']['tmp_name'];
		$photo_error= $_FILES['project_file']['error'];

		$ext = end((explode(".", $photo_name)));
		$photo_path[]=$path.'/'.$photo_name;
		if(in_array($ext,$file_ext)){
			if(!file_exists($path.'/'.$photo_name)){
				$exists_file_name = basename($photo_name,'.'.$ext);
			} else {
				$ext = strtolower(substr($photo_name, strrpos($photo_name, '.') + 1));
				$exists_file_name = basename($photo_name,'.'.$ext).rand(1, 5);
			}
			move_uploaded_file($photo_tmp,$path.'/'.$photo_name);
		} else {
			echo "fail";
		}
		$data['file_name'] = $exists_file_name;
		$data['type'] = $ext;
    	if(parent::save($data)){
    		JFactory::getApplication()->enqueueMessage('successfully added');
    		$allDone =& JFactory::getApplication();
			$allDone->redirect('index.php?option=com_iproperty&view=downloads');
    	}
    }
    
    public function publishDownloads($pks, $value = 0)
	{
		// Initialise variables.
		$table	= $this->getTable();
		$pks	= (array) $pks;

        $ipauth = new ipropertyHelperAuth();

		$successful = 0;
        // Access checks.
		foreach ($pks as $i => $pk) {
			if ($table->load($pk)) {
				if (!$ipauth->canEditOpenhouse($pk)){
					// Prune items that you can't change.
					unset($pks[$i]);
                    JError::raise(E_WARNING, 403, JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
                    return false;
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

		if($successful){
			$allDone =& JFactory::getApplication();
			$allDone->redirect('index.php?option=com_iproperty&view=downloads');
		}
	}
    
    function deleteDownload($pks)
    {
        // Initialise variables.
        $table  = $this->getTable();
        $pks    = (array) $pks;
        $ipauth = new ipropertyHelperAuth();

        $successful = 0;
        // Access checks.
        foreach ($pks as $i => $pk) {
            if ($table->load($pk)) {
                if (!$ipauth->canEditOpenhouse($pk)){
                    // Prune items that you can't change.
                    unset($pks[$i]);
                    $this->setError(JText::_('JLIB_APPLICATION_ERROR_EDITSTATE_NOT_PERMITTED'));
                    return false;
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
}
?>