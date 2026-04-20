@component('mail::message')
# New Product Inquiry

You have received a new inquiry about **{{ $productName }}** on your website.

**Customer Details:**
- **Name:** {{ $inquiry->name }}
- **Email:** {{ $inquiry->email }}
- **Phone:** {{ $inquiry->phone ?? 'N/A' }}

**Message:**
> {{ $inquiry->message }}

Thank you for using our system!
@endcomponent