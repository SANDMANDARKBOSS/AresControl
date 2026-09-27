<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class CheckMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ares:check-memberships';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica las membresías por vencer y envía notificaciones por WhatsApp';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Memberships active and expiring in exactly 3 days
        $expiringIn3Days = Membership::with('clients')
            ->where('status', 'Activa')
            ->whereDate('end_date', $today->copy()->addDays(3))
            ->get();

        // Memberships active and expiring tomorrow
        $expiringTomorrow = Membership::with('clients')
            ->where('status', 'Activa')
            ->whereDate('end_date', $today->copy()->addDay())
            ->get();

        // Memberships expiring today
        $expiringToday = Membership::with('clients')
            ->where('status', 'Activa')
            ->whereDate('end_date', $today)
            ->get();

        $count = 0;

        foreach ($expiringIn3Days as $membership) {
            foreach ($membership->clients as $client) {
                if ($client->phone) {
                    $this->sendWhatsApp(
                        $client->phone, 
                        "Hola {$client->name}, te recordamos que tu membresía '{$membership->plan->name}' en ARES GYM vence en 3 días (el " . Carbon::parse($membership->end_date)->format('d/m/Y') . "). ¡No olvides renovarla para no perder tu progreso! 💪"
                    );
                    $count++;
                }
            }
        }

        foreach ($expiringTomorrow as $membership) {
            foreach ($membership->clients as $client) {
                if ($client->phone) {
                    $this->sendWhatsApp(
                        $client->phone, 
                        "¡Hola {$client->name}! Tu membresía '{$membership->plan->name}' en ARES GYM vence MAÑANA. ¡Te esperamos para renovar tu plan! 🏋️‍♂️"
                    );
                    $count++;
                }
            }
        }

        foreach ($expiringToday as $membership) {
            foreach ($membership->clients as $client) {
                if ($client->phone) {
                    $this->sendWhatsApp(
                        $client->phone, 
                        "⚠️ Hola {$client->name}, tu membresía '{$membership->plan->name}' en ARES GYM vence HOY. Acércate a recepción para renovarla y continuar tu entrenamiento. ¡Vamos con todo! 🔥"
                    );
                    
                    // Marcar como pendiente
                    $membership->update(['status' => 'Pendiente']);
                    $count++;
                }
            }
        }

        // Buscar las que ya vencieron ayer y marcarlas vencidas
        $expired = Membership::whereIn('status', ['Activa', 'Pendiente'])
            ->whereDate('end_date', '<', $today)
            ->update(['status' => 'Vencido']);

        $this->info("Se enviaron {$count} mensajes de WhatsApp. Se actualizaron {$expired} membresías a estado Vencido.");
    }

    private function sendWhatsApp($phone, $message)
    {
        try {
            $response = Http::post('http://localhost:3000/send-message', [
                'number' => $phone,
                'message' => $message
            ]);

            if ($response->successful()) {
                $this->info("Mensaje enviado exitosamente a {$phone}");
            } else {
                $this->error("Error enviando a {$phone}: " . $response->body());
            }
        } catch (\Exception $e) {
            $this->error("Error de conexión con el bot de WhatsApp: " . $e->getMessage());
        }
    }
}
