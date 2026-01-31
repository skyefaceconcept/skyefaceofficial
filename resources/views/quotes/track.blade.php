<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Track Your Quote — {{ config('company.name') }}</title>
    <link rel="shortcut icon" href="{{ \App\Helpers\CompanyHelper::favicon() }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
    <link href="{{ asset('buzbox/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('buzbox/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; }
        .container { max-width: 600px; }
        .card { box-shadow: 0 10px 40px rgba(0,0,0,0.2); border-radius: 10px; border: none; }
        .badge { padding: 8px 15px; font-size: 13px; }
        .status-timeline { margin-top: 30px; }
        .status-item { display: flex; margin-bottom: 20px; }
        .status-item.completed .status-icon { background: #28a745; }
        .status-item.pending .status-icon { background: #ccc; }
        .status-icon { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; margin-right: 15px; flex-shrink: 0; }
        .status-info h6 { margin-bottom: 3px; font-weight: 600; }
        .status-info small { color: #999; }
        .quote-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <!-- Header -->
                <div class="text-center mb-4">
                    <h1 class="text-white mb-2"><i class="fa fa-quote-left mr-2"></i>Track Your Quote</h1>
                    <p class="text-light">Enter your quote ID and email to check the status of your request</p>
                </div>

                <!-- Search Form Card -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0"><i class="fa fa-search mr-2"></i>Find Your Quote</h5>
                    </div>
                    <div class="card-body">
                        <form id="trackForm" onsubmit="trackQuote(event)">
                            <div class="form-group">
                                <label for="email"><strong>Email Address</strong></label>
                                <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="your@email.com" required>
                                <small class="form-text text-muted">The email you used when submitting the quote</small>
                            </div>
                            <div class="form-group">
                                <label for="quote_id"><strong>Quote ID</strong></label>
                                <input type="number" id="quote_id" name="id" class="form-control form-control-lg" placeholder="12345" min="1" required>
                                <small class="form-text text-muted">Found in your quote confirmation email</small>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                <i class="fa fa-search mr-2"></i>Track Quote
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Results Card (hidden by default) -->
                <div id="resultsCard" class="card" style="display: none;">
                    <div class="card-body" id="resultsContent"></div>
                </div>

                <!-- Error Message (hidden by default) -->
                <div id="errorAlert" class="alert alert-danger alert-dismissible fade show" role="alert" style="display: none;">
                    <i class="fa fa-exclamation-circle mr-2"></i>
                    <span id="errorMessage"></span>
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery & Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        async function trackQuote(event) {
            event.preventDefault();
            
            const email = document.getElementById('email').value;
            const quoteId = document.getElementById('quote_id').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                // Hide previous results
                document.getElementById('resultsCard').style.display = 'none';
                document.getElementById('errorAlert').style.display = 'none';

                const response = await fetch('{{ route("quotes.track") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ email, id: quoteId })
                });

                const data = await response.json();

                if (data.success && data.quote) {
                    displayQuoteInfo(data.quote);
                } else {
                    showError(data.message || 'Quote not found. Please check your email and quote ID.');
                }
            } catch (error) {
                showError('An error occurred while tracking your quote. Please try again.');
                console.error('Error:', error);
            }
        }

        function displayQuoteInfo(quote) {
            const statusColors = {
                'new': 'primary',
                'reviewed': 'warning',
                'quoted': 'success',
                'rejected': 'danger',
                'accepted': 'info'
            };
            const statusLabels = {
                'new': 'Submitted',
                'reviewed': 'Under Review',
                'quoted': 'Quote Provided',
                'rejected': 'Not Interested',
                'accepted': 'Accepted'
            };

            const color = statusColors[quote.status] || 'secondary';
            const label = statusLabels[quote.status] || 'Unknown';

            let html = `
                <div class="row mb-4">
                    <div class="col-md-8">
                        <h4><i class="fa fa-quote-left mr-2"></i>Quote #${quote.id}</h4>
                        <p class="text-muted mb-0">Submitted: ${new Date(quote.created_at).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'})}</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <span class="badge badge-${color} px-3 py-2" style="font-size: 14px;">${label}</span>
                    </div>
                </div>

                <div class="quote-info">
                    <strong>Package:</strong> ${quote.package || 'General Service'}<br>
                    <strong>Status:</strong> ${label}
                </div>
            `;

            if (quote.quoted_price) {
                html += `
                    <div class="quote-info" style="background: #d4edda; border: 1px solid #c3e6cb;">
                        <h5 class="text-success mb-2"><i class="fa fa-dollar mr-2"></i>Quote Amount</h5>
                        <h3 class="text-success mb-0">$${parseFloat(quote.quoted_price).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h3>
                    </div>
                `;
            }

            if (quote.response) {
                html += `
                    <div class="quote-info" style="background: #e7f3ff; border-left: 4px solid #0066cc;">
                        <h6 class="mb-2"><i class="fa fa-comment mr-2"></i>Message from Our Team</h6>
                        <p class="mb-0" style="white-space: pre-wrap; line-height: 1.6;">${quote.response}</p>
                    </div>
                `;
            }

            // Timeline
            html += `
                <div class="status-timeline mt-4">
                    <h6 class="mb-3"><i class="fa fa-clock-o mr-2"></i>Timeline</h6>
            `;

            const timeline = [
                { status: 'new', label: 'Submitted', date: quote.created_at, completed: true },
                { status: 'reviewed', label: 'Under Review', date: null, completed: quote.status !== 'new' },
                { status: 'quoted', label: 'Quote Provided', date: quote.responded_at, completed: quote.status === 'quoted' || quote.status === 'accepted' },
                { status: 'accepted', label: 'Next Steps', date: null, completed: quote.status === 'accepted' }
            ];

            timeline.forEach(item => {
                const dateStr = item.date ? new Date(item.date).toLocaleDateString('en-US', {month: 'short', day: 'numeric'}) : '—';
                html += `
                    <div class="status-item ${item.completed ? 'completed' : 'pending'}">
                        <div class="status-icon">
                            ${item.completed ? '<i class="fa fa-check"></i>' : '<i class="fa fa-circle-o"></i>'}
                        </div>
                        <div class="status-info">
                            <h6>${item.label}</h6>
                            <small>${dateStr}</small>
                        </div>
                    </div>
                `;
            });

            html += `</div>`;

            html += `
                <div class="alert alert-info mt-4 mb-0">
                    <i class="fa fa-info-circle mr-2"></i>
                    <strong>Questions?</strong> Contact us at <a href="mailto:{{ config('mail.from.address', 'info@example.com') }}">{{ config('mail.from.address', 'info@example.com') }}</a>
                </div>
            `;

            document.getElementById('resultsContent').innerHTML = html;
            document.getElementById('resultsCard').style.display = 'block';
        }

        function showError(message) {
            document.getElementById('errorMessage').textContent = message;
            document.getElementById('errorAlert').style.display = 'block';
        }
    </script>
</body>
</html>
