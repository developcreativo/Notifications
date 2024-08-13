<?php


namespace Developcreativo\Notifications\Contracts;


interface Notification
{
	public static function make(string $title = null, string $subtitle = null, string $eventid = null);
}