<div style="margin-top: 20px;">
    <div style="width: 48%; float: left;">
        <h6 class="center">POSITIVOS</h6>
        @foreach($dados['influenciadores']['positivos'] as $key => $u)
            <div style="margin-bottom: 10px; font-size: 11px; {{ ($key < count($dados['influenciadores']['positivos']) -1 ) ? 'border-bottom: 1px solid #d7d7d7;' : '' }}">
                <img style="width: 40px; height: 40px;" src="{{ url('img/user.png') }}" alt="Imagem de Perfil" class="rounded-pill">
                <strong style="margin-bottom: 20px;">{{ $u->user_name }}</strong>
                <span>{{ $u->total }} postagens</span>
            </div>
        @endforeach
    </div>
    <div style="width: 48%; float: right;">
        <h6 class="center">NEGATIVOS</h6>
        @foreach($dados['influenciadores']['negativos'] as $key => $u)
            <div style="margin-bottom: 10px; font-size: 11px; {{ ($key < count($dados['influenciadores']['negativos']) -1 ) ? 'border-bottom: 1px solid #d7d7d7;' : '' }}">
                <img style="width: 40px; height: 40px;" src="{{ url('img/user.png') }}" alt="Imagem de Perfil" class="rounded-pill">
                <strong style="margin-bottom: 20px;">{{ $u->user_name }}</strong>
                <span>{{ $u->total }} postagens</span>
            </div>
        @endforeach
    </div>
</div>