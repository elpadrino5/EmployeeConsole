<?php

//file with global definitions
require 'config.php';

//handle page requests
ProcessPageRequest(); 

function AuthenticateUser($username, $password) 
{
    $response = array($username, $username, $username);
	//$response = LDAPauth($username, $password);
    //$response = NULL;
	if ($response != NULL)
	{
        //start the session
		session_start();
        //initialise session variables
		$_SESSION["username"] = $response[0]; 
		$_SESSION["displayName"] = $response[1]; 
		$_SESSION["firstName"] = $response[2];
        // echo $_SESSION["username"] . $_SESSION["displayName"]. $_SESSION["firstName"];

        $ipaddress = getenv("REMOTE_ADDR");
        $hostname = gethostbyaddr($ipaddress);
        $u = $_SESSION['username'];

        //output log to know who logged in from where
        //$update_log = openlog('user_info_login_log', LOG_PID, LOG_LOCAL0); 
        ////PrintSysLog("employee info admin console, $u logged in from $hostname ($ipaddress)");
        closelog();

		return true;
	}
	else
	{	
		return false;
	}	
}

function DisplayLoginPage($message="")
{
	require_once 'html/logon.html';
}

function ProcessPageRequest()
{
    // echo "session code: " . session_status() . "<br>";

	if(session_status() == PHP_SESSION_ACTIVE)
	{
		session_destroy();
	}

	if ($_POST == null)
	{
		DisplayLoginPage();
	}

	if (isset($_POST['action']))
	{
		if ($_POST['action'] == 'login')
		{		
            //store username input in lowercase	
			$username= strtolower($_POST["username_input"]);
            //store password input
			$password= $_POST["password_input"];

			if (authenticateUser($username, $password))
			{
                //go to home page after successful authentication 
				header("Location: index.php");                
			}
			else
			{
				DisplayLoginPage($message="<div id='login_message_container'>
                <p id='login_message'>The credetials you entered are incorrect. Please double-check and try again.</p></div>");
			}
		}
	}
}

?>