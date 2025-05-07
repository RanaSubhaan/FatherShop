# FatherShop - Laravel Email Campaign Package

FatherShop is a Laravel package that allows sending email campaigns to imported customers. It provides APIs to import customers, filter audiences, create campaigns, and send emails asynchronously using queues. Built with an API-first approach and using [smtp.mailersend.net](https://www.mailersend.com/) as the email provider.

---

## 📦 Features

- Import customer data
- Filter customers by status and expiry date
- Create email campaigns
- Send emails via queue using MailerSend SMTP
- Track email delivery (sent or failed)
- Reusable RESTful APIs

---

## 🛠 Installation

1. **Clone the repo:**

```bash
git clone https://github.com/YOUR_USERNAME/FatherShop.git
cd FatherShop
composer install
cp .env.example .env
php artisan key:generate
