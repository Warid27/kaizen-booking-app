🏢 Room Booking Website
A simple room booking web application built with Laravel 12 as a full-stack framework.This project combines Laravel Blade, Vite, Alpine.js, TailwindCSS, and Lucide Icons for the frontend, while the backend uses Laravel API routes consumed via Axios.

🚀 Tech Stack

Backend & Frontend: Laravel 12
Bundler: Vite
JavaScript Framework: Alpine.js
CSS Framework: TailwindCSS
Icons: Lucide Icons
HTTP Client: Axios


📂 Project Structure
project-root/
├── app/              # Laravel backend
├── resources/
│   ├── js/           # Frontend JS (Alpine.js, Axios calls)
│   ├── css/          # TailwindCSS styles
│   └── views/        # Blade templates
├── routes/           # Web & API routes
├── public/           # Public assets
├── vite.config.js    # Vite configuration
├── package.json      # NPM dependencies
├── composer.json     # PHP dependencies


⚙️ Setup & Installation

Clone Repository
git clone <repo-url>
cd <project-folder>


Install PHP Dependencies
composer install


Install Node.js Dependencies
npm install


Set Up Environment
cp .env.example .env
php artisan key:generate


Configure the database connection in the .env file.


Run Database Migrations
php artisan migrate




▶️ Running the Project
In two separate terminals, run:
php artisan serve

npm run dev

The app will be available at:👉 http://127.0.0.1:8000

🔑 Features

Room listing and availability checking
Room booking and cancellation
API-based communication using Axios
Modern, responsive UI with TailwindCSS
Lightweight interactivity with Alpine.js
Clean icon system with Lucide Icons


📌 Notes

Both frontend and backend are contained within the same Laravel 12 project folder.
Views use Blade templates with JavaScript for dynamic components.
API requests are handled via Axios.
