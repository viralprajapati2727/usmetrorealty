<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
jimport('joomla.application.component.view');

class IpropertyViewManage extends JViewLegacy
{    
    protected $items;
	protected $pagination;
	protected $state;
    protected $settings;
    protected $ipauth;
    protected $params;
    protected $return;
    protected $user;
    protected $userId;
    protected $dispatcher;
    protected $ipbaseurl;
    
    public function display($tpl = null)
    { //customize start(viral)
      if(JRequest::getVar('layout','') == 'searchcriterialist'){
          require_once JPATH_COMPONENT.'/models/searchcriteriaform.php';
          $value = new IpropertyModelSearchcriteriaForm;
          $this->data= $value->getsearchData();
          $this->setLayout(JRequest::getWord('layout', 'searchcriterialist'));
      }
      //customize end
        JPluginHelper::importPlugin( 'iproperty');
        $this->dispatcher = JDispatcher::getInstance();
        
        //require the search fields
        require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/company.php';
        require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/stypes.php';
        require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/city.php';
        require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/ipcategory.php';
        require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/beds.php';
        require_once JPATH_COMPONENT_ADMINISTRATOR .'/models/fields/baths.php';
        
        $uri                = JURI::getInstance();       

        if(JRequest::getVar('layout','') == 'searchcriterialist') {
            $this->return   = base64_encode('index.php?option=com_iproperty&view=manage&layout=searchcriterialist');        
        } else {
            $this->return   = base64_encode($uri);        
        }
        $this->settings     = ipropertyAdmin::config();
        $this->ipauth       = new ipropertyHelperAuth();
        $this->user         = JFactory::getUser();
        $this->userId		= $this->user->get('id');
        $this->agent        = null;
        $this->ipbaseurl    = JURI::root(true);
        
        //customize start for properlist login user id
        $agent_email=$this->user->get('email');
        $mail="'$agent_email'";
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__iproperty_agents'));
        $query->where($db->quoteName('email')." = ".$mail);
        $db->setQuery($query);
        $this->results = $db->loadObject();
        // customize end
        // if not admin, check for agent profile. If not found, kick them out
        $agentid            = $this->ipauth->getUagentId();
        if( !$this->user->get('id') ){
            $this->_displayLogin();
            return;
        }else if(!$this->ipauth->getAdmin()){
            if (!$this->ipauth->getAuthLevel() || !$this->agent = IpropertyHTML::buildAgent($agentid)){
                $this->_displayNoAccess();
                return;
            }
            
        }
        
        // Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raise(E_ERROR, 500, implode("\n", $errors));
			return false;
		}

        // if user is not admin AND user does not have and agent id or IP auth is disabled - no access
        if (!$this->ipauth->getAdmin() && (!$this->ipauth->getUagentId() || !$this->ipauth->getAuthLevel())){
            $this->setLayout('noaccess');
            $this->_displayNoAccess($tpl);
            return;
        }       

        // Initialise variables.
        $layout = JRequest::getWord('layout', 'proplist');
        if($layout != 'dashboard' && $layout != 'sellerproplist' && $layout != 'buyerproplist' && $layout != 'searchcriterialist' && $layout != 'myproplist' && $layout != 'mysellerproplist')
        {
            $model = $this->getModel(JRequest::getWord('layout', 'proplist'));
            $this->setLayout(JRequest::getWord('layout', 'proplist'));
            $this->items        = $model->getItems();
            $this->pagination   = $model->getPagination();
            $this->state        = $model->getState();
        } elseif($layout == 'sellerproplist'){ // customize start(viral)
        	$model = $this->getModel('proplist');
        	$this->items        = $model->getItems();
            $this->pagination   = $model->getPagination();
            $this->state        = $model->getState();
            $this->setLayout(JRequest::getWord('layout', 'sellerproplist'));
        } elseif($layout == 'buyerproplist'){
        	$model = $this->getModel('proplist');
        	$this->items        = $model->getItems();
            $this->pagination   = $model->getPagination();
            $this->state        = $model->getState();
            $this->setLayout(JRequest::getWord('layout', 'buyerproplist'));
        }elseif($layout == 'searchcriterialist'){
            $model = $this->getModel('proplist');
            $this->items        = $model->getItems();
            $this->pagination   = $model->getPagination();
            $this->state        = $model->getState();
            $this->setLayout(JRequest::getWord('layout', 'buyerproplist'));
        }elseif($layout == 'myproplist'){
            $model = $this->getModel('propList');
            $this->items        = $model->mylistquery();
           // echo "<pre>"; print_r($this->items); exit;
            $this->pagination   = $model->getPagination();
            $this->state        = $model->getState();
            $this->setLayout(JRequest::getWord('layout', '  ')); // customize end
        }elseif($layout == 'mysellerproplist'){
            $model = $this->getModel('propList');
            $this->items        = $model->mysellerlistquery();
           // echo "<pre>"; print_r($this->items); exit;
            $this->pagination   = $model->getPagination();
            $this->state        = $model->getState();
            $this->setLayout(JRequest::getWord('layout', '  ')); // customize end
        }else {
            $this->setLayout(JRequest::getWord('layout', 'proplist'));
        }
            
        $this->_prepareDocument();	
        
        parent::display($this->getLayout());
    }

    protected function _prepareDocument() 
    {
        $app            = JFactory::getApplication();
        $document       = JFactory::getDocument();
        $menus          = $app->getMenu();
        $pathway        = $app->getPathway();
        $this->params   = $app->getParams();
        $title          = null;

        $menu = $menus->getActive();
        $this->params->def('page_heading', JText::_('COM_IPROPERTY_AGENT_MANAGE' ));

        $title = JText::_('COM_IPROPERTY_AGENT_MANAGE');
        $this->iptitle = $title;
        $this->document->setTitle($this->params->get('page_title', $title));

        // Set meta data according to menu params
        if ($this->params->get('menu-meta_description')) $this->document->setDescription($this->params->get('menu-meta_description'));
        if ($this->params->get('menu-meta_keywords')) $this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
        if ($this->params->get('robots')) $this->document->setMetadata('robots', $this->params->get('robots'));
        
        //Escape strings for HTMLfunction getData(){
            $app   = JFactory::getApplication();
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);

            $query->select('*')
            ->from($db->quoteName('#__iproperty_search_criteria'));
            $db->setQuery($query);

            $results = $db->loadObjectList();

		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));
    }  
    
    protected function getSortFields($type = 'property')
	{
		switch($type)
        {
            case 'property':
                default:
                return array(
                    'mls_id' => JText::_('COM_IPROPERTY_REF'),
                    'street' => JText::_('COM_IPROPERTY_STREET'),
                    'title' => JText::_('COM_IPROPERTY_TITLE'),
                    'city' => JText::_('COM_IPROPERTY_CITY'),
                    'state' => JText::_('JSTATUS'),
                    'p.id' => JText::_('JGRID_HEADING_ID')
                );
                break;
            case 'agent':
                return array(
                    'ordering' => JText::_('COM_IPROPERTY_ORDER'),
                    'lname' => JText::_('COM_IPROPERTY_LNAME'),
                    'user_name' => JText::_('COM_IPROPERTY_USER'),
                    'company' => JText::_('COM_IPROPERTY_COMPANY'),
                    'email' => JText::_('COM_IPROPERTY_EMAIL'),
                    'state' => JText::_('JSTATUS'),
                    'id' => JText::_('JGRID_HEADING_ID')
                );
                break;
            case 'company':
                return array(
                    'ordering' => JText::_('COM_IPROPERTY_ORDEtplR'),
                    'c.name' => JText::_('COM_IPROPERTY_TITLE'),
                    'c.email' => JText::_('COM_IPROPERTY_EMAIL'),
                    'state' => JText::_('JSTATUS'),
                    'id' => JText::_('JGRID_HEADING_ID')
                );
                break;
            case 'openhouse':
                return array();
                break;
        }
	}

    protected function _displayLogin($tpl = 'login')
    {
        $document               = JFactory::getDocument();
        $usersConfig            = JComponentHelper::getParams('com_users');
        $allowreg               = $usersConfig->get('allowUserRegistration');
        $settings               = ipropertyAdmin::config();
        //$return                 = base64_encode(JRoute::_(ipropertyHelperRoute::getManageRoute(), false));
        $return                 = base64_encode('index.php?option=com_iproperty&view=manage&layout=dashboard'); /* [[CUSTOM]] RI */
        //$return_decoded         = 'index.php?option=com_iproperty&view=manage&layout=dashboard'; /* [[CUSTOM]] RI */

        $document->setTitle( JText::_('COM_IPROPERTY_PLEASE_LOG_IN' ) );

        $this->assignRef('return', $return);
        $this->assignRef('allowreg', $allowreg);
        $this->assignRef('settings', $settings);

        parent::display($tpl);
    }

    protected function _displayNoAccess($tpl = 'noaccess')
    {
        $app  = JFactory::getApplication();
        $option     = JRequest::getCmd('option');

        $this->ipbaseurl    = JURI::root(true);
        $document           = JFactory::getDocument();
        $settings           = ipropertyAdmin::config();
        $pathway            = $app->getPathway();

        // Get the menu item object
        $menus = $app->getMenu();
        $menu  = $menus->getActive();

        $document->setTitle( JText::_('COM_IPROPERTY_NO_ACCESS' ));
        //set breadcrumbs
        if(is_object($menu) && $menu->query['view'] != 'ipuser') {
            $pathway->addItem(JText::_('COM_IPROPERTY_NO_ACCESS' ), '');
        }

        $this->assignRef('settings', $settings);

        parent::display($tpl);
    }
}


?>
