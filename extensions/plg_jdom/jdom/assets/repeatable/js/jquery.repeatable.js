function editIt(list, itemId, copy, enableEditors){
	var listName = list.closest('[data-repeatable]').attr('data-repeatable'),
		editItem = (typeof window[listName]['sample_item'] != 'undefined') ? window[listName]['sample_item'] : {},
		item = {},itemId = (itemId > 0) ? itemId : 0,
		form,modalBody,isNew = setRadioSelectValues = reactivatePlugins = false,
		enableEditors = (enableEditors != false) ? true : false,
		copy = (copy != true) ? false : true;


	if(itemId > 0){
		item = list.find('[data-item-id="'+ itemId +'"]').data('item');
	}
	editItem = jQuery.extend( {}, editItem, item );
	
	if(itemId <= 0 || copy){		
		isNew = true;
		
		if(typeof window[listName]['last_id'] == 'undefined'){
			window[listName]['last_id'] = 0;
		}
		
		if(typeof window[listName]['list_counter'] == 'undefined'){
			window[listName]['list_counter'] = 0;
		}		
		editItem.id = itemId = window[listName]['last_id'] +1;
		editItem.list_counter = window[listName]['list_counter'] +1;
	}
	
	editItem.isNew = isNew;

	form = jQuery('#'+ listName +'_forms').find('[data-item-id="'+ itemId +'"]');

	// check if we have already a form edited IF NOT get a new one
	if(typeof form == 'undefined' || form.length <= 0){
		editItem = findParents(list,editItem);
		form = createForm(listName,editItem);
		setRadioSelectValues = true;
		reactivatePlugins = true;
	}

	// replace modal
	modalBody = jQuery('#fset_modal_form .modal-body');
	
	// remove editors if any
	if(deactivateEditors(modalBody)){
		// empty the modal body
		modalBody.children().remove();
	}
		
	form.appendTo(modalBody);
	
	// remove any identical form
	jQuery('#'+ listName +'_forms')
		.find('[data-item-id="'+ itemId +'"]').remove();
	
	if(!copy && enableEditors){
	//	activateEditors(modalBody);
	}
	
	if(setRadioSelectValues){
		setValues(modalBody,editItem);
	}
	if(reactivatePlugins){
		enablePlugins(modalBody);
	}
	
	checkOptionsForUniqueness(modalBody,listName, itemId);
	
	return true;
}

function createForm(listName,editItem){
	var tmplHTML = jQuery('#tmpl_'+ window[listName]['tmpl_form'] +'_form').html(),
		tmplSets = null;
		
	if(typeof window[listName]['doT_config'] != 'undefined'){
		tmplSets = window[listName]['doT_config'];
	}
	
	var formTemplater = doT.template(tmplHTML, tmplSets);

	// check if we have FILE input in the form
	if(typeof window[listName]['file_free'] == 'undefined'){
		window[listName]['file_free'] = (jQuery(jQuery('#tmpl_'+ window[listName]['tmpl_form'] +'_form').html()).find('input[type="file"]').length <= 0);
	}
		
	// fix for HTML in input type="text" (doT will not correctly manage it)
	jQuery(tmplHTML).find('input[type="text"]').each(function(){
		var fieldName = getFieldName(jQuery(this));		
		if(typeof editItem[fieldName] == 'string'){
			editItem[fieldName] = editItem[fieldName].replace(/"/g, '&quot;');
		}
	});		
	
	form = formTemplater(editItem);
	
	// JOOMLA fix textareaname JCE
	form = form.replace(new RegExp('textareaname', 'g'), 'textarea name');
	
	return jQuery(form);
}

function checkUniqueItem(listName,item){
	if(typeof window[listName]['unique'] == 'undefined'){
		return true;
	}
	
	// remove this item if it exists in the uniqueness list
	removeItemsFromUniqueList(listName,[item]);
	
	var result = checkItemsUniqueness(listName,[item]),
		modalSelector = window[listName]['popup'];
		
	if(result.length == 1){
		return true;
	} else {
		highlightUniqueFields(jQuery(modalSelector),window[listName].uniqueRules);	
		return false;
	}
}

function checkOptionsForUniqueness(jQueryObj, listName, itemId){
	if(typeof window[listName]['unique'] == 'undefined'){
		return true;
	}
	
	var SelectedValues = {};
	// create the current list of values
	jQuery.each(window[listName].uniqueRules,function(i,v){
		if(v.length != 1){
			return true;
		}
		SelectedValues[i] = [];
		for (var key in window[listName].uniqueElements[i]){
			var uniqueItem = window[listName].uniqueElements[i][key];
			if(uniqueItem.id != itemId){				
				SelectedValues[i].push(uniqueItem[v[0]]);
			}
		}
	});
	
	jQueryObj.find('select,input[type="radio"],input[type="checkbox"]').each(function(index){		
		var fieldName = getFieldName(jQuery(this));
		if(fieldName == ''){
			return true;
		}

		for(var i in window[listName].uniqueRules){
			if(fieldName != i){
				continue;
			}
			
			if(jQuery(this).is('select')){
				jQuery(this).find('option').each(function(){
					var value = jQuery(this).attr('value');
		
					if(jQuery.isArray(SelectedValues[i]) && jQuery.inArray(value,SelectedValues[i]) >= 0){
						jQuery(this).attr('disabled','disabled').prop('disabled',true);
					} else {
						jQuery(this).removeAttr('disabled').prop('disabled',false);
					}
					
				});
			} else if(jQuery(this).is('[type="radio"]')){
				// TO DO
				
			} else if(jQuery(this).is('[type="checkbox"]')){
				// TO DO
				
			}
		}
	});

}

function array_flip_withId(obj){
	var newObj = {};
	for(v in obj){
		var item = obj[v];
		
		newObj[item.id] = v;
	}
	
	return newObj;
}

function removeItemsFromUniqueList(listName,items){
	if(typeof window[listName]['unique'] == 'undefined'){
		return true;
	}
	
	jQuery.each(window[listName].uniqueRules,function(i,v){
		var flipArr = array_flip_withId(window[listName].uniqueElements[i]);
		
		for(var t=0;t<items.length;t++){
			if(typeof flipArr[items[t].id] != 'undefined'){
				delete window[listName].uniqueElements[i][flipArr[items[t].id]];
			}
		}
	});
}

function getFieldName(jQueryInput){
	var attrName,fieldName = '';
	if(jQueryInput.is('select,textarea,input:not([type="radio"]):not([type="checkbox"])')){
		attrName = jQueryInput.attr('name');

		if(typeof attrName != 'string'){
			return '';
		}
		
		fieldName = attrName.split('[').pop().replace(']', '');	
	} else if(jQueryInput.is('input[type="radio"],input[type="checkbox"]')){
		attrName = jQueryInput.attr('name');
			if(typeof attrName != 'string'){
				return true;
			}
			
			var val,
			nameParts = attrName.split('[');
			
			fieldName = nameParts.pop().replace(']', '');
		
		if(typeof fieldName == 'undefined' || fieldName == ''){
			fieldName = nameParts.pop().replace(']', '');
		}	
	}
	
	return fieldName;
}

function highlightUniqueFields(jQueryObj,uniqueRules){
	var errorMsg = 'This field must be UNIQUE, you already have an item with this value, change value';

	jQueryObj.find('select,textarea,input').each(function(index){
		var that = this,
			fieldName = getFieldName(jQuery(this));
		jQuery.each(uniqueRules,function(i,v){
			if(jQuery.inArray(fieldName,v) >= 0){
				if(typeof jQuery.fn.validationEngine != 'undefined'){
					jQuery(that).validationEngine('showPrompt', errorMsg,'red','centerRight',true);
				} else {
					alert(fieldName +': '+ errorMsg);
				}
			}
		});		
	});
}

function enablePlugins(jQueryObj){
	// tags manager
	if(typeof jQuery.fn.tagsManagerByGiro != 'undefined'){
		jQueryObj.find('input[tgman]').tagsManagerByGiro();
	}

	// timepickerbygiro
	if(typeof jQuery.fn.timepickerbygiro != 'undefined'){
		jQueryObj.find('div.timepicker-bygiro').each(function () {
		  var $timepicker = jQuery(this);
		  $timepicker.timepickerbygiro($timepicker.data());
		});
	}
	
	// reactivate radio buttons
	radiosInit(jQueryObj);

	// activate bootstrap popover
	jQueryObj.find('[data-toggle="popover"]')
		.popover()
		.on("hidden", function (e) {
			e.stopPropagation();
		});
	
	// activate bootstrap tooltip
	jQueryObj.find(".hasTooltip")
		.tooltip({"html": true})
		.on("hidden", function (e) {
			e.stopPropagation();
		});
	
	// enable jQuery validation engine
	if(typeof jQuery.fn.validationEngine != 'undefined'){
		jQuery('#fset_modal_form').validationEngine();
	}

	// conditional rules
	if(typeof triggerCondRulesOn == 'function'){
		triggerCondRulesOn(jQueryObj);
	}
}

function activateEditors(jQueryObj){	
	// enable editors
	var editorsFn = {};
	var editors = jQueryObj.find('textarea[data-editor]').each(function(){
		var editorType = jQuery(this).attr('data-editor');
		jQuery(this).addClass('editor_'+ editorType);
		if(typeof window['editors_repeatable'] != 'undefined' && typeof window['editors_repeatable'][editorType] == 'function'){
			if(typeof editorsFn[editorType] != 'undefined'){
				return true;
			}
			var sel = '#fset_modal_form .modal-body textarea[data-editor="'+ editorType +'"]';
			window['editors_repeatable'][editorType](sel);
		}
	});	
}

function deactivateEditors(jQueryObj){
	if(typeof tinyMCE != 'undefined' || typeof tinymce != 'undefined'){
		
		if(typeof tinyMCE != 'undefined'){
			tinyMCE.triggerSave();
		} else {
			tinymce.triggerSave();
		}
		
		var instancesMCE = [];
		for (var i = tinymce.editors.length - 1; i >= 0; i--) {
			instancesMCE.push(tinymce.editors[i].id);
		}
	}

	jQueryObj.find('textarea[data-editor]').each(function(){
		var textareaID = jQuery(this).attr('id'),
			editorType = jQuery(this).attr('data-editor');
			
		switch(editorType){
			case 'jce':
			case 'tinymce':
				if(typeof tinyMCE != 'undefined'){
					if(instancesMCE.indexOf(textareaID) >= 0){
						tinyMCE.execCommand('mceFocus', false, textareaID);
						tinyMCE.execCommand("mceRemoveControl", false, textareaID);
						tinyMCE.execCommand("mceRemoveEditor", false, textareaID);
					}					
				}
			default:
				break;
		}
	});
	
	return true;
}

function setValues(jQueryObj,it){
	jQueryObj.find('select,input[type="radio"],input[type="checkbox"]').each(function(index){
		var fieldName = getFieldName(jQuery(this));
		if(fieldName == ''){
			return true;
		}
		
		val = it[fieldName];
		
		if(jQuery(this).is('select')){
			jQuery(this).val(val);
			return true;
		}
		
		if(typeof it[fieldName] == "boolean"){
			val = 0;
			if(it[fieldName]){
				val = 1;
			}
		}

		var inputVal = jQuery(this).val();
		
		// convert all values of array to string
		if(val instanceof Array){
			for(var j=0;j<val.length;j++){
				val[j] = val[j].toString();
			}
		}
		
		if((!(val instanceof Array) && val == inputVal) || (jQuery.inArray(inputVal.toString(),val) >= 0)){
			jQuery(this).prop('checked', true);
		} else {
			jQuery(this).prop('checked', false);
			jQuery(this).removeAttr('checked');
		}
	});
}

function saveIt(list,afterId){
	var listName = list.closest('[data-repeatable]').attr('data-repeatable'),
		newItem = false,
		modalSelector = window[listName]['popup'],
		modalBody = jQuery(modalSelector + ' .modal-body'),
		afterId = (afterId > 0) ? afterId : false,
		tmplHTML = jQuery('#tmpl_'+ window[listName]['tmpl_item'] +'_item').html(),
		tmplSets = null;
		
		if(typeof window[listName]['doT_config'] != 'undefined'){
			tmplSets = window[listName]['doT_config'];
		}
		
		var itemTemplater = doT.template(tmplHTML, tmplSets);

	// needed to correctly get the textarea content
	deactivateEditors(modalBody);
	
	// get item from FORM
	var formContainer = jQuery(modalSelector).find('[data-item-id]'),
		objData = formContainer.serializeObject(),
		itemId = (parseInt(formContainer.attr('data-item-id')) || 0),
		isNew = (formContainer.attr('data-item-isnew') == 'true') ? true : false,
		item;

	// process data for jList
	var cleanObj = {};
	cleanObj.id = itemId;
	cleanObj.isNew = isNew;
	jQuery.each(objData,function(index,value){
		if(index.indexOf('[')){
			var groups = index.split('['),
			index = groups.pop().replace(']', '');
		
			if(typeof index == 'undefined' || index == ''){
				index = groups.pop().replace(']', '');
			}
		}
		if(index == 'remove_item'){
			return true;
		}

		cleanObj[index] = value;
	});

	// manage the JDOM upload fields -current -remove -view
	jQuery.each(cleanObj,function(index,value){	
		if(index.indexOf('-current') >= 0){
			var originalIndex = index.split('-');
			originalIndex = originalIndex.splice(0,(originalIndex.length -1)).join('-');
			if(typeof cleanObj[originalIndex +'-remove'] != 'undefined'){
				// set original field
				if(typeof cleanObj[originalIndex] == 'undefined' || cleanObj[originalIndex] == ''){					
					if(cleanObj[originalIndex + '-remove'] != ''){
						cleanObj[originalIndex] = '';
					} else {
						cleanObj[originalIndex] = cleanObj[index];
					}
				}
				
				// remove field-current and field-remove
				delete cleanObj[index];
				delete cleanObj[originalIndex +'-remove'];
			}
		}
		
		if(index.indexOf('-view') >= 0){
			var originalIndex = index.split('-');
			originalIndex = originalIndex.splice(0,(originalIndex.length -1)).join('-');
			
			cleanObj[originalIndex] = cleanObj[index];
			
			// remove field-view
			delete cleanObj[index];
		}		
	});

	if(cleanObj.id > 0){
		// check uniqueness item
		if(!checkUniqueItem(listName,cleanObj)){
			return false;
		}
		
		// check we already have the item
		item = list.find('[data-item-id="'+ cleanObj.id +'"]');

		if(item.length > 0){
			// update old data with the new data
			cleanObj = jQuery.extend( {}, item.data('item'), cleanObj );			

			// create the new Item
			newItem = itemTemplater(cleanObj);
			newItem = jQuery(newItem).data('item',cleanObj);
			
			// update DOM item
			item.replaceWith(newItem);
			
			// update data in jlistbygiro
			if(typeof jQuery.fn.jListByGiro != 'undefined' && typeof jQuery('#'+ listName + '_list').data('plugin_jListByGiro') != 'undefined'){
				jQuery('#'+ listName + '_list').jListByGiro('query',{
					task: 'update',
					where: ['id = '+ cleanObj.id ],
					justOne: true,
					set: cleanObj
				});
			}
		} else {		
			cleanObj.list_counter = window[listName]['list_counter'] +1;
			
			// create the new Item
			newItem = itemTemplater(cleanObj);
			newItem = jQuery(newItem).data('item',cleanObj);
			
			// add item
			if(afterId){
				var itemBefore = list.find('[data-item-id="'+ parseInt(afterId) +'"]');
				if(itemBefore.length > 0){
					itemBefore.after(newItem);
				} else {
					list.append(newItem);
				}
			} else {
				list.append(newItem);
			}
			
			// add item to jlist
			if(typeof jQuery.fn.jListByGiro != 'undefined' && typeof jQuery('#'+ listName + '_list').data('plugin_jListByGiro') != 'undefined'){				
				jQuery('#'+ listName + '_list').jListByGiro('add',cleanObj, false);
			}
			
			// increase the counter & last_id
			window[listName]['last_id'] = itemId = cleanObj.id;
			window[listName]['list_counter']++;
		}	

		// move form to list forms container
		formContainer.appendTo('#'+ listName +'_forms');
	}
	
	if(cleanObj.isNew){
		// disable the COPY button (to avoid issues with file upload, the filename will be changed after upload)
		if(!window[listName]['file_free']){
			list.find('[data-item-id="'+ cleanObj.id +'"] .btn-copy').addClass('disabled');
		}
		
		// update ordering
		updateListOrdering(list);
	}

	// remove editors if any
	if(deactivateEditors(modalBody)){
		// empty the modal body
		modalBody.children().remove();
	}
	
	return true;
}

function removeIt(list,itemId){
	var form,listName = list.closest('[data-repeatable]').attr('data-repeatable'),
		tmplHTML = jQuery('#tmpl_'+ window[listName]['tmpl_form'] +'_form').html(),
		tmplSets = null,
		item = list.find('[data-item-id="'+ itemId +'"]').data('item');
		
		if(typeof window[listName]['doT_config'] != 'undefined'){
			tmplSets = window[listName]['doT_config'];
		}
		
		var formTemplater = doT.template(tmplHTML, tmplSets);

	// check if we have already a form edited IF NOT get a new one
	form = jQuery('#'+ listName +'_forms').find('[data-item-id="'+ itemId +'"]');
	
	if(typeof form == 'undefined' || form.length <= 0){
		form = formTemplater({id:itemId});
		
		// add item in the forms_list
		jQuery('#'+ listName +'_forms').append(form);
		
		form = jQuery('#'+ listName +'_forms').find('[data-item-id="'+ itemId +'"]');
	}	
		
	// add trigger to remove (by PHP) the item
		form.find('input.remove_item').val(1);	
		
	if(typeof jQuery.fn.jListByGiro != 'undefined' && typeof jQuery('#'+ listName + '_list').data('plugin_jListByGiro') != 'undefined'){
		// remove item from list
		jQuery('#'+ listName + '_list').jListByGiro('query',{
			task: 'delete',
			where: ['id = '+ itemId ],
			justOne: true
		});
	} else {
		list.find('[data-item-id="'+ itemId +'"]').remove();
	}
	
	// remove items from unique list
	if(typeof item.id != 'undefined'){
		removeItemsFromUniqueList(listName,[item]);
	}
}

function initList(list,options){
	var listName = list.closest('[data-repeatable]').attr('data-repeatable');
		
		if(typeof window[listName] == 'undefined'){
			return;
		}
		window[listName] = (window[listName] || {});
		
		var tmplHTML = jQuery('#tmpl_'+ window[listName]['tmpl_item'] +'_item').html(),
		itemTemplater,tmplSets = null;		

	if(typeof tmplHTML != 'undefined'){		
		if(typeof window[listName]['doT_config'] != 'undefined'){
			tmplSets = window[listName]['doT_config'];
		}		
		
		var itemTemplater = doT.template(tmplHTML, tmplSets);

		options.templater = itemTemplater;	
	}

	// check uniqueness if needed
	options.values = checkItemsUniqueness(listName, options.values);
	
	// check IDs if missing
	options.values = checkItemsIds(listName, options.values);

	if(typeof jQuery.fn.jListByGiro != 'undefined' && typeof options.jListByGiro != 'undefined' && options.jListByGiro){
		jQuery('#'+ listName +'_list').jListByGiro(options);
	} else {
		if(typeof itemTemplater == 'undefined'){
			return;
		}

		jQuery.each(options.values,function(ind,item){
			if(typeof item != 'object' || item == null){
				return true;
			}
			var newItem = itemTemplater(item);
			newItem = jQuery(newItem).data('item',item);
			list.append(newItem);
		});
	}
	
	// current ordering
	updateListOrdering(list);
}

function updateListOrdering(masterList){
	var ordering = [];
	
	if (typeof masterList.attr('data-repeatable') == 'undefined' || masterList.attr('data-repeatable') == false) {
		masterList = masterList.closest('[data-repeatable]');
	}
	var listName = masterList.attr('data-repeatable');
	if(typeof jQuery.fn.jListByGiro != 'undefined' && typeof jQuery('#'+ listName + '_list').data('plugin_jListByGiro') != 'undefined'){
		// TO DO
	} else {
		masterList.find('.'+ listName +'_list').children().each(function(index){
			ordering.push(jQuery(this).attr('data-item-id')); 
		});		
	}
	
	jQuery('#'+ listName +'_list_ordering').val(ordering.join());
}

function checkItemsIds(listName,items){		
	var ids = [],
		list_counter = 0,
		items_with_missing_id = [];
	
	jQuery.each(items,function(ind,item){
		if(typeof item != 'object' || item == null){
			return true;
		}
		
		// get all IDs if any
		list_counter++;		
		window[listName]['list_counter'] = items[ind].list_counter = list_counter;
		
		var id = parseInt(item.id);
		if(id >= 0){
			ids.push(id);
		} else {
			items_with_missing_id.push(ind);
		}		
	});
	
	var maxId = (parseInt(findMax(ids)) || 0);
	
	// set missing Ids
	jQuery.each(items_with_missing_id,function(i,ind){
		var item = items[ind];
		if(typeof item != 'object' || item == null){
			return true;
		}
		maxId++;
		items[ind]['id'] = maxId;
	});
	window[listName]['last_id'] = maxId;
	window[listName]['list_counter'] = list_counter;

	return items;
}

function checkItemsUniqueness(listName,items){
	if(typeof window[listName] == 'undefined'){
		return items;
	}
	
	if(typeof window[listName].uniqueElements == 'undefined' && typeof window[listName].uniqueRules == 'undefined'){
		window[listName].uniqueElements = {},
		window[listName].uniqueRules = {};
		
		if(typeof window[listName]['unique'] != 'undefined'){
			var unique = window[listName]['unique'];
			jQuery.each(unique,function(i,v){
				window[listName].uniqueRules[v] = v.split('.');
				window[listName].uniqueElements[v] = {};
			});
		}
	}
	
	jQuery.each(items,function(ind,item){
		if(typeof item != 'object' || item == null){
			return true;
		}

		// check uniqueness fields
		if(typeof window[listName]['unique'] != 'undefined'){
			jQuery.each(window[listName].uniqueRules,function(i,v){				
				var vKey = [];
				for(var k=0;k<v.length;k++){
					if(typeof item[v[k]] != 'undefined'){						
						if(item[v[k]] != ''){
							vKey.push(item[v[k]].toString());
						}
					}
				}
				
				if(vKey.length > 0){
					if(typeof window[listName].uniqueElements[i][vKey.toString()] == 'undefined'){
						window[listName].uniqueElements[i][vKey.toString()] = item;
					} else {
						// remove this item from the MAIN list, it's not unique
						items.splice(ind,1);
					}
				}
			});
		}	
	});
	
	return items;
}

function findMax(a){
	var m = -Infinity, i = 0, n = a.length;

	for (; i != n; ++i) {
		if (a[i] > m) {
			m = a[i];
		}
	}

	return m;
}

jQuery(document).ready(function(){
	// move the modal box directly in the body tag
	jQuery('#fset_modal_form').appendTo('body');
	
	// add triggers
	jQuery('[data-repeatable]').on('click','.btn-edit:not(.disabled)',function(){	
		var listContainer = jQuery(this).closest('[data-repeatable]'),
			listName = listContainer.attr('data-repeatable'),
			list = listContainer.find('.'+ listName +'_list'),
			itemId = jQuery(this).closest('[data-item-id]').attr('data-item-id');

		jQuery('#fset_modal_form')
			.attr('data-listname',listName)
			.attr('data-listid',listContainer.attr('id'));

		// prepare the modal window		
		editIt(list,itemId);
	
		// open the modal
		jQuery('#fset_modal_form').modal('show');	
	});
	
	jQuery('[data-repeatable]').on('click','.btn-copy:not(.disabled)',function(){
		var listContainer = jQuery(this).closest('[data-repeatable]'),
			listName = listContainer.attr('data-repeatable'),
			list = listContainer.find('.'+ listName +'_list'),
			itemId = jQuery(this).closest('[data-item-id]').attr('data-item-id');
		
		jQuery('#fset_modal_form')
			.attr('data-listname',listName)
			.attr('data-listid',listContainer.attr('id'));
			
		editIt(list,itemId,true);
		saveIt(list,itemId);
		updateListOrdering(list);
	});

	jQuery('[data-repeatable]').on('click','.btn-new:not(.disabled)',function(){
		var listContainer = jQuery(this).closest('[data-repeatable]'),
			listName = listContainer.attr('data-repeatable'),
			list = listContainer.find('.'+ listName +'_list');
		
		jQuery('#fset_modal_form')
			.attr('data-listname',listName)
			.attr('data-listid',listContainer.attr('id'));
			
		editIt(list);
		
		// open the modal
		jQuery('#fset_modal_form').modal('show');		
	});
	
	jQuery('[data-repeatable]').on('click','.btn-delete:not(.disabled)',function(){
		var listContainer = jQuery(this).closest('[data-repeatable]'),
			listName = listContainer.attr('data-repeatable'),
			list = listContainer.find('.'+ listName +'_list');
		
		var r = true;
		if(jQuery('#security_lock_delete_'+ listContainer.attr('id')).is(':checked')){
			r=confirm(Joomla.JText._("PLG_JDOM_ALERT_ASK_BEFORE_REMOVE"));
		}		
		
		if(!r){
			return;
		}
		var itemId = jQuery(this).closest('[data-item-id]').attr('data-item-id');
		
		removeIt(list,itemId);
		updateListOrdering(list);
	});

	jQuery('.modal.popupform').on('click','[data-dismiss="modal"]', function(e){
		var listContainerId = jQuery(this).closest('[data-listid]').attr('data-listid'),
			listContainer = jQuery('body').find('#'+ listContainerId),
			listName = listContainer.attr('data-repeatable'),
			list = listContainer.find('.'+ listName +'_list'),
			itemId = jQuery('#fset_modal_form .modal-body').find('[data-item-id]').attr('data-item-id');
		
		// do all the background work
		if(editIt(list,itemId, false, false)){
			saveIt(list);
		}

	});
		
	jQuery('.modal.popupform').on('click','.btn-apply',function(){
		var valid = false,
			listContainerId = jQuery(this).closest('[data-listid]').attr('data-listid'),
			listContainer = jQuery('body').find('#'+ listContainerId),
			listName = listContainer.attr('data-repeatable'),
			list = listContainer.find('.'+ listName +'_list');

			
		// check the form is valid
		if(typeof jQuery.fn.validationEngine != 'undefined'){
			valid = checkItemForm(list);
		} else {
			valid = true;
		}

		if(valid){
			if(saveIt(list)){
				jQuery('#fset_modal_form').modal('hide');
			}
		}
	});	
});

function checkItemForm(list){
	var listName = list.closest('[data-repeatable]').attr('data-repeatable'),
		modalSelector = '#fset_modal_form .modal-body',
		scrollExecuted = false,validations = [],
		formElements = jQuery(modalSelector).find('input, select, textarea');

	if(formElements.length == 0){
		return true;
	}
	
	formElements.each(function(ind){
		var validationResult;
		if(!jQuery(this).is('[type="radio"]')){
			validationResult = !jQuery(this).validationEngine('validate');
		} else {
			var attrName = jQuery(this).attr('name');
			validationResult = !jQuery(modalSelector).find('[name="'+ attrName +'"]').validationEngine('validate');
		}
		
		
		validations.push(validationResult);		
		if(!scrollExecuted && !validationResult){
			var scroll_opts = {
					target: jQuery(this),
					topoffset: 80,
					parent: modalSelector
				}
			scrollToElement(scroll_opts);
		}
	});

	if(!validations.AllValuesSame()){
		return false;
	} else if(validations.AllValuesSame()) {
		if(validations[0]){
			return true;
		}
		return false;
	}

	return false;
}

function findParents(currentList,item){	
	currentList.parents('[data-item-id]').each(function(ind){
		if(ind == 0){
			ind = '';
		}
		item['pid'+ ind] = jQuery(this).attr('data-item-id');
	});
	
	return item;
}

// fix the MCE focus in the DOUBLE popup
jQuery(document).on('focusin', function(e) {
    if (jQuery(e.target).closest(".mce-window").length) {
        e.stopImmediatePropagation();
    }
});