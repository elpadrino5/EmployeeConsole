<?php
    //display errors
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    //global variables
    $filename = 'data/UserInfo.csv'; //file that contains all user info and gets displayed in table format
    $fields_count = 16; //total amount of columns the file contains
    $suppress_log = 0; //set to 1 to disable priting logs to system
    $email_notification_address = "test@hotmail.com";    
    $uncommitted_updates_path = 'data/compare_values.log';
    $validate_file = 'data/validate_file.log';
    $upload_update_log_name = 'upload update'; //title of log, might be used to as search string
    $edit_page_update_log_name = 'edit page update'; //title of log, might be used to as search string

    //arguments for function that executes a command after apply (proc_open)
    $command_after_apply = "./scripts/test.sh";
    $cwd = getcwd(); //the initial working dir for the command
    $env = null; //environment variables
    //descriptor array for command
    $descriptorspec = array(
        0 => array("pipe", "r"),  // stdin
        1 => array("pipe", "w"),  // stdout
        2 => array("pipe", "w"),  // stderr
        );

    //initialize session variables
    $_SESSION['newchanges'] = 'false';

    //include code
    require_once 'data_functions.php'; //contains functions to search and manipulate data
?>
