<?php

namespace App\Jobs;

use Notification;
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

    protected $lote;
    public $timeout = 240;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($lote)
    {
       $this->lote = $lote;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    { 

        for ($i=0; $i < count($this->lote); $i++) { 
            
            $pdf = DOMPDF::loadView('medias/relatorio-light', compact('nome','dt_inicial','dt_final','dados'));
            Storage::disk('public')->put('relatorio_de_coletas_'.$i.'.pdf', $pdf->output());

        }

        $media = new Media();
        $media->notify(new MediaRelatorioNotification()); 
    }
}
