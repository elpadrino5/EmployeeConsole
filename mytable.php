<?php

//file with global definitions
require 'config.php';

function DisplayTable($info)
{
    echo "
    <head>        
        <link rel='stylesheet' href='resources/bootstrap/css/bootstrap.min.css'>   
        <link rel='stylesheet' href='css/style.css'>  
        <script src='resources/bootstrap/js/bootstrap.min.js'></script>
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>       
        <script src='js/script.js'></script>
        <title>Employee Console - Table</title>
    </head>
 
        <section id=\"pagebody\">
            <div class='collapse' aria-expanded='false' id='changes-container'>
                <p id='changes'>";

    global $uncommitted_updates_path;
    $changes = file_get_contents($uncommitted_updates_path);
    $changes_formatted = str_replace("\n", "<br>", $changes);
    echo $changes_formatted;
                
    echo "      </p>
            </div>";        

    //check if new changes were made to data
    if (isset($_SESSION['newchanges']))
    {
        global $uncommitted_updates_path;
        //check if changes are listed in log file
        $updates = file($uncommitted_updates_path);
        //count the num of changes
        $num_lines = count($updates);

        //at least one change
        if ($num_lines > 0)
        { 
            echo"<form action=\"index.php\" id=\"updateform\" method=\"post\" onsubmit='SubmitApply()'>
            <input type='hidden' name='apply_btn' value='apply'/>
            <button type=\"submit\" id='apply-button-ready' title='Apply'>Apply</button>
            <p id='apply-message-ready'>New changes ready to be applied.</p>
            </form>
            <button type='button' id='changes-button' data-toggle='collapse' data-target='#changes-container' 
            onclick='ChangeButtonValue()'>See Changes</button>";
        }
        else
        {            
            echo"<form action=\"index.php\" id=\"updateform\" method=\"post\">
                    <input type='hidden' name='apply_btn' value='apply'/>
                    <button type=\"button\" id='apply-button' title='Apply' 
                    onfocus='ShowApplyMessage()' onfocusout='HideApplyMessage()' onmouseover='ShowApplyMessage()' 
                    onmouseout='HideApplyMessage()'>Apply</button>
                    <p id='apply-message'>No new changes detected.</p>
                </form>";
        }
    }

    echo"<div class=\"csv_container\" name=\"csv_container\" id=\"csv_container\">
            <table class='buttons_table_class' id='buttons_table'>
                <tr>
                    <td>
                        <form class=\"csvform\" action=\"download.php\" id=\"downloadform\" method=\"get\">
                            <input type='hidden' name='csvbtn1' value='download'/> 
                            <button type=\"submit\" class=\"csvbtn\" id=\"download_btn\">
                                <span class=\" glyphicon glyphicon-download-alt\"></span>Download
                            </button>
                        </form>                                       
                        <button type=\"button\" class=\"csvbtn\" id=\"upload_btn\" data-toggle=\"collapse\" data-target=\"#upload_container\" onclick=\"UploadFile()\">
                            <span class=\"glyphicon glyphicon-upload\"></span>Upload
                        </button>
                        <form class=\"validation_form\" id=\"validation_form\" method=\"get\">
                            <input type='hidden' name='validation' value='validate'/> 
                            <button type=\"submit\" class=\"csvbtn\" id=\"validate_btn\">
                                <span class=\"glyphicon glyphicon-exclamation-sign\"></span>Validate
                            </button>
                        </form> 
                    </td>               
                </tr>
            
                <tr id= \"browse_row\">
                    <td>
                        <form class=\"csvform\" id=\"uploadform\" method=\"post\" enctype=\"multipart/form-data\">
                            <input type='hidden' name='upload' value='upload'/>
                            <div class=\"collapse\" id=\"upload_container\" aria-expanded=\"false\">
                                    <div id=\"browse\">
                                        <input type=\"file\" name=\"fileToUpload\" id=\"fileToUpload\" oninput=\"CheckInput()\">
                                        <button type=\"button\" class=\"btn btn-primary btn-s\" id=\"rm_file\" onclick=\"RemoveFile()\">
                                            <span class=\"glyphicon glyphicon-remove-circle\"></span>
                                        </button>
                                    </div>                                
                            </div>     
                        </form>
                    </td>
                </tr>
        
                <tr id= \"message_row\">
                    <td>
                        <div id=message_container>
                            <p id=\"upload_message\"></p> 
                        </div>
                    </td>
                </tr>
            </table>
        </div>

            <table id='mainTable'>
                <tr>
                    <th class='table_header' id='username_header'>Username</th>
                    <th class='table_header'>Name</th>
                    <th class='table_header'>Title</th>
                    <th class='table_header'>Department</th>
                    <th class='table_header'>Company</th>
                    <th class='table_header'>Telephone</th>
                    <th class='table_header'>Mobile</th>
                    <th class='table_header'>Manager</th>
                    <th class='table_header'>Office</th>
                    <th id='active_header'>Active</th>
                </tr>
    ";

    // <form action=\"editUser.php?username=$data[0]\" id='mainform$j' method='get'> <a href=\"editUser.php?username=$data[0]\">
    //<input type='hidden' id=\"username$j\" name='username' value='$data[0]'>"
    
    //index for naming form 
    $j = 0;
    foreach ($info as $data)
    {
        if ($data[0] !== "AccountName")
            {
                $username = $data[0];
                echo "<tr onclick=\"RowDetails('mainform$j','$data[0]')\">";                        
                
                //iterate through each column
                for ($i=0; $i < 14; $i++)
                {
                    //exclude columns you don't want displayed
                    if ( $i == 5 || $i == 8 || $i == 11 || $i == 12)
                    {
                        continue;
                    }
                        echo "                                
                        <td>                                                                                      
                            <p class='table_cell'>$data[$i]</p>                           
                        </td>";
                }
                echo"</tr>";
            }
        $j++;
    }
    echo    "</table>
        </section>
        <br>";
    //echo "<p id='blah'>yes</p>";
}

if (isset($_REQUEST["q"]))
{
    $q = $_REQUEST["q"];
    $string = $q;
    if (($string !== "") && ($string !== null))
    {                           
        //echo "<p>input: <b>'$string'</b></p><br>";
        $results = Search($filename, $string);
        //var_dump($results);
        //echo "<br>";
        if (!empty($results))
        {
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

?>
