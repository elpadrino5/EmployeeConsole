<?php

//start the session
session_start();

//file with global definitions
require 'config.php';

//check that user is logged in
Authenticate();

if ($_SERVER["REQUEST_METHOD"] == "GET") 
{	
	if (isset($_REQUEST["k"]))
	{
		$key = $_REQUEST['k'];
		$value = $_REQUEST['v'];
		$user = $_REQUEST['u'];

		// DisplayInfo($user, $filename);
		// echo "$value - ";
		//get user info from file
		$line = GetUser($user, $filename);	
		
		switch ($key)
        {
            case 'accountName':
                echo "$line[0]";
                break;
            case 'name':
                echo "$line[1]";
                break;
            case 'title':
                echo "$line[2]";
                break;
            case 'department':
                echo "$line[3]";
                break;
            case 'company':
                echo "$line[4]";
                break;
            case 'emailAddress':
                echo "$line[5]";
                break;
            case 'officePhone':
                echo "$line[6]";
                break;
            case 'mobile':
                echo "$line[7]";
                break;
            case 'mgrAcntName':
                echo "$line[8]";
                break;
            case 'manager':
                echo "$line[9]";
                break;
            case 'office':
                echo "$line[10]";
                break;
            case 'streetAddress':
                echo "$line[11]";
                break;
            case 'postalCode':
                echo "$line[12]";
                break;
            case 'active':
                echo "$line[13]";
                break;
			case 'lastUpdate':
				echo "$line[14]";
				break;
			case 'lastEditedBy':
				echo "$line[15]";
				break;
		}
		die();
	}

	if (isset($_REQUEST['q']))
	{
		echo $_SESSION['search_str'];
	}
}

// echo $_SESSION['search_str'];

require_once 'validateForm.php';

$user = $_GET['username'];
// echo $_GET['username'] . "<br>". $_GET['column2'] . "<br>";

//handle page requests
ProcessPageRequest($user,$filename); 

//display user information
function DisplayInfo($user, $filename)
{
	require_once 'data_functions.php';

	//declare error variables
	$anerror = $nerror = $terror = $derror = $cerror = $eaerror = $operror = $merror = $manerror = $maerror = $oerror = 
	$saerror = $pcerror = $aerror = $luerror = $leberror = '';

	//get user info from file
	$line = GetUser($user, $filename);	
       
	//map the row to variables
	$AccountName   = $line[0];
	$Name          = $line[1];
	$Title         = $line[2];
	$Department    = $line[3];
	$Company       = $line[4];
	$EmailAddress  = $line[5];
	$OfficePhone   = $line[6];
	$Mobile        = $line[7];
	$MgrAcntName   = $line[8];
	$Manager       = $line[9];
	$Office        = $line[10];
	$StreetAddress = $line[11];
	$PostalCode    = $line[12];
	$Active        = $line[13];
	$LastUpdate    = $line[14];
	$LastEditedBy  = $line[15];

	//output the html form
	require 'html/EditUserInfo.html';	
}

//display form again but with errors
function DisplayErrors($fields, $errFields, $user, $filename)
{
	//declare error variables
	$anerror = $nerror = $terror = $derror = $cerror = $eaerror = $operror = $merror = $manerror = $maerror = $oerror = 
	$saerror = $pcerror = $aerror = $luerror = $leberror = '';

	$line = GetUser($user, $filename);

	//set the array containing user info to the modified info contained in the $fields array
	$line = array($fields['accountName'], $fields['name'], $fields['title'], $fields['department'], $fields['company'], $fields['emailAddress'],
	$fields['officePhone'], $fields['mobile'], $fields['mgrAcntName'], $fields['manager'], $fields['office'], $fields['streetAddress'],
	$fields['postalCode'], $fields['active'], $fields['lastUpdate'], $fields['lastEditedBy']);
	
	$exclamation_sign = "<span class='glyphicon glyphicon-exclamation-sign'></span>";
	//procced if there is errors
	if ($errFields !== null)
	{
		foreach ($errFields as $err) 
		{
			//display error corresponding with given column name
			switch ($err) 
			{
				case 'accountName':
					$anerror = $exclamation_sign . "Warning: Username format is invalid!";;
					break;
				case 'name':
					$nerror = $exclamation_sign . "Full Name format is invalid!";;
					break;
				case 'title':
					$terror = $exclamation_sign . "Title format is invalid!";
					break;
				case 'department':
					$derror = $exclamation_sign . "Department format is invalid!";;
					break;
				case 'company':
					$cerror = $exclamation_sign . "Company format is invalid!";;
					break;
				case 'emailAddress':
					$eaerror = $exclamation_sign . "Warning: Email Address format is invalid!";;
					break;
				case 'officePhone':
					$operror = $exclamation_sign . "Office phone format must be '(xxx) xxx-xxxx' where 'x' is a number.";
					break;
				case 'mobile':
					$merror = $exclamation_sign . "Mobile format must be '(xxx) xxx-xxxx' where 'x' is a number.";
					break;
				case 'mgrAcntName':
					$manerror = $exclamation_sign . "Warning: Manager Username format is invalid!";;
					break;
				case 'manager':
					$maerror = $exclamation_sign . "Manager format is invalid!";;
					break;
				case 'office':
					$oerror = $exclamation_sign . "Office format is invalid!";;
					break;
				case 'streetAddress':
					$saerror = $exclamation_sign . "Street Address format is invalid!";;
					break;
				case 'postalCode':
					$pcerror = $exclamation_sign . "Postal Code format is invalid!";;
					break;
				case 'active':
					$aerror = $exclamation_sign . "Active format is invalid!";;
					break;
			}
		}
	}
	require 'html/EditUserInfo.html';
}

//manage request, post, get, etc 
function ProcessPageRequest($user,$filename)
{
	//declare array containing all user info
	$userInfo = []; 
	//gather all info
	$userInfo = GetInfo($filename);
	//initialize array of error fields names
	$errFields = [];
	//initialize array of field code
	$field_code = [];
	//array to store modified info
	$fields = [];
	//current info
	$line = GetUser($user, $filename);

	require_once 'html/navbar2.html';

	//nothing has been sent to the server
	if ($_POST == null)
	{
		DisplayInfo($user, $filename);
	}
	//data was sent to the server using post method
	else if ($_SERVER["REQUEST_METHOD"] == "POST") 
	{
		if ($_POST['action'] == 'edit')
		{
			//assign input from form to vars
			$accountName = trim($_POST["accountName"]); //readonly
			$name = trim($_POST["name"]);
			$title = trim($_POST["title"]);
			$department = trim($_POST["department"]); //select
			$company = trim($_POST["company"]); //select
			$emailAddress = trim($_POST["emailAddress"]); //readonly
			$officePhone = trim($_POST["officePhone"]); 
			$mobile = trim($_POST["mobile"]);
			$mgrAcntName = trim($_POST["mgrAcntName"]); //select
			$manager = trim($_POST["manager"]); //readonly
			$office = trim($_POST["office"]); //select
			$streetAddress = trim($_POST["streetAddress"]); //select
			$postalCode = trim($_POST["postalCode"]); //readonly
			$active = trim($_POST["active"]); //select
			$lastUpdate = $line[14]; //gets value from current data not the front end
			$lastEditedBy = $line[15]; //gets value from current data not the front end

			//array of fields that will be validated after submittion
 			$fields = array(	
				"accountName" => $accountName,		
				"name" => $name,
				"title" => $title,
				"department" => $department,
				"company" => $company,
				"emailAddress" => $emailAddress,
				"officePhone" => $officePhone,
				"mobile" => $mobile,
				"mgrAcntName" => $mgrAcntName,
				"manager" => $manager,
				"office" => $office,
				"streetAddress" => $streetAddress,
				"postalCode" => $postalCode,
				"active" => $active,
				"lastUpdate" => $lastUpdate,
				"lastEditedBy" => $lastEditedBy,
			);

			//sanitize input
			foreach ($fields as $key => $value)
			{
				$value = Sanitize($key, $value);
			}

			//validate input
			foreach ($fields as $key => $value)
			{
				// send column name and value to regex validation and save output
				$ret = ValidateRegex($key, $value);
				// echo "ret: .$ret[].<br>";
				//if return is not null then it returned the field names that failed the validation 
				if ($ret !== null)
				{
					//save the field names to array
					$errFields[] = $ret[0];
					$field_code[] = $ret[1];
				}
			}

			//var_dump($errFields);
			//var_dump($fields);
			//check if there are fields with the error code
			$error = in_array(0, $field_code);
			if (!(empty($errFields)) && $error)
			{
				//display errors next to the form fields
				DisplayErrors($fields, $errFields, $user, $filename);
				//exit this file and do nothing else
				die();
			} 

			//at this point validation is done
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//compare user values vs current values
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			//array of keys where key correspond to position
			$key = array("accountName",	"name",	"title", "department", "company", "emailAddress", "officePhone", "mobile", "mgrAcntName", "manager",
			"office", "streetAddress", "postalCode", "active", "lastUpdate", "lastEditedBy");

			//variable to indicate at least one change was made
			$isUpdated = 0;

			
			global $edit_page_update_log_name;
			//open file for writing 
			//openlog($edit_page_update_log_name, LOG_PID | LOG_PERROR, LOG_LOCAL0);

			//compare user values vs current values
			for ($i=0; $i < 14; $i++)
			{
				//skip fields 0 and 5 because they will not change anyways
				if ($i == 0 || $i == 5) { continue; }
				
				if ($line[$i] != $fields[$key[$i]])
				{
					//store updated value on var
					$updatedVal = $fields[$key[$i]];
					$isUpdated = 1;	
					
					//set var to true because info will be updated
					$_SESSION['newchanges'] = 'true';
					$u = $_SESSION['username'];

					////PrintSysLog("employee info admin console, $line[0], $key[$i] from '$line[$i]' to '$updatedVal', $u");
					//write detected changes to file
					PrintUpdateLog("$line[0] => $key[$i]: from '$line[$i]' to '$updatedVal'\n");		
				}
			}
			//closelog();

			//if there was at least one change set the 'lastUpdate' and 'lastEditedBy' fields
			if ($isUpdated != 0)
			{
				//variables that must be declared after validation
				$lastUpdate = trim(date("m/d/Y h:i:s A"));
				$lastEditedBy = $_SESSION["username"];
			}
			// echo "$accountName,$name,$title,$department,$company,$emailAddress,$officePhone,$mobile,$mgrAcntName,$manager,$office,$streetAddress,
			// $postalCode,$active,$lastUpdate,$lastEditedBy\n\n";		
			
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//Replace row in current data table with user input values
			//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			//initialize line
			$new_line = [];

			//save user values into array
			$new_line[0] = $line[0];
			$new_line[1] = $name;
			$new_line[2] = $title;
			$new_line[3] = $department;
			$new_line[4] = $company;
			$new_line[5] = $line[5];
			$new_line[6] = $officePhone;
			$new_line[7] = $mobile;
			$new_line[8] = $mgrAcntName;
			$new_line[9] = $manager;  
			$new_line[10] = $office;
			$new_line[11] = $streetAddress;
			$new_line[12] = $postalCode;
			$new_line[13] = $active;
			$new_line[14] = $lastUpdate;
			$new_line[15] = $lastEditedBy;			
			//var_dump($line);

			//replace the row in the array;
			//get row number of user 
			$i = GetRowNumber($user, $filename);
			//update the row
			$userInfo[$i] = $new_line;
			
			//if data table is not empty
			if (($userInfo !== null) || ($userInfo !== ""))
			{
				//update the data table with update info
				UpdateInfo($userInfo, $filename);
				//so you don't get search message
				$_SESSION['flag'] = 'true';
				//redirect back to home page
				header("Location:index.php?searchbtn=&search_action=search&textfield=" . $_SESSION['search_str']);
			}
			else
			{
				echo "Unable to update $filename because it's empty!";
			}
		}
		else
		{
			echo "Unable to update $filename. Failed to process data!";
		}
	}
	else
	{
		echo "Unable to save changes. Something went wrong!";
	}
}
								


?>