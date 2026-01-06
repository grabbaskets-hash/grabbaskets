<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdrawals | GrabBaskets</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #ff8c00;
            --primary-light: #fff5e6;
            --secondary: #2c3e50;
            --text-main: #1a1a1a;
            --text-muted: #666;
            --bg-body: #f8f9fb;
            --white: #ffffff;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            --success: #52c41a;
            --warning: #faad14;
            --danger: #ff4d4f;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg-body);
            color: var(--text-main);
            padding-bottom: 50px;
        }

        .header {
            background: var(--secondary);
            padding: 40px 20px 80px;
            color: var(--white);
            text-align: center;
            position: relative;
        }

        .back-btn {
            position: absolute;
            left: 20px;
            top: 20px;
            color: var(--white);
            text-decoration: none;
            font-size: 1.2rem;
        }

        .container {
            max-width: 600px;
            margin: -60px auto 0;
            padding: 0 20px;
        }

        .card {
            background: var(--white);
            border-radius: 24px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .balance-card {
            text-align: center;
            background: linear-gradient(135deg, var(--primary), #ff6a00);
            color: white;
        }

        .balance-value {
            font-size: 2rem;
            font-weight: 700;
            display: block;
        }

        .balance-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1.5px solid #eee;
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        .btn {
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            font-family: inherit;
            transition: opacity 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-secondary {
            background: var(--secondary);
            color: white;
            margin-top: 10px;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .status-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff7e6;
            color: #faad14;
        }

        .status-completed {
            background: #f6ffed;
            color: #52c41a;
        }

        .status-rejected {
            background: #fff1f0;
            color: #ff4d4f;
        }

        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .trans-info {
            display: flex;
            flex-direction: column;
        }

        .trans-amount {
            font-weight: 700;
            font-size: 1rem;
        }

        .trans-date {
            font-size: 0.8rem;
            color: var(--text-muted);
        }

        .alert {
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>

<body>
    <div class="header">
        <a href="{{ route('profile.show') }}" class="back-btn"><i class="bi bi-arrow-left"></i></a>
        <h2 style="font-weight: 600;">Withdrawals</h2>
    </div>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if(!$user->canWithdraw())
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                You need more than 3 successful referrals to withdraw.
                Current referrals: <strong>{{ $user->referrals()->count() }}</strong>
            </div>
        @endif

        <div class="card balance-card">
            <span class="balance-label">Available for Withdrawal</span>
            <span class="balance-value">₹{{ number_format($user->wallet_point, 0) }}</span>
            <span class="balance-label">({{ $user->wallet_point }} Points)</span>
        </div>

        <!-- Bank Details Section -->
        <div class="card">
            <h4 class="section-title"><i class="bi bi-bank"></i> Bank Details</h4>
            <form action="{{ route('withdrawals.bank-details.save') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Account Holder Name</label>
                    <input type="text" name="account_holder_name" class="form-control"
                        value="{{ $bankDetails->account_holder_name ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label>Account Number</label>
                    <input type="text" name="account_number" class="form-control"
                        value="{{ $bankDetails->account_number ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label>IFSC Code</label>
                    <input type="text" name="ifsc_code" class="form-control" value="{{ $bankDetails->ifsc_code ?? '' }}"
                        required>
                </div>
                <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" name="bank_name" class="form-control" value="{{ $bankDetails->bank_name ?? '' }}"
                        required>
                </div>
                <button type="submit" class="btn btn-secondary">Save Bank Details</button>
            </form>
        </div>

        <!-- Request Withdrawal Section -->
        <div class="card">
            <h4 class="section-title"><i class="bi bi-cash-stack"></i> Request Withdrawal</h4>
            <form action="{{ route('withdrawals.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Amount (Min ₹300)</label>
                    <input type="number" name="amount" class="form-control" placeholder="Enter amount to withdraw"
                        min="300" max="{{ $user->wallet_point }}" required {{ !$user->canWithdraw() || !$user->bankDetails ? 'disabled' : '' }}>
                </div>
                <button type="submit" class="btn btn-primary" {{ !$user->canWithdraw() || !$user->bankDetails || $user->wallet_point < 300 ? 'disabled' : '' }}>
                    Request Withdrawal
                </button>
                @if(!$user->bankDetails)
                    <p style="font-size: 0.8rem; color: var(--danger); margin-top: 10px; text-align: center;">Save bank
                        details first</p>
                @elseif($user->wallet_point < 300)
                    <p style="font-size: 0.8rem; color: var(--danger); margin-top: 10px; text-align: center;">Minimum
                        withdrawal is ₹300</p>
                @endif
            </form>
        </div>

        <!-- Withdrawal History Section -->
        <div class="card">
            <h4 class="section-title"><i class="bi bi-clock-history"></i> Withdrawal History</h4>
            @if($withdrawals->isEmpty())
                <p style="text-align: center; color: var(--text-muted); font-size: 0.9rem; padding: 20px;">No withdrawal
                    requests yet.</p>
            @else
                @foreach($withdrawals as $withdrawal)
                    <div class="transaction-item">
                        <div class="trans-info">
                            <span class="trans-amount">₹{{ number_format($withdrawal->amount, 0) }}</span>
                            <span class="trans-date">{{ $withdrawal->created_at->format('M d, Y h:i A') }}</span>
                            @if($withdrawal->transaction_id)
                                <span style="font-size: 0.7rem; color: var(--text-muted);">Ref:
                                    {{ $withdrawal->transaction_id }}</span>
                            @endif
                        </div>
                        <span class="status-badge status-{{ $withdrawal->status }}">{{ $withdrawal->status }}</span>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</body>

</html>