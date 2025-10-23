# NewsAggregator

A Laravel-based **News Aggregator Backend** that automatically fetches, stores, and exposes news articles from multiple trusted sources like **NewsAPI**, **New York Times**, and **BBC News**.

This project demonstrates clean architecture, SOLID principles, and DRY backend design using Laravel.

---

## Features

- Aggregates news from **3 live APIs**
- Stores data in normalized tables (`articles`, `sources`, `authors`, `categories`)
- Provides clean RESTful endpoints for frontend use
- Built with **Laravel best practices (DRY, SOLID, KISS)**
- Easily extendable for more news providers

---

## Tech Stack

- **PHP 8.0+**
- **MySQL 8**
- **Laravel**
- **Guzzle HTTP (API requests)**
- **Carbon (datetime parsing)**

---

## Installation Guide

### Clone the Repository
```bash
git clone https://github.com/yourusername/NewsAggregator.git
cd NewsAggregator
```

### Install Dependencies
```bash
composer install
```

### Set Up Environment
Copy .env.example to .env:
```bash
cp .env.example .env
```

Generate your Laravel app key:
```bash
php artisan key:generate
```

### Configure Database
Edit your .env file:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=news_aggregator
DB_USERNAME=root
DB_PASSWORD=
```

### Configure Database
```bash
php artisan migrate
```

### Fetch News Command
Fetch and store latest news from all sources:
```bash
php artisan news:fetch
```
