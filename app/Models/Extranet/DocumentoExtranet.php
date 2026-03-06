<?php

namespace App\Models\Extranet;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\departamento;

class DocumentoExtranet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'documentos_extranet';

    protected $fillable = [
        'titulo',
        'descripcion',
        'categoria',
        'archivo_url',
        'archivo_nombre',
        'archivo_tipo',
        'archivo_tamano',
        'version',
        'autor_id',
        'departamento_id',
        'visible_para',
        'descargas',
        'destacado',
    ];

    protected $casts = [
        'archivo_tamano' => 'integer',
        'visible_para' => 'array',
        'descargas' => 'integer',
        'destacado' => 'boolean',
    ];

    // Relaciones
    public function autor()
    {
        return $this->belongsTo(User::class, 'autor_id');
    }

    public function departamento()
    {
        return $this->belongsTo(departamento::class, 'departamento_id', 'DEP_ID');
    }

    public function publicacion()
    {
        return $this->morphOne(PublicacionMuro::class, 'referencia');
    }

    // Scopes
    public function scopePorCategoria($query, $categoria)
    {
        return $query->where('categoria', $categoria);
    }

    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }

    public function scopePorDepartamento($query, $departamentoId)
    {
        return $query->where('departamento_id', $departamentoId);
    }

    // Accessors
    public function getCategoriaIconoAttribute()
    {
        $iconos = [
            'politicas' => 'mdi-shield-check',
            'manuales' => 'mdi-book-open',
            'formatos' => 'mdi-file-document',
            'reglamentos' => 'mdi-gavel',
            'procedimientos' => 'mdi-file-tree',
            'capacitacion' => 'mdi-school',
            'otro' => 'mdi-file',
        ];

        return $iconos[$this->categoria] ?? 'mdi-file';
    }

    public function getArchivoTamanoFormateadoAttribute()
    {
        if (!$this->archivo_tamano) {
            return 'Desconocido';
        }

        $tamano = $this->archivo_tamano;

        if ($tamano < 1024) {
            return $tamano . ' B';
        } elseif ($tamano < 1048576) {
            return round($tamano / 1024, 2) . ' KB';
        } else {
            return round($tamano / 1048576, 2) . ' MB';
        }
    }

    // Métodos
    public function incrementarDescargas()
    {
        $this->increment('descargas');
    }

    public function destacar()
    {
        $this->update(['destacado' => true]);
    }

    public function quitarDestacado()
    {
        $this->update(['destacado' => false]);
    }
}
