<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\mantenimiento;

class MantenimientoProximoNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $mantenimiento;
    protected $equipo;
    protected $diasRestantes;

    /**
     * Create a new notification instance.
     *
     * @param  int  $mantenimientoId
     * @return void
     */
    public function __construct($mantenimientoId)
    {
        $this->mantenimiento = mantenimiento::with('equipo')->find($mantenimientoId);
        $this->equipo = $this->mantenimiento->equipo;
        $this->diasRestantes = $this->mantenimiento->diasRestantes();
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
        $urgencia = $this->mantenimiento->urgencia;
        $subject = $urgencia === 'vencido'
            ? 'Mantenimiento Vencido - ' . $this->equipo->EQU_NOMBRE
            : 'Mantenimiento Próximo - ' . $this->equipo->EQU_NOMBRE;

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('¡Alerta de Mantenimiento!')
            ->line('Hay un mantenimiento programado para el equipo: **' . $this->equipo->EQU_NOMBRE . '**');

        if ($urgencia === 'vencido') {
            $message->error()
                ->line('⚠️ **Este mantenimiento está VENCIDO**')
                ->line('Debía realizarse el: ' . $this->mantenimiento->MAN_FECHA_AGENDADA->format('d/m/Y'));
        } else {
            $message->line('📅 Fecha programada: ' . $this->mantenimiento->MAN_FECHA_AGENDADA->format('d/m/Y'))
                ->line('⏰ Días restantes: ' . abs($this->diasRestantes));
        }

        $message->line('**Detalles del mantenimiento:**')
            ->line('Tipo: ' . $this->mantenimiento->MAN_TIPO)
            ->line('Equipo: ' . $this->equipo->EQU_NOMBRE . ' (' . ($this->equipo->EQU_SERIAL ?? 'N/A') . ')')
            ->line('Proveedor: ' . ($this->mantenimiento->MAN_PROVEEDOR ?? 'Por asignar'))
            ->action('Ver Dashboard de Inventario', route('Inventario.dashboard'))
            ->line('Por favor, coordina la realización de este mantenimiento a la brevedad.');

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $urgencia = $this->mantenimiento->urgencia;

        return [
            'type' => 'mantenimiento_proximo',
            'urgencia' => $urgencia,
            'mantenimiento_id' => $this->mantenimiento->MAN_ID,
            'equipo_id' => $this->equipo->EQU_ID,
            'equipo_nombre' => $this->equipo->EQU_NOMBRE,
            'equipo_serial' => $this->equipo->EQU_SERIAL ?? 'N/A',
            'mantenimiento_tipo' => $this->mantenimiento->MAN_TIPO,
            'fecha_agendada' => $this->mantenimiento->MAN_FECHA_AGENDADA->format('Y-m-d'),
            'dias_restantes' => $this->diasRestantes,
            'message' => $urgencia === 'vencido'
                ? 'Mantenimiento VENCIDO para ' . $this->equipo->EQU_NOMBRE
                : 'Mantenimiento próximo para ' . $this->equipo->EQU_NOMBRE . ' (en ' . abs($this->diasRestantes) . ' días)',
            'url' => route('Inventario.dashboard'),
        ];
    }
}
