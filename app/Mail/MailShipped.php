<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class MailShipped extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    private $name;
    private $email;
    private $text;

    public function __construct(Request $request)
    {
        $this->name = $request->name;
        $this->email = $request->email;
        $this->text = $request->text;
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->email, $this->name)
            ->subject('Question')
            ->view(config('settings.theme') . '.email')
            ->with([
                'email' => $this->email,
                'name' => $this->name,
                'text' => $this->text
            ]);
        
    }
}
