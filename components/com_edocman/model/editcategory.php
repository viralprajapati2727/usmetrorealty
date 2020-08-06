<?php
/**
 * @version        1.14.0
 * @package        Joomla
 * @subpackage     EDocman
 * @author         Tuan Pham Ngoc
 * @copyright      Copyright (C) 2011 - 2019 Ossolution Team
 * @license        GNU/GPL, see LICENSE.php
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

class EDocmanModelEditCategory extends OSModelAdmin
{

    function __construct($config = array())
    {
        $config['table'] = '#__edocman_categories';
        $config['name'] = 'category';
        parent::__construct($config);
        $this->state->insert('id', 'int', 0)->insert('tmpl', 'cmd', '');
    }

    /**
     * Method to get the form for the model.
     *
     * @param    array $data An optional array of data for the form.
     * @param    boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return    JForm    A JForm object
     */
    public function getForm($data = array(), $loadData = true)
    {
        $form = parent::getForm($data, $loadData);
        $form->setFieldAttribute('category_layout', 'directory', JPATH_ROOT . '/components/com_edocman/view/editcategory/tmpl');

        return $form;
    }

    function loadData()
    {
        parent::loadData();
    }

    function saveCategory($input)
    {
        $db = JFactory::getDbo();
        $row = $this->getTable();
        $data = $input->get('jform', array(), 'array');
        $id = (int)$data['id'];
        // Delete old image if needed
        if ($id > 0) {
            $row->load($id);
            if ($input->has('del_image') && $row->image) {
                $categoryImagePath = JPATH_ROOT . '/media/com_edocman/category/';
                $thumbCategoryImagePath = $categoryImagePath . 'thumbs/';
                if (JFile::exists($categoryImagePath . $row->image)) {
                    JFile::delete($categoryImagePath . $row->image);
                }
                if (JFile::exists($thumbCategoryImagePath . $row->image)) {
                    JFile::delete($thumbCategoryImagePath . $row->image);
                }
                $data['image'] = '';
            }
        }
        //Process image upload
        $files = $input->files->get('jform');
        $image = $files['image'];
        if (is_uploaded_file($image['tmp_name'])) {
            if (!getimagesize($image['tmp_name'])) {
                throw new Exception(JText::_('EDOCMAN_IMAGE_ERROR'));
            }
            $categoryImagePath = JPATH_ROOT . '/media/com_edocman/category/';
            $thumbCategoryImagePath = $categoryImagePath . 'thumbs/';
            $fileName = JFile::makeSafe($image['name']);
            if (JFile::upload($image['tmp_name'], $categoryImagePath . $fileName)) {
                $config = EdocmanHelper::getConfig();
                $width = $config->category_thumb_width > 0 ? $config->category_thumb_width : 100;
                $height = $config->category_thumb_height > 0 ? $config->category_thumb_height : 100;
                //Perform resizing image
                EdocmanHelper::resizeImage($categoryImagePath . $fileName, $thumbCategoryImagePath . $fileName, $width, $height);
                if ($row->image) {
                    if (JFile::exists($categoryImagePath . $row->image)) {
                        JFile::delete($categoryImagePath . $row->image);
                    }
                    if (JFile::exists($thumbCategoryImagePath . $row->image)) {
                        JFile::delete($thumbCategoryImagePath . $row->image);
                    }
                }
                $data['image'] = $fileName;
            }
        }

        if (JPluginHelper::isEnabled('edocman', 'notification')) {
            if (isset($data['notify_group_ids'])) {
                $data['notify_group_ids'] = implode(',', $data['notify_group_ids']);
            } else {
                $data['notify_group_ids'] = '';
            }
        }

        //access level
        if ($data['accesspicker'] == 1) {
            $data['access'] = 255;
        }
        //Store the modified jform data back to input object for processing in parent class
        $input->set('jform', $data);

        parent::save($input);

        $category_id = $input->getInt('id');
        if ($category_id > 0) {
            $query = $db->getQuery(true);
            $query->select('count(id)')->from('#__edocman_levels')->where('data_type = 0')->where('item_id = ' . $category_id);
            $db->setQuery($query);
            $count = $db->loadResult();

            $groups = $data['groups'];
            $groups = implode(",", $groups);
            if ($count == 0) {
                $db->setQuery("Insert into #__edocman_levels (id,data_type,item_id,`groups`) values (NULL,'0',$category_id,'" . $groups . "')");
                $db->execute();
            } else {
                $db->setQuery("Update #__edocman_levels set `groups` = '" . $groups . "' where data_type = 0 and item_id = $category_id");
                $db->execute();
            }
        }
    }

    /**
     *
     * Prepare the category data, before saving record to database
     *
     * @param JTable $row
     * @param string $task
     * @param array  $data
     */
    protected function prepareTable($row, $task, $data = array())
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        if ($row->path)
        {
            $config    = EdocmanHelper::getConfig();
            $path      = str_replace("\\", "/", $row->path);
            $path      = JString::strtolower($path);
            $path      = JFolder::makeSafe($path);
            // In case parent category has it own folder, children category should be stored in a child folder of parent category
            if ($row->parent_id > 0)
            {
                $query->select('`path`')
                    ->from('#__edocman_categories')
                    ->where('id = '. (int) $row->parent_id);
                $db->setQuery($query);
                $parentCategoryPath = $db->loadResult();
                if ($parentCategoryPath)
                {
                    $parentCategoryPath = JFolder::makeSafe($parentCategoryPath);
                    $path = $parentCategoryPath . '/' . $path;
                }
            }
            $row->path = $path;
            $path      = $config->documents_path . '/' . $row->path;
            if (!JFolder::exists($path))
            {
                JFolder::create($path);
            }
        }

        if ($row->parent_id > 0)
        {
            // Calculate level
            $query->clear();
            $query->select('`level`')
                ->from('#__edocman_categories')
                ->where('id = '. (int) $row->parent_id);
            $db->setQuery($query);
            $row->level = (int) $db->loadResult() + 1;
        }
        else
        {
            $row->level = 1;
        }

        parent::prepareTable($row, $task, $data);

        //in case path value of category is empty
        if($row->path == "")
        {
            $row->path = $row->alias;
            $config    = EdocmanHelper::getConfig();
            $path      = str_replace("\\", "/", $row->path);
            $path      = JString::strtolower($path);
            $path      = JFolder::makeSafe($path);
            // In case parent category has it own folder, children category should be stored in a child folder of parent category
            if ($row->parent_id > 0)
            {
                $query->clear();
                $query->select('`path`')
                    ->from('#__edocman_categories')
                    ->where('id = '. (int) $row->parent_id);
                $db->setQuery($query);
                $parentCategoryPath = $db->loadResult();
                if ($parentCategoryPath)
                {
                    $parentCategoryPath = JFolder::makeSafe($parentCategoryPath);
                    $path = $parentCategoryPath . '/' . $path;
                }
            }
            $row->path = $path;
            $path      = $config->documents_path . '/' . $row->path;
            if (!JFolder::exists($path))
            {
                JFolder::create($path);
            }
        }
    }

    public function deleteCategory($cid)
    {
        parent::delete($cid);
    }

    public function changeCategoryState($cid,$state)
    {
        parent::publish($cid,  $state);
    }
}