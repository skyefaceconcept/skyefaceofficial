@extends('layouts.buzbox')

@section('content')
<div class="page-header-area" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px 0; color: white;">
  <div class="container">
    <h1 class="page-title" style="margin: 0; color: white; font-size: 2.5rem; font-weight: 700;">Privacy Policy</h1>
    <p class="page-subtitle" style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 1.1rem;">How we protect your information</p>
  </div>
</div>

<section class="policy-section py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-10 mx-auto">
        <!-- Last Updated -->
        <div class="alert alert-light border-left border-primary mb-4" style="border-left-width: 4px;">
          <small class="text-muted">Last updated: <strong>{{ now()->format('F d, Y') }}</strong></small>
        </div>

        <!-- Table of Contents -->
        <div class="card mb-5 shadow-sm">
          <div class="card-header bg-light">
            <h5 class="mb-0">Table of Contents</h5>
          </div>
          <div class="card-body">
            <ul class="list-unstyled">
              <li><a href="#section1" class="text-decoration-none">1. Introduction</a></li>
              <li><a href="#section2" class="text-decoration-none">2. Information We Collect</a></li>
              <li><a href="#section3" class="text-decoration-none">3. How We Use Your Information</a></li>
              <li><a href="#section4" class="text-decoration-none">4. Information Sharing</a></li>
              <li><a href="#section5" class="text-decoration-none">5. Data Security</a></li>
              <li><a href="#section6" class="text-decoration-none">6. Your Privacy Rights</a></li>
              <li><a href="#section7" class="text-decoration-none">7. Cookies and Tracking</a></li>
              <li><a href="#section8" class="text-decoration-none">8. Third-Party Links</a></li>
              <li><a href="#section9" class="text-decoration-none">9. Children's Privacy</a></li>
              <li><a href="#section10" class="text-decoration-none">10. Contact Us</a></li>
            </ul>
          </div>
        </div>

        <!-- Section 1 -->
        <div class="card mb-4 shadow-sm" id="section1">
          <div class="card-body">
            <h4 class="card-title mb-3">1. Introduction</h4>
            <p>{{ config('company.name') }} ("we", "us", "our", or "Company") is committed to protecting your privacy. This Privacy Policy explains our practices regarding the collection, use, and disclosure of your personal information when you visit our website and use our services.</p>
            <p class="mb-0">Please read this Privacy Policy carefully. By accessing and using our website, you acknowledge that you have read, understood, and agree to be bound by all the provisions of this Privacy Policy.</p>
          </div>
        </div>

        <!-- Section 2 -->
        <div class="card mb-4 shadow-sm" id="section2">
          <div class="card-body">
            <h4 class="card-title mb-3">2. Information We Collect</h4>
            <h6 class="mt-3 mb-2">Information You Provide Directly:</h6>
            <ul class="ms-3">
              <li><strong>Account Registration:</strong> Name, email address, password, phone number, and profile information</li>
              <li><strong>Orders:</strong> Billing and shipping address, payment information, and order details</li>
              <li><strong>Communications:</strong> Messages you send us through contact forms, email, or chat</li>
              <li><strong>Support:</strong> Information provided when requesting customer support or filing complaints</li>
              <li><strong>Surveys:</strong> Responses to surveys, feedback forms, and promotional offers</li>
            </ul>

            <h6 class="mt-3 mb-2">Information Collected Automatically:</h6>
            <ul class="ms-3">
              <li><strong>Device Information:</strong> IP address, browser type, operating system, and device identifiers</li>
              <li><strong>Usage Data:</strong> Pages visited, time spent on pages, clicks, and browsing patterns</li>
              <li><strong>Cookies and Tracking:</strong> Data from cookies, web beacons, and similar tracking technologies</li>
              <li><strong>Location Data:</strong> General location based on IP address</li>
            </ul>
          </div>
        </div>

        <!-- Section 3 -->
        <div class="card mb-4 shadow-sm" id="section3">
          <div class="card-body">
            <h4 class="card-title mb-3">3. How We Use Your Information</h4>
            <p>We use the information we collect for the following purposes:</p>
            <ul class="ms-3">
              <li>To provide, maintain, and improve our services</li>
              <li>To process and fulfill your orders</li>
              <li>To send transactional emails (order confirmations, receipts, etc.)</li>
              <li>To provide customer support and respond to inquiries</li>
              <li>To send marketing communications (with your consent)</li>
              <li>To personalize your experience on our website</li>
              <li>To conduct research and analytics</li>
              <li>To prevent fraud and protect against security threats</li>
              <li>To comply with legal obligations</li>
              <li>To enforce our Terms and Conditions</li>
            </ul>
          </div>
        </div>

        <!-- Section 4 -->
        <div class="card mb-4 shadow-sm" id="section4">
          <div class="card-body">
            <h4 class="card-title mb-3">4. Information Sharing</h4>
            <p>We may share your information with third parties in the following circumstances:</p>
            <ul class="ms-3">
              <li><strong>Service Providers:</strong> Companies that assist us in operating our website and conducting our business (payment processors, hosting providers, email services)</li>
              <li><strong>Business Partners:</strong> With partners for joint marketing initiatives (with your consent)</li>
              <li><strong>Legal Requirements:</strong> When required by law, court order, or government request</li>
              <li><strong>Business Transfers:</strong> In connection with mergers, acquisitions, or sale of assets</li>
              <li><strong>Your Consent:</strong> When you explicitly consent to share information</li>
            </ul>
            <p class="mb-0"><strong>Note:</strong> We do not sell your personal information to third parties.</p>
          </div>
        </div>

        <!-- Section 5 -->
        <div class="card mb-4 shadow-sm" id="section5">
          <div class="card-body">
            <h4 class="card-title mb-3">5. Data Security</h4>
            <p>We implement appropriate technical, administrative, and physical security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
            <p>Our security measures include:</p>
            <ul class="ms-3">
              <li>SSL/TLS encryption for data transmission</li>
              <li>Secure password storage using industry-standard hashing</li>
              <li>Regular security audits and updates</li>
              <li>Access controls and authentication mechanisms</li>
              <li>Employee training on data protection and privacy</li>
            </ul>
            <p class="mb-0"><strong>Important:</strong> While we strive to protect your information, no security system is impenetrable. We cannot guarantee absolute security of your data.</p>
          </div>
        </div>

        <!-- Section 6 -->
        <div class="card mb-4 shadow-sm" id="section6">
          <div class="card-body">
            <h4 class="card-title mb-3">6. Your Privacy Rights</h4>
            <p>Depending on your location, you may have the following rights:</p>
            <ul class="ms-3">
              <li><strong>Right to Access:</strong> Request a copy of your personal information</li>
              <li><strong>Right to Rectification:</strong> Request correction of inaccurate data</li>
              <li><strong>Right to Erasure:</strong> Request deletion of your personal information</li>
              <li><strong>Right to Restrict Processing:</strong> Request limitation of how we use your data</li>
              <li><strong>Right to Data Portability:</strong> Request your data in a portable format</li>
              <li><strong>Right to Withdraw Consent:</strong> Withdraw consent for marketing communications</li>
              <li><strong>Right to Object:</strong> Object to certain processing activities</li>
            </ul>
            <p class="mb-0">To exercise these rights, please contact us using the information in Section 10.</p>
          </div>
        </div>

        <!-- Section 7 -->
        <div class="card mb-4 shadow-sm" id="section7">
          <div class="card-body">
            <h4 class="card-title mb-3">7. Cookies and Tracking</h4>
            <p>We use cookies and similar tracking technologies to enhance your experience on our website. Cookies are small files stored on your device that help us remember your preferences and analyze site usage.</p>
            <h6 class="mt-3 mb-2">Types of Cookies We Use:</h6>
            <ul class="ms-3">
              <li><strong>Essential Cookies:</strong> Necessary for website functionality</li>
              <li><strong>Performance Cookies:</strong> Track site usage and performance</li>
              <li><strong>Functional Cookies:</strong> Remember your preferences</li>
              <li><strong>Marketing Cookies:</strong> Track marketing effectiveness (with consent)</li>
            </ul>
            <p class="mb-0">You can control cookies through your browser settings. Note that disabling cookies may affect website functionality.</p>
          </div>
        </div>

        <!-- Section 8 -->
        <div class="card mb-4 shadow-sm" id="section8">
          <div class="card-body">
            <h4 class="card-title mb-3">8. Third-Party Links</h4>
            <p>Our website may contain links to third-party websites. We are not responsible for the privacy practices or content of external sites. We encourage you to review the privacy policies of any third-party websites before providing your information.</p>
            <p class="mb-0">This Privacy Policy applies only to information collected through our website and services.</p>
          </div>
        </div>

        <!-- Section 9 -->
        <div class="card mb-4 shadow-sm" id="section9">
          <div class="card-body">
            <h4 class="card-title mb-3">9. Children's Privacy</h4>
            <p>Our website and services are not intended for individuals under 18 years of age. We do not knowingly collect personal information from children. If we become aware that we have collected information from a child without parental consent, we will take steps to delete such information promptly.</p>
            <p class="mb-0">If you believe we have collected information from a child, please contact us immediately.</p>
          </div>
        </div>

        <!-- Section 10 -->
        <div class="card mb-4 shadow-sm" id="section10">
          <div class="card-body">
            <h4 class="card-title mb-3">10. Contact Us</h4>
            <p>If you have questions, concerns, or requests regarding this Privacy Policy or our privacy practices, please contact us at:</p>
            <ul class="list-unstyled">
              <li><strong>Email:</strong> <a href="mailto:{{ config('company.email') }}">{{ config('company.email') }}</a></li>
              <li><strong>Phone:</strong> <a href="tel:{{ config('company.phone') }}">{{ config('company.phone') }}</a></li>
              <li><strong>Address:</strong> {{ config('company.address') }}</li>
            </ul>
            <p class="mt-3 mb-0"><strong>Response Time:</strong> We will respond to privacy requests within 30 days or as required by applicable law.</p>
          </div>
        </div>

        <!-- Policy Update Notice -->
        <div class="alert alert-info mt-5">
          <h5 class="alert-heading">Policy Updates</h5>
          <p class="mb-0">We may update this Privacy Policy from time to time. We will notify you of significant changes by posting a notice on our website or sending you an email. Your continued use of our services after changes constitutes your acceptance of the updated Privacy Policy.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.page-header-area {
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.policy-section {
  background-color: #f8f9fa;
}

.card {
  border: none;
  transition: all 0.3s ease;
}

.card:hover {
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12) !important;
}

.card-header {
  border-bottom: 1px solid #e9ecef;
}

.card-title {
  color: #667eea;
  font-weight: 600;
}

ul li {
  margin-bottom: 0.5rem;
  line-height: 1.6;
}

a {
  color: #667eea;
  text-decoration: none;
}

a:hover {
  color: #764ba2;
  text-decoration: underline;
}

@media (max-width: 768px) {
  .page-title {
    font-size: 2rem;
  }
}
</style>
@endsection
