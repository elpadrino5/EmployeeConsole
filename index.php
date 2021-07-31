<?php

//start the session
session_start();

//file with global definitions
require 'config.php';

//check that user is logged in
Authenticate();

//include code
require_once 'html/navbar.html'; //includes navigation bar and upload and download functionality
require_once 'mytable.php'; //displays data in table format
require_once 'validateForm.php';

// echo "session code: " . session_status() . "<br>";
// if (isset($_SERVER['PHP_AUTH_USER'])) { echo $_SERVER['PHP_AUTH_USER']; }

//handle page requests
ProcessPageRequest($filename); 

//closing tags
echo "</body>
</html>";

function ProcessPageRequest($filename, $autoString = '')
{    



    //get requests
    if ($_SERVER["REQUEST_METHOD"] == "GET") 
	{
        echo "
        <head>
            <link rel='stylesheet' href='css/style.css'>
        </head>";

        if (isset($_GET['search_action'])) //check if the elment was sent
		{
            $string = $_GET['textfield'];
            //make sure input search string is not empty
            if (($string !== "") && ($string !== null))
            {            
                // check for this var to determine wheter to show search message or not          
                if (isset($_SESSION['flag']))
                {
                    if($_SESSION['flag'] == 'false')
                    {
                        //shouldn't be displayed when going back to home   
                        echo "<p id='search_message' class='bodytext'>You searched for: '$string'</p>";
                    }
                    else
                    {
                        //set back to false
                        $_SESSION['flag'] = 'false';
                    }                    
                }
                else
                {
                    //displays when user submits search
                    echo "<p id='search_message' class='bodytext'>You searched for: '$string'</p>";
                }     
                
                //get the results of the search for the input string
                $results = Search($filename, $string);
                //var_dump($results);

                //check that results are not empty
                if (!empty($results))
                {
                    //display the results in table format
                    DisplayTable($results);
                }
                else
                {
                    echo "
                    <section id=\"pagebody\">
                        <p class='bodytext'>No search results found for: <b>'$string'</b>.</p>
                    </section>";
                }
            }
            else
            {
                //display complete table
                DisplayTable(GetInfo($filename));
            }
        }
        if (isset($_GET['validation']))
        {
            //validate current info and display on browser
            $currentInfo = GetInfo($filename);
            ValidateTable($currentInfo, 16);
            global $validate_file;
            $val_alerts = file_get_contents($validate_file);
            echo $val_alerts;
        }
    }
    
    //post requests 
    if ($_SERVER["REQUEST_METHOD"] == "POST") 
    {
        if (isset($_POST['upload']))//check that upload button was pressed
		{          
            if (isset($_FILES['fileToUpload']['name'])) //check that a upload file was input
            {
                //assign input file properties to vars
                $fname = $_FILES['fileToUpload']['name']; 
                $ftmp_name = $_FILES['fileToUpload']['tmp_name'];         
                $ftype = $_FILES['fileToUpload']['type'];
                $fsize = $_FILES['fileToUpload']['size'];
                $uploaded_file = "data/UserInfo_uploaded.csv";
                // echo "name: . $fname . <br> name: . $ftmp_name . <br> type: . $ftype . <br> size: . $fsize . <br>";
                //upload file must not be empty

                if ($fsize > 0)
                {
                    //move upload file from temporary location to existing path of current file. Current file will be overwritten. 
                    $moved = move_uploaded_file($ftmp_name, $uploaded_file);

                    //display message if copying upload file to new location failed
                    if ($moved == false)
                    {
                        echo "<p class='bodytext'>Error! The file could not be saved!</p><br>";
                    }
                    else //if nothing failed
                    {
                        echo"<div id='changes-msg-container'>";
                        //get uploaded info and compare it to current info and update the changes
                        ProccessUpload($uploaded_file);
                        echo"</div>";
                    }
                }
                else
                {
                    echo "<p class='bodytext'>Upload file must not be empty!</p>";
                }                
            }
                    //passes an array containing all user info and displays it in table format
		            DisplayTable(GetInfo($filename));
        }
        if (isset($_POST['apply_btn']))//check that upload button was pressed
		{
            global $uncommitted_updates_path; //use global var

            //set var to false since chages were submitted
            $_SESSION['newchanges'] = 'false';

            $u = $_SESSION['username'];
            $email = "The following changes were applied to " . $filename . " by $u\n\n";
            $email_active = "Potential employee(s) termination by $u\n\n";  
            
            //count the lines in file
            $updates = file($uncommitted_updates_path);
            $num_lines = count($updates);
            

            // echo $num_lines . "<br>";
            $changes = file_get_contents($uncommitted_updates_path);
            $changes_formatted = str_replace("\n", "<br>", $changes);

            global $email_notification_address;
            if ($num_lines > 0)
            { 
                $active_changed = 0; //num of times active field was changed to 'no'
                foreach($updates as $ea)
                {
                    $email .= $ea . "";  
                    if((stripos($ea, "active") !== false) && (stripos($ea, "to 'No'") !== false))
                    {
                        $email_active .= $ea;
                        $active_changed++;
                    }
                }

                if ($active_changed > 0 )
                {
                    mail($email_notification_address, "Employee Console Disable Account", $email_active, $email_headers);
                }                

                //change the styling of apply button to let the user know changes are ready to be applied
                echo "<div id='apply-msg-container'>";
                echo "<button type='button' id='clear-apply-button'title='clear message' onclick='ClearApplyMessage()'>Clear</button><br>";
                echo "The following changes were applied:<br>";
                echo $changes_formatted;
                

                //notify that changes were applied and by who
                //PrintSysLog("employee info admin console, changes were applied by $u");

                //send an email listing the changes made
                //mail($email_notification_address, "Employee Console Update Notification", $email);
                echo "<br><p>An email was sent to $email_notification_address</p>";
                echo "</div>";
                
                global $uncommitted_updates_path;                
                file_put_contents($uncommitted_updates_path,'');

                
                //initialize variables for executable function                
                $output=null; $retval=null;
                global $command_after_apply, $descriptorspec, $cwd, $env;

                //execute the commad 
                // $process = proc_open($command_after_apply, $descriptorspec, $pipes, $cwd ,$env);

                //get standart output and error
                // $stdout = stream_get_contents($pipes[1]);
                // fclose($pipes[1]);
                // $stderr = stream_get_contents($pipes[2]);

                // fclose($pipes[2]);                

                // echo "stdout : $stdout <br>";
                // echo "stderr : $stderr <br>";
                // $retval = proc_close($process);
                // echo "returned with status $retval<br>";
            }

            //get the input that was in search
            $string = $_SESSION['search_str'];
            
            //displays table depending on last search input
            if ($string == null || $string == '')
            {
                //passes an array containing all user info and displays it in table format
                DisplayTable(GetInfo($filename));
            }
            else
            {
                //get the search results 
                $results = Search($filename, $string);

                //check that results are not empty
                if (!empty($results))
                {
                    //display the results in table format
                    DisplayTable($results);
                }
                else
                {
                    echo "
                    <section id=\"pagebody\">
                        <p class='bodytext'>No search results found for: <b>'$string'</b>.</p>
                    </section>";
                }
            }
        }  
    }
    else
    {
        if ($_GET == null)
        {
            //passes an array containing all user info and displays it in table format
            DisplayTable(GetInfo($filename));
        }
    }
}

?>