# Fertilizer Usage and Cost Tracker (Laravel)

A Laravel 12 MVC project adapted for the fertilizer tracker shown in the student's PartyRock screenshots. The Assignment 5 student-directory example is used only as a Laravel project-format reference; its student-management features are not part of this project.

## Assignment alignment

- Assignment 3 asks for UI designs for three business processes based on the student's individual project. This project covers fertilizer/plot setup, recording applications, and reviewing usage/costs.
- Assignment 4 asks for Laravel controllers with input validation, Blade resource views, and web routes for the Assignment 3 views. Those files are organized under `app/Http/Controllers`, `resources/views/tracker`, and `routes/web.php`.
- The supplied sample is a Laravel 12 scaffold. This deliverable keeps the full application scaffold and Composer lockfile so it can be installed and run locally.

## Run locally

Requirements: PHP 8.2+, Composer, and Node.js/npm (optional if you do not change front-end assets).

1. Extract this project folder.
2. The supplied ZIP includes the matching Composer dependencies. If you remove the `vendor` folder, run `composer install` to restore them.
3. Copy `.env.example` to `.env`.
4. Run `php artisan key:generate`.
5. Run `php artisan serve` and open the local URL printed by Artisan.

The app uses Laravel's session storage for this assignment prototype. Fertilizer, plot, and application entries remain available for the current session and are not written to a database. This matches the Assignment 3/4 scope shown in the supplied screenshots, which asks for designed views, validation, controllers, and routes but does not specify database persistence. If your instructor requires MySQL persistence, tell me and I can add migrations and models.

## Main pages

- `/catalog`: add fertilizer products and plots.
- `/applications`: record usage against a fertilizer and plot.
- `/reports`: filter the application log and calculate estimated cost from quantity × saved unit price.

Form input is validated server-side. Laravel returns validation messages to the originating page. Routes use named route definitions and POST forms include CSRF protection.
