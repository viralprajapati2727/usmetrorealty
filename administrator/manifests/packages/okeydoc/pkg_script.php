<?php
/**
 * @package Okey DOC 1.x
 * @copyright Copyright (c) 2014 - 2017 Lucas Sanner
 * @license GNU General Public License version 3, or later
 */


// No direct access to this file
defined('_JEXEC') or die;


class pkg_okeydocInstallerScript
{
  /**
   * method to run before an install/update/uninstall method
   *
   * @return void
   */
  function preflight($type, $parent) 
  {
    $jversion = new JVersion();

    // Installing package manifest file version
    $this->release = $parent->get('manifest')->version;

    // Show the essential information at the install/update back-end
    echo '<div class="alert alert-info"><p>Installing package manifest file version = '.$this->release.'</p>';
    echo '<p>Current Joomla version = '.$jversion->getShortVersion().'</p></div>';

    //Abort if the component being installed is not newer than the
    //currently installed version.
    if($type == 'update') {
      $manifest = $this->getManifest();
      $oldRelease = $manifest['version'];
      $rel = ' v-'.$oldRelease.' -> v-'.$this->release;

      if(version_compare($this->release, $oldRelease, 'le')) {
	JFactory::getApplication()->enqueueMessage('Incorrect package version. Cannot upgrade: '.$rel, 'error');
	return false;
      }
    }

    if($type == 'install') {
      // Check the minimum Joomla! version
      if(!version_compare(JVERSION, '3.3.0', 'ge')) {
	JFactory::getApplication()->enqueueMessage('Cannot install Okey DOC in a Joomla release prior to: 3.3.0', 'error');
	return false;
      }
    }
  }


  /**
   * method to install the component
   *
   * @return void
   */
  function install($parent) 
  {
  }


  /**
   * method to uninstall the component
   *
   * @return void
   */
  function uninstall($parent) 
  {
    //
  }


  /**
   * method to update the component
   *
   * @return void
   */
  function update($parent) 
  {
    //
  }


  /**
   * method to run after an install/update/uninstall method
   *
   * @return void
   */
  function postflight($type, $parent) 
  {
    if($type == 'install') {
      //Enables the Okey DOC plugins.
      $db = JFactory::getDbo();
      $query = $db->getQuery(true);
      $query->update('#__extensions')
	    ->set('enabled=1')
	    ->where('type="plugin"')
	    ->where('element="okeydoc"');
      $db->setQuery($query);
      $db->query();
    }

    $component = $this->getManifest('component', 'com_okeydoc');
    echo '<div class="alert"><h4 class="alert-heading">Okey DOC extension has been successfully installed.</h4>'.
         '<p>'.JText::_($component['description']).'</p></div>';
  }


  /*
   * get a variable from the manifest file (actually, from the manifest cache).
   */
  function getManifest($type = 'package', $element = 'pkg_okeydoc')
  {
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('manifest_cache')
          ->from('#__extensions')
	  ->where('type = '.$db->Quote($type))
	  ->where('element = '.$db->Quote($element));
    $db->setQuery($query);
    $manifest = json_decode($db->loadResult(), true);

    return $manifest;
  }
}

