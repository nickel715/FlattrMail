<?php

namespace FlattrMail;

use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;

class Mail
{
    public function sendReport(array $flattrs, $recipient)
    {
        $message = new Message;
        $message->setBody($this->getBody($flattrs));
        $message->addTo($recipient);
        $message->setSubject(sprintf('You flattred %d thing(s)', count($flattrs)));

        $transport = new Sendmail;
        $transport->send($message);
    }

    public function getBody(array $flattrs)
    {
        $body  = 'You flattred the following thing(s):' . PHP_EOL . PHP_EOL;
        foreach ($flattrs as $flattr) {
            $body .= sprintf('%s (%s)%s', $flattr['thing']['url'], $flattr['thing']['owner']['username'], PHP_EOL);
        }
        return $body;
    }
}
