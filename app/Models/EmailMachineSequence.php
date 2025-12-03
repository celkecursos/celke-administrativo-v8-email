<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailMachineSequence extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'email_machine_sequences';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'email_machine_id',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * Relação: cada sequência pertence a uma máquina
     */
    public function emailMachine()
    {
        return $this->belongsTo(EmailMachine::class, 'email_machine_id');
    }

    /**
     * Relacionamento: uma sequência pode ter vários e-mails
     */
    public function emails()
    {
        return $this->hasMany(EmailSequenceEmail::class, 'email_machine_sequence_id');
    }
}
