<div style="margin-top: 30px;">
    @if($dados['reactions'])
        <img src="{{ $charts['reactions'] }}">
        <div>
            <table class="table">
                <thead class="">
                    <tr>
                        <th>Reação</th>
                        <th class="center">Ícone</th>
                        <th class="center">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dados['reactions'] as $reaction)
                        <tr>
                            <td>{{ $reaction->name }}</td>
                            <td class="center"><img src="{{ public_path('img/icon/'.$reaction->name.'.png') }}"></td>
                            <td class="center">{{ $reaction->count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p style="text-align: center;">Não existem dados para os parâmetros selecionados</p>
    @endif
</div>
