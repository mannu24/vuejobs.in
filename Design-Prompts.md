# VueJobs: Design Prompts for Web Pages

This document contains detailed design prompts for creating UI/UX designs for VueJobs platform, covering both **Candidate (Developer)** and **Recruiter (Employer)** interfaces.

---

## Design System Guidelines

**Tech Stack:**
- Frontend: Nuxt 3, Vue 3, Tailwind CSS
- Design Style: Modern, clean, professional
- Color Scheme: Vue.js green (#42b883) as primary, with complementary colors
- Typography: Modern sans-serif (Inter, Poppins, or similar)
- Responsive: Mobile-first approach

---

## PUBLIC PAGES (Unauthenticated)

### 1. Landing Page / Homepage
**Prompt:**
Design a modern landing page for VueJobs - a Vue.js-specific job board. Include:
- Hero section with tagline: "Find Vue.js Jobs & Vue Developers"
- Two CTAs: "Find Jobs" (for candidates) and "Post a Job" (for recruiters)
- Stats section: "X Vue Jobs", "Y Developers", "Z Companies"
- Featured job listings (3-4 cards with company logo, title, location, salary range)
- How it works section (3 steps for candidates, 3 steps for recruiters)
- Trust indicators: "Trusted by Vue.js companies"
- Footer with links, social media, newsletter signup

**Key Elements:**
- Vue.js branding colors
- Job cards showing: Company logo, Job title, Location type (Remote/Hybrid/On-site), Salary range, Vue version, Apply button
- Responsive grid layout

---

### 2. Job Listings Page (Public)
**Prompt:**
Design a job search and listing page with advanced filters. Include:
- Search bar at top (search by title, company, skills)
- Left sidebar with filters:
  - Experience Level (Junior, Mid, Senior, Lead)
  - Location Type (Remote, Hybrid, On-site)
  - Country & Timezone dropdowns
  - Vue Version (Vue 2, Vue 3, Both)
  - Nuxt Version (Nuxt 2, Nuxt 3, Both)
  - TypeScript Required (Toggle)
  - Contract Type (Full-time, Contract, Freelance)
  - Salary Range (Min/Max sliders)
  - Skills tags (multi-select)
- Main content area: Job cards in grid/list view toggle
- Each job card shows:
  - Company logo
  - Job title
  - Company name
  - Location (Remote/Hybrid/City, Country)
  - Salary range with currency
  - Vue/Nuxt version badges
  - TypeScript badge (if required)
  - Skills tags (3-4 visible)
  - Posted date
  - "Featured" badge if applicable
  - "View Details" button
- Pagination at bottom
- "Save Search" button (requires login)

**Design Notes:**
- Featured jobs should stand out (border highlight or background color)
- Filter sidebar should be collapsible on mobile
- Job cards should be hover-interactive

---

### 3. Job Detail Page (Public)
**Prompt:**
Design a detailed job posting page. Include:
- Header section:
  - Company logo (large)
  - Job title
  - Company name (clickable)
  - Location badge (Remote/Hybrid/On-site + Country)
  - Salary range with currency
  - Posted date & Expires date
  - "Featured" badge if applicable
- Quick info bar:
  - Experience level
  - Contract type
  - Vue version badge
  - Nuxt version badge
  - TypeScript badge
  - Skills tags (all visible)
- Main content:
  - Job description (rich text, formatted)
  - Requirements section
  - Benefits section (if provided)
  - Company info section (about company, website, size, industry)
- Sidebar:
  - "Apply Now" button (prominent, primary color)
  - "Save Job" button (bookmark icon)
  - "Share Job" button (social share)
  - Similar jobs section (3-4 recommendations)
- Company profile preview card (logo, name, about snippet, "View Company" link)

**Design Notes:**
- Apply button should be sticky on scroll
- Company section should be visually distinct
- Similar jobs should match the job's filters

---

### 4. Company Profile Page (Public)
**Prompt:**
Design a public company profile page. Include:
- Header:
  - Company logo (large)
  - Company name
  - Headline/tagline
  - Website link
  - Industry & Size badges
- About section (company description)
- Tech stack section (Vue, Nuxt, TypeScript, etc. as badges)
- Hiring regions (countries/timezones)
- Active job listings section (grid of job cards)
- "Top Vue Employer" badge if highlighted
- "Follow Company" button (for candidates)

**Design Notes:**
- Clean, professional layout
- Highlight active job count
- Tech stack should be visually prominent

---

### 5. Login / Register Pages
**Prompt:**
Design authentication pages with role selection. Include:
- Login page:
  - Email & Password fields
  - "Remember me" checkbox
  - "Forgot password?" link
  - "Login" button
  - "Don't have an account? Sign up" link
  - Social login options (Google, GitHub) - optional
- Register page:
  - Role selection: "I'm a Developer" / "I'm an Employer" (toggle or cards)
  - Name, Email, Password, Confirm Password
  - Terms & Conditions checkbox
  - "Create Account" button
  - "Already have an account? Login" link
- Design should be clean, centered, with VueJobs branding

---

## CANDIDATE (DEVELOPER) PAGES

### 6. Candidate Dashboard
**Prompt:**
Design a developer dashboard homepage. Include:
- Welcome section with user name and profile completion status
- Quick stats cards:
  - Applications sent (count)
  - Saved jobs (count)
  - Job alerts (count)
  - Profile views (count)
- Recommended jobs section (AI-matched):
  - "Jobs for You" heading
  - 4-6 job cards in horizontal scroll or grid
  - Match percentage badge on each card
  - "View All" link
- Recent applications section:
  - List of last 5 applications with status badges
  - Status: Applied, Reviewed, Shortlisted, Rejected, Hired
  - Quick actions: View job, Withdraw
- Saved jobs section (last 5 saved jobs)
- Quick actions sidebar:
  - "Update Profile"
  - "Upload Resume"
  - "Create Job Alert"
  - "Browse Jobs"

**Design Notes:**
- Dashboard should feel personalized
- Stats should be visually prominent
- Recommended jobs should highlight match reasons

---

### 7. Candidate Profile / Edit Profile Page
**Prompt:**
Design a comprehensive profile management page. Include:
- Profile header:
  - Avatar upload (with preview)
  - Name, Headline, Location fields
  - "Available for work" toggle
- Sections:
  - **About**: Rich text editor for bio
  - **Skills**: Multi-select tags for Vue-specific skills (Vue 2, Vue 3, Nuxt 2, Nuxt 3, Pinia, Vuex, TypeScript, Vite, etc.)
  - **Experience**: Add/edit experience entries (Company, Role, Duration, Description)
  - **Education**: Add/edit education (Institution, Degree, Year)
  - **Links**: 
    - LinkedIn URL
    - GitHub URL
    - Portfolio URL
    - Website URL
  - **Resume**: 
    - Upload resume (PDF/DOC)
    - Current resume preview/download
    - Resume visibility toggle (Public/Private)
  - **Settings**:
    - Timezone selection
    - Email notifications preferences
    - Profile visibility settings
- Save/Cancel buttons
- Profile preview section (how it looks to employers)

**Design Notes:**
- Form should be well-organized with clear sections
- Skills should use tag/chip UI
- Resume upload should show file size and format validation

---

### 8. Job Search Page (Candidate View)
**Prompt:**
Similar to public job listings but with candidate-specific features:
- All features from public job listings page
- Additional features:
  - "Save Job" button on each card (bookmark icon)
  - Match percentage indicator (if logged in)
  - "Quick Apply" button (one-click apply with saved resume)
  - Filter: "Show only jobs I haven't applied to"
  - Filter: "Show only remote jobs"
- Saved searches section (if any saved)
- Job alerts management link

---

### 9. Application History Page
**Prompt:**
Design a page showing all job applications. Include:
- Filter tabs: All, Applied, Reviewed, Shortlisted, Rejected, Hired
- Each application card shows:
  - Company logo
  - Job title
  - Company name
  - Applied date
  - Status badge (color-coded)
  - Last updated timestamp
  - Cover letter preview (if provided)
  - Actions: View job, View application, Withdraw
- Empty state: "No applications yet" with "Browse Jobs" CTA
- Pagination

**Design Notes:**
- Status badges should be color-coded (Applied: blue, Reviewed: yellow, Shortlisted: green, Rejected: red, Hired: purple)
- Cards should be sortable by date

---

### 10. Saved Jobs Page
**Prompt:**
Design a page for saved/bookmarked jobs. Include:
- Grid/list view of saved jobs
- Each card shows same info as job listing cards
- "Remove from saved" button on each card
- Filter: "Show only active jobs" (not expired)
- Empty state: "No saved jobs" with "Browse Jobs" CTA
- Bulk actions: "Remove selected"

---

### 11. Job Alerts Page
**Prompt:**
Design a page for managing job alerts. Include:
- "Create New Alert" button (prominent)
- List of existing alerts:
  - Alert name/title
  - Search criteria summary (filters applied)
  - Frequency (Daily, Weekly)
  - Last sent date
  - Active/Inactive toggle
  - Edit/Delete actions
- Create/Edit alert form:
  - Alert name
  - All job search filters (same as job search page)
  - Email frequency (Daily, Weekly)
  - Save button

**Design Notes:**
- Alert cards should show a summary of filters in a compact way
- Form should be modal or separate page

---

### 12. Messages / Inbox Page
**Prompt:**
Design a messaging interface. Include:
- Two-panel layout:
  - Left: Conversation list (employers/recruiters)
    - Avatar, Name, Last message preview, Timestamp, Unread badge
  - Right: Message thread
    - Header: Company/Recruiter name, Job title (if related)
    - Messages: Bubbles (sent/received), Timestamps, Read receipts
    - Input area: Text field, Send button, Attach file button
- Empty state: "No messages yet"
- Mobile: Stack layout (list view → detail view)

**Design Notes:**
- Messages should be clearly distinguished (sent vs received)
- Unread messages should be highlighted

---

## RECRUITER (EMPLOYER) PAGES

### 13. Recruiter Dashboard
**Prompt:**
Design an employer dashboard. Include:
- Welcome section with company name
- Stats cards:
  - Active jobs (count)
  - Total applications (count)
  - New applications (count, with badge)
  - Featured jobs (count)
- Quick actions:
  - "Post a New Job" button (primary, prominent)
  - "View Applications" link
  - "Manage Companies" link
- Recent activity:
  - Last 5 applications (with candidate name, job title, status, timestamp)
  - Last 3 posted jobs (with view count, application count)
- Job performance overview:
  - Jobs with most applications
  - Jobs expiring soon
- Subscription status card (if applicable):
  - Current plan
  - Jobs remaining
  - Renewal date
  - "Upgrade" button

**Design Notes:**
- Dashboard should focus on actionable metrics
- New applications should be highlighted

---

### 14. Post Job / Create Job Page
**Prompt:**
Design a comprehensive job posting form. Include:
- Multi-step form or single page with sections:
  - **Step 1: Basic Info**
    - Job title
    - Department
    - Company selection (dropdown if multiple companies)
  - **Step 2: Details**
    - Job description (rich text editor)
    - Requirements (bullet list)
    - Benefits (optional, bullet list)
  - **Step 3: Requirements**
    - Experience level (dropdown)
    - Location type (Remote/Hybrid/On-site)
    - Location (city, country) - if not remote
    - Timezone (if remote)
    - Contract type (Full-time/Contract/Freelance)
    - Vue version (Vue 2/Vue 3/Both)
    - Nuxt version (Nuxt 2/Nuxt 3/Both)
    - TypeScript required (toggle)
    - Skills (multi-select tags)
  - **Step 4: Compensation**
    - Salary min/max
    - Currency dropdown
    - Salary interval (Yearly/Monthly/Hourly)
  - **Step 5: Publishing**
    - Status: Draft/Published
    - Expiry date (optional)
    - Preview button
    - "Save as Draft" / "Publish" buttons
- Progress indicator (if multi-step)
- Job preview panel (shows how it will look)

**Design Notes:**
- Form should have validation feedback
- Preview should update in real-time
- Save draft functionality should be clear

---

### 15. Manage Jobs Page
**Prompt:**
Design a page for managing all posted jobs. Include:
- Filter tabs: All, Draft, Published, Archived, Expired
- Search bar (search by job title)
- Each job card shows:
  - Job title
  - Company name
  - Status badge
  - Published date
  - Expiry date (if set)
  - Application count (with link)
  - View count
  - "Featured" badge (if applicable)
  - Quick actions: Edit, View, Archive, Delete, Feature
- Bulk actions: Select multiple, Archive selected, Delete selected
- "Post New Job" button (prominent)
- Stats summary: Total jobs, Active jobs, Total applications

**Design Notes:**
- Status should be visually clear
- Expired jobs should be highlighted differently
- Application count should be clickable to view applications

---

### 16. Job Applications Page
**Prompt:**
Design a page for viewing and managing job applications. Include:
- Job selector dropdown (if multiple jobs)
- Filter tabs: All, New, Reviewed, Shortlisted, Rejected, Hired
- Search bar (search by candidate name, email)
- Application cards in list view:
  - Candidate avatar
  - Candidate name
  - Applied date
  - Status badge
  - Match score (if AI matching implemented)
  - Skills tags (matching job requirements)
  - Quick view: Resume preview, Cover letter preview
  - Actions: View full profile, Change status, Message, Download resume
- Application detail view (modal or side panel):
  - Full candidate profile
  - Resume (embedded PDF viewer or download)
  - Cover letter (full text)
  - Application timeline
  - Status change dropdown
  - Notes section (for recruiter's private notes)
  - Message candidate button
- Bulk actions: Change status for selected, Export to CSV

**Design Notes:**
- New applications should be highlighted
- Resume should be easily accessible
- Status change should be quick (dropdown on card)

---

### 17. Company Management Page
**Prompt:**
Design a page for managing companies. Include:
- List of companies (if user owns multiple):
  - Company logo
  - Company name
  - Active jobs count
  - "View" / "Edit" / "Delete" actions
- "Create New Company" button
- Company form (create/edit):
  - Company name
  - Slug (auto-generated, editable)
  - Headline
  - Website URL
  - Logo upload (with preview)
  - Size (dropdown: 1-10, 11-50, 51-200, etc.)
  - Industry (dropdown)
  - About (rich text)
  - Hiring regions (multi-select countries)
  - Tech stack (multi-select tags)
  - Public visibility toggle
- Company preview (how it looks publicly)

**Design Notes:**
- Logo upload should show preview
- Form should validate unique company name/slug

---

### 18. Messages / Inbox Page (Recruiter)
**Prompt:**
Similar to candidate messages but recruiter-focused:
- Same two-panel layout
- Left: Candidate list (with job title context)
- Right: Message thread (with candidate profile link)
- Additional: "View candidate profile" button in header
- Filter: "Show only unread"

---

### 19. Analytics / Job Performance Page
**Prompt:**
Design an analytics dashboard. Include:
- Date range selector
- Overview metrics:
  - Total views
  - Total applications
  - Application rate (applications/views)
  - Average time to first application
- Job performance table:
  - Job title
  - Views
  - Applications
  - Application rate
  - Status
  - Actions
- Charts:
  - Applications over time (line chart)
  - Applications by source (if tracking implemented)
  - Top performing jobs (bar chart)
- Export data button (CSV/PDF)

**Design Notes:**
- Charts should be interactive
- Metrics should be clear and actionable

---

### 20. Payment / Subscription Page
**Prompt:**
Design a billing and subscription management page. Include:
- Current subscription card:
  - Plan name
  - Features included
  - Jobs remaining
  - Renewal date
  - "Upgrade" / "Cancel" buttons
- Payment methods:
  - Saved cards (if any)
  - "Add Payment Method" button
- Billing history:
  - Table of past payments
  - Invoice download links
- Pricing plans:
  - Basic plan: ₹1,999 / $29 per job
  - Featured plan: ₹4,999 / $69 per job
  - Subscription plans (monthly unlimited)
  - Feature comparison table
  - "Select Plan" buttons

**Design Notes:**
- Pricing should be clear and prominent
- Payment methods should be secure (show last 4 digits only)

---

## ADDITIONAL PAGES

### 21. Payment Checkout Page
**Prompt:**
Design a checkout page for job posting payments. Include:
- Job summary:
  - Job title
  - Company name
  - Posting type (Basic/Featured)
  - Price breakdown
- Payment method selection:
  - Stripe (card input)
  - Razorpay (redirect option)
- Billing information form
- Order summary sidebar:
  - Subtotal
  - Tax (if applicable)
  - Total
- "Pay Now" button
- Security badges (SSL, secure payment)

---

### 22. Settings Page (Both Roles)
**Prompt:**
Design a settings page. Include:
- Tabs: Profile, Notifications, Security, Billing (if applicable)
- Profile tab: Link to profile edit
- Notifications tab:
  - Email preferences (checkboxes)
  - Job alert frequency
  - Application updates
- Security tab:
  - Change password form
  - Two-factor authentication (if implemented)
  - Active sessions list
- Billing tab: Link to payment/subscription page

---

## DESIGN REQUIREMENTS SUMMARY

**Common Design Elements:**
- Consistent header with logo, navigation, user menu
- Footer with links, social media, copyright
- Loading states for all async operations
- Empty states with helpful CTAs
- Error states with retry options
- Success/error toast notifications
- Responsive design (mobile, tablet, desktop)
- Accessibility: WCAG 2.1 AA compliance
- Dark mode support (optional but recommended)

**Color Palette:**
- Primary: Vue.js green (#42b883)
- Secondary: Complementary colors (blue, purple)
- Success: Green
- Warning: Yellow/Orange
- Error: Red
- Neutral: Gray scale

**Component Library:**
- Buttons (primary, secondary, outline, ghost)
- Cards (with hover states)
- Badges/Tags
- Forms (inputs, selects, checkboxes, toggles)
- Modals/Dialogs
- Dropdowns
- Tables
- Pagination
- Tabs
- Tooltips

---

## NOTES FOR DESIGNERS

1. **Vue.js Branding**: Use Vue.js green (#42b883) as primary color to maintain brand consistency
2. **Developer-Focused**: The platform should feel modern and tech-forward
3. **Trust Indicators**: Include security badges, SSL indicators, and trust signals
4. **Performance**: Design should prioritize fast loading and smooth interactions
5. **Mobile-First**: Ensure all pages work seamlessly on mobile devices
6. **Accessibility**: Use proper contrast ratios, ARIA labels, keyboard navigation
7. **Consistency**: Maintain consistent spacing, typography, and component styles throughout

---

**End of Design Prompts Document**

