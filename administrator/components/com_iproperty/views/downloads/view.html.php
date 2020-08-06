<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access');
jimport( 'joomla.application.component.view');

class IpropertyViewDownloads extends JViewLegacy 
{
	protected $items;
	protected $pagination;
	protected $state;
    protected $settings;
    protected $ipauth;
    protected $cofield;
    
    public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
        $this->settings     = ipropertyAdmin::config();
        $this->ipauth       = new ipropertyHelperAuth();
		$this->config 		= JFactory::getConfig();
        $this->tz			= new DateTimeZone($this->config->get('offset'));

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
        
        // Load the submenu.
		IpropertyHelper::addSubmenu(JFactory::getApplication()->input->getCmd('view', 'downloads'));

		$this->addToolbar(); 
        $this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}
    
    protected function addToolbar()
	{
		require_once JPATH_COMPONENT .'/models/fields/company.php';
        $this->cofield = new JFormFieldCompany();
        
        JToolBarHelper::title(JText::_('COM_IPROPERTY_DOWNLOADS'), 'iproperty.png');

        JToolBarHelper::addNew('download.add', 'JTOOLBAR_NEW');
        JToolBarHelper::editList('download.edit', 'JTOOLBAR_EDIT');
        JToolBarHelper::divider();
        /*JToolBarHelper::publishList('download.publish', 'JTOOLBAR_PUBLISH');
        JToolBarHelper::unpublishList('download.unpublish', 'JTOOLBAR_UNPUBLISH');*/
        JToolBarHelper::divider();        
        JToolBarHelper::deleteList(JText::_('COM_IPROPERTY_CONFIRM_DELETE'), 'downloads.delete', 'JTOOLBAR_DELETE');
        
        // Add search filters
		JHtmlSidebar::setAction('index.php?option=com_iproperty&view=downloads');
        
        JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived'=>false, 'trash'=>false, 'all'=>false)), 'value', 'text', $this->state->get('filter.state'), true)
		);
        
        JHtmlSidebar::addFilter(
			'- '.JText::_('JSELECT').' '.JText::_('COM_IPROPERTY_COMPANY').' -',
			'filter_company_id',
			JHtml::_('select.options', $this->cofield->getOptions(true), 'value', 'text', $this->state->get('filter.company_id'))
		);
	}
    
    protected function getSortFields()
	{
		return array(
			'p.file_name' => JText::_('COM_IPROPERTY_FILE_NAME'),
			'p.status' => JText::_('COM_IPROPERTY_DOWNLOAD_STATUS'),
            'p.title' => JText::_('COM_IPROPERTY_DOWNLOAD_TITLE'),
			'p.id' => JText::_('JGRID_HEADING_ID')
		);
	}

    public function _displayNoAccess($tpl = null)
    {
        JToolBarHelper::title(JText::_('COM_IPROPERTY_NO_ACCESS'), 'iproperty.png');
        JToolBarHelper::back();
        JToolBarHelper::spacer();
        
        parent::display($tpl);
    }
}
?>
