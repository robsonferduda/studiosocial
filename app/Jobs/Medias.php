<?php

namespace App\Jobs;

use DOMPDF;
use Notification;
use Storage;
use App\Enums\TypeRule;
use App\FbComment;
use App\FbPagePost;
use App\FbPagePostComment;
use App\FbPost;
use App\IgComment;
use App\Media;
use App\MediaTwitter;
use App\Rule as AppRule;
use Illuminate\Bus\Queueable;
use App\Notifications\MediaRelatorioNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\RuleProcessNotification;

class Medias implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $nome;
    protected $dt_inicial;
    protected $dt_final;
    protected $dados;
    public $timeout = 600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($nome, $dt_inicial, $dt_final, $dados)
    {
       $this->nome = $nome;
       $this->dt_inicial = $dt_inicial;
       $this->dt_final = $dt_final;
       $this->dados = $dados;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 
        $nome = $this->nome;
        $dt_inicial = $this->dt_inicial;
        $dt_final = $this->dt_final;
        $dados = $this->dados;

        $nome_arquivo = 'relatorio_de_coletas_'.date('YmdHis').".pdf";

        $pdf = DOMPDF::loadView('medias/relatorio-light', ['dados' => $dados]);
        Storage::disk('public')->put($nome_arquivo, $pdf->output());

        $media = new Media();
        $media->notify(new MediaRelatorioNotification()); 
    }
}
