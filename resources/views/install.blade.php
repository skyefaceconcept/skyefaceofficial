<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Install - {{ config('app.name') }}</title>
    <style>
        body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; padding: 2rem; }
        .container { max-width: 640px; margin: 0 auto; }
        label { display: block; margin-top: 1rem; font-weight: 600; }
        input { width: 100%; padding: .5rem; margin-top: .25rem; }
        .row { display:flex; gap:1rem; }
        .small { width: 48%; }
        .btn { margin-top:1rem; padding: .75rem 1rem; background:#111827; color:white; border:none; cursor:pointer }
        .note { color:#6b7280; font-size:.9rem }
    </style>
</head>
<body>
    <div class="container">
        <h1>Installer</h1>
        <p class="note">This installer runs in stages: <strong>1) Database</strong> → <strong>2) Admin & Finalize</strong>.</p>

        <nav style="margin-top:1rem">
            <strong>Stage:</strong>
            <span style="margin-left: .5rem;">
                <a href="{{ url('/install?step=1') }}" style="text-decoration: none; color: {{ ($step ?? 1) === 1 ? '#111827' : '#6b7280' }}">1. Database</a>
                &nbsp;•&nbsp;
                <a href="{{ url('/install?step=2') }}" style="text-decoration: none; color: {{ ($step ?? 1) === 2 ? '#111827' : '#6b7280' }}">2. Admin</a>
            </span>
        </nav>

        @if ($errors->any())
            <div style="background:#fee2e2;padding:1rem;margin-top:1rem;border-radius:6px">
                <ul style="margin:0;padding-left:1.25rem">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Step 1: Database setup --}}
        @if (($step ?? 1) === 1)
            <form id="db-form" method="post" action="{{ route('install.dbtest') }}">
                @csrf
                <label for="db_host">DB Host</label>
                <input id="db_host" name="db_host" value="{{ old('db_host', env('DB_HOST', '127.0.0.1')) }}" required />

                <div class="row">
                    <div class="small">
                        <label for="db_port">DB Port</label>
                        <input id="db_port" name="db_port" value="{{ old('db_port', env('DB_PORT', 3306)) }}" />
                    </div>
                    <div class="small">
                        <label for="db_database">Database name</label>
                        <input id="db_database" name="db_database" value="{{ old('db_database', env('DB_DATABASE')) }}" required />
                    </div>
                </div>

                <div class="row">
                    <div class="small">
                        <label for="db_username">DB username</label>
                        <input id="db_username" name="db_username" value="{{ old('db_username', env('DB_USERNAME', 'root')) }}" />
                    </div>
                    <div class="small">
                        <label for="db_password">DB password</label>
                        <input id="db_password" name="db_password" value="{{ old('db_password', env('DB_PASSWORD', '')) }}" />
                    </div>
                </div>

                <div style="margin-top:1rem">
                    <label><input type="checkbox" id="persist" name="persist" checked /> Save DB settings to <code>.env</code></label>
                </div>

                <div id="db-message" style="margin-top:1rem"></div>

                <button id="db-test-btn" class="btn" type="button">Test &amp; Create Database</button>
            </form>

            <script>
                const dbForm = document.getElementById('db-form');
                const btn = document.getElementById('db-test-btn');
                const message = document.getElementById('db-message');
                const csrf = '{{ csrf_token() }}';
                const dbTestUrl = '{{ route('install.dbtest') }}';

                btn.addEventListener('click', async (e) => {
                    message.textContent = 'Testing connection...';
                    btn.disabled = true;
                    const formData = new FormData(dbForm);
                    // include persist boolean
                    formData.set('persist', document.getElementById('persist').checked ? '1' : '0');

                    try {
                        const res = await fetch(dbTestUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        const json = await res.json();
                        if (res.ok && json.success) {
                            message.style.color = 'green';
                            message.textContent = json.message + '. Redirecting to next step...';
                            // proceed to step 2
                            setTimeout(() => { window.location = '{{ url('/install?step=2') }}'; }, 900);
                        } else {
                            message.style.color = 'crimson';
                            message.textContent = json.message || 'Unexpected response';
                        }
                    } catch (err) {
                        message.style.color = 'crimson';
                        message.textContent = err.message || 'Connection failed';
                    }
                    btn.disabled = false;
                });
            </script>
        @endif

        {{-- Step 2: Admin account and finalize --}}
        @if (($step ?? 1) === 2)
            <form method="post" action="{{ route('install.post') }}">
                @csrf

                <h3 style="margin-top:1.25rem">Database (summary)</h3>
                <p class="note">Host: <strong>{{ env('DB_HOST', '127.0.0.1') }}</strong>
                &nbsp;•&nbsp; DB: <strong>{{ env('DB_DATABASE', 'not set') }}</strong>
                &nbsp;•&nbsp; User: <strong>{{ env('DB_USERNAME', 'not set') }}</strong></p>

                <label for="site_title">Site Title (optional)</label>
                <input id="site_title" name="site_title" value="{{ old('site_title', config('app.name')) }}" />

                <h3 style="margin-top:1.5rem">Admin Account</h3>
                <label for="admin_name">Full name</label>
                <input id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required />

                <label for="admin_email">Email</label>
                <input id="admin_email" name="admin_email" type="email" value="{{ old('admin_email') }}" required />

                <div class="row">
                    <div class="small">
                        <label for="admin_password">Password</label>
                        <input id="admin_password" name="admin_password" type="password" required />
                    </div>
                    <div class="small">
                        <label for="admin_password_confirmation">Confirm password</label>
                        <input id="admin_password_confirmation" name="admin_password_confirmation" type="password" required />
                    </div>
                </div>

                <div style="margin-top:1rem">
                    <label><input type="checkbox" name="run_migrations" checked /> Run migrations after install</label>
                </div>

                <button class="btn" type="submit">Install and create admin</button>
            </form>

            <p class="note" style="margin-top:1rem">After installation, you can login using the admin user you created and finish configuration.</p>
        @endif
    </div>
</body>
</html>
