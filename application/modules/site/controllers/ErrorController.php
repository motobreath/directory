<?php

class ErrorController extends Zend_Controller_Action
{

    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');

        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->h1Message="Application Error, but don't worry it's not your fault.";
            $this->view->message = "Something happend there, give us just a few and we'll have things back up. <br />Please <a href='/'>Return Home</a> and try again";
            return;
        }
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->h1Message="That page is hiding...";
                $this->view->message = 'Page not found. <a href="/">Return Home</a>';
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->h1Message="Application Error, but don't worry it's not your fault.";
                $this->view->message = "Something happend there, give us one second and we'll have things back up. <br />Please <a href='/'>Return Home</a> and try again";

                //send mail, only when not in dev, too many emails!
                if(APPLICATION_ENV=="production"){
                    try{
                        $params=var_export($errors->request->getParams(),true);
                        $mail=new Zend_Mail();
                        $mail->addTo("cmitchell@ucmerced.edu");
                        $mail->setSubject("Directory Error");
                        $mail->setBodyHtml("<h1>Exception:</h1>" . $errors->exception);
                        $mail->setFrom("directory@ucmerced.edu");
                        $mail->send();
                    }
                    catch(Exception $e){
                        $log->log($e, $priority, $errors->exception);
                    }
                }

                break;
        }

        // Log exception, if logger available
        if ($log = $this->getLog()) {
            $log->log($errors->exception, $priority, $errors->exception);
            $log->log('Request Parameters', $priority, $errors->request->getParams());
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }


        $this->view->request   = $errors->request;
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

