
<!-- Professional Footer (fixed to bottom) -->
<footer class="footer fixed-bottom bg-white shadow-sm" style="z-index:1030;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 text-center mb-3">
                {{-- <h5 class="mb-1">Skyeface</h5>
                <p class="text-muted">Customer support platform built for speed and clarity. Manage your tickets, contact support and review past conversations from one place.</p> --}}
                <p class="mb-0 text-muted small">© {{ now()->year }} Skyeface. All rights reserved.</p>
                <p class="mt-2"><small class="text-muted">Need help? <a href="{{ route('home') }}#contact">Contact Support</a></small></p>
            </div>
        </div>
    </div>
</footer>

<!-- Ensure page content isn't hidden behind the fixed footer -->
<style>
    /* keep a safe bottom padding so page content doesn't get overlapped by the fixed footer */
    body { padding-bottom: 90px !important; }
</style>
