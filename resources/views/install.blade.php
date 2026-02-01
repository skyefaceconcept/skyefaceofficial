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
                <a href="{{ url('/install?step=2') }}" style="text-decoration: none; color: {{ ($step ?? 1) === 2 ? '#111827' : '#6b7280' }}">2. Migrate</a>
                &nbsp;•&nbsp;
                <a href="{{ url('/install?step=3') }}" style="text-decoration: none; color: {{ ($step ?? 1) === 3 ? '#111827' : '#6b7280' }}">3. Admin</a>
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
            <form id="db-form" method="post" action="{{ route('install.dbcreate') }}">
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

                <div style="display:flex;gap:0.5rem;align-items:center;margin-top:1rem">
                    <button id="db-create-btn" class="btn" type="button">Create Database</button>
                    <button id="db-test-btn" class="btn" type="button" style="background:#6b7280">Test Connection</button>
                </div>
            </form>

            <script>
                const dbForm = document.getElementById('db-form');
                const createBtn = document.getElementById('db-create-btn');
                const testBtn = document.getElementById('db-test-btn');
                const message = document.getElementById('db-message');
                const csrf = '{{ csrf_token() }}';
                const dbTestUrl = '{{ route('install.dbtest') }}';
                const dbCreateUrl = '{{ route('install.dbcreate') }}';

                // Helper to send form data to a route and return parsed JSON
                async function postForm(url, btnElement, actionLabel) {
                    message.style.color = 'black';
                    message.textContent = actionLabel + '...';
                    btnElement.disabled = true;
                    const formData = new FormData(dbForm);
                    formData.set('persist', document.getElementById('persist').checked ? '1' : '0');
                    try {
                        const res = await fetch(url, {
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
                            message.textContent = json.message;
                            return json;
                        } else {
                            message.style.color = 'crimson';
                            message.textContent = json.message || 'Unexpected response';
                            return json;
                        }
                    } catch (err) {
                        message.style.color = 'crimson';
                        message.textContent = err.message || 'Request failed';
                        return { success: false, message: err.message };
                    } finally {
                        btnElement.disabled = false;
                    }
                }

                createBtn.addEventListener('click', async (e) => {
                    // Create the DB first
                    const result = await postForm(dbCreateUrl, createBtn, 'Creating database');
                    if (result && result.success) {
                        // After creating DB, move to step 2 (Migrate)
                        window.location = '{{ url('/install?step=2') }}';
                    }
                });

                testBtn.addEventListener('click', async (e) => {
                    const result = await postForm(dbTestUrl, testBtn, 'Testing connection');
                    if (result && result.success) {
                        message.textContent = result.message + '. Redirecting to migrate step...';
                        setTimeout(() => { window.location = '{{ url('/install?step=2') }}'; }, 900);
                    }
                });
            </script>
        @endif

        {{-- Step 2: Run migrations --}}
        @if (($step ?? 1) === 2)
            <h3 style="margin-top:1.25rem">Database (summary)</h3>
            <p class="note">Host: <strong>{{ env('DB_HOST', '127.0.0.1') }}</strong>
            &nbsp;•&nbsp; DB: <strong>{{ env('DB_DATABASE', 'not set') }}</strong>
            &nbsp;•&nbsp; User: <strong>{{ env('DB_USERNAME', 'not set') }}</strong></p>

            <form id="db-test-form">
                @csrf
                <input type="hidden" name="db_host" value="{{ env('DB_HOST', '') }}" />
                <input type="hidden" name="db_port" value="{{ env('DB_PORT', '3306') }}" />
                <input type="hidden" name="db_database" value="{{ env('DB_DATABASE', '') }}" />
                <input type="hidden" name="db_username" value="{{ env('DB_USERNAME', '') }}" />
                <input type="hidden" name="db_password" value="{{ env('DB_PASSWORD', '') }}" />
            </form>

            <div style="display:flex;gap:0.5rem;align-items:center;margin-top:1rem">
                <button id="run-migrations-btn" class="btn" type="button">Run Migrations</button>
                <button id="migrate-test-btn" class="btn" type="button" style="background:#6b7280" disabled>Test Connection</button>
                <a href="{{ url('/install?step=1') }}" class="btn" style="background:#e5e7eb;color:#111">Back</a>
            </div>

            <div id="migrate-output" style="white-space:pre-wrap;background:#f3f4f6;border-radius:6px;padding:0.75rem;margin-top:1rem;display:none"></div>

            <script>
                const runBtn = document.getElementById('run-migrations-btn');
                const testBtn2 = document.getElementById('migrate-test-btn');
                const migrateOutput = document.getElementById('migrate-output');
                const testForm = document.getElementById('db-test-form');
                const csrf2 = '{{ csrf_token() }}';
                const dbMigrateUrl2 = '{{ route('install.dbmigrate') }}';
                const dbTestUrl2 = '{{ route('install.dbtest') }}';

                async function postFormFromElement(url, formElement, btnEl, actionLabel) {
                    migrateOutput.style.color = 'black';
                    migrateOutput.style.display = 'block';
                    migrateOutput.textContent = actionLabel + '...\n';
                    btnEl.disabled = true;
                    const formData = new FormData(formElement);
                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrf2, 'Accept': 'application/json' },
                            body: formData
                        });
                        const json = await res.json();
                        if (res.ok && json.success) {
                            migrateOutput.style.color = 'green';
                            migrateOutput.textContent += json.output || json.message || 'Success';
                            return json;
                        } else {
                            migrateOutput.style.color = 'crimson';
                            migrateOutput.textContent += json.message || 'Error';
                            return json;
                        }
                    } catch (err) {
                        migrateOutput.style.color = 'crimson';
                        migrateOutput.textContent += (err.message || 'Request failed');
                        return { success: false, message: err.message };
                    } finally {
                        btnEl.disabled = false;
                    }
                }

                runBtn.addEventListener('click', async () => {
                    // Start a background migration and poll status until completion
                    migrateOutput.style.display = 'block';
                    migrateOutput.style.color = 'black';
                    migrateOutput.textContent = 'Starting background migration...\n';
                    runBtn.disabled = true;

                    try {
                        const res = await fetch('{{ route('install.dbmigrate_start') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf2, 'Accept': 'application/json' } });
                        const json = await res.json();
                        if (!res.ok || !json.success) {
                            migrateOutput.style.color = 'crimson';
                            migrateOutput.textContent += (json.message || 'Failed to start migration');
                            runBtn.disabled = false;
                            return;
                        }

                        // Poll status endpoint until done/error
                        const poll = async () => {
                            const sres = await fetch('{{ route('install.dbmigrate_status') }}', { method: 'GET', headers: { 'Accept': 'application/json' } });
                            const sjson = await sres.json();
                            const status = sjson.status || {};
                            migrateOutput.textContent = '';
                            if (sjson.log) migrateOutput.textContent += sjson.log + '\n';
                            migrateOutput.textContent += '\nStatus: ' + (status.status || 'unknown');

                            if (status.status === 'running') {
                                setTimeout(poll, 1500);
                            } else if (status.status === 'done') {
                                migrateOutput.style.color = 'green';
                                migrateOutput.textContent += '\nMigration completed successfully.';
                                // enable test and auto-run it
                                testBtn2.disabled = false;
                                const test = await postFormFromElement(dbTestUrl2, testForm, testBtn2, 'Testing connection');
                                if (test && test.success) {
                                    setTimeout(() => { window.location = '{{ url('/install?step=3') }}'; }, 800);
                                }
                                runBtn.disabled = false;
                            } else if (status.status === 'error') {
                                migrateOutput.style.color = 'crimson';
                                migrateOutput.textContent += '\nMigration failed: ' + (status.message || 'See log for details');
                                runBtn.disabled = false;
                            } else {
                                runBtn.disabled = false;
                            }
                        };

                        setTimeout(poll, 1000);
                    } catch (err) {
                        migrateOutput.style.color = 'crimson';
                        migrateOutput.textContent += '\n' + (err.message || 'Request failed');
                        runBtn.disabled = false;
                    }
                });

                testBtn2.addEventListener('click', async () => {
                    const test = await postFormFromElement(dbTestUrl2, testForm, testBtn2, 'Testing connection');
                    if (test && test.success) {
                        setTimeout(() => { window.location = '{{ url('/install?step=3') }}'; }, 800);
                    }
                });
            </script>
        @endif

        {{-- Step 3: Admin account and finalize --}}
        @if (($step ?? 1) === 3)
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

                <div style="display:flex;gap:0.5rem;align-items:center;margin-top:1rem">
                    <button class="btn" type="submit">Install and create admin</button>
                    <a href="{{ url('/install?step=2') }}" class="btn" style="background:#e5e7eb;color:#111">Back</a>
                </div>
            </form>

            <p class="note" style="margin-top:1rem">After installation, you can login using the admin user you created and finish configuration.</p>
        @endif
    </div>
</body>
</html>
