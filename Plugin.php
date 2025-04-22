<?php namespace SpAnjaan\Support;

use System\Classes\PluginBase;
use Backend;

/**
 * Support Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Dependencies for this plugin
     * 
     * @var array
     */
    public $require = [
        'Winter.User',
        'Winter.Translate',
    ];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails(): array
    {
        return [
            'name'        => 'spanjaan.support::lang.plugin.name',
            'description' => 'spanjaan.support::lang.plugin.description',
            'author'      => 'spanjaan',
            'icon'        => 'icon-headset', 
        ];
    }

    /**
     * Registers plugin settings in the backend.
     *
     * @return array
     */
    public function registerSettings(): array
    {
        return [
            'settings' => [
                'label'       => 'spanjaan.support::lang.settings.label',
                'description' => 'spanjaan.support::lang.settings.description',
                'icon'        => 'icon-headset',
                'class'       => \SpAnjaan\Support\Models\Settings::class,
                'keywords'    => 'support, tickets',
                'permissions' => ['spanjaan.support.settings'],
                'order'       => 600,
            ],
        ];
    }

    /**
     * Registers the plugin components.
     *
     * @return array
     */
    public function registerComponents(): array
    {
        return [
            \SpAnjaan\Support\Components\TicketForm::class   => 'ticketForm',
            \SpAnjaan\Support\Components\TicketList::class   => 'ticketList',
            \SpAnjaan\Support\Components\TicketStatus::class => 'ticketStatus',
            \SpAnjaan\Support\Components\Upload::class       => 'ticketAttach',
        ];
    }

    /**
     * Registers mail templates for the plugin.
     *
     * @return array
     */
    public function registerMailTemplates(): array
    {
        return [
            'spanjaan.support::mail.ticket.first'  => trans('spanjaan.support::lang.mailer.first'),
            'spanjaan.support::mail.ticket.create' => trans('spanjaan.support::lang.mailer.create'),
            'spanjaan.support::mail.ticket.update' => trans('spanjaan.support::lang.mailer.update'),
            'spanjaan.support::mail.ticket.close'  => trans('spanjaan.support::lang.mailer.close'),
        ];
    }

    /**
     * Registers the backend navigation for the plugin.
     *
     * @return array
     */
    public function registerNavigation(): array
    {
        return [
            'support' => [
                'label'    => 'Support',
                'url'      => Backend::url('spanjaan/support/tickets'),
                'icon'     => 'icon-headset',
                'order'    => 500,
                'sideMenu' => [
                    'tickets' => [
                        'label'       => 'spanjaan.support::lang.app.tickets',
                        'url'         => Backend::url('spanjaan/support/tickets'),
                        'permissions' => ['spanjaan.support.tickets'],
                        'icon'        => 'icon-ticket-alt',
                    ],
                    'ticketattachments' => [
                        'label'       => 'spanjaan.support::lang.app.ticketattachments',
                        'url'         => Backend::url('spanjaan/support/ticketattachments'),
                        'permissions' => ['spanjaan.support.statuses'],
                        'icon'        => 'icon-paperclip',
                    ],
                    'ticketcategories' => [
                        'label'       => 'spanjaan.support::lang.app.ticketcategories',
                        'url'         => Backend::url('spanjaan/support/ticketcategories'),
                        'permissions' => ['spanjaan.support.categories'],
                        'icon'        => 'icon-folder-open',
                    ],
                    'ticketstatuses' => [
                        'label'       => 'spanjaan.support::lang.app.ticketstatusesandpriorities',
                        'url'         => Backend::url('spanjaan/support/ticketstatuses'),
                        'permissions' => ['spanjaan.support.statuses'],
                        'icon'        => 'icon-flag-checkered',
                    ],
                ],
            ],
        ];
    }

    /**
     * Registers permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions(): array
    {
        return [
            'spanjaan.support.tickets' => [
                'tab'   => 'spanjaan.support::lang.plugin.name',
                'label' => 'spanjaan.support::lang.permissions.tickets',
            ],
            'spanjaan.support.categories' => [
                'tab'   => 'spanjaan.support::lang.plugin.name',
                'label' => 'spanjaan.support::lang.permissions.categories',
            ],
            'spanjaan.support.statuses' => [
                'tab'   => 'spanjaan.support::lang.plugin.name',
                'label' => 'spanjaan.support::lang.permissions.statuses',
            ],
            'spanjaan.support.priorities' => [
                'tab'   => 'spanjaan.support::lang.plugin.name',
                'label' => 'spanjaan.support::lang.permissions.priorities',
            ],
            'spanjaan.support.settings' => [
                'tab'   => 'spanjaan.support::lang.plugin.name',
                'label' => 'spanjaan.support::lang.permissions.settings',
            ],
        ];
    }
}
