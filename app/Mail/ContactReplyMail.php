<?php

namespace App\Mail;

use App\Models\Contact;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Log;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public Contact $contact;
    public string $replyMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Contact $contact, string $replyMessage)
    {
        $this->contact = $contact;
        $this->replyMessage = $replyMessage;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Re: Your Contact Message - We\'ve Replied',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        // return new Content(
        //     view: 'emails.contact-reply',
        //     with: [
        //         'contact' => $this->contact,
        //         'replyMessage' => $this->replyMessage,
        //     ],
        // );

         $x = new Content(
            view: 'emails.contact-reply',
            with: [
                'contact' => $this->contact,
                'replyMessage' => $this->replyMessage,
            ],
        );
        Log::info('=== Mail content ===', ['content' => $x]);
        return $x;
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}

