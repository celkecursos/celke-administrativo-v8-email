<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailAutomationAction extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    // Indicar o nome da tabela
    protected $table = 'email_automation_actions';

    // Indicar quais campos podem ser cadastrado
    protected $fillable = [
        'name',
        'is_recursive',
        'is_active',
    ];
}
