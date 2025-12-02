<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailSequenceEmail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'email_sequence_emails';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'title',
        'content',
        'is_active',
        'skip_email',
        'delay_day',
        'delay_hour',
        'delay_minute',
        'fixed_send_datetime',
        'use_fixed_send_datetime',
        'send_window_start_hour',
        'send_window_start_minute',
        'send_window_end_hour',
        'send_window_end_minute',
        'email_machine_sequence_id',
    ];

    /**
     * Casts de atributos.
     */
    protected $casts = [
        'is_active' => 'boolean',
        'skip_email' => 'boolean',
        'use_fixed_send_datetime' => 'boolean',
        'fixed_send_datetime' => 'datetime',
    ];

    /**
     * Relacionamento: Este e-mail pertence a uma sequência.
     */
    public function emailMachineSequence()
    {
        return $this->belongsTo(EmailMachineSequence::class);
    }
}
