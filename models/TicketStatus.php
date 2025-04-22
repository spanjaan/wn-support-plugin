<?php namespace SpAnjaan\Support\Models;

use Model;

/**
 * TicketStatus Model
 */
class TicketStatus extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'spanjaan_support_ticket_statuses';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'tickets' => 'SpAnjaan\Support\Models\Ticket',
    ];


}