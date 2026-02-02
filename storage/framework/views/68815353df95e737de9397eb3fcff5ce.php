<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Installer</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; padding: 2rem; }
        .container { max-width: 720px; margin: 0 auto; }
        label { display:block; margin-top:1rem; font-weight:600 }
        input { width:100%; padding:.5rem; margin-top:.25rem }
        .row { display:flex; gap:1rem }
        .small{ width:48% }
        .btn { margin-top:1rem; padding:.75rem 1rem; background:#111827;color:white;border:none;cursor:pointer }
        .btn.secondary{ background:#6b7280 }
        .note{ color:#6b7280; font-size:.9rem }
        nav a{ text-decoration:none; color:#111827 }
    </style>
</head>
<body>
<?php
    $step = (int) request()->query('step', 1);
    $dbHost = env('DB_HOST', '127.0.0.1');
    $dbPort = env('DB_PORT', '3306');
    $dbDatabase = env('DB_DATABASE', '');
    $dbUsername = env('DB_USERNAME', '');
    $dbPassword = env('DB_PASSWORD', '');
?>

<div class="container">
    <script>const _csrf_meta = document.querySelector('meta[name="csrf-token"]'); const csrf = _csrf_meta ? _csrf_meta.getAttribute('content') : '';</script>
    <h1>Installer</h1>
    <p class="note">Run the steps below in order: <strong>Database</strong> → <strong>Migrate</strong> → <strong>Admin</strong>.</p>

    <nav style="margin-top:1rem">
        <strong>Stage:</strong>
        <span style="margin-left:.5rem">
            <a href="<?php echo e(route('install.show', ['step' => 1])); ?>" style="font-weight: <?php echo e($step===1 ? '700' : '400'); ?>">1. Database</a>
            &nbsp;•&nbsp;
            <a href="<?php echo e(route('install.show', ['step' => 2])); ?>" style="font-weight: <?php echo e($step===2 ? '700' : '400'); ?>">2. Migrate</a>
            &nbsp;•&nbsp;
            <a href="<?php echo e(route('install.show', ['step' => 3])); ?>" style="font-weight: <?php echo e($step===3 ? '700' : '400'); ?>">3. Admin</a>
        </span>
    </nav>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($step === 1): ?>
        <form id="db-form">
            <?php echo csrf_field(); ?>
            <label for="db_host">DB Host</label>
            <input id="db_host" name="db_host" value="<?php echo e(old('db_host', $dbHost)); ?>" required />

            <div class="row">
                <div class="small">
                    <label for="db_port">DB Port</label>
                    <input id="db_port" name="db_port" value="<?php echo e(old('db_port', $dbPort)); ?>" />
                </div>
                <div class="small">
                    <label for="db_database">Database name</label>
                    <input id="db_database" name="db_database" value="<?php echo e(old('db_database', $dbDatabase)); ?>" required />
                </div>
            </div>

            <div class="row">
                <div class="small">
                    <label for="db_username">DB username</label>
                    <input id="db_username" name="db_username" value="<?php echo e(old('db_username', $dbUsername)); ?>" />
                </div>
                <div class="small">
                    <label for="db_password">DB password</label>
                    <input id="db_password" name="db_password" value="<?php echo e(old('db_password', $dbPassword)); ?>" />
                </div>
            </div>

            <div style="margin-top:1rem">
                <label><input type="checkbox" id="persist" name="persist" checked /> Save DB settings to <code>.env</code></label>
            </div>

            <div id="db-message" style="margin-top:1rem"></div>

            <div style="display:flex;gap:.5rem;align-items:center;margin-top:1rem">
                <button id="db-create-btn" class="btn" type="button">Create Database</button>
                <button id="db-test-btn" class="btn secondary" type="button">Test Connection</button>
            </div>
        </form>

        <script>
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const dbForm = document.getElementById('db-form');
            const createBtn = document.getElementById('db-create-btn');
            const testBtn = document.getElementById('db-test-btn');
            const message = document.getElementById('db-message');
            const dbTestUrl = '<?php echo e(route('install.db.test')); ?>';
            const dbCreateUrl = '<?php echo e(route('install.db.create')); ?>';

            async function postForm(url, btn, label) {
                message.style.color = 'black';
                message.textContent = label + '...';
                btn.disabled = true;
                const formData = new FormData(dbForm);
                formData.set('persist', document.getElementById('persist').checked ? '1' : '0');
                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrf,
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin',
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
                    message.textContent = err.message || 'Request failed (see console)';
                    console.error(err);
                    return { success: false, message: err.message };
                } finally {
                    btn.disabled = false;
                }
            }

            createBtn.addEventListener('click', async () => {
                const result = await postForm(dbCreateUrl, createBtn, 'Creating database');
                if (result && result.success) {
                    window.location = '<?php echo e(route('install.show', ['step' => 2])); ?>';
                }
            });

            testBtn.addEventListener('click', async () => {
                const result = await postForm(dbTestUrl, testBtn, 'Testing connection');
                if (result && result.success) {
                    message.textContent = result.message + '. Redirecting...';
                    setTimeout(() => { window.location = '<?php echo e(route('install.show', ['step' => 2])); ?>'; }, 900);
                }
            });
        </script>

    <?php elseif($step === 2): ?>
        <div style="margin-top:1rem">
            <h2>Migrate Database</h2>
            <p class="note">Run migrations to create tables and seeders. You can list migration files below and run selected ones or run all migrations.</p>

            <div style="margin-top:1rem">
                <button id="list-migrations" class="btn secondary" type="button">List Migration Files</button>
            </div>

            <div id="migrations-controls" style="margin-top:.5rem;display:flex;gap:.5rem">
                <button id="select-all-migrations" class="btn secondary" type="button">Select all</button>
                <button id="clear-all-migrations" class="btn secondary" type="button">Clear</button>
            </div>

            <div id="migrations-list" style="margin-top:1rem">
                <?php
                    $serverMigrations = glob(database_path('migrations') . DIRECTORY_SEPARATOR . '*.php');
                    if ($serverMigrations) {
                        natsort($serverMigrations);
                        $serverMigrations = array_map('basename', $serverMigrations);
                    } else {
                        $serverMigrations = [];
                    }
                ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($serverMigrations)): ?>
                    <form id="migrations-form"><div style="max-height:260px;overflow:auto;border:1px solid #eee;padding:8px">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $serverMigrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mfile): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label style="display:block"><input type="checkbox" name="migrations" value="<?php echo e($mfile); ?>" /> <?php echo e($mfile); ?></label>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div></form>
                <?php else: ?>
                    <div class="note">No migration files found in <code>database/migrations</code></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div id="migrate-message" style="margin-top:1rem"></div>

            <div style="display:flex;gap:.5rem;align-items:center;margin-top:1rem">
                <button id="migrate-selected" class="btn" type="button">Run Selected</button>
                <button id="migrate-all" class="btn" type="button">Run All (artisan migrate)</button>
                <a class="btn secondary" href="<?php echo e(url('/')); ?>">Back to site</a>
            </div>
        </div>
        <script>
            const listBtn = document.getElementById('list-migrations');
            const listArea = document.getElementById('migrations-list');
            const migrateSelectedBtn = document.getElementById('migrate-selected');
            const migrateAllBtn = document.getElementById('migrate-all');
            const migrateMessage = document.getElementById('migrate-message');

            async function listMigrations() {
                listBtn.disabled = true; listArea.textContent = 'Loading...';
                try {
                    const res = await fetch('<?php echo e(route('install.list-migrations')); ?>', { credentials: 'same-origin' });
                    if (!res.ok) {
                        let errText = `Error ${res.status} ${res.statusText}`;
                        try { const errJson = await res.json(); errText = errJson.message || JSON.stringify(errJson); } catch (_) { try { errText = await res.text(); } catch(_){} }
                        console.error('list-migrations failed:', res.status, errText);
                        listArea.textContent = 'Failed to list migrations: ' + errText;
                        return;
                    }
                    const contentType = res.headers.get('content-type') || '';
                    if (!contentType.includes('application/json')) {
                        const text = await res.text();
                        console.error('list-migrations returned non-JSON:', text);
                        listArea.textContent = 'Failed to list migrations: unexpected response (not JSON)';
                        return;
                    }
                    const json = await res.json();
                    console.log('list-migrations response:', json);
                    if (json.migrations && json.migrations.length) {
                        listArea.innerHTML = '<form id="migrations-form"><div style="max-height:260px;overflow:auto;border:1px solid #eee;padding:8px">' +
                            json.migrations.map(f => `<label style="display:block"><input type="checkbox" name="migrations" value="${f}" /> ${f}</label>`).join('') +
                            '</div></form>';
                    } else {
                        listArea.innerHTML = '<div class="note">No migration files found in <code>database/migrations</code></div>';
                    }
                } catch (e) {
                    console.error('list-migrations exception:', e);
                    listArea.textContent = 'Failed to list migrations: ' + (e.message || 'Request failed');
                } finally { listBtn.disabled = false; }
            }

            listBtn.addEventListener('click', listMigrations);

            // Selection controls
            const selectAllBtn = document.getElementById('select-all-migrations');
            const clearAllBtn = document.getElementById('clear-all-migrations');
            function updateMigrationControls() {
                const form = document.getElementById('migrations-form');
                const any = form && form.querySelectorAll('input[name="migrations"]').length > 0;
                selectAllBtn.disabled = !any;
                clearAllBtn.disabled = !any;
            }
            selectAllBtn.addEventListener('click', () => {
                const form = document.getElementById('migrations-form'); if (!form) return;
                for (const cb of form.querySelectorAll('input[name="migrations"]')) cb.checked = true;
            });
            clearAllBtn.addEventListener('click', () => {
                const form = document.getElementById('migrations-form'); if (!form) return;
                for (const cb of form.querySelectorAll('input[name="migrations"]')) cb.checked = false;
            });

            // Auto-list migrations when opening step 2 so the user sees files immediately
            listMigrations();
            // If server-side rendered form exists, ensure controls reflect it
            updateMigrationControls();

            migrateSelectedBtn.addEventListener('click', async function () {
                const form = document.getElementById('migrations-form');
                if (!form) { migrateMessage.style.color = 'crimson'; migrateMessage.textContent = 'Please list migrations first.'; return; }
                const checked = Array.from(form.querySelectorAll('input[name="migrations"]:checked')).map(i => i.value);
                if (checked.length === 0) { migrateMessage.style.color = 'crimson'; migrateMessage.textContent = 'Select one or more migration files to run.'; return; }
                migrateSelectedBtn.disabled = true; migrateMessage.style.color = 'black'; migrateMessage.textContent = 'Running selected migrations...';
                try {
                    const res = await fetch('<?php echo e(route('install.db.migrate-files')); ?>', {
                        method: 'POST',
                        headers: {'X-CSRF-TOKEN': csrf, 'Accept':'application/json','Content-Type':'application/json'},
                        credentials: 'same-origin',
                        body: JSON.stringify({ files: checked })
                    });

                    if (!res.ok) {
                        // Try to parse JSON error body, otherwise show status
                        let errText = 'Request failed';
                        try { const errJson = await res.json(); errText = errJson.message || (errJson.errors && errJson.errors.join('; ')) || JSON.stringify(errJson); } catch (_) { errText = res.status + ' ' + res.statusText; }
                        migrateMessage.style.color = 'crimson'; migrateMessage.textContent = errText;
                        return;
                    }

                    const json = await res.json();
                    let ok = true; let html = '';
                    for (const [file, r] of Object.entries(json.results || {})) {
                        if (r.success) {
                            html += `<div style="color:green"><strong>${file}</strong>: migrated OK` + (r.output ? `<pre style="background:#f7f7f7;padding:8px;white-space:pre-wrap">${r.output}</pre>` : '') + `</div>`;
                        } else {
                            ok = false; html += `<div style="color:crimson"><strong>${file}</strong>: ${r.message || 'failed'}` + (r.output ? `<pre style="background:#fff7f7;padding:8px;white-space:pre-wrap">${r.output}</pre>` : '') + `</div>`;
                        }
                    }
                    migrateMessage.innerHTML = html;
                    if (ok) migrateMessage.style.color = 'green'; else migrateMessage.style.color = 'crimson';
                } catch (e) {
                    migrateMessage.style.color = 'crimson'; migrateMessage.textContent = e.message || 'Request failed';
                } finally { migrateSelectedBtn.disabled = false; }
            });

            migrateAllBtn.addEventListener('click', async function () {
                this.disabled = true; migrateMessage.style.color = 'black'; migrateMessage.textContent = 'Running full migrations via Artisan...';
                try {
                    const res = await fetch('<?php echo e(route('install.db.migrate')); ?>', { method: 'POST', headers: {'X-CSRF-TOKEN': csrf, 'Accept':'application/json'}, credentials:'same-origin' });
                    if (!res.ok) {
                        let errText = 'Migrate failed';
                        try { const errJson = await res.json(); errText = errJson.message || JSON.stringify(errJson); } catch (_) { errText = res.status + ' ' + res.statusText; }
                        migrateMessage.style.color = 'crimson'; migrateMessage.textContent = errText; return;
                    }
                    const json = await res.json();
                    if (json.success) { migrateMessage.style.color = 'green'; migrateMessage.textContent = json.message || 'Migrations completed'; }
                    else { migrateMessage.style.color = 'crimson'; migrateMessage.textContent = json.message || 'Migrate failed'; }
                } catch (e) { migrateMessage.style.color = 'crimson'; migrateMessage.textContent = e.message || 'Request failed'; }
                finally { this.disabled = false; }
            });
        </script>

    <?php elseif($step === 3): ?>
        <div style="margin-top:1rem">
            <h2>Create Admin</h2>
            <p class="note">Create the initial admin user with SuperAdmin role to finish setup.</p>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                <div style="color:crimson;margin-top:1rem">
                    <strong>Errors:</strong>
                    <ul>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($err); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <form method="POST" action="<?php echo e(route('install.perform')); ?>">
                <?php echo csrf_field(); ?>

                <label for="site_title">Site Title (optional)</label>
                <input id="site_title" name="site_title" value="<?php echo e(old('site_title')); ?>" />

                <label for="admin_name">Full name</label>
                <input id="admin_name" name="admin_name" value="<?php echo e(old('admin_name')); ?>" required />

                <label for="admin_username">Username</label>
                <input id="admin_username" name="admin_username" value="<?php echo e(old('admin_username')); ?>" required />

                <label for="admin_email">Email</label>
                <input id="admin_email" name="admin_email" type="email" value="<?php echo e(old('admin_email')); ?>" required />

                <label for="admin_password">Password</label>
                <input id="admin_password" name="admin_password" type="password" required />

                <label for="admin_password_confirmation">Confirm password</label>
                <input id="admin_password_confirmation" name="admin_password_confirmation" type="password" required />

                <div style="margin-top:1rem"><button class="btn" type="submit">Create SuperAdmin & Finish Install</button></div>
            </form>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
</body>
</html>





<?php /**PATH C:\laragon\www\Skyefaceofficial\resources\views/install.blade.php ENDPATH**/ ?>