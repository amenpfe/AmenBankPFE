<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailAddUserNotification extends Notification
{
    use Queueable;
    public $name, $role, $password;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $role, $password)
    {
        $this->name = $name;
        $this->role = $role;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    
                    ->line('Bonjour <b>'.$this->name. '</b>')
                    ->line('Vous avez été ajouté par l\'administrateur en tant que <b>'.$this->role.'</b>')
                    ->line('Votre mot de passe est: ')
                    ->line('<b style="color: black; font-size: 30px;">'.$this->password.'</b>');
                    
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
