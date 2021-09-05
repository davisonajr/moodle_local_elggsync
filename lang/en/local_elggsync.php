<?php


/**
 * @package  Elgg-Moodle User Integration
 * @copyright 2021, Davison Ramos <ramosdealmeidasistemas@gmail.com>
 * @license MIT
 * @doc https://docs.moodle.org/dev/String_API
 */
 
defined('MOODLE_INTERNAL') || die();

$string['plugintitle'] = 'ElggSync plugin';
$string['elggadminname'] = 'Moodle-Elgg sync plugin';
$string['pluginname'] = 'ElggSync plugin';
$string['ellgapikey'] = 'Elgg API: Key';
$string['ellgapikeyhelper'] = 'Insert here the Elgg API key which will be useful to create users from Moodle to Elgg';
$string['elggpath'] = 'Elgg path';
$string['elggpathhelper'] = 'Insert the Elgg path inside your Moddle relative with Moddle core path. Don\'t forget the slash ("/") before the path, e.g. /elgg';
$string['elggusercreated'] = 'User created in Elgg';
$string['elgguserfailed'] = 'Failed to create user in Elgg';
$string['enableintegration'] = 'Enables integration from Moodle to Elgg';
$string['enableintegrationhelp'] = 'Enables integration for user creation from Moodle to Elgg';
$string['update_user_profile'] = 'Syncs profile fields from Elgg to Moodle';
$string['update_user_avatar'] = 'Brings avatar of users from Elgg to Moodle';
$string['moodleapikey'] = 'Moodle Webservice API key';
$string['moodleapikeyhelper'] = 'Insert here the Moodle Webservice API key which will be useful to integrate user profile fields from Elgg to Moodle';