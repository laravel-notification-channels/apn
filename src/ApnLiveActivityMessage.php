<?php

namespace NotificationChannels\Apn;

class ApnLiveActivityMessage extends ApnMessage
{
    /**
     * Value indicating the push type.
     */
    public ?ApnMessagePushType $pushType = ApnMessagePushType::LiveActivity;

    /**
     * The content state for live activities.
     */
    public ?array $contentState = null;

    /**
     * The event for live activities.
     */
    public ?string $event = null;

    /**
     * The timestamp for live activities.
     */
    public ?int $timestamp = null;

    /**
     * The attributes type for live activities.
     */
    public ?string $attributesType = null;

    /**
     * The attributes for live activities.
     */
    public array $attributes = [];

    /**
     * The dismissal date for live activities.
     */
    public ?int $dismissalDate = null;

    /**
     * Set content state for live activities.
     */
    public function contentState(?array $contentState): self
    {
        $this->contentState = $contentState;

        return $this;
    }

    /**
     * Set event for live activities.
     */
    public function event(?string $event): self
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Set timestamp for live activities.
     */
    public function timestamp(?int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Set attributes type for live activities.
     */
    public function attributesType(?string $attributesType): self
    {
        $this->attributesType = $attributesType;

        return $this;
    }

    /**
     * Add an attribute for live activities.
     */
    public function attribute(string $key, mixed $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * Set attributes for live activities.
     */
    public function attributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Set dismissal date for live activities.
     */
    public function dismissalDate(?int $dismissalDate): self
    {
        $this->dismissalDate = $dismissalDate;

        return $this;
    }
}
