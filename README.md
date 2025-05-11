# Neembly Exam — Laravel 10 + Vue 3 + Vuetify 3 App

This project is using **Laravel 10** as the backend and **Vue 3 + Vuetify 3** for the frontend, powered by **Vite**.

---

## ⚙️ System Requirements

- PHP >= 8.1
- Composer
- Node.js >= 16
- NPM >= 8
- MySQL or compatible database
- Laravel CLI or Laragon (recommended for Windows)

---

## 🚀 Getting Started

Follow these steps to clone and run the system:

### 1. Clone the Project

```bash
git clone https://github.com/your-username/neembly-exam.git
cd neembly-exam


laravel backend set up
composer install
cp .env.example .env
php artisan key:generate

update the .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

run : php artisan migrate


frontend set up
npm install vue@3
npm install vuetify@3.3.7 @mdi/font sass sass-loader@^13.0.2
npm install vue-router@4
npm install --save-dev @vitejs/plugin-vue

run: npm run dev
