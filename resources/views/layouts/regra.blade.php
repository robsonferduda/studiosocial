<div class="row">
    <div class="col-lg-3 col-md-3 mb-2">
        <div class="form-group">
            <label>Selecione um período</label>
            <select class="form-control" name="periodo" id="periodo">
                <option value="">Selecione um período</option>
                <option value="7" selected>Últimos 7 dias</option>
                <option value="15">Últimos 15 dias</option>
                <option value="30">Últimos 30 dias</option>
                <option value="90">Últimos 90 dias</option>
            </select>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 mb-2">
        <div class="form-group">
            <label>Selecione uma regra</label>
            <select class="form-control" name="regra" id="regra">
                <option value="">Selecione uma regra</option>
                @foreach($rules as $rule)
                    <option value="{{ $rule->id }}" data-expression="Regra 1 ou regra 1 e regra 1">{{ $rule->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6 col-md-6 mb-2">
        <label>Expressão</label>
        <span class="d-block mb-2 display_regra">Nenhuma regra selecionada</span>
    </div>
</div>