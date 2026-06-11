# Notification System

<cite>
**Referenced Files in This Document**
- [AdminController.php](file://pdf-korektura/app/Http/Controllers/AdminController.php)
- [ActivityLogService.php](file://pdf-korektura/app/Services/ActivityLogService.php)
- [ActivityLog.php](file://pdf-korektura/app/Models/ActivityLog.php)
- [2024_06_10_140000_create_activity_logs_table.php](file://pdf-korektura/database/migrations/2024_06_10_140000_create_activity_logs_table.php)
- [my-assignments.blade.php](file://pdf-korektura/resources/views/livewire/my-assignments.blade.php)
- [audit-log.blade.php](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php)
- [PdfDocument.php](file://pdf-korektura/app/Models/PdfDocument.php)
- [User.php](file://pdf-korektura/app/Models/User.php)
</cite>

## Table of Contents
1. [Introduction](#introduction)
2. [Project Structure](#project-structure)
3. [Core Components](#core-components)
4. [Architecture Overview](#architecture-overview)
5. [Detailed Component Analysis](#detailed-component-analysis)
6. [Dependency Analysis](#dependency-analysis)
7. [Performance Considerations](#performance-considerations)
8. [Troubleshooting Guide](#troubleshooting-guide)
9. [Conclusion](#conclusion)

## Introduction
This document describes the assignment notification system within the application. The system currently focuses on activity logging and visibility of assignment events for administrators and users. Notifications via email or other channels are not implemented in the current codebase. Instead, the system records assignment actions and displays them in audit logs and user dashboards.

## Project Structure
The notification-related functionality centers around:
- Controllers that trigger assignment actions
- A service that logs activities
- Models representing documents, users, and activity logs
- Blade views that surface audit logs and assignment lists

```mermaid
graph TB
subgraph "Controllers"
AC["AdminController.php"]
end
subgraph "Services"
ALS["ActivityLogService.php"]
end
subgraph "Models"
PD["PdfDocument.php"]
U["User.php"]
AL["ActivityLog.php"]
end
subgraph "Views"
MA["my-assignments.blade.php"]
AUD["audit-log.blade.php"]
end
AC --> ALS
ALS --> AL
AL --> PD
AL --> U
MA --> PD
AUD --> AL
```

**Diagram sources**
- [AdminController.php:42-61](file://pdf-korektura/app/Http/Controllers/AdminController.php#L42-L61)
- [ActivityLogService.php:20-29](file://pdf-korektura/app/Services/ActivityLogService.php#L20-L29)
- [ActivityLog.php:13-59](file://pdf-korektura/app/Models/ActivityLog.php#L13-L59)
- [PdfDocument.php:94-129](file://pdf-korektura/app/Models/PdfDocument.php#L94-L129)
- [User.php](file://pdf-korektura/app/Models/User.php)
- [my-assignments.blade.php:1-134](file://pdf-korektura/resources/views/livewire/my-assignments.blade.php#L1-L134)
- [audit-log.blade.php:20-67](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php#L20-L67)

**Section sources**
- [AdminController.php:42-61](file://pdf-korektura/app/Http/Controllers/AdminController.php#L42-L61)
- [ActivityLogService.php:20-29](file://pdf-korektura/app/Services/ActivityLogService.php#L20-L29)
- [ActivityLog.php:13-59](file://pdf-korektura/app/Models/ActivityLog.php#L13-L59)
- [my-assignments.blade.php:1-134](file://pdf-korektura/resources/views/livewire/my-assignments.blade.php#L1-L134)
- [audit-log.blade.php:20-67](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php#L20-L67)

## Core Components
- Assignment controller action: Assigns a PDF document to a user and logs the event.
- Activity logging service: Creates persistent records of user actions.
- Activity log model: Defines attributes, relationships, and localized labels for actions.
- Views: Display recent activity and user assignments.

Key responsibilities:
- Triggering assignment events
- Recording audit trails
- Presenting actionable information to users and administrators

**Section sources**
- [AdminController.php:42-61](file://pdf-korektura/app/Http/Controllers/AdminController.php#L42-L61)
- [ActivityLogService.php:20-29](file://pdf-korektura/app/Services/ActivityLogService.php#L20-L29)
- [ActivityLog.php:13-59](file://pdf-korektura/app/Models/ActivityLog.php#L13-L59)

## Architecture Overview
Assignment notifications are implemented as synchronous activity logging. When an administrator assigns a document, the system:
1. Updates the document record
2. Logs the assignment action with contextual details
3. Makes the event visible in audit logs and the user's assignment list

```mermaid
sequenceDiagram
participant Admin as "Administrator"
participant Controller as "AdminController"
participant Service as "ActivityLogService"
participant Log as "ActivityLog"
participant View as "Audit Log View"
Admin->>Controller : "Assign document to user"
Controller->>Controller : "Update document and status"
Controller->>Service : "log(ACTION_ASSIGN, details)"
Service->>Log : "Create activity log record"
Log-->>Service : "Persisted"
Service-->>Controller : "Done"
Controller-->>Admin : "Success response"
View->>Log : "Fetch recent logs"
Log-->>View : "Activity rows"
View-->>Admin : "Rendered audit log"
```

**Diagram sources**
- [AdminController.php:42-61](file://pdf-korektura/app/Http/Controllers/AdminController.php#L42-L61)
- [ActivityLogService.php:20-29](file://pdf-korektura/app/Services/ActivityLogService.php#L20-L29)
- [ActivityLog.php:13-59](file://pdf-korektura/app/Models/ActivityLog.php#L13-L59)
- [audit-log.blade.php:20-67](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php#L20-L67)

## Detailed Component Analysis

### Assignment Controller Action
- Validates input and updates the document assignment and status.
- Logs the assignment action with a reason and actor details.
- Returns a user-facing success message.

```mermaid
flowchart TD
Start(["Admin assigns document"]) --> Validate["Validate request<br/>and resolve user"]
Validate --> UpdateDoc["Update document:<br/>assigned_to_user_id,<br/>status"]
UpdateDoc --> LogEvent["Log activity:<br/>ACTION_ASSIGN,<br/>details, IP"]
LogEvent --> Success["Return success response"]
```

**Diagram sources**
- [AdminController.php:42-61](file://pdf-korektura/app/Http/Controllers/AdminController.php#L42-L61)
- [ActivityLogService.php:20-29](file://pdf-korektura/app/Services/ActivityLogService.php#L20-L29)

**Section sources**
- [AdminController.php:42-61](file://pdf-korektura/app/Http/Controllers/AdminController.php#L42-L61)

### Activity Logging Service
- Provides a centralized method to persist activity logs.
- Captures the acting user, action type, related document, and client IP.
- Supports multiple action types including assignment.

```mermaid
classDiagram
class ActivityLogService {
+ACTION_UPLOAD
+ACTION_ASSIGN
+ACTION_RELEASE
+ACTION_CORRECT
+ACTION_ARCHIVE
+ACTION_VIEW
+ACTION_DOWNLOAD
+log(pdfDocument, action, details) void
}
class ActivityLog {
+pdfDocument() BelongsTo
+user() BelongsTo
+actionLabel() string
}
ActivityLogService --> ActivityLog : "creates"
```

**Diagram sources**
- [ActivityLogService.php:10-30](file://pdf-korektura/app/Services/ActivityLogService.php#L10-L30)
- [ActivityLog.php:9-59](file://pdf-korektura/app/Models/ActivityLog.php#L9-L59)

**Section sources**
- [ActivityLogService.php:10-30](file://pdf-korektura/app/Services/ActivityLogService.php#L10-L30)
- [ActivityLog.php:13-59](file://pdf-korektura/app/Models/ActivityLog.php#L13-L59)

### Audit Log Presentation
- Administrators can browse recent activity events.
- Displays timestamps, actors, actions, associated documents, details, and IPs.
- Supports pagination and filtering.

```mermaid
sequenceDiagram
participant Admin as "Administrator"
participant View as "Audit Log View"
participant Model as "ActivityLog"
Admin->>View : "Open audit log"
View->>Model : "Query recent logs"
Model-->>View : "Paginated rows"
View-->>Admin : "Rendered table"
```

**Diagram sources**
- [audit-log.blade.php:20-67](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php#L20-L67)
- [ActivityLog.php:36-44](file://pdf-korektura/app/Models/ActivityLog.php#L36-L44)

**Section sources**
- [audit-log.blade.php:20-67](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php#L20-L67)
- [ActivityLog.php:36-44](file://pdf-korektura/app/Models/ActivityLog.php#L36-L44)

### User Assignment Visibility
- Users see their assigned documents in a dedicated view.
- Includes document name, title, deadline, and current version.
- Provides actions for uploading corrections when applicable.

```mermaid
flowchart TD
Enter(["User opens My Assignments"]) --> Load["Load user's PDFs"]
Load --> Render["Render table with:<br/>Name, Title, Deadline, Version"]
Render --> Actions["Show actions per row"]
```

**Diagram sources**
- [my-assignments.blade.php:1-134](file://pdf-korektura/resources/views/livewire/my-assignments.blade.php#L1-L134)
- [PdfDocument.php:94-129](file://pdf-korektura/app/Models/PdfDocument.php#L94-L129)

**Section sources**
- [my-assignments.blade.php:1-134](file://pdf-korektura/resources/views/livewire/my-assignments.blade.php#L1-L134)
- [PdfDocument.php:94-129](file://pdf-korektura/app/Models/PdfDocument.php#L94-L129)

## Dependency Analysis
- Controllers depend on the logging service to record actions.
- The logging service depends on the activity log model.
- Views depend on models to render data.
- There is no external notification channel configured; all notifications are local and logged.

```mermaid
graph LR
AC["AdminController"] --> ALS["ActivityLogService"]
ALS --> AL["ActivityLog"]
AL --> PD["PdfDocument"]
AL --> U["User"]
MA["My Assignments View"] --> PD
AUD["Audit Log View"] --> AL
```

**Diagram sources**
- [AdminController.php:42-61](file://pdf-korektura/app/Http/Controllers/AdminController.php#L42-L61)
- [ActivityLogService.php:20-29](file://pdf-korektura/app/Services/ActivityLogService.php#L20-L29)
- [ActivityLog.php:36-44](file://pdf-korektura/app/Models/ActivityLog.php#L36-L44)
- [my-assignments.blade.php:1-134](file://pdf-korektura/resources/views/livewire/my-assignments.blade.php#L1-L134)
- [audit-log.blade.php:20-67](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php#L20-L67)

**Section sources**
- [AdminController.php:42-61](file://pdf-korektura/app/Http/Controllers/AdminController.php#L42-L61)
- [ActivityLogService.php:20-29](file://pdf-korektura/app/Services/ActivityLogService.php#L20-L29)
- [ActivityLog.php:36-44](file://pdf-korektura/app/Models/ActivityLog.php#L36-L44)
- [my-assignments.blade.php:1-134](file://pdf-korektura/resources/views/livewire/my-assignments.blade.php#L1-L134)
- [audit-log.blade.php:20-67](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php#L20-L67)

## Performance Considerations
- Logging is synchronous and lightweight; overhead is minimal.
- Audit log queries should be paginated and filtered to avoid large result sets.
- Consider indexing frequently queried columns (e.g., user_id, pdf_document_id, created_at) to improve performance.

## Troubleshooting Guide
- If assignment actions do not appear in audit logs:
  - Verify the logging service is invoked after document updates.
  - Confirm the activity logs table exists and is properly migrated.
- If user assignment lists are empty:
  - Ensure the user is assigned to documents and the status reflects active assignments.
- If logs show unexpected IPs:
  - Check proxy or load balancer configurations affecting client IP capture.

**Section sources**
- [ActivityLogService.php:20-29](file://pdf-korektura/app/Services/ActivityLogService.php#L20-L29)
- [2024_06_10_140000_create_activity_logs_table.php:11-18](file://pdf-korektura/database/migrations/2024_06_10_140000_create_activity_logs_table.php#L11-L18)
- [PdfDocument.php:94-129](file://pdf-korektura/app/Models/PdfDocument.php#L94-L129)

## Conclusion
The current assignment notification system is implemented through synchronous activity logging and local visibility in audit logs and user dashboards. Email or external notification channels are not present in the codebase. To extend the system, integrate a queue-backed notification mechanism and define templates for email dispatch, while preserving the existing logging infrastructure for auditability.