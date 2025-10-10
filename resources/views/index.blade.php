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
      background: rgba(139, 69, 19, 0.05);
      border: 1px solid rgba(139, 69, 19, 0.1);
    }

    /* 🌟 Diwali Theme Banner - Enhanced Mobile & Desktop Responsive */
    .diwali-theme-banner {
      background-image: url("/images/diwali-bg1.jpg");
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      border-radius: 15px;
      min-height: 300px;
      position: relative;
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    /* Overlay for better contrast */
    .diwali-theme-banner::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.4);
      border-radius: 15px;
      z-index: 1;
    }

    /* 🔥 Enhanced Text Glow for better readability */
    .text-glow {
      text-shadow: 
        0 0 5px #ffd700, 
        0 0 10px #ffae42, 
        0 0 20px #ffa500,
        2px 2px 4px rgba(0,0,0,0.3);
    }

    /* Ensure content stays above all effects */
    .diwali-theme-banner > * {
      position: relative;
      z-index: 5;
      text-align: center;
    }

    /* 🪔 Optional: Floating Diya Glow (light orbs) */
    .diwali-theme-banner::before,
    .diwali-theme-banner::after {
      pointer-events: none;
    }

    /* Enhanced Responsive layout */
    @media (max-width: 576px) {
      .diwali-theme-banner {
        min-height: 250px;
        padding: 15px;
        border-radius: 10px;
      }

      .diwali-theme-banner h1 {
        font-size: 1.5rem !important;
        margin-bottom: 10px;
      }

      .diwali-theme-banner p {
        font-size: 0.9rem !important;
        margin-bottom: 15px;
      }

      .diwali-theme-banner .btn {
        font-size: 0.9rem;
        padding: 8px 16px;
      }
    }

    @media (min-width: 577px) and (max-width: 768px) {
      .diwali-theme-banner {
        min-height: 320px;
        padding: 25px;
      }

      .diwali-theme-banner h1 {
        font-size: 2rem !important;
      }

      .diwali-theme-banner p {
        font-size: 1rem !important;
      }
    }

    @media (min-width: 769px) {
      .diwali-theme-banner {
        min-height: 400px;
        padding: 40px;
        border-radius: 20px;
      }

      .diwali-theme-banner .d-flex {
        text-align: left !important;
      }
    }

    @media (min-width: 992px) {
      .diwali-theme-banner {
        min-height: 450px;
      }
    }





    /* 🪔 Second Diwali Banner Theme - Enhanced Mobile Responsive */
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
  </style>
</head>

<body>
  {{-- @if(session('success'))
  <div class="alert alert-success text-center mt-3 mb-0" role="alert">
    {{ session('success') }}
  </div>
  @endif --}}
  <!-- Modern Enhanced Navbar -->

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
          <input type="text" name="q" placeholder="🔍 Search for products..." class="form-control"
            value="{{ request('q') }}" />
          <button class="btn" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </form>
      </div>
    </div>
  </nav>

  <!-- Simple Menu Button -->
  <button type="button" class="btn btn-lg btn-warning shadow position-fixed"
    style="bottom:32px;right:32px;z-index:1050;border-radius:50%;width:64px;height:64px;display:flex;align-items:center;justify-content:center;font-size:2rem;"
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
  <!-- Hero Section with Enhanced Responsive Carousel -->
  <section class="hero-section">
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
      <div class="carousel-inner">
        {{-- 🎆 Diwali Banner (first slide) - Enhanced Mobile Layout --}}
        <div class="carousel-item active">
          <div class="diwali-theme-banner">
            <div class="container">
              <div class="row align-items-center justify-content-center">
                <div class="col-12 col-lg-8 text-center text-lg-end">
                  <h1 class="fw-bold text-glow text-light mb-3">🪔 Lights, Gifts & More</h1>
                  <p class="fs-5 text-light mb-4">Celebrate Diwali with joy, color, and style — only on GrabBaskets.</p>
                  <div class="banner-buttons">
                    <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg shadow-lg">
                      <i class="bi bi-gift-fill me-2"></i>Shop Gifts
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- 🪔 Diwali Exclusive Offers Banner - Enhanced Mobile Layout -->
        <div class="carousel-item">
          <div class="diwali-theme-banner diwali-theme-banner-2">
            <div class="container">
              <div class="row align-items-center justify-content-center">
                <div class="col-12 col-lg-8 text-center text-lg-start">
                  <h1 class="fw-bold text-glow text-light mb-3">💫 GrabBaskets Diwali Festival</h1>
                  <p class="fs-5 text-light mb-3">Light up your home with grand offers this festive season!</p>
                  <p class="fs-6 text-warning mb-4">✨ Limited Time Offers | 🕓 Hurry! Ends this weekend.</p>
                  <div class="banner-buttons">
                    <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg shadow-lg">
                      <i class="bi bi-lightning-charge-fill me-2"></i>Shop Now & Save Big
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        {{-- 🛍️ Product Banners - Enhanced Responsive Design --}}
        @foreach($products as $index => $product)
          <div class="carousel-item">
            <div class="product-banner-content" style="background: linear-gradient(90deg,#232f3e,#ff9900);">
              <div class="container">
                <div class="row align-items-center">
                  <div class="col-12 col-lg-8">
                    <div class="text-white">
                      <h2 class="h2 fw-bold mb-3 text-glow">
                        🔥 {{ $product->discount ?? 30 }}% OFF – {{ $product->category?->name ?? 'Uncategorized' }}
                      </h2>
                      <p class="mb-2 fs-6">⭐ {{ $product->rating ?? 4.8 }}/5 from {{ $product->reviews_count ?? 500 }}+ happy buyers</p>
                      <p class="mb-4 text-warning fw-bold fs-6">⚡ Hurry! Only {{ $product->stock ?? 10 }} left in stock</p>
                      
                      <div class="banner-buttons">
                        <a href="{{ route('product.details', $product->id) }}" class="btn btn-light text-dark">
                          <i class="bi bi-eye-fill me-2"></i>Shop Now
                        </a>
                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                          @csrf
                          <input type="hidden" name="product_id" value="{{ $product->id }}">
                          <button type="submit" class="btn btn-warning" style="background:#ff9900;color:#232f3e;">
                            <i class="bi bi-cart-plus-fill me-2"></i>Grab Now
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
                  <span class="zepto-cat-icon">
                    @php $cImg = $category->image_url ?? $category->image ?? null; @endphp
                    @if(!empty($cImg))
                      <img src="{{ $cImg }}" alt="{{ $category->name }}" onerror="this.style.display='none'; const fb=this.nextElementSibling; if(fb) fb.classList.remove('d-none');">
                      <span class="zepto-cat-emoji d-none">{!! $category->emoji ?? '🛍️' !!}</span>
                    @else
                      <span class="zepto-cat-emoji">{!! $category->emoji ?? '🛍️' !!}</span>
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

    <!-- Enhanced Floating Category Menu - Mobile & Desktop Responsive -->
    <div class="floating-actions" style="position:fixed;bottom:20px;right:20px;z-index:1200;">
      <button class="fab-main" onclick="toggleFloatingMenu()" style="background:linear-gradient(135deg,#8B4513,#A0522D);color:#fff;border:none;border-radius:50%;padding:12px;box-shadow:0 6px 20px rgba(139,69,19,0.2);font-size:1.6rem;display:flex;align-items:center;justify-content:center;transition:all 0.3s;width:56px;height:56px;cursor:pointer;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
        <span class="fab-icon" style="font-size:1.8rem;"></span>🛍️</span>
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
  </section>
  
  <script>
    function scrollZeptoCats(dir) {
      const track = document.getElementById('zeptoCatTrack');
      if (!track) return;
      const itemWidth = track.firstElementChild ? track.firstElementChild.getBoundingClientRect().width : 100;
      track.scrollBy({ left: dir * (itemWidth * 3), behavior: 'smooth' });
    }
  </script>

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
        <h2 class="mb-3" style="color:#ff0033;"><i class="bi bi-lightning-charge-fill"></i> Flash Sale</h2>
        <div class="shelf" style="border:2px solid #ff0033;box-shadow:0 4px 24px rgba(255,0,51,0.08);">
          <button class="nav-btn nav-prev" onclick="scrollShelf('flash',-1)" style="background:#ff0033;color:#fff;"><i
              class="bi bi-chevron-left"></i></button>
          <div id="shelf-flash" class="shelf-track">
            @foreach($flashSale as $product)
            <div class="shelf-item">
              <div class="card product-card h-100 border-danger">
                <img
                  src="{{ $product->image_url }}"
                  class="card-img-top" alt="{{ $product->name }}"
                  data-fallback="{{ asset('images/no-image.png') }}"
                  onerror="this.src=this.dataset.fallback">
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
                  src="{{ $product->image_url }}"
                  class="card-img-top" alt="{{ $product->name }}"
                  data-fallback="{{ asset('images/no-image.png') }}"
                  onerror="this.src=this.dataset.fallback">
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
                  src="{{ $product->image_url }}"
                  class="card-img-top" alt="{{ $product->name }}"
                  data-fallback="{{ asset('images/no-image.png') }}"
                  onerror="this.src=this.dataset.fallback">
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
                  src="{{ $product->image_url }}"
                  class="card-img-top" alt="{{ $product->name }}"
                  data-fallback="{{ asset('images/no-image.png') }}"
                  onerror="this.src=this.dataset.fallback">
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
  {{-- </section> --}}

  <!-- Products by Category Showcase -->
  <section class="py-5 bg-light">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-4 fw-bold mb-3">🛍️ Shop by Category</h2>
        <p class="lead text-muted">Discover our curated collection of premium products across all categories</p>
      
      </div>
      @if(isset($categoryProducts) && !empty($categoryProducts))
        @foreach($categoryProducts as $categoryName => $products)
          @if($products->count() > 0)
          <div class="mb-5">
            <div class="d-flex align-items-center mb-4">
              <h3 class="h4 fw-bold text-primary mb-0">{{ $categoryName }}</h3>
              <span class="badge bg-primary ms-3">{{ $products->count() }} Products</span>
            </div>
            <div class="row g-3">
              @foreach($products as $product)
              <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12">
                <div class="card product-card h-100 shadow-sm">
                  @php
                    $fallbackUrl = 'https://picsum.photos/300/250?grayscale&text=' . urlencode(str_replace(['&', '+'], ['and', 'plus'], $categoryName));
                  @endphp
                  <img src="{{ $product->image_url }}" 
                       class="card-img-top" 
                       alt="{{ $product->name }}"
                       style="height: 250px; object-fit: cover;"
                       data-fallback="{{ $fallbackUrl }}"
                       onerror="this.src=this.dataset.fallback">
                  <div class="card-body d-flex flex-column">
                    <h6 class="card-title fw-bold">{{ \Illuminate\Support\Str::limit($product->name, 60) }}</h6>
                    <p class="card-text text-muted small">{{ \Illuminate\Support\Str::limit($product->description, 100) }}</p>
                    <div class="mt-auto">
                      @if($product->discount > 0)
                        <div class="price-section mb-2">
                          <span class="fw-bold text-success fs-6">₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                          <small class="text-muted text-decoration-line-through ms-2">₹{{ number_format($product->price, 2) }}</small>
                          <small class="badge bg-danger ms-2">{{ $product->discount }}% OFF</small>
                        </div>
                      @else
                        <div class="price-section mb-2">
                          <span class="fw-bold text-success fs-6">₹{{ number_format($product->price, 2) }}</span>
                        </div>
                      @endif
                      @if($product->stock > 0)
                        <small class="text-success d-block mb-2"><i class="bi bi-check-circle"></i> In Stock ({{ $product->stock }} available)</small>
                      @else
                        <small class="text-danger d-block mb-2"><i class="bi bi-x-circle"></i> Out of Stock</small>
                      @endif
                      <div class="d-grid gap-2">
                        <a href="{{ route('product.details', $product->id) }}" class="btn btn-outline-primary btn-sm">View Details</a>
                        @auth
                          @if($product->stock > 0)
                          <form method="POST" action="{{ route('cart.add') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary btn-sm w-100">Add to Cart</button>
                          </form>
                          @else
                          <button class="btn btn-secondary btn-sm w-100" disabled>Out of Stock</button>
                          @endif
                        @else
                          <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login to Buy</a>
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

  <section class="trending my-5">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="display-5 fw-bold mb-3">🔥 Trending Items</h2>
        <p class="lead text-muted">Most popular products loved by our customers</p>
      </div>
      <div class="row g-4">
        @foreach($trending as $product)
          <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
            <div class="item card shadow-sm h-100 position-relative">
              <!-- Share Button -->
              <div class="position-absolute top-0 end-0 m-2" style="z-index: 10;">
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary rounded-circle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-share"></i>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="shareProductFromHome('{{ $product->id }}', 'whatsapp', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-whatsapp text-success"></i> WhatsApp</a></li>
                    <li><a class="dropdown-item" href="#" onclick="shareProductFromHome('{{ $product->id }}', 'facebook', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-facebook text-primary"></i> Facebook</a></li>
                    <li><a class="dropdown-item" href="#" onclick="shareProductFromHome('{{ $product->id }}', 'twitter', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault;"><i class="bi bi-twitter text-info"></i> Twitter</a></li>
                    <li><a class="dropdown-item" href="#" onclick="shareProductFromHome('{{ $product->id }}', 'copy', '{{ $product->name }}', '{{ $product->price }}'); event.preventDefault();"><i class="bi bi-link-45deg"></i> Copy Link</a></li>
                  </ul>
                </div>
              </div>

              <a href="{{ route('product.details', $product->id) }}" style="text-decoration: none" class="text-dark">
                <div class="image-box p-3">
                  <img src="{{ $product->image_url }}"
                    alt="{{ $product->name }}"
                    class="img-fluid rounded"
                    style="height: 200px; width: 100%; object-fit: cover;"
                    data-fallback="{{ asset('images/no-image.png') }}"
                    onerror="this.src=this.dataset.fallback">
                </div>

                <div class="card-body text-center">
                  <h6 class="card-title fw-bold">{{ \Illuminate\Support\Str::limit($product->name, 40) }}</h6>

                  <div class="stars mb-2">
                    @php $stars = rand(3, 5); @endphp
                    <span class="text-warning">
                      {!! str_repeat('★', $stars) !!}{!! str_repeat('☆', 5 - $stars) !!}
                    </span>
                  </div>

                  @if($product->discount > 0)
                    <div class="price-section">
                      <p class="price fw-bold text-success mb-1">₹{{ number_format($product->price * (1 - $product->discount / 100), 2) }}</p>
                      <small class="text-muted text-decoration-line-through">₹{{ number_format($product->price, 2) }}</small>
                      <small class="badge bg-danger ms-1">{{ $product->discount }}% OFF</small>
                    </div>
                  @else
                    <p class="price fw-bold text-success">₹{{ number_format($product->price, 2) }}</p>
                  @endif
                </div>
              </a>
            </div>
          </div>
        @endforeach
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
            <button class="btn btn-warning px-4 py-2">Explore now</button>
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

    document.addEventListener('DOMContentLoaded', function() {
      // Add click events to floating category cards
      const floatingCategoryCards = document.querySelectorAll('.floating-category-card');
      floatingCategoryCards.forEach(card => {
        card.addEventListener('click', function() {
          const categoryId = this.getAttribute('data-category-id');
          const categoryName = this.getAttribute('data-category-name');
          showCategorySubcategories(categoryId, categoryName);
        });
      });
    });

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

</body>
</html>
