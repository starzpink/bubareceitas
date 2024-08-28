$(document).ready(function () {
    // Declaração de variáveis
    var page = 1;
    var current_page = 1;
    var total_page = 0;
    var is_ajax_fire = 0;
    var types = new Map();
    var dataCon;

    //carregamento de dados
    manageData();

    //criação de cabeçalho
    createHeadTable();

    //criação de formularios
    createForm();
    createEditForm();
    createViewForm();

    //carregamento de listas de opções
    getGrauDifSelect();
    getCorteSelect();
    getSubcorteSelect();

    function manageData() {
        $.ajax({
            dataType: 'json',
            url: 'getReceitaAdm.php',
            data: { page: page }
        }).done(function (data) {
            total_page = Math.ceil(data.total / 10);
            current_page = page;
            $('#pagination').twbsPagination({
                totalPages: total_page,
                visiblePages: current_page,
                onPageClick: function (event, pageL) {
                    page = pageL;
                    if (is_ajax_fire != 0) {
                        getPageData();
                    }
                }
            });

            manageRow(data.data);
            is_ajax_fire = 1;
        });
    }

    function getPageData() {
        $.ajax({
            dataType: 'json',
            url: 'getReceita.php',
            data: { page: page }
        }).done(function (data) {
            manageRow(data.data);
        });
    }

    function manageRow(data) { //criação da tabela

        dataCon = data;
        var rows = '';
        var i = 0;
        $.each(data, function (key, value) {
            rows = rows + '<tr>';
            rows = rows + '<td>' + value.id_rec + '</td>';
            rows = rows + '<td>' + value.nome_rec + '</td>';
            rows = rows + '<td data-id="' + i++ + '">';
            rows = rows + '<button data-toggle="modal" data-target="#edit-item" class="btn btn-primary edit-item">Editar</button> ';
            rows = rows + '<button data-toggle="modal" data-target="#view-item" class="btn btn-primary view-item">Visualizar</button> ';
            rows = rows + '</td>';
            rows = rows + '</tr>';
        });

        $("tbody").html(rows);
    }

    function createHeadTable() { //cabeçalho da tabela

        var rows = '<tr>';
        rows = rows + '<th> Código </th>';
        rows = rows + '<th> Título da Receita </th>';
        rows = rows + '<th width="200px">Ação</th>'
        rows = rows + '</tr>'
        $("thead").html(rows);
    }

    function getGrauDifSelect() { //select de grau de dificuldade

        $.ajax({
            dataType: 'json',
            url: 'getGrauDif.php',
            data: {}
        }).done(function (data) {

            var htmlSelect = '';
            $.each(data.data, function (key, value) {
                htmlSelect = htmlSelect + '<option value="' + value.id_grau_dif + '"> ' + value.nome_grau_dif + '</option>';
            });
            $("#grau_dif").html(htmlSelect);
            $("#grau_dif_edit").html(htmlSelect);
            $("#grau_dif_view").html(htmlSelect);

        });
    }

    function getCorteSelect() { //select da lista de cortes

        $.ajax({
            dataType: 'json',
            url: 'getCorte.php',
            data: {}
        }).done(function (data) {

            var htmlSelect = '';
            $.each(data.data, function (key, value) {
                htmlSelect = htmlSelect + '<option value="' + value.id_corte + '"> ' + value.nome_corte + '</option>';
            });
            $("#id_corte").html(htmlSelect);
            $("#id_corte_edit").html(htmlSelect);
            $("#id_corte_view").html(htmlSelect);

        });
    }

    function getSubcorteSelect(idCorte, target) { //select de subcorte depois de selecionado o corte
        $.ajax({
            dataType: 'json',
            url: 'getSubcorte.php',
            data: { id_corte: idCorte }
        }).done(function (data) {
            var options = '<option value=0>Nenhum subcorte</option>';
            if (data.length > 0) {
                $.each(data, function (key, value) {
                    options += '<option value="' + value.id_subcorte + '">' + value.nome_subcorte + '</option>';
                });
                $(target).prop('disabled', false);
            } else {
                $(target).prop('disabled', true);
            }
            $(target).html(options);

        }).fail(function () {
            var options = '<option value="">Erro ao carregar subcortes</option>';
            $(target).html(options);
            $(target).prop('disabled', true);
        });
    }

    function createForm() { //formulario de inserção de receita

        var html = '';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="nome_rec">Título da Receita</label>';
        html = html + '<input type="text" name="nome_rec" class="form-control" data-error="Por favor, insira o título." required />';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="ingredientes">Ingredientes</label>';
        html = html + '<textarea name="ingredientes" class="form-control" rows="4" data-error="Por favor, insira os ingredientes." required></textarea>';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="modo_pr">Modo de Preparo</label>';
        html = html + '<textarea name="modo_pr" class="form-control" rows="4" data-error="Por favor, insira o modo de preparo." required></textarea>';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="tempo_pr">Tempo de Preparo</label>';
        html = html + '<input type="text" name="tempo_pr" class="form-control" rows="4" data-error="Por favor, insira o tempo de preparo." required />';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="grau_dif">Grau de Dificuldade</label>';
        html = html + '<select name="grau_dif" id="grau_dif" class="form-control"></select>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="sugestao_pr">Sugestão de Preparo</label>';
        html = html + '<input type="text" name="sugestao_pr" class="form-control" rows="4" data-error="Por favor, insira a sugestão de preparo." required />';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="id_corte">Id do Corte</label>';
        html = html + '<select name="id_corte" id="id_corte" class="form-control"></select>';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="id_subcorte">Id do Subcorte</label>';
        html = html + '<select name="id_subcorte" id="id_subcorte" class="form-control"></select>';
        html = html + '</div>';
        html = html + '<button type="submit" class="btn crud-submit btn-success">Salvar</button>';
        html = html + '</div>';
        $("#create-item").find("form").html(html);
    }

    $(".crud-submit").click(function (e) { //ao clicar no botão de salvar no formulario de criação
        e.preventDefault();
        var form_action = $("#create-item").find("form").attr("action");
        var nome_rec = $("#create-item").find("input[name='nome_rec']").val();
        var ingredientes = $("#create-item").find("textarea[name='ingredientes']").val();
        var modo_pr = $("#create-item").find("textarea[name='modo_pr']").val();
        var tempo_pr = $("#create-item").find("input[name='tempo_pr']").val();
        var grau_dif = $("#create-item").find("select[name='grau_dif']").val();
        var sugestao_pr = $("#create-item").find("input[name='sugestao_pr']").val();
        var id_corte = $("#create-item").find("select[name='id_corte']").val();
        var id_subcorte = $("#create-item").find("select[name='id_subcorte']").val();


        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: form_action,
            data: { nome_rec: nome_rec, ingredientes: ingredientes, modo_pr: modo_pr, tempo_pr: tempo_pr, grau_dif: grau_dif, sugestao_pr: sugestao_pr, id_corte: id_corte, id_subcorte: id_subcorte }
        }).done(function (data) {

            $("#create-item").find("input[name='nome_rec']").val('');
            $("#create-item").find("textarea[name='ingredientes']").val('');
            $("#create-item").find("textarea[name='modo_pr']").val('');
            $("#create-item").find("input[name='tempo_pr']").val('');
            $("#create-item").find("select[name='grau_dif']").val('');
            $("#create-item").find("input[name='sugestao_pr']").val('');
            $("#create-item").find("select[name='id_corte']").val('');
            $("#create-item").find("select[name='id_subcorte']").val('');
            getPageData();
            $(".modal").modal('hide');
            toastr.success(data.msg, 'Alerta de Sucesso', { timeOut: 5000 });

        });

    });

    $(document).on('click', '#id_corte', function () { //evento click para carregamento dos subcortes
        var corteId = $(this).val();
        getSubcorteSelect(corteId, '#id_subcorte');
    });

    function createEditForm() { //formulário de edição de receita

        var html = '<input type="hidden" name="cod" class="edit-id">';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="id_rec">ID da Receita</label>';
        html = html + '<input type="text" name="id_rec" class="form-control" data-error="Por favor, insira o id da receita." required />';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="nome_rec">Título da Receita</label>';
        html = html + '<input type="text" name="nome_rec" class="form-control" data-error="Por favor, insira o título." required />';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="ingredientes">Ingredientes</label>';
        html = html + '<textarea name="ingredientes" class="form-control" rows="4" data-error="Por favor, insira os ingredientes." required></textarea>';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="modo_pr">Modo de Preparo</label>';
        html = html + '<textarea name="modo_pr" class="form-control" rows="4" data-error="Por favor, insira o modo de preparo." required></textarea>';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="tempo_pr">Tempo de Preparo</label>';
        html = html + '<input type="text" name="tempo_pr" class="form-control" rows="4" data-error="Por favor, insira o tempo de preparo." required />';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="grau_dif">Grau de Dificuldade</label>';
        html = html + '<select name="grau_dif" id="grau_dif_edit" class="form-control"></select>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="sugestao_pr">Sugestão de Preparo</label>';
        html = html + '<input type="text" name="sugestao_pr" class="form-control" rows="4" data-error="Por favor, insira a sugestão de preparo." required />';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="id_corte">Id do Corte</label>';
        html = html + '<select name="id_corte" id="id_corte_edit" class="form-control"></select>';
        html = html + '<div class="help-block with-errors"></div>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="id_subcorte">Id do Subcorte</label>';
        html = html + '<select name="id_subcorte" id="id_subcorte_edit" class="form-control"></select>';
        html = html + '</div>';
        html = html + '<button type="submit" class="btn crud-submit-edit btn-success">Salvar</button>';
        html = html + '</div>';
        $("#edit-item").find("form").html(html);

    }

    $("body").on("click", ".edit-item", function () { //recupera os dados ao clicar no botão de editar
        var index = $(this).parent("td").data('id');

        var id_rec = dataCon[index].id_rec;
        var nome_rec = dataCon[index].nome_rec;
        var ingredientes = dataCon[index].ingredientes;
        var modo_pr = dataCon[index].modo_pr;
        var tempo_pr = dataCon[index].tempo_pr;
        var grau_dif = dataCon[index].grau_dif;
        var sugestao_pr = dataCon[index].sugestao_pr;
        var id_corte = dataCon[index].id_corte;
        var id_subcorte = dataCon[index].id_subcorte;

        $("#edit-item").find("input[name='id_rec']").val(id_rec);
        $("#edit-item").find("input[name='nome_rec']").val(nome_rec);
        $("#edit-item").find("textarea[name='ingredientes']").val(ingredientes);
        $("#edit-item").find("textarea[name='modo_pr']").val(modo_pr);
        $("#edit-item").find("input[name='tempo_pr']").val(tempo_pr);
        $("#edit-item").find("select[name='grau_dif']").val(grau_dif);
        $("#edit-item").find("input[name='sugestao_pr']").val(sugestao_pr);
        $("#edit-item").find("select[name='id_corte']").val(id_corte);
        $("#edit-item").find("select[name='id_subcorte']").val(id_subcorte);
    });

    $("body").on("click", ".edit-item", function () {
        var id = $(this).parent("td").data('id');
        $.ajax({
            dataType: 'json',
            url: 'getReceita.php',
            data: { id: id }
        }).done(function (data) {
            $("#edit-item").find("input[name='id_rec']").val(data.id_rec);
            $("#edit-item").find("input[name='nome_rec']").val(data.nome_rec);
            $("#edit-item").find("textarea[name='ingredientes']").val(data.ingredientes);
            $("#edit-item").find("textarea[name='modo_pr']").val(data.modo_pr);
            $("#edit-item").find("input[name='tempo_pr']").val(data.tempo_pr);
            $("#edit-item").find("select[name='grau_dif']").val(data.grau_dif);
            $("#edit-item").find("input[name='sugestao_pr']").val(data.sugestao_pr);
            $("#edit-item").find("select[name='id_corte']").val(data.id_corte).trigger('click');
            $("#edit-item").find("select[name='id_subcorte']").val(data.id_subcorte);
            $("#edit-item").find("form").attr("action", "updateReceita.php?id=" + data.id_rec);
        });
    });

    $(".crud-submit-edit").click(function (e) { //envia as edições feitas ao banco de dados

        e.preventDefault();
        var form_action = $("#edit-item").find("form").attr("action");

        var id_rec = $("#edit-item").find("input[name='id_rec']").val();
        var nome_rec = $("#edit-item").find("input[name='nome_rec']").val();
        var ingredientes = $("#edit-item").find("textarea[name='ingredientes']").val();
        var modo_pr = $("#edit-item").find("textarea[name='modo_pr']").val();
        var tempo_pr = $("#edit-item").find("input[name='tempo_pr']").val();
        var grau_dif = $("#edit-item").find("select[name='grau_dif']").val();
        var sugestao_pr = $("#edit-item").find("input[name='sugestao_pr']").val();
        var id_corte = $("#edit-item").find("select[name='id_corte']").val();
        var id_subcorte = $("#edit-item").find("select[name='id_subcorte']").val();

        $.ajax({
            dataType: 'json',
            type: 'POST',
            url: form_action,
            data: { id_rec: id_rec, nome_rec: nome_rec, ingredientes: ingredientes, modo_pr: modo_pr, tempo_pr: tempo_pr, grau_dif: grau_dif, sugestao_pr: sugestao_pr, id_corte: id_corte, id_subcorte: id_subcorte }

        }).done(function (data) {

            getPageData();
            $(".modal").modal('hide');
            toastr.success(data.msg, 'Alerta de Sucesso', { timeOut: 5000 });
        });

    });

    $(document).on('click', '#id_corte_edit', function () { // Evento click para id_corte na edição
        var corteId = $(this).val();
        getSubcorteSelect(corteId, '#id_subcorte_edit');
    });

    function createViewForm() { //visualização da receita

        var html = '<input type="hidden" name="cod" class="view-id">';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="id_rec">ID da Receita</label>';
        html = html + '<input type="text" name="id_rec" class="form-control" readonly/>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="nome_rec">Título da Receita</label>';
        html = html + '<input type="text" name="nome_rec" class="form-control" readonly/>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="ingredientes">Ingredientes</label>';
        html = html + '<textarea name="ingredientes" class="form-control" rows="4" readonly></textarea>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="modo_pr">Modo de Preparo</label>';
        html = html + '<textarea name="modo_pr" class="form-control" rows="4" readonly></textarea>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="tempo_pr">Tempo de Preparo</label>';
        html = html + '<input type="text" name="tempo_pr" class="form-control" readonly/>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="grau_dif">Grau de Dificuldade</label>';
        html = html + '<select name="grau_dif" id="grau_dif_view" class="form-control" disabled></select>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="sugestao_pr">Sugestão de Preparo</label>';
        html = html + '<input type="text" name="sugestao_pr" class="form-control" readonly/>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="id_corte">Id do Corte</label>';
        html = html + '<select name="id_corte" id="id_corte_view" class="form-control" disabled></select>';
        html = html + '</div>';
        html = html + '<div class="form-group">';
        html = html + '<label class="control-label" for="id_subcorte">Id do Subcorte</label>';
        html = html + '<select name="id_subcorte" id="id_subcorte_view" class="form-control" disabled></select>';
        html = html + '</div>';
        $("#view-item").find("form").html(html);

    }

    $("body").on("click", ".view-item", function () { //recupera os dados ao clicar no botão de visualizar
        var index = $(this).parent("td").data('id');

        var id_rec = dataCon[index].id_rec;
        var nome_rec = dataCon[index].nome_rec;
        var ingredientes = dataCon[index].ingredientes;
        var modo_pr = dataCon[index].modo_pr;
        var tempo_pr = dataCon[index].tempo_pr;
        var grau_dif = dataCon[index].grau_dif;
        var sugestao_pr = dataCon[index].sugestao_pr;
        var id_corte = dataCon[index].id_corte;
        var id_subcorte = dataCon[index].id_subcorte;

        $("#view-item").find("input[name='id_rec']").val(id_rec);
        $("#view-item").find("input[name='nome_rec']").val(nome_rec);
        $("#view-item").find("textarea[name='ingredientes']").val(ingredientes);
        $("#view-item").find("textarea[name='modo_pr']").val(modo_pr);
        $("#view-item").find("input[name='tempo_pr']").val(tempo_pr);
        $("#view-item").find("select[name='grau_dif']").val(grau_dif);
        $("#view-item").find("input[name='sugestao_pr']").val(sugestao_pr);
        $("#view-item").find("select[name='id_corte']").val(id_corte);
        $("#view-item").find("select[name='id_subcorte']").val(id_subcorte); // tentar adicionar id_subcorte ao parametro

        getSubcorteSelect(id_corte, '#id_subcorte_view');
    });
});
