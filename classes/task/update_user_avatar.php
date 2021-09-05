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

namespace local_elggsync\task;

class update_user_avatar extends \core\task\scheduled_task {
        /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return get_string('update_user_avatar', 'local_elggsync');
    }
    /**
     * Execute the task
     */
    public function execute() {

        require_once('../curl.php');
        global $DB,$CFG;
        $config = get_config('local_elggsync');
        $elggmethod = 'get.user.info';
        $moodlefunctionname = 'core_files_upload';
        $elgg_api_key = $config->elggapikey;
        $moodle_token = $config->moodleapikey;
        $serverurl = $CFG->wwwroot .'/webservice/rest/server.php' . '?wstoken=' . $moodle_token .'&wsfunction=' . $moodlefunctionname;
        $restformat = '&moodlewsrestformat=json';

        $curl = new easycurl;

        $results = $DB->get_records_sql("SELECT u.id,u.username,u.city, u.description FROM {user} AS u WHERE u.suspended = 0");
        foreach($results as $result) {
            $url = $CFG->wwwroot.$config->elggpath.'/services/api/rest/json/?method='.$elggmethod.'&api_key='.$elgg_api_key;
            $fetched = $curl->post($url,array('username'=>$result->username));
            $fetched = json_decode($fetched);
            if(isset($fetched) && $fetched->status == 0) {
                if(isset($fetched->result) && $fetched->result->success == true) {
                    $avatar_url = (string) $fetched->result->url;
                    if(strpos($avatar_url,'/user/default/') !== false) {
                        continue;
                    }
                    else {
                        $tmp = explode('/',$avatar_url);
                        $imagename = end($tmp);
                        unset($tmp);
                        $params = array(
                            'component' => 'user',
                            'filearea' => 'draft',
                            'itemid' => 0,
                            'filepath' => '/',
                            'filename' => $result->id.'_'.$imagename,
                            'filecontent' => base64_encode(file_get_contents($avatar_url)),
                            'contextlevel' => 'user',
                            'instanceid' => 2,
                        );
                        $upload_file = $curl->post($serverurl . $restformat, $params);
                        $upload_file = json_decode($upload_file);
                        if(isset($upload_file->itemid)) {
                            $update_picture = $curl->post($CFG->wwwroot .'/webservice/rest/server.php' . '?wstoken=' . $moodle_token .'&wsfunction=core_user_update_picture' . $restformat, array('draftitemid'=>$upload_file->itemid,'userid'=>$result->id));
                        }
                    }
                }
            } 
        }
    }
}