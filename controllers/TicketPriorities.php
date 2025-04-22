<?php namespace SpAnjaan\Support\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use SpAnjaan\Support\Models\TicketPriority;
use Lang;

/**
 * Ticket Priorities Back-end Controller
 */
class TicketPriorities extends Controller
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
    public $listConfig = 'config_list.yaml';

    /**
     * TicketPriorities constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('SpAnjaan.Support', 'support', 'ticketpriorities');
    }

    /**
     * Deletes checked ticket priorities.
     */
    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $ticketpriorityId) {
                if (!$ticketpriority = TicketPriority::find($ticketpriorityId)) {
                    continue;
                }
                $ticketpriority->delete();
            }

            Flash::success(Lang::get('spanjaan.support::lang.ticketpriorities.delete_selected_success'));
        } else {
            Flash::error(Lang::get('spanjaan.support::lang.ticketpriorities.delete_selected_empty'));
        }

        return $this->listRefresh();
    }
}