<?php

namespace App\EventSubscriber;

use App\Entity\Relic;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class RelicWorkflowSubscriber implements EventSubscriberInterface
{
    private MailerInterface $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.relic_approval.completed.approve' => 'onRelicApproved',
            'workflow.relic_approval.completed.reject' => 'onRelicRejected',
        ];
    }

    public function onRelicApproved(Event $event): void
    {
        /** @var Relic $relic */
        $relic = $event->getSubject();
        $creator = $relic->getCreator();
        
        // Send notification email to the creator
        $email = (new Email())
            ->from('reliquary@example.com')
            ->to($creator->getEmail())
            ->subject('Your relic has been approved')
            ->html("<p>Your relic of {$relic->getSaint()->getName()} has been approved.</p>");
            
        $this->mailer->send($email);
    }

    public function onRelicRejected(Event $event): void
    {
        /** @var Relic $relic */
        $relic = $event->getSubject();
        $creator = $relic->getCreator();
        
        // Send notification email to the creator
        $email = (new Email())
            ->from('reliquary@example.com')
            ->to($creator->getEmail())
            ->subject('Your relic has been rejected')
            ->html("<p>Your relic of {$relic->getSaint()->getName()} has been rejected.</p>");
            
        $this->mailer->send($email);
    }
}