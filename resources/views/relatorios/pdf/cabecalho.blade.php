<div style="clear:both; margin-top: 20px;">
    <div style="width: 85%; float: left;">
        <h6 style="margin-bottom: 0px; padding-bottom: 5px; margin-top: 26px; font-size: 17px; border-bottom: 3px solid #b5b4b4;">{{ $nome }}</h6>
        <p style="color: #eb8e06; margin: 0;"><strong>Período: {{ $dt_inicial }} à {{ $dt_final }}</strong></p>
        <p style="margin: 0;">Nome do Cliente</p>        
    </div>
    <div style="width: 15%; float: right; text-align: right;">
        <img style="width: 90%" src="{{ url('img/studio_social.png') }}"/>
    </div>
</div> 
<div style="clear:both">
    <div class="text-center" style="background:#f7f7f7;">
        <strong class="d-block">TERMO DE PESQUISA</strong>
        @if($rule)
            <p>{{ $rule->getExpression() }}</p>
        @else
            <p>Todas as Regras</p>
        @endif
    </div>
</div>