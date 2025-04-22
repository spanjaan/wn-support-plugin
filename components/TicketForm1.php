<?php namespace SpAnjaan\Support\Components;

use Cms\Classes\ComponentBase;
use SpAnjaan\Support\Classes\SupportHelpers;
use SpAnjaan\Support\Classes\SupportMailer;
use SpAnjaan\Support\Models\Settings;
use SpAnjaan\Support\Models\Ticket;
use SpAnjaan\Support\Models\TicketCategory;
use SpAnjaan\Support\Models\TicketStatus;
use Illuminate\Support\Facades\Cache;

/**
 * Class TicketForm
 * @package SpAnjaan\Support\Components
 */
class TicketForm extends ComponentBase
{
    /**
     * @var SupportHelpers
     */
    private SupportHelpers $helpers;

    /**
     * @var SupportMailer
     */
    private SupportMailer $mailer;

    /**
     * TicketForm constructor.
     *
     * @param \Cms\Classes\CodeBase|null $cmsObject
     * @param array $properties
     */
    public function __construct(\Cms\Classes\CodeBase $cmsObject = null, array $properties = [])
    {
        parent::__construct($cmsObject, $properties);
        $this->helpers = new SupportHelpers();
        $this->mailer = new SupportMailer(); 
    }

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'spanjaan.support::lang.components.ticketform.name',
            'description' => 'spanjaan.support::lang.components.ticketform.description',
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        return [];
    }

    /**
     * Loads categories for ticket form
     */
    public function onRun()
    {  
        $this->page['categories'] = TicketCategory::pluck('id', 'name');
    }

    /**
     * Creates a new Ticket and redirects to its page
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \ValidationException
     */
    public function onTicketCreate(): \Illuminate\Http\RedirectResponse
    {
        $data = post();
        $this->helpers->validateTicket($data);
        
        $creator = \Auth::getUser();

        $ticketPage = Cache::remember('settings_address', 60, fn() => Settings::get('address'));
        $newStatus = Cache::remember('ticket_status_new_id', 60, fn() => TicketStatus::where('name', 'New')->value('id'));

        $content = $this->purifyTicket($data['content']);
        $ticket = new Ticket([
            'hash_id'      => 'temp', 
            'category_id'  => $data['category'],
            'creator_id'   => $creator->id,
            'email'        => $creator->email,
            'website'      => $data['website'],
            'topic'        => $data['topic'],
            'content'      => $content,
            'status'       => $newStatus,
        ]);


        $ticket->hash_id = $this->helpers->generateHashId($ticket->id);
        $ticket->save();

        $this->page['hash_id'] = $ticket->hash_id;
        $this->helpers->newTicketHandler($ticket->hash_id);
        $vars = [
            'ticket_number' => $ticket->hash_id,
            'ticket_link'   => $ticketPage . $ticket->hash_id,
        ];

        $this->mailer->sendAfterTicketCreated($creator->email, $vars);

        return \Redirect::to($ticketPage . $ticket->hash_id);
    }

    /**
     * Purifies ticket content from bad HTML
     *
     * @param string $content
     *
     * @return string
     */
    private function purifyTicket(string $content): string
    {
        $purifierConfig = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($purifierConfig);

        return $purifier->purify($content);
    }
}
