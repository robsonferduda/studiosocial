<?php

namespace App\Jobs;

use App\Enums\TypeRule;
use App\Media;
use App\Rule as AppRule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;

class Rule implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client_id = Session::get('cliente')['id'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rules = AppRule::with('expressions')->where('client_id', $this->client_id)->get();
        
        foreach($rules as $rule) {
            $todas = $rule->expressions()->wherePivot('type_rule_id', TypeRule::TODAS)->pluck('expression')->toArray();
            $algumas = $rule->expressions()->wherePivot('type_rule_id', TypeRule::ALGUMAS)->pluck('expression')->toArray();
            $nenhuma = $rule->expressions()->wherePivot('type_rule_id', TypeRule::NENHUMA)->pluck('expression')->toArray();

            $medias = Media::where('client_id', $this->client_id);
            if(count($todas) > 0) {
                $medias = $medias->where(function ($query) use ($todas, $algumas) {
                    $query->where(function ($query) use ($todas) {
                        foreach($todas as $expression) {
                            $query->where('caption', 'ilike', "% {$expression} %");
                        }        
                    });      
                    if(count($algumas) > 0) {
                        $query->orWhere(function ($query) use ($algumas) {
                             foreach($algumas as $expression) {
                                 $query->orWhere('caption', 'ilike', "% {$expression} %");
                             }               
                         });
                     }
                });    
            }

            if(count($nenhuma) > 0) {
                $medias =  $medias->where(function ($query) use ($nenhuma) {
                     foreach($nenhuma as $expression) {
                        $query->where('caption', 'not ilike', " %{$expression}% ");
                     }                             
                 });
             }

             dd($medias->get()[1]);
           
            
            

            $expression_todas = implode(' AND ', $todas);
            dd($expression_todas);
            //$expression_algumas =
            //$expressions_nenhuma =

        }
    }
}
