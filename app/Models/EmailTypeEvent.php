<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailTypeEvent extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'colors';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Relacionamento com o evento no e-email enviado.
     */
    public function emailUserSentEvent()
    {
        return $this->hasMany(EmailUserSentEvent::class);
    }
}
