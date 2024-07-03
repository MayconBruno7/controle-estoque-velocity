<?php
use App\Library\Formulario;
?>

<main class="container">
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
                <button type="button" id="imprimirRelatorio" class="btn btn-secondary mt-3">Imprimir Relatório</button>
            </form>
            <div class="container">            
                <canvas id="graficoRelatorio" class="mt-4"></canvas>
                <div id="relatorioHtml" class="mt-4"></div> 
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Função para mostrar/esconder calendários baseado no tipo de relatório selecionado
    function toggleCalendarios(tipo) {
        var calendariosDiv = document.getElementById('calendarios');
        calendariosDiv.innerHTML = ''; // Limpa conteúdo anterior

        var gerarRelatorioBtn = document.getElementById('gerarRelatorio');
        var imprimirRelatorioBtn = document.getElementById('imprimirRelatorio');

        gerarRelatorioBtn.disabled = true; // Desabilita por padrão
        imprimirRelatorioBtn.disabled = true; // Desabilita por padrão

        if (tipo === 'dia') {
            // Mostrar calendário para selecionar dia e mês
            calendariosDiv.innerHTML = `
                <label for="calendarioDia">Selecione o Dia, Mês e Ano:</label>
                <input type="date" id="calendarioDia" class="form-control" required>
            `;

            // Evento ao mudar a data do calendário de dia
            document.getElementById('calendarioDia').addEventListener('change', function() {
                checkDataPreenchida();
            });

        } else if (tipo === 'semana') {
            // Mostrar dois calendários para selecionar intervalo semanal
            calendariosDiv.innerHTML = `
                <label for="calendarioInicio">Data Início:</label>
                <input type="date" id="calendarioInicio" class="form-control" required>
                <label for="calendarioFim" class="mt-2">Data Fim:</label>
                <input type="date" id="calendarioFim" class="form-control" required>
            `;

            // Evento ao mudar a data de início da semana
            document.getElementById('calendarioInicio').addEventListener('change', function() {
                checkDataPreenchida();
            });

            // Evento ao mudar a data de fim da semana
            document.getElementById('calendarioFim').addEventListener('change', function() {
                checkDataPreenchida();
            });

        } else if (tipo === 'mes') {
            // Mostrar calendário para selecionar mês e ano
            calendariosDiv.innerHTML = `
                <label for="calendarioMesAno">Selecione o Mês e Ano:</label>
                <input type="month" id="calendarioMesAno" class="form-control" required>
            `;

            // Evento ao mudar o mês e ano
            document.getElementById('calendarioMesAno').addEventListener('change', function() {
                checkDataPreenchida();
            });

        } else if (tipo === 'ano') {
            // Mostrar calendário para selecionar apenas o ano
            calendariosDiv.innerHTML = `
                <label for="calendarioAno">Selecione o Ano:</label>
                <input type="number" id="calendarioAno" class="form-control" min="1900" max="2024" value="<?= date('Y') ?>" required>
            `;

            // Evento ao mudar o ano
            document.getElementById('calendarioAno').addEventListener('change', function() {
                checkDataPreenchida();
            });
        }

        // Função para verificar se a data está preenchida e habilitar/desabilitar os botões
        function checkDataPreenchida() {
            var dataInicio;

            switch (tipo) {
                case 'dia':
                    dataInicio = document.getElementById('calendarioDia').value;
                    break;
                case 'semana':
                    dataInicio = document.getElementById('calendarioInicio').value;
                    var fim = document.getElementById('calendarioFim').value;
                    if (dataInicio && fim) {
                        gerarRelatorioBtn.disabled = false;
                        imprimirRelatorioBtn.disabled = false;
                    } else {
                        gerarRelatorioBtn.disabled = true;
                        imprimirRelatorioBtn.disabled = true;
                    }
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

            if (dataInicio) {
                gerarRelatorioBtn.disabled = false;
                imprimirRelatorioBtn.disabled = false;
            } else {
                gerarRelatorioBtn.disabled = true;
                imprimirRelatorioBtn.disabled = true;
            }
        }

        // Inicializa a verificação quando a função é chamada
        checkDataPreenchida();
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
        let fetchedData; // Variável para armazenar os dados do fetch

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
                    fetchedData = data; // Armazena os dados retornados

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
                    return `
                        Data: ${tooltipItem.label}
                        Produto: ${descricao}
                        Valor: ${valor}
                        Entradas: ${entrada}
                        Saídas: ${saida}
                    `;
                }
            },
            displayColors: false, // Oculta a exibição das cores
            backgroundColor: 'rgba(0, 0, 0, 0.8)', // Cor de fundo do tooltip
            bodyFontColor: '#fff', // Cor do texto dentro do tooltip
            titleFontColor: '#fff', // Cor do título do tooltip
            bodyFontSize: 14, // Tamanho da fonte do texto dentro do tooltip
            bodySpacing: 6, // Espaçamento entre linhas dentro do tooltip
            cornerRadius: 8, // Raio do canto do tooltip
            caretPadding: 10, // Espaçamento entre a borda do tooltip e a "caret"
            borderWidth: 1, // Largura da borda do tooltip
            borderColor: '#ccc' // Cor da borda do tooltip
        }
    }
}
                    });
                    renderRelatorioHtml(data); // Renderiza os dados em formato HTML
                });
        });

        // Função para renderizar os dados em formato HTML
        function renderRelatorioHtml(data) {
            var html = '<h3>Relatório de Movimentações</h3>';
            html += '<table class="table table-striped">';
            html += '<thead><tr><th>Data</th><th>Produto</th><th>Valor</th><th>Entradas</th><th>Saídas</th></tr></thead>';
            html += '<tbody>';
            for (var i = 0; i < data.labels.length; i++) {
                html += '<tr>';
                html += `<td>${data.labels[i]}</td>`;
                html += `<td>${data.descricoes[i]}</td>`;
                html += `<td>${data.valores[i]}</td>`;
                html += `<td>${data.entradas[i]}</td>`;
                html += `<td>${data.saidas[i]}</td>`;
                html += '</tr>';
            }
            html += '</tbody>';
            html += '</table>';

            document.getElementById('relatorioHtml').innerHTML = html;
        }

        // Evento para imprimir relatório
        document.getElementById('imprimirRelatorio').addEventListener('click', function() {
            window.print(); // Imprime a página
        });
    });
</script>

<style>
    @media print {
        .navbar {
            display: none;
        }
        #graficoRelatorio {
            position: absolute;
            left: -9999px; /* Mova o elemento para fora do espaço visível */
            opacity: 0; /* Garanta que o elemento não seja visível */
        }
        #relatorioHtml {
            display: block;
        }
        footer {
            display: none;
        }
    }
</style>
