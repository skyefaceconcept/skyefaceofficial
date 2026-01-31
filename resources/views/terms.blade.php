@extends('layouts.buzbox')

@section('content')
<div class="page-header-area" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 60px 0; color: white;">
  <div class="container">
    <h1 class="page-title" style="margin: 0; color: white; font-size: 2.5rem; font-weight: 700;">Terms & Conditions</h1>
    <p class="page-subtitle" style="margin: 10px 0 0 0; color: rgba(255,255,255,0.9); font-size: 1.1rem;">Please read our terms carefully</p>
  </div>
</div>

<section class="terms-section py-5">
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
              <li><a href="#section1" class="text-decoration-none">1. Introduction and Acceptance of Terms</a></li>
              <li><a href="#section2" class="text-decoration-none">2. Use License</a></li>
              <li><a href="#section3" class="text-decoration-none">3. Disclaimer of Warranties</a></li>
              <li><a href="#section4" class="text-decoration-none">4. Limitation of Liability</a></li>
              <li><a href="#section5" class="text-decoration-none">5. User Accounts and Passwords</a></li>
              <li><a href="#section6" class="text-decoration-none">6. Intellectual Property Rights</a></li>
              <li><a href="#section7" class="text-decoration-none">7. User Content</a></li>
              <li><a href="#section8" class="text-decoration-none">8. Payment Terms</a></li>
              <li><a href="#section9" class="text-decoration-none">9. Refund Policy</a></li>
              <li><a href="#section10" class="text-decoration-none">10. Prohibited Activities</a></li>
              <li><a href="#section11" class="text-decoration-none">11. Termination of Services</a></li>
              <li><a href="#section12" class="text-decoration-none">12. Governing Law</a></li>
            </ul>
          </div>
        </div>

        <!-- Section 1 -->
        <div class="card mb-4 shadow-sm" id="section1">
          <div class="card-body">
            <h4 class="card-title mb-3">1. Introduction and Acceptance of Terms</h4>
            <p>Welcome to {{ config('company.name') }} ("Company", "we", "our", or "us"). These Terms and Conditions ("Terms", "Terms of Service") govern your access to and use of our website and services.</p>
            <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.</p>
            <p class="mb-0"><strong>Important:</strong> We reserve the right to modify these terms at any time. Your continued use of the website signifies your acceptance of any modifications to these Terms.</p>
          </div>
        </div>

        <!-- Section 2 -->
        <div class="card mb-4 shadow-sm" id="section2">
          <div class="card-body">
            <h4 class="card-title mb-3">2. Use License</h4>
            <p>Permission is granted to temporarily download one copy of the materials (information or software) on {{ config('company.name') }}'s website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
            <ul class="ms-3">
              <li>Modifying or copying the materials</li>
              <li>Using the materials for any commercial purpose, or for any public display (commercial or non-commercial)</li>
              <li>Attempting to decompile or reverse engineer any software contained on the website</li>
              <li>Removing any copyright or other proprietary notations from the materials</li>
              <li>Transferring the materials to another person or "mirroring" the materials on any other server</li>
              <li>Using the website in a way that infringes upon the rights of others or that restricts or inhibits anyone's use and enjoyment of the website</li>
              <li>Obtaining or attempting to obtain any materials or information not intentionally made available through this website</li>
              <li>Harassing, insulting, or intimidating any user of the website</li>
            </ul>
          </div>
        </div>

        <!-- Section 3 -->
        <div class="card mb-4 shadow-sm" id="section3">
          <div class="card-body">
            <h4 class="card-title mb-3">3. Disclaimer of Warranties</h4>
            <p>The materials on {{ config('company.name') }}'s website are provided on an 'as is' basis. {{ config('company.name') }} makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
            <p class="mb-0">Further, {{ config('company.name') }} does not warrant or make any representations concerning the accuracy, likely results, or reliability of the use of the materials on its website or otherwise relating to such materials or on any sites linked to this website.</p>
          </div>
        </div>

        <!-- Section 4 -->
        <div class="card mb-4 shadow-sm" id="section4">
          <div class="card-body">
            <h4 class="card-title mb-3">4. Limitation of Liability</h4>
            <p>In no event shall {{ config('company.name') }} or its suppliers be liable for any damages (including, without limitation, damages for loss of data or profit, or due to business interruption) arising out of the use or inability to use the materials on {{ config('company.name') }}'s website, even if {{ config('company.name') }} or an authorized representative of this website has been notified orally or in writing of the possibility of such damage.</p>
            <p class="mb-0">Because some jurisdictions do not allow limitations on implied warranties, or limitations of liability for consequential or incidental damages, these limitations may not apply to you.</p>
          </div>
        </div>

        <!-- Section 5 -->
        <div class="card mb-4 shadow-sm" id="section5">
          <div class="card-body">
            <h4 class="card-title mb-3">5. User Accounts and Passwords</h4>
            <p>If you create an account on our website, you are responsible for maintaining the confidentiality of your password and for all activities that occur under your account. You agree to:</p>
            <ul class="ms-3">
              <li>Provide true, accurate, current, and complete information during registration</li>
              <li>Maintain and promptly update your account information</li>
              <li>Maintain the security of your password and account</li>
              <li>Notify us immediately of any unauthorized use of your account</li>
            </ul>
            <p class="mb-0">{{ config('company.name') }} will not be liable for any loss or damage arising from your failure to maintain the confidentiality of your password.</p>
          </div>
        </div>

        <!-- Section 6 -->
        <div class="card mb-4 shadow-sm" id="section6">
          <div class="card-body">
            <h4 class="card-title mb-3">6. Intellectual Property Rights</h4>
            <p>Unless otherwise stated, {{ config('company.name') }} owns the intellectual property rights for all material on this website. All intellectual property rights are reserved. You may access this for personal use subject to restrictions set in these terms and conditions.</p>
            <p>You must not:</p>
            <ul class="ms-3">
              <li>Republish material from this website</li>
              <li>Sell, rent, or sub-license material from the website</li>
              <li>Reproduce, duplicate, or copy material from this website</li>
              <li>Redistribute content from {{ config('company.name') }} (unless content is specifically made for redistribution)</li>
            </ul>
          </div>
        </div>

        <!-- Section 7 -->
        <div class="card mb-4 shadow-sm" id="section7">
          <div class="card-body">
            <h4 class="card-title mb-3">7. User Content</h4>
            <p>In these Terms and Conditions, "User Content" shall mean any audio, video, text, images, or other material you choose to display on this website. By displaying User Content, you grant {{ config('company.name') }} a non-exclusive, worldwide, irrevocable license to reproduce, adapt, modify, translate, and distribute it in any media.</p>
            <p>You represent and warrant that:</p>
            <ul class="ms-3">
              <li>You own or have the necessary licenses, rights, consents, and permissions to use and authorize {{ config('company.name') }} to use all patent, trademark, trade secret, copyright, or other proprietary information in any User Content</li>
              <li>User Content does not contain any viruses, worms, malware, or any other harmful code</li>
              <li>User Content is not obscene, lewd, lascivious, filthy, violent, harassing, libelous, slanderous, or otherwise objectionable</li>
            </ul>
          </div>
        </div>

        <!-- Section 8 -->
        <div class="card mb-4 shadow-sm" id="section8">
          <div class="card-body">
            <h4 class="card-title mb-3">8. Payment Terms</h4>
            <p>When you make a purchase from {{ config('company.name') }}, you agree to pay the stated price plus any applicable taxes and shipping fees. Payment must be received in full before we deliver your order.</p>
            <p>We accept various payment methods as indicated on the checkout page. All payment information is handled securely through our payment processors. By providing payment information, you authorize us to charge your account for the purchase.</p>
            <p class="mb-0">We reserve the right to refuse service to anyone for any reason at any time.</p>
          </div>
        </div>

        <!-- Section 9 -->
        <div class="card mb-4 shadow-sm" id="section9">
          <div class="card-body">
            <h4 class="card-title mb-3">9. Refund Policy</h4>
            <p>Refunds for digital products are subject to the following conditions:</p>
            <ul class="ms-3">
              <li>Refund requests must be submitted within 30 days of purchase</li>
              <li>The digital product must not have been substantially used or downloaded excessively</li>
              <li>Refunds are issued to the original payment method</li>
              <li>Processing time for refunds is typically 5-10 business days</li>
            </ul>
            <p>For physical products, we offer a 30-day return policy. Items must be in original condition with all packaging materials intact.</p>
            <p class="mb-0">{{ config('company.name') }} reserves the right to refuse refunds for products that show evidence of significant use or damage.</p>
          </div>
        </div>

        <!-- Section 10 -->
        <div class="card mb-4 shadow-sm" id="section10">
          <div class="card-body">
            <h4 class="card-title mb-3">10. Prohibited Activities</h4>
            <p>You agree that you will not use this website or its content:</p>
            <ul class="ms-3">
              <li>To harass, abuse, insult, harm, defame, slander, disparage, intimidate, or discriminate based on gender, sexual orientation, religion, ethnicity, race, age, national origin, or disability</li>
              <li>To submit false or misleading information</li>
              <li>To upload viruses or malicious code</li>
              <li>To spam, phish, pharm, pretext, spider, crawl, or scrape</li>
              <li>For any unlawful purposes or to solicit others to do unlawful acts</li>
              <li>To violate any applicable laws, rules, or regulations</li>
              <li>To infringe on any intellectual property rights of others</li>
              <li>To interfere with the operation of the website or harm the infrastructure</li>
            </ul>
          </div>
        </div>

        <!-- Section 11 -->
        <div class="card mb-4 shadow-sm" id="section11">
          <div class="card-body">
            <h4 class="card-title mb-3">11. Termination of Services</h4>
            <p>{{ config('company.name') }} may terminate your access to the website and services immediately, without prior notice or liability, for any reason whatsoever, including if you breach the Terms.</p>
            <p>Upon termination of your access, your right to use the website will immediately cease. If you wish to terminate your account, you may contact us with a termination request.</p>
            <p class="mb-0">All provisions of these Terms which by their nature should survive termination shall survive termination, including, without limitation, ownership provisions, warranty disclaimers, indemnity, and limitations of liability.</p>
          </div>
        </div>

        <!-- Section 12 -->
        <div class="card mb-4 shadow-sm" id="section12">
          <div class="card-body">
            <h4 class="card-title mb-3">12. Governing Law</h4>
            <p>These Terms and Conditions are governed by and construed in accordance with the laws of Nigeria, and you irrevocably submit to the exclusive jurisdiction of the courts in that location.</p>
            <p class="mb-0">If any provision of these Terms is found to be invalid or unenforceable, the remaining provisions will continue in full force and effect.</p>
          </div>
        </div>

        <!-- Contact Section -->
        <div class="alert alert-light border border-secondary mt-5">
          <h5 class="mb-3">Have Questions?</h5>
          <p>If you have any questions about these Terms and Conditions, please contact us at:</p>
          <ul class="list-unstyled">
            <li><strong>Email:</strong> <a href="mailto:{{ config('company.email') }}">{{ config('company.email') }}</a></li>
            <li><strong>Phone:</strong> <a href="tel:{{ config('company.phone') }}">{{ config('company.phone') }}</a></li>
            <li><strong>Address:</strong> {{ config('company.address') }}</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.page-header-area {
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.terms-section {
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
