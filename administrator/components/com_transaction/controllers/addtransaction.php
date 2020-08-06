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

class transactionControlleraddtransaction extends JControllerForm
{
	public function save(){
        $task = JRequest::getVar('task');
        if($task=='save'){
        $input = JFactory::getApplication()->input;
                     
            $formData =$input->get('jform', '', 'array');
            $model = $this->getModel('addtransaction');
            $model->save($formData);
  		}
	}
	public function download(){
		 $id=JRequest::getVar('id');
		 $model = $this->getModel('addtransaction');
         $model->download($id);
		}
    public function replyEmail(){
            $input = JFactory::getApplication()->input;
            $formData =$input->get('jform', '', 'array');
            $model = $this->getModel('addtransaction');
            $model->replyEmail($formData);
        }
        public function uploadVideo(){
            $model = $this->getModel('addtransaction');
            //echo "<pre>"; print_r($_REQUEST); exit;
            if($_REQUEST['youtube']){
                $file_name = $_REQUEST['youtube'];
                $transaction_id = $_REQUEST['transaction_id'];
            } 
            $model->uploadVideo($file_name,$transaction_id);
        }
        public function deleteVideo(){
            //echo $_REQUEST['delete_value']; exit;
            $model = $this->getModel('addtransaction');
            $model->deleteVideo($_REQUEST['delete_value']);
        }
}