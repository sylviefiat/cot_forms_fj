/*
* @version		0.0.1
* @package		jForms
* @copyright	G. Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU GPL v3 or later
*/

function triggerCondRulesOn(searchOn){
	if(typeof searchOn == 'undefined'){
		searchOn = jQuery('body');
	} else if(!(searchOn instanceof jQuery)){
		searchOn = jQuery(searchOn);
	}
	var formElements = ['input','select','textarea'],
		condRulesElements = searchOn.find('[class^="condRule"], [class*=" condRule"]');

	var rulesGroups = {};
	condRulesElements.each(function(){
		var targetEle = self = jQuery(this),
			typeEle = self.get(0).tagName;
		
		typeEle = typeEle.toLowerCase();
		if(jQuery.inArray(typeEle,formElements) >= 0 || self.hasClass('form-widget')){
			var cGroup = self.closest('.control-group');
			if(cGroup.length > 0){
				targetEle = cGroup;
			}			
		}
		
		var inputClass = self.attr('class');
		
		var classes = inputClass.split(' ');
		
		jQuery.each(classes,function(ind,val){			
			if(val.indexOf('condRule')<0){
				return true;
			}			
			var ruleParts = val.substring(9,(val.length -1));
			ruleParts = ruleParts.split(',');
			var rule = {};
			rule.task = ruleParts[0];
			rule.trigger = ruleParts[1];
			rule.value = ruleParts[2];	
			rule.target = targetEle;
			rule.element = self;
			
			if(typeof rulesGroups[rule.trigger] == 'undefined'){
				rulesGroups[rule.trigger] = [];
			}
			
			rulesGroups[rule.trigger].push(rule);
		});
	});	
	
	jQuery.each(rulesGroups,function(trigger, rules){
		var triggerEle;
		
		if(rules.length <= 0){
			return true;
		}
		
		var self = rules[0].element,
			container = self.closest('.formFieldsContainer');
			
		if(container.length <= 0){
			container = self.closest('form');
		}

		if(container.length <= 0){
			container = jQuery('body');
		}		
		
		// fix for BAD HTML when many targets have the same ID
		var mainTrigger = container.find(trigger);
		
		if(mainTrigger.length <= 0){
			return true;
		}
		
		var tagName = mainTrigger.get(0).tagName;
		tagName = tagName.toLowerCase();
		
		if(jQuery.inArray(tagName,formElements) < 0){
			// find all the form elements inside this element
			triggerEle = mainTrigger.find(formElements.join(','));
		} else {
			triggerEle = mainTrigger;
		}
		
		jQuery.each(rules,function(i,rule){		
			doCondTask(rule,triggerEle,rule.target);
		});
		
		
		triggerEle.on('keyup change click',function(event){
			var withDelay = 1200,
				doIt = false,
				eleType,
				that = jQuery(this),
				tagName = that.get(0).tagName,
				type = that.attr('type');
				
			if(typeof type != 'undefined'){
				type = '_'+type;
			} else {
				type = '';
			}
			
			eleType = tagName + type;
			eleType = eleType.toLowerCase();
			switch(eleType){							
				case 'select':
				case 'input_hidden':
				case 'input_file':
					// onchange
					if(event.type == 'change'){
						doIt = true;
						withDelay = 50;
					}
					break;
					
				case 'input_radio':
				case 'input_checkbox':
					// onclick
					if(event.type == 'click'){
						doIt = true;
						withDelay = 50;
					}							
					break;

				case 'input_text':
				case 'textarea':						
				default:
					// onkeyup
					if(event.type == 'keyup'){
						doIt = true;
					}							
					break;
			}
		
			if(!doIt){
				return;
			}

			// check all values to see if we match the condition rule
			delay(
				function(){
					jQuery.each(rules,function(i,rule){
						doCondTask(rule,triggerEle,rule.target);
					});
				},
				withDelay
			);
		});
	});	
}

function doCondTask(rule,triggerEle,targetEle){
		var cond = false;		
		triggerEle.each(function(){
			var eleType,
				that = jQuery(this),
				tagName = that.get(0).tagName,
				type = that.attr('type');
				
			if(typeof type != 'undefined'){
				type = '_'+type;
			} else {
				type = '';
			}
			
			eleType = tagName + type;						

			switch(eleType.toLowerCase()){							
				case 'select':
					if(rule.value != '' && rule.value != '*'){
						cond = (that.val() == rule.value);
					} else if(rule.value == '*'){
						cond = (that.val() != '');
					}
					
					break;
					
				case 'input_radio':
				case 'input_checkbox':
					if((rule.value != '' && that.val() == rule.value) || rule.value == '*'){
						cond = (that.is(':checked') || that.prop('checked'));
					}
					break;
					
				case 'input_file':
				case 'input_text':
				case 'textarea':
				case 'input_hidden':
				default:
					cond = (that.val() != '');
					break;
			}
			
			if(cond == true){		
				return false;
			}
		});
		
		// callback
		var eType = 'condRule.matched';
		if(cond != true){
			eType = 'condRule.unmatched';
		}
		var e = jQuery.Event(eType);
		rule.element.trigger(e, [rule,targetEle,triggerEle]);

		// do the task
		switch(rule.task){
			case 'show':
				break;
				
			case 'hide':
				cond = !cond;
				break;
		}
		
		if(cond == true){
			targetEle.slideDown();
		} else {
			targetEle.slideUp();
		}
	}
	
var delay = (function(){
  var timer = 0;
  return function(callback, ms){
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

jQuery(document).ready(function(){
	triggerCondRulesOn();
});