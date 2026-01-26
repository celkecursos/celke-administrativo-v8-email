<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailUserSentEmail extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'email_user_sent_emails';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'from_email',
        'to_email',
        'user_id',
        'email_content_snapshot_id',
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
     * Relacionamento com o snapshot do conteúdo do e-mail.
     */
    public function emailContentSnapshot()
    {
        return $this->belongsTo(EmailContentSnapshot::class);
    }

    /**
     * Relacionamento com o e-mail da sequência.
     */
    public function emailSequenceEmail()
    {
        return $this->belongsTo(EmailSequenceEmail::class);
    }    

    /**
     * Relacionamento com o evento no e-email enviado.
     */
    public function emailUserSentEvent()
    {
        return $this->hasMany(EmailUserSentEvent::class);
    }
}