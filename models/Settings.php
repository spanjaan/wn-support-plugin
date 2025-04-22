<?php namespace SpAnjaan\Support\Models;

use Illuminate\Support\Facades\Cache;
use Winter\Storm\Database\Model;


/**
 * Class Settings
 *
 * @package SpAnjaan\Support\Models
 */
class Settings extends Model
{

    public $implement = ['System.Behaviors.SettingsModel'];

    protected $guarded = ['*'];

    protected $fillable = ['address'];

    public $settingsCode = 'spanjaan::support.settings';

    public $settingsFields = 'fields.yaml';

}