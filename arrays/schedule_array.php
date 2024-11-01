<?php
/** 
 * Default schedule array for WTG Tasks Manager plugin 
 * 
 * @package WTG Tasks Manager
 * @author Ryan Bayne   
 * @since 0.0.1
 */

// load in WordPress only
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

$wtgtasksmanager_schedule_array = array();
// history
$wtgtasksmanager_schedule_array['history']['lastreturnreason'] = __( 'None', 'wtgtasksmanager' );
$wtgtasksmanager_schedule_array['history']['lasteventtime'] = time();
$wtgtasksmanager_schedule_array['history']['lasteventtype'] = __( 'None', 'wtgtasksmanager' );
$wtgtasksmanager_schedule_array['history']['day_lastreset'] = time();
$wtgtasksmanager_schedule_array['history']['hour_lastreset'] = time();
$wtgtasksmanager_schedule_array['history']['hourcounter'] = 1;
$wtgtasksmanager_schedule_array['history']['daycounter'] = 1;
$wtgtasksmanager_schedule_array['history']['lasteventaction'] = __( 'None', 'wtgtasksmanager' );
// times/days
$wtgtasksmanager_schedule_array['days']['monday'] = true;
$wtgtasksmanager_schedule_array['days']['tuesday'] = true;
$wtgtasksmanager_schedule_array['days']['wednesday'] = true;
$wtgtasksmanager_schedule_array['days']['thursday'] = true;
$wtgtasksmanager_schedule_array['days']['friday'] = true;
$wtgtasksmanager_schedule_array['days']['saturday'] = true;
$wtgtasksmanager_schedule_array['days']['sunday'] = true;
// times/hours
$wtgtasksmanager_schedule_array['hours'][0] = true;
$wtgtasksmanager_schedule_array['hours'][1] = true;
$wtgtasksmanager_schedule_array['hours'][2] = true;
$wtgtasksmanager_schedule_array['hours'][3] = true;
$wtgtasksmanager_schedule_array['hours'][4] = true;
$wtgtasksmanager_schedule_array['hours'][5] = true;
$wtgtasksmanager_schedule_array['hours'][6] = true;
$wtgtasksmanager_schedule_array['hours'][7] = true;
$wtgtasksmanager_schedule_array['hours'][8] = true;
$wtgtasksmanager_schedule_array['hours'][9] = true;
$wtgtasksmanager_schedule_array['hours'][10] = true;
$wtgtasksmanager_schedule_array['hours'][11] = true;
$wtgtasksmanager_schedule_array['hours'][12] = true;
$wtgtasksmanager_schedule_array['hours'][13] = true;
$wtgtasksmanager_schedule_array['hours'][14] = true;
$wtgtasksmanager_schedule_array['hours'][15] = true;
$wtgtasksmanager_schedule_array['hours'][16] = true;
$wtgtasksmanager_schedule_array['hours'][17] = true;
$wtgtasksmanager_schedule_array['hours'][18] = true;
$wtgtasksmanager_schedule_array['hours'][19] = true;
$wtgtasksmanager_schedule_array['hours'][20] = true;
$wtgtasksmanager_schedule_array['hours'][21] = true;
$wtgtasksmanager_schedule_array['hours'][22] = true;
$wtgtasksmanager_schedule_array['hours'][23] = true;
// limits
$wtgtasksmanager_schedule_array['limits']['hour'] = '1000';
$wtgtasksmanager_schedule_array['limits']['day'] = '5000';
$wtgtasksmanager_schedule_array['limits']['session'] = '300';
// event types (update event_action() if adding more eventtypes)
// deleteuserswaiting - this is the auto deletion of new users who have not yet activated their account 
$wtgtasksmanager_schedule_array['eventtypes']['deleteuserswaiting']['name'] = __( 'Delete Users Waiting', 'wtgtasksmanager' ); 
$wtgtasksmanager_schedule_array['eventtypes']['deleteuserswaiting']['switch'] = 'disabled';   
?>