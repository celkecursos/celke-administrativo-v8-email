<?php

namespace App\Models;

use App\Models\Clms\CourseUser;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class UserCourseStatus extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    // Indicar o nome da tabela
    protected $table = 'user_course_statuses';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = ['name'];

    // Criar relacionamento entre um e muitos - Tabela com a chave primária
    public function courseUser()
    {
        return $this->hasMany(CourseUser::class);
    }
}
