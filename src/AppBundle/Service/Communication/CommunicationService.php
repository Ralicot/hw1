<?php

namespace AppBundle\Service\Communication;

class CommunicationService
{
    const ID = 'app.communication';

    private $emailService;

    public function __construct(EmailService $emailService)
    {
        $this->emailService = $emailService;
    }
    public function sendConfirmationEmail($emailAddress, $orderNumber)
    {
    }
    public function sendDeliveryEmail($emailAddress, $orderNumber)
    {
    }
    public function sendInvoice($emailAddress, $orderNumber)
    {
    }
    public function sendSatisfactionSurvey($emailAddress, $orderNumber)
    {
    }
}