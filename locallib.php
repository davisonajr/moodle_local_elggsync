<?php
/**
 * This function run the task to update user profiles
 * in Moodle with information of profiles in Elgg
 */
function update_user_profile() {
    global $DB,$CFG;
    require_once($CFG->dirroot . '/local/elggsync/classes/curl.php');
    $config = get_config('local_elggsync');
    $elggdomainname = $CFG->wwwroot.$config->elggpath;
    $elggmethod = 'get.user.info';
    $elgg_api_key = $config->elggapikey;
    $curl = new local_elggsync\easycurl;

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
    return true;
}
/**
 * This function run the task to update user avatars
 * in Moodle with information of profiles in Elgg
 */
function update_user_avatar() {

    global $DB,$CFG;
    require_once($CFG->dirroot . '/local/elggsync/classes/curl.php');
    $config = get_config('local_elggsync');
    $elggmethod = 'get.user.info';
    $moodlefunctionname = 'core_files_upload';
    $elgg_api_key = $config->elggapikey;
    $moodle_token = $config->moodleapikey;
    $serverurl = $CFG->wwwroot .'/webservice/rest/server.php' . '?wstoken=' . $moodle_token .'&wsfunction=' . $moodlefunctionname;
    $restformat = '&moodlewsrestformat=json';

    $curl = new local_elggsync\easycurl;

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
    return true;
}