<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);
if (file_exists(JPATH_BASE.'/templates/'. $template->template .'/includes/functions.php')) {
include_once(JPATH_BASE.'/templates/'. $template->template .'/includes/functions.php');
}

?>
<div class="page-login page-login log">
    <div class="ip-mainheader">
        <h2 class="visible visible-first">Please Log In</h2>
    </div>
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
	<?php echo wrap_with_tag(wrap_with_span($this->escape($this->params->get('page_heading'))), $template->params->get('categoryPageHeading')); ?>
	</div>
	<?php endif;

	if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	<div class="login-description">
	<?php endif;

		if($this->params->get('logindescription_show') == 1) :
		echo $this->params->get('login_description');
		endif;

		if (($this->params->get('login_image') != '')) :?>
		<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>">
		<?php endif;

	if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	</div>
	<?php endif; ?>
	<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post" class="form-horizontal">
		<fieldset class="">
		<div class="control-group">
             <?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<div class="controls" style="margin-top:10px;"><span class="linklog">Don't have an account?<a href="<?php echo JRoute::_('index.php?option=com_iproperty&view=agentform&layout=register'); ?>">
            <?php //echo JText::_('COM_IPROPERTY_CREATE_ACCOUNT'); ?>Sign Up</a></span></div>
		<?php endif; ?>
		</div>
			<?php foreach ($this->form->getFieldset('credentials') as $field):
			if (!$field->hidden): ?>
			<div class="control-group">
			<div class="control-label">
				<?php echo $field->label; ?>
			</div>
			<div class="controls">
				<?php echo $field->input; ?>
			</div>
			</div>
			<?php endif;
			endforeach; ?>

			<?php if ($this->tfa): ?>
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getField('secretkey')->label; ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getField('secretkey')->input; ?>
					</div>
				</div>
			<?php endif; ?>
            
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
			<!-- <div  class="control-group remember">
				<div class="control-label"><label><?php //echo JText::_('COM_USERS_LOGIN_REMEMBER_ME') ?></label></div>
				<div class="controls"><input id="remember" type="checkbox" name="remember" class="inputbox" value="yes"/></div>
			</div> -->
			<div class="control-group">
              <div class="controls">
                 <span class="linklog"><a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>"><?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>&nbsp;/&nbsp;
		<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>"><?php echo JText::_('COM_USERS_LOGIN_REMIND'); ?></a></span>
              </div>
            </div>
			<?php endif; ?>
			<div class="control-group">
				<div class="controls">
					<button type="submit" class="btn btn-primary"><?php echo JText::_('JLOGIN'); ?></button>
				</div>
			</div>
			<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</fieldset>
	</form>
</div>

<div style="margin-bottom: 40px">
	<!-- <ul class="item-list">
		
		
	</ul> -->
</div>