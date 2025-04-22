<?php namespace SpAnjaan\Support\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use SpAnjaan\Support\Models\TicketStatus;
use Lang;

/**
 * Ticket Statuses Back-end Controller
 */
class TicketStatuses extends Controller
{
    /**
     * @var array
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    /**
     * @var string
     */
    public $formConfig = 'config_form.yaml';
    /**
     * @var string
     */
    public $listConfig = [
        'ticket_statuses'   => 'config_list.yaml',
        'ticket_priorities' => 'config_priorities_list.yaml',
    ];

    /**
     * TicketStatuses constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('SpAnjaan.Support', 'support', 'ticketstatuses');
    }

    /**
     * Deletes checked ticket statuses.
     */
    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $ticketstatusId) {
                if (!$ticketstatus = TicketStatus::find($ticketstatusId)) {
                    continue;
                }
                $ticketstatus->delete();
            }

            Flash::success(Lang::get('spanjaan.support::lang.ticketstatuses.delete_selected_success'));
        } else {
            Flash::error(Lang::get('spanjaan.support::lang.ticketstatuses.delete_selected_empty'));
        }

        return $this->listRefresh();
    }
}