<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
		$this->view->title = "CMS :: Error occurred";
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

}





