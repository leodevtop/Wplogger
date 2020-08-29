<?php

namespace Vustech\Wplogger;

use Monolog\Handler\StreamHandler;
use Vustech\Wplogger\Handler\WpMailHandler;

/**
 * Libraries that enables logging capabilities for theme and/or plugin Wordpress development
 */
class Logger extends \Monolog\Logger
{
    $name = 'vus_logger';

    $logToFile = false;
    $path = '';
    $fileName = 'vuslogger.log';
    $wpMailHandler = [
        'to'      => get_option('admin_email'),
        'subject' => 'An Error on the site "'.get_option('bname').'" has been detected.',
        'from'    => get_option('admin_email')
    ];

    $sendMail = false;
    $sendMailTo = 'admin@domain.com';
    $sendMailFrom = 'admin@domain.com';
    $sendMailSubject = 'An Error on you website ư has been detected.';

    function __construct($config = []) {
        /* sendMail with WP default */
        if(defined(ABSPATH)) {
            $this->path = ABSPATH;
            $this->sendMailTo = sanitize_email(get_option('admin_email'));
            $this->sendMailFrom = sanitize_email(get_option('admin_email'));
            $this->sendMailSubject = 'An Error on the site "'.get_option('blogname').'" has been detected.';
        }
        // Configures an object with the initial property values
        if (!empty($config)) {
            foreach ($config as $k => $v) {
                if(isset($this->$k)) {
                    $this->$k = $v;
                }
            }
        }
        $handlers = [new StreamHandler('php://stdout')];

        if($this->logToFile) {
            $this->path = $this->path? : dirname(__FILE__) . '/';
            $handlers[] = new StreamHandler($this->path . $this->fileName);
        }
        if($this->sendMail) {
            $handlers[] = new WpMailHandler($this->sendMailTo, $this->sendMailSubject, $this->sendMailFrom);
        }

        parent::__construct($this->name, $handlers);
    }

}