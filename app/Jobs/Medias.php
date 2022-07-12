<?php

namespace App\Jobs;

use DOMPDF;
use Notification;
use Storage;
use App\Media;
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
    protected $client_id;
    public $timeout = 600;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($client_id, $nome, $dt_inicial, $dt_final, $dados)
    {
       $this->client_id = $client_id;
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
        set_time_limit(-1);

        $client_id = $this->client_id;
        $nome = $this->nome;
        $dt_inicial = $this->dt_inicial;
        $dt_final = $this->dt_final;
        $dados = $this->dados;
        $filename  = date('dmYHi').'_relatorio_de_coletas.pdf';

        $pdf = DOMPDF::loadView('medias/relatorio-light', compact('nome','dt_inicial','dt_final','dados'));

       // $pdf->setOption();
        Storage::disk('public')->put("$client_id/$filename", $pdf->output());

        $media = new Media();
        $media->notify(new MediaRelatorioNotification());
    }
}
