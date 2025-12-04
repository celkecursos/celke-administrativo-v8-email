<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class EmailTag extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'email_tags';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'name',
        'is_active',
    ];

    /**
     * Casts de atributos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Criar relacionamento entre um e muitos - Tabela com a chave primária
    public function emailTagUser()
    {
        return $this->hasMany(EmailTagUser::class);
    }
}
