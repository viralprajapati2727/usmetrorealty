<?php
/*
 *
 * @package		ARI Docs Viewer
 * @author		ARI Soft
 * @copyright	Copyright (c) 2011 www.ari-soft.com. All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
 * 
 */

defined('_JEXEC') or die;

require_once JPATH_ROOT . '/libraries/aridocsviewer/loader.php';

use Arisoft\Plugin\Editorbutton as EditorButtonPlugin;

class plgButtonAridocsviewer extends EditorButtonPlugin
{
    protected $tag = 'aridoc';

    protected $btnName = 'link';

    protected $contentPlgName = 'aridocsviewer';

    protected $contentPlgGroup = 'system';

    public function __construct(&$subject, $config)
    {
        parent::__construct($subject, $config);
		
		$this->assetKey = bin2hex(ARIDOCSVIEWER_VERSION);
	}

    protected function getJsOptions($name)
    {
        $options = parent::getJsOptions($name);
        $params = $this->params;

        $width = $this->params->get('width', '');
        $height = $this->params->get('height', '');

        $options['modalTemplate'] = str_replace(
            array(
                '{{width}}',
                '{{height}}',
            ),
            array(
                htmlspecialchars($width),
                htmlspecialchars($height),
            ),
            file_get_contents(JPATH_ROOT . '/media/aridocsviewer/editor/tpl/modal_body.tpl')
        );

        return $options;
    }

    protected function registerCustomScripts($name)
    {
        $doc = JFactory::getDocument();

		$versionKey = $this->assetKey;

        $editorAssetsBaseUri = JURI::root(true) . '/media/aridocsviewer/editor/';

        $doc->addStyleSheet($editorAssetsBaseUri . 'css/style.css?v=' . $versionKey);
        $doc->addScript($editorAssetsBaseUri . 'js/script.js?v=' . $versionKey);
    }

    protected function getClientMessages()
    {
        return array_merge(
            parent::getClientMessages(),
            array(
                'engine' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_ENGINE'),

                'engineTip' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_ENGINETIP'),

                'article' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_ARTICLEID'),

                'articleTip' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_ARTICLEIDTIP'),

                'docUrl' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_DOCURL'),

                'docUrlTip' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_DOCURLTIP'),

                'width' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_WIDTH'),

                'widthTip' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_WIDTHTIP'),

                'height' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_HEIGHT'),

                'heightTip' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_HEIGHTTIP'),

                'default' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_DEFAULT'),

                'pluginUsage' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_PLUGINUSAGE'),

                'engines' => array(
                    'article' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_ARTICLEENGINE'),

                    'iframe' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_IFRAMEENGINE'),

                    'google' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_GOOGLEENGINE'),
                    
                    'office' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_OFFICE'),

                    'pdfjs' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_LABEL_PDFJSENGINE'),
                ),

                'errors' => array(
                    'selectArticle' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_ERROR_SELECTARTICLE'),

                    'enterDocUrl' => JText::_('PLG_EDITOR_XTD_ARIDOCSVIEWER_ERROR_ENTERDOCURL')
                )
            )
        );
    }
}