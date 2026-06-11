# PDF Korektura - Proofreading Workflow System

## Tech Stack
- **Backend:** Laravel 11, PHP 8.3, PostgreSQL 16
- **Frontend:** Livewire 3 + Tailwind CSS + Alpine.js
- **Auth:** LDAP/AD integration via `directorytree/ldaprecord-laravel` and offline users like admin
- **PDF handling:** `spatie/pdf-to-text` for text extraction (spell-check), browser PDF preview via `<iframe>` or `pdf.js`
- **Storage:** Local disk with structured folders per title/issue
- **Queue:** Laravel queues for email notifications and PDF processing
- **AI/Spell:** `voku/stop-words` + `pspell` or `hunspell` integration for Czech

---

## Task 1: Project Initialization & Core Setup
- Initialize Laravel project in workspace
- Configure PostgreSQL connection in `.env`
- Install required packages:
  - `livewire/livewire`
  - `directorytree/ldaprecord-laravel`
  - `spatie/pdf-to-text`
  - `spatie/laravel-permission` (roles/permissions)
- Set up Laravel queues (database driver)
- Configure storage disk for PDFs: `storage/app/pdfs`

## Task 2: Database Schema & Models
Create migrations and Eloquent models:
- **users** (extend with LDAP fields: `username`, `guid`, `domain`)
- **roles**: editor, proofreader, admin
- **titles** (magazines/newspapers: MFDnes, Metro, etc.)
- **pdf_documents**:
  - `id`, `title_id`, `uploaded_by_user_id`, `name`, `page_number`, `issue_title`, `deadline_date`
  - `status` enum: `uploaded`, `in_progress`, `returned`, `completed`
  - `assigned_to_user_id`, `current_version_number`
  - `archived_at`, `created_at`, `updated_at`
- **pdf_versions**:
  - `id`, `pdf_document_id`, `version_number`, `file_path`, `uploaded_by_user_id`, `change_summary`
- **activity_logs**:
  - `id`, `pdf_document_id`, `user_id`, `action` (upload, assign, release, complete), `details`, `created_at`
- Run migrations

## Task 3: Authentication & Authorization
- Configure LDAP/AD provider in `config/ldap.php`
- Implement custom login using AD credentials (no local passwords)
- Create Role middleware: `editor`, `proofreader`, `admin`
- Seed default admin user (if needed for initial setup)

## Task 4: PDF Upload Module (Editor)
- Livewire component: `PdfUpload`
- Form fields: Name, Title (dropdown), Page number, Issue title, Deadline date
- File upload with validation (PDF, max size)
- On upload:
  - Store file in `pdfs/{title}/{year-month}/original/`
  - Create `PdfDocument` record with status `uploaded`
  - Create initial `PdfVersion` record (version 1)
  - Log activity
- Extract text from PDF for spell-check indexing (async job)

## Task 5: PDF Dashboard & Status Workflow
- **Editor Dashboard** (Livewire):
  - Table/list of own uploaded PDFs
  - Columns: Name, Title, Page, Deadline, Status, Assigned Proofreader
  - Filters: by Title, by Status, by Date
  - Actions: View versions, Archive, Delete (admin only)
- **Status transitions**:
  - `uploaded` -> `in_progress` (when assigned)
  - `in_progress` -> `returned` (proofreader returns)
  - `returned` -> `in_progress` (re-assigned)
  - `in_progress` -> `completed` (proofreader uploads corrected version)

## Task 6: PDF Assignment & Locking (Proofreader)
- **Proofreader Pool** (Livewire component):
  - List all unassigned PDFs (status = uploaded)
  - Hide already assigned PDFs from other proofreaders
  - Action: "Assign to me" -> sets `assigned_to_user_id`, status -> `in_progress`
- **My Assigned PDFs**:
  - List of PDFs assigned to current proofreader
  - Actions: Download PDF, Upload corrected version, Release (unassign)
- **Release rules**:
  - Proofreader can self-release their assigned PDF
  - Admin can release any assigned PDF
- On assignment: log activity, send email notification to proofreader (optional)

## Task 7: PDF Version Management & Correction Upload
- Proofreader downloads PDF, edits in Adobe Reader, uploads corrected version
- Livewire upload component for corrected PDF
- On upload:
  - Increment `current_version_number`
  - Store new file in `pdfs/{title}/{year-month}/v{N}/`
  - Create new `PdfVersion` record
  - Status -> `completed` (or `returned` if further edits needed)
  - Log activity
  - Send email notification to original editor
- Display version history timeline on PDF detail page

## Task 8: Activity Logging & Audit Trail
- Create `ActivityLogService` to centralize logging
- Log all actions: upload, assign, release, correct, archive, view
- Log details: user ID, timestamp, IP address, action type
- Log page accessible to Admin: searchable/filterable audit trail
- Implement retention job (daily) to delete logs older than 2 months (configurable)

## Task 9: Archive & Cleanup
- "Archive" action for Editors: moves PDF to archive state
- Archived PDFs are visible only to admins and the original editor
- Implement scheduled command to soft-delete/archive PDFs older than 2 months
- Keep all versions and logs for the retention period

## Task 10: Spell-Check Integration
- Extract text from uploaded PDFs using `spatie/pdf-to-text`
- Index extracted text for spell-check
- Simple spell-check service using `pspell` (Czech dictionary) or `hunspell`
- Display spell-check results on PDF detail page (highlight potential issues)
- This is a hidden/helper feature, not blocking the workflow

## Task 11: Email Notifications
- Configure mail (SMTP from internal server)
- Implement `PdfNotification` Mailable
- Send emails on events:
  - PDF assigned to proofreader
  - PDF corrected and uploaded (to editor)
  - PDF returned for revision
- Use Laravel queue for async email sending

## Task 12: Admin Panel
- User management (sync with AD, assign roles)
- PDF override: release any assigned PDF, reassign to different proofreader
- Audit log viewer with filters
- Title management (add/remove newspapers/magazines)
- System configuration (retention period, email settings)

## Task 13: UI/UX Polish
- Modern responsive UI using Tailwind CSS + Laravel Breeze or custom layout
- Dark mode support (optional)
- PDF preview in browser (iframe or PDF.js embed)
- Drag-and-drop file upload
- Toast notifications for actions
- Dashboard stats: PDFs by status, proofreader performance metrics

## Task 14: Security & Deployment Considerations
- Ensure all routes require authentication
- Restrict file downloads to authorized users only (via controller, not direct storage URL)
- Configure for internal network + VPN access
- Environment variables for AD/LDAP, DB, mail
- `php artisan storage:link` for public assets
