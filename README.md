# GeneDecode — Website

GeneDecode is a standalone Laravel-based membership platform being developed to replace the existing Uscreen membership website.

The platform will provide member authentication, subscription management, gated video content, live streaming, account management, and supporting website functionality.

## Tech Stack

- Laravel
- PHP
- Blade
- JavaScript
- HTML / CSS
- SQLite for local development
- Stripe for subscriptions and payments
- Bunny.net for video hosting and signed video playback
- Mux or Bunny.net for live streaming (final provider to be confirmed)
- Pusher / Laravel Reverb for live chat (fast-follow)
- Google Tag Manager
- Google Analytics 4

## Project Structure

```text
app/
├── Http/
├── Models/
└── Providers/

database/
├── factories/
├── migrations/
└── seeders/

public/
└── site/
    ├── assets/
    ├── css/
    └── js/

resources/
└── views/
    └── site/
        ├── layouts/
        ├── partials/
        └── *.blade.php

routes/
└── web.php

docs/
└── static-design/
    ├── assets/
    ├── *.html
    ├── app.js
    ├── catalog.js
    ├── styles.css
    └── README.md
````

## Frontend Structure

The website frontend is organized using Laravel Blade views.

### Layout

`resources/views/site/layouts/app.blade.php`

Contains the common HTML structure, stylesheet, JavaScript files, header, footer, and page content area.

### Header and Footer

Shared components are located at:

```text
resources/views/site/partials/header.blade.php
resources/views/site/partials/footer.blade.php
```

### Pages

Website pages are located under:

```text
resources/views/site/
```

Current pages include:

* Home
* About Gene Decode
* Schedule
* Surface Area
* Interviews & Videos
* Deep Dives
* Community
* Donate
* FAQ
* Join Us
* Account
* Contact
* Live
* Privacy
* Terms & Conditions
* Subscriber Agreement
* Watch

### Public Assets

Frontend assets are stored under:

```text
public/site/
```

This includes:

* CSS
* JavaScript
* Logos
* Images
* Video thumbnails

## Local Development

### Requirements

Make sure the following are installed:

* PHP
* Composer
* Node.js / npm
* Git

### Installation

Clone the repository and enter the project directory:

```bash
git clone <repository-url>
cd genedecode-web
```

Install PHP dependencies:

```bash
composer install
```

Create the environment file.

For Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate the Laravel application key:

```bash
php artisan key:generate
```

Run the database migrations:

```bash
php artisan migrate
```

Start the Laravel development server:

```bash
php artisan serve
```

The application will be available at:

```text
http://127.0.0.1:8000
```

## Database

SQLite is currently used for local development.

The production database configuration will be finalized as part of the deployment and infrastructure setup.

## Membership and Payments

The platform will support the following membership plans:

* Monthly — $7/month
* Annual — $77/year

Stripe will be integrated using the client's existing Stripe account.

The integration will include:

* Checkout
* Subscription creation
* Webhooks
* Subscription status management
* Plan changes
* Cancellation
* Payment status synchronization
* Member and subscription reconciliation

Stripe integration will initially be tested in test mode before production activation.

## Video Platform

Bunny.net will be used for video hosting and signed playback.

The platform will support:

* Video library
* Member-only videos
* Gated video playback
* Video detail pages
* Video collections and categories
* Continue watching
* Playback progress
* Up-next content
* Video migration from the existing platform

The client will provide the existing video files for bulk migration.

## Live Streaming

Core live streaming is part of the launch scope.

The planned live functionality includes:

* Scheduled live events
* Member access control
* Live video playback
* Upcoming event information
* Automatic archiving of live sessions to video-on-demand

The final live-streaming provider will be confirmed during implementation.

Advanced live functionality such as multi-source streaming and live chat is planned as fast-follow functionality.

## Authentication and Member Accounts

The platform will provide:

* Member registration
* Login
* Logout
* Password reset
* Account management
* Membership status
* Subscription information
* Billing management
* Plan changes
* Subscription cancellation
* Member migration and first-login onboarding

## Analytics and Email

The platform will integrate:

* Google Tag Manager
* Google Analytics 4
* Transactional email

Existing analytics configuration will be reused where applicable.

## Development Priorities

### Launch-Critical

The initial production release focuses on:

* Authentication and member accounts
* Membership and Stripe subscriptions
* Gated video library and playback
* Core website pages and branding
* Member migration
* Stripe/member reconciliation
* Transactional emails
* Core live streaming
* Redirects from the existing platform
* Analytics
* Production readiness and QA

### Fast-Follow

The following functionality is planned for a later phase:

* Community
* Live chat
* Advanced live streaming
* Full/advanced search and discovery
* Owner analytics dashboard

Basic search and other P2 functionality may be included during the main development cycle where appropriate.

## Static Design Backup

The original static GeneDecode website design has been preserved in:

```text
docs/static-design/
```

This directory contains the original HTML/CSS/JavaScript implementation and assets used as the design reference during the Laravel conversion.

The static design should be treated as a reference and backup and not as the primary application implementation.

## Development Workflow

Development is performed on feature branches where applicable.

Before committing changes:

1. Test the affected functionality locally.
2. Verify Laravel routes and Blade views.
3. Verify frontend assets and responsive behavior.
4. Check that sensitive files such as `.env` are not committed.
5. Run the relevant tests.
6. Review the Git diff before committing.

## Testing

Laravel tests are located under:

```text
tests/
```

Run the test suite using:

```bash
php artisan test
```

## Deployment

The production deployment process, hosting environment, database configuration, queue configuration, storage configuration, and CI/CD process will be finalized during the infrastructure and deployment phase.

## Project Status

The initial Laravel conversion of the static GeneDecode website has been completed.

Current foundation includes:

* Laravel application structure
* Laravel Blade-based website
* Shared header and footer
* Website routes
* Public website pages
* Static assets
* Video catalog structure
* Video detail/watch page
* Responsive frontend foundation
* Original static design preserved under `docs/static-design/`

Upcoming development includes:

* Authentication
* Membership and Stripe integration
* Bunny.net video integration
* Gated video playback
* Member migration
* Transactional email
* Live streaming
* Admin functionality
* QA and testing
* Production deployment

## License

This project is proprietary software developed for GeneDecode.

The source code and project assets may not be redistributed, copied, or used outside the authorized project scope without permission.

Third-party packages and dependencies remain subject to their respective licenses.

