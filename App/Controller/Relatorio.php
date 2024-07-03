<?php

use App\Library\ControllerMain;
use App\Library\Redirect;

class Relatorio extends ControllerMain
{
    public function __construct($dados)
    {
        $this->auxiliarConstruct($dados);

        // Só acessa se tiver logado
        if (!$this->getUsuario()) {
            return Redirect::page("Home");
        }
    }

    public function index()
    {
        $this->loadView("restrita/formRelatorio");
    }

    public function getDados()
    {

        $tipo = $this->getOutrosParametros(2);
        $dataInicio = $this->getOutrosParametros(3);
        $dataFinal = $this->getOutrosParametros(4);

        $dados = [];
        
        switch ($tipo) {
            case 'dia':
                $dados = $this->model->RelatorioDia($dataInicio);
                break;
            case 'semana':
                $dados = $this->model->RelatorioSemana($dataInicio, $dataFinal);
                break;
            case 'mes':
                $dados = $this->model->RelatorioMes($dataInicio);
                break;
            case 'ano':
                $dados = $this->model->RelatorioAno($dataInicio);
                break;
            default:
                $dados = [];
        }

        header('Content-Type: application/json');
        echo json_encode($this->formatarDadosParaGrafico($dados));
    }

    private function formatarDadosParaGrafico($dados)
    {
        $entradas = [];
        $saidas = [];
        $labels = [];
        $descricoes = [];
        $valores = [];

        foreach ($dados as $dado) {
            $labels[] = $dado['data_pedido'];
            $descricoes[] = $dado['descricao'];
            $valores[] = $dado['valor'];
            
            if ($dado['tipo'] == 1) { // Entrada
                $entradas[] = $dado['quantidade'];
                $saidas[] = 0;
            } else { // Saída
                $entradas[] = 0;
                $saidas[] = $dado['quantidade'];
            }
        }

        return [
            'labels' => $labels,
            'descricoes' => $descricoes,
            'valores' => $valores,
            'entradas' => $entradas,
            'saidas' => $saidas
        ];
    }
}
?>
