<?php
/**
 * @version 3.3.3 2015-04-30
 * @package Joomla
 * @subpackage Intellectual Property
 * @copyright (C) 2009 - 2015 the Thinkery LLC. All rights reserved.
 * @license GNU/GPL see LICENSE.php
 */

defined('JPATH_BASE') or die;
JFormHelper::loadFieldClass('list');

class JFormFieldRegion extends JFormFieldList
{
    protected $type = 'Region';

	public function getOptions($county = false)
	{
        $county = (isset($this->element) && $this->element['county']) ? $this->element['county'] : $county;
        
        JFactory::getLanguage()->load('com_iproperty', JPATH_ADMINISTRATOR);
        $options = array();

        $db     = JFactory::getDbo();
        $query  = $db->getQuery(true);
        $query->select('DISTINCT(region) AS value, region AS text')
            ->from('#__iproperty')
            ->where('state = 1')
            ->where('region != ""');
            if($county){
                $query->where('county = '.$db->Quote($county));
            }
        $query->order('region ASC');

        $db->setQuery($query);
        
        try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}
        
        // Merge any additional options in the XML definition.
		if(isset($this->element))
        {            
            $options = array_merge(parent::getOptions(), $options);
            array_unshift($options, JHtml::_('select.option', '', JText::_('COM_IPROPERTY_REGION')));
        }

		return $options;
    }
}


