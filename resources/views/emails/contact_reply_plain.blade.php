Hi {{ $ticket->user_name ?? 'Valued Customer' }},

Great news! Our support team has replied to your support ticket.

TICKET DETAILS
===============
Ticket Number: {{ $ticket->ticket_number }}
Subject: {{ $ticket->subject }}
Status: Pending Your Response

SUPPORT TEAM REPLY
==================
{{ $messageModel->message }}

NEXT STEPS
==========
You can view the full conversation and reply to this ticket here:
{{ url('/ticket/' . $ticket->ticket_number) }}

We're here to help! Feel free to reply anytime with any follow-up questions.

---
{{ config('app.name') }} Support Team
{{ config('app.url') }}

This is an automated message. Please do not reply directly to this email.
