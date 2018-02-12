<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/*
|--------------------------------------------------------------------------
| Account Status Codes
|--------------------------------------------------------------------------
|
*/

defined('SUPER_ADMIN') 		OR define('SUPER_ADMIN' , 'SUPER ADMIN');
defined('ADMIN') 			OR define('ADMIN' , 'ADMIN');
defined('MECHANIC') 		OR define('MECHANIC' , 'MECHANIC');
defined('CUSTOMER') 		OR define('CUSTOMER' , 'CUSTOMER');
defined('DRIVER') 			OR define('DRIVER' , 'DRIVER');
defined('OUTSOURCE') 		OR define('OUTSOURCE' , 'OUTSOURCE');
defined('WAREHOUSE') 		OR define('WAREHOUSE' , 'WAREHOUSE');


defined('GOOGLE_API_KEY') 			OR define('GOOGLE_API_KEY' , 'AIzaSyAI1IO2kYnhlEUcssfHN1xDLXhTi3rn3Mw');
defined('SERVER_TOKEN') 			OR define('SERVER_TOKEN' , 'e7b5c399efc50da24b6ffdae54fe3dfe');

defined('FIREBASE_SERVER_ID') 		OR define('FIREBASE_SERVER_ID' , 'AAAAyRfmcxs:APA91bE9a4b80flebIYZjgvEM4l7nQCYaDQeqqyO5ymhoSoXk2FIlQrnaD92XxLdHZgXV6zjI4ijjORip6uU_uiNUGVI-1wJdqOPw-6I2RHuScFywC2_68X8aWFcTUpOFYjWubju9mJu');
defined('FCM_PATH') 				OR define('FCM_PATH' , 'https://fcm.googleapis.com/fcm/send');


defined('APP_ID') 					OR define('APP_ID' , '358789');
defined('APP_KEY') 					OR define('APP_KEY' , '3aa3f56fbbaf16e66d97');
defined('APP_SECRET') 				OR define('APP_SECRET' , 'c16e010db39c874933c0');
defined('APP_CLUSTER') 				OR define('APP_CLUSTER' , 'ap1');

defined('DEFAULT_EMAIL') 			OR define('DEFAULT_EMAIL' , 'no-reply@trackerteer.com');

defined('LOCAL_URL') 				OR define('LOCAL_URL' , 'http://120.29.124.28:4571/codeigniter/welcome/generate');
defined('BACKUP_LOCAL_URL') 		OR define('BACKUP_LOCAL_URL' , 'http://www.mharbucad.com/generate_pdf/welcome/generate');


defined('GET_FACE_URL') 			OR define('GET_FACE_URL' , 'https://southeastasia.api.cognitive.microsoft.com/face/v1.0/detect');
defined('REMOVE_FACE_URL') 			OR define('REMOVE_FACE_URL' , 'https://southeastasia.api.cognitive.microsoft.com/face/v1.0/facelists/{faceListId}/persistedFaces/{persistedFaceId}');
defined('SIMILAR_URL') 				OR define('SIMILAR_URL' , 'https://southeastasia.api.cognitive.microsoft.com/face/v1.0/findsimilars');
defined('FACE_URL') 				OR define('FACE_URL' , 'https://southeastasia.api.cognitive.microsoft.com/face/v1.0/facelists/{faceListId}/persistedFaces');
defined('SUBSCRIPTION_KEY') 		OR define('SUBSCRIPTION_KEY' , '198261fe235145b1a03c87e2987dc291');
defined('GROUP_KEY') 				OR define('GROUP_KEY' , 'transport_app');
