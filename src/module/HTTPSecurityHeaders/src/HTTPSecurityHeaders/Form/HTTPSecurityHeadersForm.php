<?php
namespace HTTPSecurityHeaders\Form;

 use Zend\Form\Form;

 class HTTPSecurityHeadersForm extends Form
 {
     public function __construct($name = null)
     {
         // we want to ignore the name passed
         parent::__construct('httpsecurityheaders');
         $this->setAttributes(array(
          'method' => 'post',
          'class'  => 'form-group'
           ));
         $this->add(array(
             'name' => 'website',
             'type' => 'url',
             'options' => array(
                 'label' => '',
             ),
             'attributes' => array(
                 'value' => 'http://',
                 'size' => '35',
                 'class'=>"form-control ",
             ),
         ));
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Go',
                 'id' => 'submitbutton',
                 'class'=>"btn btn-primary btn-block",
             ),
         ));
     }
 }