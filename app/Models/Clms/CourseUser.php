<?php

namespace App\Models\Clms;

use App\Models\User;
use App\Models\UserCourseStatus;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CourseUser extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    
    // Indicar o nome da tabela
    protected $table = 'course_users';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'access_start_date',
        'access_end_date',
        'amount_paid',
        'transaction_number',
        'lessons_viewed_count',
        'user_course_status_id',
        'payment_method_id',
        'course_batch_id',
        'user_id',
    ];

    // Indicar que campos devem ser tratados como datas/Carbon
    protected $casts = [
        'access_start_date' => 'datetime',
        'access_end_date' => 'datetime',
        'amount_paid' => 'decimal:2',
        'lessons_viewed_count' => 'integer',
    ];

    // Criar relacionamento entre um e muitos - Tabela com a chave estrangeira
    public function userCourseStatus()
    {
        return $this->belongsTo(UserCourseStatus::class);
    }

    // Criar relacionamento entre um e muitos - Tabela com a chave estrangeira
    public function courseBatch()
    {
        return $this->belongsTo(CourseBatch::class);
    }

    // Criar relacionamento entre um e muitos - Tabela com a chave estrangeira
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
