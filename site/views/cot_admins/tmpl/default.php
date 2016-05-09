<?php
/**
 * @version     2.0.4
 * @package     com_cot_forms
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sylvie Fiat <sylvie.fiat@ird.fr>
 */
// no direct access
defined('_JEXEC') or die;
?>
<!-- Styling for making front end forms look OK -->
<!-- This should probably be moved to the template CSS file -->
<style>
   .items_list {
	list-style: none;
}
</style>
<script type="text/javascript">
    function deleteItem(item_id){
        if(confirm("<?php echo JText::_('COM_COT_FORMS_DELETE_MESSAGE'); ?>")){
            document.getElementById('form-cot-admin-delete-' + item_id).submit();
        }
    }
    function validateItem(item_id){
        document.getElementById('form-cot-admin-validate-' + item_id).submit();
        
    }
</script>
<h1 class="fa fa-list-alt fa-2x"> <?php echo JText::_('COM_COT_FORMS_TITLE_LIST_VIEW_COT_ADMINS'); ?></h1>

<div id="items" class="items">
    <div class="list-group">
<?php $show = false; ?>
	<?php foreach ($this->items as $item) : ?>
		<?php $show = true; ?>
		<li class="list-group-item">
			<?php if(!$item->admin_validation){ ?><span class="fa fa-square-o"></span> <?php } else { ?> <span class="fa fa-check"></span><?php } ?>
			ID<?php echo $item->id; ?> <?php echo $item->observation_date; ?> <?php echo JText::_('COM_COT_FORMS_OBSERVER_ITEM'); ?> <?php echo $item->observer_name; ?>
			<span class="badge"><?php echo $item->observation_number; ?> </span>
			&nbsp;&nbsp;&nbsp;
			<a href="<?php echo JRoute::_('index.php?option=com_cot_forms&view=cot_admin&id=' . (int)$item->id); ?>" >
				 <span class="fa fa-eye"></span> <?php echo JText::_("COM_COT_FORMS_SEE_ITEM"); ?>
	   		</a>	
			&nbsp;
			<a href="<?php echo JRoute::_('index.php?option=com_cot_forms&task=cot_admin.edit&id='.(int)$item->id); ?>" >
				 <span class="fa fa-pencil"></span> <?php echo JText::_("COM_COT_FORMS_EDIT_ITEM"); ?>
	   		</a>
			&nbsp;
			<a href="javascript:deleteItem(<?php echo $item->id; ?>);">
				<span class="fa fa-trash-o">  </span><?php echo JText::_("COM_COT_FORMS_DELETE_ITEM"); ?>
			</a>
			<form id="form-cot-admin-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_cot_forms&task=cot_admin.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
				<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
				<input type="hidden" name="option" value="com_cot_forms" />
				<input type="hidden" name="task" value="cot_admin.remove" />
				<?php echo JHtml::_('form.token'); ?>
			</form>
			&nbsp;
			<?php if(!$item->admin_validation){ ?>
				<a href="javascript:validateItem(<?php echo $item->id; ?>);">
					<span class="fa fa-check"> <?php echo JText::_("COM_COT_FORMS_VALIDATE_ITEM"); ?>
				</a>
				<form id="form-cot-admin-validate-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_cot_forms&task=cot_admin.validate'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
					<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
					<input type="hidden" name="option" value="com_cot_forms" />
					<input type="hidden" name="task" value="cot_admin.validate" />
					<?php echo JHtml::_('form.token'); ?>
				</form>
			<?php } ?>
									
							
</li>
<?php endforeach; ?>
        <?php
        if (!$show):
            echo JText::_('COM_COT_FORMS_NO_ITEMS');
        endif;
        ?>
    </div>
</div>
<?php if ($show): ?>
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>

	<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_cot_forms&task=cot_admin.edit&id=0'); ?>">
		<?php echo JText::_("COM_COT_FORMS_ADD_ITEM"); ?></a>
&nbsp;
<?php if ($show): ?>
	<a class="btn btn-default" href="<?php echo JRoute::_('index.php?option=com_cot_forms&task=cot_admins.export'); ?>">
		<?php echo JText::_("COM_COT_FORMS_EXPORT_ITEM"); ?></a>
<?php endif; ?>
