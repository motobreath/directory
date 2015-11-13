# directory

##Setup
Ensure that Zend Framework 1.x is available on the PHP path. In the php.ini config file:
```
include_path = "/path/to/Zend/library"
```
More information can be found on how to setup Zend Framework here: [Zend Framework Setup](http://framework.zend.com/manual/1.12/en/introduction.installation.html)

Modify /application/configs/application.ini to add LDAP, Database, Email, (etc) settings. 

##Apache Hosting

Sample Apache VHOST. Add DocumentRoot, ServerName, and <Directory> options as needed
```
<VirtualHost *:80>
    ServerAdmin webmaster@ucmerced.edu
    DocumentRoot "/xxx/public"
    ServerName xxx
    ErrorLog "logs/directory_log"
    CustomLog "logs/directory-access_log" combined
    SetEnv APPLICATION_ENV production
    <IfModule dir_module>
      DirectoryIndex index.php index.html
    </IfModule>
    <Directory "/xxx/public">
      Options FollowSymLinks
      AllowOverride All
      Order allow,deny
      Allow from all
    </Directory>
</VirtualHost>
```
