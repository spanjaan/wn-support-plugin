<?php namespace SpAnjaan\Support\Controllers;

use Flash;
use BackendMenu;
use BackendAuth;
use SpAnjaan\Support\Models\TicketAttachment;
use Backend\Classes\Controller;

/**
 * Class TicketAttachments
 * @package SpAnjaan\Support\Controllers
 */
class TicketAttachments extends Controller
{
    /**
     * @var array
     */
    public $implement = [
        //'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    //public $formConfig = 'config_form.yaml'; //todo - probably?
    /**
     * @var string
     */
    public $listConfig = 'config_list.yaml';

    /**
     * @var string
     */
    public $bodyClass = 'compact-container';

    /**
     * @var array
     */
    public $requiredPermissions = ['spanjaan.support.attachments'];

    /**
     * Files constructor.
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('SpAnjaan.Support', 'support', 'ticketattachments');

    }

    /**
     * Controller index
     */
    public function index()
    {
        $user = BackendAuth::getUser();
        if (isset($user) && !empty($user)) {
            $uid = $user->id;
        } else {
            $uid = 0;
        }

        $file = new TicketAttachment;
        if (!$this->user->hasAnyAccess(['spanjaan.support.attachments'])) {
            $this->vars['total'] = $file->where('user_id', $uid)->count();
        } else {
            $this->vars['total'] = TicketAttachment::count();
        }

        $this->asExtension('ListController')->index();
    }

    /**
     * @param $query
     */
    public function listExtendQuery($query)
    {
        if (!$this->user->hasAnyAccess(['spanjaan.support.attachments'])) {
            $query->where('user_id', $this->user->id);
        }
    }

    /**
     * @return mixed
     */
    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            $allvalues = array();
            foreach ($checkedIds as $postId) {
                if ((!$file = TicketAttachment::find($postId)) || !$file->canEdit($this->user)) {
                    continue;
                }
                $unlink = unlink($_SERVER['DOCUMENT_ROOT'].$file->file_path);
                $allvalues[] = $unlink;
                if ($unlink) {
                    $file->delete();
                }
            }

            if (count(array_unique($allvalues)) === 1 && end($allvalues) === true) {
                Flash::success('Successfully deleted those files.');
            } else {
                Flash::error('Some files could not be deleted.');
            }
        }

        return $this->listRefresh();
    }

}