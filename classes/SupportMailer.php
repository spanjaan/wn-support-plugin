<?php

namespace SpAnjaan\Support\Classes;

use Mail;
use InvalidArgumentException;

class SupportMailer
{
    /**
     * Template mappings for different ticket events.
     */
    private const TEMPLATES = [
        'first' => 'spanjaan.support::mail.ticket.first',
        'create' => 'spanjaan.support::mail.ticket.create',
        'update' => 'spanjaan.support::mail.ticket.update',
        'close' => 'spanjaan.support::mail.ticket.close',
    ];

    /**
     * Sends an email to the user based on a specific ticket action.
     *
     * @param string $email
     * @param string $action
     * @param array  $vars
     *
     * @throws InvalidArgumentException
     */
    private function sendEmail(string $email, string $action, array $vars): void
    {
        if (empty($email)) {
            throw new InvalidArgumentException('Email address cannot be empty.');
        }

        $template = self::TEMPLATES[$action] ?? null;

        if (!$template) {
            throw new InvalidArgumentException("Invalid email action: $action");
        }

        Mail::queue($template, $vars, function($message) use ($email) {
            $message->to($email);
        });
    }

    /**
     * Public methods to send emails for specific ticket actions.
     */
    public function sendAfterFirstTicket(string $email, array $vars): void
    {
        $this->sendEmail($email, 'first', $vars);
    }

    public function sendAfterTicketCreated(string $email, array $vars): void
    {
        $this->sendEmail($email, 'create', $vars);
    }

    public function sendAfterTicketUpdated(string $email, array $vars): void
    {
        $this->sendEmail($email, 'update', $vars);
    }

    public function sendAfterTicketClosed(string $email, array $vars): void
    {
        $this->sendEmail($email, 'close', $vars);
    }
}
