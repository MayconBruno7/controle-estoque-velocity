<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class OrdemServico extends ControllerMain
{
    /**
     * construct
     *
     * @param array $dados  
     */
    public function __construct($dados)
    {
        $this->auxiliarConstruct($dados);

        // Somente pode ser acessado por usuários adminsitradores
        if (!$this->getAdministrador()) {
            return Redirect::page("Home");
        }
    }

    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $this->loadView("restrita/listaOrdemServico", $this->model->lista("id"));
    }

    /**
     * viewLog
     *
     * @return void
     */
    public function viewLog()
    {
        $dados = [];
    
        $FuncionarioModel = $this->loadModel('Funcionario');
        $dados['aFuncionario'] = $FuncionarioModel->lista('id');
    
        $UsuarioModel = $this->loadModel('Usuario');
        $dados['aUsuario'] = $UsuarioModel->lista('id');
    
        if ($this->getAcao() != "new") {
            $registro = $this->model->getById($this->getId());
            // Mescla os dados de $registro com os dados existentes em $dados
            $dados = array_merge($dados, $registro);
        }
    
        return $this->loadView("restrita/viewLog", $dados);
    }
    

    // /**
    //  * insert
    //  *
    //  * @return void
    //  */
    // public function insert()
    // {
    //     $post = $this->getPost();

    //     if (Validator::make($post, $this->model->validationRules)) {
    //         return Redirect::page("Cargo/form/insert");     // error
    //     } else {

    //         if ($this->model->insert([
    //             "nome" => $post['nome'],
    //             "statusRegistro" => $post['statusRegistro']
    //         ])) {
    //             Session::set("msgSuccess", "Cargo adicionada com sucesso.");
    //         } else {
    //             Session::set("msgError", "Falha tentar inserir uma nova Cargo.");
    //         }
    
    //         Redirect::page("Cargo");
    //     }
    // }

//   
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "ordem_servico";

// // Criar conexão
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Verificar conexão
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// $cliente_nome = $_POST['cliente_nome'];
// $telefone_cliente = $_POST['telefone_cliente'];
// $modelo_dispositivo = $_POST['modelo_dispositivo'];
// $imei_dispositivo = $_POST['imei_dispositivo'];
// $descricao_servico = $_POST['descricao_servico'];
// $tipo_servico = $_POST['tipo_servico'];
// $problema_reportado = $_POST['problema_reportado'];
// $data_abertura = $_POST['data_abertura'];
// $status = $_POST['status'];
// $observacoes = $_POST['observacoes'];
// $pecas = $_POST['peca'];

// // Preparar e executar inserção da ordem de serviço
// $stmt = $conn->prepare("INSERT INTO ordens_servico (cliente_nome, telefone_cliente, modelo_dispositivo, imei_dispositivo, descricao_servico, tipo_servico, problema_reportado, data_abertura, status, observacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
// $stmt->bind_param("ssssssssss", $cliente_nome, $telefone_cliente, $modelo_dispositivo, $imei_dispositivo, $descricao_servico, $tipo_servico, $problema_reportado, $data_abertura, $status, $observacoes);
// $stmt->execute();

// // Obter o ID da ordem de serviço inserida
// $id_ordem_servico = $stmt->insert_id;
// $stmt->close();

// // Preparar e executar inserção das peças associadas
// if (!empty($pecas)) {
//     $stmt_pecas = $conn->prepare("INSERT INTO ordens_servico_pecas (id_ordem_servico, id_peca) VALUES (?, ?)");
//     foreach ($pecas as $id_peca) {
//         $stmt_pecas->bind_param("ii", $id_ordem_servico, $id_peca);
//         $stmt_pecas->execute();
//     }
//     $stmt_pecas->close();
// }

// $conn->close();

// header("Location: visualizar_os.php");
// exit();
// 


    // /**
    //  * update
    //  *
    //  * @return void
    //  */
    // public function update()
    // {
    //     $post = $this->getPost();

    //     if (Validator::make($post, $this->model->validationRules)) {
    //         // error
    //         return Redirect::page("Cargo/form/update/" . $post['id']);
    //     } else {

    //         if ($this->model->update(
    //             [
    //                 "id" => $post['id']
    //             ], 
    //             [
    //                 "nome" => $post['nome'],
    //                 "statusRegistro" => $post['statusRegistro']
    //             ]
    //         )) {
    //             Session::set("msgSuccess", "Cargo alterada com sucesso.");
    //         } else {
    //             Session::set("msgError", "Falha tentar alterar a Cargo.");
    //         }

    //         return Redirect::page("Cargo");
    //     }
    // }
    // /**
    //  * delete
    //  *
    //  * @return void
    //  */
    // public function delete()
    // {
    //     if ($this->model->delete(["id" => $this->getPost('id')])) {
    //         Session::set("msgSuccess", "Cargo excluída com sucesso.");
    //     } else {
    //         Session::set("msgError", "Falha tentar excluir a Cargo.");
    //     }

    //     Redirect::page("Cargo");
    // }


    // function imprimir
// require('vendor/autoload.php');
// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "ordem_servico";

// // Criar conexão
// $conn = new mysqli($servername, $username, $password, $dbname);

// // Verificar conexão
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// $id = $_GET['id'];

// // Buscar dados da ordem de serviço
// $sql = "SELECT * FROM ordens_servico WHERE id = ?";
// $stmt = $conn->prepare($sql);
// $stmt->bind_param("i", $id);
// $stmt->execute();
// $result = $stmt->get_result();
// $row = $result->fetch_assoc();

// // Buscar dados das peças
// $sql_pecas = "SELECT * FROM pecas WHERE id = ?";
// $stmt_pecas = $conn->prepare($sql_pecas);
// $stmt_pecas->bind_param("i", $id);
// $stmt_pecas->execute();
// $result_pecas = $stmt_pecas->get_result();

// $stmt->close();
// $stmt_pecas->close();
// $conn->close();

// $pdf = new FPDF();
// $pdf->AddPage();
// $pdf->SetFont('Arial', 'B', 16);

// // Adicionar a imagem
// $pdf->Image('C:\Users\Maycon Bruno\Desktop\Ordem de servico\brasao-pmrl.png', 98, 10, 15);

// // Adicionar espaço abaixo da imagem
// $pdf->Ln(20);

// // Título
// $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Ordem de Serviço'), 0, 1, 'C');
// $pdf->Ln(10);

// $pdf->SetFont('Arial', '', 12);

// // Cabeçalho da tabela principal
// $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', 'Campo'), 1, 0, 'C');
// $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Valor'), 1, 1, 'C');

// // Função para alternar cores
// function alternaCor($pdf, $line_number) {
//     if ($line_number % 2 == 0) {
//         $pdf->SetFillColor(230, 230, 230);
//     } else {
//         $pdf->SetFillColor(255, 255, 255);
//     }
// }

// // Dados da tabela principal com linhas zebrada
// $linhas = [
//     ['ID:', $row['id']],
//     [iconv('UTF-8', 'ISO-8859-1', 'Nome do Cliente:'), iconv('UTF-8', 'ISO-8859-1', $row['cliente_nome'])],
//     [iconv('UTF-8', 'ISO-8859-1', 'Telefone:'), iconv('UTF-8', 'ISO-8859-1', $row['telefone_cliente'])],
//     [iconv('UTF-8', 'ISO-8859-1', 'Modelo do Dispositivo:'), iconv('UTF-8', 'ISO-8859-1', $row['modelo_dispositivo'])],
//     [iconv('UTF-8', 'ISO-8859-1', 'IMEI:'), iconv('UTF-8', 'ISO-8859-1', $row['imei_dispositivo'])],
//     [iconv('UTF-8', 'ISO-8859-1', 'Descrição do Serviço:'), iconv('UTF-8', 'ISO-8859-1', $row['descricao_servico'])],
//     [iconv('UTF-8', 'ISO-8859-1', 'Tipo de Serviço:'), iconv('UTF-8', 'ISO-8859-1', $row['tipo_servico'])],
//     [iconv('UTF-8', 'ISO-8859-1', 'Problema Reportado:'), iconv('UTF-8', 'ISO-8859-1', $row['problema_reportado'])],
//     [iconv('UTF-8', 'ISO-8859-1', 'Data de Abertura:'), iconv('UTF-8', 'ISO-8859-1', $row['data_abertura'])],
//     [iconv('UTF-8', 'ISO-8859-1', 'Status:'), iconv('UTF-8', 'ISO-8859-1', $row['status'])],
//     [iconv('UTF-8', 'ISO-8859-1', 'Observações:'), iconv('UTF-8', 'ISO-8859-1', $row['observacoes'])],
// ];

// // Largura das colunas
// $largura_campo = 60;
// $largura_valor = $pdf->GetPageWidth() - $largura_campo - 20;

// $line_number = 0;
// foreach ($linhas as $linha) {
//     alternaCor($pdf, $line_number);
//     $pdf->SetX(10);
//     $pdf->Cell($largura_campo, 10, $linha[0], 1, 0, 'L', true);
//     $pdf->SetX(10 + $largura_campo);
//     $pdf->MultiCell($largura_valor, 10, $linha[1], 1, 'L', true);
//     $line_number++;
// }

// // Adicionar espaço antes da seção de peças
// $pdf->Ln(10);
// $pdf->SetFont('Arial', 'B', 14);
// $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Valores das Peças'), 0, 1, 'C');
// $pdf->Ln(10);

// $pdf->SetFont('Arial', '', 12);

// // Cabeçalho da tabela de peças
// $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', 'Peça'), 1, 0, 'C');
// $pdf->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1', 'Valor'), 1, 1, 'C');

// // Dados das peças
// while ($peca = $result_pecas->fetch_assoc()) {
//     $peca_nome = isset($peca['nome_peca']) ? trim($peca['nome_peca']) : 'N/A';
//     $valor = isset($peca['valor_peca']) ? (float)$peca['valor_peca'] : 0.0;

//     $pdf->Cell(60, 10, iconv('UTF-8', 'ISO-8859-1', $peca_nome), 1, 0, 'L');
//     $pdf->Cell(0, 10, number_format($valor, 2, ',', '.'), 1, 1, 'R');
// }

// $pdf->Output();


}