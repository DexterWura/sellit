<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'mail_config'       => 'object',
        'sms_config'        => 'object',
        'notice_for_buyer'  => 'object'
    ];

    public function scopeSitename($query, $pageTitle)
    {
        $pageTitle = empty($pageTitle) ? '' : ' - ' . $pageTitle;
        return $this->sitename . $pageTitle;
    }
}
