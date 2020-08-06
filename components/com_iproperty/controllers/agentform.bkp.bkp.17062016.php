<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.controllerform');

class IpropertyControllerAgentForm extends JControllerForm
{
	protected $view_item = 'agentform';
    protected $view_list = 'manage';

	public function add()
	{
		if (!parent::add()) {
			// Redirect to the return page.
			$this->setRedirect($this->getReturnPage());
		}
	}
    
    protected function allowAdd($data = array())
	{
        $allow  = parent::allowAdd($data);
        
        // Check if the user should be in this editing area
        $auth   = new ipropertyHelperAuth();
        $allow  = $auth->canAddAgent();
        
        return $allow;
	}
    
    protected function allowEdit($data = array(), $key = 'id')
	{        
        $allow  = parent::allowEdit($data, $key);
        
        // Check if the user should be in this editing area
        $recordId	= (int) isset($data[$key]) ? $data[$key] : 0;
        $auth   = new ipropertyHelperAuth();
        $allow  = $auth->canEditAgent($recordId);

        return $allow;
	}
    
	public function cancel($key = 'id')
	{
		parent::cancel($key);

		// Redirect to the return page.
		$this->setRedirect($this->getReturnPage());
	}

	public function edit($key = null, $urlVar = 'id')
	{
        $result = parent::edit($key, $urlVar);

		return $result;
	}

	public function &getModel($name = 'agentform', $prefix = '', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		$app = JFactory::getApplication();

        // Need to override the parent method completely.
		$tmpl		= $app->input->getCmd('tmpl');
		$layout		= $app->input->getCmd('layout', 'edit');
		$append		= '';

		// Setup redirect info.
		if ($tmpl) {
			$append .= '&tmpl='.$tmpl;
		}

		$append .= '&layout=edit';

		if ($recordId) {
			$append .= '&'.$urlVar.'='.$recordId;
		}

		$itemId	= $app->input->getInt('Itemid');
		$return	= $this->getReturnPage();

		if ($itemId) {
			$append .= '&Itemid='.$itemId;
		}

		if ($return) {
			$append .= '&return='.base64_encode($return);
		}
        
        //die($append);

		return $append;
	}

	protected function getReturnPage()
	{
		//$return = JFactory::getApplication()->input->get('return', null, 'default', 'base64');
        $return = $this->input->get('return', null, 'base64');

		if (empty($return) || !JUri::isInternal(base64_decode($return))) {
			return JURI::base();
		}
		else {
			return base64_decode($return);
		}
	}
    
    /*protected function getReturnPage()
	{
        $return = $this->input->get('return', null, 'base64');

		if ($return) return base64_decode($return);
        
        return JURI::base();
	}*/
    
    protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
        $app        = JFactory::getApplication();
        $settings   = ipropertyAdmin::config();
        
        // if auto agent option is set in IP settings, create the new user and attach them to the new agent
        // this will only execute if this is a new agent
        if($settings->auto_agent){
            if(!$model->createJuser($validData)){
                $app->enqueueMessage($this->getError(), 'error');
            }
        }
	}

	public function save($key = null, $urlVar = 'id')
	{
		$task   = $this->getTask();
        $result = parent::save($key, $urlVar);

		// If ok, redirect to the return page.
		if ($result && $task != 'apply') {
			$this->setRedirect($this->getReturnPage());
		}

		return $result;
	}
}
