@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('content')
<style>
  :root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --success-gradient: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    --warning-gradient: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
    --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  }

  .dashboard-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2.5rem 2rem;
    border-radius: 10px;
    margin-bottom: 2rem;
    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.2);
  }

  .dashboard-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    letter-spacing: -0.5px;
  }

  .dashboard-header p {
    font-size: 1rem;
    opacity: 0.95;
    margin-bottom: 0;
  }

  .header-meta {
    display: flex;
    gap: 2rem;
    margin-top: 1.5rem;
    font-size: 0.95rem;
  }

  .header-meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .metric-card {
    background: white;
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    position: relative;
  }

  .metric-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--card-gradient);
  }

  .metric-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12);
  }

  .metric-card.card-primary { --card-gradient: var(--primary-gradient); }
  .metric-card.card-success { --card-gradient: var(--success-gradient); }
  .metric-card.card-warning { --card-gradient: var(--warning-gradient); }
  .metric-card.card-danger { --card-gradient: var(--danger-gradient); }
  .metric-card.card-info { --card-gradient: var(--info-gradient); }

  .metric-icon {
    font-size: 2.5rem;
    opacity: 0.1;
    position: absolute;
    right: 15px;
    top: 15px;
  }

  .metric-label {
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #6c757d;
    margin-bottom: 0.5rem;
  }

  .metric-value {
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0.5rem 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .metric-subtitle {
    font-size: 0.9rem;
    color: #999;
    margin-top: 0.5rem;
  }

  .metric-stat {
    display: inline-block;
    background: #f8f9fa;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    margin-top: 0.5rem;
  }

  .metric-stat.success { background: #d4edda; color: #155724; }
  .metric-stat.warning { background: #fff3cd; color: #856404; }
  .metric-stat.danger { background: #f8d7da; color: #721c24; }

  .summary-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    overflow: hidden;
  }

  .summary-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
    font-size: 1.25rem;
    font-weight: 700;
  }

  .summary-section {
    padding: 2rem;
    border-right: 1px solid #eee;
  }

  .summary-section:last-child {
    border-right: none;
  }

  .summary-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  .summary-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .summary-list li {
    padding: 1rem 0;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
  }

  .summary-list li:last-child {
    border-bottom: none;
  }

  .summary-list .badge {
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    font-size: 0.9rem;
    font-weight: 700;
    min-width: 50px;
    text-align: center;
    white-space: nowrap;
    flex-shrink: 0;
    margin-top: 0.2rem;
  }

  .summary-content {
    flex: 1;
  }

  .summary-content strong {
    display: block;
    color: #333;
    margin-bottom: 0.25rem;
    font-size: 0.95rem;
  }

  .summary-content small {
    color: #666;
    font-size: 0.85rem;
  }

  .progress-bar-animated-gradient {
    background: var(--primary-gradient);
  }

  .progress {
    height: 6px;
    border-radius: 3px;
    background-color: #e9ecef;
    margin-top: 0.5rem;
  }

  .stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
  }

  @media (max-width: 768px) {
    .dashboard-header {
      padding: 1.5rem 1rem;
    }

    .dashboard-header h1 {
      font-size: 1.8rem;
    }

    .metric-value {
      font-size: 1.8rem;
    }

    .summary-section {
      border-right: none;
      border-bottom: 1px solid #eee;
      padding: 1.5rem;
    }

    .summary-section:last-child {
      border-bottom: none;
    }
  }
</style>

<!-- Header Section -->
<div class="dashboard-header">
  <div class="row align-items-center">
    <div class="col-md-8">
      <h1>📊 Dashboard</h1>
      <p>Welcome back, <strong>{{ auth()->user()->name }}</strong></p>
      <div class="header-meta">
        <div class="header-meta-item">
          <span>Role:</span>
          <strong>{{ auth()->user()->role->name ?? 'User' }}</strong>
        </div>
        <div class="header-meta-item">
          <span>Last Updated:</span>
          <strong>{{ now()->format('M d, Y • H:i') }}</strong>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Key Performance Indicators -->
<div class="row mb-4">
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="metric-card card-primary">
      <div class="metric-icon">💰</div>
      <div class="card-body">
        <div class="metric-label">💳 Total Revenue</div>
        <div class="metric-value">₦{{ number_format($totalRevenue ?? 0, 0) }}</div>
        <div class="metric-stat success">{{ $totalPayments ?? 0 }} Payments</div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 mb-3">
    <div class="metric-card card-warning">
      <div class="metric-icon">⏳</div>
      <div class="card-body">
        <div class="metric-label">⚠️ Pending Payments</div>
        <div class="metric-value">{{ $pendingPayments ?? 0 }}</div>
        <div class="metric-stat warning">Awaiting Completion</div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 mb-3">
    <div class="metric-card card-info">
      <div class="metric-icon">📋</div>
      <div class="card-body">
        <div class="metric-label">📄 Total Quotes</div>
        <div class="metric-value">{{ $totalQuotes ?? 0 }}</div>
        <div class="metric-stat success">{{ $acceptedQuotes ?? 0 }} Accepted</div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 mb-3">
    <div class="metric-card card-success">
      <div class="metric-icon">🔧</div>
      <div class="card-body">
        <div class="metric-label">🛠️ Total Repairs</div>
        <div class="metric-value">{{ $totalRepairs ?? 0 }}</div>
        <div class="metric-stat success">{{ $completedRepairs ?? 0 }} Completed</div>
      </div>
    </div>
  </div>
</div>

<!-- Secondary Metrics Row -->
<div class="row mb-4">
  <div class="col-lg-3 col-md-6 mb-3">
    <div class="metric-card card-danger">
      <div class="metric-icon">🎫</div>
      <div class="card-body">
        <div class="metric-label">🎟️ Support Tickets</div>
        <div class="metric-value">{{ $totalTickets ?? 0 }}</div>
        <div class="metric-stat danger">{{ $openTickets ?? 0 }} Open</div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 mb-3">
    <div class="metric-card card-primary">
      <div class="metric-icon">📦</div>
      <div class="card-body">
        <div class="metric-label">📮 Total Orders</div>
        <div class="metric-value">{{ $totalOrders ?? 0 }}</div>
        <div class="metric-stat success">{{ $completedOrders ?? 0 }} Completed</div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 mb-3">
    <div class="metric-card card-info">
      <div class="metric-icon">👥</div>
      <div class="card-body">
        <div class="metric-label">👤 Active Users</div>
        <div class="metric-value">{{ $totalUsers ?? 0 }}</div>
        <div class="metric-stat success">{{ $activeAdmins ?? 0 }} Admins</div>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 mb-3">
    <div class="metric-card card-success">
      <div class="metric-icon">⚙️</div>
      <div class="card-body">
        <div class="metric-label">⚡ System Roles</div>
        <div class="metric-value">{{ $totalRoles ?? 0 }}</div>
        <div class="metric-stat">Role Types</div>
      </div>
    </div>
  </div>
</div>

<!-- Detailed Summary Section -->
<div class="row">
  <div class="col-lg-12">
    <div class="summary-card">
      <div class="summary-card-header">
        📈 Executive Summary
      </div>
      <div class="row no-gutters">
        <div class="col-md-6 summary-section">
          <div class="summary-title">💼 Business Performance</div>
          <ul class="summary-list">
            <li>
              <span class="badge badge-primary">{{ $totalPayments ?? 0 }}</span>
              <div class="summary-content">
                <strong>Total Transactions</strong>
                <small>Completed payments processed</small>
              </div>
            </li>
            <li>
              <span class="badge badge-warning">{{ $pendingPayments ?? 0 }}</span>
              <div class="summary-content">
                <strong>Pending Payments</strong>
                <small>Require immediate attention</small>
              </div>
            </li>
            <li>
              <span class="badge badge-info">{{ $pendingQuotes ?? 0 }}</span>
              <div class="summary-content">
                <strong>Pending Quotes</strong>
                <small>Awaiting customer response</small>
              </div>
            </li>
            <li>
              <span class="badge badge-success">{{ $acceptedQuotes ?? 0 }}</span>
              <div class="summary-content">
                <strong>Accepted Quotes</strong>
                <small>Ready for service delivery</small>
              </div>
            </li>
          </ul>
        </div>
        <div class="col-md-6 summary-section">
          <div class="summary-title">🏗️ Operational Status</div>
          <ul class="summary-list">
            <li>
              <span class="badge badge-success">{{ $completedRepairs ?? 0 }}/{{ $totalRepairs ?? 0 }}</span>
              <div class="summary-content">
                <strong>Repairs Completion Rate</strong>
                <small>{{ $totalRepairs ? round(($completedRepairs / $totalRepairs) * 100) : 0 }}% completion status</small>
              </div>
            </li>
            <li>
              <span class="badge badge-warning">{{ $pendingRepairs ?? 0 }}</span>
              <div class="summary-content">
                <strong>In-Progress Repairs</strong>
                <small>Currently under service</small>
              </div>
            </li>
            <li>
              <span class="badge badge-danger">{{ $openTickets ?? 0 }}</span>
              <div class="summary-content">
                <strong>Open Support Tickets</strong>
                <small>{{ $closedTickets ?? 0 }} closed today</small>
              </div>
            </li>
            <li>
              <span class="badge badge-success">{{ $completedOrders ?? 0 }}/{{ $totalOrders ?? 0 }}</span>
              <div class="summary-content">
                <strong>Order Fulfillment Rate</strong>
                <small>{{ $totalOrders ? round(($completedOrders / $totalOrders) * 100) : 0 }}% orders delivered</small>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection
