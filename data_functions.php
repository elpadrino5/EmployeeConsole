<?php

require_once 'config.php';

//get all the current user info
function GetInfo($filename)
{
	//declare array containing all user info
	$userInfo = []; 

	//open csv file for reading
	if (($h = fopen("{$filename}", "r")) !== FALSE) 
	{
		//go through each line 
		while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
		{
            $userInfo[] = $data;
		}
		fclose($h);
		return $userInfo;
	}
	else
	{
		echo "<p class='bodytext'>Unable to open '$filename' for reading!</p>";
		return null;
	}
}

//get row of info and return it
function GetUser($user, $filename)
{
	$userInfo = GetInfo($filename);
	foreach($userInfo as $line)
    {	
        //only display form if user is found
        if($user == $line[0])
        {
			return $line;
		}
	}
	return null;	
}

//get row number where input user is located
function GetRowNumber($user, $filename)
{
	$userInfo = GetInfo($filename);
	$i = 0;
	foreach($userInfo as $line)
    {	
        //only display form if user is found
        if($user == $line[0])
        {
			return $i;
		}
		$i++;
	}
	return null;	
}

function Search($filename, $string)
{
    $returnData = [];

    //clean up input
    $string = trim($string);
    //replace all spaces with single empty space
    $string = preg_replace('/\s\s+/', ' ', $string);
    //echo "cleaned: '$string'";

    //see that string is splited by single space
    if (stripos($string, ' ') != false)
    {
        //convert string to array
        $stringArr = explode(' ', $string);
        $arrSize = count($stringArr);
    }
    else
    {
        $stringArr = $string;
    }    

    //get all the info
    $data = GetInfo($filename);

    foreach($data as $line)
    {
        //convert row of type array to string
        $haystack = implode(' ', $line);
        //echo "<br> '$haystack' <br>";
        
        $type = gettype($stringArr);
        //echo "$type <br>";

        //default to matching data in each row
        $foundPattern = 1;

        if ($type == "string")
        {
            $needle = $stringArr;
            //see if haystack contains the needle
            if (stripos($haystack,$needle) === false)
            {
                //needle not found so do not include this row to final output
                $foundPattern = 0;
                //echo "not found => h:'$haystack' n:'$needle' <br>";              
            }
            else
            {
                //echo "fOUND => h:'$haystack' n:'$needle'<br>";
            }
        }
        elseif($type == "array")
        {
            //iterate over each word in string
            for ($i = 0; $i < $arrSize; $i++)
            {
                $needle = $stringArr[$i];
                //see if haystack contains the needle
                if (stripos($haystack, $needle) === false)
                {
                    //needle not found so do not include this row to final output
                    $foundPattern = 0;
                    //exit inner loop because every input must be found in haystack
                    continue;                
                }
            }
        }
        else
        {
            echo "<p class='bodytext'>Wrong input!</p><br>";
        }

        if($foundPattern == 1)
        {
            $returnData[] = $line;
        }
    }   
    return $returnData;
}

function UpdateInfo($userInfo, $filename)
{
	if (($userInfo == null) || ($userInfo == ""))
	{
		return;
	}

	if (($h = fopen("{$filename}", "w")) !== FALSE) 
	{
		foreach($userInfo as $newrow)
		{
			//writes everything in array to csv file. writes row by row.
			fputcsv($h, $newrow);
		}
		fclose($h);		
	}
	else
	{
		echo "<p class='bodytext'>Unable to open '$filename' for writing!</p>";
	}		
}

function ValidateTable($file, $column_num)
{
    $colnum = count(current($file));
    // echo $colnum;

    if ($colnum != $column_num)
    {
        echo "<p class='bodytext'>Error! The file does not have the right amount of columns</p>";
        die();
    }
    
    $warning_cnt = 0;
    $error_cnt = 0; //count for failed validations
    $invalid = 0; //flag indicating at least one failed validation
    $j = 0; //index for each row
    global $validate_file;
    $validation_log = fopen($validate_file, 'w'); //open log file for writing
    $field_names = array();

    //iterate through rows to perform validation in every cell value    
    foreach($file as $row)
    {        
        //skip whitelisted users
        // global $whitelisted_users;
        // if (in_array($row[0], $whitelisted_users)){ continue; }

        //iterate through columns
        for($i = 0; $i < $colnum; $i++)
        {
            if ($j == 0) //headers row
            {                
                $lwr_value = strtolower(substr($row[$i], 0, 1)) . substr($row[$i], 1);
                array_push($field_names, $lwr_value);
                continue;
            }

            //validate value depending on field name
            // echo "$i $field_names[$i], $row[$i]<br>";
            $ret = ValidateRegex($field_names[$i], $row[$i]);
            //if validation failed
            if ($ret != null)
            {
                if ($ret[1] == 0)
                {
                    //print to browser
                    echo "<p class='bodytext'>Error! Invalid format in row $j => user: '$row[0]' | field: '$ret[0]' | value: '$row[$i]'</p>";
                    fwrite($validation_log, "Error! Invalid format in row $j => user: '$row[0]' | field: '$ret[0]' | value: '$row[$i]'\n"); //write to log file   
                    $error_cnt++;
                }
                if ($ret[1] == 1)
                {
                    //print to browser
                    echo "<p class='bodytext'>Warning! Invalid format in row $j => user: '$row[0]' | field: '$ret[0]' | value: '$row[$i]'</p>";
                    fwrite($validation_log, "Warning! Invalid format in row $j => user: '$row[0]' | field: '$ret[0]' | value: '$row[$i]'\n"); //write to log file   
                    $warning_cnt++;
                }                
                $invalid = 1;
            }            
        }        
        $j++;
    }
    fclose($validation_log);

    if ($invalid != 0)
    {
        echo "<p class='bodytext'>Warnings: $warning_cnt<br>Errors: $error_cnt</p>";
        die();
    }
    else
    {
        echo "<p class='bodytext'>All the data passed validation!</p>";
    }
}

function GetManager($acntName, $info)
{           
    //loop through info
    foreach ($info as $line)
    {
        //compare input data to first column
        if ($line[0] === $acntName)
        {
            //variable for second column of table (fullname)
            $mgr_name = $line[1];
            //output the full name corresponding to the input account name
            return $mgr_name;
        }
    }
}

function Authenticate()
{    
    if (isset($_SESSION['username']))
    {
        return true;
    }
    else
    {
        session_unset();
        session_destroy();
        header("Location: logon.php"); 
        return false;
    }
}

function ProccessUpload($uploaded_file)
{
    global $filename;
    
    $uploadedInfo = GetInfo($uploaded_file);
    // $d = var_dump($uploadedInfo); echo $d;
    $currentInfo = GetInfo($filename);
    // $e = var_dump($currentInfo); echo $e;
    
    //perform validation on uploaded csv. pass the uploaded file info and number of columns it's supposed to have
    //ValidateTable($uploadedInfo, 12);

    //array of field names of current info in order. Each field name corresponds with index position.
    $field_names = array("accountName", "name", "title", "department", "company", "emailAddress", "officePhone", "mobile",
    "mgrAcntName", "manager", "office", "streetAddress", "postalCode", "active");

    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    //Compare tables and write all updates to user info file
    /////////////////////////////////////////////////////////////////////////////////////////////////////////

    $rows_checked = 0; //count for rows that match username
    $rows_affected = 0;
    $rn = 0 ; //row number
    $change_cnt = 0; //count for how many changes were made
    $colnum_cur = 16;
    $hostname = gethostname();
    $ipaddress = getenv("REMOTE_ADDR");
    $u =  $_SESSION['username'];
    
    //clear button for upload changes message
    echo"<button type='button' id='clear-changes-button' title='clear message' onclick='ClearChangesMessage()'>Clear</button>";

    global $upload_update_log_name;
    //open the log for printing to syslog
    //openlog($upload_update_log_name, LOG_PID | LOG_PERROR, LOG_LOCAL0);

    $j = 0;
    $rows_notmatched = '';
    foreach($currentInfo as $cur_row)
    {
        //skip header row
        if ( $j == 0)
        {
            $j++;
            continue;                                
        }     

        $found_match = 0; //indicates that usernames matched in both files
        $uname_curInfo = $cur_row[0]; //username in current info
        foreach($uploadedInfo as $upl_row)
        {          
            $uname_uplInfo = $upl_row[0]; //username in uploaded info
            //check if usernames match to change row data
            if ($uname_curInfo === $uname_uplInfo)
            {
                $found_match = 1;
                $rows_checked++; //increment count
                //iterate through every field except the first
                $change_cnt_bfr_loop = $change_cnt;

                $k = 1; //skip accountName
                for($i = 1; $i < 14; $i++)
                {
                    if($i == 5 || $i == 9)
                    {
                        continue;
                    }

                    if ($cur_row[$i] !== $upl_row[$k])
                    {
                        $change_cnt++; //increment the amount of changes made 
                        if ($change_cnt == 1) { echo "<p class='bodytext'>The following changes were made from upload file: </p><br>"; }

                        //set var because new changes will be made
                        $_SESSION['newchanges'] = 'true';

                        $temp = $currentInfo[$j][$i]; //save current
                        $currentInfo[$j][$i] = $upl_row[$k]; //change current value
                        //message of data update
                        echo "<p class='bodytext'>$uname_uplInfo => $field_names[$i]: from '$temp' to '$upl_row[$k]'";
                        $u =  $_SESSION['username'];
                        //PrintSysLog("employee info admin console, $uname_uplInfo, $field_names[$i] from '$temp' to '$upl_row[$k]', $u"); //write to log file 
                        PrintUpdateLog("$uname_uplInfo => $field_names[$i]: from '$temp' to '$upl_row[$k]'\n");

                        //when we reach the manager field we must change the manager acnt name field         
                        if ($i == 8)
                        {
                            $change_cnt++; //increment the amount of changes made 
                            $mgr_name = GetManager($upl_row[$k], $uploadedInfo); //get manager name from uploaded info
                            $temp = $currentInfo[$j][9] ; //save current manager
                            $currentInfo[$j][9] = $mgr_name; //change current value 
                            echo "<p class='bodytext'>$uname_uplInfo => $field_names[9]: from '$temp' to '$mgr_name'</p>";                        
                            //PrintSysLog("employee info admin console, $uname_uplInfo, $field_names[9] from '$temp' to '$mgr_name', $u"); //write to log file 
                            PrintUpdateLog("$uname_uplInfo => $field_names[9]: from '$temp' to '$mgr_name'\n");
                        }                                                       
                    }
                    $k++;
                }

                //do if there was a change in the row
                if ($change_cnt_bfr_loop < $change_cnt)
                {
                    //increment num of rows affected
                    $rows_affected++;
                    //change LastUpdated column
                    $currentInfo[$j][14] = trim(date("m/d/Y h:i:s A"));
                    //change LastEditedBy column
                    $currentInfo[$j][15] = $_SESSION["username"];
                }
                break;
            }
        }
        if ($found_match == 0)
        {
            $u = strval($uname_curInfo);
            $rows_notmatched .= " $u,";
        }
        $rn++;
        $j++;
    }                        
    
    // var_dump($currentInfo);
    //write all updates to user info file
    UpdateInfo($currentInfo, $filename);

    if ($change_cnt == 0)
    {
        echo "<p class='bodytext'>No changes were made from upload file!</p><br>";
    }
    else //display and logs stats 
    {
        echo "<br><br><p class='bodytext'>Rows Checked: $rows_checked</p>";
        echo "<p class='bodytext'>Rows Affected: $rows_affected</p>";
        echo "<p class='bodytext'>Updates: $change_cnt</p>";  
        //PrintSysLog("employee info admin console, rows checked: $rows_checked, rows affected: $rows_affected, updates: $change_cnt, $u"); //write to log file                           
    }

    //if one or more user rows were not found in uploaded file display unmatched usernames
    if ($rows_notmatched !== '')
    {
        echo "<p class='bodytext'>The following users were not found in upload file: '$rows_notmatched'</p><br>";
    }

    //close log file
    closelog();
}

function PrintSysLog($message)
{
    global $suppress_log;

    //only print if var to suppress log is not activated
    if ($suppress_log != 1)
    {
        //prints message to syslog
        syslog(LOG_INFO, $message);
    }
}

function PrintUpdateLog($message)
{
    global $uncommitted_updates_path;

    //appends message to the end of file
    file_put_contents($uncommitted_updates_path, $message, FILE_APPEND | LOCK_EX);
}

?>