<?php

    use App\Library\Formulario;

?>

<main class="container">
    <?= Formulario::titulo('', true, false); ?>

    <div class="row">
        <div class="col-12">
            <?= Formulario::exibeMsgError() ?>
        </div>

        <div class="col-12 mt-3">
            <?= Formulario::exibeMsgSucesso() ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-center">Relatório de Movimentações</div>
        <div class="card-body">
            <form id="relatorioForm">
                <div class="form-group">
                    <label for="tipoRelatorio">Tipo de Relatório</label>
                    <select id="tipoRelatorio" class="form-control mb-2">
                        <option value="dia">Diário</option>
                        <option value="semana">Semanal</option>
                        <option value="mes">Mensal</option>
                        <option value="ano">Anual</option>
                    </select>
                </div>
                <!-- Div para exibir calendários dinamicamente -->
                <div id="calendarios" class="form-group"></div>
                <button type="button" id="gerarRelatorio" class="btn btn-primary mt-3">Gerar Relatório</button>
            </form>
            <canvas id="graficoRelatorio" class="mt-4"></canvas>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Função para mostrar/esconder calendários baseado no tipo de relatório selecionado
    function toggleCalendarios(tipo) {
        var calendariosDiv = document.getElementById('calendarios');
        calendariosDiv.innerHTML = ''; // Limpa conteúdo anterior

        if (tipo === 'dia') {
            // Mostrar calendário para selecionar dia e mês
            calendariosDiv.innerHTML = `
                <label for="calendarioDia">Selecione o Dia, Mês e Ano:</label>
                <input type="date" id="calendarioDia" class="form-control" required>
            `;
        } else if (tipo === 'semana') {
            // Mostrar dois calendários para selecionar intervalo semanal
            calendariosDiv.innerHTML = `
                <label for="calendarioInicio">Data Início:</label>
                <input type="date" id="calendarioInicio" class="form-control" required>
                <label for="calendarioFim">Data Fim:</label>
                <input type="date" id="calendarioFim" class="form-control" required>
            `;
        } else if (tipo === 'mes') {
            // Mostrar calendário para selecionar mês e ano
            calendariosDiv.innerHTML = `
                <label for="calendarioMesAno">Selecione o Mês e Ano:</label>
                <input type="month" id="calendarioMesAno" class="form-control" required>
            `;
        } else if (tipo === 'ano') {
            // Mostrar calendário para selecionar apenas o ano
            calendariosDiv.innerHTML = `
                <label for="calendarioAno">Selecione o Ano:</label>
                <input type="number" id="calendarioAno" class="form-control" min="1900" max="2024" value="<?= date('Y') ?>" required>
            `;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa com o tipo de relatório padrão
        toggleCalendarios(document.getElementById('tipoRelatorio').value);

        // Evento ao mudar o tipo de relatório
        document.getElementById('tipoRelatorio').addEventListener('change', function() {
            toggleCalendarios(this.value);
        });

        // Variável para armazenar a instância do gráfico
        let chart;

        // Evento para gerar relatório
        document.getElementById('gerarRelatorio').addEventListener('click', function() {
            var tipoRelatorio = document.getElementById('tipoRelatorio').value;
            var dataInicio;
            var fim;

            // Obter os dados conforme o tipo de relatório selecionado
            switch (tipoRelatorio) {
                case 'dia':
                    dataInicio = document.getElementById('calendarioDia').value;
                    break;
                case 'semana':
                    dataInicio = document.getElementById('calendarioInicio').value;
                    fim = document.getElementById('calendarioFim').value;
                    break;
                case 'mes':
                    dataInicio = document.getElementById('calendarioMesAno').value;
                    break;
                case 'ano':
                    dataInicio = document.getElementById('calendarioAno').value;
                    break;
                default:
                    break;
            }

            // Montar URL com os parâmetros
            var url = '<?= baseUrl() ?>Relatorio/getDados/' + tipoRelatorio + '/' + dataInicio;
            if (fim) {
                url += '/' + fim;
            }

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    var ctx = document.getElementById('graficoRelatorio').getContext('2d');

                    // Se existir um gráfico, destruí-lo antes de criar um novo
                    if (chart) {
                        chart.destroy();
                    }

                    chart = new Chart(ctx, {
                        type: 'bar', // ou 'line', 'pie', etc.
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Entradas',
                                data: data.entradas,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Saídas',
                                data: data.saidas,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function(tooltipItem) {
                                            let index = tooltipItem.dataIndex;
                                            let entrada = data.entradas[index];
                                            let saida = data.saidas[index];
                                            let descricao = data.descricoes[index];
                                            let valor = data.valores[index];
                                            return `Data: ${tooltipItem.label}
                                                    Produto: ${descricao}
                                                    Valor: ${valor}
                                                    Entradas: ${entrada}
                                                    Saídas: ${saida}`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
        });
    });
</script>
