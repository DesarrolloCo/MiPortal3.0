<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class novedade extends Model
{
    use HasFactory;

    protected $table = 'novedades';
    protected $primaryKey = 'NOV_ID';

    protected $fillable = [
        'TIN_ID',
        'EMP_ID',
        'NOV_FECHA',
        'NOV_DESCRIPCION',
        'NOV_ARCHIVOS',
        'NOV_ESTADO',
        'NOV_ESTADO_APROBACION',
        'NOV_OBSERVACIONES',
        'NOV_APROBADO_POR',
        'NOV_FECHA_APROBACION',
        'USER_ID',
    ];

    protected $casts = [
        'NOV_FECHA' => 'date',
        'NOV_FECHA_APROBACION' => 'datetime',
    ];

    protected $appends = ['archivos_lista'];

    protected ?Collection $cachedHorarios = null;

    public function tipoNovedad(): BelongsTo
    {
        return $this->belongsTo(tipos_novedade::class, 'TIN_ID', 'TIN_ID');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(empleado::class, 'EMP_ID', 'EMP_ID');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'USER_ID', 'id');
    }

    public function aprobadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'NOV_APROBADO_POR', 'id');
    }

    public function scopePendientes($query)
    {
        return $query->where('NOV_ESTADO_APROBACION', 'pendiente');
    }

    public function scopeAprobadas($query)
    {
        return $query->where('NOV_ESTADO_APROBACION', 'aprobada');
    }

    public function scopeRechazadas($query)
    {
        return $query->where('NOV_ESTADO_APROBACION', 'rechazada');
    }

    public function scopePorFecha($query, $fechaInicio, $fechaFin)
    {
        return $query->where(function ($q) use ($fechaInicio, $fechaFin) {
            $q->whereBetween('NOV_FECHA', [$fechaInicio, $fechaFin])
                ->orWhereHas('horarios', function ($horariosQuery) use ($fechaInicio, $fechaFin) {
                    $horariosQuery->whereBetween('MAL_DIA', [$fechaInicio, $fechaFin]);
                });
        });
    }

    public function scopePorEmpleado($query, $empleadoId)
    {
        return $query->where('EMP_ID', $empleadoId);
    }

    public function scopePorTipo($query, $tipoId)
    {
        return $query->where('TIN_ID', $tipoId);
    }

    public function getEstadoColorAttribute(): string
    {
        return [
            'pendiente' => 'warning',
            'aprobada' => 'success',
            'rechazada' => 'danger',
        ][$this->NOV_ESTADO_APROBACION] ?? 'secondary';
    }

    public function getEstadoTextoAttribute(): string
    {
        return [
            'pendiente' => 'Pendiente',
            'aprobada' => 'Aprobada',
            'rechazada' => 'Rechazada',
        ][$this->NOV_ESTADO_APROBACION] ?? 'Sin estado';
    }

    public function getArchivosRawAttribute(): ?string
    {
        return $this->attributes['NOV_ARCHIVOS'] ?? null;
    }

    public function getArchivosListaAttribute(): array
    {
        $value = $this->attributes['NOV_ARCHIVOS'] ?? null;

        if (!$value) {
            return [];
        }

        if (is_array($value)) {
            return $value;
        }

        if (is_string($value)) {
            return json_decode($value, true) ?: [];
        }

        return [];
    }

    public function agregarArchivoBinario($archivo, $nombreOriginal): self
    {
        $archivos = $this->archivos_lista;

        $archivoData = [
            'nombre_original' => $nombreOriginal,
            'contenido_binario' => base64_encode($archivo),
            'size' => strlen($archivo),
            'tipo' => mime_content_type('data://application/octet-stream;base64,' . base64_encode($archivo)) ?: 'application/octet-stream',
            'fecha_subida' => now()->toDateTimeString(),
        ];

        $archivos[] = $archivoData;
        $this->attributes['NOV_ARCHIVOS'] = json_encode($archivos);

        return $this;
    }

    public function obtenerArchivoBinario($indice): ?array
    {
        $archivos = $this->archivos_lista;

        if (isset($archivos[$indice]) && isset($archivos[$indice]['contenido_binario'])) {
            return [
                'contenido' => base64_decode($archivos[$indice]['contenido_binario']),
                'nombre_original' => $archivos[$indice]['nombre_original'],
                'tipo' => $archivos[$indice]['tipo'] ?? 'application/octet-stream',
            ];
        }

        return null;
    }

    public function eliminarArchivo($indice): self
    {
        $archivos = $this->archivos_lista;

        if (isset($archivos[$indice])) {
            unset($archivos[$indice]);
            $this->attributes['NOV_ARCHIVOS'] = json_encode(array_values($archivos));
        }

        return $this;
    }

    /**
     * Relacion con horarios afectados por la novedad
     */
    public function horariosAfectados(): HasMany
    {
        return $this->hasMany(NovedadHorario::class, 'nov_id', 'NOV_ID');
    }

    /**
     * Relacion con horarios a traves de la tabla pivot
     */
    public function horarios(): BelongsToMany
    {
        return $this->belongsToMany(
            malla::class,
            'novedad_horarios',
            'nov_id',
            'mal_id',
            'NOV_ID',
            'MAL_ID'
        );
    }

    public function getNovFechaInicioAttribute(): ?Carbon
    {
        $horarios = $this->obtenerHorarios();

        if ($horarios->isEmpty()) {
            return null;
        }

        $fecha = $horarios->min(fn ($horario) => $horario->MAL_DIA);

        return $this->parseFecha($fecha);
    }

    public function getNovFechaFinAttribute(): ?Carbon
    {
        $horarios = $this->obtenerHorarios();

        if ($horarios->isEmpty()) {
            return null;
        }

        $fecha = $horarios->max(fn ($horario) => $horario->MAL_DIA);

        return $this->parseFecha($fecha);
    }

    public function getNovHoraInicioAttribute(): ?Carbon
    {
        $horarios = $this->obtenerHorarios();

        if ($horarios->isEmpty()) {
            return null;
        }

        $hora = $horarios->min(fn ($horario) => $horario->MAL_INICIO);

        return $this->parseHora($hora);
    }

    public function getNovHoraFinAttribute(): ?Carbon
    {
        $horarios = $this->obtenerHorarios();

        if ($horarios->isEmpty()) {
            return null;
        }

        $hora = $horarios->max(fn ($horario) => $horario->MAL_FINAL);

        return $this->parseHora($hora);
    }

    protected function obtenerHorarios(): Collection
    {
        if ($this->cachedHorarios !== null) {
            return $this->cachedHorarios;
        }

        if ($this->relationLoaded('horarios')) {
            $this->cachedHorarios = $this->getRelation('horarios');
        } else {
            $this->cachedHorarios = $this->horarios()->get();
            $this->setRelation('horarios', $this->cachedHorarios);
        }

        return $this->cachedHorarios;
    }

    protected function parseFecha(?string $fecha): ?Carbon
    {
        if (!$fecha) {
            return null;
        }

        return Carbon::parse($fecha)->startOfDay();
    }

    protected function parseHora(?string $valor): ?Carbon
    {
        if (!$valor) {
            return null;
        }

        return Carbon::parse($valor);
    }
}
