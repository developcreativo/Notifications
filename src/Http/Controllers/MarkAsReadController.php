<?php


namespace Developcreativo\Notifications\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Response;

class MarkAsReadController
{
	public function __invoke(Request $request, $notification)
	{
		$markRead = $request
			->user()
			->unreadNotifications()
			->find($notification)
            ->forceFill(['read_at' => Date::now()])->save();


        

		return Response::json([
			'notification' => $request
				->user()
				->notifications()
				->find($notification),
		]);
	}
}