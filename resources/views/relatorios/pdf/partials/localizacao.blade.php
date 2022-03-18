<div style="margin-top: 20px;">
        <div style="width: 48%; float: left;">
            <h6 class="center">LOCALIZAÇÃO DOS USUÁRIOS</h6>
            @if(count($dados['localizacao']['location_user']))
                <table>
                    <thead>
                        <tr>
                            <th>Localização</th>
                            <th class="center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 0; $i < count($dados['localizacao']['location_user']); $i++)
                            <tr>
                                <td>{{ $dados['localizacao']['location_user'][$i]->user_location }}</td>
                                <td class="text-center">{{ $dados['localizacao']['location_user'][$i]->total }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            @else
                <p class="center">Não existem dados para os parâmetros selecionados</p>
            @endif
        </div>
        <div style="width: 48%; float: right;">
            <h6 class="center">LOCALIZAÇÃO DOS TWEETS</h6>
            @if(count($dados['localizacao']['location_tweet']))
                <table>
                    <thead>
                        <tr>
                            <th>Localização</th>
                            <th class="center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for($i = 0; $i < count($dados['localizacao']['location_tweet']); $i++)
                            <tr>
                                <td>{{ $dados['localizacao']['location_tweet'][$i]->place_name }}</td>
                                <td class="text-center">{{ $dados['localizacao']['location_tweet'][$i]->total }}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            @else
                <p class="center">Não existem dados para os parâmetros selecionados</p>
            @endif
        </div>
    </div>