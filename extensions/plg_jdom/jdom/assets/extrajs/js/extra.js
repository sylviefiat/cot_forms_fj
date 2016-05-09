/*
* @name			extra.js - a little helper for common JS functions
* @version		0.0.1
* @copyright	G. Tomaselli
* @author		Girolamo Tomaselli - http://bygiro.com - girotomaselli@gmail.com
* @license		GNU GPL v3 or later
*/
Array.prototype.AllValuesSame = function(){
    if(this.length > 0) {
        for(var i = 1; i < this.length; i++)
        {
            if(this[i] !== this[0])
                return false;
        }
    } 
    return true;
}

function scrollToElement( options ) {
	var defaults = {
		target: null,
		topoffset: 100,
		parent: 'html,body',
		speed: 1300
	}

	var o = jQuery.extend( defaults, options);
	
	if (typeof o.target == 'undefined'){
		return;
	} else if(!(o.target instanceof jQuery)){
		o.target = jQuery(o.target);
	}

	if(o.target.length < 0){
		return;
	}
	
	if((!(o.parent instanceof jQuery) && o.parent != '')){
		o.parent = jQuery(o.parent);
	}

	if(o.parent.length < 0){
		o.parent = jQuery(defaults.parent);
	}

	var destination = (o.target.offset().top - o.parent.offset().top) - o.topoffset;

	o.parent.animate( { scrollTop: destination}, o.speed, function() {
	/*	window.location.hash = target; */
	});

    return false;
}

jQuery.fn.serializeObject = function()
{
    var a,o = {};
	a = this.find('input,textarea,select').serializeArray();

    jQuery.each(a, function(i,v) {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function escapeHtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

function basename(path){
	var result = '';
	if(typeof path == 'string'){
		result = path.split(/[\\/]/).pop();
	}
	return result;
}

function ajaxCall(opts){
	var defaults = {
		route: '',
		varsToSend: {},
		caller: 'body',
		target: '#system-message-container',
		replace: false,
		callBack: function(){}
	}

	var o = jQuery.extend( defaults, opts);
	
	if(!(o.caller instanceof jQuery)){
		o.caller = jQuery(o.caller);
	}

	if(!(o.target instanceof jQuery)){
		o.target = jQuery(o.target);
	}
		
	o.caller.jdomAjax({
		namespace:o.route,
		vars:o.varsToSend,
		loading: function(object)
		{
			var mess = '',loadingImage = 'spinner.gif';			
			if(o.caller.width() < 40){
				loadingImage = '';
			} else if(o.caller.width() > 100){
				loadingImage = 'spinner-big.gif';
			}
			
			if(loadingImage != ''){
				mess = '<img src="libraries/jdom/assets/ajax/images/'+ loadingImage +'"/>';
			}
			
			if(typeof jQuery.fn.block != 'undefined'){
				o.caller.addClass('loading').block({ 
					message: mess
				});
			}
		},
		success: function(object, data, textStatus, jqXHR)
		{
			var thisp = this;

			//fill the target with the returned html
			if(o.replace){
				o.target.html(data);
			} else {
				o.target.append(data);
			}
			
			$(object).ready(function()
			{
				if (typeof(thisp.ready) != 'undefined')
					thisp.ready(object, data, textStatus, jqXHR);	
			});
			
			if(typeof jQuery.fn.unblock != 'undefined'){
				o.caller.removeClass('loading').unblock();
			}
			
			if(typeof o.callBack == 'function'){
				o.callBack(data);
			}
		},
		error: function(object, jqXHR, textStatus, errorThrown)
		{
		
		}		
	});
}

function add(a, b, precision) {
    var x = Math.pow(10, precision || 2);
    return (Math.round(a * x) + Math.round(b * x)) / x;
}

function sub(a, b, precision) {
    var x = Math.pow(10, precision || 2);
    return (Math.round(a * x) - Math.round(b * x)) / x;
}

var delay = (function(){
	var timer = 0;
	return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
})();

function countProperties(obj) {
    var count = 0;
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            ++count;
    }
    return count;
}


function checkFormStep(index,formID){
	var scrollExecuted = false,validations = [],
		form = jQuery('#'+ formID +'Step'+index),
		formElements = form.find('input, select, textarea');

	if(formElements.length == 0){
		return true;
	}
	formElements.each(function(ind){
		var validationResult = !jQuery(this).validationEngine('validate');
		validations.push(validationResult);
		if(!scrollExecuted && !validationResult){
			var scroll_opts = {
					target: this
				}
			scrollToElement(scroll_opts);
			scrollExecuted = true;
		}
	});
	
	if(validations.AllValuesSame()) {
		if(validations[0]){
			return true;
		}
	}

	return false;
}