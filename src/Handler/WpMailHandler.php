<?php

namespace vustech\Wplogger;
use Monolog\Logger;
use Monolog\NativeMailerHandler;

/**
 * Extends Monolog\NativeMailerHandler for wordpress used wp_mail
 */
class WpMailHandler extends NativeMailerHandler
{
    /* Override send method used wp_mail (WP) */
    protected function send($content, array $records) {
        $content = wordwrap($content, $this->maxColumnWidth);
        $headers = ltrim(implode("\r\n", $this->headers) . "\r\n", "\r\n");
        $headers .= 'Content-type: ' . $this->getContentType() . '; charset=' . $this->getEncoding() . "\r\n";
        if ($this->getContentType() == 'text/html' && false === strpos($headers, 'MIME-Version:')) {
            $headers .= 'MIME-Version: 1.0' . "\r\n";
        }

        $subject = $this->subject;
        if ($records) {
            $subjectFormatter = new LineFormatter($this->subject);
            $subject = $subjectFormatter->format($this->getHighestRecord($records));
        }
        foreach ($this->to as $to) {
            wp_mail($to, $subject, $content, $headers);
        }
    }
}