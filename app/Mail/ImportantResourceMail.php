<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ImportantResourceMail extends Mailable
{
    use Queueable, SerializesModels;
    public $resource;

    /**
     * Create a new message instance.
     */
    public function __construct($resource)
    {
        // Initialiser la ressource importante
        $this->resource = $resource;
    }

    public function build()
    {
        return $this->subject('Nouvelle ressource importante ajoutée')
                    ->view('emails.important_resource')
                    ->with([
                        'resourceTitle' => $this->resource->title,
                        'resourceDescription' => $this->resource->description,
                        'resourceUrl' => $this->resource->url,
                    ]);
    }       
    public function attachments(): array
    {
        return [];
    }
}
