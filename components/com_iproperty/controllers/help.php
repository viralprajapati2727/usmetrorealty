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

class IpropertyControllerHelp extends JControllerForm
{

	public function save(){
        $input = JFactory::getApplication()->input;
        $formData =$input->get('jform', '', 'array');
        $model = $this->getModel('help');
        $model->save($formData);
    }
    public function reply(){
        $input = JFactory::getApplication()->input;
        $formData =$input->get('jform', '', 'array');
        //echo "<pre>"; print_r($formData); exit;
        $model = $this->getModel('help');
        $model->reply($formData);
    }
    public function getListNumber()
    {
       $fullname = JRequest::getVar('fullname');
       $query = "SELECT DISTINCT `mls_id` FROM `c4aqr_iproperty` join `c4aqr_iproperty_agentmid` on `c4aqr_iproperty`.`id` = `c4aqr_iproperty_agentmid`.`prop_id` join `c4aqr_iproperty_agents` ON `c4aqr_iproperty_agentmid`.`agent_id` = `c4aqr_iproperty_agents`.`id`WHERE access=1 and agent_id!='' and agent_id!=0 and `c4aqr_iproperty_agents`.`alias` ='$fullname'";
        //echo $query;exit;
        $db = JFactory::getDBO();
        $db->setQuery($query);
        $list =  $db->loadObjectList();
      
      /*
      if(!empty($list))
        { 
            foreach ($list as $key => $value) {
                $options[] =JHTML::_('select.option', $value->mls_id, JText::_($value->mls_id));
            }

            echo json_encode($options); exit;
        }else{
            $options[] =JHTML::_('select.option', 0, JText::_("Select Active Agent"));
            //$drop=JHTML::_('select.genericlist', $options, 'jform[region_id]', 'class="chzn-done"', 'value', 'text', 'class="chzn-done"');
            echo json_encode($options); exit;
        }
      */
      
       // addd by mahesh
      $data =array();
      if(!empty($list))
        { 
           $options .="<option value=''>Select Listing-Number</option>";  
            foreach ($list as $key => $value) {
                $options .="<option value='".$value->mls_id."'>".$value->mls_id."</option>";
            }
            $data["option"] =$options;
        
            echo json_encode($data); exit;
        }else{           
            $options .="<option value=''>Select Active Agent</option>"; 
            $data["option"] =$options;           
            echo json_encode($data); exit;
        }
       // addd by mahesh
      
      
    }
}