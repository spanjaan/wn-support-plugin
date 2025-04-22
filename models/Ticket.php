<?php namespace SpAnjaan\Support\Models;

use Backend\Models\User;
use SpAnjaan\Support\Classes\SupportHelpers;
use SpAnjaan\Support\Classes\SupportMailer;
use Model;
use Exception;

/**
 * Ticket Model
 */
class Ticket extends Model
{
    public $table = 'spanjaan_support_tickets';

    protected $guarded = [];
    protected $fillable = ['hash_id', 'category_id', 'creator_id', 'email', 'website', 'topic', 'content', 'status_id', 'user_id'];
    
    public $hasMany = [
        'files' => ['SpAnjaan\Support\Models\TicketAttachment'],
    ];

    public $belongsTo = [
        'category' => 'SpAnjaan\Support\Models\TicketCategory',
        'priority' => 'SpAnjaan\Support\Models\TicketPriority',
        'status'   => 'SpAnjaan\Support\Models\TicketStatus',
        'creator'  => 'Winter\User\Models\User',
        'user'     => 'Backend\Models\User',
    ];

    public $belongsToMany = [
        'comments' => [
            'SpAnjaan\Support\Models\TicketComment',
            'table'    => 'spanjaan_support_ticket_ticket_comment',
            'key'      => 'ticket_id',
            'otherKey' => 'comment_id',
        ],
    ];

    // Caching commonly used TicketStatus IDs
    protected static $newStatusId;
    protected static $assignedStatusId;

    /**
     * Initialize status IDs.
     */
    public static function boot()
    {
        parent::boot();
        self::$newStatusId = TicketStatus::where('name', 'New')->value('id');
        self::$assignedStatusId = TicketStatus::where('name', 'Assigned')->value('id');
    }

    /**
     * Provides user list for assignation.
     */
    public function getUserOptions()
    {
        return cache()->remember('user_email_options', 60, function () {
            return User::pluck('email', 'id')->toArray();
        });
    }

    /**
     * Sets status to 'Assigned' if a user is assigned while status is 'New'.
     */
    protected function assignUserIfNew()
    {
        if ($this->user_id && $this->status_id == self::$newStatusId) {
            $this->status_id = self::$assignedStatusId;
        }
    }

    /**
     * Before create method.
     */
    public function beforeCreate()
    {
        $this->assignUserIfNew();
        $this->hash_id = 'invalid';
    }

    /**
     * After create method.
     */
    public function afterCreate()
    {
        if ($this->hash_id === 'invalid') {
            $this->hash_id = (new SupportHelpers())->generateHashId($this->id);
            $this->save();
        }
    }

    /**
     * Before update method.
     */
    public function beforeUpdate()
    {
        $this->assignUserIfNew();
    }

    /**
     * Sends email notifications after update.
     */
    public function afterUpdate()
    {
        $this->sendStatusChangeNotification();
    }

    /**
     * Sends status change notifications to the creator.
     */
    protected function sendStatusChangeNotification()
    {
        $mailer = new SupportMailer();
        $email = $this->creator->email ?? null;
        $address = Settings::get('address') ?? '';

        if (!$email) {
            throw new Exception("Email for the ticket creator is not available.");
        }

        $statusName = $this->status->name ?? 'Unknown';
        $vars = [
            'ticket_number' => $this->hash_id,
            'ticket_link'   => "{$address}/{$this->hash_id}",
            'ticket_status' => $statusName,
        ];

        if (in_array($statusName, ['Closed', 'Resolved'])) {
            $mailer->sendAfterTicketClosed($email, $vars);
        } elseif ($this->is_support) {
            $mailer->sendAfterTicketUpdated($email, $vars);
        }
    }
}
