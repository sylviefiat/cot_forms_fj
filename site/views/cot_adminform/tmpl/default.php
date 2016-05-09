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

JHtml::_('jquery.framework',  true, true);
JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_cot_forms', JPATH_ADMINISTRATOR);
$user = JFactory::getUser();
?>

<!-- Styling for making front end forms look OK -->
<!-- This should probably be moved to the template CSS file -->
<style>
   

    #jform_rules-lbl{
        display:none;
    }

    #access-rules a:hover{
        background:#f5f5f5 url('../images/slider_minus.png') right  top no-repeat;
        color: #444;
    }

    fieldset.radio label{
        width: 50px !important;
    }
</style>
<script type="text/javascript">
    function getScript(url,success) {
        var script = document.createElement('script');
        script.src = url;
        var head = document.getElementsByTagName('head')[0],
        done = false;
        // Attach handlers for all browsers
        script.onload = script.onreadystatechange = function() {
            if (!done && (!this.readyState
                || this.readyState == 'loaded'
                || this.readyState == 'complete')) {
                done = true;
                success();
                script.onload = script.onreadystatechange = null;
                head.removeChild(script);
            }
        };
        head.appendChild(script);
    }
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',function() {
        js = jQuery.noConflict();
        js(document).ready(function(){
            js('#form-cot_admin').submit(function(event){
                 
            }); 
            document.getElementById("jform_counting_method_timed_swim_chbx").checked = document.getElementById("jform_counting_method_timed_swim").value.length>0?1:0;
            document.getElementById("jform_counting_method_distance_swim_chbx").checked = document.getElementById("jform_counting_method_distance_swim").value.length>0?1:0;
            document.getElementById("jform_counting_method_other_chbx").checked = document.getElementById("jform_counting_method_other").value.length>0?1:0;

            enable_timed_swim(document.getElementById("jform_counting_method_timed_swim").value.length>0?true:false);
            enable_distance_swim(document.getElementById("jform_counting_method_distance_swim").value.length>0?true:false);
            enable_other(document.getElementById("jform_counting_method_other").value.length>0?true:false);
            
        });
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
    
</script>

<div class="cot_admin-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1 class="fa fa-map-marker fa-3x"> <?php echo JText::_('COM_COT_FORMS_EDIT_ITEM_TITLE'); ?> <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1 class="fa fa-map-marker fa-3x"> <?php echo JText::_('COM_COT_FORMS_COT_ADMIN_ADD_ITEM_TITLE'); ?></h1>
        <p class="lead" style="1.3em"> <?php echo JText::_('COM_COT_FORMS_COT_ADMIN_ADD_ITEM_DESC'); ?></p>
    <?php endif; ?>

    <form id="form-cot_admin" action="<?php echo JRoute::_('index.php?option=com_cot_forms&task=cot_admin.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
    			<div class="row">
			    <div class="col-xs-12"><?php echo $this->form->getLabel('observer_name'); ?></div>
			    <div class="col-lg-4 col-md-6 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon exergue"><span class="fa fa-user"></span></span> 
				    <?php echo $this->form->getInput('observer_name'); ?> 
				    <span style="display:none;" ><?php echo $this->form->getInput('id'); ?></span>
				</div>
			    </div>
			    <div class="col-lg-4 col-md-6 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon"><span class="fa fa-phone"></span></span>
				    <?php echo $this->form->getInput('observer_tel'); ?>
				</div>
			    </div>
			    <div class="col-lg-4 col-md-6 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon"><span class="fa fa-envelope"></span></span>
				    <?php echo $this->form->getInput('observer_email'); ?>
				</div>
			    </div>
			</div>		
			<div class="row">
			    <div class="col-xs-12"><?php echo $this->form->getLabel('observation_date'); ?></div>
			    <div class="col-lg-4 col-md-6 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon exergue"><span class="fa fa-calendar"></span></span> 
				    <?php echo $this->form->getInput('observation_date'); ?> 
				</div>
			    </div>
			    <div class="col-lg-8 col-md-6 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon"><span class="fa fa-location-arrow"></span></span>
				    <?php echo $this->form->getInput('observation_location'); ?>
				</div>
			    </div>
			</div>	
			<div class="row">
			    <div class="col-xs-12"><?php echo $this->form->getLabel('observation_localisation'); ?></div>
			    <div class="col-md-12 col-md-12 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon exergue"><span class="fa fa-map-marker"></span></span>
				    <?php echo $this->form->getInput('observation_localisation'); ?>
				</div>
			    </div>
			</div>	
			<div class="row">
			    <div class="col-md-6 col-md-6 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon"></span>
				    <?php echo $this->form->getInput('observation_region'); ?> 
				</div>
			    </div>
			    <div class="col-md-6 col-md-6 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon"></span>
				    <?php echo $this->form->getInput('observation_latitude'); ?> 
				</div>
			    </div>
			</div>	
			<div class="row">			
			    <div class="col-md-6 col-md-6 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon"></span>
				    <?php echo $this->form->getInput('observation_country'); ?>
				</div>
			    </div>
			    <div class="col-md-6 col-md-6 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon"></span>
				    <?php echo $this->form->getInput('observation_longitude'); ?>
				</div>
			    </div>
			</div>		
            		<div class="row">
			    <div class="col-xs-12"><?php echo $this->form->getLabel('observation_number'); ?></div>
			    <div class="col-lg-6 col-md-6 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon"><span class="fa fa-tachometer"></span></span> 
				    <?php echo $this->form->getInput('observation_number'); ?> 
				</div>
			    </div>
			    <div class="col-lg-6 col-md-6 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon"><span class="fa fa-refresh"></span></span>
				    <?php echo $this->form->getInput('observation_culled'); ?>
				</div>
			    </div>
			</div>
			
			<div class="row">
			    			    
			    <div class="col-lg-6 col-md-6 col-xs-12">
				<div class="form-group">
				    <?php echo $this->form->getLabel('observation_method'); ?>
				    <div class="col-xs-offset-2 col-xs-10">
					<div class="checkbox">
					    <label><?php echo $this->form->getInput('observation_method'); ?></label>
					</div>
				    </div>
				</div>
			    </div>
			    <div class="col-lg-6 col-md-6 col-xs-12">				
				<div class="form-group">
				    <?php echo $this->form->getLabel('depth_range'); ?>
				    <div class="col-xs-offset-2 col-xs-10">
					<div class="checkbox">
					    <label><?php echo $this->form->getInput('depth_range'); ?></label>
					</div>
				    </div>
				</div>
			    </div>
			</div>

			<div class="row">
			    <div class="col-xs-12">
				<label id="jform_counting_method_label" class="hasTooltip" title="<?php echo JText::_('OBSERVATION_COUNTING_METHOD_DESC');?>">
                                    <?php echo JText::_('OBSERVATION_COUNTING_METHOD');?>
                                </label>
			    </div>
			    <div class="col-lg-4 col-md-6 col-xs-12">
			        <div class="input-group">
			           <span class="input-group-addon">
				        <input id="jform_counting_method_timed_swim_chbx" class="control-label" type="checkbox" name="counting_method_timed_swim" onclick="enable_timed_swim(this.checked)">
			           </span>
    			           <?php echo $this->form->getInput('counting_method_timed_swim'); ?>
			        </div>
			    </div>
			    <div class="col-lg-4 col-md-6 col-xs-12">
			        <div class="input-group">
			           <span class="input-group-addon">
				        <input id="jform_counting_method_distance_swim_chbx" class="control-label" type="checkbox" name="counting_method_distance_swim" onclick="enable_distance_swim(this.checked)" >
			           </span>
    			           <?php echo $this->form->getInput('counting_method_distance_swim'); ?>
			        </div>
			    </div>
			    <div class="col-lg-4 col-md-6 col-xs-12">
			        <div class="input-group">
			           <span class="input-group-addon">
				        <input id="jform_counting_method_other_chbx" class="control-label" type="checkbox" name="counting_method_other" onclick="enable_other(this.checked)" >
			           </span>
    			           <?php echo $this->form->getInput('counting_method_other'); ?>
			        </div>
			    </div>
			</div>	
			
			<div class="row">
			    <div class="col-xs-12"><?php echo $this->form->getLabel('remarks'); ?></div>
			    <div class="col-lg-12 col-md-12 col-xs-12">
				<div class="input-group">
				    <span class="input-group-addon"><span class="fa fa-comment "></span></span> 
				    <?php echo $this->form->getInput('remarks'); ?> 
				</div>
			    </div>
			</div>

                        <div class="row">
                            <div class="col-xs-12"><?php echo $this->form->getLabel('observation_state'); ?></div>
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="fa fa-bolt"></span></span>
                                    <?php echo $this->form->getInput('observation_state'); ?>
                                </div>
                            </div>
                        </div>
                        <?php if($user->id != 0){ ?>
                            <div class="row">
                                <div class="col-xs-12"><?php echo $this->form->getLabel('admin_validation'); ?></div>
                                <div class="col-lg-12 col-md-12 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><span class="fa fa-check "></span></span>
                                        <?php echo $this->form->getInput('admin_validation'); ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col-xs-12"><?php echo $this->form->getLabel('captcha'); ?></div>
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <div class="input-group">
                                    <?php echo $this->form->getInput('captcha'); ?>
                                </div>
                            </div>
                        </div>


            <button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
            <?php echo JText::_('or'); ?>
            <a href="<?php echo JRoute::_('index.php?option=com_cot_forms&task=cot_adminform.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

            <input type="hidden" name="option" value="com_cot_forms" />
            <input type="hidden" name="task" value="cot_adminform.save" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>

