<?php

namespace App\Mail;

use App\Models\Student;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class StudentAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $user;
    public $temporaryPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(Student $student, User $user, string $temporaryPassword)
    {
        $this->student = $student;
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Email sending failed: ' . $exception->getMessage(), [
            'student_id' => $this->student->id,
            'exception' => get_class($exception),
            'trace' => $exception->getTraceAsString()
        ]);
    }

    /**
     * Get the message envelope. 
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Student Account Created - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.student-account-created',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
