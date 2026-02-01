# SkyefaceOfficial 🛠️

**SkyefaceOfficial** is a Laravel-based application for managing device repairs, online store catalogs, quotes, ticketing and payments. This README provides a concise guide to get the app running locally and explains the first-time web installer included in the project.

---

## 🚀 Quickstart

Requirements:
- PHP 8.0+ (match project composer.json)
- Composer
- MySQL or compatible database
- Node.js & npm (for frontend assets)
- Web server or use `php artisan serve`

Steps:
1. Clone the repository

   ```bash
   git clone https://github.com/skyefaceconcept/skyefaceofficial.git
   cd skyefaceofficial
   ```

2. Install PHP dependencies

   ```bash
   composer install
   ```

3. Copy environment file and set credentials

   ```bash
   cp .env.example .env
   # edit .env and set DB_*, MAIL_*, APP_URL, etc.
   php artisan key:generate
   ```

4. Ensure `storage` and `bootstrap/cache` are writable

   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

5. Run database migrations and seeders

   ```bash
   php artisan migrate --seed
   ```

6. Install frontend dependencies and build assets

   ```bash
   npm install
   npm run build   # or npm run dev
   ```

7. Serve the app

   ```bash
   php artisan serve
   # or configure with your local web server (Laragon, Valet, Homestead, etc.)
   ```

---

## 🧩 First-time web installer

This repo contains a small web installer that runs automatically when the app detects it is not yet installed:

- Visit `GET /install` or open `/` when `storage/app/installed` does not exist.
- The installer can: generate `APP_KEY`, optionally persist provided DB settings into `.env`, attempt `migrate`, and create an initial admin user.
- On success the installer writes `storage/app/installed` to prevent re-running.

Tip: To re-run the installer delete `storage/app/installed` and ensure `.env` is writable.

---

## ✨ Key features

- Device repair booking and tracking
- Public shop/catalog and cart/checkout
- Admin panel with settings, orders, licenses and payments
- Email verification and contact ticket system
- Payment integrations (Flutterwave, Paystack, etc.)

---

## ⚙️ Environment variables (common)

- `APP_NAME`, `APP_URL`
- Database: `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
- Mail: `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, etc.
- Payment processors specific keys (set via admin settings or `.env`)

---

## 🧪 Tests

Run the test suite:

```bash
php artisan test
```

---

## 🤝 Contributing

Contributions are welcome. Please open an issue or a PR. Keep changes focused and include tests for new behavior.

---

## 📄 License

This project is open-sourced under the **MIT License**.

---

If you'd like, I can also add a short development guide, expand environment docs, or create a feature matrix table. Which would you prefer next? 💡
