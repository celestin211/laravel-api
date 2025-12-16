<?php

namespace App\Notifications;

use App\Models\Offer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification sent to admins when an offer is published.
 */
class OfferPublishedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Offer $offer
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject('Nouvelle offre publiée : '.$this->offer->name)
            ->line('Une nouvelle offre a été publiée sur la plateforme.')
            ->line('Offre : '.$this->offer->name)
            ->line('Slug : '.$this->offer->slug)
            ->action('Voir l\'offre', url('/offers/'.$this->offer->id))
            ->line('Merci d\'utiliser notre application !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'offer_id' => $this->offer->id,
            'offer_name' => $this->offer->name,
            'offer_slug' => $this->offer->slug,
            'message' => 'Une nouvelle offre a été publiée : '.$this->offer->name,
        ];
    }
}
