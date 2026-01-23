<?php

namespace App\Jobs;

use App\Models\EmailUser;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;
use League\Csv\Statement;
use SplFileObject;
use Illuminate\Support\Facades\Storage;

class BlockedEmailImportJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Quantidade de registros por job
     */
    private const CHUNK_SIZE = 100;

    public function __construct(
        protected string $filePath,
        protected int $emailSequenceEmailId,
        protected int $userStatusId,
        protected int $isActive,
        protected int $offset = 0 // controle de progresso
    ) {}

    public function handle(): void
    {
        try {
            // $filePath = storage_path('app/private/' . $this->filePath);
            // $filePath = storage_path('app/' . $this->filePath);
            $filePath = Storage::disk('local')->path($this->filePath);

            if (!file_exists($filePath)) {
                return;
            }

            $file = new SplFileObject($filePath, 'r');
            $csv  = Reader::createFromFileObject($file);

            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0);

            /**
             * Lê apenas 1000 registros a partir do offset
             */
            $stmt = (new Statement())
                ->offset($this->offset)
                ->limit(self::CHUNK_SIZE);

            $records = $stmt->process($csv);

            if (count($records) === 0) {
                Log::info('Importação e-mails spam, inválidos e rejeitados no servidor de envio finalizada.');
                return;
            }

            foreach ($records as $record) {

                $name    = $record['name'] ?? null;
                $email   = $record['email'] ?? null;

                if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    continue;
                }

                // Recuperar o registro quando existir e cadastrar não existir o usuário cadastrado
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'     => $name,
                        'password' => '54f2d8#568W3csg#9@5D@354R#t4d6DE',
                        'user_status_id' => 6,
                    ]
                );

                // Atribuir o status do formulário quando tem outro status e is_active = 1
                if ($user->user_status_id != $this->userStatusId && $this->isActive == 1) {
                    $user->update([
                        'user_status_id' => $this->userStatusId,
                    ]);
                }

                // Apagar EmailUser existente
                EmailUser::where('user_id', $user->id)->delete();
            }

            /**
             * Dispara o próximo Job automaticamente
             */
            dispatch(new self(
                $this->filePath,
                $this->emailSequenceEmailId,
                $this->userStatusId,
                $this->isActive,
                $this->offset + self::CHUNK_SIZE
            ));

            Log::info('Job importar CSV com e-mails spam, inválidos e rejeitados no servidor de envio executado.', [
                'offset' => $this->offset,
                'next'   => $this->offset + self::CHUNK_SIZE,
            ]);
        } catch (Exception $e) {

            Log::error('Erro importar CSV com e-mails spam, inválidos e rejeitados no servidor de envio.', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            throw $e;
        }
    }
}
