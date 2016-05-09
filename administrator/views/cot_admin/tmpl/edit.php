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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_cot_forms/assets/css/cot_forms.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function() {
       document.getElementById("jform_counting_method_timed_swim_chbx").checked = document.getElementById("jform_counting_method_timed_swim").value.length>0?1:0;
       document.getElementById("jform_counting_method_distance_swim_chbx").checked = document.getElementById("jform_counting_method_distance_swim").value.length>0?1:0;
       document.getElementById("jform_counting_method_other_chbx").checked = document.getElementById("jform_counting_method_other").value.length>0?1:0;

       enable_timed_swim(document.getElementById("jform_counting_method_timed_swim").value.length>0?true:false);
       enable_distance_swim(document.getElementById("jform_counting_method_distance_swim").value.length>0?true:false);
       enable_other(document.getElementById("jform_counting_method_other").value.length>0?true:false);
    });

    function enable_timed_swim(status) {
        if(!status){
                document.getElementById("jform_counting_method_timed_swim").value = "";
                document.getElementById("jform_counting_method_timed_swim").setAttribute('readonly','readonly');
        } else {
                document.getElementById("jform_counting_method_timed_swim").removeAttribute('readonly');
        }
    }

    function enable_distance_swim(status) {
        if(!status){
                document.getElementById("jform_counting_method_distance_swim").value = "";
                document.getElementById("jform_counting_method_distance_swim").setAttribute('readonly','readonly');
        } else {
                document.getElementById("jform_counting_method_distance_swim").removeAttribute('readonly');
        }
    }

    function enable_other(status) {
        if(!status){
                document.getElementById("jform_counting_method_other").value = "";
                document.getElementById("jform_counting_method_other").setAttribute('readonly','readonly');
        } else {
                document.getElementById("jform_counting_method_other").removeAttribute('readonly');
        }
    }

    Joomla.submitbutton = function(task)
    {
        if (task == 'cot_admin.cancel') {
            Joomla.submitform(task, document.getElementById('cot_admin-form'));
        }
        else {
            
            if (task != 'cot_admin.cancel' && document.formvalidator.isValid(document.id('cot_admin-form'))) {
                
                Joomla.submitform(task, document.getElementById('cot_admin-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_cot_forms&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="cot_admin-form" class="form-validate">

    <div class="form-horizontal">
        <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

        <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_COT_FORMS_TITLE_COT_ADMIN', true)); ?>
        <div class="row-fluid">
            <div class="span10 form-horizontal">
                <fieldset class="adminform">

                    			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observer_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observer_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observer_tel'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observer_tel'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observer_email'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observer_email'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_date'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_date'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_localisation'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_localisation'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_region'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_region'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_country'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_country'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_country_code'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_country_code'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_latitude'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_latitude'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_longitude'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_longitude'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_location'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_location'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_number'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_number'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_culled'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_culled'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_list'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_list'); ?></div>
			</div>
						<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_method'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_method'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('depth_range'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('depth_range'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label">
					<label id="jform_counting_method_label" class="hasTooltip" title="<?php echo JText::_('OBSERVATION_COUNTING_METHOD_DESC');?>">
						<?php echo JText::_('OBSERVATION_COUNTING_METHOD');?>
					</label>
				</div>
				<div class="controls souslabels">
                                        <input id="jform_counting_method_timed_swim_chbx" class="control-label" type="checkbox" name="counting_method_timed_swim" onclick="enable_timed_swim(this.checked)"> &nbsp; &nbsp;
                                        <?php echo $this->form->getLabel('counting_method_timed_swim'); ?> <?php echo $this->form->getInput('counting_method_timed_swim'); ?> <?php echo JText::_('COUNTING_METHOD_TIMED_SWIM_END');?>
                                </div><div class="controls souslabels">
                                        <input id="jform_counting_method_distance_swim_chbx" class="control-label" type="checkbox" name="counting_method_distance_swim" onclick="enable_distance_swim(this.checked)" > &nbsp; &nbsp;
                                        <?php echo $this->form->getLabel('counting_method_distance_swim'); ?> <?php echo $this->form->getInput('counting_method_distance_swim'); ?> <?php echo JText::_('COUNTING_METHOD_DISTANCE_SWIM_END');?>
                                </div><div class="controls souslabels">
                                        <input id="jform_counting_method_other_chbx" class="control-label" type="checkbox" name="counting_method_other" onclick="enable_other(this.checked)" > &nbsp; &nbsp;
                                        <?php echo $this->form->getLabel('counting_method_other'); ?> <?php echo $this->form->getInput('counting_method_other'); ?> <?php echo JText::_('COUNTING_METHOD_OTHER_END');?>
                                </div>

			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('remarks'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('remarks'); ?></div>
			</div>
			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('observation_state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('observation_state'); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('admin_validation'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('admin_validation'); ?></div>
			</div>

                </fieldset>
            </div>
        </div>
        <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        

        <?php echo JHtml::_('bootstrap.endTabSet'); ?>

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>
