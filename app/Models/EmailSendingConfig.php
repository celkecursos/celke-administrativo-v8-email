<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use OwenIt\Auditing\Contracts\Auditable;

class EmailSendingConfig extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    // Indicar o nome da tabela
    protected $table = 'email_sending_configs';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'provider',
        'api_user',
        'api_key',
        'host',
        'username',
        'password',
        'from_email',
        'from_name',
        'send_quantity_per_request',
        'send_quantity_per_hour',
        'is_active',
        'is_active_marketing',
        'is_active_transactional',
    ];

    protected $hidden = ['password'];

    /**
     * Casts de atributos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'send_quantity_per_request' => 'integer',
        'send_quantity_per_hour' => 'integer',
    ];

    // Criptografar ao salvar
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Crypt::encryptString($value);
    }

    // Descriptografar ao recuperar
    public function getPasswordAttribute($value)
    {
        return Crypt::decryptString($value);
    }
}
