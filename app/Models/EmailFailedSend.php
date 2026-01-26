<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailFailedSend extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'email_failed_sends';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'user_id',
        'email_sequence_email_id',
        'message',
    ];

    /**
     * Usuário relacionado à falha no envio do e-mail.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * E-mail da sequência relacionado à falha de envio.
     */
    public function emailSequenceEmail()
    {
        return $this->belongsTo(EmailSequenceEmail::class);
    }
}