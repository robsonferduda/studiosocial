<div style="margin-top: 30px;">
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
                        <td class="center">{!! $reaction->icon !!}</td>
                        <td class="center">{{ $reaction->count }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table> 
    </div>
    <img src="{{ $charts['reactions'] }}">
</div>