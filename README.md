# ISCP.

1. To run create a folder named phpscript in the xampp>htdocs folder
2. Copy the files into the phpscript folder
2. Make sure to go to localhost/phpscript after opening xampp and create a database called iscpdb and go to import and select the database



To Run one Database on Multiple Computers
1. Open xampp folder
2. go to here C:\xampp\apache\conf\extra and find httpd-xampp
3. find Alias/phpmyadmin and replace it with this
  
Alias /phpmyadmin "C:/xampp/phpMyAdmin/"
    <Directory "C:/xampp/phpMyAdmin">
        AllowOverride AuthConfig Limit
        Require all granted
	Order allow,deny
	Allow from all
        ErrorDocument 403 /error/XAMPP_FORBIDDEN.html.var
    </Directory>

4.Run mysql then go to cmd(Command Prompt) type ipconfig find the right IPv4 Address (All must be in the same connection WiFi or LAN)
5.Then open 
(ipv4address):(port number found in my sql)/phpmyadmin
(ipv4address):(port number found in my sql)/phpscript

If an error occur check ChatGPT
 Most likely Firewall and Typo Problems

 Be sure to create a branch for own perspective Module!!
