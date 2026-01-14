<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailSequenceJob;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendEmailSequence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email-sequence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Agendar o Job para enviar e-mail';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        try {

            // Criar o job na fila "emails"
            // SendEmailSequenceJob::dispatch()
            //     ->onQueue('emails');
            SendEmailSequenceJob::dispatch();

            $this->info('Job agendado com sucesso.');

            Log::info('Job de envio de e-mail agendado via cron.');

            return Command::SUCCESS;

        } catch (Exception $e) {

            Log::error('Erro ao agendar job de envio de e-mail.', [
                'error' => $e->getMessage()
            ]);

            $this->error('Erro ao agendar o Job.');

            return Command::FAILURE;
        }
    }
}
