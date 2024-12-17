<?php

namespace App\Mail;

use App\Models\Loans_item;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OverdueLoanNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $loan;

    public function __construct(Loans_item $loan)
    {
        $this->loan = $loan;
    }

    public function build()
    {
        return $this->subject('Pemberitahuan Peminjaman Terlambat')
                    ->view('email.overdue_loan')
                    ->with([
                        'loan' => $this->loan,
                    ]);
    }
}
