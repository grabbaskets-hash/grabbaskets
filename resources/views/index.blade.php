<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>grabbaskets - Home</title>
  <link rel="icon" type="image/jpeg" href="{{ asset('asset/images/grabbaskets.jpg') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  @if(isset($database_error))
  <script>
    console.error('Database Error: {{ $database_error }}');
  </script>
  @endif
  <!-- Blinkit/Zepto Inspired Modern Design -->
  <style>
    /* ============================================
       RESET & BASE STYLES (Blinkit/Zepto Style)
       ============================================ */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    :root {
      --primary-color: #0C831F;
      --primary-hover: #0A6B19;
      --secondary-color: #F8CB46;
      --text-dark: #1C1C1C;
      --text-light: #666;
      --bg-light: #F7F7F7;
      --bg-white: #FFFFFF;
      --border-color: #E5E5E5;
      --success-color: #0C831F;
      --danger-color: #E74C3C;
      --warning-color: #F39C12;
    }

    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
      background-color: var(--bg-light);
      color: var(--text-dark);
      line-height: 1.6;
      overflow-x: hidden;
    }

    /* ============================================
       ANIMATIONS
       ============================================ */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(30px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes pulse {
      0%, 100% { 
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(12, 131, 31, 0.7);
      }
      50% { 
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(12, 131, 31, 0);
      }
    }

    @keyframes shimmer {
      0% { background-position: -1000px 0; }
      100% { background-position: 1000px 0; }
    }

    /* ============================================
       NAVBAR (Blinkit/Zepto Style)
       ============================================ */
    .navbar-modern {
      background: var(--bg-white);
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      padding: 12px 0;
      position: sticky;
      top: 0;
      z-index: 1000;
      transition: all 0.3s ease;
    }

    .navbar-brand-modern {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--primary-color);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .search-bar-modern {
      flex: 1;
      max-width: 600px;
      position: relative;
    }

    .search-bar-modern input {
      width: 100%;
      padding: 12px 50px 12px 20px;
      border: 2px solid var(--border-color);
      border-radius: 12px;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      background: var(--bg-light);
    }

    .search-bar-modern input:focus {
      outline: none;
      border-color: var(--primary-color);
      background: white;
      box-shadow: 0 4px 12px rgba(12, 131, 31, 0.1);
    }

    .search-bar-modern button {
      position: absolute;
      right: 8px;
      top: 50%;
      transform: translateY(-50%);
      background: var(--primary-color);
      border: none;
      padding: 8px 16px;
      border-radius: 8px;
      color: white;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .search-bar-modern button:hover {
      background: var(--primary-hover);
      transform: translateY(-50%) scale(1.05);
    }

    .nav-icons {
      display: flex;
      gap: 20px;
      align-items: center;
    }

    .nav-icon-btn {
      position: relative;
      background: var(--bg-light);
      border: none;
      padding: 10px 16px;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 0.95rem;
      color: var(--text-dark);
      text-decoration: none;
    }

    .nav-icon-btn:hover {
      background: var(--primary-color);
      color: white;
      transform: translateY(-2px);
    }

    .nav-icon-btn .badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: var(--danger-color);
      color: white;
      border-radius: 10px;
      padding: 2px 6px;
      font-size: 0.7rem;
      font-weight: 700;
    }

    /* ============================================
       DELIVERY BANNER (Prominent)
       ============================================ */
    .delivery-banner-modern {
      background: linear-gradient(135deg, #0C831F 0%, #14A02E 100%);
      padding: 16px 0;
      color: white;
      text-align: center;
      box-shadow: 0 4px 12px rgba(12, 131, 31, 0.2);
    }

    .delivery-banner-modern h3 {
      font-size: 1.3rem;
      font-weight: 700;
      margin-bottom: 4px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
    }

    .delivery-banner-modern p {
      margin: 0;
      font-size: 0.9rem;
      opacity: 0.95;
    }

    /* ============================================
       CATEGORY PILLS (Blinkit Style)
       ============================================ */
    .category-pills-modern {
      background: white;
      padding: 16px 0;
      border-bottom: 1px solid var(--border-color);
      position: sticky;
      top: 72px;
      z-index: 999;
    }

    .category-scroll {
      display: flex;
      gap: 12px;
      overflow-x: auto;
      scrollbar-width: none;
      -ms-overflow-style: none;
      padding: 4px 0;
    }

    .category-scroll::-webkit-scrollbar {
      display: none;
    }

    .category-pill {
      flex-shrink: 0;
      padding: 10px 20px;
      background: var(--bg-light);
      border: 2px solid transparent;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 600;
      color: var(--text-dark);
      cursor: pointer;
      transition: all 0.3s ease;
      white-space: nowrap;
      text-decoration: none;
      display: inline-block;
    }

    .category-pill:hover,
    .category-pill.active {
      background: var(--primary-color);
      color: white;
      border-color: var(--primary-color);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(12, 131, 31, 0.2);
    }

    /* ============================================
       PRODUCT GRID (Zepto Style)
       ============================================ */
    .products-grid-modern {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
      gap: 16px;
      padding: 20px 0;
    }

    @media (max-width: 768px) {
      .products-grid-modern {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
      }
    }

    @media (min-width: 1200px) {
      .products-grid-modern {
        grid-template-columns: repeat(6, 1fr);
      }
    }

    .product-card-modern {
      background: white;
      border-radius: 12px;
      padding: 12px;
      position: relative;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 1px solid var(--border-color);
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .product-card-modern:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(0,0,0,0.12);
      border-color: var(--primary-color);
    }

    .product-image-modern {
      width: 100%;
      aspect-ratio: 1;
      object-fit: contain;
      border-radius: 8px;
      margin-bottom: 10px;
      background: var(--bg-light);
      padding: 8px;
    }

    .product-timer-modern {
      position: absolute;
      top: 12px;
      left: 12px;
      background: rgba(255, 255, 255, 0.95);
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 0.75rem;
      font-weight: 600;
      color: var(--danger-color);
      display: flex;
      align-items: center;
      gap: 4px;
    }

    .product-discount-modern {
      position: absolute;
      top: 12px;
      right: 12px;
      background: var(--success-color);
      color: white;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 0.75rem;
      font-weight: 700;
    }

    .product-title-modern {
      font-size: 0.9rem;
      font-weight: 500;
      color: var(--text-dark);
      margin-bottom: 4px;
      line-height: 1.3;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .product-quantity-modern {
      font-size: 0.8rem;
      color: var(--text-light);
      margin-bottom: 8px;
    }

    .product-price-modern {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 10px;
      margin-top: auto;
    }

    .product-price-modern .current-price {
      font-size: 1.1rem;
      font-weight: 700;
      color: var(--text-dark);
    }

    .product-price-modern .original-price {
      font-size: 0.85rem;
      color: var(--text-light);
      text-decoration: line-through;
    }

    .add-to-cart-modern {
      width: 100%;
      padding: 10px;
      background: white;
      border: 2px solid var(--primary-color);
      border-radius: 8px;
      color: var(--primary-color);
      font-weight: 700;
      font-size: 0.9rem;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }

    .add-to-cart-modern:hover {
      background: var(--primary-color);
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(12, 131, 31, 0.3);
    }

    .add-to-cart-modern.in-cart {
      background: var(--primary-color);
      color: white;
    }

    /* ============================================
       SECTION HEADERS (Modern)
       ============================================ */
    .section-header-modern {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
      padding-bottom: 12px;
      border-bottom: 2px solid var(--border-color);
    }

    .section-header-modern h2 {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--text-dark);
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 0;
    }

    .section-header-modern .view-all {
      color: var(--primary-color);
      font-weight: 600;
      font-size: 0.9rem;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 4px;
      transition: all 0.3s ease;
    }

    .section-header-modern .view-all:hover {
      gap: 8px;
      color: var(--primary-hover);
    }

    /* ============================================
       MOBILE BOTTOM NAV (Blinkit/Zepto Style)
       ============================================ */
    .mobile-bottom-nav {
      display: none;
      position: fixed;
      bottom: 0;
      left: 0;
      right: 0;
      background: white;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.1);
      z-index: 1000;
      padding: 8px 0;
    }

    @media (max-width: 768px) {
      .mobile-bottom-nav {
        display: flex;
        justify-content: space-around;
        align-items: center;
      }

      body {
        padding-bottom: 80px; /* Increased from 70px for better spacing */
      }
    }

    .mobile-nav-item {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 4px;
      padding: 10px 8px; /* Increased padding */
      color: var(--text-light);
      text-decoration: none;
      font-size: 0.75rem;
      transition: all 0.3s ease;
      position: relative;
      cursor: pointer;
    }

    .mobile-nav-item.active,
    .mobile-nav-item:hover {
      color: var(--primary-color);
    }

    .mobile-nav-item i {
      font-size: 1.4rem;
    }

    .mobile-nav-item .badge {
      position: absolute;
      top: 4px;
      right: 20%;
      background: var(--danger-color);
      color: white;
      border-radius: 10px;
      padding: 2px 5px;
      font-size: 0.65rem;
    }

    /* ============================================
       RESPONSIVE UTILITIES
       ============================================ */
    @media (max-width: 768px) {
      .navbar-modern {
        padding: 8px 0;
      }

      .search-bar-modern input {
        padding: 10px 40px 10px 16px;
        font-size: 0.9rem;
      }

      .desktop-only {
        display: none !important;
      }

      .product-card-modern {
        padding: 10px;
      }

      .product-title-modern {
        font-size: 0.85rem;
      }

      .section-header-modern h2 {
        font-size: 1.2rem;
      }
    }

    @media (min-width: 769px) {
      .mobile-only {
        display: none !important;
      }
    }

    /* ============================================
       LOADING SKELETON
       ============================================ */
    .skeleton {
      background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
      background-size: 200% 100%;
      animation: shimmer 1.5s infinite;
      border-radius: 8px;
    }

    .skeleton-text {
      height: 12px;
      margin-bottom: 8px;
    }

    .skeleton-image {
      width: 100%;
      aspect-ratio: 1;
      margin-bottom: 10px;
    }

    /* Legacy styles for compatibility */
    .trending-neon-section {
      position: relative;
      min-height: 700px;
    }

    @keyframes float-particle {
      0%, 100% { transform: translateY(0) translateX(0); opacity: 0.3; }
      50% { transform: translateY(-30px) translateX(20px); opacity: 1; }
    }

    @keyframes neon-pulse {
      0%, 100% { 
        box-shadow: 0 0 20px rgba(0, 255, 255, 0.5), inset 0 0 20px rgba(0, 255, 255, 0.1);
        border-color: #00ffff;
      }
      50% { 
        box-shadow: 0 0 40px rgba(0, 255, 255, 0.8), inset 0 0 30px rgba(0, 255, 255, 0.2);
        border-color: #00dddd;
      }
    }

    @keyframes gradient-shift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    @keyframes rank-glow {
      0%, 100% { box-shadow: 0 0 30px rgba(255, 0, 255, 0.8); }
      50% { box-shadow: 0 0 50px rgba(0, 255, 255, 0.8); }
    }

    @keyframes discount-blink {
      0%, 100% { opacity: 1; box-shadow: 0 0 25px rgba(0, 255, 0, 0.6); }
      50% { opacity: 0.7; box-shadow: 0 0 40px rgba(0, 255, 0, 0.9); }
    }

    @keyframes scan {
      0% { top: 0; }
      100% { top: 100%; }
    }

    .neon-card {
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .neon-card:hover {
      transform: translateY(-15px) scale(1.02);
    }

    .neon-card:hover .card {
      box-shadow: 0 20px 60px rgba(0, 255, 255, 0.4), 0 0 40px rgba(255, 0, 255, 0.3) !important;
      border-color: rgba(0, 255, 255, 0.5) !important;
    }

    .neon-card:hover .neon-product-img {
      transform: scale(1.15);
      filter: brightness(1.1);
    }

    .neon-card:hover .neon-overlay {
      opacity: 1 !important;
    }

    .neon-wishlist:hover {
      background: rgba(255, 0, 255, 0.4) !important;
      transform: scale(1.1);
      box-shadow: 0 0 30px rgba(255, 0, 255, 0.8) !important;
    }

    .neon-btn-primary:hover {
      background: linear-gradient(135deg, rgba(0, 255, 255, 0.4), rgba(255, 0, 255, 0.4)) !important;
      transform: translateY(-2px);
      box-shadow: 0 0 30px rgba(0, 255, 255, 0.6) !important;
    }

    .neon-btn-secondary:hover {
      background: rgba(255, 0, 255, 0.4) !important;
      transform: scale(1.1);
      box-shadow: 0 0 25px rgba(255, 0, 255, 0.6) !important;
    }

    .neon-btn-cta:hover {
      background: linear-gradient(135deg, rgba(0, 255, 255, 0.4), rgba(255, 0, 255, 0.4)) !important;
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 0 60px rgba(0, 255, 255, 0.8), 0 0 80px rgba(255, 0, 255, 0.6) !important;
    }

    .slider-nav:hover {
      background: rgba(0, 255, 255, 0.3) !important;
      transform: translateY(-50%) scale(1.1);
      box-shadow: 0 0 30px rgba(0, 255, 255, 0.6) !important;
    }

    .dropdown-menu {
      backdrop-filter: blur(10px);
    }

    .dropdown-item:hover {
      background: rgba(0, 255, 255, 0.1) !important;
    }

    @media (max-width: 1200px) {
      .slider-nav {
        display: none !important;
      }
    }

    @media (max-width: 768px) {
      .trending-neon-section h2 {
        font-size: 2rem !important;
      }
      
      .neon-img-container {
        height: 240px !important;
      }

      .rank-neon {
        width: 40px !important;
        height: 40px !important;
        font-size: 1rem !important;
      }
    }
    /* Clean Modern Theme Colors */
    :root {
      --primary-blue: #4A90E2;
      --light-blue: #E3F2FD;
      --cream: #FFF8E7;
      --soft-cream: #FFFBF0;
      --accent-blue: #2196F3;
      --dark-blue: #1976D2;
      --text-dark: #2C3E50;
    }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #E3F2FD 0%, #FFF8E7 50%, #E3F2FD 100%);
      background-attachment: fixed;
      overflow-x: hidden;
      position: relative;
    }
    
    /* Subtle Pattern */
    body::before {
      content: '';
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: 
        radial-gradient(circle at 20% 30%, rgba(74, 144, 226, 0.05), transparent),
        radial-gradient(circle at 80% 70%, rgba(33, 150, 243, 0.05), transparent);
      background-size: 100% 100%;
      pointer-events: none;
      z-index: 0;
      opacity: 0.5;
    }
    
    /* Container Positioning */
    body > * {
      position: relative;
      z-index: 1;
    }

    /* Modern Navbar Styling - Clean Theme */
    .navbar {
      background: linear-gradient(135deg, #FFFFFF 0%, #E3F2FD 100%);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 15px rgba(74, 144, 226, 0.1);
      border-bottom: 1px solid rgba(74, 144, 226, 0.2);
      position: sticky;
      top: 0;
      z-index: 1030;
      transition: all 0.3s ease;
    }

    .navbar.scrolled {
      background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(227, 242, 253, 0.98) 100%);
      backdrop-filter: blur(15px);
      box-shadow: 0 4px 20px rgba(74, 144, 226, 0.15);
    }

    .navbar-brand {
      font-weight: 800;
      font-size: 1.8rem;
      background: linear-gradient(45deg, #4A90E2, #2196F3);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: 1px;
      transition: all 0.3s ease;
    }

    .navbar-brand:hover {
      transform: scale(1.05);
      filter: brightness(1.1);
    }

    .nav-link {
      color: #2C3E50 !important;
      font-weight: 600;
      border-radius: 20px;
      padding: 8px 16px !important;
      margin: 0 4px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .nav-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(74, 144, 226, 0.2), transparent);
      transition: left 0.5s ease;
    }

    .nav-link:hover::before {
      left: 100%;
    }

    .nav-link:hover {
      background: rgba(74, 144, 226, 0.15);
      color: #2C3E50 !important;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(74, 144, 226, 0.2);
    }

    .navbar-toggler {
      border: 2px solid #4A90E2;
      border-radius: 10px;
      padding: 6px 10px;
      transition: all 0.3s ease;
    }

    .navbar-toggler:hover {
      background: rgba(74, 144, 226, 0.1);
      transform: scale(1.05);
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%234A90E2' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* Search Bar Enhancement */
    .search-form {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 25px;
      box-shadow: 0 4px 15px rgba(74, 144, 226, 0.1);
    }

    /* Gender Filter Tabs */
    .gender-filter-tabs {
      display: flex;
      gap: 8px;
      margin-bottom: 25px;
      background: rgba(74, 144, 226, 0.05);
      padding: 8px;
      border-radius: 15px;
      justify-content: center;
    }

    .gender-tab {
      flex: 1;
      max-width: 200px;
      padding: 12px 20px;
      background: transparent;
      border: none;
      border-radius: 12px;
      font-size: 14px;
      font-weight: 600;
      color: #8B4513;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .gender-tab::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(45deg, #8B4513, #A0522D);
      transition: left 0.3s ease;
      z-index: -1;
    }

    .gender-tab.active::before,
    .gender-tab:hover::before {
      left: 0;
    }

    .gender-tab.active,
    .gender-tab:hover {
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(139, 69, 19, 0.3);
    }

    /* Categories Grid */
    .mega-categories-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }

    .mega-category-card {
      background: linear-gradient(135deg, #ffffff 0%, #E3F2FD 100%);
      border-radius: 16px;
      padding: 20px;
      border: 1px solid rgba(74, 144, 226, 0.1);
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .mega-category-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 3px;
      background: linear-gradient(45deg, #4A90E2, #2196F3);
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .mega-category-card:hover::before {
      transform: scaleX(1);
    }

    .mega-category-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(74, 144, 226, 0.15);
      border-color: rgba(74, 144, 226, 0.2);
    }

    .mega-category-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 15px;
      padding-bottom: 12px;
      border-bottom: 1px solid rgba(74, 144, 226, 0.1);
    }

    .mega-category-emoji {
      font-size: 24px;
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(45deg, rgba(74, 144, 226, 0.1), rgba(33, 150, 243, 0.1));
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .mega-category-card:hover .mega-category-emoji {
      transform: scale(1.1) rotate(5deg);
      background: linear-gradient(45deg, rgba(74, 144, 226, 0.2), rgba(33, 150, 243, 0.2));
    }

    .mega-category-title {
      font-size: 16px;
      font-weight: 700;
      color: #8B4513;
      margin: 0;
      flex-grow: 1;
    }

    .mega-category-count {
      background: linear-gradient(45deg, #8B4513, #A0522D);
      color: white;
      font-size: 12px;
      padding: 4px 8px;
      border-radius: 12px;
      font-weight: 600;
    }

    .mega-subcategories {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
      gap: 8px;
    }

    .mega-subcategory-link {
      display: block;
      padding: 8px 12px;
      color: #666;
      text-decoration: none;
      border-radius: 8px;
      font-size: 13px;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .mega-subcategory-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(139, 69, 19, 0.1), transparent);
      transition: left 0.4s ease;
    }

    .mega-subcategory-link:hover::before {
      left: 100%;
    }

    .mega-subcategory-link:hover {
      background: rgba(139, 69, 19, 0.08);
      color: #8B4513;
      transform: translateX(5px);
      text-decoration: none;
    }

    /* View All Button */
    .mega-view-all {
      text-align: center;
      padding-top: 20px;
      border-top: 1px solid rgba(139, 69, 19, 0.1);
    }

    .mega-view-all-btn {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      padding: 15px 30px;
      background: linear-gradient(45deg, #8B4513, #A0522D);
      color: white;
      text-decoration: none;
      border-radius: 25px;
      font-weight: 600;
      transition: all 0.3s ease;
      font-size: 14px;
    }

    .mega-view-all-btn:hover {
      background: linear-gradient(45deg, #A0522D, #8B4513);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(139, 69, 19, 0.3);
      color: white;
      text-decoration: none;
    }

    /* Mobile Responsiveness */
    @media (max-width: 1200px) {
      .mega-menu-wrapper {
        width: 98vw;
        max-width: 1200px;
      }

      .mega-categories-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 15px;
      }
    }

    @media (max-width: 992px) {
      .navbar-brand {
        font-size: 1.6rem;
      }

      .mega-menu-wrapper {
        width: 98vw;
        border-radius: 15px;
      }

      .mega-menu-content {
        padding: 20px;
      }

      .mega-categories-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 12px;
      }

      .gender-filter-tabs {
        flex-wrap: wrap;
        gap: 6px;
      }

      .gender-tab {
        max-width: none;
        flex: 1;
        min-width: 120px;
        padding: 10px 16px;
        font-size: 13px;
      }
    }

    @media (max-width: 768px) {
      .navbar {
        padding: 8px 0;
      }

      .navbar-brand {
        font-size: 1.4rem;
      }

      .mega-menu-wrapper {
        position: fixed;
        top: 70px;
        left: 2vw;
        width: 96vw;
        max-height: calc(100vh - 80px);
        overflow-y: auto;
        border-radius: 12px;
      }

      .mega-menu-content {
        padding: 15px;
      }

      .mega-menu-title {
        font-size: 1.4rem;
      }

      .mega-categories-grid {
        grid-template-columns: 1fr;
        gap: 10px;
      }

      .mega-category-card {
        padding: 15px;
      }

      .mega-subcategories {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 6px;
      }

      .gender-filter-tabs {
        grid-template-columns: repeat(2, 1fr);
        display: grid;
        gap: 8px;
      }

      .gender-tab {
        padding: 12px;
        font-size: 12px;
      }

      .search-form {
        margin: 10px 0;
      }
    }

    @media (max-width: 576px) {
      .mega-menu-wrapper {
        left: 1vw;
        width: 98vw;
        top: 65px;
      }

      .mega-categories-grid {
        gap: 8px;
      }

      .mega-category-card {
        padding: 12px;
      }

      .mega-category-emoji {
        width: 35px;
        height: 35px;
        font-size: 20px;
      }

      .mega-category-title {
        font-size: 14px;
      }

      .mega-subcategories {
        grid-template-columns: 1fr;
      }

      .gender-filter-tabs {
        grid-template-columns: 1fr;
      }
    }

    /* Interactive User Greeting Styles */
    .user-greeting-interactive {
      display: flex;
      align-items: center;
      gap: 6px;
      transition: all 0.3s ease;
    }

    .user-greeting-interactive:hover {
      transform: scale(1.05);
    }

    .greeting-emoji {
      font-size: 1.2em;
      transition: all 0.3s ease;
    }

    .user-greeting-interactive:hover .greeting-emoji {
      transform: scale(1.1);
    }

    @keyframes gentle-bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-2px); }
    }

    /* Enhanced Mega Menu Interactivity */
    .mega-subcategory-link {
      position: relative;
      overflow: hidden;
    }

    .mega-subcategory-link:hover {
      background-color: rgba(255, 153, 0, 0.1);
    }

    .mega-category-emoji {
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .mega-category-emoji:hover {
      transform: scale(1.1);
    }

    .hero-section {
      background: linear-gradient(135deg, #f5f5dc 0%, #faebd7 25%, #f5deb3 50%, #daa520 75%, #8B4513 100%);
      color: #2c1810;
      padding: 40px 0 60px 0;
      position: relative;
      overflow: hidden;
    }

    .hero-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><circle cx="20" cy="10" r="1" fill="rgba(139,69,19,0.1)"/><circle cx="40" cy="10" r="1" fill="rgba(139,69,19,0.1)"/><circle cx="60" cy="10" r="1" fill="rgba(139,69,19,0.1)"/><circle cx="80" cy="10" r="1" fill="rgba(139,69,19,0.1)"/></svg>') repeat;
      opacity: 0.3;
    }

    .hero-section h1 {
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      font-weight: 700;
      text-shadow: 0 2px 16px rgba(35, 47, 62, 0.18);
      line-height: 1.2;
    }

    .hero-section p {
      font-size: clamp(1rem, 2.5vw, 1.2rem);
      margin-bottom: 20px;
      opacity: 0.95;
      line-height: 1.4;
    }

    /* Enhanced carousel and banner responsive design */
    .carousel-item {
      min-height: 300px;
      transition: transform 0.6s ease-in-out;
    }

    @media (min-width: 768px) {
      .carousel-item {
        min-height: 400px;
      }
      .hero-section {
        padding: 60px 0 80px 0;
      }
    }

    @media (min-width: 992px) {
      .carousel-item {
        min-height: 450px;
      }
    }

    .search-bar {
      max-width: 700px;
      margin: 30px auto 0 auto;
      /* background: #fff; */
      /* border-radius: 24px; */
      box-shadow: 0 2px 8px rgba(35, 47, 62, 0.08);
      padding: 0px 16px;
      margin-top: -10px;
    }

    .search-bar input {
      border-radius: 18px;
      padding: 10px 20px;
      border: 1px solid #ddd;
      background: #f3f4f6;
      font-size: 1rem;
    }

    .search-bar button {
      border-radius: 18px;
      padding: 10px 20px;
      background: #ff9900;
      color: #232f3e;
      border: none;
      font-weight: 600;
      box-shadow: 0 2px 8px rgba(255, 153, 0, 0.10);
    }

    /* Promo tiles */
    .promo-tiles .tile {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 10px rgba(35, 47, 62, 0.08);
      padding: 18px;
      display: flex;
      gap: 12px;
      align-items: center;
      transition: transform .2s, box-shadow .2s;
    }

    .promo-tiles .tile:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 18px rgba(35, 47, 62, 0.12);
    }

    .promo-tiles .icon {
      width: 44px;
      height: 44px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      background: #f3f4f6;
      color: #232f3e;
    }

    /* Product shelf */
    .shelf {
      background: #fff;
      border-radius: 16px;
      padding: 12px;
      position: relative;
      box-shadow: 0 2px 8px rgba(35, 47, 62, 0.08);
    }

    .shelf-track {
      display: grid;
      grid-auto-flow: column;
      grid-auto-columns: 180px;
      gap: 12px;
      overflow-x: auto;
      padding: 8px 0;
      scroll-snap-type: x mandatory;
    }

    .shelf-track::-webkit-scrollbar {
      height: 8px;
    }

    .shelf-track::-webkit-scrollbar-thumb {
      background: rgba(35, 47, 62, 0.2);
      border-radius: 8px;
    }

    .shelf-item {
      scroll-snap-align: start;
    }

    .shelf .nav-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 36px;
      height: 36px;
      border: none;
      border-radius: 50%;
      background: #232f3e;
      color: #ff9900;
      display: grid;
      place-items: center;
      box-shadow: 0 4px 12px rgba(35, 47, 62, 0.2);
    }

    .shelf .nav-prev {
      left: -10px;
    }

    .shelf .nav-next {
      right: -10px;
    }

    /* Trust badges */
    .trust-badges .badge {
      background: #fff;
      border-radius: 16px;
      padding: 18px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(35, 47, 62, 0.08);
    }

    /* ========================================
       TRENDING SECTION - NEW DESIGN
    ======================================== */
    .trending-section {
      background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
      border-radius: 24px;
      position: relative;
      overflow: hidden;
    }

    .trending-section::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 200px;
      background: linear-gradient(135deg, rgba(255, 107, 0, 0.05) 0%, rgba(255, 215, 0, 0.05) 100%);
      z-index: 0;
    }

    .trending-section .container {
      position: relative;
      z-index: 1;
    }

    /* Trending Badge Animation */
    .trending-badge {
      display: inline-flex;
      align-items: center;
      gap: 10px;
      background: linear-gradient(135deg, #ff6b00 0%, #ff9900 100%);
      padding: 12px 30px;
      border-radius: 50px;
      box-shadow: 0 4px 20px rgba(255, 107, 0, 0.3);
      animation: pulse-trending 2s ease-in-out infinite;
    }

    @keyframes pulse-trending {
      0%, 100% {
        transform: scale(1);
        box-shadow: 0 4px 20px rgba(255, 107, 0, 0.3);
      }
      50% {
        transform: scale(1.05);
        box-shadow: 0 6px 30px rgba(255, 107, 0, 0.5);
      }
    }

    .fire-icon {
      font-size: 1.5rem;
      animation: flicker 1.5s ease-in-out infinite;
    }

    @keyframes flicker {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.8; }
    }

    .trending-text {
      color: white;
      font-weight: 700;
      font-size: 1rem;
      letter-spacing: 2px;
    }

    /* Trending Product Card */
    .trending-product-card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      position: relative;
      height: 100%;
      display: flex;
      flex-direction: column;
    }

    .trending-product-card:hover {
      transform: translateY(-12px);
      box-shadow: 0 12px 40px rgba(255, 107, 0, 0.25);
    }

    .trending-product-card:hover .trending-quick-actions {
      opacity: 1;
      transform: translateY(0);
    }

    .trending-product-card:hover .trending-product-image {
      transform: scale(1.1);
    }

    /* Wishlist Button */
    .wishlist-btn-trending {
      position: absolute;
      top: 15px;
      left: 15px;
      z-index: 10;
      background: rgba(255, 255, 255, 0.95);
      border: none;
      border-radius: 50%;
      width: 42px;
      height: 42px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .wishlist-btn-trending:hover {
      background: #ff6b00;
      transform: scale(1.15) rotate(10deg);
    }

    .wishlist-btn-trending:hover .wishlist-icon-trending {
      color: white;
    }

    .wishlist-icon-trending {
      font-size: 1.2rem;
      color: #ff6b00;
      transition: all 0.3s ease;
    }

    .wishlist-icon-trending.filled {
      color: #e74c3c;
    }

    /* Discount Badge */
    .discount-badge-trending {
      position: absolute;
      top: 15px;
      right: 15px;
      z-index: 10;
      background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
      color: white;
      padding: 8px 12px;
      border-radius: 12px;
      font-weight: 700;
      text-align: center;
      box-shadow: 0 4px 15px rgba(231, 76, 60, 0.4);
      display: flex;
      flex-direction: column;
      line-height: 1.2;
    }

    .discount-badge-trending span {
      font-size: 1.1rem;
    }

    .discount-badge-trending small {
      font-size: 0.7rem;
      opacity: 0.9;
    }

    /* Share Button */
    .share-btn-trending {
      position: absolute;
      top: 65px;
      left: 15px;
      z-index: 10;
    }

    .share-dropdown-btn {
      background: rgba(255, 255, 255, 0.95);
      border: none;
      border-radius: 50%;
      width: 38px;
      height: 38px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      color: #555;
    }

    .share-dropdown-btn:hover {
      background: #232f3e;
      color: white;
      transform: rotate(360deg) scale(1.1);
    }

    /* Image Container with Fixed Aspect Ratio */
    .trending-image-container {
      position: relative;
      width: 100%;
      background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
      overflow: hidden;
    }

    .trending-image-wrapper {
      position: relative;
      width: 100%;
      padding-bottom: 100%; /* 1:1 Aspect Ratio - Perfect Square */
      overflow: hidden;
    }

    .trending-product-image {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: contain; /* Changed from cover to contain for better image display */
      object-position: center;
      transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      padding: 15px; /* Add padding so images don't touch edges */
    }

    .trending-product-image.fallback-image {
      object-fit: contain;
      opacity: 0.5;
    }

    /* Product Link */
    .product-link-trending {
      text-decoration: none;
      color: inherit;
      display: flex;
      flex-direction: column;
      height: 100%;
    }

    .product-link-trending:hover {
      color: inherit;
    }

    /* Product Info */
    .trending-product-info {
      padding: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .trending-product-title {
      font-size: 1rem;
      font-weight: 600;
      color: #232f3e;
      margin-bottom: 12px;
      line-height: 1.4;
      min-height: 44px;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    /* Rating */
    .trending-rating {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .stars-trending {
      display: inline-flex;
      gap: 2px;
    }

    .star-filled {
      color: #ffa500;
      font-size: 0.9rem;
    }

    .star-empty {
      color: #ddd;
      font-size: 0.9rem;
    }

    .review-count {
      font-size: 0.85rem;
      color: #777;
    }

    /* Price Section */
    .trending-price-section {
      display: flex;
      align-items: baseline;
      gap: 10px;
      margin-top: auto;
      margin-bottom: 8px;
    }

    .current-price {
      font-size: 1.5rem;
      font-weight: 700;
      color: #27ae60;
    }

    .original-price {
      font-size: 1rem;
      color: #999;
      text-decoration: line-through;
    }

    .savings-text {
      font-size: 0.85rem;
      color: #e74c3c;
      font-weight: 600;
      margin-bottom: 10px;
    }

    /* Stock Status */
    .stock-status {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 0.85rem;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 20px;
      margin-top: 8px;
    }

    .stock-status.in-stock {
      background: rgba(39, 174, 96, 0.1);
      color: #27ae60;
    }

    .stock-status.out-of-stock {
      background: rgba(231, 76, 60, 0.1);
      color: #e74c3c;
    }

    /* Quick Actions on Hover */
    .trending-quick-actions {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(135deg, #ff6b00 0%, #ff9900 100%);
      color: white;
      text-align: center;
      padding: 15px;
      font-weight: 600;
      opacity: 0;
      transform: translateY(100%);
      transition: all 0.3s ease;
    }

    .quick-view-text {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
      .trending-product-title {
        font-size: 0.95rem;
        min-height: 40px;
      }

      .current-price {
        font-size: 1.3rem;
      }

      .trending-badge {
        padding: 10px 20px;
      }

      .trending-text {
        font-size: 0.85rem;
      }

      .fire-icon {
        font-size: 1.2rem;
      }
    }

    @media (max-width: 576px) {
      .trending-image-wrapper {
        padding-bottom: 100%; /* Keep square on mobile */
      }

      .wishlist-btn-trending,
      .share-dropdown-btn {
        width: 36px;
        height: 36px;
      }

      .discount-badge-trending {
        padding: 6px 10px;
      }

      .discount-badge-trending span {
        font-size: 1rem;
      }
    }

    /* ========================================
       END TRENDING SECTION
    ======================================== */

    .categories-section {
      background: #fff;
      padding: 40px 0;
      border-radius: 24px;
      margin-top: -24px;
      box-shadow: 0 2px 8px rgba(35, 47, 62, 0.08);
    }

    .categories-section h2 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 30px;
      color: #232f3e;
    }

    .category-card {
      background: linear-gradient(135deg, #ffffff 0%, #FFF8E7 100%);
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(74, 144, 226, 0.04);
      transition: box-shadow 0.2s, transform 0.2s;
    }

    .category-card:hover {
      box-shadow: 0 4px 16px rgba(74, 144, 226, 0.18);
      transform: translateY(-4px) scale(1.03);
    }

    .category-card img {
      height: 80px;
      object-fit: cover;
      margin-bottom: 10px;
      border-radius: 16px;
      box-shadow: 0 12px 32px rgba(74, 144, 226, 0.22), 0 2px 0 #fff inset, 0 0 0 4px rgba(74, 144, 226, 0.2);
      transform: perspective(600px) rotateY(-18deg) scale(1.12) rotateX(6deg);
      transition: transform 0.4s cubic-bezier(.25, .8, .25, 1), box-shadow 0.4s;
    }

    .category-card:hover img {
      transform: perspective(600px) rotateY(18deg) scale(1.18) rotateX(-6deg);
      box-shadow: 0 24px 48px rgba(74, 144, 226, 0.28), 0 4px 0 #fff inset, 0 0 0 6px rgba(74, 144, 226, 0.4);
    }

    .products-section {
      padding: 40px 0;
    }

    .products-section h2 {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 30px;
      color: #232f3e;
    }

    .product-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(74, 144, 226, 0.04);
      transition: box-shadow 0.2s, transform 0.2s;
      background: linear-gradient(135deg, #ffffff 0%, #E3F2FD 100%);
    }

    .product-card:hover {
      box-shadow: 0 4px 16px rgba(74, 144, 226, 0.18);
      transform: translateY(-4px) scale(1.03);
    }

    .product-card img {
      height: 180px;
      object-fit: cover;
      border-radius: 24px 24px 0 0;
      box-shadow: 0 16px 40px rgba(74, 144, 226, 0.18), 0 0 0 4px rgba(74, 144, 226, 0.4);
      transform: perspective(800px) rotateY(-10deg) scale(1.08) rotateX(4deg);
      transition: transform 0.4s cubic-bezier(.25, .8, .25, 1), box-shadow 0.4s;
      transform: perspective(800px) rotateY(10deg) scale(1.13) rotateX(-4deg);
      box-shadow: 0 32px 64px rgba(74, 144, 226, 0.22), 0 0 0 8px rgba(74, 144, 226, 0.5);
    }

    .product-card .card-title {
      font-size: 1.1rem;
      font-weight: 600;
      color: #232f3e;
    }

    .product-card .card-text {
      color: #555;
      font-size: 0.95rem;
    }

    .product-card .btn {
      border-radius: 18px;
      font-weight: 600;
      background: #ff9900;
      color: #232f3e;
      border: none;
      box-shadow: 0 2px 8px rgba(255, 153, 0, 0.10);
    }

    .product-card .btn:hover {
      background: #232f3e;
      color: #ff9900;
    }

    /* Wishlist Heart Button */
    .wishlist-heart-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 10;
      background: rgba(255, 255, 255, 0.9);
      border: none;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .wishlist-heart-btn:hover {
      background: #fff;
      transform: scale(1.1);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .wishlist-icon {
      color: #ccc;
      font-size: 1.25rem;
      transition: all 0.2s;
    }

    .wishlist-heart-btn:hover .wishlist-icon {
      color: #e74c3c !important;
      transform: scale(1.1);
    }

    .wishlist-icon.bi-heart-fill {
      color: #e74c3c !important;
    }

    footer {
      background: #232f3e;
      color: red;
      padding: 40px 0 20px 0;
      border-radius: 16px 16px 0 0;
      box-shadow: 0 -2px 8px rgba(35, 47, 62, 0.10);
    }

    footer a {
      color: #ff9900;
      text-decoration: none;
    }

    footer a:hover {
      color: #232f3e;
    }

    /* Mega Menu Styling - Zepto Style */
    .mega-menu {
      width: 95vw;
      max-width: 1200px;
      left: 50%;
      transform: translateX(-50%);
      border: none;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
      border-radius: 12px;
      padding: 24px;
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      backdrop-filter: blur(10px);
    }

    .mega-menu .category-section {
      margin-bottom: 20px;
      padding: 16px;
      background: #fff;
      border-radius: 8px;
      border-left: 4px solid #8B4513;
      box-shadow: 0 2px 8px rgba(139, 69, 19, 0.1);
      transition: all 0.3s ease;
      height: auto;
      min-height: 200px;
      display: flex;
      flex-direction: column;
    }

    .mega-menu .category-section:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(139, 69, 19, 0.2);
    }

    .mega-menu .category-title {
      font-size: 1rem;
      font-weight: 700;
      color: #8B4513;
      margin-bottom: 12px;
      padding-bottom: 8px;
      border-bottom: 2px solid #f5f5dc;
      display: flex;
      align-items: center;
      gap: 8px;
      flex-shrink: 0;
    }

    .mega-menu .category-icon {
      width: 24px;
      height: 24px;
      background: linear-gradient(45deg, #8B4513, #A0522D);
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 12px;
    }

    .mega-menu .subcategory-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
      gap: 6px;
      flex-grow: 1;
      align-content: start;
    }

    .mega-menu .dropdown-item {
      font-size: 12px;
      padding: 6px 10px;
      color: #6c757d;
      border-radius: 6px;
      transition: all 0.2s ease;
      position: relative;
      overflow: hidden;
      text-align: left;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .mega-menu .dropdown-item::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(139, 69, 19, 0.1), transparent);
      transition: left 0.3s ease;
    }

    .mega-menu .dropdown-item:hover::before {
      left: 100%;
    }

    .mega-menu .dropdown-item:hover {
      color: #8B4513;
      background: rgba(139, 69, 19, 0.08);
      transform: translateX(4px);
    }

    .mega-menu .gender-tabs {
      display: flex;
      gap: 4px;
      margin-bottom: 20px;
      background: #f8f9fa;
      padding: 4px;
      border-radius: 8px;
    }

    .mega-menu .gender-tab {
      flex: 1;
      padding: 8px 16px;
      background: transparent;
      border: none;
      border-radius: 6px;
      font-size: 13px;
      font-weight: 600;
      color: #6c757d;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .mega-menu .gender-tab.active,
    .mega-menu .gender-tab:hover {
      background: #8B4513;
      color: white;
      transform: translateY(-1px);
    }

    .mega-menu .view-all-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 10px 20px;
      background: linear-gradient(45deg, #8B4513, #A0522D);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      transition: all 0.3s ease;
      margin-top: 16px;
    }

    .mega-menu .view-all-btn:hover {
      background: linear-gradient(45deg, #A0522D, #8B4513);
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(139, 69, 19, 0.3);
      color: white;
    }

    /* Categories Badge */
    .category-badge {
      background: linear-gradient(45deg, #8B4513, #A0522D);
      color: white;
      font-size: 10px;
      padding: 2px 6px;
      border-radius: 12px;
      margin-left: auto;
    }

    .navbar .dropdown-menu.mega-menu {
      position: fixed;
      top: 70px;
      left: 50%;
      transform: translateX(-50%);
      width: 95vw;
      max-width: 1200px;
      z-index: 1050;
    }

    /* Responsive Design for Mega Menu */
    @media (max-width: 1200px) {
      .mega-menu {
        width: 98vw;
        max-width: 1000px;
        padding: 20px;
      }
    }

    @media (max-width: 992px) {
      .mega-menu {
        width: 98vw;
        padding: 16px;
      }
      
      .mega-menu .category-section {
        margin-bottom: 16px;
        min-height: 180px;
      }
      
      .mega-menu .subcategory-grid {
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 4px;
      }
    }

    @media (max-width: 768px) {
      .mega-menu {
        width: 98vw;
        padding: 15px;
      }
      
      .mega-menu .category-section {
        margin-bottom: 16px;
        min-height: 160px;
      }
      
      .mega-menu .subcategory-grid {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 4px;
      }
      
      .mega-menu .gender-tabs {
        flex-direction: column;
        gap: 2px;
      }
      
      .mega-menu .gender-tab {
        text-align: center;
        padding: 6px 12px;
        font-size: 12px;
      }
    }

    @media (max-width: 576px) {
      .mega-menu .subcategory-grid {
        grid-template-columns: 1fr;
      }
      
      .mega-menu {
        top: 60px;
      }

      .mega-menu .category-section {
        min-height: 140px;
      }
    }

    /* --------------------------- */

    .categories {
      text-align: center;
      padding: 50px 20px;
    }

    .categories h2 {
      font-size: 22px;
      margin-bottom: 10px;
      align-items: center;
    }

    .categories .items {
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
      margin-top: 20px;
    }

    .categories .items div {
      text-align: center;
      width: 100px;
    }

    .categories .items img {
      width: 80px;
      height: 80px;
      object-fit: cover;
      border-radius: 6px;
    }

    /* About Section */
    .about {
      text-align: center;
      padding: 40px 20px;
      background: #fafafa;
    }

    .about p {
      max-width: 600px;
      margin: auto;
      font-size: 14px;
      line-height: 1.6;
    }

    .about button {
      margin-top: 20px;
      padding: 8px 20px;
      border: 1px solid #333;
      background: none;
      cursor: pointer;
    }


    .grid {
      display: flex;
      gap: 20px;
      overflow-x: auto;
      padding: 10px 0;
      scroll-behavior: smooth;
    }

    .item .image-box {
      width: 245px;
      /* fixed width */
      height: 180px;
      /* fixed height */
      margin: 0 auto;
      overflow: hidden;
      border-radius: 8px;
      background: #f9f9f9;
      /* fallback bg */
    }

    .item .image-box img {
      width: 90%;
      height: 100%;
      object-fit: contain;
      /* crops and keeps ratio */
      display: block;
    }

    .carousel-img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 10px;
    }

    /* Enhanced Carousel Indicators */
    .carousel-indicators {
      bottom: 20px;
    }

    .carousel-indicators [data-bs-target] {
      width: 12px;
      height: 12px;
      border-radius: 50%;
      background-color: rgba(255,255,255,0.5);
      border: 2px solid rgba(255,255,255,0.8);
      transition: all 0.3s ease;
    }

    .carousel-indicators .active {
      background-color: #ff9900;
      border-color: #ff9900;
      transform: scale(1.2);
    }

    /* Enhanced Carousel Controls */
    .carousel-control-prev,
    .carousel-control-next {
      width: 5%;
      opacity: 0.8;
      transition: opacity 0.3s ease;
    }

    .carousel-control-prev:hover,
    .carousel-control-next:hover {
      opacity: 1;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
      background-size: 20px 20px;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
    }

    @media (max-width: 768px) {
      .carousel-control-prev,
      .carousel-control-next {
        width: 8%;
      }
      
      .carousel-indicators {
        bottom: 10px;
      }
      
      .carousel-indicators [data-bs-target] {
        width: 10px;
        height: 10px;
      }
    }

    /* Additional carousel image enhancements */
    .carousel-img:hover {
      object-position: center;
      border-radius: 24px;
      box-shadow: 0 16px 48px #232f3e44, 0 0 0 8px #ff9900cc;
      transform: perspective(900px) rotateY(-12deg) scale(1.08) rotateX(6deg);
      transition: transform 0.4s, box-shadow 0.4s;
    }




    /* .navbar{
        background: #232f3e;

}
.nav-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 60px; 
  gap: 40px;
}


.logo {
  font-size: 1.6rem;
  font-weight: bold;
  color: #ff9900;
  text-decoration: none;
  white-space: nowrap;
  margin-left: -30%;
 
}


.nav-center {
  flex: 2;
  display: flex;
  justify-content: center;
}

.search-box {
  display: flex;
  width: 100%;
  max-width: 600px;
  margin-left: 60%;
}


.nav-right {
  display: flex;
  align-items:flex-end;
  gap: 20px;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 20px;
  margin: 0;
  padding: 0;
   color: #fff !important;

}
li{
  
}
li a{
  text-decoration: none;

}*/
    .navbar {
      background: linear-gradient(135deg, #f5f5dc 0%, #faf8f3 50%, #f5f5dc 100%);
      box-shadow: 0 4px 12px rgba(139, 69, 19, 0.15);
      border-bottom: 3px solid rgba(139, 69, 19, 0.1);
      backdrop-filter: blur(10px);
      position: sticky;
      top: 0;
      z-index: 1040;
    }

    .navbar-brand {
      font-weight: bold;
      font-size: 2rem;
      color: #8B4513;
      letter-spacing: 2px;
      text-shadow: 0 2px 8px rgba(139, 69, 19, 0.12);
      transition: all 0.3s ease;
    }

    .navbar-brand:hover {
      color: #654321;
      transform: scale(1.02);
      text-shadow: 0 4px 12px rgba(139, 69, 19, 0.2);
    }

    .nav-link {
      color: #8B4513 !important;
      font-weight: 500;
      border-radius: 12px;
      transition: all 0.3s ease;
      padding: 8px 16px !important;
      margin: 0 4px;
      position: relative;
      overflow: hidden;
    }

    .nav-link::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(139, 69, 19, 0.1), transparent);
      transition: left 0.3s ease;
    }

    .nav-link:hover::before {
      left: 100%;
    }

    .nav-link:hover {
      background: rgba(139, 69, 19, 0.1);
      color: #654321 !important;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(139, 69, 19, 0.15);
    }

    .navbar-toggler {
      border: 2px solid #8B4513;
      border-radius: 8px;
      padding: 4px 8px;
    }

    .navbar-toggler:focus {
      box-shadow: 0 0 0 0.2rem rgba(139, 69, 19, 0.25);
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%238B4513' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* Navbar Dropdown Styling */
    .navbar .dropdown-menu:not(.mega-menu) {
      background: linear-gradient(135deg, #f5f5dc 0%, #faf8f3 100%);
      border: 2px solid rgba(139, 69, 19, 0.1);
      border-radius: 12px;
      box-shadow: 0 8px 24px rgba(139, 69, 19, 0.15);
      padding: 8px 0;
      min-width: 200px;
    }

    .navbar .dropdown-item {
      color: #8B4513;
      padding: 8px 20px;
      font-weight: 500;
      transition: all 0.2s ease;
      border-radius: 8px;
      margin: 2px 8px;
    }

    .navbar .dropdown-item:hover {
      background: rgba(139, 69, 19, 0.1);
      color: #654321;
      transform: translateX(4px);
    }

    .navbar .dropdown-divider {
      border-color: rgba(139, 69, 19, 0.2);
      margin: 8px 16px;
    }

    /* Navbar User Greeting */
    .nav-link:has(.user-greeting) {
      background: rgba(74, 144, 226, 0.05);
      border: 1px solid rgba(74, 144, 226, 0.1);
    }

    /* Modern Clean Banner Styles */
    .modern-clean-banner {
      border-radius: 0;
      min-height: 400px;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 40px 20px;
    }

    /* Subtle overlay pattern */
    .modern-clean-banner::before {
      content: "";
      position: absolute;
      inset: 0;
      background-image: 
        radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.1) 0%, transparent 50%);
      z-index: 1;
    }

    /* Ensure content stays above overlay */
    .modern-clean-banner > * {
      position: relative;
      z-index: 2;
    }

    /* Enhanced Responsive layout */
    @media (max-width: 576px) {
      .modern-clean-banner {
        min-height: 300px !important;
        padding: 30px 15px !important;
      }

      .modern-clean-banner h1 {
        font-size: 1.75rem !important;
        margin-bottom: 15px !important;
      }

      .modern-clean-banner p {
        font-size: 1rem !important;
        margin-bottom: 20px !important;
      }

      .modern-clean-banner .btn {
        font-size: 0.95rem !important;
        padding: 10px 25px !important;
      }
    }

    @media (min-width: 577px) and (max-width: 768px) {
      .modern-clean-banner {
        min-height: 350px !important;
        padding: 35px 25px !important;
      }

      .modern-clean-banner h1 {
        font-size: 2.25rem !important;
      }

      .modern-clean-banner p {
        font-size: 1.1rem !important;
      }
    }

    @media (min-width: 769px) {
      .modern-clean-banner {
        min-height: 400px;
        padding: 40px;
      }
    }

    @media (min-width: 992px) {
      .modern-clean-banner {
        min-height: 450px;
      }
    }

    /* Product Banner Content */
    .product-banner-content {
      min-height: 400px;
      display: flex;
      align-items: center;
      padding: 40px 20px;
    }

    @media (max-width: 768px) {
      .product-banner-content {
        min-height: 350px;
        padding: 30px 15px;
      }
    }

    /* Optional: Add subtle fireworks motion overlay */
    .diwali-theme-banner-2::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle, rgba(255, 140, 0, 0.5) 1.5px, transparent 3px) 0 0 / 60px 60px;
      animation: sparkleMove 14s linear infinite;
      opacity: 0.5;
      z-index: 2;
    }

    /* Enhanced Product Banner Responsive Design */
    .product-banner-content {
      min-height: 300px;
      padding: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      text-align: center;
    }

    @media (min-width: 768px) {
      .product-banner-content {
        min-height: 350px;
        padding: 40px;
        text-align: left;
        justify-content: flex-start;
      }
    }

    @media (min-width: 992px) {
      .product-banner-content {
        min-height: 400px;
        padding: 60px;
      }
    }

    /* Mobile-first button styling */
    .banner-buttons {
      display: flex;
      flex-direction: column;
      gap: 10px;
      align-items: center;
    }

    @media (min-width: 576px) {
      .banner-buttons {
        flex-direction: row;
        justify-content: center;
      }
    }

    @media (min-width: 768px) {
      .banner-buttons {
        justify-content: flex-start;
      }
    }

    .banner-buttons .btn {
      min-width: 120px;
      font-weight: 600;
      border-radius: 25px;
      transition: all 0.3s ease;
    }

    .banner-buttons .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    /* Zepto-like category strip below banner */
    .zepto-cat-section {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 2px 12px rgba(35, 47, 62, 0.08);
      margin-top: -16px;
      padding: 14px 8px 6px;
      position: relative;
      z-index: 2;
    }

    .zepto-cat-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 8px 8px;
    }

    .zepto-cat-header h2 {
      font-size: 1.05rem;
      font-weight: 700;
      color: #232f3e;
      margin: 0;
    }

    .zepto-cat-track {
      display: grid;
      grid-auto-flow: column;
      grid-auto-columns: 86px;
      gap: 10px;
      overflow-x: auto;
      padding: 6px 36px 10px; /* leave room for nav buttons */
      scroll-snap-type: x proximity;
    }

    .zepto-cat-track::-webkit-scrollbar { height: 8px; }
    .zepto-cat-track::-webkit-scrollbar-thumb { background: rgba(35,47,62,0.18); border-radius: 8px; }

    .zepto-cat-item { scroll-snap-align: start; text-align: center; }

    .zepto-cat-link {
      display: inline-flex;
      flex-direction: column;
      align-items: center;
      text-decoration: none;
      color: #232f3e;
      gap: 6px;
    }

    .zepto-cat-icon {
      width: 64px;
      height: 64px;
      border-radius: 50%;
      background: linear-gradient(135deg,#fafafa,#fff);
      border: 1px solid rgba(139,69,19,0.12);
      display: grid;
      place-items: center;
      box-shadow: 0 4px 14px rgba(35,47,62,0.08);
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      overflow: hidden;
    }

    .zepto-cat-icon img { width: 100%; height: 100%; object-fit: cover; }
    .zepto-cat-emoji { font-size: 1.4rem; }

    .zepto-cat-link:hover .zepto-cat-icon { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(35,47,62,0.16); }

    .zepto-cat-name {
      font-size: 0.78rem;
      font-weight: 600;
      color: #2c3e50;
      line-height: 1.1;
      max-width: 80px;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .zepto-cat-nav {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 32px;
      height: 32px;
      border-radius: 50%;
      border: none;
      display: grid;
      place-items: center;
      background: #232f3e;
      color: #ff9900;
      box-shadow: 0 4px 12px rgba(35,47,62,0.25);
      z-index: 3;
    }
    .zepto-cat-prev { left: 8px; }
    .zepto-cat-next { right: 8px; }

    @media (min-width: 768px) {
      .zepto-cat-section { margin-top: -24px; padding: 18px 10px 8px; }
      .zepto-cat-track { grid-auto-columns: 96px; gap: 12px; }
      .zepto-cat-icon { width: 72px; height: 72px; }
      .zepto-cat-name { font-size: 0.82rem; max-width: 92px; }
    }

    /* ============ BLINKIT-STYLE MOBILE 3×3 GRID ============ */
    @media (max-width: 767px) {
      /* Hide desktop scrolling navigation */
      .zepto-cat-nav {
        display: none !important;
      }

      /* Convert horizontal scroll to 3×3 grid */
      .zepto-cat-track {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-auto-flow: row;
        grid-auto-columns: auto;
        gap: 16px;
        overflow-x: visible;
        overflow-y: visible;
        padding: 12px 16px 16px;
        scroll-snap-type: none;
      }

      /* Show only first 9 items in mobile grid */
      .zepto-cat-item:nth-child(n+10) {
        display: none;
      }

      /* Center items in grid cells */
      .zepto-cat-item {
        scroll-snap-align: none;
        text-align: center;
        display: flex;
        justify-content: center;
      }

      /* Mobile category link styling */
      .zepto-cat-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #232f3e;
        gap: 8px;
        width: 100%;
      }

      /* Larger icons for mobile Blinkit style */
      .zepto-cat-icon {
        width: 70px;
        height: 70px;
        border-radius: 16px; /* Rounded square like Blinkit */
        background: linear-gradient(135deg, #fff 0%, #fafafa 100%);
        border: 2px solid rgba(139, 69, 19, 0.12);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: all 0.2s ease;
      }

      /* Enhanced hover effect for mobile */
      .zepto-cat-link:active .zepto-cat-icon {
        transform: scale(0.95);
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.12);
      }

      /* Category name - more readable on mobile */
      .zepto-cat-name {
        font-size: 0.75rem;
        font-weight: 600;
        color: #333;
        line-height: 1.2;
        max-width: 100%;
        white-space: normal;
        overflow: visible;
        text-overflow: clip;
        text-align: center;
        word-break: break-word;
        max-height: 32px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }

      /* Emoji larger on mobile */
      .zepto-cat-emoji {
        font-size: 1.8rem;
      }

      /* Mobile header adjustments */
      .zepto-cat-header {
        padding: 0 16px 12px;
      }

      .zepto-cat-header h2 {
        font-size: 1.1rem;
        font-weight: 700;
      }

      /* View all link styling */
      .zepto-cat-header a {
        font-size: 0.85rem;
        font-weight: 600;
      }

      /* Add "View All" button at bottom for mobile */
      .zepto-cat-section::after {
        content: '';
        display: block;
        height: 0;
      }
    }

    /* Tablet optimization (768px - 1024px) */
    @media (min-width: 768px) and (max-width: 1024px) {
      .zepto-cat-track {
        grid-auto-columns: 90px;
        gap: 14px;
      }

      .zepto-cat-icon {
        width: 68px;
        height: 68px;
      }
    }

    /* ============================================
       LOCATION MODAL (Zepto/Blinkit Style)
       ============================================ */
    .location-modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      z-index: 9998;
      display: none;
      animation: fadeIn 0.3s ease;
    }

    .location-modal {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: white;
      border-radius: 16px;
      width: 90%;
      max-width: 480px;
      max-height: 90vh;
      overflow-y: auto;
      z-index: 9999;
      display: none;
      animation: slideInUp 0.3s ease;
      box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    }

    .location-modal.active {
      display: block;
    }

    .location-modal-overlay.active {
      display: block;
    }

    .location-modal-header {
      padding: 20px;
      border-bottom: 1px solid var(--border-color);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .location-modal-header h3 {
      font-size: 1.25rem;
      font-weight: 600;
      margin: 0;
    }

    .location-modal-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      color: var(--text-light);
      cursor: pointer;
      padding: 0;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: all 0.2s;
    }

    .location-modal-close:hover {
      background: var(--bg-light);
      color: var(--text-dark);
    }

    .location-modal-body {
      padding: 20px;
    }

    .location-detect-btn {
      width: 100%;
      padding: 16px;
      background: linear-gradient(135deg, #0C831F 0%, #0A6B19 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-weight: 600;
      font-size: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      cursor: pointer;
      transition: all 0.3s;
      margin-bottom: 16px;
    }

    .location-detect-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(12, 131, 31, 0.3);
    }

    .location-detect-btn:active {
      transform: translateY(0);
    }

    .location-detect-btn i {
      font-size: 1.2rem;
      animation: pulse 2s infinite;
    }

    .location-divider {
      text-align: center;
      position: relative;
      margin: 20px 0;
    }

    .location-divider::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 1px;
      background: var(--border-color);
    }

    .location-divider span {
      background: white;
      padding: 0 16px;
      position: relative;
      color: var(--text-light);
      font-size: 0.875rem;
    }

    .location-search-input {
      width: 100%;
      padding: 14px 16px 14px 44px;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.2s;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%23666' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: 16px center;
    }

    .location-search-input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(12, 131, 31, 0.1);
    }

    .location-loading {
      text-align: center;
      padding: 20px;
      color: var(--text-light);
    }

    .location-loading i {
      font-size: 2rem;
      color: var(--primary-color);
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translate(-50%, -40%);
      }
      to {
        opacity: 1;
        transform: translate(-50%, -50%);
      }
    }

    .current-location-display {
      padding: 12px 16px;
      background: var(--bg-light);
      border-radius: 8px;
      margin-top: 16px;
      display: none;
    }

    .current-location-display.active {
      display: block;
    }

    .current-location-display .location-icon {
      color: var(--primary-color);
      font-size: 1.1rem;
      margin-right: 8px;
    }

    .current-location-text {
      font-size: 0.875rem;
      color: var(--text-light);
      margin-bottom: 4px;
    }

    .current-location-address {
      font-size: 1rem;
      font-weight: 600;
      color: var(--text-dark);
    }

    .location-accuracy {
      display: inline-block;
      padding: 4px 8px;
      background: rgba(12, 131, 31, 0.1);
      color: var(--primary-color);
      border-radius: 4px;
      font-size: 0.75rem;
      margin-top: 8px;
    }

    /* ============================================
       MOBILE LOCATION BAR
       ============================================ */
    .mobile-location-bar {
      display: none;
      background: linear-gradient(135deg, #0C831F 0%, #0A6B19 100%);
      color: white;
      padding: 12px 16px;
      position: sticky;
      top: 0;
      z-index: 999;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
      .mobile-location-bar {
        display: block;
      }

      .mobile-location-bar.below-nav {
        position: relative;
        top: 0;
      }
    }

    .mobile-location-content {
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
    }

    .mobile-location-icon {
      font-size: 1.5rem;
      animation: pulse 2s infinite;
    }

    .mobile-location-text {
      flex: 1;
    }

    .mobile-location-label {
      font-size: 0.75rem;
      opacity: 0.9;
      margin-bottom: 2px;
    }

    .mobile-location-address {
      font-size: 0.95rem;
      font-weight: 600;
    }

    /* ============================================
       MOBILE LOGIN CARD
       ============================================ */
    .mobile-login-card {
      background: white;
      border-radius: 16px;
      padding: 24px;
      margin: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
      animation: slideInUp 0.3s ease;
      display: none;
    }

    @media (max-width: 768px) {
      .mobile-login-card.show {
        display: block;
      }
    }

    .mobile-login-card h3 {
      color: var(--primary-color);
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 8px;
    }

    .mobile-login-card p {
      color: var(--text-light);
      font-size: 0.9rem;
      margin-bottom: 20px;
    }

    .mobile-login-form {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .mobile-login-input {
      width: 100%;
      padding: 14px 16px;
      border: 1.5px solid var(--border-color);
      border-radius: 10px;
      font-size: 1rem;
      transition: all 0.2s;
    }

    .mobile-login-input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(12, 131, 31, 0.1);
    }

    .mobile-login-btn {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #0C831F 0%, #0A6B19 100%);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
    }

    .mobile-login-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(12, 131, 31, 0.3);
    }

    .mobile-login-divider {
      text-align: center;
      position: relative;
      margin: 20px 0;
    }

    .mobile-login-divider::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 1px;
      background: var(--border-color);
    }

    .mobile-login-divider span {
      background: white;
      padding: 0 16px;
      position: relative;
      color: var(--text-light);
      font-size: 0.875rem;
    }

    .mobile-login-footer {
      text-align: center;
      margin-top: 16px;
      font-size: 0.9rem;
      color: var(--text-light);
    }

    .mobile-login-footer a {
      color: var(--primary-color);
      font-weight: 600;
      text-decoration: none;
    }

    .mobile-login-close {
      position: absolute;
      top: 16px;
      right: 16px;
      background: var(--bg-light);
      border: none;
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      font-size: 1.2rem;
      color: var(--text-light);
    }

    /* ============================================
       HIDE BANNER ON MOBILE
       ============================================ */
    @media (max-width: 768px) {
      #heroCarousel {
        display: none;
      }

      .mobile-location-section {
        display: block;
        padding-top: 8px;
      }
    }

    @media (min-width: 769px) {
      .mobile-location-section {
        display: none;
      }
    }
  </style>

  <!-- Google Maps API -->
  <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_api_key') }}&libraries=places" async defer></script>
</head>

<body>
  {{-- @if(session('success'))
  <div class="alert alert-success text-center mt-3 mb-0" role="alert">
    {{ session('success') }}
  </div>
  @endif --}}
  <!-- Modern Enhanced Navbar -->

  <!-- Blinkit/Zepto Style Navigation -->
  <nav class="navbar-modern">
    <div class="container">
      <div class="d-flex align-items-center gap-3 w-100">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="navbar-brand-modern">
          <i class="bi bi-bag-check-fill"></i>
          <span class="desktop-only">GrabBaskets</span>
          <span class="mobile-only">GB</span>
        </a>

        <!-- Delivery Location (Desktop) -->
        <div class="desktop-only" 
             id="locationDisplay"
             onclick="openLocationModal()"
             style="display: flex; align-items: center; gap: 4px; padding: 8px 12px; background: var(--bg-light); border-radius: 8px; cursor: pointer; min-width: 150px; transition: all 0.2s;">
          <i class="bi bi-geo-alt-fill" style="color: var(--primary-color);"></i>
          <div style="line-height: 1.2;">
            <div style="font-size: 0.75rem; color: var(--text-light);" id="locationLabel">Delivery in 10 mins</div>
            <div style="font-size: 0.9rem; font-weight: 600; color: var(--text-dark);" id="locationText">Detecting location...</div>
          </div>
          <i class="bi bi-chevron-down" style="color: var(--text-light); font-size: 0.8rem;"></i>
        </div>

        <!-- Search Bar -->
        <div class="search-bar-modern">
          <form action="{{ route('products.index') }}" method="GET">
            <input type="text" name="q" placeholder="Search for products..." value="{{ request('q') }}">
            <button type="submit">
              <i class="bi bi-search"></i>
            </button>
          </form>
        </div>

        <!-- Nav Icons -->
        <div class="nav-icons desktop-only">
          @auth
            <!-- Cart -->
            <a href="{{ route('cart.index') }}" class="nav-icon-btn">
              <i class="bi bi-cart3" style="font-size: 1.3rem;"></i>
              <span>Cart</span>
              @if(session('cart') && count(session('cart')) > 0)
                <span class="badge">{{ count(session('cart')) }}</span>
              @endif
            </a>

            <!-- Notifications -->
            <button class="nav-icon-btn" data-bs-toggle="modal" data-bs-target="#notificationsModal">
              <i class="bi bi-bell" style="font-size: 1.3rem;"></i>
            </button>

            <!-- User -->
            <div class="dropdown">
              <button class="nav-icon-btn dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle" style="font-size: 1.3rem;"></i>
                <span>Account</span>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-box-seam me-2"></i>My Orders</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-heart me-2"></i>Wishlist</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                      <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                  </form>
                </li>
              </ul>
            </div>
          @else
            <a href="{{ route('login') }}" class="nav-icon-btn" style="background: var(--primary-color); color: white;">
              <i class="bi bi-person-circle" style="font-size: 1.3rem;"></i>
              <span>Login</span>
            </a>
          @endauth
        </div>
      </div>
    </div>
  </nav>

  <!-- Delivery Banner -->
  <div class="delivery-banner-modern">
    <div class="container">
      <h3>
        <i class="bi bi-lightning-charge-fill"></i>
        Get 10-Minute Delivery on Groceries & Essentials
        <span class="badge" style="background: rgba(255,255,255,0.2); font-size: 0.8rem; margin-left: 8px;">Within 5km</span>
      </h3>
      <p>Free delivery on orders above ₹499 • Same-day delivery available nationwide</p>
    </div>
  </div>

  <!-- Category Pills -->
  <div class="category-pills-modern">
    <div class="container">
      <div class="category-scroll">
        <a href="{{ route('home') }}" class="category-pill active">
          <i class="bi bi-house-fill me-1"></i> All
        </a>
        @if(!empty($categories) && $categories->count())
          @foreach($categories->take(15) as $category)
            <a href="{{ route('buyer.productsByCategory', $category->id) }}" class="category-pill">
              {{ $category->emoji }} {{ $category->name }}
            </a>
          @endforeach
        @endif
        <a href="{{ route('buyer.dashboard') }}" class="category-pill">
          <i class="bi bi-three-dots"></i> More
        </a>
      </div>
    </div>
  </div>

  <!-- Mobile Search (Collapsible) -->
  <div class="collapse mobile-only" id="mobileSearch" style="background: white; padding: 12px; border-bottom: 1px solid var(--border-color);">
    <div class="container">
      <form action="{{ route('products.index') }}" method="GET">
        <div class="search-bar-modern">
          <input type="text" name="q" placeholder="Search for products..." value="{{ request('q') }}">
          <button type="submit">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- Mobile Location Bar -->
  <div class="mobile-location-bar below-nav" onclick="openLocationModal()">
    <div class="mobile-location-content">
      <i class="bi bi-geo-alt-fill mobile-location-icon"></i>
      <div class="mobile-location-text">
        <div class="mobile-location-label" id="mobileLocationLabel">Delivery in 10 mins</div>
        <div class="mobile-location-address" id="mobileLocationText">Detecting location...</div>
      </div>
      <i class="bi bi-chevron-down" style="font-size: 1.2rem;"></i>
    </div>
  </div>

  <!-- Mobile Login Card Section -->
  @guest
  <div class="mobile-location-section">
    <div class="mobile-login-card show" id="mobileLoginCard">
      <button class="mobile-login-close" onclick="closeMobileLoginCard()">
        <i class="bi bi-x"></i>
      </button>
      
      <h3>🎉 Welcome to GrabBaskets!</h3>
      <p>Login to unlock exclusive deals and faster checkout</p>
      
      <form action="{{ route('login') }}" method="POST" class="mobile-login-form">
        @csrf
        <input 
          type="email" 
          name="email" 
          class="mobile-login-input" 
          placeholder="📧 Email Address"
          required
          autocomplete="email"
        >
        
        <input 
          type="password" 
          name="password" 
          class="mobile-login-input" 
          placeholder="🔒 Password"
          required
          autocomplete="current-password"
        >
        
        <button type="submit" class="mobile-login-btn">
          <i class="bi bi-box-arrow-in-right me-2"></i>Login Now
        </button>
      </form>
      
      <div class="mobile-login-divider">
        <span>OR</span>
      </div>
      
      <a href="{{ route('products.index') }}" class="mobile-login-btn" style="display: block; text-align: center; text-decoration: none; background: white; color: var(--primary-color); border: 2px solid var(--primary-color);">
        <i class="bi bi-bag-check me-2"></i>Continue as Guest
      </a>
      
      <div class="mobile-login-footer">
        Don't have an account? 
        <a href="{{ route('register') }}">Sign up</a>
      </div>
    </div>
  </div>
  @endguest

  <!-- Old navbar kept for reference but hidden -->
  <nav class="navbar navbar-expand-lg d-none" id="mainNavbar">
    <div class="container">
      <!-- Logo -->
      <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center">
        🛒 GrabBaskets
      </a>

      <!-- Mobile Search Toggle -->
      <button class="btn d-lg-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch">
        <i class="bi bi-search" style="color: #8B4513;"></i>
      </button>

      <!-- Hamburger Button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar Content -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Desktop Search -->
        <form action="{{ route('products.index') }}" method="GET" class="d-none d-lg-flex search-form mx-auto">
          <input type="text" name="q" placeholder="🔍 Search products, brands, stores..." class="form-control"
            value="{{ request('q') }}" />
          <button class="btn" type="submit">
            <i class="bi bi-search me-1"></i> Search
          </button>
        </form>

        <!-- Nav Links -->
        <ul class="navbar-nav ms-auto align-items-center">
          <!-- Shop with Mega Menu -->
          <li class="nav-item dropdown position-relative">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="shopMegaMenu" 
               data-bs-toggle="dropdown" aria-expanded="false">
              🛍️ <span class="ms-1">Shop</span>
            </a>
            
            <!-- Mega Menu -->
            <div class="mega-menu-wrapper dropdown-menu" aria-labelledby="shopMegaMenu">
              <div class="mega-menu-content">
                <!-- Amazon-like Mega Menu: Emoji for category, 3x3 subcategory grid -->
                <div class="mega-categories-list" id="megaCategoriesList" style="display:flex;flex-direction:column;gap:8px;max-height:420px;overflow-y:auto;">
                  @if(!empty($categories) && $categories->count())
                    @foreach($categories as $category)
                      <div class="mega-category-card" style="display:flex;align-items:flex-start;gap:10px;background:#fff;border-radius:10px;box-shadow:0 1px 4px 0 rgba(139,69,19,0.05);padding:10px 14px;min-height:40px;transition:box-shadow 0.2s;">
                        <div class="mega-category-emoji" style="font-size:1.08rem;width:22px;height:22px;display:flex;align-items:center;justify-content:center;background:#f8f9fa;border-radius:50%;box-shadow:0 1px 2px #e5e5e5;flex-shrink:0;">{{ $category->emoji }}</div>
                        <div style="flex:1;">
                          <div class="mega-category-title mb-1" style="font-size:0.89rem;font-weight:600;color:#232f3e;">{{ $category->name }}</div>
                          @if($category->subcategories && $category->subcategories->count())
                            <div class="mega-subcategories" style="display:flex;flex-wrap:wrap;gap:4px 8px;">
                              @foreach($category->subcategories->take(8) as $subcategory)
                                <a href="{{ route('buyer.productsBySubcategory', $subcategory->id) }}" class="mega-subcategory-link" style="font-size:0.81rem;padding:1px 7px;border-radius:5px;color:#654321;background:rgba(139,69,19,0.04);transition:background 0.2s;">{{ $subcategory->name }}</a>
                              @endforeach
                              @if($category->subcategories->count() > 8)
                                <a href="{{ route('buyer.productsByCategory', $category->id) }}" class="mega-subcategory-link" style="font-weight:600;color:#8B4513;">+{{ $category->subcategories->count() - 8 }} more</a>
                              @endif
                            </div>
                          @else
                            <span class="text-muted small" style="font-size:0.78rem;">No subcategories</span>
                          @endif
                        </div>
                      </div>
                    @endforeach
                  @endif
                </div>

                <!-- View All Button -->
                <div class="mega-view-all">
                  <a href="{{ route('buyer.dashboard') }}" class="mega-view-all-btn">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                    View All Categories
                    <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>
          </li>

          <!-- Cart -->
          @auth
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="{{ route('cart.index') }}">
                🛒 <span class="ms-1 d-none d-md-inline">Cart</span>
              </a>
            </li>
            
            <!-- Notification Bell -->
            <li class="nav-item">
              <x-notification-bell />
            </li>
          @endauth

          <!-- User Dropdown -->
          @auth
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" 
                 role="button" data-bs-toggle="dropdown" aria-expanded="false">
                @php
                  $gender = Auth::user()?->sex ?? 'other';
                  
                  // Fancy queen names for female users
                  $queenNames = [
                    '👑 Queen Cinderella',
                    '✨ Princess Aurora', 
                    '🌹 Queen Isabella',
                    '💎 Princess Anastasia',
                    '🦋 Queen Seraphina',
                    '🌺 Princess Arabella',
                    '⭐ Queen Valentina',
                    '🌙 Princess Luna',
                    '💖 Queen Cordelia',
                    '🌸 Princess Evangeline'
                  ];
                  
                  $greeting = match($gender) {
                    'male' => '👨 Mr.',
                    'female' => $queenNames[array_rand($queenNames)],
                    default => '✨'
                  };
                @endphp
                <span class="user-greeting-interactive">
                  {{ $greeting }} 
                  <span class="ms-1 user-name-bounce">{{ Str::limit(Auth::user()?->name ?? 'User', 12) }}</span>
                  <span class="greeting-emoji">{{ $gender === 'female' ? '👸' : ($gender === 'male' ? '🤴' : '🌟') }}</span>
                </span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0" style="border-radius: 12px;">
                <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                  <i class="bi bi-person me-2"></i>Profile
                </a></li>
                <li><a class="dropdown-item" href="#">
                  <i class="bi bi-box-seam me-2"></i>My Orders
                </a></li>
                <li><a class="dropdown-item" href="#">
                  <i class="bi bi-heart me-2"></i>Wishlist
                </a></li>
                <li><a class="dropdown-item" href="#" onclick="alert('Tamil Welcome: வணக்கம்!'); return false;">
                  <i class="bi bi-volume-up me-2"></i>Tamil Welcome
                </a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                      <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                  </form>
                </li>
              </ul>
            </li>
          @else
            <li class="nav-item">
              <a href="{{ route('login') }}" class="nav-link d-flex align-items-center">
                🔐 <span class="ms-1">Login</span>
              </a>
            </li>
          @endauth
        </ul>
      </div>

      <!-- Mobile Search Bar -->
      <div class="collapse w-100 mt-2 d-lg-none" id="mobileSearch">
        <form action="{{ route('products.index') }}" method="GET" class="search-form">
          <input type="text" name="q" placeholder="🔍 Search products, brands, stores..." class="form-control"
            value="{{ request('q') }}" />
          <button class="btn" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </form>
      </div>
    </div>
  </nav>

  <!-- Simple Menu Button -->
  <button type="button" class="btn btn-lg shadow position-fixed"
    style="bottom:32px;right:32px;z-index:1050;border-radius:50%;width:64px;height:64px;display:flex;align-items:center;justify-content:center;font-size:2rem;background:linear-gradient(135deg,#4A90E2,#2196F3);border:none;color:white;"
    data-bs-toggle="modal" data-bs-target="#categoryMenuModal">
    <i class="bi bi-grid-3x3-gap"></i>
  </button>

  <!-- Category Menu Modal -->
  <div class="modal fade" id="categoryMenuModal" tabindex="-1" aria-labelledby="categoryMenuModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content border-0 shadow-lg" style="border-radius:20px;overflow:hidden;">
        <div class="modal-header bg-gradient-to-r from-orange-50 to-yellow-50 rounded-top-xl border-0">
          <h5 class="modal-title fw-bold" id="categoryMenuModalLabel" style="color:#8B4513;font-size:1.3rem;">� Browse Categories</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body p-0">
          @include('components.category-menu', ['categories' => $categories])
        </div>
      </div>
    </div>
  </div>
  <!-- Hero Section with Modern Clean Carousel -->
  <section class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
      <div class="carousel-inner">
        {{-- Welcome Banner (first slide) - Clean Modern Layout --}}
        <div class="carousel-item active">
          <div class="modern-clean-banner" style="background: linear-gradient(135deg, #4A90E2 0%, #2196F3 100%); min-height: 400px; display: flex; align-items: center;">
            <div class="container">
              <div class="row align-items-center justify-content-center">
                <div class="col-12 col-lg-8 text-center">
                  <h1 class="fw-bold text-white mb-3" style="font-size: clamp(2rem, 5vw, 3.5rem);">Welcome to GrabBaskets</h1>
                  <p class="fs-5 text-white mb-4" style="opacity: 0.95;">Discover amazing products at unbeatable prices</p>
                  <div class="banner-buttons">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg shadow-lg" style="border-radius: 50px; padding: 12px 35px; font-weight: 700;">
                      <i class="bi bi-shop me-2"></i>Start Shopping  
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Special Offers Banner - Clean Modern Layout -->
        <div class="carousel-item">
          <div class="modern-clean-banner" style="background: linear-gradient(135deg, #FFF8E7 0%, #E3F2FD 100%); min-height: 400px; display: flex; align-items: center;">
            <div class="container">
              <div class="row align-items-center justify-content-center">
                <div class="col-12 col-lg-8 text-center">
                  <h1 class="fw-bold mb-3" style="color: #2C3E50; font-size: clamp(2rem, 5vw, 3.5rem);">🎉 Special Offers Just For You</h1>
                  <p class="fs-5 mb-3" style="color: #4A90E2;">Grab amazing deals on your favorite products!</p>
                  <p class="fs-6 text-muted mb-4">✨ Limited Time | ⏰ Don't Miss Out</p>
                  <div class="banner-buttons">
                    <a href="{{ route('products.index') }}" class="btn btn-lg shadow-lg" style="background: linear-gradient(135deg, #4A90E2, #2196F3); color: white; border-radius: 50px; padding: 12px 35px; font-weight: 700; border: none;">
                      <i class="bi bi-lightning-charge-fill me-2"></i>Shop Deals Now
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- Product Banners - Clean Responsive Design --}}
        @foreach($products as $index => $product)
          <div class="carousel-item">
            <div class="product-banner-content" style="background: linear-gradient(135deg, #FFF8E7 0%, #E3F2FD 100%); min-height: 400px; display: flex; align-items: center;">
              <div class="container">
                <div class="row align-items-center">
                  <div class="col-12 col-lg-8">
                    <div style="color: #2C3E50;">
                      <h2 class="h2 fw-bold mb-3">
                        <span class="badge" style="background: linear-gradient(135deg, #4A90E2, #2196F3); color: white; font-size: 1.2rem; padding: 8px 20px; border-radius: 50px;">{{ $product->discount ?? 30 }}% OFF</span>
                      </h2>
                      <h3 class="mb-3" style="color: #2C3E50; font-size: clamp(1.5rem, 4vw, 2.5rem);">{{ $product->category?->name ?? 'Special Category' }}</h3>
                      <p class="mb-2 fs-6" style="color: #4A90E2;">⭐ {{ $product->rating ?? 4.8 }}/5 from {{ $product->reviews_count ?? 500 }}+ happy buyers</p>
                      <p class="mb-4 fw-bold fs-6" style="color: #FF6B6B;">⚡ Hurry! Only {{ $product->stock ?? 10 }} left in stock</p>
                      
                      <div class="banner-buttons d-flex gap-3 flex-wrap">
                        <a href="{{ route('product.details', $product->id) }}" class="btn btn-lg" style="background: white; color: #4A90E2; border: 2px solid #4A90E2; border-radius: 50px; padding: 12px 30px; font-weight: 700;">
                          <i class="bi bi-eye-fill me-2"></i>View Details
                        </a>
                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                          @csrf
                          <input type="hidden" name="product_id" value="{{ $product->id }}">
                          <button type="submit" class="btn btn-lg" style="background: linear-gradient(135deg, #4A90E2, #2196F3); color: white; border: none; border-radius: 50px; padding: 12px 30px; font-weight: 700;">
                            <i class="bi bi-cart-plus-fill me-2"></i>Add to Cart
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Enhanced Carousel Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
      
      <!-- Carousel Indicators for better mobile navigation -->
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        @foreach($products as $index => $product)
          <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index + 2 }}" aria-label="Slide {{ $index + 3 }}"></button>
        @endforeach
      </div>
    </div>
  </section>

  <!-- Zepto-like Category Strip (below banner) -->
  <section class="py-2">
    <div class="container">
      <div class="zepto-cat-section" aria-label="Shop by category">
        <div class="zepto-cat-header">
          <h2>Shop by category</h2>
          <a href="{{ route('buyer.dashboard') }}" class="text-decoration-none" style="font-weight:600;color:#8B4513;">View all</a>
        </div>

        <button class="zepto-cat-nav zepto-cat-prev d-none d-md-grid" type="button" onclick="scrollZeptoCats(-1)">
          <i class="bi bi-chevron-left"></i>
        </button>
        <div id="zeptoCatTrack" class="zepto-cat-track">
          @if(!empty($categories) && $categories->count())
            @foreach($categories->take(24) as $category)
              <div class="zepto-cat-item">
                <a class="zepto-cat-link" href="{{ route('buyer.productsByCategory', $category->id) }}" title="{{ $category->name }}">
                  <span class="zepto-cat-icon position-relative" style="background: linear-gradient(135deg,#fffbe6,#ffe4b5); border: 2px solid #ff9900;">
                    @php $cImg = $category->image_url ?? $category->image ?? null; $emoji = $category->emoji ?? '🛍️'; @endphp
                    @if(!empty($cImg))
                      <img src="{{ $cImg }}" alt="{{ $category->name }}" style="z-index:2;position:relative;" onerror="this.style.display='none'; const fb=this.nextElementSibling; if(fb) fb.classList.remove('d-none');">
                      <span class="zepto-cat-emoji position-absolute top-50 start-50 translate-middle d-none" style="font-size:2rem;z-index:1;">{!! $emoji !!}</span>
                    @else
                      <span class="zepto-cat-emoji position-absolute top-50 start-50 translate-middle" style="font-size:2rem;z-index:1;">{!! $emoji !!}</span>
                    @endif
                  </span>
                  <span class="zepto-cat-name">{{ Str::limit($category->name, 12) }}</span>
                </a>
              </div>
            @endforeach
          @else
            <div class="text-center text-muted" style="grid-column:1/-1;padding:10px 0;">No categories available</div>
          @endif
        </div>
        <button class="zepto-cat-nav zepto-cat-next d-none d-md-grid" type="button" onclick="scrollZeptoCats(1)">
          <i class="bi bi-chevron-right"></i>
        </button>
      </div>

      <!-- Mobile "View All Categories" Button -->
      <div class="d-md-none text-center mt-3 px-3">
        <a href="{{ route('buyer.dashboard') }}" class="btn btn-outline-primary w-100" style="border-radius: 12px; padding: 12px; font-weight: 600; border: 2px solid #667eea;">
          <i class="bi bi-grid-3x3-gap-fill me-2"></i>View All Categories
        </a>
      </div>
    </div>
  </section>

  <!-- 🚀 Delivery Options Banner - Zepto/Blinkit Style -->
  <section class="py-3" style="background: linear-gradient(135deg, #E8F5E9 0%, #F1F8E9 100%); border-bottom: 1px solid rgba(76, 175, 80, 0.1);">
    <div class="container">
      <div class="row g-3 align-items-center">
        <!-- Quick Delivery (Zepto/Blinkit Style) -->
        <div class="col-12 col-md-6">
          <div class="delivery-card" style="background: linear-gradient(135deg, #ffffff 0%, #E8F5E9 100%); padding: 20px; border-radius: 16px; border: 2px solid rgba(76, 175, 80, 0.3); box-shadow: 0 4px 15px rgba(76, 175, 80, 0.1); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(76, 175, 80, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(76, 175, 80, 0.1)'">
            <div class="d-flex align-items-center gap-3">
              <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #4CAF50, #66BB6A); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);">
                <i class="bi bi-lightning-charge-fill" style="font-size: 2rem; color: white;"></i>
              </div>
              <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <h5 class="mb-0 fw-bold" style="color: #2E7D32; font-size: 1.2rem;">⚡ 10-Minute Delivery</h5>
                  <span class="badge" style="background: linear-gradient(45deg, #FF5252, #FF1744); color: white; font-size: 0.7rem; padding: 3px 8px; animation: pulse 2s infinite;">HOT</span>
                </div>
                <p class="mb-0 text-muted" style="font-size: 0.9rem; line-height: 1.4;">
                  <strong style="color: #4CAF50;">Within 5km radius</strong> • Groceries, Essentials & More
                </p>
                <small class="text-success fw-semibold">🎯 Perfect for urgent needs!</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Standard Delivery (Amazon Style) -->
        <div class="col-12 col-md-6">
          <div class="delivery-card" style="background: linear-gradient(135deg, #ffffff 0%, #E3F2FD 100%); padding: 20px; border-radius: 16px; border: 2px solid rgba(33, 150, 243, 0.3); box-shadow: 0 4px 15px rgba(33, 150, 243, 0.1); transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 8px 25px rgba(33, 150, 243, 0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(33, 150, 243, 0.1)'">
            <div class="d-flex align-items-center gap-3">
              <div style="width: 60px; height: 60px; background: linear-gradient(135deg, #2196F3, #42A5F5); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);">
                <i class="bi bi-truck" style="font-size: 2rem; color: white;"></i>
              </div>
              <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-2 mb-1">
                  <h5 class="mb-0 fw-bold" style="color: #1976D2; font-size: 1.2rem;">🚚 Standard Delivery</h5>
                  <span class="badge" style="background: linear-gradient(45deg, #2196F3, #1976D2); color: white; font-size: 0.7rem; padding: 3px 8px;">RELIABLE</span>
                </div>
                <p class="mb-0 text-muted" style="font-size: 0.9rem; line-height: 1.4;">
                  <strong style="color: #2196F3;">2-5 days nationwide</strong> • All products available
                </p>
                <small class="text-primary fw-semibold">📦 Free delivery on orders above ₹499</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Info Banner -->
      <div class="text-center mt-3">
        <p class="mb-0" style="font-size: 0.85rem; color: #666;">
          <i class="bi bi-info-circle-fill me-1" style="color: #4CAF50;"></i>
          <strong>10-min delivery</strong> available for select pin codes within 5km • Check availability at checkout
        </p>
      </div>
    </div>
  </section>



  <!-- Promo Highlights -->
  <section class="py-3">
    <div class="container promo-tiles">
      <div class="row g-3">
        <div class="col-6 col-md-3">
          <div class="tile">
            <div class="icon"><i class="bi bi-lightning-charge-fill"></i></div>
            <div>
              <div class="fw-bold">Lightning Deals</div>
              <small>Grab them before they’re gone</small>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="tile">
            <div class="icon"><i class="bi bi-bag-check-fill"></i></div>
            <div>
              <div class="fw-bold">Assured Quality</div>
              <small>Trusted by millions</small>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="tile">
            <div class="icon"><i class="bi bi-truck"></i></div>
            <div>
              <div class="fw-bold">Fast Delivery</div>
              <small>Speedy doorstep service</small>
            </div>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="tile">
            <div class="icon"><i class="bi bi-arrow-repeat"></i></div>
            <div>
              <div class="fw-bold">Easy Returns</div>
              <small>Hassle-free process</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

    <!-- Enhanced Floating Category Menu - Mobile & Desktop Responsive with Hide/Show -->
    <div id="floatingActionsContainer" class="floating-actions" style="position:fixed;bottom:20px;right:20px;z-index:1200;transition:transform 0.3s ease, opacity 0.3s ease;">
      <!-- Hide Button (small, always visible on mobile) -->
      <button class="fab-hide-btn" id="fabHideBtn" onclick="hideFloatingButton()" style="display:none;background:linear-gradient(135deg,#dc3545,#c82333);color:#fff;border:none;border-radius:50%;padding:6px;box-shadow:0 3px 10px rgba(220,53,69,0.3);font-size:0.9rem;position:absolute;top:-38px;right:8px;width:32px;height:32px;cursor:pointer;transition:all 0.3s;opacity:0.9;" onmouseover="this.style.opacity='1';this.style.transform='scale(1.1)'" onmouseout="this.style.opacity='0.9';this.style.transform='scale(1)'">
        <span style="font-weight:bold;line-height:1;">✕</span>
      </button>
      
      <!-- Main FAB Button -->
      <button class="fab-main" id="fabMainBtn" onclick="toggleFloatingMenu()" style="background:linear-gradient(135deg,#8B4513,#A0522D);color:#fff;border:none;border-radius:50%;padding:12px;box-shadow:0 6px 20px rgba(139,69,19,0.2);font-size:1.6rem;display:flex;align-items:center;justify-content:center;transition:all 0.3s;width:56px;height:56px;cursor:pointer;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
        <span class="fab-icon" style="font-size:1.8rem;">🛍️</span>
      </button>
      
      <!-- Enhanced Mobile & Desktop Responsive Floating Menu Popup -->
      <div id="floatingMenu" class="floating-menu-popup" style="display:none;position:absolute;bottom:70px;right:0;width:min(90vw,420px);max-width:420px;max-height:min(80vh,500px);background:#fff;border-radius:20px;box-shadow:0 15px 50px rgba(139,69,19,0.2);padding:24px;overflow-y:auto;border:1px solid rgba(139,69,19,0.1);">
        <div class="floating-menu-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
          <h6 style="margin:0;font-weight:700;color:#232f3e;font-size:1.1rem;">😊 Browse by Categories 🛍️</h6>
          <button onclick="toggleFloatingMenu()" style="background:rgba(139,69,19,0.1);border:none;font-size:1.3rem;color:#8B4513;cursor:pointer;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;transition:background 0.2s;" onmouseover="this.style.background='rgba(139,69,19,0.2)'" onmouseout="this.style.background='rgba(139,69,19,0.1)'">✕</button>
        </div>
        
        <!-- Enhanced Responsive Categories Grid -->
        <div class="floating-categories-grid" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(80px,1fr));gap:12px;max-width:100%;">
          @if(!empty($categories) && $categories->count())
            @foreach($categories->take(20) as $category)
              <div class="floating-category-card" data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}" style="display:flex;flex-direction:column;align-items:center;padding:12px 8px;border-radius:16px;background:linear-gradient(135deg,#f8f9fa,#fff);border:1px solid rgba(139,69,19,0.1);transition:all 0.3s;cursor:pointer;text-align:center;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 16px rgba(139,69,19,0.15)';this.style.background='linear-gradient(135deg,#fff,#f8f9fa)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='none';this.style.background='linear-gradient(135deg,#f8f9fa,#fff)'">
                <div class="floating-category-emoji" style="font-size:1.4rem;margin-bottom:6px;transition:transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">{!! $category->emoji !!}</div>
                <div class="floating-category-name" style="font-size:0.8rem;font-weight:600;color:#232f3e;line-height:1.2;word-break:break-word;">{{ Str::limit($category->name, 12) }}</div>
              </div>
            @endforeach
          @else
            <div style="grid-column:1/-1;text-align:center;padding:20px;color:#666;">
              <div style="font-size:2rem;margin-bottom:8px;">😅</div>
              <p style="margin:0;font-size:0.9rem;">No categories available</p>
            </div>
          @endif
        </div>
        
        <!-- Enhanced Subcategory Display Area -->
        <div id="subcategoryArea" style="display:none;margin-top:20px;padding-top:20px;border-top:2px solid rgba(139,69,19,0.1);">
          <div id="subcategoryHeader" style="font-weight:700;color:#8B4513;margin-bottom:12px;font-size:1rem;display:flex;align-items:center;gap:8px;">
            <span style="font-size:1.2rem;">📂</span>
            <span></span>
          </div>
          <div id="subcategoryList" style="display:flex;flex-wrap:wrap;gap:8px;"></div>
        </div>
      </div>
    </div>

    <!-- Show FAB Button (appears when FAB is hidden) -->
    <button id="showFabBtn" onclick="showFloatingButton()" style="display:none;position:fixed;bottom:20px;right:20px;z-index:1199;background:linear-gradient(135deg,#28a745,#20c997);color:#fff;border:none;border-radius:50%;padding:10px;box-shadow:0 4px 15px rgba(40,167,69,0.3);font-size:1.3rem;width:48px;height:48px;cursor:pointer;align-items:center;justify-content:center;transition:all 0.3s;animation:pulse 2s infinite;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
      <span>👁️</span>
    </button>
  </section>
  
  <style>
    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
        box-shadow: 0 4px 15px rgba(40,167,69,0.3);
      }
      50% {
        transform: scale(1.05);
        box-shadow: 0 6px 20px rgba(40,167,69,0.5);
      }
    }

    /* Mobile specific styles for floating button */
    @media (max-width: 768px) {
      /* Hide ALL floating elements on mobile - Comprehensive */
      #floatingActionsContainer,
      #showFabBtn,
      .floating-actions,
      .fab-main,
      .fab-hide-btn,
      #fabMainBtn,
      #fabHideBtn,
      .floating-menu-popup,
      #floatingMenu {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
        z-index: -9999 !important;
      }

      /* Hide chatbot widget on mobile */
      .chatbot-widget,
      [id*="chatbot"],
      [id*="Chatbot"],
      [class*="chatbot"],
      [class*="chat-widget"],
      .chat-bubble,
      .support-chat {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        pointer-events: none !important;
      }

      /* Hide any other fixed bottom elements that might interfere */
      [style*="position: fixed"][style*="bottom:"],
      [style*="position:fixed"][style*="bottom:"] {
        position: static !important;
      }

      /* Ensure mobile bottom nav is visible if exists */
      .mobile-bottom-nav,
      .bottom-navigation {
        display: flex !important;
        visibility: visible !important;
        opacity: 1 !important;
        z-index: 1000 !important;
      }
    }
  </style>
  
  <script>
    function scrollZeptoCats(dir) {
      const track = document.getElementById('zeptoCatTrack');
      if (!track) return;
      const itemWidth = track.firstElementChild ? track.firstElementChild.getBoundingClientRect().width : 100;
      track.scrollBy({ left: dir * (itemWidth * 3), behavior: 'smooth' });
    }
  </script>

  <!-- Hero Banners Section - Admin Managed -->
  @if(isset($banners) && $banners->count() > 0)
  <section class="hero-banners py-4">
    <div class="container">
      <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
          @foreach($banners as $index => $banner)
          <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $index }}" 
                  class="{{ $index === 0 ? 'active' : '' }}" aria-label="Banner {{ $index + 1 }}"></button>
          @endforeach
        </div>
        <div class="carousel-inner" style="border-radius: 20px; overflow: hidden;">
          @foreach($banners as $index => $banner)
          <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
            @if($banner->image_url)
              <!-- Image Banner -->
              <div style="position: relative; height: 400px; background: url('{{ asset($banner->image_url) }}') center/cover;">
                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.7), transparent); padding: 40px;">
                  <div class="container">
                    <h2 class="text-white fw-bold mb-2" style="font-size: 2.5rem;">{{ $banner->title }}</h2>
                    @if($banner->description)
                    <p class="text-white mb-3" style="font-size: 1.1rem;">{{ $banner->description }}</p>
                    @endif
                    @if($banner->link_url)
                    <a href="{{ $banner->link_url }}" class="btn btn-light btn-lg fw-bold px-4" 
                       style="border-radius: 25px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
                      {{ $banner->button_text }} <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    @endif
                  </div>
                </div>
              </div>
            @else
              <!-- Color Banner -->
              <div style="height: 400px; background: {{ $banner->background_color }}; display: flex; align-items: center; justify-content: center; text-align: center; padding: 40px;">
                <div style="color: {{ $banner->text_color }}; max-width: 800px;">
                  <h2 class="fw-bold mb-3" style="font-size: 3rem;">{{ $banner->title }}</h2>
                  @if($banner->description)
                  <p class="mb-4" style="font-size: 1.3rem;">{{ $banner->description }}</p>
                  @endif
                  @if($banner->link_url)
                  <a href="{{ $banner->link_url }}" class="btn btn-lg fw-bold px-5 py-3" 
                     style="background: {{ $banner->text_color }}; color: {{ $banner->background_color }}; border-radius: 30px; box-shadow: 0 6px 20px rgba(0,0,0,0.2);">
                    {{ $banner->button_text }} <i class="bi bi-arrow-right ms-2"></i>
                  </a>
                  @endif
                </div>
              </div>
            @endif
          </div>
          @endforeach <!-- Close carousel items foreach -->
        </div>
        @if($banners->count() > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Next</span>
        </button>
        @endif
      </div>
    </div>
  </section>
  @endif

  <!-- Products Section -->
  <section class="products-section">
    <div class="container">
      @php
      $items = ($products instanceof \Illuminate\Pagination\LengthAwarePaginator) ? collect($products->items()) :
      collect($products);
      $flashSale = $items->where('is_flash_sale', true)->take(8);
      // Prefer global top deals so SRM-updated products appear here
      try {
        $deals = \App\Models\Product::orderByDesc('discount')->take(12)->get();
      } catch (\Throwable $e) {
        $deals = $items->sortByDesc('discount')->take(12);
      }
      $trending = $items->take(12);
      $freeDelivery = $items->filter(fn($p) => (int)($p->delivery_charge ?? 0) === 0)->take(12);
      @endphp

      @if($flashSale->count())
      <div class="mb-4">
        <div class="p-3 rounded-4 mb-3" style="background:linear-gradient(90deg,#ff0033 60%,#ff9900 100%);box-shadow:0 6px 32px #ff003344;display:flex;align-items:center;gap:16px;">
          <span class="badge bg-warning text-dark fs-5 px-3 py-2" style="border-radius:16px 0 16px 0;font-weight:700;"><i class="bi bi-lightning-charge-fill"></i> FLASH SALE</span>
          <span class="text-white fw-bold fs-5">Hurry! Limited time offers</span>
          <span class="ms-auto d-none d-md-inline-block text-white-50" style="font-size:1.1rem;">🔥 Ends Soon</span>
        </div>
        <div class="shelf" style="border:2px solid #ff0033;box-shadow:0 4px 24px rgba(255,0,51,0.08);background:linear-gradient(90deg,#fff6f6 60%,#fffbe6 100%);">
          <button class="nav-btn nav-prev" onclick="scrollShelf('flash',-1)" style="background:#ff0033;color:#fff;"><i class="bi bi-chevron-left"></i></button>
          <div id="shelf-flash" class="shelf-track">
            @foreach($flashSale as $product)
            <div class="shelf-item">
              <div class="card product-card h-100 border-0 shadow-sm position-relative" style="overflow:visible;">
                @if($product->discount > 0)
                  <span class="position-absolute top-0 start-0 badge bg-danger fs-6" style="z-index:2;border-radius:0 0 12px 0;">-{{ (int)($product->discount ?? 0) }}%</span>
                @endif
                <!-- Wishlist Heart Button -->
                @auth
                <button class="btn btn-link p-1 wishlist-heart-btn" 
                        data-product-id="{{ $product->id }}" 
                        title="Add to Wishlist"
                        onclick="event.stopPropagation();"
                        style="position: absolute; top: 10px; right: 10px; z-index: 10; background: rgba(255, 255, 255, 0.9); border-radius: 50%; width: 40px; height: 40px;">
                    <i class="bi bi-heart wishlist-icon" style="color: #ccc; font-size: 1.25rem;"></i>
                </button>
                @endauth
                <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none">
                  <img
                    src="{{ $product->image_url }}"
                    class="card-img-top" alt="{{ $product->name }}"
                    data-fallback="{{ asset('images/no-image.png') }}"
                    onerror="this.src=this.dataset.fallback"
                    style="height:170px;object-fit:cover;border-radius:18px 18px 0 0;box-shadow:0 8px 24px #ff003322;cursor:pointer;transition:transform 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.05)'"
                    onmouseout="this.style.transform='scale(1)'">
                </a>
                <div class="card-body d-flex flex-column">
                  <div class="small text-danger fw-bold mb-1"><i class="bi bi-lightning-charge-fill"></i> Flash Sale!</div>
                  <h6 class="card-title mt-1">
                    <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none text-dark" style="cursor:pointer;">
                      {{ \Illuminate\Support\Str::limit($product->name, 40) }}
                    </a>
                  </h6>
                  <div class="mt-auto">
                    @if($product->discount > 0)
                      <span class="fw-bold text-danger fs-5">₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                      <small class="text-muted text-decoration-line-through ms-2">₹{{ number_format($product->price, 2) }}</small>
                    @else
                      <span class="fw-bold fs-5">₹{{ number_format($product->price, 2) }}</span>
                    @endif
                    @auth
                    <form method="POST" action="{{ route('cart.add') }}" class="mt-2 d-flex align-items-center">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $product->id }}">
                      <input type="number" name="quantity" min="1" value="1" class="form-control me-2" style="width:70px;" required>
                      <button type="submit" class="btn btn-danger flex-grow-1">Add to Cart</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-danger w-100 mt-2">Login</a>
                    @endauth
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <button class="nav-btn nav-next" onclick="scrollShelf('flash',1)" style="background:#ff0033;color:#fff;"><i class="bi bi-chevron-right"></i></button>
        </div>
      </div>
      @endif

      <!-- Deals of the Day - Modern Blinkit/Zepto Style -->
      <div class="mb-4">
        <div class="section-header-modern">
          <h2>
            <i class="bi bi-lightning-charge-fill" style="color: #FF6B00;"></i>
            Deals of the Day
            <span class="badge" style="background: linear-gradient(135deg, #FF6B00, #FF9800); color: white; font-size: 0.7rem; padding: 4px 12px; margin-left: 8px;">Limited Time</span>
          </h2>
          <a href="{{ route('products.index') }}" class="view-all">
            View All <i class="bi bi-arrow-right"></i>
          </a>
        </div>
        
        <div class="products-grid-modern">
          @foreach($deals as $product)
            <div class="product-card-modern" onclick="window.location.href='{{ route('product.details', $product->id) }}'">
              <!-- Discount Badge -->
              @if($product->discount > 0)
                <div class="product-discount-modern">
                  {{ (int)$product->discount }}% OFF
                </div>
              @endif

              <!-- Wishlist Button -->
              @auth
                <button class="btn btn-link p-0 wishlist-heart-btn" 
                        data-product-id="{{ $product->id }}" 
                        title="Add to Wishlist"
                        onclick="event.stopPropagation();"
                        style="position: absolute; top: 8px; left: 8px; z-index: 10; background: rgba(255, 255, 255, 0.95); border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                  <i class="bi bi-heart wishlist-icon" style="color: #FF6B00; font-size: 1rem;"></i>
                </button>
              @endauth

              <!-- Product Image -->
              <img
                src="{{ $product->image_url }}"
                alt="{{ $product->name }}"
                class="product-image-modern"
                data-fallback="{{ asset('images/no-image.png') }}"
                onerror="this.src=this.dataset.fallback"
                loading="lazy">

              <!-- Product Info -->
              <div style="flex: 1; display: flex; flex-direction: column;">
                <div class="product-title-modern">{{ \Illuminate\Support\Str::limit($product->name, 45) }}</div>
                
                <!-- Price Section -->
                <div class="product-price-modern">
                  @if($product->discount > 0)
                    <span class="current-price">₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                    <span class="original-price">₹{{ number_format($product->price, 2) }}</span>
                  @else
                    <span class="current-price">₹{{ number_format($product->price, 2) }}</span>
                  @endif
                </div>

                <!-- Add to Cart Button -->
                @auth
                  <button class="add-to-cart-modern" onclick="event.stopPropagation(); document.getElementById('quick-add-{{ $product->id }}').submit();">
                    <i class="bi bi-cart-plus"></i>
                    Add to Cart
                  </button>
                  <form id="quick-add-{{ $product->id }}" method="POST" action="{{ route('cart.add') }}" style="display: none;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                  </form>
                @else
                  <button class="add-to-cart-modern" onclick="event.stopPropagation(); window.location.href='{{ route('login') }}';">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Login to Buy
                  </button>
                @endauth
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <!-- Trending Now - Modern Blinkit/Zepto Style -->
      <div class="mb-4">
        <div class="section-header-modern">
          <h2>
            <i class="bi bi-fire" style="color: var(--primary-color);"></i>
            Trending Now
            <span class="badge" style="background: linear-gradient(135deg, var(--primary-color), #0A6917); color: white; font-size: 0.7rem; padding: 4px 12px; margin-left: 8px;">🔥 Hot</span>
          </h2>
          <a href="{{ route('products.index') }}" class="view-all">
            View All <i class="bi bi-arrow-right"></i>
          </a>
        </div>
        
        <div class="products-grid-modern">
          @foreach($trending as $product)
            <div class="product-card-modern" onclick="window.location.href='{{ route('product.details', $product->id) }}'">
              <!-- Discount Badge -->
              @if($product->discount > 0)
                <div class="product-discount-modern">
                  {{ (int)$product->discount }}% OFF
                </div>
              @endif

              <!-- Wishlist Button -->
              @auth
                <button class="btn btn-link p-0 wishlist-heart-btn" 
                        data-product-id="{{ $product->id }}" 
                        title="Add to Wishlist"
                        onclick="event.stopPropagation();"
                        style="position: absolute; top: 8px; left: 8px; z-index: 10; background: rgba(255, 255, 255, 0.95); border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                  <i class="bi bi-heart wishlist-icon" style="color: var(--primary-color); font-size: 1rem;"></i>
                </button>
              @endauth

              <!-- Product Image -->
              <img
                src="{{ $product->image_url }}"
                alt="{{ $product->name }}"
                class="product-image-modern"
                data-fallback="{{ asset('images/no-image.png') }}"
                onerror="this.src=this.dataset.fallback"
                loading="lazy">

              <!-- Product Info -->
              <div style="flex: 1; display: flex; flex-direction: column;">
                <div class="product-title-modern">{{ \Illuminate\Support\Str::limit($product->name, 45) }}</div>
                
                <!-- Price Section -->
                <div class="product-price-modern">
                  @if($product->discount > 0)
                    <span class="current-price">₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                    <span class="original-price">₹{{ number_format($product->price, 2) }}</span>
                  @else
                    <span class="current-price">₹{{ number_format($product->price, 2) }}</span>
                  @endif
                </div>

                <!-- Add to Cart Button -->
                @auth
                  <button class="add-to-cart-modern" onclick="event.stopPropagation(); document.getElementById('quick-add-trend-{{ $product->id }}').submit();">
                    <i class="bi bi-cart-plus"></i>
                    Add to Cart
                  </button>
                  <form id="quick-add-trend-{{ $product->id }}" method="POST" action="{{ route('cart.add') }}" style="display: none;">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                  </form>
                @else
                  <button class="add-to-cart-modern" onclick="event.stopPropagation(); window.location.href='{{ route('login') }}';">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Login to Buy
                  </button>
                @endauth
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="mb-4">
        <h2 class="mb-3">Free Delivery Picks</h2>
        <div class="shelf">
          <button class="nav-btn nav-prev" onclick="scrollShelf('free',-1)"><i class="bi bi-chevron-left"></i></button>
          <div id="shelf-free" class="shelf-track">
            @forelse($freeDelivery as $product)
            <div class="shelf-item">
              <div class="card product-card h-100 position-relative">
                <!-- Wishlist Heart Button -->
                @auth
                <button class="btn btn-link p-1 wishlist-heart-btn" 
                        data-product-id="{{ $product->id }}" 
                        title="Add to Wishlist"
                        onclick="event.stopPropagation();"
                        style="position: absolute; top: 10px; right: 10px; z-index: 10; background: rgba(255, 255, 255, 0.9); border-radius: 50%; width: 40px; height: 40px;">
                    <i class="bi bi-heart wishlist-icon" style="color: #ccc; font-size: 1.25rem;"></i>
                </button>
                @endauth
                <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none">
                  <img
                    src="{{ $product->image_url }}"
                    class="card-img-top" alt="{{ $product->name }}"
                    data-fallback="{{ asset('images/no-image.png') }}"
                    onerror="this.src=this.dataset.fallback"
                    style="cursor:pointer;transition:transform 0.3s ease;"
                    onmouseover="this.style.transform='scale(1.05)'"
                    onmouseout="this.style.transform='scale(1)'">
                </a>
                <div class="card-body d-flex flex-column">
                  <div class="small text-success">Free Delivery</div>
                  <h6 class="card-title">
                    <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none text-dark" style="cursor:pointer;">
                      {{ \Illuminate\Support\Str::limit($product->name, 40) }}
                    </a>
                  </h6>
                  <div class="mt-auto">
                    @if($product->discount > 0)
                      <span class="fw-bold text-success">₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                      <small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</small>
                      <small class="text-danger">({{ $product->discount }}% off)</small>
                    @else
                      <span class="fw-bold">₹{{ number_format($product->price, 2) }}</span>
                    @endif
                    @auth
                    <form method="POST" action="{{ route('cart.add') }}" class="mt-2 d-flex align-items-center">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $product->id }}">
                      <input type="number" name="quantity" min="1" value="1" class="form-control me-2"
                        style="width:70px;" required>
                      <button type="submit" class="btn btn-primary flex-grow-1">Add</button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-primary w-100 mt-2">Login</a>
                    @endauth
                  </div>
                </div>
              </div>
            </div>
            @empty
            <div class="text-muted">No free delivery picks right now.</div>
            @endforelse
          </div>
          <button class="nav-btn nav-next" onclick="scrollShelf('free',1)"><i class="bi bi-chevron-right"></i></button>
        </div>
      </div>
    </div>
  {{-- </section> --}}

  <!-- Products by Category Showcase - MODERN CLEAN THEME -->
  <section class="py-5" style="background: linear-gradient(135deg, #E3F2FD 0%, #FFFBF0 50%, #E3F2FD 100%);">
    <div class="container">
      <!-- Clean Section Header -->
      <div class="text-center mb-5">
        <div class="d-inline-block position-relative mb-3">
          <h2 class="display-4 fw-bold mb-0" style="background: linear-gradient(45deg, #4A90E2, #2196F3, #64B5F6, #2196F3, #4A90E2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-shadow: 0 4px 10px rgba(74, 144, 226, 0.3);">
            Shop by Category
          </h2>
          <div style="position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); width: 200px; height: 4px; background: linear-gradient(90deg, transparent, #4A90E2, #2196F3, #4A90E2, transparent); border-radius: 2px; box-shadow: 0 0 10px rgba(74, 144, 226, 0.5);"></div>
        </div>
        <p class="lead mt-4" style="color: #2196F3; font-weight: 600;">Explore our collections with amazing deals</p>
        <p class="text-muted">Special Offers on Every Category!</p>
      </div>

      <!-- Category Grid with Emojis -->
      <div class="row g-4 mb-5">
        @if(!empty($categories) && $categories->count())
          @foreach($categories as $category)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
              <a href="{{ route('buyer.productsByCategory', $category->id) }}" class="text-decoration-none">
                <div class="category-card-emoji-design" style="
                  background: linear-gradient(135deg, #FFFFFF 0%, #FFF5E6 100%);
                  border-radius: 20px;
                  padding: 25px 20px;
                  border: 2px solid rgba(74, 144, 226, 0.2);
                  transition: all 0.3s ease;
                  height: 100%;
                  position: relative;
                  overflow: hidden;
                  box-shadow: 0 4px 15px rgba(74, 144, 226, 0.15), 0 0 20px rgba(74, 144, 226, 0.1);
                " onmouseover="
                  this.style.transform='translateY(-10px) scale(1.02)';
                  this.style.boxShadow='0 15px 40px rgba(74, 144, 226, 0.3), 0 0 40px rgba(74, 144, 226, 0.2)';
                  this.style.borderColor='rgba(74, 144, 226, 0.5)';
                " onmouseout="
                  this.style.transform='translateY(0) scale(1)';
                  this.style.boxShadow='0 4px 15px rgba(74, 144, 226, 0.15), 0 0 20px rgba(74, 144, 226, 0.1)';
                  this.style.borderColor='rgba(74, 144, 226, 0.2)';
                ">
                  
                  <!-- Modern Clean Background Effect -->
                  <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(74,144,226,0.05) 0%, rgba(33,150,243,0.05) 100%); pointer-events: none; opacity: 0; transition: opacity 0.3s;" class="hover-gradient"></div>
                  
                  <!-- Emoji Circle with Blue Glow -->
                  <div class="text-center mb-3">
                    <div style="
                      width: 100px;
                      height: 100px;
                      margin: 0 auto;
                      background: linear-gradient(135deg, rgba(74,144,226,0.15) 0%, rgba(33,150,243,0.15) 100%);
                      border-radius: 50%;
                      display: flex;
                      align-items: center;
                      justify-content: center;
                      font-size: 3.5rem;
                      transition: all 0.3s ease;
                      box-shadow: 0 5px 15px rgba(74, 144, 226, 0.2), 0 0 30px rgba(74, 144, 226, 0.3);
                      border: 3px solid rgba(74, 144, 226, 0.3);
                    " class="emoji-circle">
                      {{ $category->emoji ?? '🛒' }}
                    </div>
                  </div>
                  
                  <!-- Category Name with Blue Color -->
                  <h5 class="text-center fw-bold mb-2" style="color: #2196F3; font-size: 1.1rem; text-shadow: 0 2px 4px rgba(33, 150, 243, 0.2);">
                    {{ $category->name }}
                  </h5>
                  
                  <!-- Product Count Badge -->
                  @php
                    // Safe way to get product count - check if relationship is loaded
                    try {
                      $productCount = $category->products()->count();
                    } catch (\Exception $e) {
                      $productCount = 0;
                    }
                  @endphp
                  <div class="text-center">
                    <span class="badge" style="
                      background: linear-gradient(45deg, #4A90E2, #2196F3);
                      color: white;
                      font-size: 0.85rem;
                      padding: 6px 15px;
                      border-radius: 20px;
                      font-weight: 600;
                      box-shadow: 0 2px 10px rgba(74, 144, 226, 0.4);
                    ">
                      {{ $productCount }} {{ $productCount === 1 ? 'Item' : 'Items' }}
                    </span>
                  </div>
                  
                  <!-- Subcategories Preview (if any) -->
                  @if($category->subcategories && $category->subcategories->count() > 0)
                    <div class="mt-3 pt-3" style="border-top: 1px solid rgba(255, 107, 0, 0.2);">
                      <div style="font-size: 0.75rem; color: #666; text-align: center;">
                        @foreach($category->subcategories->take(3) as $subcat)
                          <span style="
                            display: inline-block;
                            background: linear-gradient(135deg, rgba(255,215,0,0.15), rgba(255,107,0,0.1));
                            padding: 3px 10px;
                            border-radius: 12px;
                            margin: 2px;
                            font-weight: 500;
                            color: #FF4444;
                            border: 1px solid rgba(255, 107, 0, 0.2);
                          ">{{ Str::limit($subcat->name, 12) }}</span>
                        @endforeach
                        @if($category->subcategories->count() > 3)
                          <span style="font-weight: 600; color: #4A90E2;">+{{ $category->subcategories->count() - 3 }}</span>
                        @endif
                      </div>
                    </div>
                  @endif
                  
                  <!-- View Arrow Indicator with Blue Color -->
                  <div class="text-center mt-3">
                    <span style="
                      display: inline-flex;
                      align-items: center;
                      gap: 5px;
                      color: #4A90E2;
                      font-size: 0.9rem;
                      font-weight: 600;
                      transition: all 0.3s;
                    " class="view-arrow">
                      View Collection
                      <i class="bi bi-arrow-right" style="font-size: 1rem; transition: transform 0.3s;"></i>
                    </span>
                  </div>
                </div>
              </a>
            </div>
          @endforeach
        @else
          <div class="col-12">
            <div class="text-center py-5">
              <div style="font-size: 4rem; margin-bottom: 20px;">😅</div>
              <h4 class="text-muted">No categories available yet</h4>
              <p class="text-muted">Check back soon for amazing deals!</p>
            </div>
          </div>
        @endif
      </div>

      <!-- Clean View All Categories Button -->
      <div class="text-center mt-5">
        <a href="{{ route('buyer.dashboard') }}" class="btn btn-lg" style="
          background: linear-gradient(45deg, #4A90E2, #2196F3, #64B5F6);
          color: white;
          border: none;
          border-radius: 30px;
          padding: 15px 50px;
          font-weight: 700;
          font-size: 1.1rem;
          box-shadow: 0 8px 25px rgba(74, 144, 226, 0.4), 0 0 30px rgba(74, 144, 226, 0.3);
          transition: all 0.3s ease;
          border: 2px solid rgba(74, 144, 226, 0.5);
        " onmouseover="
          this.style.transform='translateY(-3px) scale(1.05)';
          this.style.boxShadow='0 12px 35px rgba(74, 144, 226, 0.5), 0 0 50px rgba(74, 144, 226, 0.5)';
          this.style.background='linear-gradient(45deg, #64B5F6, #2196F3, #4A90E2)';
        " onmouseout="
          this.style.transform='translateY(0) scale(1)';
          this.style.boxShadow='0 8px 25px rgba(74, 144, 226, 0.4), 0 0 30px rgba(74, 144, 226, 0.3)';
          this.style.background='linear-gradient(45deg, #4A90E2, #2196F3, #64B5F6)';
        ">
          <i class="bi bi-stars me-2"></i>
          Explore All Categories
          <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- Enhanced Clean Hover Effects -->
  <style>
    .category-card-emoji-design:hover .emoji-circle {
      transform: scale(1.15) rotate(5deg);
      background: linear-gradient(135deg, rgba(74,144,226,0.3) 0%, rgba(33,150,243,0.3) 100%);
      box-shadow: 0 10px 30px rgba(74, 144, 226, 0.4), 0 0 40px rgba(74, 144, 226, 0.5);
    }
    
    .category-card-emoji-design:hover .hover-gradient {
      opacity: 1;
    }
    
    .category-card-emoji-design:hover .view-arrow i {
      transform: translateX(5px);
      color: #4A90E2;
    }
    
    @media (max-width: 576px) {
      .category-card-emoji-design {
        padding: 20px 15px !important;
      }
      
      .emoji-circle {
        width: 80px !important;
        height: 80px !important;
        font-size: 2.8rem !important;
      }
    }
    
    /* Modern Product Card Hover Effects */
    .festive-product-card {
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .festive-product-card:hover {
      transform: translateY(-12px) scale(1.02);
      box-shadow: 0 15px 40px rgba(74, 144, 226, 0.25), 0 0 50px rgba(74, 144, 226, 0.2) !important;
      border-color: rgba(74, 144, 226, 0.5) !important;
    }
    
    .festive-product-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(74, 144, 226, 0.05), rgba(33, 150, 243, 0.05));
      opacity: 0;
      transition: opacity 0.4s ease;
      pointer-events: none;
      z-index: 1;
    }
    
    .festive-product-card:hover::before {
      opacity: 1;
    }
    
    @media (max-width: 768px) {
      .festive-product-card:hover {
        transform: translateY(-8px) scale(1.01);
      }
    }
    
    /* Featured Products Grid Alignment */
    .row.g-4 {
      margin-left: -0.75rem;
      margin-right: -0.75rem;
    }
    
    .row.g-4 > [class*='col-'] {
      padding-left: 0.75rem;
      padding-right: 0.75rem;
    }
    
    /* Ensure equal height cards in grid */
    .card.h-100 {
      display: flex;
      flex-direction: column;
      height: 100%;
    }
    
    .card.h-100 .card-body {
      flex: 1 1 auto;
      display: flex;
      flex-direction: column;
    }
    
    .card.h-100 .card-body .mt-auto {
      margin-top: auto !important;
    }
    
    /* Responsive grid fixes */
    @media (min-width: 576px) and (max-width: 767.98px) {
      /* Small tablets: 2 columns */
      .col-sm-6 {
        flex: 0 0 50%;
        max-width: 50%;
      }
    }
    
    @media (min-width: 768px) and (max-width: 991.98px) {
      /* Medium tablets: 2 columns */
      .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
      }
    }
    
    @media (min-width: 992px) and (max-width: 1199.98px) {
      /* Large screens: 3 columns */
      .col-lg-4 {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
      }
    }
    
    @media (min-width: 1200px) {
      /* Extra large screens: 4 columns */
      .col-xl-3 {
        flex: 0 0 25%;
        max-width: 25%;
      }
    }
    
    /* Ensure images maintain aspect ratio */
    .card-img-top {
      width: 100%;
      object-fit: cover;
    }
    
    /* Remove extra spacing on mobile */
    @media (max-width: 575.98px) {
      .row.g-4 {
        gap: 1rem !important;
      }
    }
  </style>

  <!-- Featured Products Section - Fresh Monsoon Theme -->
  <section class="py-5" style="background: linear-gradient(135deg, rgba(230, 245, 255, 0.95) 0%, rgba(200, 230, 255, 0.95) 50%, rgba(180, 220, 255, 0.95) 100%); position: relative; overflow: hidden;">
    <!-- Decorative Rain Pattern -->
    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-image: radial-gradient(circle at 10% 20%, rgba(100, 180, 255, 0.08) 0%, transparent 50%), radial-gradient(circle at 90% 80%, rgba(70, 150, 230, 0.08) 0%, transparent 50%); pointer-events: none;"></div>
    
    <div class="container" style="position: relative; z-index: 1;">
      <div class="text-center mb-5">
        <h2 class="h3 fw-bold mb-3" style="background: linear-gradient(45deg, #4A90E2, #5BA3E8, #6CB5EE, #5BA3E8, #4A90E2); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; font-size: 2.5rem; text-shadow: 0 4px 10px rgba(74, 144, 226, 0.3);">
          Featured Products
        </h2>
        <div style="width: 100px; height: 4px; background: linear-gradient(90deg, transparent, #4A90E2, #6CB5EE, #4A90E2, transparent); margin: 0 auto 20px; border-radius: 2px; box-shadow: 0 0 10px rgba(74, 144, 226, 0.5);"></div>
        <p class="text-muted mb-2" style="color: #4A90E2 !important; font-weight: 500; font-size: 1.1rem;">Handpicked items from our best categories</p>
        <p class="text-muted" style="color: #6c757d !important; font-size: 0.95rem;">Fresh deals on trending products!</p>
      </div>
      @if(isset($categoryProducts) && !empty($categoryProducts))
        @foreach($categoryProducts as $categoryName => $products)
          @if($products->count() > 0)
          <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between mb-4" style="background: linear-gradient(135deg, rgba(74, 144, 226, 0.1) 0%, rgba(100, 180, 255, 0.05) 100%); padding: 15px 20px; border-radius: 15px; border: 2px solid rgba(74, 144, 226, 0.2); box-shadow: 0 4px 15px rgba(74, 144, 226, 0.1);">
              <h3 class="h4 fw-bold mb-0" style="color: #4A90E2; text-shadow: 0 2px 4px rgba(74, 144, 226, 0.2);">
                {{ $categoryName }}
              </h3>
              <span class="badge" style="background: linear-gradient(45deg, #4A90E2, #6CB5EE); color: white; padding: 8px 16px; font-size: 0.9rem; border-radius: 20px; box-shadow: 0 4px 12px rgba(74, 144, 226, 0.3);">
                {{ $products->count() }} Products
              </span>
            </div>
            <div class="row g-4">
              @foreach($products as $product)
              <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card h-100 festive-product-card position-relative" style="border: 2px solid rgba(74, 144, 226, 0.2); border-radius: 20px; overflow: hidden; background: linear-gradient(135deg, #FFFFFF 0%, #E8F4FD 100%); box-shadow: 0 8px 25px rgba(74, 144, 226, 0.15), 0 0 30px rgba(100, 180, 255, 0.1); transition: all 0.4s ease;">
                  <!-- Wishlist Heart Button -->
                  @auth
                  <button class="btn btn-link p-1 wishlist-heart-btn" 
                          data-product-id="{{ $product->id }}" 
                          title="Add to Wishlist"
                          style="position: absolute; top: 10px; left: 10px; z-index: 10; background: rgba(255, 255, 255, 0.9); border-radius: 50%; width: 40px; height: 40px;">
                      <i class="bi bi-heart wishlist-icon" style="color: #ccc; font-size: 1.25rem;"></i>
                  </button>
                  @endauth
                  <div style="position: relative; overflow: hidden;">
                    @php
                      $fallbackUrl = 'https://picsum.photos/300/250?grayscale&text=' . urlencode(str_replace(['&', '+'], ['and', 'plus'], $categoryName));
                    @endphp
                    <img src="{{ $product->image_url }}" 
                         class="card-img-top" 
                         alt="{{ $product->name }}"
                         style="height: 280px; object-fit: cover; transition: transform 0.4s ease;"
                         data-fallback="{{ $fallbackUrl }}"
                         onerror="this.src=this.dataset.fallback"
                         onmouseover="this.style.transform='scale(1.1)'"
                         onmouseout="this.style.transform='scale(1)'">
                    <!-- Monsoon Fresh Discount Badge -->
                    @if($product->discount > 0)
                    <div style="position: absolute; top: 10px; right: 10px; background: linear-gradient(135deg, #4A90E2, #5BA3E8); color: white; padding: 8px 15px; border-radius: 25px; font-weight: bold; font-size: 0.85rem; box-shadow: 0 4px 15px rgba(74, 144, 226, 0.4); border: 2px solid rgba(100, 180, 255, 0.3);">
                      {{ $product->discount }}% OFF
                    </div>
                    @endif
                  </div>
                  <div class="card-body d-flex flex-column" style="padding: 20px;">
                    <h6 class="card-title fw-bold mb-2" style="color: #4A90E2; font-size: 1.05rem; line-height: 1.4;">
                      {{ \Illuminate\Support\Str::limit($product->name, 60) }}
                    </h6>
                    <p class="card-text small mb-3" style="color: #666; line-height: 1.6;">
                      {{ \Illuminate\Support\Str::limit($product->description, 100) }}
                    </p>
                    <div class="mt-auto">
                      <!-- Monsoon Price Section -->
                      @if($product->discount > 0)
                        <div class="price-section mb-3" style="background: linear-gradient(135deg, rgba(74, 144, 226, 0.1), rgba(100, 180, 255, 0.1)); padding: 12px; border-radius: 12px; border: 1px solid rgba(74, 144, 226, 0.2);">
                          <div class="d-flex align-items-center justify-content-between">
                            <div>
                              <span class="fw-bold d-block" style="color: #4A90E2; font-size: 1.4rem;">₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                              <small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</small>
                            </div>
                            <div class="text-end">
                              <small class="badge" style="background: linear-gradient(45deg, #4A90E2, #6CB5EE); color: white; padding: 6px 12px; border-radius: 15px;">Save ₹{{ number_format($product->price * ($product->discount / 100), 2) }}</small>
                            </div>
                          </div>
                        </div>
                      @else
                        <div class="price-section mb-3" style="background: linear-gradient(135deg, rgba(74, 144, 226, 0.1), rgba(100, 180, 255, 0.1)); padding: 12px; border-radius: 12px; border: 1px solid rgba(74, 144, 226, 0.2);">
                          <span class="fw-bold" style="color: #4A90E2; font-size: 1.4rem;">₹{{ number_format($product->price, 2) }}</span>
                        </div>
                      @endif
                      <!-- Stock Status with Fresh Style -->
                      @if($product->stock > 0)
                        <small class="d-block mb-3" style="color: #28a745; font-weight: 600;">
                          <i class="bi bi-check-circle-fill"></i> In Stock 
                          <span style="background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05)); padding: 4px 10px; border-radius: 8px; margin-left: 5px;">{{ $product->stock }} available</span>
                        </small>
                      @else
                        <small class="d-block mb-3" style="color: #dc3545; font-weight: 600;">
                          <i class="bi bi-x-circle-fill"></i> Out of Stock
                        </small>
                      @endif
                      <!-- Monsoon Fresh Action Buttons -->
                      <div class="d-grid gap-2">
                        <a href="{{ route('product.details', $product->id) }}" 
                           class="btn btn-sm" 
                           style="background: linear-gradient(135deg, rgba(74, 144, 226, 0.1), rgba(100, 180, 255, 0.1)); color: #4A90E2; border: 2px solid rgba(74, 144, 226, 0.3); font-weight: 600; padding: 10px; border-radius: 12px; transition: all 0.3s ease;"
                           onmouseover="this.style.background='linear-gradient(135deg, #4A90E2, #6CB5EE)'; this.style.color='white'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(74, 144, 226, 0.4)'"
                           onmouseout="this.style.background='linear-gradient(135deg, rgba(74, 144, 226, 0.1), rgba(100, 180, 255, 0.1))'; this.style.color='#4A90E2'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                          <i class="bi bi-eye"></i> View Details
                        </a>
                        @auth
                          @if($product->stock > 0)
                          <form method="POST" action="{{ route('cart.add') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" 
                                    class="btn btn-sm w-100" 
                                    style="background: linear-gradient(45deg, #4A90E2, #5BA3E8, #6CB5EE); color: white; border: none; font-weight: 700; padding: 12px; border-radius: 12px; box-shadow: 0 6px 20px rgba(74, 144, 226, 0.3); transition: all 0.3s ease;"
                                    onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(74, 144, 226, 0.5)'"
                                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(74, 144, 226, 0.3)'">
                              <i class="bi bi-cart-plus"></i> Add to Cart
                            </button>
                          </form>
                          @else
                          <button class="btn btn-sm w-100" 
                                  style="background: linear-gradient(135deg, #6c757d, #5a6268); color: white; border: none; padding: 12px; border-radius: 12px;" 
                                  disabled>
                            <i class="bi bi-x-circle"></i> Out of Stock
                          </button>
                          @endif
                        @else
                          <a href="{{ route('login') }}" 
                             class="btn btn-sm w-100" 
                             style="background: linear-gradient(45deg, #4A90E2, #6CB5EE); color: white; border: none; font-weight: 700; padding: 12px; border-radius: 12px; box-shadow: 0 6px 20px rgba(74, 144, 226, 0.3); transition: all 0.3s ease;"
                             onmouseover="this.style.transform='translateY(-3px)'; this.style.boxShadow='0 10px 30px rgba(74, 144, 226, 0.5)'"
                             onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 6px 20px rgba(74, 144, 226, 0.3)'">
                            <i class="bi bi-box-arrow-in-right"></i> Login to Buy
                          </a>
                        @endauth
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>
          @endif
        @endforeach
      @endif
    </div>
  </section>

  <!-- Trending Now Section - Sleek Dark Neon Design -->
  <section class="trending-neon-section position-relative py-5 my-5" style="background: linear-gradient(180deg, #0f0f23 0%, #1a1a3e 50%, #0f0f23 100%); overflow: hidden;">
    <!-- Neon Grid Background -->
    <div class="neon-grid-bg" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-image: 
      repeating-linear-gradient(0deg, transparent, transparent 50px, rgba(0, 255, 255, 0.03) 50px, rgba(0, 255, 255, 0.03) 51px),
      repeating-linear-gradient(90deg, transparent, transparent 50px, rgba(255, 0, 255, 0.03) 50px, rgba(255, 0, 255, 0.03) 51px);
      opacity: 0.4;"></div>

    <!-- Floating Particles -->
    <div class="particles-container" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; pointer-events: none;">
      <div class="particle" style="position: absolute; width: 4px; height: 4px; background: #00ffff; border-radius: 50%; top: 20%; left: 10%; animation: float-particle 6s infinite;"></div>
      <div class="particle" style="position: absolute; width: 3px; height: 3px; background: #ff00ff; border-radius: 50%; top: 60%; left: 80%; animation: float-particle 8s infinite 2s;"></div>
      <div class="particle" style="position: absolute; width: 5px; height: 5px; background: #00ff00; border-radius: 50%; top: 40%; left: 50%; animation: float-particle 7s infinite 1s;"></div>
    </div>
    
    <div class="container position-relative" style="z-index: 2;">
      <!-- Section Header -->
      <div class="text-center mb-5">
        <!-- Neon Title -->
        <div class="mb-4">
          <span class="neon-badge d-inline-block px-4 py-2 mb-3" style="background: rgba(0, 255, 255, 0.1); border: 2px solid #00ffff; border-radius: 30px; color: #00ffff; font-weight: 700; text-transform: uppercase; letter-spacing: 3px; font-size: 0.9rem; box-shadow: 0 0 20px rgba(0, 255, 255, 0.5), inset 0 0 20px rgba(0, 255, 255, 0.1); animation: neon-pulse 2s ease-in-out infinite;">
            <i class="bi bi-lightning-charge-fill me-2"></i>TRENDING NOW<i class="bi bi-lightning-charge-fill ms-2"></i>
          </span>
        </div>
        
        <h2 class="neon-title mb-3" style="font-size: clamp(2.5rem, 6vw, 4rem); font-weight: 900; background: linear-gradient(90deg, #00ffff, #ff00ff, #00ffff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; text-shadow: 0 0 30px rgba(0, 255, 255, 0.5); filter: drop-shadow(0 0 10px rgba(255, 0, 255, 0.5)); animation: gradient-shift 3s ease infinite; background-size: 200% 200%;">
          🔥 HOTTEST PICKS 🔥
        </h2>
        
        <p class="lead mb-0" style="color: #a0a0ff; font-size: 1.1rem; font-weight: 500;">
          ⚡ Products everyone is buying right now ⚡
        </p>
      </div>

      <!-- Products Slider -->
      <div class="trending-slider-wrapper position-relative">
        <!-- Previous Button -->
        <button class="slider-nav slider-prev" onclick="slideTrending(-1)" style="position: absolute; left: -60px; top: 50%; transform: translateY(-50%); z-index: 10; width: 50px; height: 50px; border-radius: 50%; background: rgba(0, 255, 255, 0.1); border: 2px solid #00ffff; color: #00ffff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s; box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);">
          <i class="bi bi-chevron-left" style="font-size: 1.5rem;"></i>
        </button>

        <!-- Products Grid -->
        <div class="row g-4" id="trendingGrid">
          @foreach($trending as $product)
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 trending-item">
              <div class="neon-card position-relative h-100" style="transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
                <a href="{{ route('product.details', $product->id) }}" class="text-decoration-none d-block h-100">
                  <div class="card border-0 h-100" style="background: linear-gradient(145deg, #1a1a3e, #0f0f23); border-radius: 24px; overflow: hidden; box-shadow: 0 10px 40px rgba(0, 255, 255, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.05); border: 1px solid rgba(0, 255, 255, 0.2);">
                    
                    <!-- Rank Badge -->
                    <div class="rank-neon" style="position: absolute; top: 15px; left: 15px; z-index: 10; width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #ff00ff, #00ffff); display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 1.2rem; color: #000; box-shadow: 0 0 30px rgba(255, 0, 255, 0.8); animation: rank-glow 2s ease-in-out infinite;">
                      {{ $loop->iteration }}
                    </div>

                    <!-- Wishlist -->
                    @auth
                    <button class="btn wishlist-heart-btn neon-wishlist" 
                            data-product-id="{{ $product->id }}"
                            onclick="event.preventDefault(); event.stopPropagation();"
                            style="position: absolute; top: 15px; right: 15px; z-index: 10; width: 45px; height: 45px; border-radius: 50%; background: rgba(255, 0, 255, 0.2); border: 2px solid #ff00ff; color: #ff00ff; box-shadow: 0 0 20px rgba(255, 0, 255, 0.5);">
                      <i class="bi bi-heart-fill wishlist-icon" style="font-size: 1.1rem;"></i>
                    </button>
                    @endauth

                    <!-- Discount Neon -->
                    @if($product->discount > 0)
                      <div class="discount-neon" style="position: absolute; top: 75px; left: 15px; z-index: 10; background: rgba(0, 255, 0, 0.2); border: 2px solid #00ff00; padding: 8px 16px; border-radius: 20px; color: #00ff00; font-weight: 900; font-size: 0.95rem; box-shadow: 0 0 25px rgba(0, 255, 0, 0.6); animation: discount-blink 1.5s ease-in-out infinite;">
                        -{{ $product->discount }}%
                      </div>
                    @endif

                    <!-- Product Image -->
                    <div class="neon-img-container position-relative" style="height: 300px; overflow: hidden; border-bottom: 2px solid rgba(0, 255, 255, 0.2);">
                      <img src="{{ $product->image_url }}"
                           alt="{{ $product->name }}"
                           class="neon-product-img w-100 h-100"
                           loading="lazy"
                           data-fallback="{{ asset('images/no-image.png') }}"
                           onerror="this.onerror=null; this.src=this.dataset.fallback;"
                           style="object-fit: cover; transition: all 0.5s ease; filter: brightness(0.9);">
                      
                      <!-- Scan Line Effect -->
                      <div class="scan-line" style="position: absolute; top: 0; left: 0; right: 0; height: 2px; background: linear-gradient(90deg, transparent, #00ffff, transparent); animation: scan 3s linear infinite;"></div>
                      
                      <!-- Hover Overlay -->
                      <div class="neon-overlay" style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(0, 255, 255, 0.95), rgba(255, 0, 255, 0.95)); opacity: 0; transition: opacity 0.4s; display: flex; flex-column; align-items: center; justify-content: center; gap: 15px;">
                        <i class="bi bi-eye-fill" style="font-size: 3.5rem; color: white; filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.8));"></i>
                        <span style="color: white; font-weight: 800; font-size: 1.2rem; letter-spacing: 2px; text-transform: uppercase;">QUICK VIEW</span>
                      </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body p-4" style="background: linear-gradient(145deg, #1f1f3f, #151530);">
                      <!-- Title -->
                      <h6 class="neon-product-title mb-3" style="color: #ffffff; font-size: 1rem; font-weight: 700; line-height: 1.4; min-height: 44px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);">
                        {{ $product->name }}
                      </h6>

                      <!-- Rating -->
                      <div class="d-flex align-items-center mb-3">
                        @php 
                          $stars = rand(4, 5);
                          $reviews = rand(100, 500);
                        @endphp
                        <div class="neon-stars me-2">
                          @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star-fill" style="font-size: 0.9rem; color: {{ $i <= $stars ? '#FFD700' : '#4a4a6a' }}; filter: drop-shadow(0 0 3px {{ $i <= $stars ? 'rgba(255, 215, 0, 0.8)' : 'transparent' }});"></i>
                          @endfor
                        </div>
                        <small style="color: #a0a0ff; font-weight: 600;">({{ $reviews }})</small>
                      </div>

                      <!-- Price -->
                      <div class="mb-3">
                        @if($product->discount > 0)
                          <div class="d-flex align-items-baseline gap-2 mb-1">
                            <span class="neon-price" style="font-size: 1.6rem; font-weight: 900; background: linear-gradient(90deg, #00ffff, #00ff00); -webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 0 8px rgba(0, 255, 255, 0.6));">
                              ₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}
                            </span>
                            <small class="text-decoration-line-through" style="color: #6a6a8a;">₹{{ number_format($product->price, 2) }}</small>
                          </div>
                          <small style="color: #00ff00; font-weight: 700; text-shadow: 0 0 8px rgba(0, 255, 0, 0.6);">
                            <i class="bi bi-tag-fill"></i> Save ₹{{ number_format($product->price * ($product->discount / 100), 2) }}
                          </small>
                        @else
                          <span class="neon-price" style="font-size: 1.6rem; font-weight: 900; background: linear-gradient(90deg, #00ffff, #00ff00); -webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0 0 8px rgba(0, 255, 255, 0.6));">
                            ₹{{ number_format($product->price, 2) }}
                          </span>
                        @endif
                      </div>

                      <!-- Stock & Actions -->
                      <div class="d-flex align-items-center gap-2 mb-3">
                        @if($product->stock > 0)
                          <span class="badge flex-grow-0" style="background: rgba(0, 255, 0, 0.2); color: #00ff00; border: 1px solid #00ff00; padding: 6px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: 700; box-shadow: 0 0 15px rgba(0, 255, 0, 0.4);">
                            <i class="bi bi-check-circle-fill"></i> IN STOCK
                          </span>
                        @else
                          <span class="badge flex-grow-0" style="background: rgba(255, 0, 0, 0.2); color: #ff0000; border: 1px solid #ff0000; padding: 6px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: 700;">
                            OUT
                          </span>
                        @endif
                      </div>

                      <!-- Action Buttons -->
                      <div class="d-flex gap-2">
                        @auth
                          @if($product->stock > 0)
                            <button class="btn btn-sm flex-grow-1 neon-btn-primary" 
                                    onclick="event.preventDefault(); event.stopPropagation(); addToCartQuick({{ $product->id }});"
                                    style="background: linear-gradient(135deg, rgba(0, 255, 255, 0.2), rgba(255, 0, 255, 0.2)); border: 2px solid #00ffff; color: #00ffff; padding: 10px; border-radius: 12px; font-weight: 800; transition: all 0.3s; box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);">
                              <i class="bi bi-cart-plus-fill"></i> ADD TO CART
                            </button>
                          @endif
                        @else
                          <a href="{{ route('login') }}" 
                             onclick="event.preventDefault(); event.stopPropagation(); window.location.href='{{ route('login') }}';"
                             class="btn btn-sm flex-grow-1 neon-btn-primary" 
                             style="background: linear-gradient(135deg, rgba(0, 255, 255, 0.2), rgba(255, 0, 255, 0.2)); border: 2px solid #00ffff; color: #00ffff; padding: 10px; border-radius: 12px; font-weight: 800; box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);">
                            <i class="bi bi-box-arrow-in-right"></i> LOGIN
                          </a>
                        @endauth
                        
                        <!-- Share -->
                        <div class="dropdown" onclick="event.stopPropagation();">
                          <button class="btn btn-sm neon-btn-secondary" type="button" data-bs-toggle="dropdown" onclick="event.stopPropagation();" style="background: rgba(255, 0, 255, 0.2); border: 2px solid #ff00ff; color: #ff00ff; padding: 10px 15px; border-radius: 12px; box-shadow: 0 0 15px rgba(255, 0, 255, 0.3);">
                            <i class="bi bi-share-fill"></i>
                          </button>
                          <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="background: #1a1a3e; border: 1px solid rgba(0, 255, 255, 0.3); border-radius: 12px;">
                            <li><a class="dropdown-item" href="#" onclick="event.stopPropagation(); shareProductFromHome('{{ $product->id }}', 'whatsapp', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();" style="color: #00ff00;"><i class="bi bi-whatsapp me-2"></i> WhatsApp</a></li>
                            <li><a class="dropdown-item" href="#" onclick="event.stopPropagation(); shareProductFromHome('{{ $product->id }}', 'facebook', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();" style="color: #00ffff;"><i class="bi bi-facebook me-2"></i> Facebook</a></li>
                            <li><a class="dropdown-item" href="#" onclick="event.stopPropagation(); shareProductFromHome('{{ $product->id }}', 'twitter', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();" style="color: #ff00ff;"><i class="bi bi-twitter me-2"></i> Twitter</a></li>
                            <li><hr class="dropdown-divider" style="border-color: rgba(0, 255, 255, 0.2);"></li>
                            <li><a class="dropdown-item" href="#" onclick="event.stopPropagation(); shareProductFromHome('{{ $product->id }}', 'copy', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();" style="color: #ffffff;"><i class="bi bi-link-45deg me-2"></i> Copy Link</a></li>
                          </ul>
                        </div>
                      </div>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Next Button -->
        <button class="slider-nav slider-next" onclick="slideTrending(1)" style="position: absolute; right: -60px; top: 50%; transform: translateY(-50%); z-index: 10; width: 50px; height: 50px; border-radius: 50%; background: rgba(0, 255, 255, 0.1); border: 2px solid #00ffff; color: #00ffff; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s; box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);">
          <i class="bi bi-chevron-right" style="font-size: 1.5rem;"></i>
        </button>
      </div>

      <!-- View All Button -->
      <div class="text-center mt-5">
        <a href="{{ route('buyer.dashboard') }}" class="btn btn-lg px-5 py-3 neon-btn-cta" style="background: linear-gradient(135deg, rgba(0, 255, 255, 0.2), rgba(255, 0, 255, 0.2)); border: 3px solid #00ffff; color: #00ffff; border-radius: 50px; font-weight: 900; font-size: 1.2rem; text-transform: uppercase; letter-spacing: 2px; box-shadow: 0 0 40px rgba(0, 255, 255, 0.5); transition: all 0.4s;">
          <i class="bi bi-grid-3x3-gap-fill me-2"></i> EXPLORE ALL TRENDING
          <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
  </section>



  <!-- Trust Badges & Brand Strip -->
  {{-- <section class="py-4">
    <div class="container">
      <div class="row g-3 trust-badges">
        <div class="col-6 col-md-3">
          <div class="badge">
            <div class="display-6 mb-2" style="color:#ff9900;"><i class="bi bi-shield-lock-fill"></i></div>
            <div class="fw-bold">Secure Payments</div>
            <small>Protected checkout</small>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="badge">
            <div class="display-6 mb-2" style="color:#ff9900;"><i class="bi bi-truck"></i></div>
            <div class="fw-bold">Fast Delivery</div>
            <small>Across the country</small>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="badge">
            <div class="display-6 mb-2" style="color:#ff9900;"><i class="bi bi-arrow-counterclockwise"></i></div>
            <div class="fw-bold">Easy Returns</div>
            <small>7-day return policy</small>
          </div>
        </div>
        <div class="col-6 col-md-3">
          <div class="badge">
            <div class="display-6 mb-2" style="color:#ff9900;"><i class="bi bi-headset"></i></div>
            <div class="fw-bold">24x7 Support</div>
            <small>We’re here to help</small>
          </div>
        </div>
      </div>
      <div class="mt-3 p-3 rounded" style="background:#fff; box-shadow: 0 2px 8px rgba(35,47,62,0.08);">
        <div class="d-flex flex-wrap justify-content-center gap-4 align-items-center">
          <span class="text-muted">Top Brands:</span>
          <span class="fw-semibold" style="color:#232f3e;">BrandOne</span>
          <span class="fw-semibold" style="color:#232f3e;">BrandTwo</span>
          <span class="fw-semibold" style="color:#232f3e;">BrandThree</span>
          <span class="fw-semibold" style="color:#232f3e;">BrandFour</span>
          <span class="fw-semibold" style="color:#232f3e;">BrandFive</span>
        </div>
      </div>
    </div>
  {{-- </section> --}}

  <section class="container my-5">
    <div class="row align-items-center" style="margin-left: 20%">
      @if($lookbookProduct)
        <div class="col-md-6 text-center text-md-start">
          <h2 class="fw-semibold mb-2">LOOKBOOK</h2>
          <p class="text-muted mb-4">
            {{ \Illuminate\Support\Str::limit($lookbookProduct->description ?? 'Carefully curated furniture, well matched in style and looks', 120) }}
          </p>
          <a href="{{ route('product.details', $lookbookProduct->id) }}">
            <button class="btn px-4 py-2" style="background:linear-gradient(135deg,#4A90E2,#2196F3);border:none;color:white;">Explore now</button>
          </a>
        </div>
        <div class="col-md-6 text-center">
          <img
            src="{{ ($lookbookProduct->image || $lookbookProduct->image_data) ? $lookbookProduct->image_url : 'https://via.placeholder.com/450' }}"
            alt="{{ $lookbookProduct->name }}" class="img-fluid rounded"
            style="max-height:450px; object-fit:contain; min-height:400px">
        </div>
      @endif
    </div>
  </section>



  <!-- Chatbot Widget -->

  <x-chatbot-widget />

  <!-- Premium Footer -->
  <footer class="mt-5" style="background:linear-gradient(135deg,#232f3e 60%,#8B4513 100%);color:#fff;padding:48px 0 24px 0;border-radius:24px 24px 0 0;box-shadow:0 -2px 16px rgba(35,47,62,0.12);">
    <div class="container">
      <div class="row g-4 align-items-start">
        <!-- Brand Section -->
        <div class="col-lg-4 col-md-6 text-center text-md-start mb-4 mb-md-0">
          <div class="fw-bold fs-3 mb-2" style="letter-spacing:1px;">grabbaskets</div>
          <div class="mb-3 text-white-50">Premium Shopping Experience</div>
          <div class="d-flex gap-3 justify-content-center justify-content-md-start">
            <a href="https://facebook.com/grabbaskets" target="_blank" class="text-warning footer-social-link"><i class="bi bi-facebook fs-4"></i></a>
            <a href="https://twitter.com/grabbaskets" target="_blank" class="text-warning footer-social-link"><i class="bi bi-twitter fs-4"></i></a>
            <a href="https://instagram.com/grabbaskets" target="_blank" class="text-warning footer-social-link"><i class="bi bi-instagram fs-4"></i></a>
            <a href="https://youtube.com/@grabbaskets" target="_blank" class="text-warning footer-social-link"><i class="bi bi-youtube fs-4"></i></a>
          </div>
        </div>
        
        <!-- Quick Links Section -->
        <div class="col-lg-4 col-md-6 text-center mb-4 mb-lg-0">
          <div class="mb-3 fw-semibold">Quick Links</div>
          <div class="d-flex flex-column gap-2">
            <a href="/" class="footer-link">🏠 Home</a>
            <a href="/products" class="footer-link">🛍️Shop</a>
            <a href="/cart" class="footer-link">🛒 Cart</a>
            <a href="mailto:grabbaskets@gmail.com" class="footer-link">📞 Contact</a>
          </div>
        </div>
        
        <!-- Contact Section -->
        <div class="col-lg-4 col-md-12 text-center text-lg-end">
          <div class="mb-3 fw-semibold">Contact Us</div>
          <div class="contact-info">
            <!-- Email -->
            <div class="contact-item mb-2">
              <a href="mailto:grabbaskets@gmail.com" class="contact-link">
                <i class="bi bi-envelope-fill me-2"></i>
                <span class="d-none d-sm-inline">grabbaskets@gmail.com</span>
                <span class="d-inline d-sm-none">Email Us</span>
              </a>
            </div>
            
            <!-- Phone -->
            <div class="contact-item mb-2">
              <a href="tel:+918300504230" class="contact-link">
                <i class="bi bi-telephone-fill me-2"></i>
                <span>+91 83005 04230</span>
              </a>
            </div>
            
            <!-- WhatsApp -->
            <div class="contact-item mb-2">
              <a href="https://wa.me/918300504230" target="_blank" class="contact-link">
                <i class="bi bi-whatsapp me-2"></i>
                <span class="d-none d-sm-inline">WhatsApp Us</span>
                <span class="d-inline d-sm-none">WhatsApp</span>
              </a>
            </div>
            
            <!-- Location -->
            <div class="contact-item">
              <a href="https://maps.google.com/?q=Theni,Tamil Nadu,India" target="_blank" class="contact-link">
                <i class="bi bi-geo-alt-fill me-2"></i>
                <span>Theni, Tamil Nadu, India</span>
              </a>
            </div>
          </div>
        </div>
      </div>
      
      <hr class="my-4" style="border-color:rgba(255,255,255,0.1);">
      
      <!-- Copyright Section -->
      <div class="row align-items-center">
        <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
          <div class="text-white-50">&copy; {{ date('Y') }} GrabBaskets. All rights reserved.</div>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <div class="d-flex gap-3 justify-content-center justify-content-md-end flex-wrap">
            <a href="mailto:grabbaskets@gmail.com?subject=Privacy Policy Inquiry" class="footer-link-small">Privacy Policy</a>
            <a href="mailto:grabbaskets@gmail.com?subject=Terms and Conditions Inquiry" class="footer-link-small">Terms & Conditions</a>
            <a href="mailto:grabbaskets@gmail.com?subject=Support Request" class="footer-link-small">Support</a>
          </div>
        </div>
      </div>
    </div>
  </footer>

  <!-- Voice Controller (Premium Welcome) -->
  <script>
    function playLoginWelcomeMessage() {
      if ('speechSynthesis' in window) {
  const userName = "{{ auth()->check() ? auth()->user()->name : 'Guest' }}";
  const gender = "{{ auth()->check() ? (auth()->user()->gender ?? 'other') : 'other' }}";
        let welcomeMessage = '';
        if (gender === 'female') {
          welcomeMessage = `Welcome back, beautiful ${userName}! Ready to discover amazing products just for you?`;
        } else if (gender === 'male') {
          welcomeMessage = `Welcome back, ${userName}! Let's find some great deals and products today!`;
        } else {
          welcomeMessage = `Welcome back to GrabBasket, ${userName}! Enjoy your premium shopping experience!`;
        }
        speechSynthesis.cancel();
        const utterance = new SpeechSynthesisUtterance(welcomeMessage);
        utterance.rate = 0.85;
        utterance.pitch = gender === 'female' ? 1.2 : 0.9;
        utterance.volume = 0.8;
        const voices = speechSynthesis.getVoices();
        const preferredVoice = voices.find(voice => voice.lang.includes('en') && (gender === 'female' ? voice.name.toLowerCase().includes('female') : true)) || voices.find(voice => voice.lang.includes('en'));
        if (preferredVoice) utterance.voice = preferredVoice;
        showEnhancedVoiceNotification(welcomeMessage, gender);
        utterance.onend = function() { };
        utterance.onerror = function(event) { 
          // Silently handle speech errors to avoid console spam
          if (event.error !== 'not-allowed') {
            console.log('Speech synthesis not available:', event.error); 
          }
        };
        
        // Only attempt speech if it's allowed
        try {
          speechSynthesis.speak(utterance);
        } catch (error) {
          console.log('Speech synthesis not supported or not allowed');
        }
      }
    }
    function showEnhancedVoiceNotification(message, gender) {
      const notification = document.createElement('div');
      const bgGradient = gender === 'female' ? 'linear-gradient(135deg,#F43397,#ff6b9d)' : gender === 'male' ? 'linear-gradient(135deg,#1CA9C9,#20cfcf)' : 'linear-gradient(135deg,#28a745,#20c997)';
      notification.innerHTML = `<div class="enhanced-voice-notification" style="position:fixed;top:20px;right:20px;z-index:9999;background:${bgGradient};color:white;padding:20px 25px;border-radius:20px;box-shadow:0 12px 35px rgba(0,0,0,0.3);font-weight:600;max-width:350px;animation:slideInEnhanced 0.8s cubic-bezier(0.175,0.885,0.32,1.275);border:3px solid rgba(255,255,255,0.3);"><div style="display:flex;align-items:center;gap:15px;"><div style="font-size:2rem;animation:voiceIconPulse 1s ease-in-out infinite;">${gender==='female'?'👸':gender==='male'?'🤴':'🎤'}</div><div><div style="font-size:1rem;margin-bottom:8px;opacity:0.9;">🔊 Personal Welcome Message</div><div style="font-size:0.85rem;opacity:0.8;line-height:1.3;">${message}</div></div></div></div>`;
      document.body.appendChild(notification);
      setTimeout(()=>{notification.style.animation='slideOutEnhanced 0.8s ease-in forwards';setTimeout(()=>notification.remove(),800);},6000);
    }
    function initializeVoiceWelcome() {
  const justLoggedIn = "{{ session('just_logged_in') ? 'true' : 'false' }}" === 'true';
  const isAuthenticated = "{{ auth()->check() ? 'true' : 'false' }}" === 'true';
      if (justLoggedIn || isAuthenticated) {
        setTimeout(() => { playLoginWelcomeMessage(); }, 2000);
      }
    }
    document.addEventListener('DOMContentLoaded', initializeVoiceWelcome);
  </script>

  <style>
    .enhanced-voice-notification { font-family:inherit; }
    @keyframes slideInEnhanced { from { opacity:0;transform:translateX(100%) scale(0.8);} to { opacity:1;transform:translateX(0) scale(1);} }
    @keyframes slideOutEnhanced { from { opacity:1;transform:translateX(0) scale(1);} to { opacity:0;transform:translateX(100%) scale(0.8);} }
    @keyframes voiceIconPulse { 0%,100%{transform:scale(1);} 50%{transform:scale(1.2);} }
  </style>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Trending Slider Navigation
    function slideTrending(direction) {
      const grid = document.getElementById('trendingGrid');
      if (!grid) return;
      
      const scrollAmount = 400;
      grid.scrollBy({
        left: scrollAmount * direction,
        behavior: 'smooth'
      });
    }
  </script>
  <script>
    function scrollShelf(key, dir) {
      const el = document.getElementById('shelf-' + key);
      if (!el) return;
      const amount = 300 * (dir || 1);
      el.scrollBy({ left: amount, behavior: 'smooth' });
    }

    const carousel = new bootstrap.Carousel('#diwaliCarousel', {
      interval: 4000,  // changes every 4 seconds
      ride: 'carousel'
    });

    // Share Functions for Homepage
    function shareProductFromHome(productId, platform, productName, price) {
      const baseUrl = window.location.origin;
      const productUrl = `${baseUrl}/product/${productId}`;
      const text = `Check out this amazing product: ${productName} - ₹${price} on grabbasket!`;
      
      switch(platform) {
          case 'whatsapp':
              const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(text + ' ' + productUrl)}`;
              window.open(whatsappUrl, '_blank');
              break;
              
          case 'facebook':
              const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(productUrl)}`;
              window.open(facebookUrl, '_blank', 'width=600,height=400');
              break;
              
          case 'twitter':
              const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(productUrl)}`;
              window.open(twitterUrl, '_blank', 'width=600,height=400');
              break;
              
          case 'copy':
              navigator.clipboard.writeText(productUrl).then(function() {
                  // Show success feedback
                  const dropdown = event.target.closest('.dropdown');
                  const btn = dropdown.querySelector('button');
                  const originalHtml = btn.innerHTML;
                  btn.innerHTML = '<i class="bi bi-check text-success"></i>';
                  
                  setTimeout(function() {
                      btn.innerHTML = originalHtml;
                  }, 2000);
              }).catch(function(err) {
                  alert('Failed to copy link. Please copy manually: ' + productUrl);
              });
              break;
      }
    }

    // Prevent back button after logout
    window.addEventListener('load', function() {
      if (performance.navigation.type == performance.navigation.TYPE_BACK_FORWARD) {
        window.location.replace('/');
      }
    });

    // Handle logout forms
    document.addEventListener('DOMContentLoaded', function() {
      // Initialize Bootstrap carousel properly
      if (typeof bootstrap !== 'undefined' && bootstrap.Carousel) {
        const carouselElement = document.getElementById('heroCarousel');
        if (carouselElement) {
          new bootstrap.Carousel(carouselElement, {
            keyboard: true,
            pause: 'hover',
            wrap: true,
            interval: 5000
          });
        }
      }

      const logoutForms = document.querySelectorAll('form[action*="logout"]');
      logoutForms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
          localStorage.setItem('logged_out', 'true');
        });
      });

      // Check if user just logged out
      if (localStorage.getItem('logged_out') === 'true') {
        localStorage.removeItem('logged_out');
        // Clear browser cache
        if ('caches' in window) {
          caches.keys().then(function(names) {
            names.forEach(function(name) {
              caches.delete(name);
            });
          });
        }
      }
    });

    // Enhanced Mega Menu Functionality
    document.addEventListener('DOMContentLoaded', function() {
      const navbar = document.getElementById('mainNavbar');
      const megaMenuToggle = document.getElementById('shopMegaMenu');
      const megaMenu = document.querySelector('.mega-menu-wrapper');
      const genderTabs = document.querySelectorAll('.gender-tab');
      const categoryCards = document.querySelectorAll('.mega-category-card');

      // Navbar scroll effect
      window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      });

      // Mega menu hover functionality
      let hoverTimeout;
      
      megaMenuToggle.addEventListener('mouseenter', function() {
        clearTimeout(hoverTimeout);
        megaMenu.classList.add('show');
      });

      megaMenuToggle.parentElement.addEventListener('mouseleave', function() {
        hoverTimeout = setTimeout(() => {
          megaMenu.classList.remove('show');
        }, 300);
      });

      megaMenu.addEventListener('mouseenter', function() {
        clearTimeout(hoverTimeout);
      });

      megaMenu.addEventListener('mouseleave', function() {
        hoverTimeout = setTimeout(() => {
          megaMenu.classList.remove('show');
        }, 300);
      });

      // Gender filter functionality
      function filterMegaCategories(selectedGender) {
        categoryCards.forEach(function(card) {
          const cardGender = card.getAttribute('data-gender');
          
          if (selectedGender === 'all' || cardGender === selectedGender || cardGender === 'all') {
            card.style.display = 'block';
            card.style.animation = 'fadeInUp 0.4s ease';
          } else {
            card.style.display = 'none';
          }
        });

        // Update active tab
        genderTabs.forEach(function(tab) {
          tab.classList.remove('active');
        });
        document.querySelector(`.gender-tab[data-gender="${selectedGender}"]`).classList.add('active');
      }

      // Add click events to gender tabs
      genderTabs.forEach(function(tab) {
        tab.addEventListener('click', function(e) {
          e.preventDefault();
          const selectedGender = this.getAttribute('data-gender');
          filterMegaCategories(selectedGender);
        });
      });

      // Close mega menu on outside click
      document.addEventListener('click', function(e) {
        if (!megaMenuToggle.parentElement.contains(e.target)) {
          megaMenu.classList.remove('show');
        }
      });

      // Mobile mega menu handling
      if (window.innerWidth <= 768) {
        megaMenuToggle.addEventListener('click', function(e) {
          e.preventDefault();
          megaMenu.classList.toggle('show');
        });
      }

      // Animate category cards on load
      setTimeout(() => {
        categoryCards.forEach((card, index) => {
          setTimeout(() => {
            card.style.animation = 'fadeInUp 0.4s ease forwards';
          }, index * 100);
        });
      }, 300);

      // Fix mega menu link interactions
      const megaSubcategoryLinks = document.querySelectorAll('.mega-subcategory-link');
      megaSubcategoryLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
          // Allow the link to navigate naturally
          // Add a small delay to allow the click to register
          setTimeout(() => {
            megaMenu.classList.remove('show');
          }, 100);
        });
      });

      // Interactive emoji effects
      const categoryEmojis = document.querySelectorAll('.mega-category-emoji');
      categoryEmojis.forEach(function(emoji) {
        emoji.addEventListener('click', function(e) {
          e.stopPropagation(); // Prevent card click
          
          // Add fun interaction
          this.style.animation = 'emoji-dance 0.8s ease-in-out';
          
          // Reset animation after completion
          setTimeout(() => {
            this.style.animation = '';
          }, 800);
        });
      });

      // Add sparkle effect on user greeting hover
      const userGreeting = document.querySelector('.user-greeting-interactive');
      // Simple hover effect for user greeting
      if (userGreeting) {
        userGreeting.addEventListener('mouseenter', function() {
          this.style.transform = 'scale(1.05)';
        });
        userGreeting.addEventListener('mouseleave', function() {
          this.style.transform = 'scale(1)';
        });
      }

    });
  </script>

  <!-- Floating Menu JavaScript -->
  <script>
    // Initialize FAB visibility state from localStorage
    document.addEventListener('DOMContentLoaded', function() {
      const fabHidden = localStorage.getItem('fabHidden') === 'true';
      const fabHideBtn = document.getElementById('fabHideBtn');
      
      // Apply saved state
      if (fabHidden && window.innerWidth <= 768) {
        hideFloatingButton(false); // Don't save again, just apply state
      }
      
      // Show hide button on mobile (always visible on mobile)
      if (window.innerWidth <= 768) {
        fabHideBtn.style.display = 'block';
      }
      
      // Add click events to floating category cards
      const floatingCategoryCards = document.querySelectorAll('.floating-category-card');
      floatingCategoryCards.forEach(card => {
        card.addEventListener('click', function() {
          const categoryId = this.getAttribute('data-category-id');
          const categoryName = this.getAttribute('data-category-name');
          showCategorySubcategories(categoryId, categoryName);
        });
      });

      // Desktop: show hide button on hover
      const fabContainer = document.getElementById('floatingActionsContainer');
      
      if (window.innerWidth > 768) {
        fabContainer.addEventListener('mouseenter', () => {
          fabHideBtn.style.display = 'block';
        });
        
        fabContainer.addEventListener('mouseleave', () => {
          fabHideBtn.style.display = 'none';
        });
      }
    });

    function toggleFloatingMenu() {
      const menu = document.getElementById('floatingMenu');
      const subcategoryArea = document.getElementById('subcategoryArea');
      if (menu.style.display === 'none' || menu.style.display === '') {
        menu.style.display = 'block';
        subcategoryArea.style.display = 'none';
      } else {
        menu.style.display = 'none';
      }
    }

    function hideFloatingButton(saveState = true) {
      const fabContainer = document.getElementById('floatingActionsContainer');
      const showBtn = document.getElementById('showFabBtn');
      const floatingMenu = document.getElementById('floatingMenu');
      
      // Close popup if open
      floatingMenu.style.display = 'none';
      
      // Hide FAB
      fabContainer.style.transform = 'translateX(150px)';
      fabContainer.style.opacity = '0';
      
      // Show the show button
      setTimeout(() => {
        fabContainer.style.display = 'none';
        showBtn.style.display = 'flex';
      }, 300);
      
      // Save state to localStorage (mobile only)
      if (saveState && window.innerWidth <= 768) {
        localStorage.setItem('fabHidden', 'true');
      }
    }

    function showFloatingButton() {
      const fabContainer = document.getElementById('floatingActionsContainer');
      const showBtn = document.getElementById('showFabBtn');
      
      // Hide show button
      showBtn.style.display = 'none';
      
      // Show FAB with animation
      fabContainer.style.display = 'block';
      setTimeout(() => {
        fabContainer.style.transform = 'translateX(0)';
        fabContainer.style.opacity = '1';
      }, 10);
      
      // Save state to localStorage (mobile only)
      if (window.innerWidth <= 768) {
        localStorage.setItem('fabHidden', 'false');
      }
    }

    function showCategorySubcategories(categoryId, categoryName) {
      // Show subcategory area
      const subcategoryArea = document.getElementById('subcategoryArea');
      const subcategoryHeader = document.getElementById('subcategoryHeader');
      const subcategoryList = document.getElementById('subcategoryList');
      
      subcategoryHeader.textContent = categoryName + ' Subcategories';
      subcategoryArea.style.display = 'block';
      
      // Clear previous subcategories
      subcategoryList.innerHTML = '';
      
      // Find category data
      const categories = <?php echo json_encode($categories ?? []); ?>;
      const category = categories.find(cat => cat.id == categoryId);
      
      if (category && category.subcategories && category.subcategories.length > 0) {
        category.subcategories.slice(0, 12).forEach(subcategory => {
          const link = document.createElement('a');
          link.href = `/buyer/subcategory/${subcategory.id}/products`;
          link.textContent = subcategory.name;
          link.style.cssText = 'font-size:0.8rem;padding:4px 8px;border-radius:6px;color:#654321;background:rgba(139,69,19,0.04);text-decoration:none;transition:background 0.2s;';
          link.addEventListener('mouseenter', () => link.style.background = 'rgba(139,69,19,0.08)');
          link.addEventListener('mouseleave', () => link.style.background = 'rgba(139,69,19,0.04)');
          subcategoryList.appendChild(link);
        });
      } else {
        subcategoryList.innerHTML = '<span style="color:#666;font-size:0.8rem;">No subcategories available</span>';
      }
    }

    // Close floating menu when clicking outside
    document.addEventListener('click', function(event) {
      const floatingMenu = document.getElementById('floatingMenu');
      const fabButton = document.querySelector('.fab-main');
      
      if (floatingMenu && !floatingMenu.contains(event.target) && !fabButton.contains(event.target)) {
        floatingMenu.style.display = 'none';
      }
    });
  </script>

  @if(session('tamil_greeting') && auth()->check())
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Wait for voices to be loaded
      if (speechSynthesis.getVoices().length === 0) {
        speechSynthesis.addEventListener('voiceschanged', function() {
          setTimeout(() => {
            playTamilGreeting('{{ auth()->user()->name }}');
          }, 1000);
        });
      } else {
        setTimeout(() => {
          playTamilGreeting('{{ auth()->user()->name }}');
        }, 1000);
      }
    });
  </script>
  @endif

  <!-- Wishlist Functionality -->
  <script>
    // Setup CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Quick Add to Cart Function
    function addToCartQuick(productId) {
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ 
                product_id: productId,
                quantity: 1
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showQuickToast('✅ Added to cart!', 'success');
            } else {
                showQuickToast('❌ ' + (data.message || 'Failed to add'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showQuickToast('❌ Please try again', 'error');
        });
    }

    // Quick Toast Notification
    function showQuickToast(message, type) {
        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'linear-gradient(135deg, #06D6A0, #00B88F)' : 'linear-gradient(135deg, #ff3366, #ff6b6b)';
        toast.className = 'quick-toast';
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: ${bgColor};
            color: white;
            padding: 15px 25px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            z-index: 99999;
            animation: slideInToast 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            font-weight: 700;
            font-size: 1rem;
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.animation = 'slideOutToast 0.4s ease';
            setTimeout(() => toast.remove(), 400);
        }, 2500);
    }

    // Add animation styles
    const toastStyle = document.createElement('style');
    toastStyle.textContent = `
        @keyframes slideInToast {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOutToast {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(toastStyle);
    
    // Initialize wishlist hearts
    function initWishlistHearts() {
        document.querySelectorAll('.wishlist-heart-btn').forEach(button => {
            const productId = button.getAttribute('data-product-id');
            
            // Check if product is in wishlist
            checkWishlistStatus(productId, button);
            
            // Add click event
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                toggleWishlist(productId, button);
            });
        });
    }
    
    // Check wishlist status
    function checkWishlistStatus(productId, button) {
        fetch(`/wishlist/check/${productId}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            updateHeartIcon(button, data.in_wishlist);
        })
        .catch(error => console.error('Error checking wishlist status:', error));
    }
    
    // Toggle wishlist
    function toggleWishlist(productId, button) {
        // Check if user is authenticated
        @guest
            showWishlistToast('Please login to add items to wishlist');
            setTimeout(() => {
                window.location.href = '{{ route('login') }}';
            }, 1500);
            return;
        @endguest

        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => {
            if (!response.ok) {
                if (response.status === 401) {
                    throw new Error('Please login to add items to wishlist');
                }
                throw new Error('Failed to update wishlist');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateHeartIcon(button, data.in_wishlist);
                
                // Show success message
                showWishlistToast(data.message);
                
                // Update mobile nav wishlist badge if exists
                updateWishlistBadge();
            } else {
                showWishlistToast(data.message || 'Failed to update wishlist');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showWishlistToast(error.message || 'An error occurred. Please try again.');
        });
    }
    
    // Update wishlist badge count in mobile nav
    function updateWishlistBadge() {
        fetch('/wishlist/count', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const wishlistNavItem = document.querySelector('a[href="{{ route('wishlist.index') }}"]');
            if (wishlistNavItem) {
                let badge = wishlistNavItem.querySelector('.badge');
                if (data.count > 0) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.className = 'badge';
                        wishlistNavItem.appendChild(badge);
                    }
                    badge.textContent = data.count;
                } else if (badge) {
                    badge.remove();
                }
            }
        })
        .catch(error => console.error('Error updating wishlist badge:', error));
    }
    
    // Update heart icon
    function updateHeartIcon(button, inWishlist) {
        const icon = button.querySelector('.wishlist-icon');
        if (inWishlist) {
            icon.classList.remove('bi-heart');
            icon.classList.add('bi-heart-fill');
            icon.style.color = '#e74c3c';
            button.setAttribute('title', 'Remove from Wishlist');
        } else {
            icon.classList.remove('bi-heart-fill');
            icon.classList.add('bi-heart');
            icon.style.color = '#ccc';
            button.setAttribute('title', 'Add to Wishlist');
        }
    }
    
    // Show toast notification
    function showWishlistToast(message) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = 'wishlist-toast';
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(135deg, #FF6B00, #FFD700);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(255, 107, 0, 0.4);
            z-index: 9999;
            animation: slideIn 0.3s ease;
            font-weight: 600;
        `;
        
        document.body.appendChild(toast);
        
        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Add animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
    
    // Initialize on page load
    document.addEventListener('DOMContentLoaded', initWishlistHearts);
  </script>

  <!-- Mobile Bottom Navigation (Blinkit/Zepto Style) -->
  <div class="mobile-bottom-nav">
    <a href="{{ route('home') }}" class="mobile-nav-item active">
      <i class="bi bi-house-fill"></i>
      <span>Home</span>
    </a>
    <a href="{{ route('products.index') }}" class="mobile-nav-item">
      <i class="bi bi-grid-3x3-gap-fill"></i>
      <span>Categories</span>
    </a>
    <a href="{{ route('cart.index') }}" class="mobile-nav-item">
      <i class="bi bi-cart3"></i>
      <span>Cart</span>
      @if(session('cart') && count(session('cart')) > 0)
        <span class="badge">{{ count(session('cart')) }}</span>
      @endif
    </a>
    @auth
      <a href="{{ route('wishlist.index') }}" class="mobile-nav-item">
        <i class="bi bi-heart"></i>
        <span>Wishlist</span>
        @php
          $wishlistCount = \App\Models\Wishlist::where('user_id', auth()->id())->count();
        @endphp
        @if($wishlistCount > 0)
          <span class="badge">{{ $wishlistCount }}</span>
        @endif
      </a>
    @else
      <a href="{{ route('login') }}" class="mobile-nav-item">
        <i class="bi bi-heart"></i>
        <span>Wishlist</span>
      </a>
    @endauth
    @auth
      <a href="{{ route('profile.show') }}" class="mobile-nav-item">
        <i class="bi bi-person-circle"></i>
        <span>Account</span>
      </a>
    @else
      <a href="{{ route('login') }}" class="mobile-nav-item">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>Login</span>
      </a>
    @endauth
  </div>

  <!-- Location Detection Modal -->
  <div class="location-modal-overlay" id="locationModalOverlay" onclick="closeLocationModal()"></div>
  <div class="location-modal" id="locationModal">
    <div class="location-modal-header">
      <h3><i class="bi bi-geo-alt-fill text-success me-2"></i>Select Location</h3>
      <button class="location-modal-close" onclick="closeLocationModal()">
        <i class="bi bi-x"></i>
      </button>
    </div>
    <div class="location-modal-body">
      <!-- Auto Detect Button -->
      <button class="location-detect-btn" onclick="detectCurrentLocation()">
        <i class="bi bi-crosshair"></i>
        <span id="detectButtonText">Detect My Location</span>
      </button>

      <div class="location-divider">
        <span>OR</span>
      </div>

      <!-- Search Input -->
      <input 
        type="text" 
        class="location-search-input" 
        id="locationSearchInput"
        placeholder="Search for your area, street name..."
        autocomplete="off"
      >

      <!-- Loading State -->
      <div class="location-loading" id="locationLoading" style="display: none;">
        <i class="bi bi-arrow-repeat"></i>
        <p>Detecting your location...</p>
      </div>

      <!-- Current Location Display -->
      <div class="current-location-display" id="currentLocationDisplay">
        <div style="display: flex; align-items: start;">
          <i class="bi bi-geo-alt-fill location-icon"></i>
          <div style="flex: 1;">
            <div class="current-location-text">Current Location</div>
            <div class="current-location-address" id="detectedAddress">Loading...</div>
            <div class="location-accuracy">
              <i class="bi bi-check-circle-fill me-1"></i>
              <span id="accuracyText">High accuracy</span>
            </div>
          </div>
        </div>
        <button 
          class="btn btn-success mt-3 w-100"
          onclick="confirmLocation()"
          style="border-radius: 8px; padding: 12px; font-weight: 600;"
        >
          Confirm Location
        </button>
      </div>
    </div>
  </div>

  <!-- Location Detection JavaScript -->
  <script>
    // Global variables
    let currentLocationData = {
      latitude: null,
      longitude: null,
      address: '',
      area: '',
      city: '',
      state: '',
      pincode: '',
      country: ''
    };

    let googleMapsLoaded = false;
    let autocompleteService = null;

    // Wait for Google Maps to load
    function initGoogleMaps() {
      if (typeof google !== 'undefined' && google.maps) {
        googleMapsLoaded = true;
        console.log('✅ Google Maps loaded successfully');
        
        // Initialize autocomplete service
        autocompleteService = new google.maps.places.AutocompleteService();
        
        // Auto-detect location on page load
        autoDetectLocation();
      } else {
        console.log('⏳ Waiting for Google Maps to load...');
        setTimeout(initGoogleMaps, 200);
      }
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
      initGoogleMaps();
      
      // Check if location is already saved in localStorage
      const savedLocation = localStorage.getItem('userLocation');
      if (savedLocation) {
        try {
          currentLocationData = JSON.parse(savedLocation);
          updateLocationDisplay(currentLocationData.area || currentLocationData.address);
        } catch (e) {
          console.error('Error parsing saved location:', e);
        }
      }
    });

    // Auto-detect location on page load (silent)
    function autoDetectLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function(position) {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            
            // Reverse geocode silently
            reverseGeocode(lat, lng, function(address) {
              currentLocationData = {
                latitude: lat,
                longitude: lng,
                address: address.fullAddress,
                area: address.area,
                city: address.city,
                state: address.state,
                pincode: address.pincode,
                country: address.country
              };
              
              // Save to localStorage
              localStorage.setItem('userLocation', JSON.stringify(currentLocationData));
              
              // Update display
              updateLocationDisplay(address.area || address.city);
              
              console.log('✅ Location detected:', address.area, address.city);
            });
          },
          function(error) {
            console.log('📍 Location detection skipped (user may have denied)');
            document.getElementById('locationText').textContent = 'Select Location';
          },
          { enableHighAccuracy: true, timeout: 10000, maximumAge: 300000 }
        );
      }
    }

    // Open location modal
    function openLocationModal() {
      document.getElementById('locationModal').classList.add('active');
      document.getElementById('locationModalOverlay').classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    // Close location modal
    function closeLocationModal() {
      document.getElementById('locationModal').classList.remove('active');
      document.getElementById('locationModalOverlay').classList.remove('active');
      document.body.style.overflow = '';
    }

    // Detect current location (manual)
    function detectCurrentLocation() {
      if (!navigator.geolocation) {
        alert('Geolocation is not supported by your browser');
        return;
      }

      // Show loading
      document.getElementById('detectButtonText').textContent = 'Detecting...';
      document.getElementById('locationLoading').style.display = 'block';
      document.getElementById('currentLocationDisplay').classList.remove('active');

      navigator.geolocation.getCurrentPosition(
        function(position) {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          const accuracy = position.coords.accuracy;

          console.log('📍 Location detected:', lat, lng, 'Accuracy:', accuracy + 'm');

          // Reverse geocode to get address
          reverseGeocode(lat, lng, function(address) {
            // Hide loading
            document.getElementById('locationLoading').style.display = 'none';
            document.getElementById('detectButtonText').textContent = 'Detect My Location';

            // Update current location display
            document.getElementById('detectedAddress').textContent = address.fullAddress;
            
            // Update accuracy text
            let accuracyLevel = accuracy < 50 ? 'High accuracy' : accuracy < 200 ? 'Medium accuracy' : 'Low accuracy';
            document.getElementById('accuracyText').textContent = accuracyLevel;
            
            // Show location display
            document.getElementById('currentLocationDisplay').classList.add('active');

            // Store location data
            currentLocationData = {
              latitude: lat,
              longitude: lng,
              address: address.fullAddress,
              area: address.area,
              city: address.city,
              state: address.state,
              pincode: address.pincode,
              country: address.country
            };
          });
        },
        function(error) {
          document.getElementById('locationLoading').style.display = 'none';
          document.getElementById('detectButtonText').textContent = 'Detect My Location';
          
          let errorMessage = 'Unable to detect location. ';
          switch(error.code) {
            case error.PERMISSION_DENIED:
              errorMessage += 'Please allow location access in your browser settings.';
              break;
            case error.POSITION_UNAVAILABLE:
              errorMessage += 'Location information is unavailable.';
              break;
            case error.TIMEOUT:
              errorMessage += 'Location request timed out.';
              break;
            default:
              errorMessage += 'An unknown error occurred.';
          }
          alert(errorMessage);
          console.error('Geolocation error:', error);
        },
        { 
          enableHighAccuracy: true, 
          timeout: 10000, 
          maximumAge: 0 
        }
      );
    }

    // Reverse geocode using Google Maps API
    function reverseGeocode(lat, lng, callback) {
      if (!googleMapsLoaded) {
        console.error('Google Maps not loaded yet');
        return;
      }

      const apiKey = '{{ config("services.google.maps_api_key") }}';
      const geocodeUrl = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=${apiKey}`;

      fetch(geocodeUrl)
        .then(response => response.json())
        .then(data => {
          if (data.status === 'OK' && data.results.length > 0) {
            const result = data.results[0];
            
            // Extract address components
            let area = '';
            let city = '';
            let state = '';
            let pincode = '';
            let country = '';
            
            result.address_components.forEach(component => {
              if (component.types.includes('sublocality') || component.types.includes('sublocality_level_1')) {
                area = component.long_name;
              }
              if (component.types.includes('locality')) {
                city = component.long_name;
              }
              if (component.types.includes('administrative_area_level_1')) {
                state = component.long_name;
              }
              if (component.types.includes('postal_code')) {
                pincode = component.long_name;
              }
              if (component.types.includes('country')) {
                country = component.long_name;
              }
            });

            const fullAddress = result.formatted_address;
            
            callback({
              fullAddress: fullAddress,
              area: area,
              city: city,
              state: state,
              pincode: pincode,
              country: country
            });
          } else {
            console.error('Geocoding failed:', data.status);
            callback({
              fullAddress: `${lat.toFixed(4)}, ${lng.toFixed(4)}`,
              area: 'Unknown',
              city: 'Unknown',
              state: '',
              pincode: '',
              country: ''
            });
          }
        })
        .catch(error => {
          console.error('Geocoding error:', error);
          callback({
            fullAddress: `${lat.toFixed(4)}, ${lng.toFixed(4)}`,
            area: 'Unknown',
            city: 'Unknown',
            state: '',
            pincode: '',
            country: ''
          });
        });
    }

    // Confirm location
    function confirmLocation() {
      if (!currentLocationData.latitude || !currentLocationData.longitude) {
        alert('Please detect your location first');
        return;
      }

      // Save to localStorage
      localStorage.setItem('userLocation', JSON.stringify(currentLocationData));

      // Update navbar display
      updateLocationDisplay(currentLocationData.area || currentLocationData.city);

      // Close modal
      closeLocationModal();

      // Show success message
      showToast('✅ Location saved successfully!');

      console.log('✅ Location confirmed:', currentLocationData);
    }

    // Update location display in navbar
    function updateLocationDisplay(locationText) {
      const locationElement = document.getElementById('locationText');
      const locationLabel = document.getElementById('locationLabel');
      
      if (locationElement && locationText) {
        locationElement.textContent = locationText;
        locationLabel.textContent = 'Delivery in 10 mins';
        
        // Add animation
        locationElement.style.animation = 'fadeInUp 0.5s ease';
      }

      // Update mobile location bar
      const mobileLocationText = document.getElementById('mobileLocationText');
      const mobileLocationLabel = document.getElementById('mobileLocationLabel');
      
      if (mobileLocationText && locationText) {
        mobileLocationText.textContent = locationText;
        mobileLocationLabel.textContent = 'Delivery in 10 mins';
        
        // Add animation
        mobileLocationText.style.animation = 'fadeInUp 0.5s ease';
      }
    }

    // Close mobile login card
    function closeMobileLoginCard() {
      const card = document.getElementById('mobileLoginCard');
      if (card) {
        card.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
          card.style.display = 'none';
        }, 300);
      }
    }

    // Show toast notification
    function showToast(message) {
      // Create toast element
      const toast = document.createElement('div');
      toast.style.cssText = `
        position: fixed;
        bottom: 80px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(12, 131, 31, 0.95);
        color: white;
        padding: 14px 24px;
        border-radius: 8px;
        font-weight: 600;
        z-index: 10000;
        animation: slideInUp 0.3s ease;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
      `;
      toast.textContent = message;
      
      document.body.appendChild(toast);
      
      // Remove after 3 seconds
      setTimeout(() => {
        toast.style.animation = 'fadeOut 0.3s ease';
        setTimeout(() => {
          document.body.removeChild(toast);
        }, 300);
      }, 3000);
    }

    // Search input functionality (basic)
    document.addEventListener('DOMContentLoaded', function() {
      const searchInput = document.getElementById('locationSearchInput');
      if (searchInput) {
        searchInput.addEventListener('input', function(e) {
          const query = e.target.value.trim();
          if (query.length > 2) {
            // TODO: Implement Places Autocomplete
            console.log('Searching for:', query);
          }
        });
      }
    });
  </script>

</body>
</html>
