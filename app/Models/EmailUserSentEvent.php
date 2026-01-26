<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailUserSentEvent extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'EmailUserSentEvent';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'event_at',
        'description',
        'log',
        'link_url',
        'email_user_sent_email_id',
        'email_type_event_id',
    ];

    /**
     * Casts de atributos.
     */
    protected $casts = [
        'event_at' => 'datetime',
    ];

    /**
     * Relacionamento com o e-mail enviado ao usuário.
     */
    public function emailUserSentEmail()
    {
        return $this->belongsTo(EmailUserSentEmail::class);
    }

    /**
     * Relacionamento com o tipo de evento do e-mail.
     */
    public function emailTypeEvent()
    {
        return $this->belongsTo(EmailTypeEvent::class);
    }
}
