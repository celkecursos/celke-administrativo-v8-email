<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailMachine extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'email_machines';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relação: uma máquina pode ter várias sequências
     */
    public function sequences()
    {
        return $this->hasMany(EmailMachineSequence::class, 'email_machine_id');
    }
}
