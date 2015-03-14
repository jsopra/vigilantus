<?php

namespace app\models;

use app\components\ActiveRecord;

/**
 * Este é a classe de modelo da tabela "usuario_roles".
 *
 * Estas são as colunas disponíveis na tabela 'usuario_roles':
 * @property integer $id
 * @property string $nome
 */
class UsuarioRole extends ActiveRecord
{
    const ROOT = 1;
    const ADMINISTRADOR = 2;
    const GERENTE = 3;
    const USUARIO = 4;
    const ANALISTA = 5;

    /**
     * @return string nome da tabela do banco de dados
     */
    public static function tableName()
    {
        return 'usuario_roles';
    }

    /**
     * @return array regras de validação para os atributos do modelo
     */
    public function rules()
    {
        return array(
            array('nome', 'required'),
        );
    }

    /**
     * @return array regras de relações
     */
    public function relations()
    {
        return array(
            'usuarios' => array(self::HAS_MANY, 'Usuarios', 'usuario_role_id'),
        );
    }

    /**
     * @return array descrição dos atributos (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'nome' => 'Nome',
        );
    }

    /**
     * Exclui a linha da tabela correspondente a este active record.
     * @return boolean se a exclusão foi feita com sucesso ou não.
     * @throws CException se o registro for novo
     */
    public function delete()
    {
        throw new \Exception('Exclusão não habilitada', 500);
    }

    /**
     * @return array
     */
    public static function listDataNivelUsuario(Usuario $usuario)
    {
        $listData = [];

        $query = self::find()
            ->doNivelDoUsuario($usuario)
            ->select('id,nome')
            ->orderBy('nome')
        ;

        foreach ($query->all() as $row) {
            $listData[$row->id] = $row->nome;
        }

        return $listData;
    }
}
