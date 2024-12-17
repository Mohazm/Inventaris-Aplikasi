<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OverdueLoanNotification extends Notification
{
    use Queueable;
    protected $loan;

    /**
     * Create a new notification instance.
     */
    public function __construct($loan)
    {
        $this->loan = $loan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail']; // Mengirimkan email
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Peminjaman Anda Telah Melebihi Batas Waktu')
            ->line("Halo {$notifiable->name},")
            ->line("Peminjaman barang dengan ID: {$this->loan->id} telah melewati batas waktu pengembalian.")
            ->line("Tanggal jatuh tempo: {$this->loan->tanggal_kembali->format('d M Y')}")
            ->line("Harap segera mengembalikan barang tersebut untuk menghindari denda.")
            ->line('Terima kasih telah menggunakan layanan kami!');
    }
}
