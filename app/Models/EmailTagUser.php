<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailTagUser extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'email_tag_users';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'user_id',
        'email_tag_id',
    ];

    /**
     * Relacionamento com o usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com a tag de e-mail.
     */
    public function emailTag()
    {
        return $this->belongsTo(EmailTag::class);
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'email_tag_users',
            'email_tag_id',
            'user_id'
        )->withTimestamps();
    }
}
