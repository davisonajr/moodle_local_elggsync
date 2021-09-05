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

class update_user_profile extends \core\task\scheduled_task {
        /**
     * Return the task's name as shown in admin screens.
     *
     * @return string
     */
    public function get_name() {
        return get_string('update_user_profile', 'local_elggsync');
    }
    /**
     * Execute the task
     */
    public function execute() {
        require_once('../curl.php');
        global $DB,$CFG;
        $config = get_config('local_elggsync');
        $elggdomainname = $CFG->wwwroot.$config->elggpath;
        $elggmethod = 'get.user.info';
        $elgg_api_key = $config->elggapikey;
        $curl = new easycurl;

        $results = $DB->get_records_sql("SELECT u.id,u.username,u.city, u.description FROM {user} AS u WHERE u.suspended = 0");
        foreach($results as $result) {
            $url = $elggdomainname.'/services/api/rest/json/?method='.$elggmethod.'&api_key='.$elgg_api_key;
            $fetched = $curl->post($url,array('username'=>$result->username));
            $fetched = json_decode($fetched);
            if(isset($fetched) && $fetched->status == 0) {
                if(isset($fetched->result) && $fetched->result->success == true) {
                    $dataobject = new stdClass;
                    $dataobject->id = $result->id;
                    if($fetched->result->firstname || $fetched->result->firstname !== null) {
                        $dataobject->firstname =  $fetched->result->firstname;
                    }
                    if($fetched->result->lastname || $fetched->result->lastname !== null) {
                        $dataobject->lastname =  $fetched->result->lastname;
                    }
                    if($fetched->result->email || $fetched->result->email !== null) {
                        $dataobject->email =  $fetched->result->email;
                    }
                    if($fetched->result->phone || $fetched->result->phone !== null) {
                        $dataobject->phone1 =  $fetched->result->phone;
                    }
                    if($fetched->result->mobile || $fetched->result->mobile !== null) {
                        $dataobject->phone2 =  $fetched->result->mobile;
                    }
                    if($fetched->result->city || $fetched->result->city !== null) {
                        $dataobject->city =  $fetched->result->city;
                        $dataobject->country =  'BR';
                    }
                    if($fetched->result->description || $fetched->result->description !== null) {
                        $dataobject->description =  $fetched->result->description;
                    }
                    $DB->update_record('user',$dataobject);
                    unset($dataobject);
                    if($fetched->result->company || $fetched->result->company !== null) {
                        $companyfieldid =[];
                        try {
                            $companyfieldid  = $DB->get_record_sql("SELECT uid.id,uid.data
                                    FROM {user_info_data} AS uid
                                    JOIN {user_info_field} AS uif ON (uif.id = uid.fieldid)
                                    WHERE uid.userid = :userid
                                    AND uif.shortname = 'empresa'",array('userid'=>$result->id),MUST_EXIST);
                            if(strcmp($companyfieldid->data,$fetched->result->company) !== 0) {
                                $DB->update_record('user_info_data',array('id'=>$companyfieldid->id,'data'=>$fetched->result->company));
                            }
                        }
                        catch(Exception $e) {
                            echo $e;
                        }
                    }
                }
            }
        }
    }
}