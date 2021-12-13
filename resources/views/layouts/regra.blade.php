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
                <option value="custom">Personalizado</option>
            </select>
        </div>
    </div>
    <div class="col-lg-2 col-md-2 mb-2">
        <div class="form-group">
            <label>Data Inicial</label>
            <input type="text" class="form-control dt_inicial_relatorio dt_periodo">
        </div>
    </div>
    <div class="col-lg-2 col-md-2 mb-2">
        <div class="form-group">
            <label>Data Final</label>
            <input type="text" class="form-control dt_final_relatorio dt_periodo">
        </div>
    </div>
    <div class="col-lg-5 col-md-5 mb-2">
        <div class="form-group">
            <label>Selecione uma regra</label>
            <select class="form-control load_expression" name="regra" id="regra">
                <option value="">Selecione uma regra</option>
                @foreach($rules as $rule)
                    <option value="{{ $rule->id }}" data-expression="{{ $rule->getExpression() }}">{{ $rule->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 mb-2">
        <label>Expressão</label>
        <span class="d-block mb-2 display_regra">Nenhuma regra selecionada</span>
    </div>
</div>