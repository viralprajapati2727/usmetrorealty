<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Facebook_instant_articles
 * @author     Raindrops Infotech <raindropsinfotech@gmail.com>
 * @copyright  2016 Raindrops Infotech
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

use Joomla\Utilities\ArrayHelper;

/**
 * Listarticless list controller class.
 *
 * @since  1.6
 */
class transactionControllertransaction extends JControllerAdmin
{
	public function approve(){
        $cid	= JRequest::getVar('cid', array(), '', 'array');
        //echo "<pre>"; print_r($cid); exit;
        $model = $this->getModel('transaction');
        $model->approve($cid);
	}
	public function disapprove(){
        $cid	= JRequest::getVar('cid', array(), '', 'array');
        $model = $this->getModel('transaction');
        $model->disapprove($cid);
	}
	public function delete(){
        $cid	= JRequest::getVar('cid', array(), '', 'array');
        $model = $this->getModel('transaction');
        $model->delete($cid);
	}
    public function messageEmail(){
        $input = JFactory::getApplication()->input;
        $formData =$input->get('jform', '', 'array');
        //echo "<pre>"; print_r($formData); exit;
        $model = $this->getModel('transaction');
        $model->messageEmail($formData);
    }
    public function download(){
     $id=JRequest::getVar('id');
     $model = $this->getModel('transaction');
     $model->download($id);
    }
    public function update(){
        $task = JRequest::getVar('task');
        if($task=='update'){
        $input = JFactory::getApplication()->input;
                     
            $formData =$input->get('jform', '', 'array');
            //echo "<pre>"; print_r($formData); exit;
            $model = $this->getModel('transaction');
            $model->update($formData);
        }
    }
    public function editComments(){
        $input = JFactory::getApplication()->input;
        $formData =$input->get('jform', '', 'array');
        //echo "<pre>"; print_r($formData); exit;
        $model = $this->getModel('transaction');
        $model->editComments($formData);
    }
        
}