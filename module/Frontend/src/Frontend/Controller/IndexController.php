<?php

namespace Frontend\Controller;

use My\Controller\MyController,
    My\General;

class IndexController extends MyController
{
    /* @var $serviceCategory \My\Models\Category */
    /* @var $serviceProduct \My\Models\Product */

    public function __construct()
    {
    }

    public function indexAction()
    {
        $params = $this->params()->fromRoute();

        $helper_title = $this->serviceLocator->get('viewhelpermanager')->get('MyHeadTitle');
        $helper_title->setTitle(General::SITE_AUTH);

        $this->renderer = $this->serviceLocator->get('Zend\View\Renderer\PhpRenderer');
        $this->renderer->headMeta()->appendName('robots', 'index');
        
        return array();
    }

}
