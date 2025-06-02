<?php

namespace App\Mail;

use App\Models\GradeRecheckApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RecheckApplicationStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The grade recheck application instance.
     *
     * @var \App\Models\GradeRecheckApplication
     */
    public $application;

    /**
     * Create a new message instance.
     *
     * @param \App\Models\GradeRecheckApplication $application
     * @return void
     */
    public function __construct(GradeRecheckApplication $application)
    {
        $this->application = $application->load('student', 'course'); // Eager load relationships for the email
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Grade Recheck Application (ID: ' . $this->application->id . ') Status Updated to ' . ucfirst($this->application->status->value),
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            html: 'emails.recheck-updated', // Points to the Blade template
            with: [
                'application' => $this->application,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}
