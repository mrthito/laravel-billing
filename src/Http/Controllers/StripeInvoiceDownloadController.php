<?php

namespace MrThito\LaravelBilling\Http\Controllers;

use Illuminate\Http\Request;

class StripeInvoiceDownloadController
{
    public function __invoke(Request $request)
    {
        $invoiceId = $request->query('invoice');
        $user = auth()->user();
        $invoice = $user->findInvoice($invoiceId);

        if (! $invoice) {
            abort(404);
        }

        return response()->streamDownload(function () use ($invoice) {
            echo $invoice->download();
        }, 'invoice.pdf');
    }
}
