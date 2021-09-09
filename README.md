# Employee Info Admin Console
### LOGIN PAGE

![login](https://user-images.githubusercontent.com/71573442/132612446-29797377-8f06-4ec8-8d66-719e7e221a50.PNG)<br>

### INDEX PAGE

![image](https://user-images.githubusercontent.com/71573442/132611473-aae53d73-74e7-4036-bc5f-bf1aeeaf0c08.png)<br>

### EDIT PAGE
![image](https://user-images.githubusercontent.com/71573442/132612594-47552125-86f1-47d2-a0e4-b5ee30c808b7.png)<br><br>


## MANUAL

### SEARCHING <br>
The search bar can be used to search for one or more employees that contain all or part of the search words in ANY of the fields including the ones that are not visible in the main table (must go to edit page to see): 'Email Address', 'Street Address', and 'Postal Code'. The resulting rows will be dynamically displayed (each row represents info of one employee) as input changes in the search bar. If there's no input on the search bar or if it's cleared then all rows will be displayed.<br>
e.g. For the displayed users below, the word "chicago" was found on their 'Office' field; "cynthia" was found on their 'Name' and/or ‘Manager’ field; and "773" was found on their 'Telephone' or 'Mobile' field.
 <br><br>
 
 ![image](https://user-images.githubusercontent.com/71573442/132609979-9f337b27-0af5-4ba1-b281-0c917a859f28.png)<br><br>

### DOWNLOAD/UPLOAD <br>
Pressing the 'Download' button will give users the option of opening or saving all employee info to their local machine. The downloaded file may be modified and used to update the current data. To achieve this, users must select the ‘Upload’ button, browse for the file in their machine, and re-select ‘Upload’. Then changes found in the uploaded file will be made to the current information. Changes made will be displayed in the browser.
 <br><br>
 
 ![image](https://user-images.githubusercontent.com/71573442/132610011-c3478d1a-74d3-4d92-957d-58d7a3a2974b.png)<br><br>


### VALIDATE<br>
Pressing the 'Validate' button will perform validation to all data. Warnings or errors will be displayed depending on which fields failed validation.
<br><br>
 
 ![image](https://user-images.githubusercontent.com/71573442/132610018-2b13fd09-5214-4cba-8585-185576dc8da1.png)<br><br>


### EDIT<br>
An employee's info can be viewed and edited in the edit page. Validation is performed as input is changed in the fields. As input fails validation an alert message will appear next to it and the input field will be highlighted red; as input passes validation the alert message will go away and the highlight will turn green. The select options are auto-generated depending on the current data. Selecting an option for 'ManagerUsername' will automatically fill in the 'Manager' field. The following fields are read-only; they also have a colored background: 'Username', 'Email Address', 'Manager', 'LastUpdate', 'LastEditedBy'. The 'LastUpdate' field contains the date when info was last modified through the web app for that employee, while the 'LastEditedBy' indicates who did the modification.	 
<br><br>

![image](https://user-images.githubusercontent.com/71573442/132610038-aa0df993-0e61-4106-918d-09a4d63f2c4d.png)<br><br>

### UPDATE<br>
Pressing the 'Update' button triggers validation to all field values; if passed it will update the row and go back to the index page and display the last search results. If validation fails, alert messages will appear next to the fields that failed validation and no changes will be made.
 <br><br>
	
  ![image](https://user-images.githubusercontent.com/71573442/132610061-8c9a9fa1-0fc3-4c31-beaf-19f1840b4111.png)<br><br>

 ### RESET<br>
 Pressing the 'Reset' button resets fields modified by users back to the current information in the table.
<br><br>

### APPLY<br>
The 'Apply' button changes color and becomes clickable when the data gets updated. Pressing the Apply button will send an email to the specified address and commit the currently modified data. A message will display the applied changes. Additionally, the log registering the changes since the last update will be cleared. 
 <br><br> 
 
![image](https://user-images.githubusercontent.com/71573442/132610080-1928ed52-4fdd-4a82-b806-8168b8167ab9.png)<br>
![image](https://user-images.githubusercontent.com/71573442/132610090-328e303e-56a0-48e9-bc59-46791d23f6ad.png)<br><br>


### REVERT<br>
Pressing the 'Revert' button will revert the repository's working copy; in other words, any changes made since the last "Apply" will be reverted.
<br><br>

### SEE CHANGES<br>
Pressing the 'See Changes' button will display all changes made to the data since the last 'Apply'. Additionally, the text for the button will change from "See Changes" to "Hide Changes". Thus, pressing the button when the message is displayed will hide the message.
<br><br>

![image](https://user-images.githubusercontent.com/71573442/132610116-146c2dc8-3354-40b1-bb12-3f660f93b4f0.png)<br><br> 

