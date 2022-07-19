<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MediaRelatorioNotification extends Notification
{
    use Queueable;

    protected $cliente_id;
    protected $file;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->client_id = $data['client_id'];
        $this->file = $data['file'];
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
        $notifiable->email = ['robsonferduda@gmail.com','rafael01costa@gmail.com','alvaro@studioclipagem.com.br'];
        $url = url('file/'.$this->client_id.'/'.$this->file);

        return (new MailMessage)
                    ->from('boletins@clipagens.com.br')
                    ->subject('Relatório de Mídias')
                    ->markdown('email.regra_processada',['url' => $url])
                    ->line('Relatório gerado com sucesso.')
                    ->line('Utilize o endereço abaixo para baixar o arquivo.')
                    ->line($url)
                    ->line('Uma cópia do arquivo ficará disponível para download no sistema.')
                    ->line('Acesse https://studiosocial.app/relatorios/postagens e selecione o cliente desejado.');
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
