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
        <p class="note">This simple installer configures minimal settings and creates an admin user. You can update other settings later in the admin panel.</p>

        @if ($errors->any())
            <div style="background:#fee2e2;padding:1rem;margin-top:1rem;border-radius:6px">
                <ul style="margin:0;padding-left:1.25rem">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="{{ route('install.post') }}">
            @csrf
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

            <h3 style="margin-top:1.25rem">(Optional) Database</h3>
            <small class="note">If you provide DB details here, the installer will attempt to write them to <code>.env</code> and run migrations.</small>
            <label for="db_database">Database name</label>
            <input id="db_database" name="db_database" value="{{ old('db_database') }}" />

            <div class="row">
                <div class="small">
                    <label for="db_username">DB username</label>
                    <input id="db_username" name="db_username" value="{{ old('db_username') }}" />
                </div>
                <div class="small">
                    <label for="db_password">DB password</label>
                    <input id="db_password" name="db_password" value="{{ old('db_password') }}" />
                </div>
            </div>

            <button class="btn" type="submit">Install and create admin</button>
        </form>

        <p class="note" style="margin-top:1rem">After installation, you can login using the admin user you created and finish configuration.</p>
    </div>
</body>
</html>
