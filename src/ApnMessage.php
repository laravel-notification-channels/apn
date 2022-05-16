<?php

namespace NotificationChannels\Apn;

use DateTime;
use Pushok\Client;

class ApnMessage
{
    /**
     * The background push type.
     *
     * @var string
     */
    const PUSH_TYPE_BACKGROUND = 'background';

    /**
     * The title of the notification.
     *
     * @var string
     */
    public $title;

    /**
     * The subtitle of the notification.
     *
     * @var string
     */
    public $subtitle;

    /**
     * The body of the notification.
     *
     * @var string
     */
    public $body;

    /**
     * The badge of the notification.
     *
     * @var int
     */
    public $badge;

    /**
     * The sound of the notification.
     *
     * @var string|null
     */
    public $sound;

    /**
     * The interruption level of the notification.
     *
     * @var string|null
     */
    public $interruptionLevel;

    /**
     * The category for action button.
     *
     * @var string|null
     * */
    public $category;

    /**
     * The thread ID of the notification.
     *
     * @var string|null
     * */
    public $threadId;

    /**
     * Value indicating incoming resource in the notification.
     *
     * @var int|null
     */
    public $contentAvailable = null;

    /**
     * The key to a title string in the Localizable.strings file for the current localization.
     *
     * @var string|null
     */
    public $titleLocKey;

    /**
     * Variable string values to appear in place of the format specifiers in title-loc-key.
     *
     * @var string|null
     */
    public $titleLocArgs;

    /**
     * If a string is specified, the iOS system displays an alert that includes the Close and View buttons.
     *
     * @var string|null
     */
    public $actionLocKey;

    /**
     * A key to an alert-message string in a Localizable.strings file for the current localization.
     *
     * @var string|null
     */
    public $locKey;

    /**
     * Variable string values to appear in place of the format specifiers in loc-key.
     *
     * @var array|null
     */
    public $locArgs;

    /**
     * Additional data of the notification.
     *
     * @var array
     */
    public $custom = [];

    /**
     * URL arguments of the notification.
     *
     * @var array
     */
    public $urlArgs = [];

    /**
     * Value indicating when the message will expire.
     *
     * @var \string
     */
    public $pushType = null;

    /**
     * The expiration time of the notification.
     *
     * @var \DateTime|null
     */
    public $expiresAt = null;

    /**
     * ID for the collapsing of similar notifications.
     *
     * @var string|null
     */
    public $collapseId;

    /**
     * Message specific client.
     *
     * @var \Pushok\Client|null
     */
    public $client = null;

    /**
     * The notification service app extension flag.
     *
     * @var int|null
     */
    public $mutableContent = null;

    /**
     * Custom alert for Edamov/Pushok.
     *
     * @var string|array|null
     */
    public $customAlert = null;

    /**
     * @param  string|null  $title
     * @param  string|null  $body
     * @param  array  $custom
     * @param  null|int  $badge
     * @return static
     */
    public static function create($title = null, $body = null, $custom = [], $badge = null)
    {
        return new static($title, $body, $custom, $badge);
    }

    /**
     * @param  string|null  $title
     * @param  string|null  $body
     * @param  array  $custom
     * @param  null|int  $badge
     */
    public function __construct($title = null, $body = null, $custom = [], $badge = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->custom = $custom;
        $this->badge = $badge;
    }

    /**
     * Set the alert title of the notification.
     *
     * @param  string  $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the alert subtitle of the notification.
     *
     * @param  string  $subtitle
     * @return $this
     */
    public function subtitle($subtitle)
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Set the alert message of the notification.
     *
     * @param  string  $body
     * @return $this
     */
    public function body($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Set the badge of the notification.
     *
     * @param  int  $badge
     * @return $this
     */
    public function badge($badge)
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Set the sound of the notification.
     *
     * @param  string|null  $sound
     * @return $this
     */
    public function sound($sound = 'default')
    {
        $this->sound = $sound;

        return $this;
    }

    /**
     * Set the interruptionLevel of the notification.
     *
     * @param string|null  $interruptionLevel
     * @return $this
     */
    public function interruptionLevel($interruptionLevel = 'active')
    {
        $this->interruptionLevel = $interruptionLevel;

        return $this;
    }

    /**
     * Set category of the notification.
     *
     * @param  string|null  $category
     * @return $this
     * */
    public function category($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Set thread ID of the notification.
     *
     * @param  string|null  $threadId
     * @return $this
     * */
    public function threadId($threadId)
    {
        $this->threadId = $threadId;

        return $this;
    }

    /**
     * Set content available value of the notification.
     *
     * @param  int  $value
     * @return $this
     */
    public function contentAvailable($value = 1)
    {
        $this->contentAvailable = $value;

        return $this;
    }

    /**
     * Set the push type of the notification.
     *
     * @param  string  $pushType
     * @return $this
     */
    public function pushType(string $pushType)
    {
        $this->pushType = $pushType;

        return $this;
    }

    /**
     * Set the expiration time for the message.
     *
     * @param  \DateTime  $expiresAt
     * @return $this
     */
    public function expiresAt(DateTime $expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Set the collapse ID of the notification.
     *
     * @param  string|null  $collapseId
     * @return $this
     */
    public function collapseId($collapseId)
    {
        $this->collapseId = $collapseId;

        return $this;
    }

    /**
     * Set a title-loc-key.
     *
     * @param  string|null  $titleLocKey
     * @return $this
     */
    public function titleLocKey($titleLocKey = null)
    {
        $this->titleLocKey = $titleLocKey;

        return $this;
    }

    /**
     * Set the title-loc-args.
     *
     * @param  array|null  $titleLocArgs
     * @return $this
     */
    public function titleLocArgs(array $titleLocArgs = null)
    {
        $this->titleLocArgs = $titleLocArgs;

        return $this;
    }

    /**
     * Set an action-loc-key.
     *
     * @param  string|null  $actionLocKey
     * @return $this
     */
    public function actionLocKey($actionLocKey = null)
    {
        $this->actionLocKey = $actionLocKey;

        return $this;
    }

    /**
     * Set a loc-key.
     *
     * @param  string  $locKey
     * @return $this
     */
    public function setLocKey($locKey)
    {
        $this->locKey = $locKey;

        return $this;
    }

    /**
     * Set the loc-args.
     *
     * @param  array  $locArgs
     * @return $this
     */
    public function setLocArgs($locArgs)
    {
        $this->locArgs = $locArgs;

        return $this;
    }

    /**
     * Add custom data to the notification.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function custom($key, $value)
    {
        $this->custom[$key] = $value;

        return $this;
    }

    /**
     * Sets custom alert value as JSON or Array.
     *
     * @param  string|array  $customAlert
     * @return $this
     */
    public function setCustomAlert($customAlert)
    {
        $this->customAlert = $customAlert;

        return $this;
    }

    /**
     * Override the data of the notification.
     *
     * @param  array  $custom
     * @return $this
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * Add a URL argument to the notification.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function urlArg($key, $value)
    {
        $this->urlArgs[$key] = $value;

        return $this;
    }

    /**
     * Override the URL arguemnts of the notification.
     *
     * @param  array  $urlArgs
     * @return $this
     */
    public function setUrlArgs($urlArgs)
    {
        $this->urlArgs = $urlArgs;

        return $this;
    }

    /**
     * Add an action to the notification.
     *
     * @param  string  $action
     * @param  mixed  $params
     * @return $this
     */
    public function action($action, $params = null)
    {
        return $this->custom('action', [
            'action' => $action,
            'params' => $params,
        ]);
    }

    /**
     * Set message specific client.
     *
     * @param \Pushok\Client
     * @return $this
     */
    public function via(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Set mutable content value of the notification.
     *
     * @param  int  $value
     * @return $this
     */
    public function mutableContent($value = 1)
    {
        $this->mutableContent = $value;

        return $this;
    }
}
