<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailAutomationTrigger extends Model
{
    public function action()
    {
        return $this->belongsTo(EmailAutomationAction::class, 'email_automation_action_id');
    }

    public function filterType()
    {
        return $this->belongsTo(EmailFilterType::class, 'email_filter_type_id');
    }

    public function actionType()
    {
        return $this->belongsTo(EmailActionType::class, 'email_action_type_id');
    }
}
