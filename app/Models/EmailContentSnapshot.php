<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailContentSnapshot extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'email_content_snapshots';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'subject',
        'body_html',
        'body_text',
        'type',
    ];

    // Relacionamento com a tabela e-mails enviados
    public function emailContentSnapshot()
    {
        return $this->hasMany(EmailContentSnapshot::class);
    }
}
