<?php
namespace HTTPSecurityHeaders\Model;

use Zend\Http\Request;
use Zend\Http\Client;

class HTTPSecurityHeaders
{
    public $website;
    protected $score;
    protected $headersData;
    protected $headersDataRaw;
    protected $total=14;
    public function exchangeArray($data) {
        $this->website = $data["website"];
        $this->score = 0;
        $this->checkHeaders();
    }

    public function checkHeaders() {
        //Create the request client
        $client = new Client($this->website,array(
        'adapter' => 'Zend\Http\Client\Adapter\Curl')
        );
        $client->setOptions(array('maxredirects' => 1, 
        	'timeout' => 30,
        	'useragent' => "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36",)
        );
        
        //make the request
        $this->headersDataRaw = $client->send()->getHeaders();
        $headersDataRaw = $this->headersDataRaw;

        //Check the headers
        //X-XSS-Protection
        $this->headersData["X-XSS-Protection"] = array();
        $this->headersData["X-XSS-Protection"]["recommend"] = "1; mode=block";
        if ($headersDataRaw->has("X-XSS-Protection")) {
            $value = $headersDataRaw->get("X-XSS-Protection")->getFieldValue("X-XSS-Protection");
            $value = str_replace(' ', '', $value);
            if (strcasecmp($value, "1;mode=block") == 0) {
                $this->headersData["X-XSS-Protection"]["used"] = "yes";
                $this->score+= 1;
            } else {
                $this->headersData["X-XSS-Protection"]["used"] = "wrong";
            }
            $this->headersData["X-XSS-Protection"]["value"] = $value;
        } else {
            $this->headersData["X-XSS-Protection"]["used"] = "no";
            $this->headersData["X-XSS-Protection"]["value"] = "";
        }
        
        //X-Content-Type-Options
        $this->headersData["X-Content-Type-Options"] = array();
        $this->headersData["X-Content-Type-Options"]["recommend"] = "nosniff";
        if ($headersDataRaw->has("X-Content-Type-Options")) {
            $value = $headersDataRaw->get("X-Content-Type-Options")->getFieldValue("X-Content-Type-Options");
            $value = str_replace(' ', '', $value);
            if (strcasecmp($value, "nosniff") == 0) {
                $this->headersData["X-Content-Type-Options"]["used"] = "yes";
                $this->score+= 1;
            } else {
                $this->headersData["X-Content-Type-Options"]["used"] = "wrong";
            }
            $this->headersData["X-Content-Type-Options"]["value"] = $value;
        } else {
            $this->headersData["X-Content-Type-Options"]["used"] = "no";
            $this->headersData["X-Content-Type-Options"]["value"] = "";
        }
        
        //X-Frame-Options 
        $this->headersData["X-Frame-Options"] = array();
        $this->headersData["X-Frame-Options"]["recommend"] = "sameorigin";
        if ($headersDataRaw->has("X-Frame-Options")) {
            $value = $headersDataRaw->get("X-Frame-Options")->getFieldValue("X-Frame-Options");
            if (strcasecmp($value, "deny") == 0 || strcasecmp($value, "sameorigin") == 0) {
                $this->headersData["X-Frame-Options"]["used"] = "yes";
                $this->score+= 1;
            } else {
                $this->headersData["X-Frame-Options"]["used"] = "wrong";
            }
            $this->headersData["X-Frame-Options"]["value"] = $value;
        } else {
            $this->headersData["X-Frame-Options"]["used"] = "no";
            $this->headersData["X-Frame-Options"]["value"] = "";
        }
        
        //Strict-Transport-Security 
        $this->headersData["Strict-Transport-Security"] = array();
        $this->headersData["Strict-Transport-Security"]["recommend"] = "max-age=31536000;includeSubDomains";
        if ($headersDataRaw->has("Strict-Transport-Security")) {
            $value = $headersDataRaw->get("Strict-Transport-Security")->getFieldValue("Strict-Transport-Security");
            $value = str_replace(' ', '', $value); //Some sites have space characters between header options
            if (strcasecmp($value, "max-age=31536000;includeSubDomains") == 0) {
                $this->headersData["Strict-Transport-Security"]["used"] = "yes";
                $this->score+= 1;
            } else {
                $this->headersData["Strict-Transport-Security"]["used"] = "wrong";
            }
            $this->headersData["Strict-Transport-Security"]["value"] = $value;
        } else {
            $this->headersData["Strict-Transport-Security"]["used"] = "no";
            $this->headersData["Strict-Transport-Security"]["value"] = "";
        }
        
        //Content-Security-Policy 
        $this->headersData["Content-Security-Policy"] = array();
        $this->headersData["Content-Security-Policy"]["recommend"] = "read more at  <a href=\"http://www.html5rocks.com/en/tutorials/security/content-security-policy/\">HTML5 Rocks blog.</a> ";
        if ($headersDataRaw->has("Content-Security-Policy")) {
            $value = $headersDataRaw->get("Content-Security-Policy")->getFieldValue("Content-Security-Policy");
            $this->headersData["Content-Security-Policy"]["used"] = "yes";
            $this->headersData["Content-Security-Policy"]["value"] = $value;
            $this->score+= 1;
        } else {
            $this->headersData["Content-Security-Policy"]["used"] = "no";
            $this->headersData["Content-Security-Policy"]["value"] = "";
        }
        
        //Content-Type 
        $this->headersData["Content-Type"] = array();
        $this->headersData["Content-Type"]["recommend"] = "text/html; charset=utf-8";
        if ($headersDataRaw->has("Content-Type")) {
            $value = $headersDataRaw->get("Content-Type")->getFieldValue("Content-Type");
            if (strcasecmp($value, "text/html; charset=utf-8") == 0)
            {
            $this->headersData["Content-Type"]["used"] = "yes";
            $this->score+= 1;
            }
            else
            {
                $this->headersData["Content-Type"]["used"] = "wrong";
            }
            $this->headersData["Content-Type"]["value"] = $value;
            
        } else {
            $this->headersData["Content-Type"]["used"] = "no";
            $this->headersData["Content-Type"]["value"] = "";
        }
        
        //Cache-Control 
        $this->headersData["Cache-Control"] = array();
        $this->headersData["Cache-Control"]["recommend"] = "no-cache, no-store, must-revalidate";
        if ($headersDataRaw->has("Cache-Control")) {
            $value = $headersDataRaw->get("Cache-Control")->getFieldValue("Cache-Control");
            $value = str_replace(' ', '', $value);
            if (strpos($value, "no-cache") !== false && strpos($value, "no-store") !== false && strpos($value, "must-revalidate") !== false) {
                $this->headersData["Cache-Control"]["used"] = "yes";
                $this->score+= 1;
            } else {
                $this->headersData["Cache-Control"]["used"] = "wrong";
            }
            $this->headersData["Cache-Control"]["value"] = $value;
        } else {
            $this->headersData["Cache-Control"]["used"] = "no";
            $this->headersData["Cache-Control"]["value"] = "";
        }
        
        //Pragma 
        $this->headersData["Pragma"] = array();
        $this->headersData["Pragma"]["recommend"] = "no-cache";
        if ($headersDataRaw->has("Pragma")) {
            $value = $headersDataRaw->get("Pragma")->getFieldValue("Pragma");
            if (strcasecmp($value, "no-cache") == 0) {
                $this->headersData["Pragma"]["used"] = "yes";
                $this->score+= 1;
            } else {
                $this->headersData["Pragma"]["used"] = "wrong";
            }
            $this->headersData["Pragma"]["value"] = $value;
        } else {
            $this->headersData["Pragma"]["used"] = "no";
            $this->headersData["Pragma"]["value"] = "";
        }
        
        //Expires 
        $this->headersData["Expires"] = array();
        $this->headersData["Expires"]["recommend"] = "Expires: [some valid date in the past]";
        if ($headersDataRaw->has("Expires")) {
            $value = $headersDataRaw->get("Expires")->getFieldValue("Expires");
            $today = gmdate('D, d M Y H:i:s T', time());
            if (strtotime($value) <= strtotime($today)) {
                $this->headersData["Expires"]["used"] = "yes";
                $this->score+= 1;
            } else {
                $this->headersData["Expires"]["used"] = "wrong";
            }
            $this->headersData["Expires"]["value"] = $value;
        } else {
            $this->headersData["Expires"]["used"] = "no";
            $this->headersData["Expires"]["value"] = "";
        }
        
        //X-Permitted-Cross-Domain-Policies 
        $this->headersData["X-Permitted-Cross-Domain-Policies"] = array();
        $this->headersData["X-Permitted-Cross-Domain-Policies"]["recommend"] = "master-only";
        if ($headersDataRaw->has("X-Permitted-Cross-Domain-Policies")) {
            $value = $headersDataRaw->get("X-Permitted-Cross-Domain-Policies")->getFieldValue("X-Permitted-Cross-Domain-Policies");
            if (strcasecmp($value, "master-only") == 0) {
                $this->headersData["X-Permitted-Cross-Domain-Policies"]["used"] = "yes";
                $this->score+= 1;
            } else {
                $this->headersData["X-Permitted-Cross-Domain-Policies"]["used"] = "wrong";
            }
            $this->headersData["X-Permitted-Cross-Domain-Policies"]["value"] = $value;
        } else {
            $this->headersData["X-Permitted-Cross-Domain-Policies"]["used"] = "no";
            $this->headersData["X-Permitted-Cross-Domain-Policies"]["value"] = "";
        }
        
        //Access-Control-Allow-Origin 
        $this->headersData["Access-Control-Allow-Origin"] = array();
        $this->headersData["Access-Control-Allow-Origin"]["recommend"] = "read more at  <a href=\"http://www.html5rocks.com/en/tutorials/cors/\">HTML5 Rocks blog.</a> ";
        $value = str_replace(' ', '', $value);
        if ($headersDataRaw->has("Access-Control-Allow-Origin")) {
            $value = $headersDataRaw->get("Access-Control-Allow-Origin")->getFieldValue("Access-Control-Allow-Origin");
            $this->headersData["Access-Control-Allow-Origin"]["used"] = "yes";
            $this->headersData["Access-Control-Allow-Origin"]["value"] = $value;
            $this->score+= 1;
        } else {
            $this->headersData["Access-Control-Allow-Origin"]["used"] = "no";
            $this->headersData["Access-Control-Allow-Origin"]["value"] = "";
        }
        
        //X-Powered-By  
        if ($headersDataRaw->has("X-Powered-By")) {
            $this->headersData["X-Powered-By"] = array();
            $this->headersData["X-Powered-By"]["recommend"] = "Avoid header";
            $value = $headersDataRaw->get("X-Powered-By")->getFieldValue("X-Powered-By");
            $this->headersData["X-Powered-By"]["used"] = "wrong";
            $this->headersData["X-Powered-By"]["value"] = $value;
        } else {
            $this->total--;
        }
        
        //Server
        if ($headersDataRaw->has("Server")) {
            $value = $headersDataRaw->get("Server")->getFieldValue("Server");
            $this->headersData["Server"] = array();
            if(preg_match('#[0-9]#',$value))
            {
            $this->headersData["Server"]["recommend"] = "Avoid version numbers";
            }
            else
            {
              $this->headersData["Server"]["recommend"] = "Avoid header";
             //There is no version number exposed, display warning but
             //exclude this header from the final score
              $this->total--;
            }
            $this->headersData["Server"]["used"] = "wrong";
            $this->headersData["Server"]["value"] = $value;
        } else {
            $this->total--;
        }

        //Set-Cookie
        if($headersDataRaw->has("Set-Cookie"))
        {
            $this->headersData["Set-Cookie"]=array();
            $this->headersData["Set-Cookie"]["recommend"]="add secure, httponly";
            $value = $headersDataRaw->get("Set-Cookie");
            $isValid = true;
            foreach ($value as $cookie) {
               if($cookie->isHttponly()==false || $cookie->isSecure()==false )
               {
                $isValid=false;
               }
            }
           if ($isValid==true)
           {
              $this->headersData["Set-Cookie"]["used"]="yes";
              $this->score+= 1;
           }
           else
           {
            $this->headersData["Set-Cookie"]["used"]="wrong";
           }
           $this->headersData["Set-Cookie"]["value"]="See All headers for the actual value";
        }
        else
        {
          $this->total--;
        }
        
    }
    public function getData() {
        return $this->headersData;
    }
    public function getAll() {
        $allHeaders = $this->headersDataRaw->toString();
        //Get each header as string
        $allHeaders = explode("\n", $allHeaders);
        //The last item is always empty string
        array_pop($allHeaders);
        //Parse the header values
        foreach ($allHeaders as $tmpHeader) {
            $header = explode(":", $tmpHeader);
            $headers[$header[0]] = ""; //Set the name of header as key
            for ($k = 1; $k < count($header); $k++) 
                $headers[$header[0]].= $header[$k];//Magic, do not touch
        }
    //Get all the values from Set-Cookie header
    if($this->headersDataRaw->has("Set-Cookie")){
           $value = $this->headersDataRaw->get("Set-Cookie");
           $count = 1;
           $headers["Set-Cookie"]="";
            foreach ($value as $cookie) {
              $headers["Set-Cookie"].=$cookie->toString()."</br>";
            }
    }
    return $headers;
    }

    public function getScore() {
        $score = $this->score;
        return $score * (float)(100/$this->total);
    }
}
