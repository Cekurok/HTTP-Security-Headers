<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'HTTPSecurityHeaders\Controller\HTTPSecurityHeaders' => 'HTTPSecurityHeaders\Controller\HTTPSecurityHeadersController',
         ),
     ),
////
       'router' => array(
         'routes' => array(
             'httpsecurityheaders' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/[:action]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        // 'site'     => '[a-zA-Z][a-zA-Z0-9_-]*',[/:site]
                     ),
                     'defaults' => array(
                         'controller' => 'HTTPSecurityHeaders\Controller\HTTPSecurityHeaders',
                         'action'     => 'index',
                     ),
                 ),
             ),
         ),
     ),




     //////
     'view_manager' => array(
         'template_path_stack' => array(
             'httpsecurityheaders' => __DIR__ . '/../view',
         ),
     ),
 );