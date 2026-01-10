<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Clms\CourseUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf',
        'alias',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Chave estrangeira - Criar relacionamento entre usuário e status
    public function userStatus()
    {
        return $this->belongsTo(UserStatus::class);
    }

    // Criar relacionamento entre um e muitos - Tabela com a chave primária
    public function emailUser()
    {
        return $this->hasMany(EmailUser::class);
    }

    // Criar relacionamento entre um e muitos - Tabela com a chave primária
    public function emailTagUser()
    {
        return $this->hasMany(EmailTagUser::class);
    }

    // Relacionamento many-to-many com tags através da tabela pivot email_tag_users
    public function emailTags()
    {
        return $this->belongsToMany(
            EmailTag::class,
            'email_tag_users',
            'user_id',
            'email_tag_id'
        )->withTimestamps();
    }

    // Usar esse relacionamento no gerenciado de e-mail do CLMS v8
    public function courseUsers()
    {
        return $this->hasMany(CourseUser::class);
    }

    // Formatar o CPF para imprimir na VIEW
    public function getCpfFormattedAttribute()
    {
        if (!$this->cpf || strlen($this->cpf) !== 11) {
            return $this->cpf;
        }

        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->cpf);
    }

    // Accessor para retornar a imagem ou uma imagem padrão
    public function getImageUrlAttribute()
    {
        /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
        $disk = Storage::disk('s3');

        // Se não tiver imagem, usa padrão
        if (!$this->image) {
            return asset('/images/users/user.png');
        }

        // Caminho da imagem
        $path = 'users/' . $this->id . '/' . $this->image;

        // Se não encontrar a imagem no S3, carrega a imagem padrão
        if ($disk->exists($path)) {
            /** @var \Illuminate\Filesystem\AwsS3V3Adapter|\Illuminate\Contracts\Filesystem\Cloud $disk */
            return $disk->temporaryUrl($path, now()->addMinutes(30));
        } else {
            // Caminho alternativo, como imagem padrão
            return asset('/images/users/user.png');
        }
    }
}
