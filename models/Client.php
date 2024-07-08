<?php

namespace app\models;

use app\controllers\ClientController;
use Yii;
use yii\db\ActiveRecord;

class Client extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%client}}';
    }

    public function rules()
    {
        return [
            ['name', 'string', 'max' => 100],
            ['cpf', 'validateCpf', 'skipOnError' => false],
            ['cep', 'string', 'max' => 8],
            ['address', 'string', 'max' => 255],
            ['number', 'string', 'max' => 10],
            ['city', 'string', 'max' => 50],
            ['state', 'string', 'max' => 2],
            ['complement', 'string', 'max' => 50],
            ['photo', 'string', 'max' => 255],
            ['sex', 'string', 'max' => 1],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Nome',
            'cpf' => 'CPF',
            'cep' => 'CEP',
            'address' => 'Endereço',
            'number' => 'Número',
            'city' => 'Cidade',
            'state' => 'Estado',
            'complement' => 'Complemento',
            'photo' => 'Foto',
            'sex' => 'Sexo',
        ];
    }

    public function validateCpf($attribute)
    {
        $cpf = preg_replace('/[^0-9]/', '', $this->$attribute);
        Yii::info("Validating CPF: {$cpf}", __METHOD__);

        if (strlen($cpf) != 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            $this->addError($attribute, 'CPF inválido.');
            return;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$t] != $d) {
                $this->addError($attribute, 'CPF inválido.');
                return;
            }
        }
    }
}