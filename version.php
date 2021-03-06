<?php

/**
 * @package  Elgg-Moodle User Integration
 * @copyright 2021, Davison Ramos <ramosdealmeidasistemas@gmail.com>
 * @license MIT
 * @doc https://docs.moodle.org/dev/version.php
 */

defined('MOODLE_INTERNAL') || die();

$plugin->component = 'local_elggsync'; // Declare the type and name of this plugin.
$plugin->version = 2021092317; // Plugin released on 22th September 2021.
$plugin->requires = 2014051200; // Moodle 2.7.0 is required.
$plugin->maturity = MATURITY_ALPHA; // This is considered as ALPHA for production sites.
$plugin->release = '0.1.0'; // This is our first.
