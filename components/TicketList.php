<?php namespace SpAnjaan\Support\Components;

use Cms\Classes\ComponentBase;
use SpAnjaan\Support\Models\Settings;
use SpAnjaan\Support\Models\Ticket;

/**
 * Class TicketList
 * @package SpAnjaan\Support\Components
 */
class TicketList extends ComponentBase
{

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'spanjaan.support::lang.components.ticketlist.name',
            'description' => 'spanjaan.support::lang.components.ticketlist.description',
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
     * Loads users tickets
     */
    public function onRun()
    {
        $creator = \Auth::getUser();
        $url = Settings::get('address');
        $tickets = Ticket::where('creator_id', $creator->id)->get();

        $this->page['ticket_page'] = $url;
        $this->page['tickets'] = $tickets;
    }


}