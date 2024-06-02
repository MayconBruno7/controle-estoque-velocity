<?php

use App\Library\ControllerMain;
use App\Library\Redirect;
use App\Library\Validator;
use App\Library\Session;

class Setor extends ControllerMain
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
        $this->loadView("restrita/listaSetor", $this->model->lista("id"));
    }

    /**
     * form
     *
     * @return void
     */
    public function form()
    {
        $SetorModel = $this->loadModel("Setor");

        $DbDados = [];

        if ($this->getAcao() != 'new') {
            $DbDados = $this->model->getById($this->getId());
        }

        $DbDados['aSetor'] = $SetorModel->lista('id');

        return $this->loadView(
            "restrita/formSetor",
            $DbDados
        );
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
    //         // error
    //         return Redirect::page("Setor/form/insert");
    //     } else {

    //         if (!empty($_FILES['imagem']['name'])) {

    //             // Faz uploado da imagem
    //             $nomeRetornado = UploadImages::upload($_FILES, 'Setor');

    //             // se for boolean, significa que o upload falhou
    //             if (is_bool($nomeRetornado)) {
    //                 Session::set( 'inputs' , $post );
    //                 return Redirect::page("Setor/form/update/" . $post['id']);
    //             }

    //         } else {
    //             $nomeRetornado = $post['nomeImagem'];
    //         }

    //         if ($this->model->insert([
    //             "descricao"         => $post['descricao'],
    //             "caracteristicas"   => $post['caracteristicas'],
    //             "statusRegistro"    => $post['statusRegistro'],
    //             "categoria_id"      => $post['categoria_id'],
    //             "saldoEmEstoque"    => strNumber($post['saldoEmEstoque']),
    //             "precoVenda"        => strNumber($post['precoVenda']),
    //             "precoPromocao"     => strNumber($post['precoPromocao']),
    //             "custoTotal"        => strNumber($post['custoTotal']),
    //             'imagem'            => $nomeRetornado
    //         ])) {
    //             Session::set("msgSuccess", "Setor adicionada com sucesso.");
    //         } else {
    //             Session::set("msgError", "Falha tentar inserir uma nova Setor.");
    //         }
    
    //         Redirect::page("Setor");
    //     }
    // }

    // /**
    //  * update
    //  *
    //  * @return void
    //  */
    // public function update()
    // {
    //     $post = $this->getPost();

    //     if (Validator::make($post, $this->model->validationRules)) {
    //         return Redirect::page("Setor/form/update/" . $post['id']);    // error
    //     } else {

    //         if (!empty($_FILES['imagem']['name'])) {

    //             // Faz uploado da imagem
    //             $nomeRetornado = UploadImages::upload($_FILES, 'Setor');

    //             // se for boolean, significa que o upload falhou
    //             if (is_bool($nomeRetornado)) {
    //                 Session::set( 'inputs' , $post );
    //                 return Redirect::page("Setor/form/update/" . $post['id']);
    //             }

    //             UploadImages::delete($post['nomeImagem'], 'Setor');

    //         } else {
    //             $nomeRetornado = $post['nomeImagem'];
    //         }

    //         if ($this->model->update(
    //             [
    //                 "id" => $post['id']
    //             ], 
    //             [
    //                 "descricao"         => $post['descricao'],
    //                 "caracteristicas"   => $post['caracteristicas'],
    //                 "statusRegistro"    => $post['statusRegistro'],
    //                 "categoria_id"      => $post['categoria_id'],
    //                 "saldoEmEstoque"    => strNumber($post['saldoEmEstoque']),
    //                 "precoVenda"        => strNumber($post['precoVenda']),
    //                 "precoPromocao"     => strNumber($post['precoPromocao']),
    //                 "custoTotal"        => strNumber($post['custoTotal']),
    //                 'imagem'            => $nomeRetornado
    //             ]
    //         )) {
    //             Session::set("msgSuccess", "Setor alterada com sucesso.");
    //         } else {
    //             Session::set("msgError", "Falha tentar alterar a Setor.");
    //         }

    //         return Redirect::page("Setor");
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
    //         Session::set("msgSuccess", "Setor excluída com sucesso.");
    //     } else {
    //         Session::set("msgError", "Falha tentar excluir a Setor.");
    //     }

    //     Redirect::page("Setor");
    // }

    // /**
    //  * getSetorCombo
    //  *
    //  * @return string
    //  */
    // public function getSetorComboBox()
    // {
    //     $dados = $this->model->getSetorComboBox($this->getId());

    //     if (count($dados) == 0) {
    //         $dados[] = [
    //             "id" => "",
    //             "descricao" => "... Selecione uma Categoria ..."
    //         ];
    //     }

    //     echo json_encode($dados);
    // }
}