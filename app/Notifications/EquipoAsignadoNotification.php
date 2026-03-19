<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\equ_asignado;
use App\Models\equipo;
use App\Models\empleado;

class EquipoAsignadoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $asignacion;
    protected $equipo;
    protected $empleado;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($asignacionId)
    {
        $this->asignacion = equ_asignado::with(['equipo', 'empleado'])->find($asignacionId);
        $this->equipo = $this->asignacion->equipo;
        $this->empleado = $this->asignacion->empleado;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Equipo Asignado - ' . $this->equipo->EQU_NOMBRE)
                    ->greeting('¡Hola ' . $this->empleado->EMP_NOMBRES . '!')
                    ->line('Se te ha asignado un nuevo equipo.')
                    ->line('**Detalles del equipo:**')
                    ->line('Nombre: ' . $this->equipo->EQU_NOMBRE)
                    ->line('Serial: ' . $this->equipo->EQU_SERIAL)
                    ->line('Fecha de entrega: ' . $this->asignacion->EAS_FECHA_ENTREGA->format('d/m/Y'))
                    ->action('Ver Asignaciones', route('Asignacion_equipo.index'))
                    ->line('Por favor, verifica el equipo y confirma que todo esté en orden.')
                    ->line('Recuerda cuidar el equipo y reportar cualquier inconveniente.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'equipo_asignado',
            'asignacion_id' => $this->asignacion->EAS_ID,
            'equipo_id' => $this->equipo->EQU_ID,
            'equipo_nombre' => $this->equipo->EQU_NOMBRE,
            'equipo_serial' => $this->equipo->EQU_SERIAL,
            'fecha_entrega' => $this->asignacion->EAS_FECHA_ENTREGA->format('Y-m-d'),
            'message' => 'Se te ha asignado el equipo: ' . $this->equipo->EQU_NOMBRE,
            'url' => route('Asignacion_equipo.index'),
        ];
    }

    /**
     * Get the database representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return $this->toArray($notifiable);
    }
}
