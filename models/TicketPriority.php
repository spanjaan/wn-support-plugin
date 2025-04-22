<?php namespace SpAnjaan\Support\Models;

use Model;

/**
 * TicketPriority Model
 */
class TicketPriority extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'spanjaan_support_ticket_priorities';

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