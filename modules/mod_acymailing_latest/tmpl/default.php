<?php
/**
 * @copyright	Copyright (C) 2009-2014 ACYBA SARL - All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-3.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
?>

<ul class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
<?php foreach ($newsletters as $item) :  ?>
	<li class="latestnews<?php echo $params->get('moduleclass_sfx'); ?>">
		<a <?php if($params->get('popup',1)){ echo 'class="modal" rel="{handler: \'iframe\', size: {x: '.intval($config->get('popup_width',750)).', y: '.intval($config->get('popup_height',550)).'}}"';}else{echo 'class="latestnews'.$params->get('moduleclass_sfx').'"';} ?> href="<?php echo JRoute::_('index.php?option=com_acymailing&ctrl=archive&task=view'.(empty($item->listid) ? '&key='.$item->key : '&listid='.$item->listid.'-'.$item->listalias).'&mailid='.$item->mailid.'-'.$item->alias.$acyItem); ?>" >
			<?php if($params->get('senddate',0) && !empty($item->senddate)) echo acymailing_getDate($item->senddate,$params->get('dateFormat', '%B %Y')).' : '; ?>
			<?php echo $item->subject; ?></a>
	</li>
<?php endforeach; ?>
</ul>