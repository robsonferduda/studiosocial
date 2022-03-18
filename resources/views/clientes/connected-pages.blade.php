@extends('layouts.app')
@section('content')
<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="card-title ml-2"><i class="nc-icon nc-briefcase-24"></i> Páginas Associadas</h4>
                </div>
                <div class="col-md-6">
                 
                </div>
            </div>
        </div>
        <div class="card-body">            
            <table class="table" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <td></td>
                        <td class="text-center">Reactions</td>  
                        <td class="text-center">Total de Comentários</td>  
                        <td class="text-center">Total de Compartilhamentos</td>                          
                    </tr>
                </thead>
                <tbody>
                    @php
                        $reaction_count = 0;   
                    @endphp

                    @foreach($client->pagesMonitor as $page)

                        @php
                            
                            foreach ($reactions as $reaction) {
                                ${"reaction_".$reaction->name} = 0;
                            }
                            
                            foreach($page->fbPagesPost as $post) {      
                              
                                foreach($post->reactions as $reaction) {
                                    ${"reaction_".$reaction->name}++;                                     
                                }
                            }    
                        @endphp
                       
                        <tr>
                            <td>{{ $page->name }}</td>
                            <td class="text-center">
                                @foreach ($reactions as $reaction) 
                                    <div>{{ $reaction->icon }} {{ ${"reaction_".$reaction->name} }}</div>
                                    
                                @endforeach
                            
                            </td>  
                            <td class="text-center">{{ $page->fbPagesPost->sum('comment_count') }}</td>
                            <td class="text-center">{{ $page->fbPagesPost->sum('share_count') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div> 
@endsection