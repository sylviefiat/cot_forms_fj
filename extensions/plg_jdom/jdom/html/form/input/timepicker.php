<?php
/**                               ______________________________________________
*                          o O   |                                              |
*                 (((((  o      <     JDom Class - Cook Self Service library    |
*                ( o o )         |______________________________________________|
* --------oOOO-----(_)-----OOOo---------------------------------- www.j-cook.pro --- +
* @version		2.5
* @package		Cook Self Service
* @subpackage	JDom
* @license		GNU General Public License
* @author		Jocelyn HUARD
*
*             .oooO  Oooo.
*             (   )  (   )
* -------------\ (----) /----------------------------------------------------------- +
*               \_)  (_/
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


class JDomHtmlFormInputTimepicker extends JDomHtmlFormInput
{
	var $size;
	var $time;
	var $start;
	var $end;
	var $step;

	/*
	 * Constuctor
	 * 	@namespace 	: requested class
	 *  @options	: Configuration
	 * 	@dataKey	: database field name
	 * 	@dataObject	: complete object row (stdClass or Array)
	 * 	@dataValue	: value  default = dataObject->dataKey
	 * 	@domID		: HTML id (DOM)  default=dataKey
	 *
	 * 	@size		: Input Size
	 * 
	 */
	function __construct($args)
	{
		parent::__construct($args);

		$this->arg('size'		, null, $args, '32');
		$this->arg('time'		, null, $args, '8:00');
		$this->arg('start'		, null, $args, null);
		$this->arg('end'		, null, $args, null);
		$this->arg('step'		, null, $args, 1);
	}
	
	function build()
	{
	
		JDom::_('framework.bootstrap.timepicker');
		
		$time = $this->time;
		$start = $this->start;
		$end = $this->end;
		$step = $this->step;
		
		$input =	'<input type="text" id="<%DOM_ID%>" name="<%INPUT_NAME%>"<%STYLE%><%CLASS%><%SELECTORS%>'
			.	' value="<%VALUE%>"'
			.	' size="' . $this->size . '"'
			.	' readonly/>' .LN
			.	'<%VALIDOR_ICON%>'.LN
			.	'<%MESSAGE%>';

		$html = '<div class="timepicker-bygiro" data-start="'. $start .'" data-end="'. $end .'" data-time="'. $time .'" data-step="'. $step .'">
  <div class="input-prepend timepicker-bygiro-toggle" data-toggle="timepicker-bygiro">
    <span class="add-on"><i class="glyphicon icon-time"></i></span>
    '. $input .'
  </div>
  <div class="timepicker-bygiro-popover">
    <table class="table">
      <tbody>
        <tr>
          <td class="hour">
            <a class="next" href="#"><i class="icomoon icon-chevron-up"></i></a><br>
            <input type="text" class="input-mini" readonly><br>
            <a class="previous" href="#"><i class="icomoon icon-chevron-down"></i></a>
          </td>
          <td class="separator">:</td>
          <td class="minute">
            <a class="next" href="#"><i class="icomoon icon-chevron-up"></i></a><br>
            <input type="text" class="input-mini" readonly><br>
            <a class="previous" href="#"><i class="icomoon icon-chevron-down"></i></a>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>';
		
		return $html;
	}

}