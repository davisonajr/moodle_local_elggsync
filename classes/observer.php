<?php

/**
 * @package  Elgg-Moodle User Integration
 * @copyright 2021, Davison Ramos <ramosdealmeidasistemas@gmail.com>
 * @license MIT
 * @doc https://docs.moodle.org/dev/Event_2
 */

namespace local_elggsync;

defined('MOODLE_INTERNAL') || die();

class observer {
    public static function create_user_in_elgg(\core\event\user_created $event) {
        global $DB,$CFG;
        $config = get_config('local_elggsync');
<<<<<<< HEAD
=======

>>>>>>> 8869cca77e705f9deeafaeb796ccfdb8bdfe4a74
        if($config->enableintegration == 1){
            require_once('curl.php');

            // get user created info
            $user = $DB->get_record('user', array('id' => $event->objectid));

            // prepares REST API for user creation
            $domainname = $CFG->wwwroot.$config->elggpath;
            $restformat = 'json';
            $restmethod = 'create.user';
            $elgg_api_key = $config->elggapikey;
            $url = $domainname.'/services/api/rest/'.$restformat.'/?method='.$restmethod.'&api_key='.$elgg_api_key;
            $curl = new easycurl;

            // Call REST API for user creation
            $add = $curl->post($url,array('username'=>$user->username,'password'=>'@Senha123','name'=>$user->firstname.' '.$user->lastname,'email'=>$user->email));
                $add = json_decode($add);
                if(isset($add) && $add->status == 0) {
                    if(isset($add->result) && $add->result->success == true) {
                        $newevent = \local_elggsync\event\user_created::create(array(
                            'objectid' => $user->id));
                        $newevent->trigger(); 
                    }
                    else {
                        $newevent = \local_elggsync\event\user_failed::create(array(
                            'objectid' => $user->id));
                        $newevent->trigger(); 
                    }
                }
                else {
                    $newevent = \local_elggsync\event\user_failed::create(array(
                        'objectid' => $user->id));
                    $newevent->trigger(); 
                }
            die();
            }
    }
}