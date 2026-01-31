@extends('layouts.admin.app')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header -->
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1><i class="fa fa-quote-left mr-2"></i>Quote #{{ $quote->id }}</h1>
            <small class="text-muted">Submitted: {{ $quote->created_at->format('M d, Y \a\t H:i') }}</small>
        </div>
        <div class="col-md-4 text-right">
            @php
                $statusColors = ['new' => 'primary', 'reviewed' => 'warning', 'quoted' => 'success', 'rejected' => 'danger', 'accepted' => 'info'];
                $color = $statusColors[$quote->status] ?? 'secondary';
            @endphp
            <span class="badge badge-{{ $color }} px-3 py-2" style="font-size: 14px;">{{ ucfirst($quote->status) }}</span>
            @if($quote->notified)
                <span class="badge badge-secondary px-3 py-2 ml-2" style="font-size: 14px;">Notified</span>
            @endif
        </div>
    </div>

    <!-- Success Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column: Quote Details -->
        <div class="col-md-8">
            <!-- Customer Info Card -->
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fa fa-user mr-2"></i>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-3">
                                <strong>Name</strong><br>
                                <span class="text-dark">{{ $quote->name }}</span>
                            </p>
                            <p class="mb-3">
                                <strong>Email</strong><br>
                                <a href="mailto:{{ $quote->email }}">{{ $quote->email }}</a>
                            </p>
                            <p class="mb-3">
                                <strong>Phone</strong><br>
                                <span class="text-dark">{{ $quote->phone ?? 'Not provided' }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-3">
                                <strong>IP Address</strong><br>
                                <code>{{ $quote->ip_address ?? 'N/A' }}</code>
                            </p>
                            <p class="mb-3">
                                <strong>Package</strong><br>
                                <span class="badge badge-light">{{ $quote->package ?? 'General Inquiry' }}</span>
                            </p>
                            <p class="mb-0">
                                <strong>Submission Date</strong><br>
                                <span class="text-dark">{{ $quote->created_at->format('M d, Y H:i') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Details Card -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fa fa-file-text mr-2"></i>Project Details</h5>
                </div>
                <div class="card-body">
                    <p style="white-space: pre-wrap; line-height: 1.6;">{{ $quote->details }}</p>
                </div>
            </div>

            <!-- Admin Notes Card -->
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0"><i class="fa fa-sticky-note mr-2"></i>Internal Team Notes</h5>
                </div>
                <div class="card-body">
                    <form id="notesForm" method="POST" action="{{ route('admin.quotes.addNotes', $quote->id) }}">
                        @csrf
                        <textarea name="admin_notes" class="form-control" rows="4" placeholder="Add internal notes for your team (not visible to customer)...">{{ $quote->admin_notes }}</textarea>
                        <button type="button" onclick="saveNotes()" class="btn btn-sm btn-warning mt-2">
                            <i class="fa fa-save mr-1"></i>Save Notes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Response Card (if already responded) -->
            @if($quote->responded_at)
                <div class="card mb-3 border-success">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fa fa-reply mr-2"></i>Response Sent</h5>
                        <small>{{ $quote->responded_at->format('M d, Y H:i') }}</small>
                    </div>
                    <div class="card-body">
                        @if($quote->quoted_price)
                            <p class="mb-2"><strong>Quote Price:</strong> <span class="text-success" style="font-size: 18px;"><strong>₦{{ number_format($quote->quoted_price, 2) }}</strong></span></p>
                        @endif
                        <p class="mt-3" style="white-space: pre-wrap; line-height: 1.6;">{{ $quote->response }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Column: Actions -->
        <div class="col-md-4">
            <!-- Status Update Card -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fa fa-exchange mr-2"></i>Change Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.quotes.updateStatus', $quote->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-2">
                            <select name="status" class="form-control" required>
                                <option value="">-- Select Status --</option>
                                @foreach($statuses ?? [] as $statusKey => $statusLabel)
                                    <option value="{{ $statusKey }}" {{ $quote->status === $statusKey ? 'selected' : '' }}>
                                        {{ $statusLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa fa-check mr-1"></i>Update Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Send Response Card -->
            @if(!$quote->responded_at || $quote->status !== 'rejected')
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h5 class="card-title mb-0"><i class="fa fa-envelope mr-2"></i>Send Response</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.quotes.respond', $quote->id) }}">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="resp_status" class="small"><strong>Response Status</strong></label>
                                <select name="status" id="resp_status" class="form-control form-control-sm" required onchange="updateTemplateOptions()">
                                    <option value="quoted">Send Quote</option>
                                    <option value="rejected">Reject</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="template_select" class="small"><strong>Email Template</strong></label>
                                <select id="template_select" class="form-control form-control-sm" onchange="loadTemplate()">
                                    <option value="">-- Select a template --</option>
                                    <option value="quote_thank_you">Quote: Thank You & Proposal</option>
                                    <option value="quote_professional">Quote: Professional Response</option>
                                    <option value="quote_custom">Quote: Custom Message</option>
                                    <option value="reject_professional">Rejection: Professional</option>
                                    <option value="reject_friendly">Rejection: Friendly</option>
                                </select>
                            </div>
                            <div class="form-group mb-2">
                                <label for="price" class="small"><strong>Quote Price</strong></label>
                                <div class="input-group input-group-sm">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₦</span>
                                    </div>
                                    <input type="number" name="quoted_price" id="price" class="form-control" step="0.01" placeholder="0.00">
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label for="msg" class="small"><strong>Your Message</strong> <small class="text-muted">(Editable)</small></label>
                                <div class="mb-2">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="insertQuickMessage('intro')" title="Insert introduction">
                                            <i class="fa fa-plus"></i> Intro
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="insertQuickMessage('timeline')" title="Insert timeline">
                                            <i class="fa fa-clock-o"></i> Timeline
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="insertQuickMessage('scope')" title="Insert scope details">
                                            <i class="fa fa-list"></i> Scope
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="insertQuickMessage('closing')" title="Insert closing">
                                            <i class="fa fa-handshake-o"></i> Closing
                                        </button>
                                    </div>
                                </div>
                                <textarea name="response" id="msg" class="form-control form-control-sm" rows="6" required minlength="10" placeholder="Start typing or select a template above, then customize this message..."></textarea>
                                <small class="form-text text-muted">
                                    💡 Tip: Select a template first, then use the buttons above to insert suggested sections or edit the text directly.
                                </small>
                            </div>
                            <button type="submit" class="btn btn-success btn-block btn-sm">
                                <i class="fa fa-send mr-1"></i>Send Response
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Actions Card -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0"><i class="fa fa-cogs mr-2"></i>Actions</h5>
                </div>
                <div class="card-body">
                    <a href="mailto:{{ $quote->email }}" class="btn btn-info btn-sm btn-block mb-2">
                        <i class="fa fa-envelope mr-1"></i>Email Customer
                    </a>
                    <a href="{{ route('admin.quotes.index') }}" class="btn btn-secondary btn-sm btn-block mb-2">
                        <i class="fa fa-arrow-left mr-1"></i>Back to List
                    </a>
                    <button type="button" onclick="deleteQuote()" class="btn btn-danger btn-sm btn-block">
                        <i class="fa fa-trash mr-1"></i>Delete Quote
                    </button>
                    <form id="deleteForm" action="{{ route('admin.quotes.destroy', $quote->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

            <!-- Payment Info Card -->
            @if($quote->quoted_price)
                <div class="card mt-3">
                    <div class="card-header bg-info text-white">
                        <h5 class="card-title mb-0"><i class="fa fa-credit-card mr-2"></i>Payment Status</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $payment = $quote->payments()->latest()->first();
                            $hasPayment = $payment && $payment->isCompleted();
                        @endphp

                        @if($hasPayment)
                            <div class="alert alert-success mb-0">
                                <i class="fa fa-check-circle mr-2"></i>
                                <strong>Payment Received</strong><br>
                                <small>
                                    Amount: ${{ number_format($payment->amount, 2) }}<br>
                                    Reference: {{ $payment->reference }}<br>
                                    Paid: {{ $payment->paid_at->format('M d, Y H:i') }}
                                </small>
                            </div>
                        @else
                            <p class="text-muted mb-2">
                                <i class="fa fa-info-circle mr-2"></i>
                                Quote amount: <strong>₦{{ number_format($quote->quoted_price, 2) }}</strong>
                            </p>
                            @if($payment && $payment->status === 'pending')
                                <div class="alert alert-warning mb-2">
                                    <i class="fa fa-clock-o mr-2"></i>Payment pending since {{ $payment->created_at->format('M d, Y') }}
                                </div>
                            @elseif($payment && $payment->status === 'failed')
                                <div class="alert alert-danger mb-2">
                                    <i class="fa fa-times-circle mr-2"></i>Payment failed
                                </div>
                            @else
                                <p class="small text-muted">No payment received yet</p>
                            @endif
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
const customerName = "{{ $quote->name }}";

// Quick message suggestions for different sections
const quickMessages = {
    intro: `Dear {{ $quote->name }},

Thank you for your inquiry. We appreciate the opportunity to work with you.`,

    timeline: `Timeline:
- Project Start: [Date]
- Milestones: [Milestone Details]
- Completion: [Date]
- Revisions: [Number of rounds included]`,

    scope: `Scope of Work:
- [Deliverable 1]
- [Deliverable 2]
- [Deliverable 3]
- [Deliverable 4]`,

    closing: `Next Steps:
1. Review this proposal
2. Confirm timeline and requirements
3. We'll proceed with project initiation

Please feel free to reach out with any questions.

Best regards,
Skyeface Team`
};

// Email templates for quotes and rejections
const emailTemplates = {
    quote_thank_you: `Dear {{ $quote->name }},

Thank you for reaching out to us! We appreciate your interest in our services.

We have reviewed your project requirements and would like to present our proposal for your consideration.

Project Overview:
Based on your submission, we understand you're looking for [SERVICE DESCRIPTION]. We're confident we can deliver exceptional results.

Our Proposal:
- Timeline: [TIMELINE]
- Deliverables: [DELIVERABLES]
- Investment: [PRICE will be added separately]

Next Steps:
We would be delighted to discuss this further and answer any questions you may have. Please feel free to reach out at your convenience.

Looking forward to working with you!

Best regards,
Skyeface Team`,

    quote_professional: `Dear {{ $quote->name }},

Thank you for your inquiry. We have carefully reviewed your requirements and are pleased to provide a quotation for your project.

Proposal Summary:
We are proposing a comprehensive solution tailored to meet your specific needs. Our team has extensive experience in delivering high-quality results.

Investment: [PRICE will be added separately]
Timeline: [TIMELINE]

We believe this solution offers excellent value and will help you achieve your business objectives.

Please review the attached details and let us know if you have any questions or would like to schedule a discussion.

Best regards,
Skyeface Team`,

    quote_custom: `Dear {{ $quote->name }},

Thank you for choosing to work with us!

[ADD YOUR CUSTOM MESSAGE HERE]

We look forward to collaborating with you.

Best regards,
Skyeface Team`,

    reject_professional: `Dear {{ $quote->name }},

Thank you for your inquiry and for considering Skyeface for your project.

After careful review of your requirements, we have determined that this project falls outside our current service offering or capacity at this time. We want to ensure that any project we undertake receives our full attention and expertise.

We appreciate you thinking of us and wish you the very best with your project. Should your needs align with our services in the future, please don't hesitate to reach out.

Best regards,
Skyeface Team`,

    reject_friendly: `Dear {{ $quote->name }},

Thank you for your interest in Skyeface!

Unfortunately, we're unable to move forward with your project at this time. However, we genuinely appreciate you reaching out to us.

If you have any future projects that might be a better fit, we'd love to hear from you.

Best regards,
Skyeface Team`
};

function insertQuickMessage(type) {
    const messageField = document.getElementById('msg');
    const currentText = messageField.value;
    const insertText = quickMessages[type];

    if (currentText.trim()) {
        // Add to existing text with a line break
        messageField.value = currentText + '\n\n' + insertText;
    } else {
        // Replace empty field
        messageField.value = insertText;
    }

    // Scroll to the textarea
    messageField.focus();
    messageField.scrollIntoView({ behavior: 'smooth' });
}

function updateTemplateOptions() {
    const status = document.getElementById('resp_status').value;
    const templateSelect = document.getElementById('template_select');

    // Clear current options except first
    while (templateSelect.options.length > 1) {
        templateSelect.remove(1);
    }
    templateSelect.value = '';

    if (status === 'quoted') {
        templateSelect.add(new Option('Quote: Thank You & Proposal', 'quote_thank_you'));
        templateSelect.add(new Option('Quote: Professional Response', 'quote_professional'));
        templateSelect.add(new Option('Quote: Custom Message', 'quote_custom'));
    } else if (status === 'rejected') {
        templateSelect.add(new Option('Rejection: Professional', 'reject_professional'));
        templateSelect.add(new Option('Rejection: Friendly', 'reject_friendly'));
    }
}

function loadTemplate() {
    const templateKey = document.getElementById('template_select').value;
    const messageField = document.getElementById('msg');

    if (templateKey && emailTemplates[templateKey]) {
        messageField.value = emailTemplates[templateKey];
        messageField.focus();
    }
}

function deleteQuote() {
    if (confirm('Are you sure you want to delete this quote? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}

function saveNotes() {
    const notes = document.querySelector('textarea[name="admin_notes"]').value;
    const formData = new FormData();
    formData.append('admin_notes', notes);
    formData.append('_token', document.querySelector('input[name="_token"]').value);

    fetch('{{ route("admin.quotes.addNotes", $quote->id) }}', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            alert('Notes saved successfully!');
        } else {
            alert('Error saving notes: ' + (d.message || 'Unknown error'));
        }
    })
    .catch(e => alert('Error: ' + e));
}
</script>
@endsection
