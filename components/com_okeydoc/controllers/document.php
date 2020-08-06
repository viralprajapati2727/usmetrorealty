<?php
/**
 * @package Okey DOC 1.x
 * @copyright Copyright (c)2014 - 2017 Lucas Sanner
 * @license GNU General Public License version 3, or later
 * @contact lucas.sanner@gmail.com
 */


//Note: Override some parent form methods (libraries/legacy/controllers/form.php).
//      See the file for more details.

defined('_JEXEC') or die;

/**
 * @package     Joomla.Site
 * @subpackage  com_okeydoc
 */
class OkeydocControllerDocument extends JControllerForm
{
  /**
   * The URL view item variable.
   *
   * @var    string
   * @since  1.6
   */
  protected $view_item = 'form';

  /**
   * The URL view list variable.
   *
   * @var    string
   * @since  1.6
   */
  protected $view_list = 'categories';

  /**
   * The URL edit variable.
   *
   * @var    string
   * @since  3.2
   */
  protected $urlVar = 'd.id';

  /**
   * Method to add a new record.
   *
   * @return  mixed  True if the record can be added, a error object if not.
   *
   * @since   1.6
   */
  public function add()
  {
    if(!parent::add()) {
      // Redirect to the return page.
      $this->setRedirect($this->getReturnPage());
    }
  }

  /**
   * Method override to check if you can add a new record.
   *
   * @param   array  $data  An array of input data.
   *
   * @return  boolean
   *
   * @since   1.6
   */
  protected function allowAdd($data = array())
  {
    //Note: If a category id is found, check whether the user is allowed to create an item into this category.

    $user = JFactory::getUser();
    //Get a possible category id passed in the data or URL.
    $categoryId = JArrayHelper::getValue($data, 'catid', $this->input->getInt('catid'), 'int');
    $allow = null;

    if($categoryId) {
      // If the category has been passed in the data or URL check it.
      $allow = $user->authorise('core.create', 'com_okeydoc.category.'.$categoryId);
    }

    if($allow === null) {
      // In the absense of better information, revert to the component permissions.
      return parent::allowAdd();
    }
    else {
      return $allow;
    }
  }

  /**
   * Method override to check if you can edit an existing record.
   *
   * @param   array   $data  An array of input data.
   * @param   string  $key   The name of the key for the primary key; default is id.
   *
   * @return  boolean
   *
   * @since   1.6
   *
   */
  protected function allowEdit($data = array(), $key = 'id')
  {
    $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
    $user = JFactory::getUser();
    $userId = $user->get('id');
    $asset = 'com_okeydoc.document.'.$recordId;

    // Check general edit permission first.
    if($user->authorise('core.edit', $asset)) {
      return true;
    }

    // Fallback on edit.own.
    // First test if the permission is available.
    if($user->authorise('core.edit.own', $asset)) {
      // Now test the owner is the user.
      $ownerId = (int) isset($data['created_by']) ? $data['created_by'] : 0;
      if(empty($ownerId) && $recordId) {
	// Need to do a lookup from the model.
	$record = $this->getModel()->getItem($recordId);

	if(empty($record)) {
	  return false;
	}

	$ownerId = $record->created_by;
      }

      // If the owner matches 'me' then do the test.
      if($ownerId == $userId) {
	return true;
      }
    }

    // Since there is no asset tracking, revert to the component permissions.
    return parent::allowEdit($data, $key);
  }

  /**
   * Method to cancel an edit.
   *
   * @param   string  $key  The name of the primary key of the URL variable.
   *
   * @return  boolean  True if access level checks pass, false otherwise.
   *
   * @since   1.6
   */
  public function cancel($key = 'd_id')
  {
    parent::cancel($key);

    // Redirect to the return page.
    $this->setRedirect($this->getReturnPage());
  }

  /**
   * Method to edit an existing record.
   *
   * @param   string  $key     The name of the primary key of the URL variable.
   * @param   string  $urlVar  The name of the URL variable if different from the primary key
   * (sometimes required to avoid router collisions).
   *
   * @return  boolean  True if access level check and checkout passes, false otherwise.
   *
   * @since   1.6
   */
  public function edit($key = null, $urlVar = 'd_id')
  {
    $result = parent::edit($key, $urlVar);

    return $result;
  }

  /**
   * Method to get a model object, loading it if required.
   *
   * @param   string  $name    The model name. Optional.
   * @param   string  $prefix  The class prefix. Optional.
   * @param   array   $config  Configuration array for model. Optional.
   *
   * @return  object  The model.
   *
   * @since   1.5
   */
  public function getModel($name = 'form', $prefix = '', $config = array('ignore_request' => true))
  {
    $model = parent::getModel($name, $prefix, $config);

    return $model;
  }

  /**
   * Gets the URL arguments to append to an item redirect.
   *
   * @param   integer  $recordId  The primary key id for the item.
   * @param   string   $urlVar    The name of the URL variable for the id.
   *
   * @return  string	The arguments to append to the redirect URL.
   *
   * @since   1.6
   */
  protected function getRedirectToItemAppend($recordId = null, $urlVar = 'd_id')
  {
    // Need to override the parent method completely.
    $tmpl   = $this->input->get('tmpl');
    // $layout = $this->input->get('layout', 'edit');
    $append = '';

    // Setup redirect info.
    if($tmpl) {
      $append .= '&tmpl='.$tmpl;
    }

    // TODO This is a bandaid, not a long term solution.
    // if ($layout)
    // {
    //   $append .= '&layout=' . $layout;
    // }
    $append .= '&layout=edit';

    if($recordId) {
      $append .= '&'.$urlVar.'='.$recordId;
    }

    $itemId = $this->input->getInt('Itemid');
    $return = $this->getReturnPage();
    $catId = $this->input->getInt('catid', null, 'get');

    if($itemId) {
      $append .= '&Itemid='.$itemId;
    }

    if($catId) {
      $append .= '&catid='.$catId;
    }

    if($return) {
      $append .= '&return='.base64_encode($return);
    }

    return $append;
  }

  /**
   * Get the return URL.
   *
   * If a "return" variable has been passed in the request
   *
   * @return  string	The return URL.
   *
   * @since   1.6
   */
  protected function getReturnPage()
  {
    $return = $this->input->get('return', null, 'base64');

    if(empty($return) || !JUri::isInternal(base64_decode($return))) {
      return JUri::base();
    }
    else {
      return base64_decode($return);
    }
  }

  /**
   * Function that allows child controller access to model data after the data has been saved.
   *
   * @param   JModelLegacy  $model  The data model object.
   * @param   array         $validData   The validated data.
   *
   * @return  void
   *
   * @since   1.6
   */
  protected function postSaveHook(JModelLegacy $model, $validData = array())
  {
    return;
  }

  /**
   * Method to save a record.
   *
   * @param   string  $key     The name of the primary key of the URL variable.
   * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
   *
   * @return  boolean  True if successful, false otherwise.
   *
   * @since   1.6
   */
  public function save($key = null, $urlVar = 'd_id')
  {
    $app = JFactory::getApplication();
    $recordId = $this->input->getInt($urlVar);
    //Get the jform data.
    $data = $this->input->post->get('jform', array(), 'array');

    //Set the alias of the document.
    
    //Remove possible spaces.
    $data['alias'] = trim($data['alias']);
    if(empty($data['alias'])) {
      //Created a sanitized alias from the title field, (see stringURLSafe function for details).
      $data['alias'] = JFilterOutput::stringURLSafe($data['title']);
    }

    // Verify that the alias is unique

    //Note: Usually this code goes into the overrided store JTable function but the file
    //would already be uploaded on the server if any duplicate alias is found.
    //To avoid this situation we check the alias unicity here as the file uploading
    //is not still triggered.

    $model = $this->getModel();
    $table = $model->getTable();

    if($table->load(array('alias' => $data['alias'], 'catid' => $data['catid'])) && ($table->id != $recordId || $recordId == 0)) {
      JFactory::getApplication()->enqueueMessage(JText::_('COM_OKEYDOC_DATABASE_ERROR_DOCUMENT_UNIQUE_ALIAS'), 'error');

      // Save the data in the session.
      //Note: It allows to preserve the data previously set by the user after the redirection.
      $app->setUserState($this->option.'.edit.'.$this->context.'.data', $data);

      $this->setRedirect(JRoute::_('index.php?option='.$this->option.'&view='.$this->view_item.$this->getRedirectToItemAppend($recordId, $urlVar), false));
      return false;
    }

    //Update jform with the modified data.
    $this->input->post->set('jform', $data);

    $result = parent::save($key, $urlVar);

    // If ok, redirect to the return page.
    if($result) {
      $this->setRedirect($this->getReturnPage());
    }

    return $result;
  }
}

