<?php

namespace App\Jobs;

use Barryvdh\DomPDF\Facade\Pdf;
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
    public $timeout = 2000;

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

        $time_start = microtime(true);

        $options = [
            'debugLayoutBlocks' => false,
            'debugLayoutLines' => false,
            'debugLayoutInline' => false,
            'debugLayoutPaddingBox' => false
        ];

        $pdf = Pdf::setOption($options)->loadView('medias/relatorio-light', compact('nome','dt_inicial','dt_final','dados'));
       // $pdf->setOption();
        Storage::disk('public')->put("$client_id/$filename", $pdf->output());
        $time_end = microtime(true);
        $execution_time = ($time_end - $time_start)/60;

        //execution time of the script
        echo '<b>Total Execution Time:</b> '.$execution_time.' Mins';

        $media = new Media();
        $media->notify(new MediaRelatorioNotification());
    }
}
