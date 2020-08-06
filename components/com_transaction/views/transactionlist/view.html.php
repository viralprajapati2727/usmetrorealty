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
class transactionViewtransactionlist extends JViewLegacy
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
		require_once JPATH_COMPONENT.'/models/transaction.php';
		$value = new transactionModeltransaction;
		$user = JFactory::getUser();
		if($user->id){
		 	if(JRequest::getVar('layout','') == 'mytransactionlist'){
		 		$id=JRequest::getVar('id');
	          	$this->mydata= $value->getmyData($id);
	          	$this->myimages= $value->getmyimages($id);
	          	$this->setLayout(JRequest::getWord('layout', 'mytransactionlist'));
	          	parent::display('mytransactionlist');
      		} else {
	          	$this->data= $value->getData();
			  	$this->msg = 'All Transaction List';
			  	parent::display($tpl);
			}
		} else {
			$allDone =& JFactory::getApplication();
			$allDone->redirect('index.php?option=com_iproperty&view=ipuser&Itemid=143');
		}
	}
}
