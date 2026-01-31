<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;
use App\Models\Repair;

class RepairStatusUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public Repair $repair;
    public $status;
    public $notes;

    public function __construct(Repair $repair, $status, $notes = null)
    {
        $this->repair = $repair;
        $this->status = $status;
        $this->notes = $notes;
    }

    public function envelope(): Envelope
    {
        $statusLabel = match($this->status) {
            'Pending' => 'Repair Pending',
            'Received' => 'Device Received',
            'Diagnosed' => 'Diagnosis Complete',
            'In Progress' => 'Repair In Progress',
            'Quality Check' => 'Quality Check In Progress',
            'Quality Checked' => 'Quality Checked - Ready for Approval',
            'Cost Approval' => 'Cost Approved - Ready for Pickup',
            'Ready for Pickup' => 'Ready for Pickup',
            'Completed' => 'Repair Completed',
            default => 'Status Update',
        };

        return new Envelope(
            from: config('mail.from.address'),
            to: $this->repair->customer_email,
            replyTo: [config('mail.from.address')],
            subject: 'Update: ' . $statusLabel . ' - ' . $this->repair->invoice_number,
        );
    }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'X-Priority' => '3',
                'X-Mailer' => 'Skyeface Repair System v1.0',
                'X-MSMail-Priority' => 'Normal',
                'Precedence' => 'bulk',
                'List-Unsubscribe' => '<' . route('home') . '>',
                'List-Unsubscribe-Post' => 'List-Unsubscribe=One-Click',
                'X-Originating-IP' => '[127.0.0.1]',
                'Importance' => 'normal',
                'Content-Language' => 'en',
            ],
        );
    }

    public function content(): Content
    {
        $statusMap = [
            'Pending' => 'status_received',
            'Received' => 'status_received',
            'Diagnosed' => 'status_diagnosed',
            'In Progress' => 'status_in_progress',
            'Quality Check' => 'status_quality_check',
            'Quality Checked' => 'status_quality_checked',
            'Cost Approval' => 'status_ready_for_pickup',
            'Ready for Pickup' => 'status_ready_for_pickup',
            'Completed' => 'status_completed',
        ];

        $template = $statusMap[$this->status] ?? 'status_update';
        $viewName = 'emails.repairs.' . $template;
        
        \Log::info('RepairStatusUpdate email', [
            'status' => $this->status,
            'template_name' => $template,
            'view_name' => $viewName,
            'invoice' => $this->repair->invoice_number,
        ]);

        return new Content(
            html: $viewName,
            text: $viewName . '_text',
            with: [
                'repair' => $this->repair,
                'notes' => $this->notes,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
