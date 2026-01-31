Hi {{ $ticket->user_name ?? 'Valued Customer' }},

Thank you for reaching out to us! Your support ticket has been closed.

TICKET DETAILS
===============
Ticket Number: {{ $ticket->ticket_number }}
Subject: {{ $ticket->subject }}
Status: Closed

NEED MORE HELP?
===============
If you need further assistance on this matter, you can reply to your ticket or submit a new support request.

View your ticket here:
{{ url('/ticket/' . $ticket->ticket_number) }}

We appreciate your business and look forward to helping you again in the future!

---
{{ config('app.name') }} Support Team
{{ config('app.url') }}

This is an automated message. Please do not reply directly to this email.
