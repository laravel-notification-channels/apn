<?php

namespace NotificationChannels\Apn;

use DateTime;
use Pushok\Client;
use Pushok\Payload\Sound;

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
     */
    public ?string $title = null;

    /**
     * The subtitle of the notification.
     */
    public ?string $subtitle = null;

    /**
     * The body of the notification.
     */
    public ?string $body = null;

    /**
     * The badge of the notification.
     */
    public ?int $badge;

    /**
     * The sound of the notification.
     */
    public null|string|Sound $sound = null;

    /**
     * The interruption level of the notification.
     */
    public ?string $interruptionLevel = null;

    /**
     * The category for action button.
     */
    public ?string $category = null;

    /**
     * The thread ID of the notification.
     */
    public ?string $threadId = null;

    /**
     * Value indicating incoming resource in the notification.
     */
    public ?int $contentAvailable = null;

    /**
     * The key to a title string in the Localizable.strings file for the current localization.
     */
    public ?string $titleLocKey = null;

    /**
     * Variable string values to appear in place of the format specifiers in title-loc-key.
     */
    public ?array $titleLocArgs = null;

    /**
     * If a string is specified, the iOS system displays an alert that includes the Close and View buttons.
     */
    public ?string $actionLocKey = null;

    /**
     * A key to an alert-message string in a Localizable.strings file for the current localization.
     */
    public ?string $locKey = null;

    /**
     * Variable string values to appear in place of the format specifiers in loc-key.
     */
    public ?array $locArgs = null;

    /**
     * Additional data of the notification.
     */
    public array $custom = [];

    /**
     * URL arguments of the notification.
     */
    public array $urlArgs = [];

    /**
     * Value indicating when the message will expire.
     */
    public ?string $pushType = null;

    /**
     * The expiration time of the notification.
     */
    public ?DateTime $expiresAt = null;

    /**
     * ID for the collapsing of similar notifications.
     */
    public ?string $collapseId = null;

    /**
     * A canonical UUID that is the unique ID for the notification.
     * APNs includes this value when reporting the error to your server.
     */
    public ?string $apnsId = null;

    /**
     * The message priority.
     */
    public ?ApnMessagePriority $priority = null;

    /**
     * Message specific client.
     */
    public ?Client $client = null;

    /**
     * The notification service app extension flag.
     */
    public ?int $mutableContent = null;

    /**
     * Custom alert for Edamov/Pushok.
     *
     * @var string|array|null
     */
    public $customAlert = null;

    /**
     * Create a new messages instance.
     */
    public function __construct(?string $title = null, ?string $body = null, array $custom = [], ?int $badge = null)
    {
        $this->title = $title;
        $this->body = $body;
        $this->custom = $custom;
        $this->badge = $badge;
    }

    /**
     * Create a new message instance.
     */
    public static function create(?string $title = null, ?string $body = null, array $custom = [], ?int $badge = null): static
    {
        return new static($title, $body, $custom, $badge);
    }

    /**
     * Set the alert title of the notification.
     */
    public function title(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set the alert subtitle of the notification.
     */
    public function subtitle(?string $subtitle): self
    {
        $this->subtitle = $subtitle;

        return $this;
    }

    /**
     * Set the alert message of the notification.
     */
    public function body(?string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Set the badge of the notification.
     */
    public function badge(?int $badge): self
    {
        $this->badge = $badge;

        return $this;
    }

    /**
     * Set the sound of the notification.
     */
    public function sound(null|string|Sound $sound = 'default'): self
    {
        $this->sound = $sound;

        return $this;
    }

    /**
     * Set the interruptionLevel of the notification.
     */
    public function interruptionLevel(string|ApnMessageInterruptionLevel|null $interruptionLevel = 'active'): self
    {
        $this->interruptionLevel = $interruptionLevel instanceof ApnMessageInterruptionLevel
            ? $interruptionLevel->value
            : $interruptionLevel;

        return $this;
    }

    /**
     * Set category of the notification.
     * */
    public function category(?string $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Set thread ID of the notification.
     * */
    public function threadId(?string $threadId): self
    {
        $this->threadId = $threadId;

        return $this;
    }

    /**
     * Set content available value of the notification.
     */
    public function contentAvailable(?int $value = 1): self
    {
        $this->contentAvailable = $value;

        return $this;
    }

    /**
     * Set the push type of the notification.
     */
    public function pushType(?string $pushType): self
    {
        $this->pushType = $pushType;

        return $this;
    }

    /**
     * Set the expiration time for the message.
     */
    public function expiresAt(DateTime $expiresAt): self
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Set the collapse ID of the notification.
     */
    public function collapseId(?string $collapseId): self
    {
        $this->collapseId = $collapseId;

        return $this;
    }

    /**
     * Set the APNS ID.
     */
    public function apnsId(?string $apnsId): self
    {
        $this->apnsId = $apnsId;

        return $this;
    }

    /**
     * Set the message priority.
     */
    public function priority(ApnMessagePriority $priority): self
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Set a title-loc-key.
     */
    public function titleLocKey(?string $titleLocKey = null): self
    {
        $this->titleLocKey = $titleLocKey;

        return $this;
    }

    /**
     * Set the title-loc-args.
     */
    public function titleLocArgs(array $titleLocArgs = null): self
    {
        $this->titleLocArgs = $titleLocArgs;

        return $this;
    }

    /**
     * Set an action-loc-key.
     */
    public function actionLocKey($actionLocKey = null): self
    {
        $this->actionLocKey = $actionLocKey;

        return $this;
    }

    /**
     * Set a loc-key.
     */
    public function setLocKey(?string $locKey): self
    {
        $this->locKey = $locKey;

        return $this;
    }

    /**
     * Set the loc-args.
     */
    public function setLocArgs(?array $locArgs): self
    {
        $this->locArgs = $locArgs;

        return $this;
    }

    /**
     * Add custom data to the notification.
     */
    public function custom(string $key, mixed $value): self
    {
        $this->custom[$key] = $value;

        return $this;
    }

    /**
     * Sets custom alert value as JSON or Array.
     *
     * @param  string|array  $customAlert
     */
    public function setCustomAlert($customAlert): self
    {
        $this->customAlert = $customAlert;

        return $this;
    }

    /**
     * Override the data of the notification.
     */
    public function setCustom(array $custom): self
    {
        $this->custom = $custom;

        return $this;
    }

    /**
     * Add a URL argument to the notification.
     */
    public function urlArg(string $key, mixed $value): self
    {
        $this->urlArgs[$key] = $value;

        return $this;
    }

    /**
     * Override the URL arguemnts of the notification.
     */
    public function setUrlArgs(array $urlArgs): self
    {
        $this->urlArgs = $urlArgs;

        return $this;
    }

    /**
     * Add an action to the notification.
     */
    public function action(string $action, mixed $params = null): self
    {
        return $this->custom('action', [
            'action' => $action,
            'params' => $params,
        ]);
    }

    /**
     * Set message specific client.
     */
    public function via(Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Set mutable content value of the notification.
     */
    public function mutableContent(?int $value = 1): self
    {
        $this->mutableContent = $value;

        return $this;
    }
}
