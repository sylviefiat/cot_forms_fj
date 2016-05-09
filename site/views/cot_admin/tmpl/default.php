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
    function validateItem(item_id){
        document.getElementById('form-cot-admin-validate-' + item_id).submit();
        
    }
    var map;
    function initMap(lat,lng,zoom) {
    	var div = document.getElementById("map");
        var map = new google.maps.Map(div, {
            center: {lat: lat, lng: lng},
            zoom: zoom,	  
	    mapTypeId: google.maps.MapTypeId.SATELLITE 
        });
	if(zoom === 12){
		var marker = new google.maps.Marker({
		    position: {lat: lat, lng: lng},
		    map: map,
		    title: 'Acanthasters!'
		});
	}
    }
     getScript('//maps.google.com/maps/api/js',function() {
        js = jQuery.noConflict();
        js(document).ready(function(){
            var latitude = document.getElementById("latitude").innerText;
            var longitude = document.getElementById("longitude").innerText;
	    if(!isNaN(parseFloat(latitude)) && !isNaN(parseFloat(longitude))){
	    	initMap(parseFloat(latitude),parseFloat(longitude),12);		
	    } else {
	    	initMap(-21.5, 165.5, 5);
	    }
        });
    });
</script>
<?php
//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_cot_forms', JPATH_ADMINISTRATOR);
?>
<?php if ($this->item) : ?>
	<div class="col-xs-12" >
		<div class="panel panel-info">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_ID'); ?>: <?php echo $this->item->id; ?></h3>
			</div>
		<div class="panel-body">
			<div class="row">
				<div class="col-md-3 col-lg-3 " align="center"> <span class="fa fa-user fa-2x"></span> </div>
				<div class=" col-md-9 col-lg-9 "> 
                  			<table class="table table-user-information">
						<tbody>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVER_NAME'); ?>: </td>
								<td><?php echo $this->item->observer_name; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVER_TEL'); ?>: </td>
								<td><?php echo $this->item->observer_tel; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVER_EMAIL'); ?>: </td>
								<td><?php echo $this->item->observer_email; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3 col-lg-3 " align="center"> <span class="fa fa-eye fa-2x"></span> </div>
				<div class=" col-md-9 col-lg-9 "> 
                  			<table class="table table-user-information">
						<tbody>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVATION_DATE'); ?>: </td>
								<td><?php echo $this->item->observation_date; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVATION_LOCATION'); ?>: </td>
								<td><?php echo $this->item->observation_location; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_LOCALISATION'); ?>: </td>
								<td><?php echo $this->item->observation_localisation; ?></td>
							</tr>
							<tr>
								<td colspan="2"><div id="map" style="height:400px"></div></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVATION_REGION'); ?>: </td>
								<td><?php echo $this->item->observation_region; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVATION_COUNTRY'); ?>: </td>
								<td><?php echo $this->item->observation_country; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVATION_LATITUDE'); ?>: </td>
								<td id="latitude"><?php echo $this->item->observation_latitude; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVATION_LONGITUDE'); ?>: </td>
								<td id="longitude"><?php echo $this->item->observation_longitude; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVATION_NUMBER'); ?>: </td>
								<td><?php echo $this->item->observation_number; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVATION_CULLED'); ?>: </td>
								<td><?php echo $this->item->observation_culled; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_OBSERVATION_METHOD'); ?>: </td>
								<td><?php echo $this->item->observation_method; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_DEPTH_RANGE'); ?>: </td>
								<td><?php echo $this->item->depth_range; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_COUNTING_METHOD'); ?>: </td>
								<td><?php if($this->item->counting_method_timed_swim!=='') {
									echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_COUNTING_METHOD_TIMED_SWIM'); 
									echo ": ";
									echo $this->item->counting_method_timed_swim; 
									if($this->item->counting_method_distance_swim!=='' || ($this->item->counting_method_distance_swim=='' && $this->item->counting_method_other!==''))
										echo ', ';
								} ?><br><br><?php if($this->item->counting_method_distance_swim!=='') { 
									echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_COUNTING_METHOD_DISTANCE_SWIM');
									echo ": ";
									echo $this->item->counting_method_distance_swim; 
									if($this->item->counting_method_other!=='')
										echo ', ';
								} ?><br><br><?php if($this->item->counting_method_other!=='')  {
									echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_COUNTING_METHOD_OTHER');
									echo ": ";					 
									echo $this->item->counting_method_other; 
								} ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_REMARKS'); ?>: </td>
								<td><?php echo $this->item->remarks; ?></td>
							</tr>
							<tr>
								<td><?php echo JText::_('COM_COT_FORMS_FORM_LBL_COT_ADMIN_ADMIN_VALIDATION'); ?>: </td>
								<td><?php if(!$this->item->admin_validation){ ?><span class="fa fa-square-o"> <?php } else { ?> <span class="fa fa-check"></span><?php } ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>	

    </div>
   
	<a class="btn btn-primary" href="<?php echo JRoute::_('index.php?option=com_cot_forms&task=cot_admin.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_COT_FORMS_EDIT_ITEM"); ?></a>

								
	<a class="btn btn-primary" href="javascript:document.getElementById('form-cot-admin-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_COT_FORMS_DELETE_ITEM"); ?></a>
									<form id="form-cot-admin-delete-<?php echo $this->item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_cot_forms&task=cot-admin.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
										<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
										<input type="hidden" name="option" value="com_cot_forms" />
										<input type="hidden" name="task" value="cot_admin.remove" />
										<?php echo JHtml::_('form.token'); ?>
									</form>

	<?php if(!$this->item->admin_validation){ ?>
	<a class="btn btn-primary" href="javascript:document.getElementById('form-cot-admin-validate-<?php echo $this->item->id; ?>').submit()"><?php echo JText::_("COM_COT_FORMS_VALIDATE_ITEM"); ?></a>
									<form id="form-cot-admin-validate-<?php echo $this->item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_cot_forms&task=cot_admin.validate'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
										<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
										<input type="hidden" name="option" value="com_cot_forms" />
										<input type="hidden" name="task" value="cot_admin.validate" />
										<?php echo JHtml::_('form.token'); ?>
									</form>
	<?php } ?>
	
								
<?php
else:
    echo JText::_('COM_COT_FORMS_ITEM_NOT_LOADED');
endif;
?>
