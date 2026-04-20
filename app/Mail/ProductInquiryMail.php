<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductInquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $inquiry;
    public $productName;

    public function __construct($inquiry, $productName)
    {
        $this->inquiry = $inquiry;
        $this->productName = $productName;
    }

    public function build()
    {
        return $this->subject('New Product Inquiry: ' . $this->productName)
                    ->markdown('emails.product-inquiry');
    }
}