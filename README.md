# Laravel Shopping Cart Setup Guide

## Prerequisites
Ensure you have the following installed:
- PHP (>= 8.0)
- Composer
- MySQL or MariaDB
- Node.js & npm
- Laravel CLI

## Step 1: Clone the Repository
```sh
git clone https://github.com/your-repo/your-project.git
cd your-project
```

## Step 2: Install Dependencies
```sh
composer install
npm install && npm run dev
```

## Step 3: Configure Environment Variables
Copy the `.env.example` file and rename it to `.env`:
```sh
cp .env.example .env
```
Update the following values in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

RAZORPAY_KEY=your_razorpay_key
RAZORPAY_SECRET=your_razorpay_secret
```

## Step 4: Set Up Database
```sh
php artisan migrate --seed
```

## Step 5: Generate Application Key
```sh
php artisan key:generate
```

## Step 6: Serve the Application
```sh
php artisan serve
```

## Step 7: Configure Razorpay Webhook
Set up Razorpay webhooks to send payment status updates:
1. Log in to [Razorpay Dashboard](https://dashboard.razorpay.com/)
2. Navigate to Webhooks under Settings
3. Add a new webhook URL: `https://yourdomain.com/razorpay/callback`
4. Select events: `payment.captured`, `payment.failed`
5. Save webhook settings

## Step 8: Handling Payment Response
Ensure your routes handle Razorpay responses properly:
```php
Route::post('/razorpay/callback', [PaymentController::class, 'handlePayment'])->name('razorpay.callback');
```
Modify `PaymentController` to redirect users on success or failure:
```php
public function handlePayment(Request $request) {
    if ($request->status == 'captured') {
        return redirect()->route('product.page')->with('success', 'Order has been placed successfully!');
    } else {
        return redirect()->route('product.page')->with('error', 'Payment failed, please try again.');
    }
}
```

## Additional Notes
- Run `php artisan cache:clear && php artisan config:clear` if you face any caching issues.
- Ensure `.env` file is correctly configured for database and Razorpay API keys.

Happy coding! 🚀

