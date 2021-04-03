<?php

namespace App\Services;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class MailerService
{
    /**
     * @var string
     */
    private $smtpHost;

    /**
     * @var string
     */
    private $smtpPort;

    /**
     * @var string
     */
    private $smtpEncription;

    /**
     * @var string
     */
    private $smtpUserName;

    /**
     * @var string
     */
    private $smtpUserPass;

    /**
     * @param string $smtpHost
     * @param string $smtpPort
     * @param string $smtpEncription
     * @param string $smtpUserName
     * @param string $smtpUserPass
     */
    public function __construct(
        string $smtpHost,
        string $smtpPort,
        string $smtpEncription,
        string $smtpUserName,
        string $smtpUserPass
    ) {
        $this->smtpHost = $smtpHost;
        $this->smtpPort = $smtpPort;
        $this->smtpEncription = $smtpEncription;
        $this->smtpUserName = $smtpUserName;
        $this->smtpUserPass = $smtpUserPass;
    }

    /**
     * @param string $messageSubject
     * @param string $messageBody
     * @param array $recipientEmailAddress
     *
     * @return void
     */
    public function sendEmail(
        string $messageSubject,
        string $messageBody,
        array $recipientEmailAddress
    ): void {
        $transport = $this->getSmtpTransport();
        $mailer = new Swift_Mailer($transport);
        $mail = (new Swift_Message($messageSubject))
            ->setFrom($transport->getUsername())
            ->setTo($recipientEmailAddress)
            ->setBody($messageBody);
        $mailer->send($mail);
    }

    /**
     * @return Swift_SmtpTransport
     */
    private function getSmtpTransport(): Swift_SmtpTransport
    {
        return (new Swift_SmtpTransport($this->smtpHost, $this->smtpPort, $this->smtpEncription))
            ->setUsername($this->smtpUserName)
            ->setPassword($this->smtpUserPass);
    }
}
