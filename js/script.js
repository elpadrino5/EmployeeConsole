
function ResetInfo()
{
    //get the current value for all inputs
    var accountName = document.getElementById('accountName').value;
    var name = document.getElementById('name').value;
    var title = document.getElementById('title').value;
    var department = document.getElementById('department_option').value;
    var company = document.getElementById('company_option').value;
    var emailAddress = document.getElementById('emailAddress').value;
    var officePhone = document.getElementById('officePhone').value; 
    var mobile = document.getElementById('mobile').value;
    var mgrAcntName = document.getElementById('mgrAcntName_option').value;
    var manager = document.getElementById('manager').value;
    var office = document.getElementById('office_option').value;
    var streetAddress = document.getElementById('streetAddress_option').value;
    var postalCode = document.getElementById('postalCode_option').value;
    var active = document.getElementById('active_option').value;
    // var lastUpdate = document.getElementById('lastUpdate').value;
    // var lastEditedBy = document.getElementById('lastEditedBy').value;

    //key value array where key is the field name and value is the user input value
    var var_array = {'accountName': accountName, 'name': name, 'title': title, 'department': department, 'company': company,
    'emailAddress': emailAddress, 'officePhone': officePhone, 'mobile': mobile, 'mgrAcntName': mgrAcntName, 'manager': manager,
    'office': office, 'streetAddress': streetAddress, 'postalCode': postalCode, 'active': active};
    // ,'lastUpdate': lastUpdate, 'lastEditedBy': lastEditedBy};

    //key value array where key is the field name and value is the id of html element that displays error. keys are the same as var_array
    var err_array = {'accountName': 'anerror', 'name': 'nerror', 'title': 'terror', 'department': 'derror', 'company': 'cerror',
    'emailAddress': 'eaerror', 'officePhone': 'operror', 'mobile': 'merror', 'mgrAcntName': 'manerror', 'manager': 'maerror',
    'office': 'oerror', 'streetAddress': 'saerror', 'postalCode': 'pcerror', 'active': 'aerror'};
    // ,'lastUpdate': 'luerror', 'lastEditedBy': 'leberror'};

    for (var key in var_array)
    {
        var value = var_array[key];

        //reset the value in each field back to the info in the csv
        ResetInfoEach(key, value, accountName);
        
        //get the new value after the reset
        var value = document.getElementById(key).value;
        //perform validation in each value so user can see what fails and what doesn't  
        ValidateInput(key, value, err_array[key]);
        document.getElementById(key).style.boxShadow = "none";   
    }

    //perform validation to get rid of messages and show validations that will fail
    // for (var key in var_array)
    // {
    //     var value = document.getElementById(key).value;
    //     // alert(document.getElementById('name').value);
    //     ValidateInput(key, value, err_array[key]);
    // }
    return;
}

function ResetInfoEach(key, value, accountName)
{
        //start the request
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                //variables for each element and its parent
                var element = document.getElementById(key);
                var parent = element.parentNode;
                
                //  alert(element.tagName);
                if (element.tagName === 'OPTION')
                {                 
                    //reset both the value and the printed output   
                    element.innerHTML = this.responseText;
                    element.value = this.responseText; 
                    //reset the options for select input                   
                    parent.value = element.value;
                }
                else
                {
                    document.getElementById(key).value = this.responseText;
                    //alert(document.getElementById('name').value);
                    // alert(this.responseText);
                }
            }
        };
        
        //pass the field name, its value and the username to the server
        var url = 'editUser.php' + '?k=' + key + '&v=' + value + '&u=' + accountName;
        xmlhttp.open("GET", url, false);
        xmlhttp.send();
}

function SubmitSearch(name)
{
    var search = document.getElementById('searchTextBox').value;
    var form = document.getElementById(name);
    if (search.length > 0)
    {
        document.getElementById(name).submit();
    }
}

function ChangeHighlight(field)
{
    document.getElementById(field).style.boxShadow = "none";
}

function ValidateInput(field, value, err) 
{
        //alert(value);
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                output = this.responseText;
                //alert(output);

                if(output != '' || output != null)
                {
                    document.getElementById(field).style.boxShadow = "0px 0px 3px 1.9px red";
                }
                if(output == '' || output == null)
                {
                    document.getElementById(field).style.boxShadow = "0px 0px 3px 1.9px rgb(34, 190, 34)";
                }
                
                document.getElementById(err).innerHTML = output;            
            }
        };
            
        var encoded_field = encodeURIComponent(field);
        var encoded_value = encodeURIComponent(value);
        var url = 'validateForm.php' + '?k=' + encoded_field + '&v=' + encoded_value;
        xmlhttp.open("GET",url, false);
        xmlhttp.send();         
} 

function AutoSearch(str) 
{
    var output, out1, xmlDoc, parser, string;

    //clear the search message after submitting search and typing something on search bar
    if(document.getElementById('search_message'))
        document.getElementById('search_message').innerHTML = '';

    if (str.length == 0) 
    {
        return;
    }
    else
    {
        //alert('noway');
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() 
        {

            if (this.readyState == 4 && this.status == 200) 
            {
                output = this.responseText;

                document.getElementById("pagebody").innerHTML = output;            
            }
            //return;
            //alert('here');
        };
        //alert('down');                
        var url = 'mytable.php' + '?q=' + str;
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
        //return;
        //alert('end');
    }
}

function AutoPopulate(name, target)
{
    // alert("id: " + name + "target:" + target);
    //get the input (manager account name)
    var val = document.getElementById(name).value;
    var output;
    //  alert(val);

    //check if it's empty
    if (val.length == 0) 
    {
        return;
    }
    else
    {
        //initiate http request
        var xmlhttp = new XMLHttpRequest();
        //do function when communication is established
        xmlhttp.onreadystatechange = function() 
        {
            if (this.readyState == 4 && this.status == 200) 
            {
                //get output from server
                output = this.responseText;
                // alert(output);
                //assign output to desire document element
                document.getElementById(target).value = output;              
            }
        };
        //var for url containing request variable                
        var url = 'managerName.php' + '?k=' + name + '&v=' + val;
        xmlhttp.open("GET", url, true);
        xmlhttp.send();
    }
}

function UploadFile()
{
    //collapse the container
    AdjustHeight();

    if (!window.FileReader) 
    { // This is VERY unlikely, browser support is near-universal
        alert("The file API isn't supported on this browser yet.");
        return;
    }

    //get input file
    var ftu = document.getElementById('fileToUpload');
    if (!ftu.files)
    {
        alert("error");
        // console.error("This browser doesn't seem to support the `files` property of file inputs.");
    }
    else if (!ftu.files[0])
    {
        // alert("nothing");
    }
    else
    {
        // alert("something");
        var file = ftu.files[0];
        // alert("File " + file.name + " is " + file.size + " bytes in size");
        // document.getElementById('upload_btn').style.color = 'red';
        document.getElementById('uploadform').submit();
    }
    // alert("exiting");
    return;
}

//if there is input the upload button changes color and a message is displayed;
function CheckInput()
{
    document.getElementById('upload_btn').style.color = 'orange';
    document.getElementById('upload_message').innerHTML = "Click on Upload button to confirm";
}

//clear input, message, and style if cancel button is pressed
function RemoveFile()
{
    document.getElementById('fileToUpload').value = '';
    document.getElementById('upload_message').innerHTML = "";
    document.getElementById('upload_btn').style.color = '';
}

//collapse container to specified size
function AdjustHeight()
{
    var height;
    var isCollapsed = $("#upload_container").attr("aria-expanded");

    // alert(isCollapsed);
    if (isCollapsed)
    {
        document.getElementById('upload_btn').style.backgroundColor = 'white';
        document.getElementById('browse_row').style.backgroundColor = 'white';
        document.getElementById('message_row').style.backgroundColor = 'white';
        height = 'auto';
    }
    if (isCollapsed == 'false')
    {
        document.getElementById('upload_btn').style.backgroundColor = 'whitesmoke';
        document.getElementById('upload_btn').style.borderRadius = '0%';
        document.getElementById('download_btn').style.borderRadius = '0%';
        document.getElementById('validate_btn').style.borderRadius = '0%';
        document.getElementById('browse_row').style.backgroundColor = 'whitesmoke';
        document.getElementById('message_row').style.backgroundColor = 'whitesmoke';
        height = '100px'; 
    }
    document.getElementById('csv_container').style.height = height;

    return;
}

function ShowLabel(input, label, row, container)
{
    document.getElementById(input).style.height = '30px';
    document.getElementById(input).style.paddingTop = '0px';
    document.getElementById(label).style.visibility = 'visible';
    document.getElementById(row).style.visibility = 'visible';
    document.getElementById(container).style.border = '2px solid rgb(23, 153, 153)';
}

function HideLabel(input, label, row, container)
{
    if (document.getElementById(input).value == '')
    {
        document.getElementById(input).style.height = '56px';
        document.getElementById(input).style.paddingTop = '8px';
        document.getElementById(label).style.visibility = 'hidden';
        document.getElementById(row).style.visibility = 'collapse';
        document.getElementById(container).style.border = '1px solid gray';
    }
}

function DarkenButton(input)
{
    if (document.getElementById('username_input').value != '' && document.getElementById('password_input').value != '')
    {
        document.getElementById('logon_btn').style.pointerEvents = 'all';
        document.getElementById('logon_btn').style.backgroundColor = 'rgb(23, 153, 153)';
        document.getElementById('logon_btn').style.cursor = 'pointer';
    }
    else
    {
        document.getElementById('logon_btn').style.pointerEvents = 'none';
        document.getElementById('logon_btn').style.backgroundColor = 'rgba(23, 153, 153, 0.719)';
        document.getElementById('logon_btn').style.cursor = 'auto';
    }
}

//reveal a popup on mouse over
function ShowPopup()
{
    var popup = document.getElementById('popup_container');

    if(popup.style.visibility != 'visible')
    {
        document.getElementById('popup_container').style.visibility = 'visible';
    }
    else
    {
        HidePopup();
    }
    //alert(popup.style.visibility);
}

function HidePopup()
{
    document.getElementById('popup_container').style.visibility = 'hidden';
}

function TitleAnimation()
{
    alert(document.getElementById('navbar-title').style.width);
    document.getElementById('navbar-title').style.marginTop= '0px';
    document.getElementById('navbar-title').style.paddingLeft= '10px';
    document.getElementById('navbar-title').innerHTML = "Employee Info <br> Admin Console";
}

function TitleAnimationReset()
{
    document.getElementById('navbar-title').style.marginTop= '12px';
    document.getElementById('navbar-title').style.paddingLeft= '0px';
    document.getElementById('navbar-title').innerHTML = "Employee Console";
}

function SetSessionVar()
{
    var search_str = document.getElementById('searchTextBox').value;
    SetSessionVarAJAX(search_str)
}

function SetSessionVarAJAX(search_str)
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200) 
        {
            output = this.responseText;
            document.getElementById('searchTextBox').value = output;     
        }
    };               
    var search_str_enc = encodeURIComponent(search_str);
    var url = 'session_var.php' + '?search_str=' + search_str_enc;
    xmlhttp.open("GET", url, false);
    xmlhttp.send();
}

function GetSessionVar(str)
{
    BackToHome();
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200) 
        {       
            str = this.responseText;
        }
    };               
    var url = 'session_var.php' + '?q=' + "fill_search_bar";
    xmlhttp.open("GET", url, false);
    xmlhttp.send();
    location.replace("index.php?searchbtn=&search_action=search&textfield=" + str);    
}

function RowDetails(form, user)
{
    SetSessionVar();
    location.replace("editUser.php?username=" + user);
    return;
}

function ClearSearchBar()
{
    SetSessionVarAJAX('');
    document.getElementById('search').submit();
    location.replace('index.php');
}

function BackToHome()
{    
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200) 
        {

        }
    };               
    var url = 'session_var.php' + '?flag=' + 'true';
    xmlhttp.open("GET", url, false);
    xmlhttp.send();
    return;
}

function ShowApplyMessage()
{
    document.getElementById('apply-message').style.visibility = 'visible';
}

function HideApplyMessage()
{
    document.getElementById('apply-message').style.visibility = 'hidden';
}

function SubmitApply()
{
    SetSessionVar();
}

function ChangeButtonValue()
{
    var isCollapsed = $("#changes-container").attr("aria-expanded");

    if (isCollapsed == 'true')
    {
        document.getElementById('changes-button').innerHTML = 'See Changes';
    }

    if (isCollapsed == 'false')
    {
        document.getElementById('changes-button').innerHTML = 'Hide Changes';
    }
}

function ClearChangesMessage()
{
    document.getElementById('changes-msg-container').innerHTML = '';
}

function ClearApplyMessage()
{
    document.getElementById('apply-msg-container').innerHTML = '';
}
