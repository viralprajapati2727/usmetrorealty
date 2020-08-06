<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.controlleradmin');

class IpropertyControllerProperties extends JControllerAdmin
{
    protected $text_prefix = 'COM_IPROPERTY';

	public function __construct($config = array())
	{
		parent::__construct($config);
        $this->registerTask('unpublish',	'publish');
        $this->registerTask('unfeature',	'feature');
        $this->registerTask('delete',       'remove');
        $this->registerTask('unapprove',    'approve');
	}
		
	public function getModel($name = 'Property', $prefix = 'IpropertyModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
    
    public function publish()
	{
		// Check for request forgeries
        JSession::checkToken() or die( 'Invalid Token');
        $cid	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('publish' => 1, 'unpublish' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

        if (empty($cid)) {
			JError::raise(E_WARNING, 500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		}else{
			// Get the model.
			$model = $this->getModel();
            
            // Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

            // Change the items.
            if ($count = $model->publishProp($cid, $value)) {
                $msg = ($value) ? $this->text_prefix.'_N_ITEMS_PUBLISHED' : $this->text_prefix.'_N_ITEMS_UNPUBLISHED';
				$this->setMessage(JText::plural($msg, $count));
			} else {
				JError::raise(E_WARNING, 500, $model->getError());
			}
		}

		$this->setRedirect($this->getReturnPage());
	}

    public function feature()
	{
		// Check for request forgeries
        JSession::checkToken() or die( 'Invalid Token');
        $cid	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('feature' => 1, 'unfeature' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

        if (empty($cid)) {
			JError::raise(E_WARNING, 500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		}else{
			// Get the model.
			$model = $this->getModel();
            
            // Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

            // Change the items.
            if ($count = $model->featureProp($cid, $value)) {
                $msg = ($value) ? $this->text_prefix.'_N_ITEMS_FEATURED' : $this->text_prefix.'_N_ITEMS_UNFEATURED';
				$this->setMessage(JText::plural($msg, $count));
			} else {
				JError::raise(E_WARNING, 500, $model->getError());
			}
		}

		$this->setRedirect($this->getReturnPage());
	}    

	public function remove()
	{
		// Check for request forgeries
        JSession::checkToken() or die( 'Invalid Token');
        $cid	= JRequest::getVar('cid', array(), '', 'array');

//echo "<pre>"; print_r($cid); exit;
        if (empty($cid)) {
			JError::raise(E_WARNING, 500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		}else{
			// Get the model.
			$model = $this->getModel();
		//echo "<pre>"; print_r($model); exit;

			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if ($model->deleteProp($cid)) {
				/* delete code placedmty here*/
				/*end*/
				$this->setMessage(JText::plural($this->text_prefix.'_N_ITEMS_DELETED', count($cid)));
			} else {
				JError::raise(E_WARNING, 500, $model->getError());
			}
		}

		$this->setRedirect($this->getReturnPage());
	}
    
    public function clearHits()
	{
        // Check for request forgeries
        JSession::checkToken() or die( 'Invalid Token');
        $cid	= JRequest::getVar('cid', array(), '', 'array');

        if (empty($cid)) {
			JError::raise(E_WARNING, 500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		}else{
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

			// Remove the items.
			if ($model->clearHits($cid)) {
				$this->setMessage(JText::plural($this->text_prefix.'_N_ITEMS_RESET', count($cid)));
			} else {
				JError::raise(E_WARNING, 500, $model->getError());
			}
		}

		$this->setRedirect($this->getReturnPage());
	}  
    
    public function approve()
	{
		// Check for request forgeries
        JSession::checkToken() or die( 'Invalid Token');
        $cid	= JRequest::getVar('cid', array(), '', 'array');
		$values	= array('approve' => 1, 'unapprove' => 0);
		$task	= $this->getTask();
		$value	= JArrayHelper::getValue($values, $task, 0, 'int');

        if (empty($cid)) {
			JError::raise(E_WARNING, 500, JText::_($this->text_prefix.'_NO_ITEM_SELECTED'));
		}else{
			// Get the model.
			$model = $this->getModel();
            
            // Make sure the item ids are integers
			jimport('joomla.utilities.arrayhelper');
			JArrayHelper::toInteger($cid);

            // Change the items.
            if ($count = $model->approveProp($cid, $value)) {
                $msg = ($value) ? $this->text_prefix.'_N_ITEMS_APPROVED' : $this->text_prefix.'_N_ITEMS_UNAPPROVED';
				$this->setMessage(JText::plural($msg, $count));
			} else {
				JError::raise(E_WARNING, 500, $model->getError());
			}
		}

		$this->setRedirect($this->getReturnPage());
	}
    
    protected function getReturnPage()
	{
		return 'index.php?option=com_iproperty&view=properties';
	}
}
?>