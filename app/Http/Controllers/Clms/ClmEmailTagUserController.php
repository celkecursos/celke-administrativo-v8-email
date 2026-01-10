<?php

namespace App\Http\Controllers\Clms;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Clms\Course;
use App\Models\EmailTag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClmEmailTagUserController extends Controller
{
    // Listar os usuários
    public function index(Request $request)
    {
        // Recuperar os registros do banco dados
        $users = User::when(
            $request->filled('course_id'),
            function ($query) use ($request) {
                $query->whereHas('courseUsers.courseBatch', function ($q) use ($request) {
                    $q->where('course_id', $request->course_id);
                });
            }
        )
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        // Recuperar os cursos
        $courses = Course::orderBy('name', 'ASC')->get();

        // Recuperar as tags
        $emailTags = EmailTag::where('is_active', true)->orderBy('name', 'ASC')->get();

        // Salvar log
        Log::info('Listar os usuários.', ['action_user_id' => Auth::id()]);

        // Carregar a view 
        return view('clm-email-tag-users.index', [
            'menu' => 'clms-email-tag-users',
            'course_id' => $request->course_id,
            'users' => $users,
            'courses' => $courses,
            'emailTags' => $emailTags,
        ]);
    }

    // Atribuir tag para os usuários
    public function store(Request $request)
    {
        $request->validate(
            [
                'course_id'     => 'required|exists:courses,id',
                'email_tag_id'  => 'required|exists:email_tags,id',
            ],
            [
                'course_id.required'    => 'O curso é obrigatório.',
                'course_id.exists'      => 'O curso selecionado não é válido.',

                'email_tag_id.required' => 'É obrigatório selecionar uma tag.',
                'email_tag_id.exists'   => 'A tag selecionada não é válida.',
            ]
        );

        $courseId   = $request->course_id;
        $emailTagId = $request->email_tag_id;

        // Processar em blocos de 500
        User::whereHas('courseUsers.courseBatch', function ($q) use ($courseId) {
            $q->where('course_id', $courseId);
        })
            ->orderBy('id')
            ->chunk(500, function ($users) use ($emailTagId) {

                $now = now();

                $insertData = [];

                foreach ($users as $user) {
                    // Verifica se já existe a tag para o usuário
                    $exists = DB::table('email_tag_users')
                        ->where('user_id', $user->id)
                        ->where('email_tag_id', $emailTagId)
                        ->exists();

                    if (! $exists) {
                        $insertData[] = [
                            'user_id'       => $user->id,
                            'email_tag_id'  => $emailTagId,
                            'created_at'    => $now,
                            'updated_at'    => $now,
                        ];
                    }
                }

                // Inserção em lote (muito mais performático)
                if (! empty($insertData)) {
                    DB::table('email_tag_users')->insert($insertData);
                }
            });

        return redirect()
            ->back()
            ->with('success', 'Tag atribuída com sucesso aos usuários do curso.');
    }
}
