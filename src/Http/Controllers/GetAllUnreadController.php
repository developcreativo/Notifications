<?php


namespace Developcreativo\Notifications\Http\Controllers;

use App\Eventos;
use App\Traits\AccessScopeTraits;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class GetAllUnreadController
{
    use AccessScopeTraits;
	public function __invoke(Request $request)
	{
        $user = Auth::user();
        $unreadNotifications = $user->unreadNotifications;

// Obtener los IDs de acceso según el rol del usuario
        $accessScopeIds = self::getAccessScopeIds();
        $clientIds = $accessScopeIds['clientes'] ?? [];
        $subjectIds = $accessScopeIds['subjects'] ?? [];

        $filteredNotifications = $unreadNotifications->filter(function ($notification) use ($clientIds, $subjectIds) {
            $data = $notification->data;

            // Verificar si la notificación tiene 'event_id'
            if (isset($data['event_id'])) {
                $event = Eventos::find($data['event_id']);

                if ($event) {
                    // Verificar si el 'id_cliente' o 'subject_id' del evento está en los IDs permitidos
                    //mtzmorenoj@gmail.com
                    //AEROPUERTO INTERNACIONAL DE PUERTO VALLARTA B3i(F6Ln
                    $hasClientId = in_array($event->id_cliente ?? null, $clientIds);
                    $hasSubjectId = in_array($event->subject_id ?? null, $subjectIds);

                    return $hasClientId || $hasSubjectId;
                }
            }

            return false;
        });

// Luego puedes mapear las notificaciones filtradas por claves si es necesario
        $notifications = $filteredNotifications->mapWithKeys(function($notification) {
            return [$notification->id => $notification];
        });

//		$notifications = $request->user()
//			->unreadNotifications
//			->mapWithKeys(function(DatabaseNotification $notification) {
//				return [$notification->id => $notification];
//			});

		return Response::json([
			'count'         => $notifications->count(),
			'notifications' => $notifications,
			'user_id'       => $request->user()->id,
		]);
	}
}