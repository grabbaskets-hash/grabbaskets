<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>grabbasket - Home</title>
  <link rel="icon" type="image/jpeg" href="{{ asset('asset/images/grabbaskets.jpg') }}">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Dark mode toggle removed -->
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f3f4f6;
      overflow-x: hidden;
    }

    /* Modern Navbar Styling */
    .navbar {
      background: linear-gradient(135deg, #f5f5dc 0%, #faebd7 50%, #f5deb3 100%);
      backdrop-filter: blur(10px);
      box-shadow: 0 4px 20px rgba(139, 69, 19, 0.1);
      border-bottom: 3px solid rgba(139, 69, 19, 0.1);
      position: sticky;
      top: 0;
      z-index: 1030;
      transition: all 0.3s ease;
    }

    .navbar.scrolled {
      background: linear-gradient(135deg, rgba(245, 245, 220, 0.95) 0%, rgba(250, 235, 215, 0.95) 50%, rgba(245, 222, 179, 0.95) 100%);
      backdrop-filter: blur(15px);
      box-shadow: 0 6px 30px rgba(139, 69, 19, 0.15);
    }

    .navbar-brand {
      font-weight: 800;
      font-size: 1.8rem;
      background: linear-gradient(45deg, #8B4513, #D2691E, #8B4513);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: 1px;
      text-shadow: 0 2px 4px rgba(139, 69, 19, 0.2);
      transition: all 0.3s ease;
    }

    .navbar-brand:hover {
      transform: scale(1.05);
      filter: brightness(1.1);
    }

    .nav-link {
      color: #8B4513 !important;
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
      background: linear-gradient(90deg, transparent, rgba(139, 69, 19, 0.1), transparent);
      transition: left 0.5s ease;
    }

    .nav-link:hover::before {
      left: 100%;
    }

    .nav-link:hover {
      background: rgba(139, 69, 19, 0.08);
      color: #654321 !important;
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(139, 69, 19, 0.2);
    }

    .navbar-toggler {
      border: 2px solid #8B4513;
      border-radius: 10px;
      padding: 6px 10px;
      transition: all 0.3s ease;
    }

    .navbar-toggler:hover {
      background: rgba(139, 69, 19, 0.1);
      transform: scale(1.05);
    }

    .navbar-toggler-icon {
      background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='%23654321' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
    }

    /* Search Bar Enhancement */
    .search-form {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 25px;
      box-shadow: 0 4px 15px rgba(139, 69, 19, 0.1);
      border: 2px solid rgba(139, 69, 19, 0.1);
      overflow: hidden;
      transition: all 0.3s ease;
    }

    .search-form:focus-within {
      transform: scale(1.02);
      box-shadow: 0 6px 25px rgba(139, 69, 19, 0.2);
      border-color: rgba(139, 69, 19, 0.3);
    }

    .search-form .form-control {
      border: none;
      background: transparent;
      padding: 12px 20px;
      font-size: 14px;
      color: #8B4513;
    }

    .search-form .form-control:focus {
      box-shadow: none;
      background: transparent;
    }

    .search-form .btn {
      background: linear-gradient(45deg, #8B4513, #A0522D);
      border: none;
      color: white;
      padding: 12px 24px;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .search-form .btn:hover {
      background: linear-gradient(45deg, #A0522D, #8B4513);
      transform: scale(1.05);
    }

    /* Advanced Mega Menu Styling */
    .mega-menu-wrapper {
      position: absolute;
      top: 100%;
      left: 50%;
      transform: translateX(-50%);
      width: 95vw;
      max-width: 1400px;
      background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
      backdrop-filter: blur(20px);
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(139, 69, 19, 0.15);
      border: 1px solid rgba(139, 69, 19, 0.1);
      opacity: 0;
      visibility: hidden;
      transform: translateX(-50%) translateY(-20px);
      transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
      z-index: 1040;
      overflow: hidden;
    }

    .mega-menu-wrapper.show {
      opacity: 1;
      visibility: visible;
      transform: translateX(-50%) translateY(0);
    }

    .mega-menu-wrapper::before {
      content: '';
      position: absolute;
      top: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 20px;
      height: 20px;
      background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
      border: 1px solid rgba(139, 69, 19, 0.1);
      border-bottom: none;
      border-right: none;
      transform: translateX(-50%) rotate(45deg);
    }

    .mega-menu-content {
      padding: 30px;
      position: relative;
      z-index: 1;
    }

    .mega-menu-header {
      text-align: center;
      margin-bottom: 25px;
      padding-bottom: 20px;
      border-bottom: 2px solid rgba(139, 69, 19, 0.1);
    }

    .mega-menu-title {
      font-size: 1.8rem;
      font-weight: 800;
      background: linear-gradient(45deg, #8B4513, #D2691E);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin-bottom: 8px;
    }

    .mega-menu-subtitle {
      color: #8B4513;
      font-size: 14px;
      opacity: 0.8;
    }

    /* Gender Filter Tabs */
    .gender-filter-tabs {
      display: flex;
      gap: 8px;
      margin-bottom: 25px;
      background: rgba(139, 69, 19, 0.05);
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
      background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
      border-radius: 16px;
      padding: 20px;
      border: 1px solid rgba(139, 69, 19, 0.1);
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
      background: linear-gradient(45deg, #8B4513, #D2691E);
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }

    .mega-category-card:hover::before {
      transform: scaleX(1);
    }

    .mega-category-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(139, 69, 19, 0.15);
      border-color: rgba(139, 69, 19, 0.2);
    }

    .mega-category-header {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 15px;
      padding-bottom: 12px;
      border-bottom: 1px solid rgba(139, 69, 19, 0.1);
    }

    .mega-category-emoji {
      font-size: 24px;
      width: 45px;
      height: 45px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(45deg, rgba(139, 69, 19, 0.1), rgba(210, 105, 30, 0.1));
      border-radius: 12px;
      transition: all 0.3s ease;
    }

    .mega-category-card:hover .mega-category-emoji {
      transform: scale(1.1) rotate(5deg);
      background: linear-gradient(45deg, rgba(139, 69, 19, 0.2), rgba(210, 105, 30, 0.2));
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

    .user-name-bounce {
      animation: gentle-bounce 2s ease-in-out infinite;
    }

    .greeting-emoji {
      font-size: 1.2em;
      animation: rotate-sparkle 3s ease-in-out infinite;
      transition: all 0.3s ease;
    }

    .user-greeting-interactive:hover .greeting-emoji {
      animation: excited-bounce 0.6s ease-in-out;
      transform: scale(1.3);
    }

    @keyframes gentle-bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-2px); }
    }

    @keyframes rotate-sparkle {
      0%, 100% { transform: rotate(0deg); }
      25% { transform: rotate(10deg); }
      75% { transform: rotate(-10deg); }
    }

    @keyframes excited-bounce {
      0%, 100% { transform: scale(1.3) translateY(0); }
      25% { transform: scale(1.4) translateY(-4px); }
      50% { transform: scale(1.5) translateY(-2px); }
      75% { transform: scale(1.4) translateY(-4px); }
    }

    /* Enhanced Mega Menu Interactivity */
    .mega-subcategory-link {
      position: relative;
      overflow: hidden;
    }

    .mega-subcategory-link::after {
      content: '✨';
      position: absolute;
      right: 8px;
      top: 50%;
      transform: translateY(-50%) scale(0);
      transition: all 0.3s ease;
      opacity: 0;
    }

    .mega-subcategory-link:hover::after {
      transform: translateY(-50%) scale(1);
      opacity: 1;
    }

    .mega-category-emoji {
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .mega-category-emoji:hover {
      animation: emoji-dance 0.8s ease-in-out;
    }

    @keyframes emoji-dance {
      0%, 100% { transform: rotate(0deg) scale(1); }
      25% { transform: rotate(10deg) scale(1.1); }
      50% { transform: rotate(-10deg) scale(1.2); }
      75% { transform: rotate(5deg) scale(1.1); }
    }
    .hero-section {
      background: linear-gradient(135deg, #f5f5dc 0%, #faebd7 25%, #f5deb3 50%, #daa520 75%, #8B4513 100%);
      color: #2c1810;
      padding: 60px 0 80px 0;
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
      animation: float 20s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-10px); }
    }

    .hero-section h1 {
      font-size: 2.8rem;
      font-weight: 700;
      text-shadow: 0 2px 16px rgba(35, 47, 62, 0.18);
    }

    .hero-section p {
      font-size: 1.2rem;
      margin-bottom: 30px;
      opacity: 0.95;
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
      background: #f3f4f6;
      border: none;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(35, 47, 62, 0.04);
      transition: box-shadow 0.2s, transform 0.2s;
    }

    .category-card:hover {
      box-shadow: 0 4px 16px rgba(255, 153, 0, 0.18);
      transform: translateY(-4px) scale(1.03);
    }

    .category-card img {
      height: 80px;
      object-fit: cover;
      margin-bottom: 10px;
      border-radius: 16px;
  box-shadow: 0 12px 32px rgba(35, 47, 62, 0.22), 0 2px 0 #fff inset, 0 0 0 4px #ff990033;
  transform: perspective(600px) rotateY(-18deg) scale(1.12) rotateX(6deg);
  transition: transform 0.4s cubic-bezier(.25, .8, .25, 1), box-shadow 0.4s;
    }

    .category-card:hover img {
      transform: perspective(600px) rotateY(18deg) scale(1.18) rotateX(-6deg);
      box-shadow: 0 24px 48px rgba(255, 153, 0, 0.28), 0 4px 0 #fff inset, 0 0 0 6px #ff9900aa;
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
      box-shadow: 0 2px 8px rgba(35, 47, 62, 0.04);
      transition: box-shadow 0.2s, transform 0.2s;
      background: #fff;
    }

    .product-card:hover {
      box-shadow: 0 4px 16px rgba(255, 153, 0, 0.18);
      transform: translateY(-4px) scale(1.03);
    }

    .product-card img {
      height: 180px;
      object-fit: cover;
      border-radius: 24px 24px 0 0;
      box-shadow: 0 16px 40px rgba(35, 47, 62, 0.18), 0 0 0 4px #ff9900aa;
      transform: perspective(800px) rotateY(-10deg) scale(1.08) rotateX(4deg);
      transition: transform 0.4s cubic-bezier(.25, .8, .25, 1), box-shadow 0.4s;
      transform: perspective(800px) rotateY(10deg) scale(1.13) rotateX(-4deg);
      box-shadow: 0 32px 64px rgba(255, 153, 0, 0.22), 0 0 0 8px #ff9900cc;
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

    footer {
      background: #232f3e;
      color: #fff;
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
      width: 20%;
      /* full width of carousel item */
      max-height: 250px;
      /* adjust as per design */
      object-fit: cover;
      /* crop nicely, keeps ratio */
      object-position: center;
      /* crop from center */
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
      background: rgba(139, 69, 19, 0.05);
      border: 1px solid rgba(139, 69, 19, 0.1);
    }

    /* 🌟 Diwali Theme Banner */
    .diwali-theme-banner {
      background-image: url("/images/diwali-bg1.jpg");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      border-radius: 20px;
      min-height: 400px;
      position: relative;
      overflow: hidden;
    }

    /* Overlay for better contrast */
    .diwali-theme-banner::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.3);
      border-radius: 20px;
      z-index: 1;
    }

    /* ✨ Floating Sparkles Animation */
    .diwali-theme-banner::after {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: radial-gradient(circle, rgba(255, 215, 0, 0.6) 2px, transparent 3px) 0 0 / 40px 40px;
      animation: sparkleMove 12s linear infinite;
      opacity: 0.4;
      z-index: 2;
    }

    /* Sparkle Animation */
    @keyframes sparkleMove {
      from {
        background-position: 0 0;
      }

      to {
        background-position: 200px 200px;
      }
    }

    /* 🔥 Text Glow */
    .text-glow {
      text-shadow: 0 0 10px #ffd700, 0 0 20px #ffae42, 0 0 40px #ffa500;
    }

    /* Ensure content stays above all effects */
    .diwali-theme-banner>* {
      position: relative;
      z-index: 5;
    }

    /* 🪔 Optional: Floating Diya Glow (light orbs) */
    .diwali-theme-banner::before,
    .diwali-theme-banner::after {
      pointer-events: none;
    }

    /* Responsive layout */
    @media (max-width: 768px) {
      .diwali-theme-banner {
        flex-direction: column;
        text-align: center;
        padding: 2rem;
      }

      .diwali-theme-banner img {
        max-height: 250px;
      }

      .diwali-theme-banner h1 {
        font-size: 1.8rem;
      }
    }





    /* 🪔 Second Diwali Banner Theme */
    .diwali-theme-banner-2 {
      background-image: url("/images/diwali-bg2.jpg");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
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
  </style>
</head>

<body>
  {{-- @if(session('success'))
  <div class="alert alert-success text-center mt-3 mb-0" role="alert">
    {{ session('success') }}
  </div>
  @endif
  --}}
  <!-- Modern Enhanced Navbar -->
  <!-- Language Tab -->
  <div class="d-flex justify-content-end align-items-center p-2" style="background: linear-gradient(90deg, #f5f5dc 0%, #faebd7 100%); border-bottom: 1px solid #e5e5e5;">
    <div class="btn-group" role="group" aria-label="Language switcher" style="box-shadow: 0 2px 8px rgba(139,69,19,0.07); border-radius: 8px; overflow: hidden;">
      <button class="btn btn-sm btn-outline-primary language-btn" data-lang="en" style="font-weight:600; background: #fff;">🇬🇧 English</button>
      <button class="btn btn-sm btn-outline-success language-btn" data-lang="ta" style="font-weight:600; background: #fff;">🇮🇳 தமிழ்</button>
      <button class="btn btn-sm btn-outline-warning language-btn" data-lang="te" style="font-weight:600; background: #fff;">🇮🇳 తెలుగు</button>
    </div>
  </div>
  <nav class="navbar navbar-expand-lg" id="mainNavbar">
    <div class="container">
      <!-- Logo -->
      <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center">
        🛒 grabbasket
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
          <input type="text" name="q" placeholder="🔍 Search for products..." class="form-control"
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
                <!-- Header -->
                <div class="mega-menu-header">
                  <h3 class="mega-menu-title">🛍️ Shop by Categories</h3>
                  <p class="mega-menu-subtitle">Discover amazing products in every category</p>
                </div>

                <!-- Gender Filter Tabs -->
                <div class="gender-filter-tabs">
                  <button class="gender-tab active" data-gender="all">🌟 All Categories</button>
                  <button class="gender-tab" data-gender="men">👨 Men's Collection</button>
                  <button class="gender-tab" data-gender="women">👩 Women's Collection</button>
                  <button class="gender-tab" data-gender="kids">👶 Kids Zone</button>
                </div>

                <!-- Categories Grid -->
                <div class="mega-categories-grid" id="megaCategoriesGrid">
                  @if(!empty($categories) && $categories->count())
                    @foreach($categories as $category)
                      @if(strtolower($category->name) !== 'furniture' && strtolower($category->name) !== 'mobillio')
                      <div class="mega-category-card unique-3d-card" data-gender="{{ $category->gender ?? 'all' }}">
                        <div class="mega-category-header">
                          <div class="mega-category-emoji" style="box-shadow: 0 8px 32px #ff9900cc,0 0 0 4px #fff; background: #fff;">
                            @php
                              $modelImages = [
                                'ELECTRONICS' => 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=80&q=80',
                                'MEN\'S FASHION' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=80&q=80',
                                'WOMEN\'S FASHION' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=80&q=80',
                                'HOME & KITCHEN' => 'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=80&q=80',
                                'BEAUTY & PERSONAL CARE' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=80&q=80',
                                'SPORTS & FITNESS' => 'https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=80&q=80',
                                'BOOKS & EDUCATION' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=80&q=80',
                                'KIDS & TOYS' => 'https://images.unsplash.com/photo-1503457574465-0ec62fae31a0?auto=format&fit=crop&w=80&q=80',
                                'AUTOMOTIVE' => 'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=80&q=80',
                                'HEALTH & WELLNESS' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=80&q=80',
                                'JEWELRY & ACCESSORIES' => 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=80&q=80',
                                'GROCERY & FOOD' => 'https://images.unsplash.com/photo-1502741338009-cac2772e18bc?auto=format&fit=crop&w=80&q=80',
                                'GARDEN & OUTDOOR' => 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=80&q=80',
                                'PET SUPPLIES' => 'https://images.unsplash.com/photo-1518717758536-85ae29035b6d?auto=format&fit=crop&w=80&q=80',
                                'BABY PRODUCTS' => 'https://images.unsplash.com/photo-1503457574465-0ec62fae31a0?auto=format&fit=crop&w=80&q=80',
                              ];
                              $catKey = strtoupper($category->name);
                              $img = $category->image ?? ($modelImages[$catKey] ?? 'https://via.placeholder.com/80');
                            @endphp
                            <div class="category-3d-img" style="width:60px;height:60px;perspective:400px;display:flex;align-items:center;justify-content:center;">
                              <img src="{{ $img }}" alt="{{ $category->name }}" style="width:60px;height:60px;object-fit:cover;border-radius:14px;box-shadow:0 8px 32px #ff9900cc,0 0 0 4px #fff;transform:rotateY(-18deg) rotateX(12deg) scale(1.08);transition:transform 0.4s cubic-bezier(.25,1.7,.45,.87);">
                            </div>
                          </div>
                          <h6 class="mega-category-title">{{ $category->name }}</h6>
                          <span class="mega-category-count">
                            {{ $category->subcategories ? $category->subcategories->count() : 0 }}
                          </span>
                        </div>
    /* Unique 3D Card Effect for Categories */
    .unique-3d-card {
      box-shadow: 0 8px 32px #ff9900cc, 0 0 0 4px #fff;
      transform-style: preserve-3d;
      transition: transform 0.5s cubic-bezier(.25,1.7,.45,.87), box-shadow 0.3s;
      background: linear-gradient(135deg, #fffbe6 0%, #f5f5dc 100%);
      border: 2px solid #ffe066;
      position: relative;
    }
    .unique-3d-card:hover {
      transform: rotateY(12deg) rotateX(6deg) scale(1.04);
      box-shadow: 0 16px 48px #ffb300cc, 0 0 0 6px #fff;
      z-index: 2;
    }
    .category-3d-img img {
      box-shadow: 0 8px 32px #ff9900cc,0 0 0 4px #fff;
      border-radius: 14px;
      background: #fff;
      transition: transform 0.4s cubic-bezier(.25,1.7,.45,.87);
    }
    .unique-3d-card:hover .category-3d-img img {
      transform: rotateY(0deg) rotateX(0deg) scale(1.13);
      box-shadow: 0 16px 48px #ffb300cc,0 0 0 6px #fff;
    }
                        
                        @if($category->subcategories && $category->subcategories->count())
                          <div class="mega-subcategories">
                            @foreach($category->subcategories->take(6) as $subcategory)
                              <a href="{{ route('buyer.productsBySubcategory', $subcategory->id) }}" 
                                 class="mega-subcategory-link">
                                {{ $subcategory->name }}
                              </a>
                            @endforeach
                            @if($category->subcategories->count() > 6)
                              <a href="{{ route('buyer.productsByCategory', $category->id) }}" 
                                 class="mega-subcategory-link" style="font-weight: 600; color: #8B4513;">
                                +{{ $category->subcategories->count() - 6 }} more
                              </a>
                            @endif
                          </div>
                        @else
                          <p class="text-muted small">No subcategories available</p>
                        @endif
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
                  $gender = Auth::user()->sex ?? 'other';
                  
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
                  <span class="ms-1 user-name-bounce">{{ Str::limit(Auth::user()->name, 12) }}</span>
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
                <li><a class="dropdown-item" href="#" onclick="playTamilGreeting('{{ Auth::user()->name }}'); return false;">
                  <i class="bi bi-volume-up me-2"></i>🇮🇳 Tamil Welcome
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
          <input type="text" name="q" placeholder="🔍 Search for products..." class="form-control"
            value="{{ request('q') }}" />
          <button class="btn" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </form>
      </div>
    </div>
  </nav>

  <!-- Floating Menu Button -->
  <button type="button" class="btn btn-lg btn-warning shadow position-fixed floating-menu-btn"
    style="bottom:32px;right:32px;z-index:1050;border-radius:50%;width:64px;height:64px;display:flex;align-items:center;justify-content:center;font-size:2rem;animation:bounce 2s infinite;"
    data-bs-toggle="modal" data-bs-target="#categoryMenuModal">
    �
  </button>

  <style>
    .floating-menu-btn {
      transition: all 0.3s ease;
      background: linear-gradient(45deg, #ffc107, #ffb300) !important;
      border: 3px solid #fff;
      box-shadow: 0 4px 20px rgba(255, 193, 7, 0.4);
    }
    
    .floating-menu-btn:hover {
      transform: scale(1.1);
      box-shadow: 0 6px 25px rgba(255, 193, 7, 0.6);
      background: linear-gradient(45deg, #ffb300, #ffc107) !important;
    }
    
    @keyframes bounce {
      0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
      }
      40% {
        transform: translateY(-5px);
      }
      60% {
        transform: translateY(-3px);
      }
    }
  </style>

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
  <!-- Hero Section with Carousel -->
  <section class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        {{-- 🎆 Diwali Banner (first slide) --}}
        <div class="carousel-item active">
          <div class="d-flex align-items-center justify-content-between p-5 diwali-theme-banner">
            {{-- <img src="{{ asset('images/banners/diwali-banner.png') }}" alt="Shop Lights and Gifts"
              class="carousel-img" style="max-height: 380px;"> --}}

            <div class="text-end text-light ms-4">
              <h1 class="fw-bold display-5 text-glow">🪔 Lights, Gifts & More</h1>
              <p class="fs-4">Celebrate Diwali with joy, color, and style — only on Grabbasket.</p>
              {{-- <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg fw-semibold shadow-lg">Shop
                Gifts</a> --}}
            </div>
          </div>
        </div>
        <!-- 🪔 Diwali Exclusive Offers Banner -->
        <div class="carousel-item">
          <div class="d-flex align-items-center justify-content-between p-5 diwali-theme-banner diwali-theme-banner-2">

            <div class="text-light me-4">
              <h1 class="fw-bold display-5 text-glow">💫 Grabbasket Diwali Festival</h1>
              <p class="fs-4 mb-3">
                Light up your home with grand offers this festive season! </p>
              <p class="fs-5 text-warning mb-4">
                ✨ Limited Time Offers | 🕓 Hurry! Ends this weekend.
              </p>
              {{-- <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg fw-semibold shadow-lg">
                Shop Now & Save Big
              </a> --}}
            </div>

            <img src="{{ asset('images/banners/banner3.png') }}" alt="Diwali Grand Sale Offers" class="img-fluid"
              style="max-height: 380px; border-radius: 16px;">
          </div>
        </div>



        {{-- 🛍️ Product Banners --}}
        @foreach($products as $index => $product)
          <div class="carousel-item {{ $index === 0 ? '' : '' }}">
            <div class="py-5"
              style="background: linear-gradient(90deg,#232f3e,#ff9900);">
              <div class="container d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="text-white">
                  <h2 class="h1 fw-bold mb-2">
                    🔥 {{ $product->discount ?? 30 }}% OFF – {{ $product->category?->name ?? 'Uncategorized' }}
                  </h2>
                  <p class="mb-2">⭐ {{ $product->rating ?? 4.8 }}/5 from {{ $product->reviews_count ?? 500 }}+ happy
                    buyers</p>
                  <p class="mb-3 text-warning fw-bold">⚡ Hurry! Only {{ $product->stock ?? 10 }} left in stock</p>
                  <a href="{{ route('product.details', $product->id) }}" class="btn btn-light text-dark me-2"
                    style="margin-left: 70px">Shop Now</a>
                  <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-warning" style="background:#ff9900;color:#232f3e;">
                      Grab Now
                    </button>
                  </form>
                </div>
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="carousel-img" {{--
                  style="height:220px;width:520px;object-fit:contain;border-radius:24px;
                                  box-shadow:0 16px 48px #232f3e44,0 0 0 8px #ff9900cc;
                                  transform:perspective(900px) rotateY(-12deg) scale(1.08) rotateX(6deg);
                                  transition:transform 0.4s,box-shadow 0.4s;" --}}>
              </div>
            </div>
          </div>
        @endforeach
      </div>


      <!-- Controls -->
      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
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

  <!-- Categories Section -->
  {{-- <section class="categories-section">
    <div class="container">
      <h2 class="text-center mb-4"
        style="font-size:2.2rem;font-weight:800;letter-spacing:1px;text-shadow:0 2px 12px #232f3e22;">Shop by Category
      </h2>
      @php
      $user = Auth::user();
      $filteredCategories = $categories;
      if ($user && isset($user->sex)) {
      $sex = strtolower($user->sex);
      $filteredCategories = $categories->filter(function($cat) use ($sex) {
      $name = strtolower($cat->name);
      if ($sex === 'female') {
      return str_contains($name, 'women') || str_contains($name, 'beauty') || str_contains($name, 'fashion');
      } elseif ($sex === 'male') {
      return str_contains($name, 'men') || str_contains($name, 'electronics') || str_contains($name, 'sports');
      }
      return true;
      });
      }
      @endphp
      @foreach($filteredCategories as $category)
      <div class="mb-5">
        <div class="d-flex align-items-center gap-3 mb-3">
          @php
          $modelImages = [
          'FASHION & CLOTHING' =>
          'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
          'ELECTRONICS' =>
          'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=400&q=80',
          'HOME' => 'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=400&q=80',
          'BEAUTY' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=400&q=80',
          'SPORTS' => 'https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=400&q=80',
          'KIDS' => 'https://images.unsplash.com/photo-1503457574465-0ec62fae31a0?auto=format&fit=crop&w=400&q=80',
          'FOOD' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80',
          'BOOKS' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80',
          'JEWELLERY' => 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=400&q=80',
          'GROCERY' => 'https://images.unsplash.com/photo-1502741338009-cac2772e18bc?auto=format&fit=crop&w=400&q=80',
          'TOYS' => 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=400&q=80',
          'FURNITURE' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=400&q=80',
          'MOBILE' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=400&q=80',
          'LAPTOPS' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=400&q=80',
          'SHOES' => 'https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=400&q=80',
          'WATCHES' => 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=400&q=80',
          'BAGS' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=400&q=80',
          'ACCESSORIES' =>
          'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
          'HEALTH' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80',
          'AUTOMOTIVE' =>
          'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=400&q=80',
          ];
          $catKey = strtoupper($category->name);
          $img = $category->image ?? ($modelImages[$catKey] ?? 'https://via.placeholder.com/80');
          @endphp
          <img src="{{ $img }}" alt="{{ $category->name }}" class="rounded shadow"
            style="height:70px;width:70px;object-fit:cover;box-shadow:0 8px 32px #ff9900cc,0 0 0 4px #fff;">
          <h4 class="mb-0" style="font-weight:800;letter-spacing:1px;">{{ $category->name }}</h4>
          <a href="{{ route('buyer.productsByCategory', $category->id) }}" class="btn btn-outline-primary ms-auto">View
            All</a>
        </div>
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
          @php
          $catProducts = $products->where('category_id', $category->id);
          if ($user && isset($user->sex)) {
          $sex = strtolower($user->sex);
          $catProducts = $catProducts->filter(function($prod) use ($sex) {
          $cat = strtolower(optional($prod->category)->name ?? '');
          if ($sex === 'female') {
          return str_contains($cat, 'women') || str_contains($cat, 'beauty') || str_contains($cat, 'fashion');
          } elseif ($sex === 'male') {
          return str_contains($cat, 'men') || str_contains($cat, 'electronics') || str_contains($cat, 'sports');
          }
          return true;
          });
          }
          @endphp
          @forelse($catProducts->take(5) as $product)
          <div class="col">
            <div class="card product-card h-100">
              <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/200' }}"
                class="card-img-top" alt="{{ $product->name }}">
              <div class="card-body d-flex flex-column">
                <h6 class="card-title">{{ $product->name }}</h6>
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
                  <input type="number" name="quantity" min="1" value="1" class="form-control me-2" style="width:70px;"
                    required>
                  <button type="submit" class="btn btn-primary flex-grow-1">Add</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary w-100 mt-2">Login</a>
                @endauth
              </div>
            </div>
          </div>
          @empty
          <div class="col">
            <div class="text-muted">No products in this category.</div>
          </div>
          @endforelse
        </div>
      </div>
      @endforeach
    </div>
  </section> --}}

  <!-- 3D Shop by Categories -->
  <section class="categories-3d">
    <div class="categories-header">
      <h2 class="section-title">🏛️ SHOP BY CATEGORIES 🏛️</h2>
      <div class="title-glow"></div>
      <p class="section-subtitle">✨ Explore our luxurious 3D collections ✨</p>
    </div>
    
    <div class="categories-3d-grid">
      <!-- Living Room Palace -->
      <div class="category-cube" data-category="living">
        <div class="cube-face front">
          <div class="category-icon">🛋️</div>
          <h3>Living Palace</h3>
          <p>Royal comfort</p>
        </div>
        <div class="cube-face back">
          <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTqvWFTS8vzDHCgJKy5LPZm0-jPaw3LZ6c4RA&s" alt="Furniture">
          <div class="category-overlay">
            <span>Premium Collection</span>
          </div>
        </div>
      </div>

      <!-- Bedroom Kingdom -->
      <div class="category-cube" data-category="bedroom">
        <div class="cube-face front">
          <div class="category-icon">🏰</div>
          <h3>Bedroom Kingdom</h3>
          <p>Dreamy comfort</p>
        </div>
        <div class="cube-face back">
          <img src="https://www.estre.in/cdn/shop/files/1-min_1a7b23d8-e00c-4bca-86fe-9c65a55bcd1d.jpg?v=1743763633" alt="Chairs">
          <div class="category-overlay">
            <span>Luxury Dreams</span>
          </div>
        </div>
      </div>

      <!-- Dining Empire -->
      <div class="category-cube" data-category="dining">
        <div class="cube-face front">
          <div class="category-icon">🍽️</div>
          <h3>Dining Empire</h3>
          <p>Feast in style</p>
        </div>
        <div class="cube-face back">
          <img src="https://thetimberguy.com/cdn/shop/products/Buy-Compact-Wooden-Dining-table-with-1-Bench-3-chairs-furniture-set-for-modern-Home-2_1200x.jpg?v=1640455849" alt="Tables">
          <div class="category-overlay">
            <span>Elegant Dining</span>
          </div>
        </div>
      </div>

      <!-- Lighting Sanctuary -->
      <div class="category-cube" data-category="lighting">
        <div class="cube-face front">
          <div class="category-icon">💡</div>
          <h3>Light Sanctuary</h3>
          <p>Illuminate beauty</p>
        </div>
        <div class="cube-face back">
          <img src="https://plus.unsplash.com/premium_photo-1668005190411-1042cd38522e?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8bGFtcHxlbnwwfHwwfHx8MA%3D%3D" alt="Lamps">
          <div class="category-overlay">
            <span>Ambient Glow</span>
          </div>
        </div>
      </div>

      <!-- Garden Paradise -->
      <div class="category-cube" data-category="garden">
        <div class="cube-face front">
          <div class="category-icon">🌿</div>
          <h3>Garden Paradise</h3>
          <p>Nature's beauty</p>
        </div>
        <div class="cube-face back">
          <img src="https://www.thespruce.com/thmb/ZhNUOJ4Pt0Bj422Pu_uEzZXa_j0=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/increase-humidity-for-houseplants-1902801-6-eadf73df8284421ca827c073d8a43fd2.jpg" alt="Plants">
          <div class="category-overlay">
            <span>Green Haven</span>
          </div>
        </div>
      </div>

      <!-- Decor Gallery -->
      <div class="category-cube" data-category="decor">
        <div class="cube-face front">
          <div class="category-icon">🎨</div>
          <h3>Decor Gallery</h3>
          <p>Artistic flair</p>
        </div>
        <div class="cube-face back">
          <img src="https://anilevents.in/wp-content/uploads/2021/03/20210204_100836-021-scaled.jpeg" alt="Decor">
          <div class="category-overlay">
            <span>Art Collection</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Floating Action Buttons -->
    <div class="floating-actions">
      <button class="fab-main" onclick="showCategoryMenu()">
        <span class="fab-icon">🔮</span>
        <span class="fab-text">Explore Magic</span>
      </button>
    </div>
  </section>
      <!-- Existing items -->
      <div><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTqvWFTS8vzDHCgJKy5LPZm0-jPaw3LZ6c4RA&s"
          alt="Desk">
        <p>Desks</p>
      </div>
      <div><img src="https://www.estre.in/cdn/shop/files/1-min_1a7b23d8-e00c-4bca-86fe-9c65a55bcd1d.jpg?v=1743763633"
          alt="Chair">
        <p>Chairs</p>
      </div>
      <div><img
          src="https://thetimberguy.com/cdn/shop/products/Buy-Compact-Wooden-Dining-table-with-1-Bench-3-chairs-furniture-set-for-modern-Home-2_1200x.jpg?v=1640455849"
          alt="Table">
        <p>Tables</p>
      </div>
      <div><img
          src="https://plus.unsplash.com/premium_photo-1668005190411-1042cd38522e?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8bGFtcHxlbnwwfHwwfHx8MA%3D%3D"
          alt="Lamp">
        <p>Lamps</p>
      </div>
      <div><img
          src="https://www.thespruce.com/thmb/ZhNUOJ4Pt0Bj422Pu_uEzZXa_j0=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/increase-humidity-for-houseplants-1902801-6-eadf73df8284421ca827c073d8a43fd2.jpg"
          alt="Plant">
        <p>Plants</p>
      </div>
      <div><img src="https://anilevents.in/wp-content/uploads/2021/03/20210204_100836-021-scaled.jpeg" alt="Decor">
        <p>Decoration</p>
      </div>

      <!-- NEW ITEMS -->
      <div><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQcaeNOjysBGQ83oQDf7FSVu90w6AMZhtkbwg&s"
          alt="Bookshelf">
        <p>Bookshelves</p>
      </div>
      {{-- <div><img src="https://cdn.shopify.com/s/files/1/0533/2089/files/area-rug-living-room.jpg?v=1620356820"
          alt="Rug">
        <p>Rugs</p>
      </div> --}}
      <div><img
          src="data:image/jpeg;base64,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"
          alt="Curtains">
        <p>Curtains</p>
      </div>
      <div><img
          src="data:image/jpeg;base64,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"
          alt="Mirror">
        <p>Mirrors</p>
      </div>
      <div><img
          src="data:image/jpeg;base64,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"
          alt="Coffee Table">
        <p>Coffee Tables</p>
      </div>
      <div><img
          src="https://paperplanedesign.in/cdn/shop/files/lord-buddha-wall-art-painting-for-bedroom-and-office-wall-decoration-951053.jpg?v=1715591479&width=1080"
          alt="Wall Art">
        <p>Wall Art</p>
      </div>
    </div>
  </section>

  <!-- About -->
  <section class="about">
    <h2>MOBILLIO FURNITURE</h2>
    <p>Mobillio Store Inc. are unique resellers of modern furniture, designer-made, handcrafted items, since 1997. Our
      legacy guarantees exceptional product quality, unique design and special prices for all of our product line up.
    </p>
    <a href="{{ route('buyer.dashboard') }}"><button>Explore</button></a>
  </section>

  <!-- Products Section -->
  {{-- <section class="products-section">
    <div class="container">
      @php
      $items = ($products instanceof \Illuminate\Pagination\LengthAwarePaginator) ? collect($products->items()) :
      collect($products);
      $flashSale = $items->where('is_flash_sale', true)->take(8);
      $deals = $items->sortByDesc('discount')->take(12);
      $trending = $items->take(12);
      $freeDelivery = $items->filter(fn($p) => (int)($p->delivery_charge ?? 0) === 0)->take(12);
      @endphp

      @if($flashSale->count())
      <div class="mb-4">
        <h2 class="mb-3" style="color:#ff0033;"><i class="bi bi-lightning-charge-fill"></i> Flash Sale</h2>
        <div class="shelf" style="border:2px solid #ff0033;box-shadow:0 4px 24px rgba(255,0,51,0.08);">
          <button class="nav-btn nav-prev" onclick="scrollShelf('flash',-1)" style="background:#ff0033;color:#fff;"><i
              class="bi bi-chevron-left"></i></button>
          <div id="shelf-flash" class="shelf-track">
            @foreach($flashSale as $product)
            <div class="shelf-item">
              <div class="card product-card h-100 border-danger">
                <img
                  src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/200' }}"
                  class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                  <div class="small text-danger">Flash Sale! {{ (int)($product->discount ?? 0) }}% off</div>
                  <h6 class="card-title mt-1">{{ \Illuminate\Support\Str::limit($product->name, 40) }}</h6>
                  <div class="mt-auto">
                    @if($product->discount > 0)
                      <span class="fw-bold text-danger">₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                      <small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</small>
                    @else
                      <span class="fw-bold">₹{{ number_format($product->price, 2) }}</span>
                    @endif
                    @auth
                    <form method="POST" action="{{ route('cart.add') }}" class="mt-2 d-flex align-items-center">
                      @csrf
                      <input type="hidden" name="product_id" value="{{ $product->id }}">
                      <input type="number" name="quantity" min="1" value="1" class="form-control me-2"
                        style="width:70px;" required>
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
          <button class="nav-btn nav-next" onclick="scrollShelf('flash',1)" style="background:#ff0033;color:#fff;"><i
              class="bi bi-chevron-right"></i></button>
        </div>
      </div>
      @endif

      <div class="mb-4">
        <h2 class="mb-3">Deals of the Day</h2>
        <div class="shelf">
          <button class="nav-btn nav-prev" onclick="scrollShelf('deals',-1)"><i class="bi bi-chevron-left"></i></button>
          <div id="shelf-deals" class="shelf-track">
            @foreach($deals as $product)
            <div class="shelf-item">
              <div class="card product-card h-100">
                <img
                  src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/200' }}"
                  class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                  <div class="small text-muted">Up to {{ (int)($product->discount ?? 0) }}% off</div>
                  <h6 class="card-title mt-1">{{ \Illuminate\Support\Str::limit($product->name, 40) }}</h6>
                  <div class="mt-auto">
                    @if($product->discount > 0)
                      <span class="fw-bold text-success">₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                      <small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</small>
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
            @endforeach
          </div>
          <button class="nav-btn nav-next" onclick="scrollShelf('deals',1)"><i class="bi bi-chevron-right"></i></button>
        </div>
      </div>

      <div class="mb-4">
        <h2 class="mb-3">Trending Now</h2>
        <div class="shelf">
          <button class="nav-btn nav-prev" onclick="scrollShelf('trend',-1)"><i class="bi bi-chevron-left"></i></button>
          <div id="shelf-trend" class="shelf-track">
            @foreach($trending as $product)
            <div class="shelf-item">
              <div class="card product-card h-100">
                <img
                  src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/200' }}"
                  class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                  <h6 class="card-title">{{ \Illuminate\Support\Str::limit($product->name, 40) }}</h6>
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
            @endforeach
          </div>
          <button class="nav-btn nav-next" onclick="scrollShelf('trend',1)"><i class="bi bi-chevron-right"></i></button>
        </div>
      </div>

      <div class="mb-4">
        <h2 class="mb-3">Free Delivery Picks</h2>
        <div class="shelf">
          <button class="nav-btn nav-prev" onclick="scrollShelf('free',-1)"><i class="bi bi-chevron-left"></i></button>
          <div id="shelf-free" class="shelf-track">
            @forelse($freeDelivery as $product)
            <div class="shelf-item">
              <div class="card product-card h-100">
                <img
                  src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/200' }}"
                  class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                  <div class="small text-success">Free Delivery</div>
                  <h6 class="card-title">{{ \Illuminate\Support\Str::limit($product->name, 40) }}</h6>
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
  </section> --}}

  <section class="trending my-5" style="margin-left:20px ">
    <h2 class="mb-3 " style="margin-left: 40%">🔥 Trending Items</h2>
    <div class="grid">
      @foreach($products as $product)
        <div class="item card shadow-sm p-3 text-center position-relative" style="min-width:250px;">
          <!-- Share Button -->
          <div class="position-absolute top-0 end-0 m-2">
            <div class="dropdown">
              <button class="btn btn-sm btn-outline-secondary rounded-circle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-share"></i>
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="shareProductFromHome('{{ $product->id }}', 'whatsapp', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-whatsapp text-success"></i> WhatsApp</a></li>
                <li><a class="dropdown-item" href="#" onclick="shareProductFromHome('{{ $product->id }}', 'facebook', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-facebook text-primary"></i> Facebook</a></li>
                <li><a class="dropdown-item" href="#" onclick="shareProductFromHome('{{ $product->id }}', 'twitter', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-twitter text-info"></i> Twitter</a></li>
                <li><a class="dropdown-item" href="#" onclick="shareProductFromHome('{{ $product->id }}', 'copy', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-link-45deg"></i> Copy Link</a></li>
              </ul>
            </div>
          </div>

          <a href="{{ route('product.details', $product->id) }}" style="text-decoration: none">
            <div class="image-box">
              <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/200' }}"
                alt="{{ $product->name }}">
            </div>

            <h6 class="mt-2">{{ \Illuminate\Support\Str::limit($product->name, 30) }}</h6>

            <div class="stars">
              @php $stars = rand(3, 5); @endphp
              {!! str_repeat('★', $stars) !!}{!! str_repeat('☆', 5 - $stars) !!}
            </div>

            @if($product->discount > 0)
              <p class="price fw-bold text-success">₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}</p>
              <small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</small>
              <small class="text-danger">({{ $product->discount }}% off)</small>
            @else
              <p class="price fw-bold text-success">₹{{ number_format($product->price, 2) }}</p>
            @endif
          </a>
        </div>
      @endforeach
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
  </section> --}}

  <section class="container my-5">
    <div class="row align-items-center" style="margin-left: 20%">
      @if($lookbookProduct)
        <div class="col-md-6 text-center text-md-start">
          <h2 class="fw-semibold mb-2">LOOKBOOK</h2>
          <p class="text-muted mb-4">
            {{ \Illuminate\Support\Str::limit($lookbookProduct->description ?? 'Carefully curated furniture, well matched in style and looks', 120) }}
          </p>
          <a href="{{ route('product.details', $lookbookProduct->id) }}">
            <button class="btn btn-warning px-4 py-2">Explore now</button>
          </a>
        </div>
        <div class="col-md-6 text-center">
          <img
            src="{{ $lookbookProduct->image ? asset('storage/' . $lookbookProduct->image) : 'https://via.placeholder.com/450' }}"
            alt="{{ $lookbookProduct->name }}" class="img-fluid rounded"
            style="max-height:450px; object-fit:contain; min-height:400px">
        </div>
      @endif
    </div>
  </section>



  <section class="container my-5">
    <div class="text-center mb-4">
      <h2 class="text-uppercase text-primary small fw-semibold">OUR BLOG</h2>
      <p class="text-primary small">Read the latest news and product related articles</p>
    </div>

    <div class="row g-4 justify-content-center">
                    @foreach($categories as $category)
                      @if(strtolower($category->name) !== 'furniture' && strtolower($category->name) !== 'mobillio')
                        <div class="mega-category-card unique-3d-card" data-gender="{{ $category->gender ?? 'all' }}">
                          <div class="mega-category-header">
                            <div class="mega-category-emoji" style="box-shadow: 0 8px 32px #ff9900cc,0 0 0 4px #fff; background: #fff;">
                              @php
                                $modelImages = [
                                  'ELECTRONICS' => 'https://images.unsplash.com/photo-1519125323398-675f0ddb6308?auto=format&fit=crop&w=80&q=80',
                                  'MEN\'S FASHION' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=80&q=80',
                                  'WOMEN\'S FASHION' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=80&q=80',
                                  'HOME & KITCHEN' => 'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=80&q=80',
                                  'BEAUTY & PERSONAL CARE' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=80&q=80',
                                  'SPORTS & FITNESS' => 'https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=80&q=80',
                                  'BOOKS & EDUCATION' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=80&q=80',
                                  'KIDS & TOYS' => 'https://images.unsplash.com/photo-1503457574465-0ec62fae31a0?auto=format&fit=crop&w=80&q=80',
                                  'AUTOMOTIVE' => 'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?auto=format&fit=crop&w=80&q=80',
                                  'HEALTH & WELLNESS' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=80&q=80',
                                  'JEWELRY & ACCESSORIES' => 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=80&q=80',
                                  'GROCERY & FOOD' => 'https://images.unsplash.com/photo-1502741338009-cac2772e18bc?auto=format&fit=crop&w=80&q=80',
                                  'GARDEN & OUTDOOR' => 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=80&q=80',
                                  'PET SUPPLIES' => 'https://images.unsplash.com/photo-1518717758536-85ae29035b6d?auto=format&fit=crop&w=80&q=80',
                                  'BABY PRODUCTS' => 'https://images.unsplash.com/photo-1503457574465-0ec62fae31a0?auto=format&fit=crop&w=80&q=80',
                                ];
                                $catKey = strtoupper($category->name);
                                $img = $category->image ?? ($modelImages[$catKey] ?? 'https://via.placeholder.com/80');
                              @endphp
                              <div class="category-3d-img" style="width:60px;height:60px;perspective:400px;display:flex;align-items:center;justify-content:center;">
                                <img src="{{ $img }}" alt="{{ $category->name }}" style="width:60px;height:60px;object-fit:cover;border-radius:14px;box-shadow:0 8px 32px #ff9900cc,0 0 0 4px #fff;transform:rotateY(-18deg) rotateX(12deg) scale(1.08);transition:transform 0.4s cubic-bezier(.25,1.7,.45,.87);">
                              </div>
                            </div>
                            <h6 class="mega-category-title">{{ $category->name }}</h6>
                            <span class="mega-category-count">
                              {{ $category->subcategories ? $category->subcategories->count() : 0 }}
                            </span>
                          </div>
                          @if($category->subcategories && $category->subcategories->count())
                            <div class="mega-subcategories">
                              @foreach($category->subcategories->take(6) as $subcategory)
                                <a href="{{ route('buyer.productsBySubcategory', $subcategory->id) }}" 
                                   class="mega-subcategory-link">
                                  {{ $subcategory->name }}
                                </a>
                              @endforeach
                              @if($category->subcategories->count() > 6)
                                <a href="{{ route('buyer.productsByCategory', $category->id) }}" 
                                   class="mega-subcategory-link" style="font-weight: 600; color: #8B4513;">
                                  +{{ $category->subcategories->count() - 6 }} more
                                </a>
                              @endif
                            </div>
                          @else
                            <p class="text-muted small">No subcategories available</p>
                          @endif
                        </div>
                      @endif
                    @endforeach
            <a href="#" class="text-light"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-light"><i class="bi bi-twitter"></i></a>
            <a href="#" class="text-light"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-light"><i class="bi bi-youtube"></i></a>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- Chatbot Widget -->
  <x-chatbot-widget />

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Language tab switching
    document.addEventListener('DOMContentLoaded', function() {
      const langBtns = document.querySelectorAll('.language-btn');
      const langKey = 'grabbasket_lang';
      // Set active button from localStorage
      const savedLang = localStorage.getItem(langKey) || 'en';
      langBtns.forEach(btn => {
        if (btn.dataset.lang === savedLang) {
          btn.classList.add('active');
        } else {
          btn.classList.remove('active');
        }
        btn.addEventListener('click', function() {
          localStorage.setItem(langKey, this.dataset.lang);
          langBtns.forEach(b => b.classList.remove('active'));
          this.classList.add('active');
          // Optionally, reload or trigger translation here
        });
      });
    });
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
      if (userGreeting) {
        userGreeting.addEventListener('mouseenter', function() {
          // Create sparkle effect
          for (let i = 0; i < 3; i++) {
            setTimeout(() => {
              createSparkle(this);
            }, i * 200);
          }
        });
      }

      function createSparkle(element) {
        const sparkle = document.createElement('span');
        sparkle.innerHTML = '✨';
        sparkle.style.position = 'absolute';
        sparkle.style.pointerEvents = 'none';
        sparkle.style.zIndex = '1000';
        sparkle.style.fontSize = '12px';
        sparkle.style.animation = 'sparkle-fly 1s ease-out forwards';
        
        const rect = element.getBoundingClientRect();
        sparkle.style.left = (rect.left + Math.random() * rect.width) + 'px';
        sparkle.style.top = (rect.top + Math.random() * rect.height) + 'px';
        
        document.body.appendChild(sparkle);
        
        setTimeout(() => {
          sparkle.remove();
        }, 1000);
      }

      // Add sparkle animation CSS
      if (!document.querySelector('#sparkleAnimations')) {
        const style = document.createElement('style');
        style.id = 'sparkleAnimations';
        style.textContent = `
          @keyframes sparkle-fly {
            0% {
              opacity: 1;
              transform: translateY(0) scale(1);
            }
            100% {
              opacity: 0;
              transform: translateY(-30px) scale(0.5);
            }
          }
        `;
        document.head.appendChild(style);
      }

      // Tamil Voice Greeting Function
      function playTamilGreeting(userName) {
        if ('speechSynthesis' in window) {
          // Tamil greeting message
          const tamilMessage = `வணக்கம் ${userName}! கிராப்பாஸ்கெட்டுக்கு தங்களை அன்புடன் வரவேற்கிறோம்!`;
          
          const utterance = new SpeechSynthesisUtterance(tamilMessage);
          
          // Try to find Tamil voice
          const voices = speechSynthesis.getVoices();
          const tamilVoice = voices.find(voice => 
            voice.lang.includes('ta') || 
            voice.lang.includes('hi') || 
            voice.name.toLowerCase().includes('tamil')
          );
          
          if (tamilVoice) {
            utterance.voice = tamilVoice;
          } else {
            // Fallback to any available voice
            utterance.voice = voices[0] || null;
          }
          
          utterance.rate = 0.8;
          utterance.pitch = 1.1;
          utterance.volume = 0.7;
          
          // Add visual feedback with enhanced Tamil styling
          const notification = document.createElement('div');
          notification.innerHTML = `
            <div class="alert alert-success d-flex align-items-center tamil-greeting-notification" style="
              position: fixed; 
              top: 20px; 
              right: 20px; 
              z-index: 9999; 
              border-radius: 15px; 
              box-shadow: 0 8px 32px rgba(0,123,255,0.3);
              background: linear-gradient(135deg, #28a745, #20c997);
              border: 2px solid #ffd700;
              min-width: 300px;
              animation: tamilSlideIn 0.8s ease-out;
            ">
              <div class="d-flex align-items-center">
                <i class="bi bi-volume-up-fill me-2" style="font-size: 1.5rem; color: #ffd700;"></i>
                <div>
                  <div style="color: white; font-weight: bold; font-size: 1.1rem;">
                    🔊 வணக்கம் ${userName}! 🎉
                  </div>
                  <div style="color: #f8f9fa; font-size: 0.9rem; margin-top: 2px;">
                    கிராப்பாஸ்கெட்டுக்கு வரவேற்கிறோம்!
                  </div>
                </div>
              </div>
            </div>
          `;
          
          document.body.appendChild(notification);
          
          // Remove notification after 5 seconds with fade out
          setTimeout(() => {
            notification.style.animation = 'tamilFadeOut 0.5s ease-in forwards';
            setTimeout(() => notification.remove(), 500);
          }, 5000);
          
          // Play the speech
          speechSynthesis.speak(utterance);
        }
      }

      // Add Tamil greeting animations CSS
      if (!document.querySelector('#tamilAnimations')) {
        const style = document.createElement('style');
        style.id = 'tamilAnimations';
        style.textContent = `
          @keyframes tamilSlideIn {
            0% {
              opacity: 0;
              transform: translateX(100%) scale(0.8);
            }
            50% {
              transform: translateX(-10px) scale(1.05);
            }
            100% {
              opacity: 1;
              transform: translateX(0) scale(1);
            }
          }
          
          @keyframes tamilFadeOut {
            0% {
              opacity: 1;
              transform: scale(1);
            }
            100% {
              opacity: 0;
              transform: scale(0.9) translateX(50px);
            }
          }
          
          .tamil-greeting-notification:hover {
            transform: scale(1.02);
            transition: transform 0.2s ease;
          }
        `;
        document.head.appendChild(style);
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

  <!-- Chatbot Assistant Widget -->
  <div id="chatbotWidget" class="chatbot-widget">
    <!-- Chatbot Toggle Button -->
    <div id="chatbotToggle" class="chatbot-toggle" onclick="toggleChatbot()">
      <div class="robot-anim">
        <i class="bi bi-robot"></i>
        <div class="robot-leg left-leg"></div>
        <div class="robot-leg right-leg"></div>
      </div>
      <span class="chat-pulse"></span>
    /* Robot walking animation */
    .robot-anim {
      position: relative;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: robotFloat 3s ease-in-out infinite;
    }
    .robot-anim i {
      font-size: 32px;
      z-index: 2;
      animation: robotWalk 1.2s steps(2) infinite;
    }
    .robot-leg {
      position: absolute;
      bottom: 0;
      width: 6px;
      height: 16px;
      background: #007bff;
      border-radius: 3px;
      z-index: 1;
      left: 7px;
      opacity: 0.7;
    }
    .robot-leg.right-leg {
      left: 19px;
      animation: legMove 1.2s infinite alternate;
    }
    .robot-leg.left-leg {
      left: 7px;
      animation: legMove 1.2s 0.6s infinite alternate;
    }
    @keyframes robotWalk {
      0% { filter: brightness(1); }
      50% { filter: brightness(1.2) drop-shadow(0 2px 2px #0056b3); }
      100% { filter: brightness(1); }
    }
    @keyframes legMove {
      0% { transform: rotate(10deg); }
      100% { transform: rotate(-20deg); }
    }
    @keyframes robotFloat {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-10px); }
    }
    /* Draggable chatbot toggle */
    .chatbot-toggle.dragging {
      opacity: 0.7;
      box-shadow: 0 0 0 4px #007bff44;
      cursor: grabbing;
      z-index: 10000;
      transition: none;
    }
  <script>
    // Make chatbot toggle draggable
    document.addEventListener('DOMContentLoaded', function() {
      const chatbotToggle = document.getElementById('chatbotToggle');
      let isDragging = false, offsetX = 0, offsetY = 0;
      chatbotToggle.addEventListener('mousedown', function(e) {
        isDragging = true;
        chatbotToggle.classList.add('dragging');
        offsetX = e.clientX - chatbotToggle.getBoundingClientRect().left;
        offsetY = e.clientY - chatbotToggle.getBoundingClientRect().top;
        document.body.style.userSelect = 'none';
      });
      document.addEventListener('mousemove', function(e) {
        if (!isDragging) return;
        let x = e.clientX - offsetX;
        let y = e.clientY - offsetY;
        // Keep within viewport
        x = Math.max(0, Math.min(window.innerWidth - chatbotToggle.offsetWidth, x));
        y = Math.max(0, Math.min(window.innerHeight - chatbotToggle.offsetHeight, y));
        chatbotToggle.parentElement.style.right = 'auto';
        chatbotToggle.parentElement.style.left = x + 'px';
        chatbotToggle.parentElement.style.top = y + 'px';
        chatbotToggle.parentElement.style.bottom = 'auto';
      });
      document.addEventListener('mouseup', function() {
        if (isDragging) {
          isDragging = false;
          chatbotToggle.classList.remove('dragging');
          document.body.style.userSelect = '';
        }
      });
      // Touch support
      chatbotToggle.addEventListener('touchstart', function(e) {
        isDragging = true;
        chatbotToggle.classList.add('dragging');
        const touch = e.touches[0];
        offsetX = touch.clientX - chatbotToggle.getBoundingClientRect().left;
        offsetY = touch.clientY - chatbotToggle.getBoundingClientRect().top;
        document.body.style.userSelect = 'none';
      });
      document.addEventListener('touchmove', function(e) {
        if (!isDragging) return;
        const touch = e.touches[0];
        let x = touch.clientX - offsetX;
        let y = touch.clientY - offsetY;
        x = Math.max(0, Math.min(window.innerWidth - chatbotToggle.offsetWidth, x));
        y = Math.max(0, Math.min(window.innerHeight - chatbotToggle.offsetHeight, y));
        chatbotToggle.parentElement.style.right = 'auto';
        chatbotToggle.parentElement.style.left = x + 'px';
        chatbotToggle.parentElement.style.top = y + 'px';
        chatbotToggle.parentElement.style.bottom = 'auto';
      });
      document.addEventListener('touchend', function() {
        if (isDragging) {
          isDragging = false;
          chatbotToggle.classList.remove('dragging');
          document.body.style.userSelect = '';
        }
      });
    });
  </script>
    </div>

    <!-- Chatbot Window -->
    <div id="chatbotWindow" class="chatbot-window">
      <!-- Chatbot Header -->
      <div class="chatbot-header">
        <div class="d-flex align-items-center">
          <div class="chatbot-avatar">
            <i class="bi bi-robot"></i>
          </div>
          <div class="ms-2">
            <h6 class="mb-0">GrabBasket Assistant</h6>
            <small class="text-muted">🟢 Online</small>
          </div>
        </div>
        <button class="btn btn-sm" onclick="closeChatbot()">
          <i class="bi bi-x-lg"></i>
        </button>
      </div>

      <!-- Chatbot Messages -->
      <div id="chatbotMessages" class="chatbot-messages">
        <div class="message bot-message">
          <div class="message-avatar">
            <i class="bi bi-robot"></i>
          </div>
          <div class="message-content">
            <p>🙏 வணக்கம்! Hello! I'm your GrabBasket Assistant. How can I help you today?</p>
            <div class="quick-actions">
              <button class="quick-btn" onclick="askQuestion('What products do you have?')">🛍️ Products</button>
              <button class="quick-btn" onclick="askQuestion('How to place an order?')">📦 Orders</button>
              <button class="quick-btn" onclick="askQuestion('What are delivery charges?')">🚚 Delivery</button>
              <button class="quick-btn" onclick="askQuestion('Customer support contact')">📞 Support</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Chatbot Input -->
      <div class="chatbot-input">
        <div class="input-group">
          <input type="text" id="chatbotMessageInput" class="form-control" 
                 placeholder="Ask me anything about GrabBasket..." 
                 onkeypress="handleChatbotKeypress(event)">
          <button class="btn btn-primary" onclick="sendMessage()">
            <i class="bi bi-send"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <style>
    /* Chatbot Widget Styles */
    .chatbot-widget {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 9999;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }

    .chatbot-toggle {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #007bff, #0056b3);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      cursor: pointer;
      box-shadow: 0 4px 20px rgba(0, 123, 255, 0.3);
      transition: all 0.3s ease;
      position: relative;
      animation: chatbotBounce 2s infinite;
    }

    .chatbot-toggle:hover {
      transform: scale(1.1);
      box-shadow: 0 6px 25px rgba(0, 123, 255, 0.4);
    }

    .chatbot-toggle i {
      font-size: 24px;
    }

    .chat-pulse {
      position: absolute;
      width: 100%;
      height: 100%;
      border-radius: 50%;
      background: rgba(0, 123, 255, 0.4);
      animation: chatbotPulse 2s infinite;
    }

    .chatbot-window {
      position: absolute;
      bottom: 80px;
      right: 0;
      width: 380px;
      height: 500px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
      display: none;
      flex-direction: column;
      overflow: hidden;
      animation: chatbotSlideUp 0.3s ease-out;
    }

    .chatbot-header {
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: white;
      padding: 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .chatbot-avatar {
      width: 32px;
      height: 32px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .chatbot-messages {
      flex: 1;
      overflow-y: auto;
      padding: 16px;
      background: #f8f9fa;
    }

    .message {
      display: flex;
      margin-bottom: 16px;
      animation: messageSlideIn 0.3s ease-out;
    }

    .bot-message {
      align-items: flex-start;
    }

    .user-message {
      align-items: flex-end;
      flex-direction: row-reverse;
    }

    .message-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 14px;
      margin: 0 8px;
    }

    .bot-message .message-avatar {
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: white;
    }

    .user-message .message-avatar {
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
    }

    .message-content {
      max-width: 70%;
      background: white;
      padding: 12px 16px;
      border-radius: 16px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .user-message .message-content {
      background: linear-gradient(135deg, #007bff, #0056b3);
      color: white;
    }

    .message-content p {
      margin: 0;
      line-height: 1.4;
    }

    .quick-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      margin-top: 12px;
    }

    .quick-btn {
      background: #e9ecef;
      border: none;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .quick-btn:hover {
      background: #007bff;
      color: white;
      transform: translateY(-1px);
    }

    .chatbot-input {
      padding: 16px;
      background: white;
      border-top: 1px solid #e9ecef;
    }

    .chatbot-input .form-control {
      border-radius: 25px;
      border: 1px solid #e9ecef;
      padding: 12px 16px;
    }

    .chatbot-input .btn {
      border-radius: 50%;
      width: 40px;
      height: 40px;
      padding: 0;
      margin-left: 8px;
    }

    .typing-indicator {
      display: flex;
      align-items: center;
      padding: 12px 16px;
      background: white;
      border-radius: 16px;
      margin-left: 40px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .typing-dots {
      display: flex;
      gap: 4px;
    }

    .typing-dot {
      width: 8px;
      height: 8px;
      background: #007bff;
      border-radius: 50%;
      animation: typingBounce 1.4s infinite ease-in-out;
    }

    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }

    /* Animations */
    @keyframes chatbotBounce {
      0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
      40% { transform: translateY(-5px); }
      60% { transform: translateY(-3px); }
    }

    @keyframes chatbotPulse {
      0% { transform: scale(1); opacity: 1; }
      100% { transform: scale(1.3); opacity: 0; }
    }

    @keyframes chatbotSlideUp {
      from { transform: translateY(20px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    @keyframes messageSlideIn {
      from { transform: translateX(-20px); opacity: 0; }
      to { transform: translateX(0); opacity: 1; }
    }

    @keyframes typingBounce {
      0%, 80%, 100% { transform: scale(0); }
      40% { transform: scale(1); }
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .chatbot-window {
        width: 320px;
        height: 450px;
      }
      
      .chatbot-toggle {
        width: 50px;
        height: 50px;
      }
      
      .chatbot-toggle i {
        font-size: 20px;
      }
    }
  </style>

  <script>
    // Chatbot Knowledge Base
    const chatbotKnowledge = {
      // Products and Categories
      'products': [
        "We offer a wide range of products including furniture, home decor, electronics, and household items.",
        "Our categories include Living Room furniture, Bedroom sets, Dining tables, Lighting, Plants, and Decorative items.",
        "You can browse our 3D categories section to explore different product collections."
      ],
      'furniture': [
        "We have premium furniture collections including sofas, chairs, tables, beds, and storage solutions.",
        "Our furniture ranges from modern to traditional styles with competitive pricing and quality guarantee."
      ],
      'categories': [
        "We have organized products into categories like Living Palace, Bedroom Kingdom, Dining Empire, Light Sanctuary, Garden Paradise, and Decor Gallery.",
        "Each category is designed to help you find exactly what you're looking for."
      ],
      
      // Orders and Shopping
      'order': [
        "To place an order: 1) Browse products 2) Add to cart 3) Proceed to checkout 4) Enter shipping details 5) Make payment",
        "You can track your orders from your dashboard after logging in.",
        "We accept multiple payment methods for your convenience."
      ],
      'cart': [
        "Add products to cart by clicking the 'Add to Cart' button on product pages.",
        "You can view and modify your cart contents before checkout.",
        "Items remain in your cart even after logging out."
      ],
      'payment': [
        "We accept credit cards, debit cards, UPI, and cash on delivery.",
        "All payments are processed securely through encrypted channels.",
        "You'll receive confirmation emails for all transactions."
      ],
      
      // Delivery and Shipping
      'delivery': [
        "Delivery charges vary by location and product. Many items offer free delivery.",
        "Standard delivery takes 3-7 business days depending on your location.",
        "We provide tracking information once your order is shipped."
      ],
      'shipping': [
        "We ship across India with reliable courier partners.",
        "Express delivery options available for urgent orders.",
        "Free shipping on orders above ₹999 in most areas."
      ],
      
      // Account and Support
      'account': [
        "Create an account to track orders, save favorites, and get personalized recommendations.",
        "You can manage your profile, addresses, and preferences in the dashboard.",
        "Both buyer and seller accounts are available."
      ],
      'support': [
        "For support, contact us through the website or call our helpline.",
        "Our customer service team is available during business hours.",
        "You can also use this chat for immediate assistance."
      ],
      'return': [
        "We offer easy returns within 7 days of delivery for most products.",
        "Items should be in original condition with packaging for returns.",
        "Refunds are processed within 5-7 business days after return approval."
      ],
      
      // Company Information
      'about': [
        "GrabBasket is your one-stop destination for quality furniture and home essentials.",
        "We've been serving customers with premium products and excellent service.",
        "Our mission is to make shopping convenient and affordable for everyone."
      ],
      'contact': [
        "You can reach us through the contact form on our website.",
        "Our customer service email and phone numbers are available in the footer.",
        "We're here to help with any questions or concerns you may have."
      ]
    };

    // Chatbot State
    let chatbotOpen = false;

    // Toggle Chatbot
    function toggleChatbot() {
      const window = document.getElementById('chatbotWindow');
      const toggle = document.getElementById('chatbotToggle');
      
      if (chatbotOpen) {
        closeChatbot();
      } else {
        window.style.display = 'flex';
        chatbotOpen = true;
        toggle.style.transform = 'scale(0.9)';
      }
    }

    // Close Chatbot
    function closeChatbot() {
      const window = document.getElementById('chatbotWindow');
      const toggle = document.getElementById('chatbotToggle');
      
      window.style.display = 'none';
      chatbotOpen = false;
      toggle.style.transform = 'scale(1)';
    }

    // Send Message
    function sendMessage() {
      const input = document.getElementById('chatbotMessageInput');
      const message = input.value.trim();
      
      if (message) {
        addUserMessage(message);
        input.value = '';
        
        // Show typing indicator
        showTypingIndicator();
        
        // Get bot response
        setTimeout(() => {
          hideTypingIndicator();
          const response = getBotResponse(message);
          addBotMessage(response);
        }, 1000 + Math.random() * 1000);
      }
    }

    // Handle Enter Key
    function handleChatbotKeypress(event) {
      if (event.key === 'Enter') {
        sendMessage();
      }
    }

    // Quick Question
    function askQuestion(question) {
      const input = document.getElementById('chatbotMessageInput');
      input.value = question;
      sendMessage();
    }

    // Add User Message
    function addUserMessage(message) {
      const messagesContainer = document.getElementById('chatbotMessages');
      
      const messageDiv = document.createElement('div');
      messageDiv.className = 'message user-message';
      messageDiv.innerHTML = `
        <div class="message-avatar">
          <i class="bi bi-person"></i>
        </div>
        <div class="message-content">
          <p>${message}</p>
        </div>
      `;
      
      messagesContainer.appendChild(messageDiv);
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Add Bot Message
    function addBotMessage(message) {
      const messagesContainer = document.getElementById('chatbotMessages');
      
      const messageDiv = document.createElement('div');
      messageDiv.className = 'message bot-message';
      messageDiv.innerHTML = `
        <div class="message-avatar">
          <i class="bi bi-robot"></i>
        </div>
        <div class="message-content">
          <p>${message}</p>
        </div>
      `;
      
      messagesContainer.appendChild(messageDiv);
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Show Typing Indicator
    function showTypingIndicator() {
      const messagesContainer = document.getElementById('chatbotMessages');
      
      const typingDiv = document.createElement('div');
      typingDiv.id = 'typingIndicator';
      typingDiv.className = 'typing-indicator';
      typingDiv.innerHTML = `
        <div class="typing-dots">
          <div class="typing-dot"></div>
          <div class="typing-dot"></div>
          <div class="typing-dot"></div>
        </div>
        <span class="ms-2 text-muted">Assistant is typing...</span>
      `;
      
      messagesContainer.appendChild(typingDiv);
      messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Hide Typing Indicator
    function hideTypingIndicator() {
      const typingIndicator = document.getElementById('typingIndicator');
      if (typingIndicator) {
        typingIndicator.remove();
      }
    }

    // Get Bot Response
    function getBotResponse(userMessage) {
      const message = userMessage.toLowerCase();
      
      // Check for greetings
      if (message.includes('hello') || message.includes('hi') || message.includes('vanakkam') || message.includes('வணக்கம்')) {
        return "🙏 வணக்கம்! Hello! Welcome to GrabBasket! How can I assist you today? You can ask about our products, orders, delivery, or anything else you'd like to know!";
      }
      
      // Check for thanks
      if (message.includes('thank') || message.includes('thanks')) {
        return "😊 You're welcome! I'm always here to help. Is there anything else you'd like to know about GrabBasket?";
      }
      
      // Search knowledge base
      for (const [key, responses] of Object.entries(chatbotKnowledge)) {
        if (message.includes(key)) {
          const randomResponse = responses[Math.floor(Math.random() * responses.length)];
          return randomResponse + "\n\n💬 Need more help? Feel free to ask anything else!";
        }
      }
      
      // Check for specific keywords
      if (message.includes('price') || message.includes('cost')) {
        return "💰 Our products have competitive prices with regular discounts! You can see the exact price on each product page. We also offer free delivery on orders above ₹999. Would you like to know about any specific product?";
      }
      
      if (message.includes('discount') || message.includes('offer')) {
        return "🎉 We regularly have amazing discounts and flash sales! Check our homepage for current offers. You can also sign up for our newsletter to get notified about exclusive deals!";
      }
      
      if (message.includes('quality') || message.includes('genuine')) {
        return "✅ We guarantee 100% genuine products with quality assurance. All our items go through strict quality checks before shipping. We also offer easy returns if you're not satisfied!";
      }
      
      // Default responses
      const defaultResponses = [
        "🤔 I understand you're asking about that. Let me help you! Could you please be more specific? You can ask about products, orders, delivery, pricing, or support.",
        "💡 That's a great question! I'm here to help with anything related to GrabBasket. Try asking about our products, how to place orders, delivery information, or customer support.",
        "🌟 Thanks for your question! I can help you with product information, ordering process, delivery details, pricing, returns, or any other GrabBasket-related queries. What specifically would you like to know?",
        "🛍️ I'd love to help you with that! I can assist with product categories, shopping guidance, account management, order tracking, or general information about GrabBasket. What can I help you find?"
      ];
      
      return defaultResponses[Math.floor(Math.random() * defaultResponses.length)];
    }

    // Initialize chatbot on page load
    document.addEventListener('DOMContentLoaded', function() {
      // Add welcome animation to toggle button
      setTimeout(() => {
        const toggle = document.getElementById('chatbotToggle');
        toggle.style.animation = 'chatbotBounce 2s infinite, chatbotPulse 3s infinite';
      }, 2000);
    });
  </script>
</body>

</html>