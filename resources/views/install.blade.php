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
                const dbTestUrl = '/install/db-test';
                const dbCreateUrl = '/install/db-create';

                // sendClientLog helper - posts client-side debug info to the server
                async function sendClientLog(level, messageText, extra) {
                    try {
                        await fetch('/install/client-log', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrf,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            credentials: 'include',
                            body: JSON.stringify({ level: level, message: messageText, extra: extra || {} })
                        });
                    } catch (e) {
                        // ignore client logging failures
                        console.warn('client log failed', e);
                    }
                }

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
                            credentials: 'include',
                            body: formData
                        });

                        if (res.status === 419) {
                            message.style.color = 'crimson';
                            message.textContent = 'Session expired or CSRF token mismatch. Please reload the page and try again.';
                            btnElement.disabled = false;
                            sendClientLog('warn', 'CSRF mismatch on postForm', { url: url, status: 419 });
                            return { success: false, message: message.textContent };
                        }

                        const json = await res.json();
                        if (res.ok && json.success) {
                            message.style.color = 'green';
                            message.textContent = json.message;
                            sendClientLog('info', actionLabel + ' success', { url: url });
                            return json;
                        } else {
                            message.style.color = 'crimson';
                            message.textContent = json.message || 'Unexpected response';
                            sendClientLog('error', actionLabel + ' failed', { url: url, payload: json });
                            return json;
                        }
                    } catch (err) {
                        console.error('Installer request error', err);
                        message.style.color = 'crimson';
                        message.textContent = err.message || 'Request failed (see console)';
                        sendClientLog('error', 'postForm exception', { url: url, error: '' + err });
                        return { success: false, message: err.message };
                    } finally {
                        btnElement.disabled = false;
                    }
                }

                createBtn.addEventListener('click', async (e) => {
                    console.log('Create Database clicked');
                    sendClientLog('info', 'Create Database clicked');
                    // Create the DB first
                    const result = await postForm(dbCreateUrl, createBtn, 'Creating database');
                    if (result && result.success) {
                        // After creating DB, move to step 2 (Migrate)
                        window.location = '{{ url('/install?step=2') }}';
                    }
                });

                testBtn.addEventListener('click', async (e) => {
                    console.log('Test Connection clicked (step 1)');
                    sendClientLog('info', 'Test Connection clicked (step 1)');
                    const result = await postForm(dbTestUrl, testBtn, 'Testing connection');
                    if (result && result.success) {
                        message.textContent = result.message + '. Redirecting to migrate step...';
                        setTimeout(() => { window.location = '{{ url('/install?step=2') }}'; }, 900);
                    }
                });
            </script>
        @endif

        {{-- Step 2: Run migrations (clean minimal UI) --}}
        @if (($step ?? 1) === 2)
            <h3 style="margin-top:1.25rem">Database (summary)</h3>
            <p class="note">Host: <strong>{{ env('DB_HOST', '127.0.0.1') }}</strong>
            &nbsp;•&nbsp; DB: <strong>{{ env('DB_DATABASE', 'not set') }}</strong>
            &nbsp;•&nbsp; User: <strong>{{ env('DB_USERNAME', 'not set') }}</strong></p>

            <div style="display:flex;gap:0.5rem;align-items:center;margin-top:1rem">
                <button id="run-migrations-btn" class="btn" type="button">Run Migrations</button>
                <button id="run-queue-btn" class="btn" type="button" style="background:#8b5cf6;color:#fff;display:none">Run worker once</button>
                <button id="installer-test-btn" class="btn" type="button" style="background:#06b6d4;color:#fff">Test Installer</button>
            </div>

            <div id="migrate-output" style="white-space:pre-wrap;background:#f3f4f6;border-radius:6px;padding:0.75rem;margin-top:1rem;display:none"></div>
            <div id="installer-debug" style="display:none; white-space:pre-wrap; background:#111827; color:#e5e7eb; padding:0.75rem; border-radius:6px; margin-top:0.75rem; font-family:monospace; font-size:13px; max-height:220px; overflow:auto"></div>

            <div id="migration-list" style="margin-top:1rem;background:#fff;border:1px solid #e5e7eb;padding:0.75rem;border-radius:6px;display:none">
                <strong>Migrations</strong>
                <ul id="migration-items" style="list-style:none;padding-left:0;margin-top:0.5rem"></ul>
            </div>

            <style>
                .mig-pending { color: #6b7280 }
                .mig-running { color: #f59e0b }
                .mig-done { color: #10b981; font-weight:600 }
                .mig-failed { color: #ef4444; font-weight:600 }
                .mig-item { padding:0.25rem 0; border-bottom: 1px dashed #efefef }
            </style>

            <script>
                (function(){
                    const csrf = '{{ csrf_token() }}';
                    const queueDriver = '{{ config('queue.default') }}';
                    const runBtn = document.getElementById('run-migrations-btn');
                    const runQueueBtn = document.getElementById('run-queue-btn');
                    const testBtn = document.getElementById('installer-test-btn');
                    const migrateOutput = document.getElementById('migrate-output');
                    const debugEl = document.getElementById('installer-debug');
                    const migrationList = document.getElementById('migration-list');
                    const migrationItems = document.getElementById('migration-items');

                    function showDebug(obj){ try { debugEl.style.display='block'; debugEl.textContent = JSON.stringify(obj, null, 2); debugEl.scrollTop = debugEl.scrollHeight; } catch(e) {} }
                    function writeOutput(txt, color){ try { migrateOutput.style.display='block'; migrateOutput.style.color = color || 'black'; migrateOutput.textContent = txt; } catch(e){} }

                    async function loadMigrationList(){
                        try {
                            const res = await fetch('/install/list-migrations', { method: 'GET', headers: { 'Accept': 'application/json' }, credentials: 'include' });
                            if (!res.ok) { writeOutput('Failed to load migration list: ' + res.status, 'crimson'); return; }
                            const data = await res.json();
                            if (data && data.migrations && data.migrations.length){
                                migrationItems.innerHTML = '';
                                for (const m of data.migrations){
                                    const li = document.createElement('li');
                                    li.className = 'mig-item mig-pending';
                                    li.setAttribute('data-name', m.name);
                                    li.innerHTML = `<span style="display:inline-block;width:70%">${m.name}</span><span style="float:right" class="mig-status">${m.status || 'pending'}</span>`;
                                    migrationItems.appendChild(li);
                                }
                                migrationList.style.display = 'block';
                            } else {
                                migrationList.style.display = 'none';
                                writeOutput('No migration files found', 'crimson');
                            }
                        } catch (e) { writeOutput('Error loading migration list: ' + (e.message || e), 'crimson'); }
                    }

                    function updateMigrationStatus(migs){
                        for (const m of (migs || [])){
                            const el = migrationItems.querySelector(`[data-name='${m.name}']`);
                            if (!el) continue;
                            const statusEl = el.querySelector('.mig-status');
                            const s = (m.status || 'pending').toLowerCase();
                            const labelMap = { done: 'Done', failed: 'Failed', running: 'Running', pending: 'Pending' };
                            statusEl.textContent = labelMap[s] || s;
                            el.classList.remove('mig-pending','mig-running','mig-done','mig-failed');
                            if (s === 'done') el.classList.add('mig-done');
                            else if (s === 'failed') el.classList.add('mig-failed');
                            else if (s === 'running') el.classList.add('mig-running');
                            else el.classList.add('mig-pending');
                        }
                    }

                    // Start migration (POST then poll)
                    async function startMigrations(){
                        try {
                            writeOutput('Starting migrations...', 'black');
                            runBtn.disabled = true;
                            const res = await fetch('/install/db-migrate-start', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, credentials: 'include' });
                            const txt = await res.text();
                            let json; try { json = JSON.parse(txt); } catch(e){ json = { success:false, message: txt }; }
                            showDebug(json);
                            if (!json.success){ writeOutput('Failed to start migrations: ' + (json.message || 'server error'), 'crimson'); runBtn.disabled = false; return; }

                            writeOutput('Migrations queued. Polling status...', 'black');

                            // Show queue button if needed
                            if (runQueueBtn) { if (json.queue_driver && json.queue_driver !== 'sync') runQueueBtn.style.display = 'inline-block'; }

                            const poll = async () => {
                                try {
                                    const sres = await fetch('/install/db-migrate-status', { credentials: 'include' });
                                    if (!sres.ok) { writeOutput('Failed to fetch status: ' + sres.status, 'crimson'); runBtn.disabled = false; return; }
                                    const sjson = await sres.json();
                                    if (sjson.log) writeOutput(sjson.log, 'black');
                                    if (sjson.status && sjson.status.migrations) updateMigrationStatus(sjson.status.migrations);
                                    const st = sjson.status && sjson.status.status;
                                    if (st === 'running') { setTimeout(poll, 1500); }
                                    else if (st === 'done') { writeOutput('Migration completed successfully', 'green'); runBtn.disabled = false; }
                                    else if (st === 'error') { writeOutput('Migration finished with errors. See debug panel.', 'crimson'); showDebug(sjson); runBtn.disabled = false; }
                                    else { runBtn.disabled = false; }
                                } catch (e) { writeOutput('Error polling status: ' + (e.message || e), 'crimson'); runBtn.disabled = false; }
                            };

                            setTimeout(poll, 800);
                        } catch (e) { writeOutput('Start migrations error: ' + (e.message || e), 'crimson'); runBtn.disabled = false; }
                    }

                    // Run single worker once
                    async function runWorkerOnce(){
                        try { runQueueBtn.disabled = true; writeOutput('Running queue worker...', 'black');
                            const res = await fetch('/install/queue-work-once', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, credentials: 'include' });
                            const json = await res.json(); showDebug(json);
                            if (json && json.success) writeOutput('Worker executed', 'green'); else writeOutput('Worker failed: ' + (json.message || ''), 'crimson');
                        } catch (e) { writeOutput('Queue worker error: ' + (e.message || e), 'crimson'); } finally { runQueueBtn.disabled = false; }
                    }

                    // Simple installer health checks
                    async function runInstallerTest(){
                        try { testBtn.disabled = true; writeOutput('Running checks...', 'black');
                            const [ping, status, dbTest] = await Promise.all([ fetch('/install/ping', { credentials: 'include' }), fetch('/install/db-migrate-status', { credentials: 'include', headers: { 'Accept': 'application/json' } }), fetch('/install/debug-test', { credentials: 'include', headers: { 'Accept': 'application/json' } }) ]);
                            const pingJson = await ping.json(); showDebug({ ping: pingJson });
                            const statusJson = await status.json(); if (statusJson.log) writeOutput(statusJson.log, 'black'); if (statusJson.status && statusJson.status.migrations) updateMigrationStatus(statusJson.status.migrations);
                            const dbJson = await dbTest.json(); showDebug(Object.assign({}, debugEl ? JSON.parse(debugEl.textContent || '{}') : {}, { db_test: dbJson }));
                            writeOutput('Checks completed', 'black');
                        } catch (e) { writeOutput('Installer checks failed: ' + (e.message || e), 'crimson'); } finally { testBtn.disabled = false; }
                    }

                    // Attach handlers and init
                    runBtn.addEventListener('click', startMigrations);
                    if (runQueueBtn) runQueueBtn.addEventListener('click', runWorkerOnce);
                    if (testBtn) testBtn.addEventListener('click', runInstallerTest);

                    // Show run queue button only if queue is not sync
                    try { if (queueDriver && queueDriver !== 'sync' && runQueueBtn) runQueueBtn.style.display = 'inline-block'; } catch (e) {}

                    // initial load
                    setTimeout(loadMigrationList, 120);
                })();
            </script>
        @endif

            </div>

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
                <p class="note" style="margin-top:0.25rem">(Note: Title will be applied for this install session; you can save it permanently later from Admin → Settings to avoid .env issues.)</p>

                <h3 style="margin-top:1.5rem">Admin Account</h3>
                <label for="admin_name">Full name</label>
                <input id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required />

                <label for="admin_username">Username</label>
                <input id="admin_username" name="admin_username" value="{{ old('admin_username') }}" required placeholder="alphanumeric, underscores allowed" />

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
            <p class="note" style="margin-top:0.5rem"><strong>Note:</strong> The account created will be assigned the <strong>SuperAdmin</strong> role (full access). Use the <strong>username</strong> and email to sign in.</p>
        @endif
    </div>
</body>
</html>
