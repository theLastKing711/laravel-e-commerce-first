<?php

namespace App\Broadcasting;

use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;

class WhatsAppChannel
{
    // notifiable is a model that uses notifiable trait, user (user model by default have it
    // notification extends notification, i.e OrderAccepted
    //,is set in $user->notify(new OrderAccepted($order))
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsApp($notifiable);

        //look up routeNotificationForWhatsApp method in notifiable user's model,
        //which return's user's number
        $to = $notifiable->routeNotificationFor('WhatsApp');
        $from = config('services.twilio.whatsapp_from');

        $twilio = new Client(config('services.twilio.sid'), config('services.twilio.token'));

        return $twilio
            ->messages
            ->create(
                'whatsapp:'.$to,
                [
                    'from' => 'whatsapp:'.$from,
                    'body' => $message->content,
                ]
            );
    }
}
