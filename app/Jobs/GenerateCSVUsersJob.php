<?php

namespace App\Jobs;

use App\Models\User;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class GenerateCSVUsersJob implements ShouldQueue
{
    use Queueable;

    /**
     * Quantidade de registros por job
     */
    private const CHUNK_SIZE = 500;

    public function __construct(
        protected string $filePath = '',
        protected string $name = '',
        protected string $email = '',
        protected string $start_date = '',
        protected string $end_date = '',
        protected string $tagged_or_untagged = '',
        protected ?array $email_tag_id = null,
        protected int $offset = 0 // Controle de progresso
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {

            // Ler os usuários a partir do offset limitado pelo CHUNK_SIZE
            $users = User::query()
                ->when($this->name, fn($query) => $query->whereLike('name', '%' . $this->name . '%'))
                ->when($this->email, fn($query) => $query->whereLike('email', '%' . $this->email . '%'))
                ->when($this->start_date, fn($query) => $query->whereDate('created_at', '>=', $this->start_date))
                ->when($this->end_date, fn($query) => $query->whereDate('created_at', '<=', $this->end_date))
                ->when($this->tagged_or_untagged !== '', function ($query) {
                    // COM TAG
                    if ($this->tagged_or_untagged === '1') {
                        if (!empty($this->email_tag_id)) {
                            $query->whereHas('emailTags', fn($q) => $q->whereIn('email_tag_id', $this->email_tag_id));
                        } else {
                            $query->whereHas('emailTags');
                        }
                    }

                    // SEM TAG
                    if ($this->tagged_or_untagged === '0') {
                        if (!empty($this->email_tag_id)) {
                            $query->whereDoesntHave('emailTags', fn($q) => $q->whereIn('email_tag_id', $this->email_tag_id));
                        } else {
                            $query->whereDoesntHave('emailTags');
                        }
                    }
                })
                ->orderBy('id', 'ASC')
                ->skip($this->offset)
                ->take(self::CHUNK_SIZE)
                ->get();


            // Se não encontrar nenhum registro finaliza o job
            if (count($users) === 0) {
                Log::info('Importação CSV finalizada.');
                return;
            }

            // Criar arquivo apenas no primeiro job
            if ($this->filePath === "") {
                $directory = storage_path('app/csv');

                // Criar pasta se não existir
                if (!file_exists($directory)) {
                    mkdir($directory, 0755, true);
                }

                // Caminho completo do arquivo
                $this->filePath = $directory . '/users_' . time() . '.csv';
            }

            // Abrir o arquivo na forma de escrita
            $openFile = fopen($this->filePath, 'a');

            // Criar o cabeçalho se for o primeiro job.
            if ($this->offset === 0) {
                fputcsv($openFile, ['EMAIL', 'FIRSTNAME'], ';');
            }

            // Processar os $users e escrever no CSV
            foreach ($users as $user) {
                // Criar o array com os dados da linha do Excel e escrever o conteúdo no arquivo
                fputcsv($openFile, [
                    trim((string) $user->email),
                    trim((string) $user->name),
                ], ';');
            }

            // Fechar o arquivo após a escrita
            fclose($openFile);

            /**
             * Dispara o próximo Job automaticamente
             */
            dispatch(new self(
                $this->filePath,
                $this->name,
                $this->email,
                $this->start_date,
                $this->end_date,
                $this->tagged_or_untagged,
                $this->email_tag_id,
                $this->offset + self::CHUNK_SIZE
            ));
        } catch (Exception $e) {

            Log::error('Erro gerar CSV dos usuários', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            throw $e;
        }
    }
}
