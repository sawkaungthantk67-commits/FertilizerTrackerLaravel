# Fertilizer Tracker

A Laravel web application for tracking fertilizer usage across plots.

## Features
- Fertilizer catalog: add, edit and view fertilizers
- Plot management: add and edit plots
- Applications: record which fertilizer was applied to which plot
- Reports: summary of fertilizer usage

## Tech Stack
- PHP, Laravel
- MySQL
- Blade templates, CSS, Vite

## How to Run
1. `git clone https://github.com/sawkaungthantk67-commits/FertilizerTrackerLaravel.git`
2. `composer install`
3. `cp .env.example .env`, then `php artisan key:generate`
4. Set your database name and password in `.env`
5. `php artisan migrate`
6. `php artisan serve`
7. Open `http://localhost:8000`
