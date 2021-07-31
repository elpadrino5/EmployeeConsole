<?php

//start the session
session_start();

//file with global definitions
require 'config.php';

//check that user is logged in
Authenticate();

if ($_SERVER["REQUEST_METHOD"] == "GET") 
{
    if (isset($_GET['csvbtn1']))  //check if the elment was sent
    {
        if (file_exists($filename)) //check that there's actually a file to download
        {
            //declare array to contain all downloaded info except excluded columns;
            $downloadInfo = array(); 

            //open csv file for reading
            if (($h = fopen("{$filename}", "r")) !== FALSE) 
            {
                $data = array(); 
                //go through each line
                while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
                {
                    //add row to 2d array
                    $downloadInfo[] = array($data[0], $data[1], $data[2], $data[3], $data[4], $data[6], $data[7], $data[8],
                    $data[10], $data[11], $data[12], $data[13]);
                }
                fclose($h); //close the file
            }
            else
            {
                echo "Unable to open '$filename' for reading!";
            }	

            //name of new file to be downloaded
            $newfile = "data/UserInfo_downloaded.csv";
            //open csv file for writing. Deletes previous content
            if (($h = fopen("{$newfile}", "w")) !== FALSE) 
            {
                //for each row in 2d array
                foreach($downloadInfo as $newrow)
                {
                    //writes everything in array to csv file. writes row by row.
                    fputcsv($h, $newrow);
                }
                fclose($h);	//close the file
            }
            else
            {
                echo "Unable to open '$newfile' for writing!";
            }	

            //use combination of headers and readfile() to force the browser to download file
            header('Content-Description: Download csv');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($newfile).'"');
            header('Expires: 0');
            header('Cache-Control: no-cache');
            header('Pragma: no-cache');
            header('Content-Length: ' . filesize($newfile));
            ob_clean(); //cleans output buffer
            readfile($newfile); //outputs content of file
            exit;   
        }     
    } 
}

?>