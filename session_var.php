<?php

//start the session
session_start();

if (isset($_REQUEST['search_str']))
{
    $_SESSION['search_str'] = $_REQUEST['search_str'];
    echo $_SESSION['search_str'];
    //  echo 'crazy';
}	

if (isset($_REQUEST['q']))
{
    echo $_SESSION['search_str'];
}

if (isset($_REQUEST['flag']))
{
    $_SESSION['flag'] = $_REQUEST['flag'];
}

?>