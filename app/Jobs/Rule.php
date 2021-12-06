<?php

namespace App\Jobs;

use App\Enums\TypeRule;
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
            $todas = $rule->expressions->where('type_rule_id', TypeRule::TODAS)->pluck('expression')->toArray();
            dd($todas);
            //$algumas = 
            //$nenhuma = 
        }

        dd($rules);
    }
}
