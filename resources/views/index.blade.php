<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'GrabBaskets') }} - 10 Minute Grocery Delivery</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            /* Brand Colors */
            --primary: #3C096C;
            --primary-light: #5A189A;
            --secondary: #FF6B00;
            --accent: #FFD700;
            /* Gold */
            --logout-red: linear-gradient(135deg, #ff416c, #ff4b2b);
            /* UI Colors */
            --bg-body: #f5f5f5;
            --bg-white: #ffffff;
            --text-main: #212529;
            --text-muted: #6c757d;
            --border-light: #e9ecef;
            --search-border: #d0d0d0;
            /* Spacing */
            --header-height-mobile: 110px;
            --bottom-nav-height: 70px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            -webkit-tap-highlight-color: transparent;
        }

        /* Utilities */
        .text-primary {
            color: var(--primary) !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .fw-semibold {
            font-weight: 600 !important;
        }

        .fs-7 {
            font-size: 0.85rem;
        }

        .fs-8 {
            font-size: 0.75rem;
        }

        .truncate-1 {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ===== ALL NAV BUTTONS: 10px RADIUS ===== */
        .join-btn,
        .cart-btn,
        .auth-name,
        .logout-btn,
        .icon-btn,
        .mobile-logout-btn,
        .browse-btn,
        .add-btn,
        .add-to-cart-btn-desktop {
            border-radius: 10px !important;
        }

        .navbar-gradient {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
        }

        /* Join Button */
        .join-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 4px 10px rgba(60, 9, 108, 0.25);
        }

        .join-btn i {
            color: var(--accent);
            /* Gold icon */
            font-size: 1.2rem;
        }

        .join-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(60, 9, 108, 0.4);
            background: linear-gradient(135deg, var(--primary-light), #7b2cbf);
        }

        /* Cart Button - Now matches Join Button */
        .cart-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 22px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            box-shadow: 0 4px 10px rgba(60, 9, 108, 0.25);
            text-decoration: none;
        }

        .cart-btn i {
            color: var(--accent);
            /* Gold icon */
            font-size: 1.2rem;
        }

        .cart-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(60, 9, 108, 0.4);
            background: linear-gradient(135deg, var(--primary-light), #7b2cbf);
        }

        /* Auth buttons */
        .auth-name {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.95);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }

        .auth-name i {
            color: var(--primary);
            font-size: 1.1rem;
        }

        .auth-name:hover {
            background: white;
            color: var(--primary-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .logout-btn {
            background: var(--logout-red);
            color: white;
            border: none;
            padding: 10px 18px;
            font-weight: 700;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(255, 75, 43, 0.2);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(255, 75, 43, 0.4);
            filter: brightness(1.1);
        }

        .logout-btn i {
            font-size: 1rem;
        }

        /* Search Bar */
        .search-bar-container {
            position: relative;
            width: 100%;
        }

        .search-input {
            width: 100%;
            background: transparent !important;
            border: 1px solid var(--search-border) !important;
            border-radius: 50px !important;
            padding: 10px 20px 10px 50px;
            color: white !important;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .search-input::placeholder {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        .search-input:focus {
            background: white !important;
            color: var(--text-main) !important;
            border: 1px solid var(--search-border) !important;
            outline: none;
            box-shadow: 0 0 0 2px rgba(60, 9, 108, 0.2) !important;
        }

        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.9) !important;
            font-size: 1.2rem;
            pointer-events: none;
        }

        .search-input:focus~.search-icon {
            color: var(--text-muted) !important;
        }

        /* Bounce Animation for Down Arrow */
        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-8px);
            }

            60% {
                transform: translateY(-4px);
            }
        }

        .down-arrow {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            animation: bounce 2s infinite;
        }

        /* Firework Animation (Global) */
        @keyframes explode {
            0% { transform: scale(1); opacity: 1; box-shadow: 0 0 0 #fdcf58, 0 0 0 #fdcf58, 0 0 0 #fdcf58, 0 0 0 #fdcf58, 0 0 0 #fdcf58, 0 0 0 #fdcf58, 0 0 0 #fdcf58, 0 0 0 #fdcf58; }
            100% { transform: scale(1.5); opacity: 0; box-shadow: -20px -30px 0 #fdcf58, 20px -30px 0 #ff00ea, -30px 10px 0 #00ffea, 30px 10px 0 #ff004c, -10px -50px 0 #ffac38, 10px -50px 0 #ffac38, -40px 0px 0 #ffac38, 40px 0px 0 #ffac38; }
        }

        .firework {
            position: absolute;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            animation: explode 1.5s infinite ease-out;
            opacity: 0;
            z-index: 1;
            pointer-events: none; /* Prevent clicking interaction issues */
        }
        .fw-1 { top: 20%; left: 20%; animation-delay: 0s; }
        .fw-2 { top: 30%; right: 20%; animation-delay: 0.5s; }
        .fw-3 { bottom: 30%; left: 40%; animation-delay: 1s; }
        .fw-4 { top: 15%; right: 40%; animation-delay: 0.8s; }

        /* ===== MOBILE VIEW ===== */
        @media (max-width: 991px) {
            .desktop-only {
                display: none !important;
            }

            body {
                padding-bottom: calc(var(--bottom-nav-height) + 20px);
            }

            .mobile-header {
                position: sticky;
                top: 0;
                z-index: 1000;
                padding: 15px;
                background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                border-bottom-left-radius: 20px;
                border-bottom-right-radius: 20px;
                margin-bottom: 0;
                box-shadow: 0 4px 15px rgba(37, 117, 252, 0.2);
            }

            .brand-mobile {
                font-size: 1.6rem;
                font-weight: 800;
                color: white;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .search-bar-container {
                margin-top: 12px;
            }

            .search-input {
                padding: 12px 15px 12px 48px;
                border-radius: 12px !important;
            }

            .search-icon {
                left: 15px;
                font-size: 1.1rem;
            }

            /* Mobile Icons - Consistent styling */
            .mobile-icons {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .icon-btn {
                width: 40px;
                height: 40px;
                border-radius: 10px;
                background: rgba(255, 255, 255, 0.2);
                backdrop-filter: blur(10px);
                display: flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                transition: all 0.2s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            }

            .icon-btn i {
                color: var(--accent) !important;
                font-size: 1.2rem;
                font-weight: bold;
            }

            .icon-btn:hover {
                transform: scale(1.05);
                background: rgba(255, 255, 255, 0.3);
            }

            .mobile-auth-group {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .mobile-logout-btn {
                background: var(--logout-red);
                border: none;
                color: white;
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .mobile-logout-btn i {
                font-size: 1.1rem;
            }

            .mobile-logout-btn:hover {
                transform: scale(1.1);
            }

            /* Banners - Match desktop style */
            .hero-banner {
                /* Premium Image - Dark City Night for Fireworks */
                background: url('https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1674&q=80');
                background-size: cover;
                background-position: center;
                animation: kenBurns 20s ease-in-out infinite alternate;
                height: auto;
                min-height: 260px;
                border-radius: 24px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center; /* Center Content */
                text-align: center; /* Center Text */
                padding: 35px 25px;
                color: white;
                margin: 0; /* Margin handled by carousel */
                position: relative;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
                overflow: hidden;
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            /* Second Hiring Slide - Dark Team Theme + Fireworks */
            .hero-banner-hiring-2 {
                /* Dark overlay to make fireworks pop on office image */
                background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1770&q=80');
                background-size: cover;
                background-position: center;
            }

            /* Partnership Slide */
            .hero-banner-alt {
                background: url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&auto=format&fit=crop&w=1769&q=80');
                background-size: cover;
                background-position: center;
            }

            @keyframes kenBurns {
                0% { background-size: 100%; transform: scale(1); }
                100% { background-size: 120%; transform: scale(1.05); }
            }



            /* REMOVED: Decorative Circle / Overlay Effect */
            .hero-banner::before {
                display: none;
            }

            .hero-banner h2 {
                font-size: 1.8rem;
                font-weight: 800;
                margin-bottom: 12px;
                line-height: 1.2;
                color: #fff;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.9); /* Stronger shadow for night mode */
                z-index: 2;
                position: relative;
            }

            .hero-banner p {
                 font-size: 1rem;
                 opacity: 1; /* Full opacity */
                 margin-bottom: 24px;
                 font-weight: 500;
                 line-height: 1.5;
                 color: #f1f1f1;
                 text-shadow: 1px 1px 3px rgba(0,0,0,0.9);
                 z-index: 2;
                 position: relative;
            }
            
            .style-join-btn {
                position: relative;
                z-index: 2;
                display: inline-block; /* Ensure centering works */
            }

            .join-btn-banner {
                background: #ffffff;
                color: var(--primary);
                border: none;
                padding: 12px 30px;
                font-weight: 700;
                border-radius: 50px; /* Pillow shape */
                text-align: center;
                text-decoration: none;
                width: fit-content;
                box-shadow: 0 5px 15px rgba(0,0,0,0.15);
                transition: transform 0.2s, box-shadow 0.2s;
                font-size: 1rem;
                z-index: 2;
                display: inline-block;
            }

            .join-btn-banner:hover {
                 transform: translateY(-2px);
                 box-shadow: 0 8px 20px rgba(0,0,0,0.2);
                 background: #f8f9fa;
            }

            .munchies-banner {
                background: linear-gradient(135deg, #FFD700 0%, #FF9A00 100%);
                min-height: 220px;
                border-radius: 20px;
                padding: 25px 20px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                margin: 20px 15px;
                box-shadow: 0 4px 15px rgba(255, 154, 0, 0.25);
            }

            .munchies-banner h3 {
                font-size: 1.8rem;
                margin-bottom: 10px;
                color: #212529;
                font-weight: 700;
            }

            .munchies-banner p {
                color: #5a5a5a;
                margin-bottom: 20px;
                font-weight: 500;
            }

            .browse-btn {
                background: #3C096C;
                color: white;
                border: none;
                padding: 10px 24px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                text-decoration: none;
                transition: all 0.3s ease;
                width: fit-content;
            }

            .browse-btn:hover {
                background: #2a064d;
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(60, 9, 108, 0.3);
            }

            .category-grid {
                display: grid;
                grid-template-columns: repeat(4, 1fr); /* 4 items per row for better fit */
                gap: 12px 8px;
                padding: 20px 15px;
                background: #fff;
                border-radius: 20px;
                margin: 15px 10px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            }

            .category-item {
                text-align: center;
                text-decoration: none;
                color: var(--text-main);
                display: flex;
                flex-direction: column;
                align-items: center;
            }

            .cat-icon-box {
                width: 60px;
                height: 60px;
                background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.6rem;
                margin: 0 auto 6px;
                transition: all 0.2s;
                box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            }

            .category-item:active .cat-icon-box {
                transform: scale(0.95);
            }

            .product-rail {
                background: #fff;
                padding: 20px 0;
                margin-bottom: 10px;
            }

            .rail-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0 15px 15px;
            }

            .rail-scroll {
                display: flex;
                overflow-x: auto;
                gap: 15px;
                padding: 0 15px;
                scrollbar-width: none;
            }

            .rail-scroll::-webkit-scrollbar {
                display: none;
            }

            .product-card-mobile {
                min-width: 140px;
                width: 140px;
                flex-shrink: 0;
            }

            .pm-image-box {
                width: 100%;
                height: 140px;
                background: #f8f9fa;
                border-radius: 14px;
                padding: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 10px;
                border: 1px solid var(--border-light);
            }

            .pm-image {
                max-width: 100%;
                max-height: 100%;
                object-fit: contain;
                mix-blend-mode: multiply;
            }

            .add-btn {
                background: #fff;
                border: 1px solid var(--primary);
                color: var(--primary);
                padding: 5px 0;
                width: 100%;
                font-weight: 600;
                font-size: 0.9rem;
                margin-top: 5px;
                border-radius: 8px; /* Rounded */
                transition: all 0.2s;
            }

            /* Quantity Control Styles */
            .qty-control {
                display: flex;
                align-items: center;
                justify-content: space-between;
                background: var(--primary);
                border-radius: 8px;
                padding: 4px;
                width: 100%;
                margin-top: 5px;
                height: 33px;
            }

            .qty-btn {
                background: none;
                border: none;
                color: white;
                font-weight: 700;
                width: 24px;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
            }

            .qty-val {
                color: white;
                font-weight: 600;
                font-size: 0.9rem;
            }

            /* Fix product card layout shift */
            .product-card-mobile {
                min-width: 140px;
                width: 140px;
                flex-shrink: 0;
                display: flex;
                flex-direction: column;
            }

            /* Mobile Header - Remove duplicate */
            /* Already defined above */
            
            main {
                padding-left: 5px;
                padding-right: 5px;
            }

            .add-btn:active {
                background: var(--primary);
                color: #fff;
            }

            .bottom-nav {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                height: var(--bottom-nav-height);
                background: #fff;
                display: flex;
                justify-content: space-around;
                align-items: center;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                z-index: 1050;
                padding: 5px 0;
                border-top: 1px solid var(--border-light);
            }

            .nav-link-mobile {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                color: var(--text-muted);
                font-size: 0.7rem;
                font-weight: 600;
                gap: 3px;
                flex: 1;
                height: 100%;
                transition: all 0.2s;
            }

            .nav-link-mobile i {
                font-size: 1.5rem;
                margin-bottom: 0;
            }

            .nav-link-mobile.active {
                color: var(--primary);
                font-weight: 700;
            }

            .nav-link-mobile.active i {
                transform: scale(1.1);
            }

            /* Mobile Categories Sidebar */
            .mobile-sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                z-index: 2000;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }

            .mobile-sidebar-overlay.active {
                opacity: 1;
                visibility: visible;
            }

            .mobile-categories-sidebar {
                position: fixed;
                top: 0;
                left: -100%;
                width: 80%;
                max-width: 320px;
                height: 100%;
                background: #fff;
                z-index: 2001;
                transition: left 0.3s ease;
                overflow-y: auto;
                box-shadow: 4px 0 15px rgba(0, 0, 0, 0.2);
            }

            .mobile-categories-sidebar.active {
                left: 0;
            }

            .sidebar-header {
                background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                color: white;
                padding: 20px 15px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: sticky;
                top: 0;
                z-index: 10;
            }

            .sidebar-header h3 {
                margin: 0;
                font-size: 1.3rem;
                font-weight: 700;
            }

            .sidebar-close-btn {
                background: rgba(255, 255, 255, 0.2);
                border: none;
                color: white;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                cursor: pointer;
                transition: all 0.2s;
            }

            .sidebar-close-btn:hover {
                background: rgba(255, 255, 255, 0.3);
                transform: scale(1.1);
            }

            .sidebar-category-list {
                padding: 10px 0;
            }

            .sidebar-category-item {
                display: flex;
                align-items: center;
                padding: 15px 20px;
                text-decoration: none;
                color: var(--text-main);
                transition: all 0.2s;
                border-bottom: 1px solid var(--border-light);
            }

            .sidebar-category-item:hover,
            .sidebar-category-item:active {
                background: var(--bg-body);
                padding-left: 25px;
            }

            .sidebar-category-icon {
                width: 45px;
                height: 45px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.8rem;
                background: linear-gradient(135deg, #f0fdf4 0%, #e8f5e9 100%);
                border-radius: 12px;
                margin-right: 15px;
                flex-shrink: 0;
            }

            .sidebar-category-name {
                font-weight: 600;
                font-size: 1rem;
            }
        }

        /* ===== DESKTOP VIEW ===== */
        @media (min-width: 992px) {
            .mobile-only {
                display: none !important;
            }

            .desktop-navbar {
                background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
                padding: 15px 0;
                position: sticky;
                top: 0;
                z-index: 1000;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
            }

            .navbar-container {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
                max-width: 1400px;
                margin: 0 auto;
                padding: 0 20px;
            }

            .brand-section {
                display: flex;
                align-items: center;
            }

            .brand-logo {
                font-size: 1.8rem;
                font-weight: 800;
                color: white;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .delivery-info {
                max-width: 200px;
                line-height: 1.2;
                border-left: 1px solid rgba(255, 255, 255, 0.3);
                padding-left: 15px;
                margin-left: 15px;
                color: rgba(255, 255, 255, 0.9);
            }

            .desktop-search {
                flex: 1;
                max-width: 500px;
                margin: 0 20px;
            }

            .nav-actions {
                display: flex;
                align-items: center;
                gap: 15px;
                flex-shrink: 0;
            }

            .main-layout {
                padding-top: 30px;
                display: grid;
                grid-template-columns: 240px 1fr;
                gap: 30px;
            }

            .sidebar-menu {
                background: #fff;
                border-radius: 16px;
                padding: 15px;
                position: sticky;
                top: 100px;
                max-height: calc(100vh - 120px);
                overflow-y: auto;
            }

            .side-link {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 12px 15px;
                color: var(--text-main);
                text-decoration: none;
                border-radius: 10px;
                transition: all 0.2s;
                font-weight: 500;
            }

            .side-link:hover {
                background: #f8f9fa;
                color: var(--primary);
                transform: translateX(5px);
            }

            .desktop-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                gap: 20px;
                margin-bottom: 40px;
            }

            .product-card-desktop {
                background: #fff;
                border: 1px solid var(--border-light);
                border-radius: 16px;
                padding: 15px;
                display: flex;
                flex-direction: column;
                transition: all 0.3s ease;
                height: 100%;
                cursor: pointer;
            }

            .product-card-desktop:hover {
                transform: translateY(-8px);
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
                border-color: var(--primary-light);
            }

            .pd-image-box {
                height: 180px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 15px;
                padding: 10px;
            }

            .pd-image {
                max-height: 100%;
                max-width: 100%;
                object-fit: contain;
            }

            .add-to-cart-btn-desktop {
                margin-top: auto;
                background: #fff;
                border: 1px solid var(--primary);
                color: var(--primary);
                padding: 8px;
                font-weight: 600;
                width: 100%;
                transition: all 0.2s;
            }

            .add-to-cart-btn-desktop:hover {
                background: var(--primary);
                color: #fff;
            }

            /* Banners - Match Mobile Elegant Style */
            .hero-banner {
                /* Premium Image - Dark City Night for Fireworks */
                background: url('https://images.unsplash.com/photo-1478760329108-5c3ed9d495a0?ixlib=rb-4.0.3&auto=format&fit=crop&w=1674&q=80');
                background-size: cover;
                background-position: center;
                animation: kenBurns 20s ease-in-out infinite alternate;
                height: auto;
                min-height: 260px;
                border-radius: 24px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center; /* Center Content */
                text-align: center; /* Center Text */
                padding: 35px 25px;
                color: white;
                margin-bottom: 30px;
                position: relative;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
                overflow: hidden;
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            /* Second Hiring Slide */
            .hero-banner-hiring-2 {
                background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1770&q=80');
                background-size: cover;
                background-position: center;
            }

            /* Partnership Slide */
            .hero-banner-alt {
                background: url('https://images.unsplash.com/photo-1521791136064-7986c2920216?ixlib=rb-4.0.3&auto=format&fit=crop&w=1769&q=80');
                background-size: cover;
                background-position: center;
            }

            /* REMOVED: Decorative Circle / Overlay */
            .hero-banner::before {
                display: none;
            }

            .hero-banner h2 {
                font-size: 2rem;
                font-weight: 800;
                margin-bottom: 12px;
                line-height: 1.2;
                background: linear-gradient(to right, #fff, #e0e0e0);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                z-index: 2;
            }

            .hero-banner p {
                 font-size: 1.1rem;
                 opacity: 0.95;
                 margin-bottom: 24px;
                 font-weight: 400;
                 line-height: 1.5;
                 color: rgba(255, 255, 255, 0.9);
                 z-index: 2;
            }

            .join-btn-banner {
                background: #ffffff;
                color: var(--primary);
                border: none;
                padding: 12px 30px;
                font-weight: 700;
                border-radius: 50px;
                text-align: center;
                text-decoration: none;
                width: fit-content;
                box-shadow: 0 5px 15px rgba(0,0,0,0.15);
                transition: transform 0.2s, box-shadow 0.2s;
                font-size: 1rem;
                z-index: 2;
                display: inline-block;
            }

            .join-btn-banner:hover {
                 transform: translateY(-2px);
                 box-shadow: 0 8px 20px rgba(0,0,0,0.2);
                 background: #f8f9fa;
            }

            .munchies-banner {
                background: linear-gradient(135deg, #FFD700 0%, #FF9A00 100%);
                height: 250px;
                width: 370px;
                border-radius: 20px;
                padding: 30px;
                display: flex;
                flex-direction: column;
                justify-content: center;
                margin-top: 10px;
            }

            .munchies-banner h3 {
                font-size: 1.8rem;
                margin-bottom: 10px;
                color: #212529;
                font-weight: 700;
            }

            .munchies-banner p {
                color: #5a5a5a;
                margin-bottom: 20px;
                font-weight: 500;
            }

            .browse-btn {
                background: #3C096C;
                color: white;
                border: none;
                padding: 10px 24px;
                font-weight: 600;
                display: inline-flex;
                align-items: center;
                gap: 8px;
                text-decoration: none;
                transition: all 0.3s ease;
                width: fit-content;
            }

            .browse-btn:hover {
                background: #2a064d;
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(60, 9, 108, 0.3);
            }
        }

        .location-tracker {
            cursor: pointer;
        }

        .location-tracker:hover {
            opacity: 0.8;
        }
    </style>
</head>

<body>
    <!-- MOBILE VIEW -->
    <div class="mobile-only">
        <div class="mobile-header navbar-gradient">
            <div class="d-flex align-items-center justify-content-between">
                <a href="{{ route('home') }}" class="brand-mobile">
                    <i class="bi bi-bag-check-fill"></i> GrabBaskets
                </a>
                <div class="d-flex align-items-center">
                    <div class="mobile-icons">
                        <a href="#" class="icon-btn" title="Join With Us">
                            <i class="bi bi-shop"></i>
                        </a>
                        <a href="{{ route('cart.index') }}" class="icon-btn" title="Cart">
                            <i class="bi bi-cart3"></i>
                        </a>
                    </div>
                    @auth
                        <div class="mobile-auth-group">
                            <a href="{{ route('profile.show') }}" class="auth-name"
                                style="font-size: 0.95rem;">{{ Auth::user()->name }}</a>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="mobile-logout-btn" title="Logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="icon-btn" title="Login">
                            <i class="bi bi-person-fill"></i>
                        </a>
                    @endauth
                </div>
            </div>
            <form action="{{ route('products.index') }}" method="GET" class="search-bar-container">
                <input type="text" name="q" class="search-input" placeholder="Search for products...">
                <i class="bi bi-search search-icon"></i>
            </form>
        </div>

        <!-- Mobile Categories Sidebar -->
        <div class="mobile-sidebar-overlay" id="mobileSidebarOverlay" onclick="closeMobileSidebar()"></div>
        <div class="mobile-categories-sidebar" id="mobileCategoriesSidebar">
            <div class="sidebar-header">
                <h3><i class="bi bi-grid-fill me-2"></i>Categories</h3>
                <button class="sidebar-close-btn" onclick="closeMobileSidebar()">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            <div class="sidebar-category-list">
                @foreach(($categories ?? []) as $cat)
                    <a href="{{ route('buyer.productsByCategory', $cat->id ?? 1) }}" class="sidebar-category-item">
                        <div class="sidebar-category-icon">
                            {{ $cat->emoji ?? '📦' }}
                        </div>
                        <div class="sidebar-category-name">{{ $cat->name ?? 'Category' }}</div>
                    </a>
                @endforeach
                @if(count($categories ?? []) == 0)
                    @foreach(['Fruits' => '🍎', 'Veggies' => '🥬', 'Dairy' => '🥛', 'Bakery' => '🍞', 'Munchies' => '🍿', 'Cold Drinks' => '🥤', 'Instant' => '⚡', 'Cleaning' => '🧹', 'Home' => '🏠', 'Beauty' => '💄', 'Pharma' => '💊', 'Pet' => '🐾'] as $name => $emoji)
                        <a href="#" class="sidebar-category-item">
                            <div class="sidebar-category-icon">{{ $emoji }}</div>
                            <div class="sidebar-category-name">{{ $name }}</div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        <main style="padding-bottom: 20px;">
            <div id="mobileBannerCarousel" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="hover" style="margin: 20px 15px 20px;">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#mobileBannerCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                    <button type="button" data-bs-target="#mobileBannerCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    <button type="button" data-bs-target="#mobileBannerCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                </div>
                <div class="carousel-inner" style="border-radius: 24px; overflow: hidden;">
                    <!-- Slide 1: Hiring / Night City / Fireworks -->
                    <div class="carousel-item active">
                        <div class="hero-banner">
                            <div class="firework fw-1"></div>
                            <div class="firework fw-2"></div>
                            <div class="firework fw-3"></div>
                            <div class="firework fw-4"></div>
                            <span class="badge bg-warning text-dark mb-2" style="position:relative; z-index:2;">🚀 Career Opportunity</span>
                            <h2>We Are Hiring: Secure Your Future</h2>
                            <p>Join our elite program! Pay a one-time fee of ₹30,000 and earn a guaranteed ₹15,000 per month.</p>
                            <button class="join-btn-banner mt-2 style-join-btn" onclick="window.location.href='https://forms.gle/68qbSngL12fNT1BB9'">
                                Join Now
                            </button>
                        </div>
                    </div>
                    <!-- Slide 2: Hiring / Dark Team / Fireworks (Duplicate Content) -->
                    <div class="carousel-item">
                         <div class="hero-banner hero-banner-hiring-2">
                            <div class="firework fw-1"></div>
                            <div class="firework fw-2"></div>
                            <div class="firework fw-3"></div>
                            <div class="firework fw-4"></div>
                            <span class="badge bg-warning text-dark mb-2" style="position:relative; z-index:2;">🚀 Career Opportunity</span>
                            <h2>We Are Hiring: Secure Your Future</h2>
                            <p>Join our elite program! Pay a one-time fee of ₹30,000 and earn a guaranteed ₹15,000 per month.</p>
                            <button class="join-btn-banner mt-2 style-join-btn" onclick="window.location.href='https://forms.gle/68qbSngL12fNT1BB9'">
                                Join Now
                            </button>
                        </div>
                    </div>
                    <!-- Slide 3: Partnership -->
                    <div class="carousel-item">
                        <div class="hero-banner hero-banner-alt">
                            <span class="badge bg-light text-primary mb-2" style="position:relative; z-index:2;">🤝 Partnership</span>
                            <h2>Unlock Your Potential: Earn Big</h2>
                            <p>Collaborate with the best in the industry. Meaningful work, guaranteed growth.</p>
                            <button class="join-btn-banner mt-2 style-join-btn" onclick="window.location.href='https://forms.gle/68qbSngL12fNT1BB9'">
                                Join Now
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="category-grid">
                @foreach(($categories ?? []) as $cat)
                    <a href="{{ route('buyer.productsByCategory', $cat->id ?? 1) }}" class="category-item">
                        <div class="cat-icon-box shadow-sm-custom">
                            {{ $cat->emoji ?? '🥬' }}
                        </div>
                        <span class="fs-8 fw-semibold truncate-1">{{ $cat->name ?? 'Category' }}</span>
                    </a>
                @endforeach
                @if(count($categories ?? []) == 0)
                    @foreach(['Fruits', 'Veggies', 'Dairy', 'Bakery', 'Munchies', 'Cold Drinks', 'Instant', 'Cleaning', 'Home', 'Beauty', 'Pharma', 'Pet'] as $dummy)
                        <a href="#" class="category-item">
                            <div class="cat-icon-box shadow-sm-custom">📦</div>
                            <span class="fs-8 fw-semibold truncate-1">{{ $dummy }}</span>
                        </a>
                    @endforeach
                @endif
            </div>
            <div class="product-rail">
                <div class="rail-header">
                    <h5 class="fw-bold mb-0">🔥 Trending Now</h5>
                    <a href="#" class="text-primary text-decoration-none fs-7 fw-bold">See All</a>
                </div>
                <div class="rail-scroll">
                    @foreach(collect($ten_min_products ?? [])->take(6) as $prod)
                        <div class="product-card-mobile"
                            onclick="window.location.href='{{ route('product.details', $prod->id) }}'">
                            <div class="pm-image-box">
                                <img src="{{ $prod->image_url ?? asset('images/no-image.png') }}" alt="{{ $prod->name }}"
                                    class="pm-image" onerror="this.src='{{ asset('images/no-image.png') }}'">
                            </div>
                            <div class="fs-8 text-muted truncate-1">1 unit</div>
                            <div class="fs-7 fw-bold truncate-2 mb-1" style="height: 38px;">{{ $prod->name }}</div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fs-7 fw-bold">₹{{ number_format($prod->price, 0) }}</span>
                                <s class="fs-8 text-muted">₹{{ number_format($prod->price * 1.2, 0) }}</s>
                            </div>
                            <div id="btn-container-{{ $prod->id }}">
                                @auth
                                    @php
                                        $cartItem = \App\Models\CartItem::where('user_id', auth()->id())
                                                    ->where('product_id', $prod->id)
                                                    ->first();
                                        $qty = $cartItem ? $cartItem->quantity : 0;
                                    @endphp
                                    @if($qty > 0)
                                        <div class="qty-control" onclick="event.stopPropagation();">
                                            <button class="qty-btn" onclick="updateCart({{ $prod->id }}, 'decrease')">-</button>
                                            <span class="qty-val">{{ $qty }}</span>
                                            <button class="qty-btn" onclick="updateCart({{ $prod->id }}, 'increase')">+</button>
                                        </div>
                                    @else
                                        <button class="add-btn" onclick="event.stopPropagation(); updateCart({{ $prod->id }}, 'add')">
                                            ADD
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="add-btn" style="text-align:center; text-decoration:none;">
                                        Login
                                    </a>
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="munchies-banner">
                <h3>⚡ Instant Munchies (Nearby)</h3>
                <p>Quick snacks and drinks delivered in minutes!</p>
                <a href="/tenmins" class="browse-btn">Browse <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="product-rail">
                <div class="rail-header">
                    <h5 class="fw-bold mb-0">🥬 Fresh Vegetables</h5>
                    <a href="#" class="text-primary text-decoration-none fs-7 fw-bold">See All</a>
                </div>
                <div class="rail-scroll">
                    @forelse(($products ?? [])->take(6) as $prod)
                        <div class="product-card-mobile"
                            onclick="window.location.href='{{ route('product.details', $prod->id) }}'">
                            <div class="pm-image-box">
                                <img src="{{ $prod->image_url ?? asset('images/no-image.png') }}" alt="{{ $prod->name }}"
                                    class="pm-image" onerror="this.src='{{ asset('images/no-image.png') }}'">
                            </div>
                            <div class="fs-8 text-muted truncate-1">500g</div>
                            <div class="fs-7 fw-bold truncate-2 mb-1" style="height: 38px;">{{ $prod->name }}</div>
                            <div class="d-flex align-items-center justify-content-between">
                                <span class="fs-7 fw-bold">₹{{ number_format($prod->price, 0) }}</span>
                            </div>
                            <div id="btn-container-{{ $prod->id }}">
                                @auth
                                    @php
                                        $cartItem = \App\Models\CartItem::where('user_id', auth()->id())
                                                    ->where('product_id', $prod->id)
                                                    ->first();
                                        $qty = $cartItem ? $cartItem->quantity : 0;
                                    @endphp
                                    @if($qty > 0)
                                        <div class="qty-control" onclick="event.stopPropagation();">
                                            <button class="qty-btn" onclick="updateCart({{ $prod->id }}, 'decrease')">-</button>
                                            <span class="qty-val">{{ $qty }}</span>
                                            <button class="qty-btn" onclick="updateCart({{ $prod->id }}, 'increase')">+</button>
                                        </div>
                                    @else
                                        <button class="add-btn"
                                            onclick="event.stopPropagation(); updateCart({{ $prod->id }}, 'add')">ADD</button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="add-btn" style="text-align:center; text-decoration:none; display:block;">Login</a>
                                @endauth
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-center w-100 text-muted">No items available</div>
                    @endforelse
                </div>
            </div>

            <!-- Food Delivery Section -->
            <div class="product-rail" style="margin-top: 30px;">
                <div class="rail-header">
                    <h5 class="fw-bold mb-0">🍔 Food Delivery</h5>
                    <a href="{{ route('customer.food.index') }}" class="text-primary text-decoration-none fs-7 fw-bold">See All</a>
                </div>
                
                @php
                    $foodItems = \App\Models\FoodItem::with('hotelOwner')
                        ->where('is_available', 1)
                        ->whereNotNull('image')
                        ->where('image', '!=', '')
                        ->whereHas('hotelOwner', function($q) {
                            $now = now();
                            $currentTime = $now->format('H:i:s');
                            $today = strtolower($now->format('l'));
                            $q->where('is_active', true)
                                ->whereRaw("JSON_CONTAINS(operating_days, '" . json_encode($today) . "')")
                                ->where('opening_time', '<=', $currentTime)
                                ->where('closing_time', '>=', $currentTime);
                        })
                        ->latest()
                        ->take(8)
                        ->get();
                @endphp
                
                <div class="rail-scroll">
                    @forelse($foodItems as $food)
                        <div class="product-card-mobile"
                            onclick="window.location.href='{{ route('customer.food.details', $food->id) }}'">
                            <div class="pm-image-box" style="position: relative;">
                                @if($food->first_image_url)
                                    <img src="{{ $food->first_image_url }}" alt="{{ $food->name }}"
                                        class="pm-image">
                                @endif
                                <span style="position: absolute; top: 5px; right: 5px; background: {{ $food->food_type === 'veg' ? '#48C479' : '#D12939' }}; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.65rem; font-weight: 700;">
                                    {{ strtoupper($food->food_type) }}
                                </span>
                            </div>
                            <div class="fs-8 text-muted truncate-1">
                                {{ $food->hotelOwner ? $food->hotelOwner->restaurant_name : 'Restaurant' }}
                            </div>
                            <div class="fs-7 fw-bold truncate-2 mb-1" style="height: 38px;">{{ $food->name }}</div>
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fs-7 fw-bold">₹{{ number_format($food->getFinalPrice(), 0) }}</span>
                                @if($food->rating)
                                    <span class="badge" style="background: #48C479; font-size: 0.65rem;">
                                        ⭐ {{ number_format($food->rating, 1) }}
                                    </span>
                                @endif
                            </div>
                            <div style="margin-top: 8px;">
                                <a href="{{ route('customer.food.details', $food->id) }}" 
                                   class="add-btn" 
                                   onclick="event.stopPropagation();"
                                   style="text-align:center; text-decoration:none; display:block;">
                                    View Details
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-center w-100 text-muted">No food items available right now</div>
                    @endforelse
                </div>
            </div>
        </main>

        <nav class="bottom-nav">
            <a href="{{ route('home') }}" class="nav-link-mobile active">
                <i class="bi bi-house-door-fill"></i>
                <span>Home</span>
            </a>
            <a href="#" class="nav-link-mobile" onclick="event.preventDefault(); openMobileSidebar();">
                <i class="bi bi-grid"></i>
                <span>Categories</span>
            </a>
            <a href="{{ route('cart.index') }}" class="nav-link-mobile position-relative">
                <i class="bi bi-cart3"></i>
                <span id="mobile-cart-badge" class="position-absolute translate-middle badge rounded-pill bg-danger"
                    style="top: 5px; right: 15px; font-size: 0.6rem; display: {{ (\App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') ?? 0) > 0 ? 'inline-block' : 'none' }};">
                    @auth
                        {{ \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') ?? 0 }}
                    @else
                        0
                    @endauth
                </span>
                <span>Cart</span>
            </a>
            @auth
                <a href="{{ route('profile.show') }}" class="nav-link-mobile">
                    <i class="bi bi-person"></i>
                    <span>{{ Auth::user()->name }}</span>
                </a>
            @else
                <a href="{{ route('login') }}" class="nav-link-mobile">
                    <i class="bi bi-person"></i>
                    <span>Login</span>
                </a>
            @endauth
        </nav>
    </div>

    <!-- DESKTOP VIEW -->
    <div class="desktop-only">
        <nav class="desktop-navbar">
            <div class="navbar-container">
                <div class="brand-section">
                    <a href="{{ route('home') }}" class="brand-logo">
                        <i class="bi bi-bag-check-fill"></i> GrabBaskets
                    </a>
                    <div class="delivery-info location-tracker" onclick="getUserLocation()">
                        <div class="fw-bold fs-7">
                            <i class="bi bi-geo-alt-fill"></i>
                            <span id="location-text">Detect your location</span>
                        </div>
                        <div class="text-muted fs-8 truncate-1" id="location-subtext">
                            Click to find your location
                        </div>
                    </div>

                </div>
                <form action="{{ route('products.index') }}" method="GET" class="search-bar-container desktop-search">
                    <input type="text" name="q" class="search-input" placeholder="Search for products, brands and more">
                    <i class="bi bi-search search-icon"></i>
                </form>
                <div class="nav-actions">
                    <button class="join-btn" onclick="window.location.href='/joinus'">
                        <i class="bi bi-shop"></i> Join With Us
                    </button>

                    <a href="{{ route('cart.index') }}" class="cart-btn position-relative">
                        <i class="bi bi-cart3"></i> Cart
                        <span id="desktop-cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                             style="font-size: 0.6rem; display: {{ (\App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') ?? 0) > 0 ? 'inline-block' : 'none' }};">
                            @auth
                                {{ \App\Models\CartItem::where('user_id', auth()->id())->sum('quantity') ?? 0 }}
                            @else
                                0
                            @endauth
                        </span>
                    </a>
                    @auth
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('profile.show') }}" class="auth-name">{{ Auth::user()->name }}</a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="auth-name">
                            <i class="bi bi-person"></i> Login
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        <div class="container main-layout">
            <aside>
                <div class="sidebar-menu shadow-sm-custom">
                    <div class="text-muted fs-8 fw-bold mb-3 px-3 text-uppercase tracking-wider">Categories</div>
                    @foreach(collect($categories ?? [])->take(10) as $cat)
                        <a href="{{ route('buyer.productsByCategory', $cat->id ?? 1) }}" class="side-link">
                            <span class="fs-5">{{ $cat->emoji ?? '📦' }}</span> {{ $cat->name ?? 'Category' }}
                        </a>
                    @endforeach
                    <a href="{{ route('categories.index') }}" class="side-link text-primary mt-2">
                        <i class="bi bi-grid fs-5"></i> View All
                    </a>
                </div>
            </aside>
            <main>
                <div class="row mb-5">
                    <div class="col-8">
                        <div id="desktopBannerCarousel" class="carousel slide mb-4" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="hover">
                             <div class="carousel-indicators">
                                <button type="button" data-bs-target="#desktopBannerCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#desktopBannerCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                                <button type="button" data-bs-target="#desktopBannerCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            </div>
                            <div class="carousel-inner" style="border-radius: 24px; overflow: hidden;">
                                <!-- Slide 1: Hiring / Night City -->
                                <div class="carousel-item active">
                                    <div class="hero-banner">
                                        <div class="firework fw-1"></div>
                                        <div class="firework fw-2"></div>
                                        <div class="firework fw-3"></div>
                                        <div class="firework fw-4"></div>
                                        <span class="badge bg-warning text-dark mb-2" style="position:relative; z-index:2;">🚀 Career Opportunity</span>
                                        <h2>We Are Hiring: Secure Your Future</h2>
                                        <p>Join our elite program! Pay a one-time fee of ₹30,000 and earn a guaranteed ₹15,000 per month.</p>
                                        <button class="join-btn-banner mt-2 style-join-btn" onclick="window.location.href='https://forms.gle/68qbSngL12fNT1BB9'">
                                            Join Now
                                        </button>
                                    </div>
                                </div>
                                <!-- Slide 2: Hiring / Dark Team -->
                                <div class="carousel-item">
                                    <div class="hero-banner hero-banner-hiring-2">
                                        <div class="firework fw-1"></div>
                                        <div class="firework fw-2"></div>
                                        <div class="firework fw-3"></div>
                                        <div class="firework fw-4"></div>
                                        <span class="badge bg-warning text-dark mb-2" style="position:relative; z-index:2;">🚀 Career Opportunity</span>
                                        <h2>We Are Hiring: Secure Your Future</h2>
                                        <p>Join our elite program! Pay a one-time fee of ₹30,000 and earn a guaranteed ₹15,000 per month.</p>
                                        <button class="join-btn-banner mt-2 style-join-btn" onclick="window.location.href='https://forms.gle/68qbSngL12fNT1BB9'">
                                            Join Now
                                        </button>
                                    </div>
                                </div>
                                <!-- Slide 3: Partnership -->
                                <div class="carousel-item">
                                    <div class="hero-banner hero-banner-alt">
                                        <span class="badge bg-light text-primary mb-2" style="position:relative; z-index:2;">🤝 Partnership</span>
                                        <h2>Unlock Your Potential: Earn Big</h2>
                                        <p>Collaborate with the best in the industry. Meaningful work, guaranteed growth.</p>
                                        <button class="join-btn-banner mt-2 style-join-btn" onclick="window.location.href='https://forms.gle/68qbSngL12fNT1BB9'">
                                            Join Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="munchies-banner">
                            <h3>⚡ Instant Munchies (Nearby)</h3>
                            <p>Quick snacks and drinks delivered in minutes!</p>
                            <a href="/tenmins" class="browse-btn">Browse <i class="bi bi-arrow-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold m-0" id="daily">Daily Staples</h3>
                    <a href="#" class="text-primary text-decoration-none fw-bold">see all</a>
                </div>
                <div class="desktop-grid">
                    @forelse(collect($all_products ?? [])->take(8) as $prod)
                        <div class="product-card-desktop"
                            onclick="window.location.href='{{ route('product.details', $prod->id) }}'">
                            <div class="pd-image-box">
                                <img src="{{ $prod->image_url ?? asset('images/no-image.png') }}" class="pd-image"
                                    alt="{{ $prod->name }}" onerror="this.src='{{ asset('images/no-image.png') }}'">
                            </div>
                            <div class="text-muted fs-8 mb-1">1 unit</div>
                            <h6 class="fw-bold truncate-2 mb-3" style="min-height: 40px;">{{ $prod->name }}</h6>
                            <div class="d-flex justify-content-between align-items-end mt-auto">
                                <div>
                                    <div class="text-decoration-line-through text-muted fs-8">
                                        ₹{{ number_format($prod->price * 1.1, 0) }}</div>
                                    <div class="fw-bold fs-5">₹{{ number_format($prod->price, 0) }}</div>
                                </div>
                                <div id="btn-container-{{ $prod->id }}" style="width: 100px;"> <!-- Fixed width wrapper -->
                                    @auth
                                        @php
                                            $cartItem = \App\Models\CartItem::where('user_id', auth()->id())
                                                        ->where('product_id', $prod->id)
                                                        ->first();
                                            $qty = $cartItem ? $cartItem->quantity : 0;
                                        @endphp
                                        @if($qty > 0)
                                            <div class="qty-control" onclick="event.stopPropagation();">
                                                <button class="qty-btn" onclick="updateCart({{ $prod->id }}, 'decrease')">-</button>
                                                <span class="qty-val">{{ $qty }}</span>
                                                <button class="qty-btn" onclick="updateCart({{ $prod->id }}, 'increase')">+</button>
                                            </div>
                                        @else
                                            <button class="btn btn-outline-primary rounded-3 px-3 fw-bold w-100"
                                                onclick="event.stopPropagation(); updateCart({{ $prod->id }}, 'add')">ADD</button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-3 px-3 fw-bold w-100">
                                            Login
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 text-muted bg-white rounded-4">
                            <i class="bi bi-basket display-4 mb-3 d-block"></i>
                            No products found
                        </div>
                    @endforelse
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold m-0">Snacks & Drinks</h3>
                    <a href="#" class="text-primary text-decoration-none fw-bold">see all</a>
                </div>
                <div class="desktop-grid">
                    @foreach(collect($ten_min_products ?? [])->skip(6)->take(4) as $prod)
                        <div class="product-card-desktop"
                            onclick="window.location.href='{{ route('product.details', $prod->id) }}'">
                            <div class="pd-image-box">
                                <img src="{{ $prod->image_url ?? asset('images/no-image.png') }}" class="pd-image"
                                    alt="{{ $prod->name }}" onerror="this.src='{{ asset('images/no-image.png') }}'">
                            </div>
                            <div class="text-muted fs-8 mb-1">Pack</div>
                            <h6 class="fw-bold truncate-2 mb-3" style="min-height: 40px;">{{ $prod->name }}</h6>
                            <div class="d-flex justify-content-between align-items-end mt-auto">
                                <div class="fw-bold fs-5">₹{{ number_format($prod->price, 0) }}</div>
                                <div id="btn-container-{{ $prod->id }}" style="width: 100px;">
                                    @auth
                                        @php
                                            $cartItem = \App\Models\CartItem::where('user_id', auth()->id())
                                                        ->where('product_id', $prod->id)
                                                        ->first();
                                            $qty = $cartItem ? $cartItem->quantity : 0;
                                        @endphp
                                        @if($qty > 0)
                                            <div class="qty-control" onclick="event.stopPropagation();">
                                                <button class="qty-btn" onclick="updateCart({{ $prod->id }}, 'decrease')">-</button>
                                                <span class="qty-val">{{ $qty }}</span>
                                                <button class="qty-btn" onclick="updateCart({{ $prod->id }}, 'increase')">+</button>
                                            </div>
                                        @else
                                            <button class="btn btn-outline-primary rounded-3 px-3 fw-bold w-100"
                                                onclick="event.stopPropagation(); updateCart({{ $prod->id }}, 'add')">ADD</button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-3 px-3 fw-bold w-100">
                                            Login
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/location-delivery.js') }}"></script>
    <script>
        // Update Cart Logic (Handles Add, Increase, Decrease)
        function updateCart(productId, action) {
            @auth
                let quantityChange = 0;
                let currentQty = parseInt(document.querySelector(`#btn-container-${productId} .qty-val`)?.innerText || 0);

                if (action === 'add') {
                    quantityChange = 1;
                    currentQty = 1;
                } else if (action === 'increase') {
                    quantityChange = 1;
                    currentQty++;
                } else if (action === 'decrease') {
                    quantityChange = -1;
                    currentQty--;
                }

                // Optimistic UI Update
                renderProductButton(productId, currentQty);
                
                // Optimistic Badge Update
                 const mobileBadge = document.getElementById('mobile-cart-badge');
                 if (mobileBadge) {
                     let currentCount = parseInt(mobileBadge.innerText || 0);
                     let newCount = currentCount + quantityChange;
                     updateCartBadge(newCount);
                 }

                // Server Request
                fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantityChange // delta
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.cart_count !== undefined) {
                        updateCartBadge(data.cart_count);
                    } else {
                        refreshCartCount(); 
                    }
                })
                .catch(error => console.error('Error:', error));
            @else
                window.location.href = '{{ route('login') }}';
            @endauth
        }

        // Render Button State (Add vs +/-)
        function renderProductButton(productId, qty) {
            const container = document.getElementById(`btn-container-${productId}`);
            if (!container) return;

            if (qty > 0) {
                container.innerHTML = `
                    <div class="qty-control" onclick="event.stopPropagation();">
                        <button class="qty-btn" onclick="updateCart(${productId}, 'decrease')">-</button>
                        <span class="qty-val">${qty}</span>
                        <button class="qty-btn" onclick="updateCart(${productId}, 'increase')">+</button>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <button class="add-btn" onclick="event.stopPropagation(); updateCart(${productId}, 'add')">
                        ADD
                    </button>
                `;
            }
        }

        function updateCartBadge(count) {
             const mobileBadge = document.getElementById('mobile-cart-badge');
             if (mobileBadge) {
                 mobileBadge.innerText = count;
                 mobileBadge.style.display = count > 0 ? 'inline-block' : 'none';
             }
        }

        function refreshCartCount() {
             fetch('{{ route('cart.index') }}');
        }

        // Desktop Backwards Compatibility
        function addToCart(productId) {
            updateCart(productId, 'add');
        }
    </script>

    <script>
        function getUserLocation() {
            if (!navigator.geolocation) {
                alert("Geolocation is not supported by your browser");
                return;
            }

            document.getElementById("location-text").innerText = "Detecting...";
            document.getElementById("location-subtext").innerText = "Please allow location access";

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    // Show coordinates (fast & safe)
                    document.getElementById("location-text").innerText = "Your Location";
                    document.getElementById("location-subtext").innerText =
                        `Lat: ${lat.toFixed(4)}, Lng: ${lng.toFixed(4)}`;

                    // OPTIONAL: Open Google Maps
                    // window.open(`https://www.google.com/maps?q=${lat},${lng}`, "_blank");
                },
                () => {
                    document.getElementById("location-text").innerText = "Location denied";
                    document.getElementById("location-subtext").innerText =
                        "Please enable location access";
                }
            );
        }

        // Mobile Categories Sidebar Functions
        function openMobileSidebar() {
            const sidebar = document.getElementById('mobileCategoriesSidebar');
            const overlay = document.getElementById('mobileSidebarOverlay');
            
            if (sidebar && overlay) {
                sidebar.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('mobileCategoriesSidebar');
            const overlay = document.getElementById('mobileSidebarOverlay');
            
            if (sidebar && overlay) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = ''; // Restore scrolling
            }
        }
    </script>

</body>

</html>