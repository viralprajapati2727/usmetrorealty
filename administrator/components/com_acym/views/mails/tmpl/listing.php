<?php
defined('_JEXEC') or die('Restricted access');
?><form id="acym_form" action="<?php echo acym_completeLink(acym_getVar('cmd', 'ctrl')); ?>" method="post" name="acyForm" enctype="multipart/form-data">
	<input type="hidden" id="acym_create_template_type_editor" name="type_editor">
	<div id="acym__templates" class="acym__content">
        <?php if (empty($data['allMails']) && empty($data['search']) && empty($data['tag']) && empty($data['status'])) { ?>
			<div class="grid-x text-center">
				<h1 class="acym__listing__empty__title cell"><?php echo acym_translation('ACYM_YOU_DONT_HAVE_ANY_TEMPLATE'); ?></h1>
				<h1 class="acym__listing__empty__subtitle cell"><?php echo acym_translation('ACYM_CREATE_AN_AMAZING_TEMPLATE_WITH_OUR_AMAZING_EDITOR'); ?></h1>
				<div class="medium-3"></div>
				<div class="medium-6 small-12 cell">
                    <?php include acym_getView('mails', 'listing_actions'); ?>
				</div>
				<div class="medium-3"></div>
			</div>
        <?php } else { ?>
			<div class="grid-x grid-margin-x">
                <?php include acym_getView('mails', 'listing_filters'); ?>
				<div class="xlarge-1 medium-shrink"></div>
                <?php include acym_getView('mails', 'listing_actions'); ?>
                <?php include acym_getView('mails', 'listing_listing'); ?>
			</div>
        <?php } ?>
	</div>
    <?php acym_formOptions(); ?>
</form>

