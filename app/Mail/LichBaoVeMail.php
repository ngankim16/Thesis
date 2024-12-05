<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
class LichBaoVeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ma_bv, $gio_bat_dau, $ma_ph;

    public function __construct($ma_bv, $gio_bat_dau, $ma_ph)
    {
        $this->ma_bv = $ma_bv;
        $this->gio_bat_dau = $gio_bat_dau;
        $this->ma_ph = $ma_ph;
    }

    public function build()
    {
        return $this->view('admin.emails.thongbao')
                    ->subject('Thông báo lịch bảo vệ')
                    ->with([
                        'ma_bv' => $this->ma_bv,
                        'gio_bat_dau' => $this->gio_bat_dau,
                        'ma_ph' => $this->ma_ph,
                    ]);
    }
}