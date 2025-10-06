# 🛒 GrabBasket E-Commerce Platform

A comprehensive Laravel-based e-commerce platform with advanced features including multi-vendor support, SMS notifications, email marketing, and courier tracking.

## 🌟 Features

### 🏪 **Multi-Vendor Platform**
- **Seller Dashboard**: Product management, order tracking, sales analytics
- **Buyer Dashboard**: Shopping, order history, wishlist, notifications
- **Admin Panel**: Complete control over users, products, orders, and system settings

### 📱 **Communication System**
- **SMS Integration**: Infobip API integration for payment confirmations and order notifications
- **Email Marketing**: Promotional email campaigns with multiple templates
- **In-App Notifications**: Amazon-like notification system with bell icons

### 🚚 **Logistics & Tracking**
- **Universal Courier Tracking**: Support for 8+ courier services (Delhivery, BlueDart, DTDC, etc.)
- **Real-time Tracking**: Automatic status updates across all user dashboards
- **Shipping Management**: Integrated tracking links for buyers and sellers

### 💳 **Payment & Orders**
- **Order Management**: Complete order lifecycle from placement to delivery
- **Payment Processing**: Secure payment integration
- **Order Tracking**: Real-time status updates and delivery confirmations

## 🛠️ Tech Stack

- **Backend**: Laravel 12.28.1
- **Frontend**: Blade Templates, Bootstrap 5, JavaScript
- **Database**: MySQL
- **SMS Service**: Infobip API
- **Email**: Laravel Mail with multiple templates
- **Asset Management**: Vite
- **Styling**: Bootstrap 5 + Custom CSS

## 📦 Installation

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Infobip Account (for SMS)

### Setup Steps

1. **Clone Repository**
   ```bash
   git clone https://github.com/grabbaskets-hash/grabbaskets10.git
   cd grabbaskets10
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Configure Services**
   Update `.env` with your credentials:
   ```
   # Database
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=grabbasket
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   # Mail Configuration
   MAIL_MAILER=smtp
   MAIL_HOST=your_smtp_host
   MAIL_PORT=587
   MAIL_USERNAME=your_email
   MAIL_PASSWORD=your_password

   # Infobip SMS
   INFOBIP_API_KEY=your_infobip_api_key
   INFOBIP_BASE_URL=https://your_subdomain.api.infobip.com
   INFOBIP_SENDER=YourSender
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

## 🚀 Key Features Implementation

### 📧 **Email Marketing System**
- Promotional email campaigns
- Multiple email templates
- Automated buyer notifications
- Admin email management dashboard

### 📱 **SMS Integration**
- Payment confirmation messages
- Order status updates
- Shipping notifications
- Promotional SMS campaigns
- Demo mode support with whitelist management

### 🔔 **Notification System**
- Amazon-like bell notifications
- Real-time in-app alerts
- Email and SMS notification preferences
- Admin promotional notification management

### 📊 **Courier Tracking**
- Universal tracking API supporting multiple couriers
- Auto-detection of courier services
- Real-time tracking updates
- Integrated tracking links in all dashboards

## 🗂️ Project Structure

```
├── app/
│   ├── Http/Controllers/     # Controllers for all modules
│   ├── Models/              # Eloquent models
│   ├── Services/            # Business logic services
│   │   ├── InfobipSmsService.php
│   │   ├── CourierTrackingService.php
│   │   └── NotificationService.php
│   └── Console/Commands/    # Artisan commands
├── resources/
│   ├── views/              # Blade templates
│   │   ├── admin/          # Admin panel views
│   │   ├── seller/         # Seller dashboard views
│   │   ├── buyer/          # Buyer interface views
│   │   └── emails/         # Email templates
│   └── css/js/            # Frontend assets
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/           # Database seeders
└── public/
    ├── asset/images/      # Application images
    └── storage/           # File uploads
```

## 🔧 **Admin Features**
- **Dashboard**: Sales analytics, order management, user overview
- **User Management**: Manage buyers and sellers
- **Product Management**: Approve products, manage categories
- **Order Management**: Process orders, update statuses
- **SMS Management**: Send promotional SMS, test delivery
- **Email Campaigns**: Create and send promotional emails
- **Notification System**: Manage in-app notifications

## 👥 **User Roles**

### 🛒 **Buyers**
- Browse and search products
- Add to cart and wishlist
- Place orders and track delivery
- Receive notifications
- Manage profile and addresses

### 🏪 **Sellers**
- Manage product catalog
- Process orders
- Track sales and analytics
- Receive order notifications
- Update inventory

### ⚙️ **Admins**
- Complete system control
- User and product management
- Order processing
- Marketing campaigns
- System analytics

## 📱 **SMS Features**
- **Payment Confirmations**: Automatic SMS on successful payments
- **Order Updates**: Status changes and shipping notifications
- **Promotional Messages**: Marketing campaigns via SMS
- **OTP Verification**: Secure authentication
- **Demo Mode Support**: Whitelist management for testing

## 🧪 **Testing Commands**

```bash
# Test SMS integration
php artisan sms:test-demo

# Test with current sellers
php artisan sms:test-sellers

# Check delivery reports
php artisan sms:check-delivery

# Test email notifications
php artisan notifications:send-promotions --type=daily --user-type=buyers

# Simulate SMS messages
php artisan sms:simulate-received
```

## 🔗 **API Integrations**

### Infobip SMS API
- Real-time SMS delivery
- Delivery reports
- International SMS support
- Demo mode for testing

### Courier Tracking APIs
- Delhivery, BlueDart, DTDC
- India Post, FedEx, Aramex
- Real-time tracking updates
- Auto-courier detection

## 📋 **Recent Updates**

- ✅ Complete SMS integration with Infobip
- ✅ Universal courier tracking system
- ✅ Enhanced notification system
- ✅ Email marketing campaigns
- ✅ Admin promotional dashboard
- ✅ GrabBasket branding implementation
- ✅ Mobile-responsive design
- ✅ Demo mode SMS testing

## 🤝 **Contributing**

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 **License**

This project is proprietary software developed for GrabBasket.

## 📞 **Support**

For support and questions:
- Create an issue in this repository
- Contact the development team

---

**🎯 Built with ❤️ for GrabBasket E-Commerce Platform**
