<?php

namespace Database\Seeders;

use App\Models\Resource;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = Resource::create(['name' => 'Categorias']);
        $category->permissions()->create(['name' => 'visualizar_categorias']);
        $category->permissions()->create(['name' => 'visualizar_categoria']);
        $category->permissions()->create(['name' => 'deletar_categoria']);
        $category->permissions()->create(['name' => 'editar_categoria']);

        $resourceCompany = Resource::create(['name' => 'Empresas']);
        $resourceCompany->permissions()->create(['name' => 'visualizar_empresas']);
        $resourceCompany->permissions()->create(['name' => 'visualizar_empresa']);
        $resourceCompany->permissions()->create(['name' => 'deletar_empresa']);
        $resourceCompany->permissions()->create(['name' => 'editar_empresa']);
    }
}
