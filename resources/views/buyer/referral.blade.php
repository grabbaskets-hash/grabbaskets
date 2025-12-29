<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Referral Code - GrabBasket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .referral-card {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .referral-code-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            margin: 30px 0;
        }

        .referral-code {
            font-size: 2.5rem;
            font-weight: bold;
            letter-spacing: 5px;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
        }

        .copy-btn {
            background: white;
            color: #667eea;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .copy-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .stats-box {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 30px;
        }

        .stat-item {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .share-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 20px;
        }

        .share-btn {
            padding: 10px 20px;
            border-radius: 10px;
            border: none;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
        }

        .share-whatsapp {
            background: #25D366;
        }

        .share-facebook {
            background: #1877F2;
        }

        .share-twitter {
            background: #1DA1F2;
        }
    </style>
</head>

<body>
    <div class="referral-card">
        <div class="text-center mb-4">
            <i class="fas fa-gift" style="font-size: 3rem; color: #667eea;"></i>
            <h2 class="mt-3">Refer & Earn</h2>
            <p class="text-muted">Share your code and earn 300 points for each friend who joins!</p>
        </div>

        <div class="referral-code-box">
            <div class="small">Your Referral Code</div>
            <div class="referral-code" id="referralCode">{{ auth()->user()->referral_code }}</div>
            <button class="copy-btn" onclick="copyCode()">
                <i class="fas fa-copy"></i> Copy Code
            </button>
        </div>

        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i>
            <strong>How it works:</strong>
            <ul class="mb-0 mt-2">
                <li>Share your referral code with friends</li>
                <li>They enter it during registration</li>
                <li>They get their own referral code to share</li>
                <li>You get <strong>300 points</strong> for each referral!</li>
            </ul>
        </div>

        <div class="stats-box">
            <div class="stat-item">
                <div class="stat-number">{{ auth()->user()->referrals()->count() }}</div>
                <div class="stat-label">Total Referrals</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">{{ auth()->user()->wallet_point }}</div>
                <div class="stat-label">Wallet Points</div>
            </div>
        </div>

        <div class="share-buttons">
            <button class="share-btn share-whatsapp" onclick="shareWhatsApp()">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </button>
            <button class="share-btn share-facebook" onclick="shareFacebook()">
                <i class="fab fa-facebook"></i> Facebook
            </button>
            <button class="share-btn share-twitter" onclick="shareTwitter()">
                <i class="fab fa-twitter"></i> Twitter
            </button>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Back to Home
            </a>
        </div>
    </div>

    <script>
        const referralCode = "{{ auth()->user()->referral_code }}";
        const referralUrl = "{{ url('/register') }}";

        function copyCode() {
            navigator.clipboard.writeText(referralCode).then(() => {
                const btn = document.querySelector('.copy-btn');
                const originalText = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                btn.style.background = '#28a745';
                btn.style.color = 'white';

                setTimeout(() => {
                    btn.innerHTML = originalText;
                    btn.style.background = 'white';
                    btn.style.color = '#667eea';
                }, 2000);
            });
        }

        function shareWhatsApp() {
            const message = `Join GrabBasket using my referral code ${referralCode} and get started! Register here: ${referralUrl}`;
            window.open(`https://wa.me/?text=${encodeURIComponent(message)}`, '_blank');
        }

        function shareFacebook() {
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(referralUrl)}`, '_blank');
        }

        function shareTwitter() {
            const message = `Join GrabBasket using my referral code ${referralCode} and get started!`;
            window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(message)}&url=${encodeURIComponent(referralUrl)}`, '_blank');
        }
    </script>
</body>

</html>