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
     * Relação: uma máquina pode ter várias sequências
     */
    public function emailMachineSequence()
    {
        return $this->hasMany(EmailMachineSequence::class);
    }

    /**
     * Relacionamento: cada e-mail pertence a uma sequência.
     */
    public function emailSequenceEmail()
{
    return $this->hasMany(EmailSequenceEmail::class, 'email_machine_sequence_id');
}
}
