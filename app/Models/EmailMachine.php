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
}
