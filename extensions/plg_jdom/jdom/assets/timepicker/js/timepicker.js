/*
 * A time picker for jQuery
 *
 * Dual licensed under the MIT and GPL licenses.
 * Copyright (c) 2013 Girolamo Tomaselli
 * @name     Simple Bootstrap timePicker
 * @author   Girolamo Tomaselli (http://bygiro.com)
 *
 * Based on:bootstrap-formhelpers-timepicker.js (https://github.com/vlamanna/BootstrapFormHelpers)
 *
 */

(function( $ ) {
	"use strict";

	 /* TIMEPICKER CLASS DEFINITION
	  * ========================= */

	  var toggle = '[data-toggle="timepicker-bygiro"]'
		, TimePickerByGiro = function (element, options) {
			this.options = $.extend({}, $.fn.timepickerbygiro.defaults, options)
			this.$element = $(element)
			this.initPopover()
		  }

	  TimePickerByGiro.prototype = {

		constructor: TimePickerByGiro
	  
	  , initPopover: function() {
		var time = this.options.time,
			start = (this.options.start || ''),
			end = (this.options.end || ''),
			step = (this.options.step || 1);

		// check current input time
		var currentTime = this.$element.find('.timepicker-bygiro-toggle > input[type="text"]').val();
		if(currentTime != ''){
			time = currentTime;
		}
		
		this.$element.data('starthour', '');
		this.$element.data('startminute', '');
		this.$element.data('endhour', '');
		this.$element.data('endminute', '');
		this.$element.data('step', step);
		if(start != '' && end != ''){
			var sTimeParts = new String(start).split(":");
			this.$element.data('starthour', sTimeParts[0]);
			this.$element.data('startminute', sTimeParts[1]);

			var eTimeParts = new String(end).split(":");
			this.$element.data('endhour', eTimeParts[0]);
			this.$element.data('endminute', eTimeParts[1]);
		}
			
		if (time == "") {
		  var today = new Date()
		
		  this.$element.data('hour', today.getHours())
		  this.$element.data('minute', today.getMinutes())
		} else {
		  this.$element.find('.timepicker-bygiro-toggle').val(time)
		  var timeParts = new String(time).split(":")
		  this.$element.data('hour', timeParts[0])
		  this.$element.data('minute', timeParts[1])
		}
		
		this.updatePopover()
	  }
	  
	  , updatePopover: function() {
		var hour = this.$element.data('hour'),
			minute = this.$element.data('minute'),
			starthour = this.$element.data('starthour'),
			startminute = this.$element.data('startminute'),
			endhour = this.$element.data('endhour'),
			endminute = this.$element.data('endminute');
		
		// check valid hour
		if(starthour != '' && endhour != ''){
			if(parseInt(hour) < parseInt(starthour)){
				hour = starthour;
			} else if(parseInt(hour) > parseInt(endhour)){
				hour = endhour;
			}
		}
		
		hour = new String(hour)
		if (hour.length == 1) {
		  hour = "0" + hour
		}

		
		// check valid minute
		if(startminute != '' && endminute != ''){
			if(hour == starthour){
				if(parseInt(minute) < parseInt(startminute)){
					minute = startminute;
				}
			} else if(hour == endhour){
				if(parseInt(minute) > parseInt(endminute)){
					minute = endminute;
				}				
			}
		}
		
		minute = new String(minute)
		if (minute.length == 1) {
		  minute = "0" + minute
		}
		
		this.$element.find('.hour > input[type=text]').val(hour)
		this.$element.find('.minute > input[type=text]').val(minute)
		this.$element.find('.timepicker-bygiro-toggle > input[type=text]').val(hour + ":" + minute)
	  }
	  
	  , previousHour: function (e) {
		var $this = $(this)
		  , $parent
		  , $timePicker;
		  
		$parent = $this.closest('.timepicker-bygiro')

		var minute = $parent.data('minute'),
			hour = $parent.data('hour'),
			starthour = $parent.data('starthour'),
			startminute = $parent.data('startminute'),
			endhour = $parent.data('endhour'),
			endminute = $parent.data('endminute');
		
		if (hour == 23 || hour < 0) {
			hour = 0;
		} else {
			hour--;
		}

		// check valid hour
		if(starthour != '' && endhour != ''){
			if(parseInt(hour) < parseInt(starthour)){
				hour = starthour;
			} else if(parseInt(hour) > parseInt(endhour)){
				hour = endhour;
			}
		}
		
		$parent.data('hour', new Number(hour));
		
		
		// check valid minute
		if(startminute != '' && endminute != ''){
			if(hour == starthour){
				if(parseInt(minute) < parseInt(startminute)){
					minute = startminute;
				}
			} else if(hour == endhour){
				if(parseInt(minute) > parseInt(endminute)){
					minute = endminute;
				}				
			}
		}
		
		$parent.data('minute', new Number(minute));		
		
		$timePicker = $parent.data('timepickerbygiro')
		$timePicker.updatePopover()
		
		return false;
	  }
	  
	  , nextHour: function (e) {
		var $this = $(this)
		  , $parent
		  , $timePicker;
		  
		$parent = $this.closest('.timepicker-bygiro')

		var minute = $parent.data('minute'),
			hour = $parent.data('hour'),
			starthour = $parent.data('starthour'),
			startminute = $parent.data('startminute'),
			endhour = $parent.data('endhour'),
			endminute = $parent.data('endminute');
			
		if (hour == 23) {
			hour = 0;
		} else {
			hour++;
		}

		// check valid hour
		if(starthour != '' && endhour != ''){
			if(parseInt(hour) < parseInt(starthour)){
				hour = starthour;
			} else if(parseInt(hour) > parseInt(endhour)){
				hour = endhour;
			}
		}
		
		$parent.data('hour', new Number(hour));


		// check valid minute
		if(startminute != '' && endminute != ''){
			if(hour == starthour){
				if(parseInt(minute) < parseInt(startminute)){
					minute = startminute;
				}
			} else if(hour == endhour){
				if(parseInt(minute) > parseInt(endminute)){
					minute = endminute;
				}				
			}
		}
		
		$parent.data('minute', new Number(minute));
		
		$timePicker = $parent.data('timepickerbygiro')
		$timePicker.updatePopover()
		
		return false;
	  }
	  
	  , previousMinute: function (e) {
		var $this = $(this)
		  , $parent
		  , $timePicker;
		  
		$parent = $this.closest('.timepicker-bygiro');
		
		var minute = $parent.data('minute'),
			hour = $parent.data('hour'),
			starthour = $parent.data('starthour'),
			startminute = $parent.data('startminute'),
			endhour = $parent.data('endhour'),
			endminute = $parent.data('endminute'),
			step = $parent.data('step');
			
		if (minute == 59 || (minute - step) < 0) {
			minute = 0;
		} else {
			minute -= step;
		}

		// check valid minute
		if(startminute != '' && endminute != ''){
			if(hour == starthour){
				if(parseInt(minute) < parseInt(startminute)){
					minute = startminute;
				}
			} else if(hour == endhour){
				if(parseInt(minute) > parseInt(endminute)){
					minute = endminute;
				}				
			}
		}
		
		$parent.data('minute', new Number(minute));
		
		$timePicker = $parent.data('timepickerbygiro')
		$timePicker.updatePopover()
		
		return false;
	  }
	  
	  , nextMinute: function (e) {
		var $this = $(this)
		  , $parent
		  , $timePicker;
		  
		$parent = $this.closest('.timepicker-bygiro');
		
		var minute = $parent.data('minute'),
			hour = $parent.data('hour'),
			starthour = $parent.data('starthour'),
			startminute = $parent.data('startminute'),
			endhour = $parent.data('endhour'),
			endminute = $parent.data('endminute'),
			step = $parent.data('step');

		if (minute == 59 || (minute + step) > 59) {
			minute = 0;
		} else {
			minute += step;
		}

		// check valid minute
		if(startminute != '' && endminute != ''){
			if(hour == starthour){
				if(parseInt(minute) < parseInt(startminute)){
					minute = startminute;
				}
			} else if(hour == endhour){
				if(parseInt(minute) > parseInt(endminute)){
					minute = endminute;
				}				
			}
		}
		
		$parent.data('minute', new Number(minute));
		
		$timePicker = $parent.data('timepickerbygiro')
		$timePicker.updatePopover()
		
		return false;
	  }
	  
	  , toggle: function (e) {
		  var $this = $(this)
			, $parent
			, isActive;

		  if ($this.is('.disabled, :disabled')) return

		  $parent = getParent($this)

		  isActive = $parent.hasClass('open')

		  clearMenus()

		  if (!isActive) {
			$parent.toggleClass('open')
		  }

		  return false
		}
	  }

	  function clearMenus() {
		getParent($(toggle))
		  .removeClass('open')
	  }

	  function getParent($this) {
		var selector = $this.attr('data-target')
		  , $parent

		if (!selector) {
		  selector = $this.attr('href')
		  selector = selector && /#/.test(selector) && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
		}

		$parent = $(selector)
		$parent.length || ($parent = $this.parent())

		return $parent
	  }


	  /* TIMEPICKER PLUGIN DEFINITION
	   * ========================== */

	  $.fn.timepickerbygiro = function (option) {
		return this.each(function () {
		  var $this = $(this)
			, data = $this.data('timepickerbygiro')
			, options = typeof option == 'object' && option
			
		  if (!data) $this.data('timepickerbygiro', (data = new TimePickerByGiro(this, options)))
		  if (typeof option == 'string') data[option]()
		})
	  }

	  $.fn.timepickerbygiro.Constructor = TimePickerByGiro

	  $.fn.timepickerbygiro.defaults = {
		time: "",
		step: 1
	  }
	  
	  /* APPLY TO STANDARD TIMEPICKER ELEMENTS
	   * =================================== */

	  $(window).on('load', function () {
		$('div.timepicker-bygiro').each(function () {
		  var $timepicker = $(this)

		  $timepicker.timepickerbygiro($timepicker.data())
		})
	  })
	  
	  $(function () {
		$('html')
		  .on('click.timepickerbygiro.data-api', clearMenus)
		$('body')
		  .on('click.timepickerbygiro.data-api touchstart.timepickerbygiro.data-api', toggle, TimePickerByGiro.prototype.toggle)
		  .on('click.timepickerbygiro.data-api touchstart.timepickerbygiro.data-api', '.timepicker-bygiro-popover > table .hour > .previous', TimePickerByGiro.prototype.previousHour)
		  .on('click.timepickerbygiro.data-api touchstart.timepickerbygiro.data-api', '.timepicker-bygiro-popover > table .hour > .next', TimePickerByGiro.prototype.nextHour)
		  .on('click.timepickerbygiro.data-api touchstart.timepickerbygiro.data-api', '.timepicker-bygiro-popover > table .minute > .previous', TimePickerByGiro.prototype.previousMinute)
		  .on('click.timepickerbygiro.data-api touchstart.timepickerbygiro.data-api', '.timepicker-bygiro-popover > table .minute > .next', TimePickerByGiro.prototype.nextMinute)
		  .on('click.timepickerbygiro.data-api touchstart.timepickerbygiro.data-api', '.timepicker-bygiro-popover > table', function() { return false })
	  });
	return this;
	
}( jQuery ));
