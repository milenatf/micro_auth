<?php

namespace App\Models\Traits;

use Illuminate\Support\Str;

/**
 * Esta trait é reponsável por gerar os uuids para as classes que
 * usam o uuid como chave primária das tabelas.
 */
trait UuidTrait
{
    /**
     * Antes de criar o objeto, que tenha a coluna uuid como chave primária,
     * na coluna id (ou qualquer outro nome que seja colocado na coluna de PK) é inserido o uuid.
     *
     * Foi realizada essa implementação pois o id incremental nas colunas de chaves primárias das tabelas
     * foi substituído pelo uuid. O uuid que será a chave primária em algumas tabelas
     *
     * Caso seja necessário mudar o nome da coluna 'id' que conterá o uuid (por exemplo, 'codigoUnico')
     * o método getKeyName() é reponsável por pegar o nome desta coluna. Dessa forma a criação do
     * uuid para a coluna de Chave Primária será dinâmica.
     *
     * @return void
     */
    public static function booted()
    {
        static::creating(function($model) {
            // Gera automaticamente um uuid para um usuário
            $model->{$model->getKeyName()} = (String) Str::uuid();
        });
    }
}