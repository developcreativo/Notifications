<?php

namespace Developcreativo\Notifications;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Developcreativo\Notifications\Contracts\Notification as NotificationContract;

class Notification implements NotificationContract, Arrayable
{
    /**
     *
     */
    const LEVELS = ['info', 'success', 'error'];
    /**
     * @var array
     */
    protected $notification = [];

    /**
     * @param $title
     * @param $subtitle
     */
    public function __construct($title = null, $subtitle = null, string $eventid = null)
    {
        if (!empty($title)) {
            $this->title($title);
        }

        if (!empty($subtitle)) {
            $this->subtitle($subtitle);
        }
        if (!empty($eventid)) {
            $this->eventId($eventid);
        }

        $this
            ->showMarkAsRead()
            ->showCancel()
            ->playSound()
            ->displayToasted()
            ->createdAt(Carbon::now());
    }

    /**
     * @param string|null $title
     * @param string|null $subtitle
     * @param string|null $eventid
     * @return Notification
     */
    public static function make(string $title = null, string $subtitle = null, string $eventid = null): Notification
    {
        return new static($title, $subtitle, $eventid);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function title(string $value): Notification
    {
        $this->notification['title'] = $value;
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function subtitle(string $value): Notification
    {
        $this->notification['subtitle'] = $value;
        return $this;
    }

    /**
     * @param string $value
     * @param bool $external
     * @return $this
     */
    public function link(string $value, bool $external = false): Notification
    {
        $this->notification['url'] = $value;
        $this->notification['external'] = $external;
        return $this;
    }

    /**
     * @param string $name
     * @param string $resourceName
     * @param $resourceId
     * @return $this
     */
    public function route(string $name, string $resourceName, $resourceId = null): Notification
    {
        $this->notification['route'] = [
            'name' => $name,
            'params' => ['resourceName' => $resourceName]
        ];

        if (!empty($resourceId)) {
            $this->notification['route']['params']['resourceId'] = $resourceId;
        }

        return $this;
    }

    /**
     * @param string $resourceName
     * @return $this
     */
    public function routeIndex(string $resourceName): Notification
    {
        return $this->route('index', $resourceName);
    }

    /**
     * @param string $resourceName
     * @return $this
     */
    public function routeCreate(string $resourceName): Notification
    {
        return $this->route('create', $resourceName);
    }

    /**
     * @param string $resourceName
     * @param $resourceId
     * @return $this
     */
    public function routeEdit(string $resourceName, $resourceId): Notification
    {
        return $this->route('edit', $resourceName, $resourceId);
    }

    /**
     * @param string $resourceName
     * @param $resourceId
     * @return $this
     */
    public function routeDetail(string $resourceName, $resourceId): Notification
    {
        return $this->route('detail', $resourceName, $resourceId);
    }

    public function level(string $value): Notification
    {
        if (!in_array($value, Notification::LEVELS)) {
            $value = 'info';
        }

        $this->notification['level'] = $value;
        return $this;
    }

    public function info(string $value): Notification
    {
        return $this
            ->title($value)
            ->level('info');
    }

    public function success(string $value): Notification
    {
        return $this
            ->title($value)
            ->level('success');
    }

    public function error(string $value): Notification
    {
        return $this
            ->title($value)
            ->level('error');
    }

    public function createdAt(Carbon $value): Notification
    {
        $this->notification['created_at'] = $value->toAtomString();
        return $this;
    }

    public function eventId($value): Notification
    {
        $this->notification['event_id'] = $value;
        return $this;
    }

    public function icon(string $value): Notification
    {
        $this->notification['icon'] = $value;
        return $this;
    }

    public function showMarkAsRead(bool $value = true): Notification
    {
        $this->notification['show_mark_as_read'] = $value;
        return $this;
    }

    public function showCancel(bool $value = true): Notification
    {
        $this->notification['show_cancel'] = $value;
        return $this;
    }

    public function sound(string $value): Notification
    {
        $this->notification['sound'] = $value;
        return $this;
    }
    
    public function playSound(bool $value = true): Notification
    {
        $this->notification['play_sound'] = $value;
        return $this;
    }

    public function displayToasted(bool $value = true): Notification
    {
        $this->notification['display_toasted'] = $value;
        return $this;
    }

    public function hideToasted(): Notification
    {
        return $this->displayToasted(false);
    }

    public function toArray(): array
    {
        return $this->notification;
    }
}
