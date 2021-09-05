<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Task definition for local_elggsync plugin.
 * @author    Davison Ramos <ramosdealmeidasistemas@gmail.com>
 * @package   local_elggsync
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$tasks = array(
    array(
        'classname' => '\local_elggsync\task\update_user_profile',
        'blocking' => 0,
        'minute' => '30',
        'hour' => '00',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
    ),
    array(
        'classname' => '\local_elggsync\task\update_user_avatar',
        'blocking' => 0,
        'minute' => '59',
        'hour' => '00',
        'day' => '*',
        'month' => '*',
        'dayofweek' => '*',
    )
);

