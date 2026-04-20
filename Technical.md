## VueJobs Tech Blueprint

- **Tech Stack**
  - Backend: Laravel 11, PHP 8.2, MySQL 8 (PostgreSQL optional), Redis queue, Laravel Scout + MeiliSearch, Horizon, Passport.
  - Frontend: Nuxt 3 (SSR), Vue 3 + Composition API, Pinia, Tailwind CSS, VueUse, Nuxt Image, Nuxt Content.
  - DevOps: Vite build, Vitest/Cypress tests, Docker (local), GitHub Actions, Laravel Forge/Vapor deploy, S3 storage, Cloudflare CDN, Stripe + Razorpay payments, Sentry/Telescope monitoring.

- **Initial Database Schema**
  - `users`: basic profile, role (employer/developer/admin), timezone, socials, resume_url, portfolio_url, skill_tags (JSON), verification flags.
  - `companies`: owner (user_id), name, slug, website, logo, team size, about, hiring_regions (JSON), highlighted_until.
  - `jobs`: company_id, title, slug, description, location_type, country, salary_min/max, currency, contract_type, experience_level, vue_version, nuxt_version, tags (JSON), status, featured_until, expires_at.
  - `applications`: job_id, user_id, cover_letter, resume_url, status pipeline, viewed_at timestamps.
  - `subscriptions`: company_id, plan tier, billing_provider, billing_id, seats, renews_at, status.
  - `payments`: company_id, job_id/subscription_id, amount, currency, type, provider, receipt_url, metadata JSON.
  - `alerts`: user_id, saved filter JSON, frequency, last_sent_at.
  - `messages`: sender_id, receiver_id, job_id, body, attachments JSON, read_at (phase 2).
  - `resumes`: user_id, file_url, headline, summary, experience JSON, education JSON, visibility.
  - Supporting: `activity_logs`, `failed_jobs`, `personal_access_tokens`.

- **Feature Roadmap**
  - Phase 1 MVP: employer job posting, Nuxt SSR listings with filters (role, location, Vue/Nuxt versions, TS, remote, experience), candidate signup/profile, resume upload, application flow, email notifications, Stripe/Razorpay checkout, admin moderation.
  - Phase 2: recruiter dashboard (analytics, applicant pipeline, messaging), candidate dashboard (saved jobs, alerts, AI suggestions), resume database search, company branding (spotlights, badges), webhooks + Zapier integrations.
  - Phase 3: job aggregation cron + AI classifier, newsletters, public API, education marketplace, interview prep modules, gamified badges, AI resume review, subscription upsells.

- **Related Considerations**
  - Security: Passport tokens, rate limiting, encrypted resume storage, GDPR compliance, auditing with activity logs.
  - SEO/Performance: Nuxt SSR, OpenGraph, sitemap, structured job posting data, MeiliSearch facets for fast filtering, caching job feeds, queue-based emails.
  - Observability & Automation: Telescope, Sentry, Logtail, cron jobs for expiring listings/alerts, aggregation queues, OpenAI tagging for auto-categorization.
  - Scalability: horizontal-ready Laravel setup (Redis, queues, read replicas later), storage via S3, CDN delivery, feature flagging for gradual rollouts.

---

## Backend Implementation Snapshot (Nov 20, 2025)

- **Core Packages**
  - `laravel/passport` for OAuth2 + personal access tokens.
  - API resources, requests, and controllers scaffolded under `app/Http/Controllers/Api`, `Requests`, and `Resources`.

- **Database & Migrations**
  - Custom tables for `companies`, `jobs`, `job_applications`, `job_alerts`, `subscriptions`, `payments`, `messages`, `resumes`, and `activity_logs`.
  - Queue infrastructure moved to `queue_jobs` table to avoid collision with the job listings table.
  - Passport migrations installed; run `php artisan migrate:fresh` on new environments.

- **Auth & Routing**
  - `config/auth.php` wired so `auth:api` uses Passport.
  - `routes/api.php` exposes public job listings plus authenticated CRUD for companies, jobs, alerts, applications, and messaging.
  - `bootstrap/app.php` now registers the API route file.

- **How to Run Locally**
  - Copy `.env.example` to `.env` and set MySQL creds (`DB_CONNECTION=mysql`, host, port, db, user, pass) plus mail/payment stubs.
  - Install deps: `composer install` + `npm install`.
  - Generate keys & tables: `php artisan key:generate`, `php artisan migrate --seed` (or `migrate:fresh --seed`), `php artisan passport:install`.
  - Serve APIs: `php artisan serve` and queue worker `php artisan queue:listen`.

- **Next Steps**
  - Hook payment webhooks + plan enforcement logic.
  - Add resume parsing/upload storage (S3) and background processing.
  - Implement admin policies + form requests for subscription/billing flows.

---

## Frontend Implementation (Nuxt 3)

- **Location**: `frontend/` directory
- **Stack**: Nuxt 3, Vue 3 Composition API, Pinia, Tailwind CSS, Google Fonts (Inter)
- **Auth**: Cookie-based token storage, Passport Bearer tokens, Google OAuth flow
- **Pages**:
  - Public: Homepage, Job listings with filters, Job detail, Company profile, Login, Register, Google OAuth callback
  - Developer dashboard: Overview, Applications, Saved jobs, Job alerts, Profile edit
  - Employer dashboard: Overview, My jobs, Create job, Companies, Create company
- **How to Run Frontend**:
  - `cd frontend && npm install && npm run dev` (runs on http://localhost:3000)
  - Set `NUXT_PUBLIC_API_BASE=http://localhost:8000/api` in `frontend/.env`


