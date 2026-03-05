<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';
    protected $primaryKey = 'CLI_ID';

    public function unidadNegocioClientes(): HasMany
    {
        return $this->hasMany(uni_cli::class, 'CLI_ID', 'CLI_ID');
    }
}
