<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_helloworld
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * HTML View class for the HelloWorld Component
 *
 * @since  0.0.1
 */
class transactionViewtransaction extends JViewLegacy
{
	/**
	 * Display the Hello World view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	function display($tpl = null)
	{
		// Assign data to the view
		$this->msg = 'Transaction Form';
		$this->form   = $this->get('Form');
		$this->layout = JRequest::getVar('layout');
		$user = JFactory::getUser();
		if($user->id){
			if($this->layout == 'edit'){
				$id = JRequest::getVar('id');
				$model=$this->getModel('Transaction');
				$this->result = $model->getedit($id);
				$this->video = $model->getVideo($id);
				//echo "<pre>"; print_r($this->result); exit;
				parent::display('edit');
			} else if($this->layout == 'listmessage'){
				$id = JRequest::getVar('id');
				$model=$this->getModel('Transaction');
				$this->listmessage = $model->getlistmessage($id);
				//echo "<pre>"; print_r($this->listmessage); exit;
				parent::display('listmessage');
			} else {
				parent::display($tpl);
			}
		} else {
			$allDone =& JFactory::getApplication();
			$allDone->redirect('index.php?option=com_iproperty&view=ipuser&Itemid=143');
		}
		
		/*$params = JComponentHelper::getParams('com_transaction');
		$Arizona=$params->get('Arizona_transaction');
		$Oregon=$params->get('Oregon_transaction');
		$Washington=$params->get('Washington_transaction');*/
	}
}
