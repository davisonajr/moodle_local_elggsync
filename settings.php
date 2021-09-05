<?php

/**
 * @package  Elgg-Moodle User Integration
 * @copyright 2021, Davison Ramos <ramosdealmeidasistemas@gmail.com>
 * @license MIT
 * @doc https://docs.moodle.org/dev/Admin_settings
 */
 
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
	// Create the new settings page
	// - in a local plugin this is not defined as standard, so normal $settings->methods will throw an error as
	// $settings will be NULL
	$settings = new admin_settingpage( 'local_elggsync',  get_string('elggadminname','local_elggsync'));

	
	$ADMIN->add( 'localplugins', $settings );
	// Add a setting field to the settings for this page
	$settings->add(new admin_setting_configcheckbox(
		'local_elggsync/enableintegration',
		get_string('enableintegration', 'local_elggsync'),
		get_string('enableintegrationhelp', 'local_elggsync'),
		1,
		1,
		0)
	);

	$settings->add( new admin_setting_configtext(
		
		// This is the reference you will use to your configuration
		'local_elggsync/elggpath',
	
		// This is the friendly title for the config, which will be displayed
		get_string('elggpath','local_elggsync'),
	
		// This is helper text for this config field
		get_string('elggpathhelper','local_elggsync'),
	
		// This is the default value
		'/networking',
	
		// This is the type of Parameter this config is
		PARAM_TEXT
		) 
	);

	$settings->add( new admin_setting_configtext(
		
		// This is the reference you will use to your configuration
		'local_elggsync/elggapikey',
	
		// This is the friendly title for the config, which will be displayed
		get_string('ellgapikey','local_elggsync'),
	
		// This is helper text for this config field
		get_string('ellgapikeyhelper','local_elggsync'),
	
		// This is the default value
		'89bc7be46b3ce6d596bd34477e6dba414b7bc786',
	
		// This is the type of Parameter this config is
		PARAM_TEXT
		) 
	);
	$settings->add( new admin_setting_configtext(
		
		// This is the reference you will use to your configuration
		'local_elggsync/moodleapikey',
	
		// This is the friendly title for the config, which will be displayed
		get_string('moodleapikey','local_elggsync'),
	
		// This is helper text for this config field
		get_string('moodleapikeyhelper','local_elggsync'),
	
		// This is the default value
		'',
	
		// This is the type of Parameter this config is
		PARAM_TEXT
		) 
	);
	// Create 

}
