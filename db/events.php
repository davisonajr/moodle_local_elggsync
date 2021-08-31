<?php

/**
 * @package  Elgg-Moodle User Integration
 * @copyright 2021, Davison Ramos <ramosdealmeidasistemas@gmail.com>
 * @license MIT
 * @doc https://docs.moodle.org/dev/Events_API
 */

// Event handlers (subscriptions) are defined here. It lists all the events that your plugin want to observe and be notified about.
 
defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname' => 'core\event\user_created',
        'callback' => 'local_elggsync\observer::create_user_in_elgg',
        'priority'    => 999,
    ),
);
