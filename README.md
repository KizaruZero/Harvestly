# 🌱 Harvestly

**A Modern E-commerce Platform for Fresh Produce with Integrated Payment Gateway**

Harvestly is a full-stack e-commerce application designed specifically for fresh produce businesses. Built with Laravel 11 and React/TypeScript, it provides a seamless shopping experience with integrated Midtrans payment gateway for secure transactions.

## ✨ Features

### 🛒 **E-commerce Core**

- **Product Catalog**: Browse fresh produce with high-quality images
- **Shopping Cart**: Add to cart with quantity management
- **Stock Management**: Real-time stock tracking and validation
- **Order Management**: Complete order lifecycle from pending to completed

### 💳 **Payment Integration**

- **Midtrans Gateway**: Secure payment processing
- **Multiple Payment Methods**: Credit cards, bank transfers, e-wallets
- **Real-time Notifications**: Webhook integration for payment status updates
- **Transaction Security**: Signature verification and idempotency checks

### 🔐 **Authentication & Security**

- **Laravel Sanctum**: API authentication with session-based tokens
- **Two-Factor Authentication**: Enhanced security for user accounts
- **CSRF Protection**: Built-in Laravel security features
- **Input Validation**: Comprehensive request validation

### 🎨 **Modern UI/UX**

- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Component Library**: Reusable UI components with shadcn/ui
- **Dark/Light Mode**: Theme switching capability
- **Interactive Elements**: Smooth animations and transitions

## 🚀 Tech Stack

### **Backend**

- **Laravel 11**: PHP framework with modern features
- **MySQL**: Relational database for data persistence
- **Laravel Sanctum**: API authentication
- **Midtrans SDK**: Payment gateway integration

### **Frontend**

- **React 18**: Modern JavaScript library
- **TypeScript**: Type-safe development
- **Inertia.js**: SPA-like experience without API complexity
- **Tailwind CSS**: Utility-first CSS framework
- **shadcn/ui**: High-quality component library

### **Development Tools**

- **Vite**: Fast build tool and dev server
- **ESLint**: Code linting and formatting
- **Prettier**: Code formatting
- **PHPUnit**: Backend testing

## 📦 Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18+ and npm
- MySQL 8.0+
- Git

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/harvestly.git
cd harvestly
```

### 2. Backend Setup

```bash
# Install PHP dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure database in .env file
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=harvestly
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed
```

### 3. Frontend Setup

```bash
# Install Node.js dependencies
npm install

# Build assets for development
npm run dev

# Or build for production
npm run build
```

### 4. Midtrans Configuration

```bash
# Add to .env file
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_IS_PRODUCTION=false
```

### 5. Start Development Server

```bash
# Start Laravel server
php artisan serve

# In another terminal, start Vite dev server
npm run dev
```

Visit `http://localhost:8000` to see the application.

## 🏗️ Project Structure

```
harvestly/
├── app/
│   ├── Http/Controllers/     # API controllers
│   │   ├── ProductController.php
│   │   ├── OrderController.php
│   │   └── MidtransWebhookController.php
│   ├── Models/               # Eloquent models
│   │   ├── Product.php
│   │   ├── Order.php
│   │   └── User.php
│   └── Http/Middleware/      # Custom middleware
├── resources/
│   ├── js/
│   │   ├── components/       # React components
│   │   ├── pages/           # Page components
│   │   ├── layouts/         # Layout components
│   │   └── types/           # TypeScript definitions
│   └── css/                 # Stylesheets
├── routes/
│   ├── api.php              # API routes
│   ├── web.php              # Web routes
│   └── auth.php             # Authentication routes
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
└── public/
    └── images/             # Product images
```

## 🔄 API Endpoints

### Products

- `GET /api/products` - Get all products
- `POST /api/products` - Create new product (admin)

### Orders

- `POST /api/orders` - Create new order
- `GET /api/orders` - Get user orders
- `GET /api/orders/{id}` - Get order details
- `PUT /api/orders/{id}` - Update order status
- `DELETE /api/orders/{id}` - Cancel order

### Webhooks

- `POST /api/midtrans/webhook` - Midtrans payment notifications

## 💳 Payment Flow

1. **Order Creation**: User selects products and quantities
2. **Stock Validation**: Backend validates available stock
3. **Order Processing**: Order created with 'pending' status
4. **Payment Gateway**: Midtrans snap token generated
5. **Payment Processing**: User completes payment
6. **Webhook Notification**: Midtrans sends payment status
7. **Order Update**: Status updated and stock reduced
8. **User Notification**: Confirmation sent to user

## 🧪 Testing

```bash
# Run PHP tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 🚀 Deployment

### Production Environment

1. Set `APP_ENV=production` in `.env`
2. Set `MIDTRANS_IS_PRODUCTION=true`
3. Configure production database
4. Run `npm run build` for optimized assets
5. Set up webhook URL in Midtrans dashboard

### Environment Variables

```env
APP_NAME=Harvestly
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=your_db_host
DB_DATABASE=harvestly_prod
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

MIDTRANS_SERVER_KEY=your_production_server_key
MIDTRANS_CLIENT_KEY=your_production_client_key
MIDTRANS_IS_PRODUCTION=true
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com/) - The PHP framework
- [React](https://reactjs.org/) - The JavaScript library
- [Midtrans](https://midtrans.com/) - Payment gateway
- [Tailwind CSS](https://tailwindcss.com/) - CSS framework
- [shadcn/ui](https://ui.shadcn.com/) - Component library

## 📞 Support

For support, email support@harvestly.com or create an issue in this repository.

---

**Built with ❤️ for fresh produce businesses**
