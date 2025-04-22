<?php namespace SpAnjaan\Support\Components;

use Cms\Classes\ComponentBase;
use Auth;
use Cache;
use Redirect;
use SpAnjaan\Support\Classes\SupportHelpers;
use SpAnjaan\Support\Classes\SupportMailer;
use SpAnjaan\Support\Models\Ticket;
use SpAnjaan\Support\Models\TicketCategory;
use SpAnjaan\Support\Models\TicketStatus;
use HTMLPurifier;
use HTMLPurifier_Config;
use ValidationException;
use Illuminate\Http\RedirectResponse;

class TicketForm extends ComponentBase
{
    private SupportHelpers $helpers;
    private SupportMailer $mailer;
    private HTMLPurifier $purifier;

    private const CACHE_DURATION = 300;
    private const TICKET_BASE_URL = 'ticket/';

    public function __construct($cmsObject = null, array $properties = [])
    {
        parent::__construct($cmsObject, $properties);
        $this->initializeHelpers();
    }

    public function componentDetails(): array
    {
        return [
            'name'        => 'spanjaan.support::lang.components.ticketform.name',
            'description' => 'spanjaan.support::lang.components.ticketform.description',
        ];
    }

    public function defineProperties(): array
    {
        return [];
    }

    public function onRun(): void
    {
        $this->page['categories'] = $this->getCachedCategories();
    }

    public function onTicketCreate(): RedirectResponse
    {
        $data = post();
        $creator = Auth::getUser();

        if (!$creator) {
            throw new ValidationException(['user' => 'You must be logged in to create a ticket.']);
        }

        $this->helpers->validateTicket($data);

        $ticket = $this->createTicket($data, $creator);

        $this->sendTicketCreatedNotification($creator->email, $ticket->hash_id);

        return Redirect::to(self::TICKET_BASE_URL . $ticket->hash_id);
    }

    private function initializeHelpers(): void
    {
        $this->helpers = new SupportHelpers();
        $this->mailer = new SupportMailer();
        $this->purifier = $this->initializePurifier();
    }

    private function initializePurifier(): HTMLPurifier
    {
        $config = HTMLPurifier_Config::createDefault();
        return new HTMLPurifier($config);
    }

    private function getCachedCategories(): array
    {
        return Cache::remember('ticket_categories', self::CACHE_DURATION, function () {
            return TicketCategory::pluck('name', 'id')->all();
        });
    }

    private function createTicket(array $data, $creator): Ticket
    {
        $newStatusId = $this->getNewStatusId();
        $content = $this->purifyTicketContent($data['content']);

        $ticket = Ticket::create([
            'hash_id'      => 'temp',
            'category_id'  => $data['category'],
            'creator_id'   => $creator->id,
            'email'        => $creator->email,
            'website'      => $data['website'] ?? null,
            'topic'        => $data['topic'],
            'content'      => $content,
            'status_id'    => $newStatusId,
        ]);

        $ticket->update(['hash_id' => $this->helpers->generateHashId($ticket->id)]);
        $this->helpers->newTicketHandler($ticket->hash_id);

        return $ticket;
    }

    private function getNewStatusId(): int
    {
        return Cache::remember('ticket_status_new_id', self::CACHE_DURATION, fn() => TicketStatus::where('name', 'New')->value('id'));
    }

    private function purifyTicketContent(string $content): string
    {
        return $this->purifier->purify($content);
    }

    private function sendTicketCreatedNotification(string $email, string $hashId): void
    {
        $vars = [
            'ticket_number' => $hashId,
            'ticket_link'   => self::TICKET_BASE_URL . $hashId,
        ];
        $this->mailer->sendAfterTicketCreated($email, $vars);
    }
}
