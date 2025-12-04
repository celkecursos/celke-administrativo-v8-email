<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailUser extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'email_users';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'is_active',
        'user_id',
        'email_sequence_email_id',
    ];

    /**
     * Relacionamento com o usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o e-mail da sequência.
     */
    public function emailSequenceEmail()
    {
        return $this->belongsTo(EmailSequenceEmail::class);
    }
}
