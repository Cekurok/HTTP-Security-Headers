<?php
 namespace HTTPSecurityHeaders\Controller;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use HTTPSecurityHeaders\Form\HTTPSecurityHeadersForm;
 use HTTPSecurityHeaders\Model\HTTPSecurityHeaders;
 use Exception;

 class HTTPSecurityHeadersController extends AbstractActionController
 {
     public function indexAction()
     {
     	$form = new HTTPSecurityHeadersForm();
     	return array("form"=> $form);
     }

     public function resultAction()
     {
        $request = $this->getRequest();
        if ($request->isPost())
        {
        	$web = new HTTPSecurityHeaders();
        	$postData = $request->getPost();
            try{
        	$web->exchangeArray($postData);
            return array(
            	"headers" => $web->getData(),
            	"allHeaders" => $web->getAll(),
            	"website" => $web->website,
            	"score" => $web->getScore(),
            	);
            }
          //Note* This is very bad way to handle exceptions, don't try this at work.
          catch (\Exception $e) { //Something is wrong, probably timeout or invalid input.
           //Display cat image, after 5 seconds return to the index page.
           die("<img src=\"http://thecatapi.com/api/images/get.php?format=src&type=gif\" height=\"300px\" width=\"350px\"> </br>
           	</br>Sorry, I got error.</br>
           	</br>
           	I will take you back to the main page in 5 seconds.
            <meta http-equiv=\"refresh\" content=\"5;url=./\">");
            }
        }
        else //Opening results page without POST data
        {
            die(" <img src=\"https://i.imgur.com/Ola1bOt.png\" height=\"100%\" width=\"70%\"> 
            	<meta http-equiv=\"refresh\" content=\"3;url=./\">");
        }
     }

 }