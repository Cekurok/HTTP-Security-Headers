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
     	$form->get('submit')->setValue('GO');
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
             return array("headers"=>$web->getData(),"allHeaders"=>$web->getAll(),"website"=>$web->website,"score"=>$web->getScore());
            }
          catch (\Exception $e) { //Something gone wrong, probably timeout or invalid input.
           die("<img src=\"https://i.imgur.com/zrAcqr7.jpg\" >
           	</br>Sorry, I got error.</br>Please don't panic and try again.</br>
           <meta http-equiv=\"refresh\" content=\"7;url=./\">");
        }
        }
        else //Opening results page without POST data
        {
            die(" <img src=\"https://i.imgur.com/Ola1bOt.png\" height=\"100%\" width=\"70%\"> ");
        }
     }

 }