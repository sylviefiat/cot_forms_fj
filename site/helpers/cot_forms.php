<?php
/**
 * @version     2.0.4
 * @package     com_cot_forms
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Sylvie Fiat <sylvie.fiat@ird.fr>
 */

defined('_JEXEC') or die;

abstract class Cot_formsHelper
{
	public static function sendMail($data,$email_admin)
	{
		// Get JMail helper
		$mailer = JFactory::getMailer();		

		// Set the sender (from joomla configuration)	
		$config = JFactory::getConfig();
		$sender = array( 
		$config->get( 'mailfrom' ),
		$config->get( 'fromname' ) );
		$mailer->setSender($sender);

		// Set the recipient
		$mailer->addRecipient($email_admin);				

		$valid = "<a href='".JURI::base()."index.php/administrer-les-observations/".$data['id']."?view=cot_admin'>Visit site to validate report</a>";

		$body   = "<h4>New acanthaster report:</h4>"
				."<div>Observer: ".$data['observer_name']."</div>"
				.($data['observer_tel']!== ''?"<div>Phone: ".$data['observer_tel']."</div>":"")
				.($data['observer_email']!== ''?"<div>Mail: ".$data['observer_email']."</div>":"")
				."<div>Observation date: ".$data['observation_date']."</div>"
				."<div>Details observation localization: ".$data['observation_location']."</div>"
				."<div>Position: ".$data['observation_localisation']."</div>"
				.($data['observation_region']!== ''?"<div>Region: ".$data['observation_region']."</div>":"")
				.($data['observation_country']!== ''?"<div>Country: ".$data['observation_country']."</div>":"")
				.($data['observation_number']!== ''?"<div>Number of canthasters: ".$data['observation_number']."</div>":"")
				.($data['observation_culled']!== ''?"<div>Nomber of acanthsters culled: ".$data['observation_culled']."</div>":"")
				."<div>Observation method: ".implode( ',', $data['observation_method'])."</div>"
				.($data['depth_range']!== ''?"<div>Depth range: ".implode(", ",$data['depth_range'])."</div>":"")
				.($data['counting_method_timed_swim']!== ''&&$data['counting_method_distance_swim']!== ''&&$data['counting_method_other']!== ''?"<div>Counting method(s): </div>":"")
				.($data['counting_method_timed_swim']!== ''?"<div>timed swim: ".$data['counting_method_timed_swim']."</div>":"")
				.($data['counting_method_distance_swim']!== ''?"<div>distance swim: ".$data['counting_method_distance_swim']."</div>":"")
				.($data['counting_method_other']!== ''?"<div>other: ".$data['counting_method_other']."</div>":"")
				.($data['remarks']!== ''?"<div>Remarks: ".$data['remarks']."</div>":"")
				."<div>Observation validation: ".$valid." </div>";

		

		$mailer->setSubject("Oreanet FJ: new acanthaster report in Fidji");
		$mailer->setBody($body);
		$mailer->AltBody =JMailHelper::cleanText( strip_tags( $body));

		$mailer->isHTML(true);

		// Send email
		$send = $mailer->Send();
		if ( $send !== true ) {
		    return 'Error sending email: ' . $send->__toString();
		} else {
		    return 'Mail sent';
		}
	}

	

}

