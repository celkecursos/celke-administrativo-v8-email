<?php

namespace App\Models\Clms;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Support\Facades\Storage;

class Course extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    // Indicar o nome da tabela
    protected $table = 'courses';

    // Indicar quais colunas podem ser manipuladas
    protected $fillable = [
        'name',
        'subtitle',
        'content',
        'image',
        'menu_situation',
        'workload',
        'certified_status',
        'description',
        'keywords',
        'slug',
        'price',
        'registration_link',
        'hotmart_product_identifier',
        'order',
        'course_category_id',
        'course_status_id',
        'created_at',
        'updated_at',
    ];

    // Relacionamento com turmas do curso (course_batches)
    public function courseBatches()
    {
        return $this->hasMany(CourseBatch::class);
    }
}
