# HTTP Security Headers
Tests for presence and proper configuration of the following headers:
* X-XSS-Protection
* X-Content-Type-Options
* X-Frame-Options (deprecated)
* Strict-Transport-Security 
* Content-Security-Policy 
* Content-Type 
* Cache-Control 
* Pragma 
* Expires 
* X-Permitted-Cross-Domain-Policies 
* Access-Control-Allow-Origin 
* X-Powered-By  
* Server
* Set-Cookie

#Installation
Go to https://github.com/RandomAdversary/HTTP-Security-Headers 
and click the “Download ZIP” button. 

This will download a file with a name like HTTP-Security-Headers-master.zip.

Unzip this file into the directory where you keep all your vhosts and rename the resultant directory to name of your choice.

HTTP Security Headers uses Zend Framework 2 which needs to be downloaded and installed separately using Composer.

Composer can be downloaded at [getcomposer.org](https://getcomposer.org/)

To install Zend Framework 2 into our application we simply type:

    php composer.phar self-update
    php composer.phar install
from the HTTP Security Headers folder.
#Screenshots
![Home](http://i.imgur.com/6B3YvtZ.jpg)
![Results](http://i.imgur.com/K1AZAdW.png)
![Results Techcrunch](http://i.imgur.com/kUGL1Fg.png)
