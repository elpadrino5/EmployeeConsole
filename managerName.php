<?php 

//start the session
session_start();

//file with global definitions
require 'config.php';

//check that user is logged in
Authenticate();

    //check that request was sent
    if (isset($_REQUEST["k"]))
    {
        $k = $_REQUEST["k"];
        $v = $_REQUEST["v"];

        switch ($k)
        {
            case 'mgrAcntName':      
                          
                //make sure it's not empty
                if (($v !== "") && ($v !== null))
                {    
                    $acntName = strtolower($v);            
                    //get all info           
                    $table = GetInfo($filename);
                    // loop through info

                    if ($acntName === "notapplicable")
                    {
                        echo "Not Applicable";
                        break;
                    }

                    foreach ($table as $line)
                    {
                        $cur_acnt_name = strtolower($line[0]);
                        //compare input data to first column
                        if ($cur_acnt_name === $acntName)
                        {
                            //variable for second column of table (fullname)
                            $fname = $line[1];
                            //output the full name corresponding to the input account name
                            echo "$fname";
                            //exit();
                        }
                    }
                }
                break;

            case 'streetAddress':

                //make sure it's not empty
                if (($v !== "") && ($v !== null))
                {     
                    $streetAddress = $v;           
                    //get all info           
                    $table = GetInfo($filename);
                    // loop through info
                    foreach ($table as $line)
                    {
                        //compare input data to first column
                        if ($line[0] === $acntName)
                        {
                            //variable for second column of table (fullname)
                            $fname = $line[1];
                            //output the full name corresponding to the input account name
                            echo "$fname";
                            //exit();
                        }
                    }
                }
                break;
        }
    }  

?>