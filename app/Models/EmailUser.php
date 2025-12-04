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
        'user_id',
        'email_sequence_email_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relacionamento: cada registro pertence a um usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: cada registro pertence a um e-mail da sequência
     */
    public function emailSequenceEmail()
    {
        return $this->belongsTo(EmailSequenceEmail::class);
    }
}
