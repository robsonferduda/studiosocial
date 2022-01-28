<div>
    <table class="table">
        <thead>
            <tr>
                <th>Rede Social</th>
                <th class="text-center">Positivos</th>
                <th class="text-center">Negativos</th>
                <th class="text-center">Neutros</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Facebook</td>
                <td class="text-center">{{ $dados['sentimentos']['facebook']['total_positivo'] }}</td>
                <td class="text-center">{{ $dados['sentimentos']['facebook']['total_negativo'] }}</td>
                <td class="text-center">{{ $dados['sentimentos']['facebook']['total_neutro'] }}</td>
            </tr>
            <tr>
                <td>Instagram</td>
                <td class="text-center">{{ $dados['sentimentos']['instagram']['total_positivo'] }}</td>
                <td class="text-center">{{ $dados['sentimentos']['instagram']['total_negativo'] }}</td>
                <td class="text-center">{{ $dados['sentimentos']['instagram']['total_neutro'] }}</td>
            </tr>
            <tr>
                <td>Twitter</td>
                <td class="text-center">{{ $dados['sentimentos']['twitter']['total_positivo'] }}</td>
                <td class="text-center">{{ $dados['sentimentos']['twitter']['total_negativo'] }}</td>
                <td class="text-center">{{ $dados['sentimentos']['twitter']['total_neutro'] }}</td>
            </tr>
        </tbody>
    </table>
    <img src="{{ $charts['sentimentos'] }}">
</div>