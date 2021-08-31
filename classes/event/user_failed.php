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
 * H5P viewed event class.
 *
 * @package    core
 * @since      Moodle 2.6
 * @copyright  2021  Davison Ramos de Almeida Junior <ramosdealmeidasistemas@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_elggsync\event;

defined('MOODLE_INTERNAL') || die();

/**
 * Elggsync user created event class.
 *
 * @package    elggsync_user_failed
 * @since      Moodle 3.8
 * @copyright  2019 Carlos Escobedo <carlos@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user_failed extends \core\event\base {
        /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('elgguserfailed','local_elggsync');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "Failed to create user with id '$this->objectid' on Elgg";
    }
    /**
     * Create instance of event.
     *
     * @since Moodle 2.6.4, 2.7.1
     *
     * @param int $userid id of user
     * @return user_created
     */
    public static function error_from_userid($userid) {
        $data = array(
            'objectid' => $userid,
        );
    
        // Create user_created event.
        $event = self::create($data);
        return $event;
    }

}