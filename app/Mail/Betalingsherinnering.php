<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Betalingsherinnering extends Mailable
{
    use Queueable, SerializesModels;


    public function __construct($data)
    {
        $this->data = $data;
    }


    public function build()
    {
      print_r($this->data);
        return $this
            ->subject('Betalingsherinnering Stampot')
            ->replyTo('stampot@scouting-ijsselgroep.nl', 'Stampot | Scouting IJsselgroep')
            ->from('noreply@scouting-ijsselgroep.nl','Stampot | Scouting IJsselgroep')
            ->with([
                    'balance' => $this->data['balance'],
                    'name' => $this->data['name'],
                  ])
            ->view('mail.betalingsherinnering');
    }
}
