<?php

namespace MrThito\LaravelBilling\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Invoice;

class SendInvoiceMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $user, public Invoice $invoice) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __(
                '[:appName] Your invoice is available for :invoiceDate',
                [
                    'appName' => config('app.name', 'Laravel'),
                    'invoiceDate' => $this->invoice->date()->format(config('laravel-billing.date_time.date_format', 'M d, Y')),
                ]
            ),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'laravel-billing::emails.invoice',
            with: [
                'invoice' => $this->invoice,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $invoiceCompanyData = config('laravel-billing.invocing.company');
        $invoice = $this->invoice->pdf($invoiceCompanyData);
        // save the invoice to the disk
        $filename = 'invoice-'.$this->invoice->id.'.pdf';
        Storage::put($filename, $invoice);

        return [
            Storage::path($filename),
        ];
    }
}
