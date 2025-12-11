<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailAutomationTrigger extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'email_automation_triggers';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'email_automation_action_id',
        'email_filter_type_id',
        'email_action_type_id',
        'filter_email_machine_id',
        'filter_email_machine_sequence_id',
        'filter_email_sequence_email_id',
        'action_add_email_tag_id',
        'action_remove_email_tag_id',
        'action_add_email_sequence_email_id',
        'action_remove_email_sequence_email_id',
        'action_remove_email_machine_sequence_id',
        'is_active',
    ];

    /**
     * ---------------------------
     * Relationships (belongsTo)
     * ---------------------------
     */

    // Obrigatórios
    public function automationAction()
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

    /**
     * FILTROS (opcionais)
     */
    public function filterEmailMachine()
    {
        return $this->belongsTo(EmailMachine::class, 'filter_email_machine_id');
    }

    public function filterEmailMachineSequence()
    {
        return $this->belongsTo(EmailMachineSequence::class, 'filter_email_machine_sequence_id');
    }

    public function filterEmailSequenceEmail()
    {
        return $this->belongsTo(EmailSequenceEmail::class, 'filter_email_sequence_email_id');
    }

    /**
     * ACTIONS (opcionais)
     */
    public function actionAddEmailTag()
    {
        return $this->belongsTo(EmailTag::class, 'action_add_email_tag_id');
    }

    public function actionRemoveEmailTag()
    {
        return $this->belongsTo(EmailTag::class, 'action_remove_email_tag_id');
    }

    public function actionAddEmailSequenceEmail()
    {
        return $this->belongsTo(EmailSequenceEmail::class, 'action_add_email_sequence_email_id');
    }

    public function actionRemoveEmailSequenceEmail()
    {
        return $this->belongsTo(EmailSequenceEmail::class, 'action_remove_email_sequence_email_id');
    }

    public function actionRemoveEmailMachineSequence()
    {
        return $this->belongsTo(EmailMachineSequence::class, 'action_remove_email_machine_sequence_id');
    }
}
