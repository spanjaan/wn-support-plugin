<?php namespace SpAnjaan\Support\Components;

use Cms\Classes\ComponentBase;
use SpAnjaan\Support\Models\Ticket;
use SpAnjaan\Support\Models\TicketComment;
use Validator;
use Cms\Classes\Page;
use Flash;

/**
 * Class TicketStatus
 * @package SpAnjaan\Support\Components
 */
class TicketStatus extends ComponentBase
{

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'spanjaan.support::lang.components.ticketstatus.name',
            'description' => 'spanjaan.support::lang.components.ticketstatus.description',
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        return [
            'slug' => [
                'title'       => 'spanjaan.support::lang.app.hash',
                'description' => 'spanjaan.support::lang.app.hash_desc',
                'default'     => '{{ :hash }}',
                'type'        => 'string',
            ],
        ];
    }

    /**
     * Loads ticket selected with page slug
     */
    public function onRun()
    {
        $hash = $this->property('slug');
        $creator = \Auth::getUser();
        $ticket = Ticket::where('hash_id', $hash)->where('creator_id', $creator->id)->first();
        $this->page['ticket'] = $ticket;
        $this->page['ticket_comments'] = $ticket->comments;

        // Add TinyMCE script to the page
        $this->addJs('https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.4.1/tinymce.min.js');
    }

    /**
     * Adds comment to the ticket
     *
     * @throws \ValidationException
     */
    public function onAddComment()
    {
        $data = post();
        $this->validateComment($data);
        $hash = $this->property('slug');
    
        if (!$hash && array_key_exists('ticket_number', $data)) {
            $hash = $data['ticket_number'];
        }
    
        $ticket = Ticket::where('hash_id', $hash)->first();
        $author = $ticket->creator->first_name . ' ' . $ticket->creator->last_name;
        $authorEmail = ' (' . $ticket->creator->email . ')';
        $commentContent = $this->purifyComment($data['comment_content']);
    
        $comment = new TicketComment([
            'author'     => $author . $authorEmail,
            'is_support' => 0,
            'content'    => $commentContent,
        ]);
    
        $ticket->comments()->save($comment);

        Flash::success('Comment added successfully!');
    
        // Return the updated comments to the frontend
        return [
            '#commentsList' => $this->renderPartial('@_comments', ['ticket' => $ticket]),
        ];
    }
    

    /**
     * Validates comment input
     *
     * @param array $data
     *
     * @throws \ValidationException
     */
    private function validateComment($data)
    {
        $rules = [
            'comment_content' => 'required',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new \ValidationException($validator);
        }
    }

    /**
     * Cleans comment content to prevent unsafe HTML
     *
     * @param string $comment
     *
     * @return string
     */
    private function purifyComment($comment)
    {
        $purifierConfig = \HTMLPurifier_Config::createDefault();
        $purifier = new \HTMLPurifier($purifierConfig);

        return $purifier->purify($comment);
    }
}
