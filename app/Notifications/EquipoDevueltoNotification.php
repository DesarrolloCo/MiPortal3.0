<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Devolucion;

class EquipoDevueltoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $devolucion;

    public function __construct($devolucionId)
    {
        $this->devolucion = Devolucion::with(['asignacion.equipo', 'asignacion.empleado'])->find($devolucionId);
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        $equipo = $this->devolucion->asignacion->equipo;
        $empleado = $this->devolucion->asignacion->empleado;

        return (new MailMessage)
                    ->subject('Equipo Devuelto - ' . $equipo->EQU_NOMBRE)
                    ->greeting('Hola!')
                    ->line('Se ha registrado la devolución de un equipo.')
                    ->line('**Detalles:**')
                    ->line('Equipo: ' . $equipo->EQU_NOMBRE . ' (' . $equipo->EQU_SERIAL . ')')
                    ->line('Empleado: ' . $empleado->EMP_NOMBRES)
                    ->line('Estado: ' . $this->devolucion->DEV_ESTADO_EQUIPO)
                    ->line('Fecha: ' . $this->devolucion->DEV_FECHA_DEVOLUCION->format('d/m/Y'))
                    ->action('Ver Acta', route('Asignacion_equipo.acta_devolucion', $this->devolucion->DEV_ID))
                    ->line('Gracias por mantener el inventario actualizado.');
    }

    public function toArray($notifiable)
    {
        $equipo = $this->devolucion->asignacion->equipo;

        return [
            'type' => 'equipo_devuelto',
            'devolucion_id' => $this->devolucion->DEV_ID,
            'equipo_nombre' => $equipo->EQU_NOMBRE,
            'equipo_serial' => $equipo->EQU_SERIAL,
            'estado_equipo' => $this->devolucion->DEV_ESTADO_EQUIPO,
            'fecha_devolucion' => $this->devolucion->DEV_FECHA_DEVOLUCION->format('Y-m-d'),
            'message' => 'Equipo devuelto: ' . $equipo->EQU_NOMBRE . ' (Estado: ' . $this->devolucion->DEV_ESTADO_EQUIPO . ')',
            'url' => route('Asignacion_equipo.acta_devolucion', $this->devolucion->DEV_ID),
        ];
    }
}
