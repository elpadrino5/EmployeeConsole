<?php

//file with global definitions
require 'config.php';

function Sanitize($key, $value)
{
    $out = filter_var($value, FILTER_SANITIZE_STRING);

    if($key == 'emailAddress')
    {
        $out = filter_var($out, FILTER_SANITIZE_EMAIL);
        //echo $out;
        //die();
    }
    return $out;
}

function ValidateRegex($key, $value)
{
    //2d array composed of key(field name) and value(array containing regex and field type)
    //field type 0 means error 1 means warning
    $regex = array(		
        "accountName" => array("/^(([a-zA-Z])([a-zA-Z0-9_\-]{1,25}))$/", 1), //no uppercase, size limit 26		
        "name" => array("/^([A-Z]\'?[a-zA-Z\.\-]{1,24}\ ?){1,4}$/", 0), //first letter of every string must be uppercase, size limit per string 25, total 100       
        "title" => array("/^(([A-Z])([a-zA-Z0-9\ \.\/\(\)\,\&\-]){1,99})$/", 0), //uppercase in first letter, limit of 100
        "department" => array("/^(([A-Z])([a-zA-Z0-9\ \.\,\&\-]){1,49})$/", 0),//uppercase in first letter, limit of 50
        "company" => array("/^(([A-Z])([a-zA-Z0-9\ \.\,\&\-]){1,49})$/", 0), //uppercase in first letter, limit of 50
        "emailAddress" => array("/^((([a-zA-Z])([a-zA-Z]){1,24})\.(([a-zA-Z])([a-zA-Z\-]){1,24})\@luna\.com)$/", 1), //firstName(25) . lastName(25)@luna.com
        "officePhone" => array("/^$|^(\([0-9]{3}\)\ [0-9]{3}\-[0-9]{4})$/", 0),//format (xxx) xxx-xxxx
        "mobile" => array("/(^$|^(\([0-9]{3}\)\ [0-9]{3}\-[0-9]{4}))$/", 0),//format (xxx) xxx-xxxx       
        "mgrAcntName" => array("/^$|^(([a-zA-Z])([a-zA-Z0-9_\-]{1,25}))$/", 0),//no uppercase, size limit 26
        "manager" => array("/^$|^([A-Z]\'?[a-zA-Z\.\-]{1,24}\ ?){1,4}$/", 0),//first letter of every string must be uppercase, size limit per string 25, total 100
        "office" => array("/^(([A-Z])([a-zA-Z-9\ ]){1,49})$/", 0),//first letter must be uppercase, size limit 50
        "streetAddress" => array("/^(([0-9]{1,9})\ ([a-zA-Z])([a-zA-Z0-9\ \#\.\'\,\-]){1,89})$/", 0),//starts with number limit 1-10, followed by letters limit 90
        "postalCode" => array("/^(([0-9]{5})|([0-9]{5}\-[0-9]{4}))$/", 0),//accepts 5 digits and/or followed by a '-'and 4 digits 
        "active" => array("/^(Yes|No|True|False)$/i", 0), //letters limit 1-5
        "lastUpdate" => array("/^$|^([0-9\/]{8,10} [0-9:]{4,10} (AM|PM))$/i", 1),//date format
        "lastEditedBy" => array("/^$|^(([a-zA-Z])([a-zA-Z0-9_\-]{1,25}))$/", 1),//no uppercase, size limit 26  
    );

    //see if the arg field name exists
    if(array_key_exists($key, $regex) == false)
    {
        echo "Error! The field name '$key' is invalid.<br><br>";
        die();
    }

    // echo "key: $key, value:$value <br>";
    $regex_value = $regex[$key][0];
    $line = "key: $key, value:$value, regex:$regex_value\n";
    // file_put_contents("validate_regex_input.log", $line);
    if (!(preg_match($regex[$key][0], $value))) 
    {
        $ret_array = array($key, $regex[$key][1]);
        return $ret_array;
    }
    return null;
}

function AutoValidate($key, $value)
{
    // echo ("'$key', '$value'");
    $ret = ValidateRegex($key, $value);
    ValidationMessage($ret);
}

function ValidationMessage($ret)
{   
    if ($ret == null)
    {
        echo '';
    }
    else
    {
        $field_name = strval($ret[0]);

        $exclamation_sign = "<span class='glyphicon glyphicon-exclamation-sign'></span>";
        //echo $ret;
        switch ($field_name)
        {
            case 'accountName':
                echo $exclamation_sign . "Warning: Username format is invalid!";;
                break;
            case 'name':
                echo $exclamation_sign . "Full name format is invalid!";;
                break;
            case 'title':
                echo $exclamation_sign . "Title format is invalid!";
                break;
            case 'department':
                echo $exclamation_sign . "Department format is invalid!";;
                break;
            case 'company':
                echo $exclamation_sign . "Company format is invalid!";;
                break;
            case 'emailAddress':
                echo $exclamation_sign . "Warning: Email Address format is invalid!";;
                break;
            case 'officePhone':
                echo $exclamation_sign . "Office phone format must be '(xxx) xxx-xxxx' where 'x' is a number.";
                break;
            case 'mobile':
                echo $exclamation_sign . "Mobile format must be '(xxx) xxx-xxxx' where 'x' is a number.";
                break;
            case 'mgrAcntName':
                echo $exclamation_sign . "Manager username format is invalid!";;
                break;
            case 'manager':
                echo $exclamation_sign . "Warning: Manager format is invalid!";;
                break;
            case 'office':
                echo $exclamation_sign . "Office format is invalid!";;
                break;
            case 'streetAddress':
                echo $exclamation_sign . "Street Address format is invalid!";;
                break;
            case 'postalCode':
                echo $exclamation_sign . "Postal Code format is invalid!";;
                break;
            case 'active':
                echo $exclamation_sign . "Active format is invalid!";;
                break;
            
            // case 'lastUpdate':
            //     echo "Last Update format is invalid!";;
            //     break;
            // case 'lastEditedBy':
            //     echo "Last Edited By format is invalid!";;
            //     break;
        }  
    }
}

if (isset($_REQUEST["k"]) && isset($_REQUEST["v"]))
{
    $key = $_REQUEST["k"];
    $value = $_REQUEST['v'];
    //echo "$key<br>";
    //echo "$value<br>";
    AutoValidate($key,$value);
}

?>