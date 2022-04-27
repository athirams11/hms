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
| Co-bank Constants
|--------------------------------------------------------------------------
|
| These constants are used in the co-bank project
|
*/
/****************** ADMIN DETAILS **** END *********************/	
/****************** ADMIN DETAILS **** END *********************/
defined('CO_COMPANY_NAME')    	OR define('CO_COMPANY_NAME', "WIKTA IT SEVICES"); // Loan approved status

/****************** Payment Constants **** END *********************/
defined('INSURANCE_ID_CONSTANT')    OR define('INSURANCE_ID_CONSTANT', 1); // Verify payment type in op registration

defined('ACTIVITY_LOG_PATH')        OR define('ACTIVITY_LOG_PATH', 'user_logs/logs/'); // no errorsSAVED LOCATION
defined('SOAP_LOG_PATH')        	OR define('SOAP_LOG_PATH', 'soap_logs/logs/'); // no errorsSAVED LOCATION

defined('CAT_DEF_ICON')        		OR define('CAT_DEF_ICON', 'public/assets/images/cat-ico.png'); // no errors
defined('COMP_DEF_ICON')        	OR define('COMP_DEF_ICON', 'public/assets/images/company-ico.png'); // no errors

defined('WEB_ADMINISTRATOR')        OR define('WEB_ADMINISTRATOR', 0); // no errors
defined('LOG_TYPE')        			OR define('LOG_TYPE', 2); // no LOG_TYPE 2 file, 1 database
defined('SOAP_LOG_TYPE')        	OR define('SOAP_LOG_TYPE', 1); // no LOG_TYPE 2 file, 1 database
/****************** FIREBASE DETAILS **** END *********************/



/****************** API DETAILS ****  *********************/
defined('API_KEY')        	OR define('API_KEY', "HMS-KEY"); 
defined('API_KEY_VALUE')        	OR define('API_KEY_VALUE', "5dd26ca2624be"); 
/****************** API DETAILS ****  *********************/

/****************** E-MAIL DETAILS **** END *********************/
defined('CUSTOMER_OTP_FROM_MAIL_ID')    OR define('CUSTOMER_OTP_FROM_MAIL_ID', 'wikta@wiktait.com');
defined('CUSTOMER_OTP_FROM_MAIL_NAME')  OR define('CUSTOMER_OTP_FROM_MAIL_NAME', 'No Reply');


defined('DOCUMENT_UPLOAD_MAX_SIZE')  OR define('DOCUMENT_UPLOAD_MAX_SIZE', '10000'); // 10000 in Mb
defined('DOCUMENT_UPLOAD_PATH')      OR define('DOCUMENT_UPLOAD_PATH', 'public/uploads/documents/'); // doc upload path
defined('SUBMISSION_FILE_PATH')      OR define('SUBMISSION_FILE_PATH', 'public/xml/submission/'); // doc upload path
defined('REMITTANCE_FILE_PATH')      OR define('REMITTANCE_FILE_PATH', 'public/xml/remittance/'); // doc upload path
defined('PRODUCTION_FILE_PATH')      OR define('PRODUCTION_FILE_PATH', 'public/xml/submission/production/'); // doc upload path
defined('LOGO_FILE_PATH')      OR define('LOGO_FILE_PATH', 'public/uploads/logo/'); // doc upload path
defined('CONSENT_FILE_PATH')      OR define('CONSENT_FILE_PATH', 'public/uploads/documents/consent/'); // doc upload path
defined('CLAIM_XML_SUBMISSION')     OR define('CLAIM_XML_SUBMISSION', 'submission'); // doc upload path
defined('CLAIM_CREATED')      		OR define('CLAIM_CREATED', '1'); // doc upload path
defined('CLAIM_SUBMITTED')      	OR define('CLAIM_SUBMITTED', '2'); // doc upload path
defined('CLAIM_DOWNLOADED')     	OR define('CLAIM_DOWNLOADED', '3'); // doc upload path
defined('CLAIM_DOWNLOADED')      	OR define('CLAIM_RESUBMITTED', '4'); // doc upload path

defined('CLAIM_SENDER_ID')      	OR define('CLAIM_SENDER_ID', 'DHA-F-0047990'); // doc upload path
defined('CLAIM_DISPOSITION_FLAG')   OR define('CLAIM_DISPOSITION_FLAG', 'TEST'); // doc upload path
defined('CLAIM_DISPOSITION_PRODUCTION_FLAG')   OR define('CLAIM_DISPOSITION_PRODUCTION_FLAG', 'PRODUCTION'); // doc upload path
defined('PROD_ENV')   OR define('PROD_ENV', 0); // 0-disabled,1-enabled

//SOAP CONFIG
defined('SOAP_USER')    		OR define('SOAP_USER', 'american heart'); // doc upload path
defined('SOAP_PASS')    		OR define('SOAP_PASS', 'ahcdubai1'); // doc upload path
defined('SOAP_HOST')    		OR define('SOAP_HOST', 'dhpo.eclaimlink.ae'); // doc upload path
defined('SOAP_URL')    			OR define('SOAP_URL', 'dhpo.eclaimlink.ae/ValidateTransactions.asmx'); // doc upload path

		

defined('MODULE_APPOINTMENT')        		OR define('MODULE_APPOINTMENT', 3); 
defined('MODULE_VISIT')        				OR define('MODULE_VISIT', 5); 
defined('MODULE_NURSING_ASESMENT')        	OR define('MODULE_NURSING_ASESMENT', 10); 
defined('MODULE_DOCTORS_ASESMENT')        	OR define('MODULE_DOCTORS_ASESMENT', 12); 
defined('MODULE_BILLING')        			OR define('MODULE_BILLING', 45);
defined('MODULE_CLAIM')        				OR define('MODULE_CLAIM', 46);

//timezone
defined('CONF_TIME_ZONE_TEXT')		OR define('CONF_TIME_ZONE_TEXT','Asia/Dubai');

//erxPrescription
defined('SENDER_ID')      	OR define('SENDER_ID', 'DHA-F-0047990'); // doc upload path
defined('RECEIVER_ID')      	OR define('RECEIVER_ID', 'DHA-F-0047990'); // doc upload path
defined('DISPOSITION_FLAG')   OR define('DISPOSITION_FLAG', 'TEST'); // doc upload path 
defined('ERX_DISPOSITION_PRODUCTION_FLAG')   OR define('ERX_DISPOSITION_PRODUCTION_FLAG', 'PRODUCTION'); // doc 
defined('ERX_REQUEST')   OR define('ERX_REQUEST', 'eRxRequest'); // doc 
defined('ERX_USER')    		OR define('ERX_USER', 'GOPI'); // doc upload path
defined('ERX_PASS')    		OR define('ERX_PASS', 'modernfc@2017'); // doc upload path
defined('ERX_CLINICIAN_USER')    		OR define('ERX_CLINICIAN_USER', 'rtonyshtnme8064'); // doc upload path
defined('ERX_CLINICIAN_PASS')    		OR define('ERX_CLINICIAN_PASS', 'Tony@9847148717'); // doc upload path
defined('ERX_URL')    			OR define('ERX_URL', 'dhpo.eclaimlink.ae/eRxValidateTransaction.asmx'); // doc upload 
defined('EXR_REQUEST_FILE_PATH')      OR define('EXR_REQUEST_FILE_PATH', 'public/xml/erxRequest/'); // doc upload path
defined('OP_ATTACHMENT')      OR define('OP_ATTACHMENT', 'public/uploads/op_attachment/'); // doc upload path

defined('OP_NUMBER_PREFIX')      OR define('OP_NUMBER_PREFIX', 'OP'); // op registration number prefix
defined('ERX_REQUEST_CANCELLATION')  OR define('ERX_REQUEST_CANCELLATION', 'eRxCancellation');
defined('MR_NO_PREFIX')      OR define('MR_NO_PREFIX', 'MR0-');// op registration number prefix
defined('LABRESULT_UPLOAD_PATH')      OR define('LABRESULT_UPLOAD_PATH', 'public/uploads/labresult/');
