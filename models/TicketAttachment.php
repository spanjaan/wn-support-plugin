<?php namespace SpAnjaan\Support\Models;

use Model;
use Backend\Models\User;

/**
 * Class File
 * @package SpAnjaan\Support\Models
 */
class TicketAttachment extends Model
{

    /**
     * @var string
     */
    public $table = 'spanjaan_support_ticket_attachments';

    /**
     * Relations
     *
     * @var array
     */
    public $belongsTo = [
        'user'   => ['Backend\Models\User'],
        'ticket' => ['SpAnjaan\Support\Models\Ticket'],
    ];

    /**
     * @param User $user
     *
     * @return bool
     */
    public function canEdit(User $user)
    {
        return ($this->user_id == $user->id) || $user->hasAnyAccess(['spanjaan.support.access_uploaded_files']);
    }
}
