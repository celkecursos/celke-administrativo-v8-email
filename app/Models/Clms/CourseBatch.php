<?php

namespace App\Models\Clms;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CourseBatch extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    // Indicar o nome da tabela
    protected $table = 'course_batches';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'name', 
        'lesson_count',
        'certificate_content',
        'image',
        'course_id',
        'course_batch_status_id',
    ];

    // Criar relacionamento entre um e muitos inverso
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Criar relacionamento entre um e muitos - Tabela com a chave primária
    public function courseUser()
    {
        return $this->hasMany(CourseUser::class);
    }
}
