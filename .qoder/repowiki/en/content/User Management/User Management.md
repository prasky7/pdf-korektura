# User Management

<cite>
**Referenced Files in This Document**
- [AuthController.php](file://pdf-korektura/app/Http/Controllers/AuthController.php)
- [AdminController.php](file://pdf-korektura/app/Http/Controllers/AdminController.php)
- [User.php](file://pdf-korektura/app/Models/User.php)
- [ActivityLog.php](file://pdf-korektura/app/Models/ActivityLog.php)
- [ActivityLogService.php](file://pdf-korektura/app/Services/ActivityLogService.php)
- [permission.php](file://pdf-korektura/config/permission.php)
- [auth.php](file://pdf-korektura/config/auth.php)
- [2024_06_10_100000_create_permission_tables.php](file://pdf-korektura/database/migrations/2024_06_10_100000_create_permission_tables.php)
- [0001_01_01_000000_create_users_table.php](file://pdf-korektura/database/migrations/0001_01_01_000000_create_users_table.php)
- [login.blade.php](file://pdf-korektura/resources/views/auth/login.blade.php)
- [user-management.blade.php](file://pdf-korektura/resources/views/livewire/admin/user-management.blade.php)
- [audit-log.blade.php](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php)
- [web.php](file://pdf-korektura/routes/web.php)
- [AppServiceProvider.php](file://pdf-korektura/app/Providers/AppServiceProvider.php)
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
This document provides comprehensive user management documentation for the PDF correction system. It covers authentication and session management, role-based access control using Spatie Laravel Permission, user registration and profile management, password reset functionality, user assignment workflows, activity tracking and audit trails, and LDAP integration for enterprise environments. The goal is to enable administrators and developers to understand how users are managed, secured, and tracked within the system.

## Project Structure
The user management functionality spans controllers, models, services, configuration files, migrations, and Blade views. Authentication is handled via a dedicated controller, while authorization leverages Spatie Laravel Permission. Activity logging is implemented through a model and service. Administrative user management is exposed via Livewire components and Blade templates.

```mermaid
graph TB
subgraph "HTTP Layer"
Routes["Routes"]
AuthController["AuthController"]
AdminController["AdminController"]
end
subgraph "Domain Layer"
UserModel["User Model"]
ActivityLogModel["ActivityLog Model"]
ActivityLogService["ActivityLogService"]
end
subgraph "Configuration"
AuthConfig["auth.php"]
PermissionConfig["permission.php"]
end
subgraph "Persistence"
UsersTable["Users Migration"]
PermissionTables["Permission Tables Migration"]
ActivityLogsTable["Activity Logs Migration"]
end
subgraph "Presentation"
LoginView["Login View"]
AdminUserView["Admin User Management View"]
AuditView["Audit Log View"]
end
Routes --> AuthController
Routes --> AdminController
AuthController --> UserModel
AdminController --> UserModel
UserModel --> ActivityLogModel
ActivityLogModel --> ActivityLogService
AuthConfig --> AuthController
PermissionConfig --> UserModel
UsersTable --> UserModel
PermissionTables --> UserModel
ActivityLogsTable --> ActivityLogModel
LoginView --> AuthController
AdminUserView --> AdminController
AuditView --> ActivityLogService
```

**Diagram sources**
- [AuthController.php](file://pdf-korektura/app/Http/Controllers/AuthController.php)
- [AdminController.php](file://pdf-korektura/app/Http/Controllers/AdminController.php)
- [User.php](file://pdf-korektura/app/Models/User.php)
- [ActivityLog.php](file://pdf-korektura/app/Models/ActivityLog.php)
- [ActivityLogService.php](file://pdf-korektura/app/Services/ActivityLogService.php)
- [auth.php](file://pdf-korektura/config/auth.php)
- [permission.php](file://pdf-korektura/config/permission.php)
- [0001_01_01_000000_create_users_table.php](file://pdf-korektura/database/migrations/0001_01_01_000000_create_users_table.php)
- [2024_06_10_100000_create_permission_tables.php](file://pdf-korektura/database/migrations/2024_06_10_100000_create_permission_tables.php)
- [login.blade.php](file://pdf-korektura/resources/views/auth/login.blade.php)
- [user-management.blade.php](file://pdf-korektura/resources/views/livewire/admin/user-management.blade.php)
- [audit-log.blade.php](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php)

**Section sources**
- [AuthController.php](file://pdf-korektura/app/Http/Controllers/AuthController.php)
- [User.php](file://pdf-korektura/app/Models/User.php)
- [auth.php](file://pdf-korektura/config/auth.php)
- [permission.php](file://pdf-korektura/config/permission.php)
- [0001_01_01_000000_create_users_table.php](file://pdf-korektura/database/migrations/0001_01_01_000000_create_users_table.php)
- [2024_06_10_100000_create_permission_tables.php](file://pdf-korektura/database/migrations/2024_06_10_100000_create_permission_tables.php)
- [login.blade.php](file://pdf-korektura/resources/views/auth/login.blade.php)
- [user-management.blade.php](file://pdf-korektura/resources/views/livewire/admin/user-management.blade.php)
- [audit-log.blade.php](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php)

## Core Components
- Authentication Controller: Handles login, logout, and session lifecycle.
- User Model: Represents users and integrates with Spatie Laravel Permission for roles and permissions.
- Activity Log Model and Service: Capture and manage user actions for auditing.
- Configuration: Defines guards, providers, and Spatie Permission settings.
- Migrations: Create users table and Spatie permission-related tables.
- Views: Provide login UI and administrative management UI for users and audit logs.

Key responsibilities:
- Authentication: Validate credentials, establish sessions, invalidate sessions on logout.
- Authorization: Enforce role-based access control using Spatie roles and permissions.
- User Management: Registration, profile updates, and administrative user operations.
- Security: Password reset, secure session handling, and audit trail generation.
- Enterprise Integration: LDAP support for centralized authentication.

**Section sources**
- [AuthController.php](file://pdf-korektura/app/Http/Controllers/AuthController.php)
- [User.php](file://pdf-korektura/app/Models/User.php)
- [ActivityLog.php](file://pdf-korektura/app/Models/ActivityLog.php)
- [ActivityLogService.php](file://pdf-korektura/app/Services/ActivityLogService.php)
- [auth.php](file://pdf-korektura/config/auth.php)
- [permission.php](file://pdf-korektura/config/permission.php)
- [2024_06_10_100000_create_permission_tables.php](file://pdf-korektura/database/migrations/2024_06_10_100000_create_permission_tables.php)
- [0001_01_01_000000_create_users_table.php](file://pdf-korektura/database/migrations/0001_01_01_000000_create_users_table.php)
- [login.blade.php](file://pdf-korektura/resources/views/auth/login.blade.php)
- [user-management.blade.php](file://pdf-korektura/resources/views/livewire/admin/user-management.blade.php)
- [audit-log.blade.php](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php)

## Architecture Overview
The system follows a layered architecture:
- HTTP Layer: Routes dispatch to controllers.
- Domain Layer: Controllers interact with the User model and ActivityLogService.
- Persistence Layer: Eloquent models map to database tables created by migrations.
- Presentation Layer: Blade views render login and administrative UIs.
- Configuration Layer: auth.php and permission.php define guards, providers, and Spatie settings.

```mermaid
graph TB
Client["Browser"]
Routes["Web Routes"]
AuthCtrl["AuthController"]
AdminCtrl["AdminController"]
UserModel["User Model"]
ActivitySvc["ActivityLogService"]
ActivityModel["ActivityLog Model"]
ConfigAuth["auth.php"]
ConfigPerm["permission.php"]
Client --> Routes
Routes --> AuthCtrl
Routes --> AdminCtrl
AuthCtrl --> UserModel
AdminCtrl --> UserModel
UserModel --> ActivityModel
ActivityModel --> ActivitySvc
ConfigAuth --> AuthCtrl
ConfigPerm --> UserModel
```

**Diagram sources**
- [web.php](file://pdf-korektura/routes/web.php)
- [AuthController.php](file://pdf-korektura/app/Http/Controllers/AuthController.php)
- [AdminController.php](file://pdf-korektura/app/Http/Controllers/AdminController.php)
- [User.php](file://pdf-korektura/app/Models/User.php)
- [ActivityLogService.php](file://pdf-korektura/app/Services/ActivityLogService.php)
- [ActivityLog.php](file://pdf-korektura/app/Models/ActivityLog.php)
- [auth.php](file://pdf-korektura/config/auth.php)
- [permission.php](file://pdf-korektura/config/permission.php)

## Detailed Component Analysis

### Authentication System
The authentication system manages user login, logout, and session lifecycle. It validates credentials against the configured provider and establishes authenticated sessions. Logout invalidates the current session.

```mermaid
sequenceDiagram
participant Browser as "Browser"
participant Routes as "Web Routes"
participant AuthCtrl as "AuthController"
participant Guard as "Auth Guard"
participant Session as "Session Store"
Browser->>Routes : Submit login form
Routes->>AuthCtrl : Call login action
AuthCtrl->>Guard : Attempt to authenticate
Guard-->>AuthCtrl : Authentication result
AuthCtrl->>Session : Regenerate session ID
AuthCtrl-->>Browser : Redirect to dashboard
Browser->>Routes : Request logout
Routes->>AuthCtrl : Call logout action
AuthCtrl->>Session : Invalidate session
AuthCtrl-->>Browser : Redirect to login
```

**Diagram sources**
- [AuthController.php](file://pdf-korektura/app/Http/Controllers/AuthController.php)
- [auth.php](file://pdf-korektura/config/auth.php)
- [login.blade.php](file://pdf-korektura/resources/views/auth/login.blade.php)

**Section sources**
- [AuthController.php](file://pdf-korektura/app/Http/Controllers/AuthController.php)
- [auth.php](file://pdf-korektura/config/auth.php)
- [login.blade.php](file://pdf-korektura/resources/views/auth/login.blade.php)

### Role-Based Access Control (RBAC) with Spatie Laravel Permission
The system uses Spatie Laravel Permission to implement RBAC. Roles and permissions are stored in dedicated tables and associated with users via the User model. Guards and providers are configured in auth.php, while Spatie-specific settings are defined in permission.php.

```mermaid
classDiagram
class User {
+roles()
+permissions()
+assignRole(role)
+removeRole(role)
+givePermissionTo(permission)
+revokePermission(permission)
+hasRole(role) bool
+can(permission) bool
}
class ActivityLog {
+user()
+loggable()
}
class ActivityLogService {
+log(action, userId, payload)
}
User --> ActivityLog : "creates"
ActivityLog --> ActivityLogService : "persisted by"
```

**Diagram sources**
- [User.php](file://pdf-korektura/app/Models/User.php)
- [ActivityLog.php](file://pdf-korektura/app/Models/ActivityLog.php)
- [ActivityLogService.php](file://pdf-korektura/app/Services/ActivityLogService.php)
- [permission.php](file://pdf-korektura/config/permission.php)
- [2024_06_10_100000_create_permission_tables.php](file://pdf-korektura/database/migrations/2024_06_10_100000_create_permission_tables.php)

**Section sources**
- [User.php](file://pdf-korektura/app/Models/User.php)
- [permission.php](file://pdf-korektura/config/permission.php)
- [2024_06_10_100000_create_permission_tables.php](file://pdf-korektura/database/migrations/2024_06_10_100000_create_permission_tables.php)

### User Registration and Profile Management
User registration creates new user records in the users table. Profile management allows users to update personal information. Administrative users can manage profiles via Livewire components.

```mermaid
flowchart TD
Start(["Registration Entry"]) --> Validate["Validate Input"]
Validate --> Valid{"Valid?"}
Valid --> |No| ShowErrors["Show Validation Errors"]
Valid --> |Yes| Create["Create User Record"]
Create --> Notify["Notify User (optional)"]
Notify --> End(["Registration Complete"])
ProfileStart(["Profile Update Entry"]) --> LoadUser["Load Current User"]
LoadUser --> UpdateFields["Update Profile Fields"]
UpdateFields --> Save["Save Changes"]
Save --> ProfileEnd(["Profile Updated"])
```

**Diagram sources**
- [0001_01_01_000000_create_users_table.php](file://pdf-korektura/database/migrations/0001_01_01_000000_create_users_table.php)
- [user-management.blade.php](file://pdf-korektura/resources/views/livewire/admin/user-management.blade.php)

**Section sources**
- [0001_01_01_000000_create_users_table.php](file://pdf-korektura/database/migrations/0001_01_01_000000_create_users_table.php)
- [user-management.blade.php](file://pdf-korektura/resources/views/livewire/admin/user-management.blade.php)

### Password Reset Functionality
Password reset enables users to recover access to their accounts. The system integrates with the configured auth provider to issue reset tokens and update passwords securely.

```mermaid
sequenceDiagram
participant User as "User"
participant Routes as "Web Routes"
participant AuthCtrl as "AuthController"
participant Mail as "Mail Service"
User->>Routes : Request password reset
Routes->>AuthCtrl : Call sendResetLink
AuthCtrl->>Mail : Send reset notification
Mail-->>User : Reset email sent
User->>Routes : Click reset link and submit new password
Routes->>AuthCtrl : Call reset
AuthCtrl-->>User : Redirect to login with success
```

**Diagram sources**
- [AuthController.php](file://pdf-korektura/app/Http/Controllers/AuthController.php)
- [auth.php](file://pdf-korektura/config/auth.php)

**Section sources**
- [AuthController.php](file://pdf-korektura/app/Http/Controllers/AuthController.php)
- [auth.php](file://pdf-korektura/config/auth.php)

### User Assignment Workflows
Administrators can assign documents to editors and proofreaders. This involves selecting users with appropriate roles and linking them to specific PDF documents. The assignment process respects role permissions to ensure only authorized users can access assigned documents.

```mermaid
flowchart TD
AdminStart(["Admin Starts Assignment"]) --> SelectDoc["Select Target Document"]
SelectDoc --> ChooseRole["Choose Role (Editor/Proofreader)"]
ChooseRole --> FindUsers["Find Users with Selected Role"]
FindUsers --> Assign["Assign Document to Selected Users"]
Assign --> Confirm["Confirm Assignment"]
Confirm --> AdminEnd(["Assignment Complete"])
```

**Section sources**
- [AdminController.php](file://pdf-korektura/app/Http/Controllers/AdminController.php)
- [User.php](file://pdf-korektura/app/Models/User.php)

### User Activity Tracking and Audit Trails
The system captures user activities through the ActivityLog model and service. Every significant action performed by a user is logged with metadata such as the actor, action type, target, and timestamp. Administrators can review audit logs to monitor system usage and detect anomalies.

```mermaid
sequenceDiagram
participant User as "Authenticated User"
participant Service as "ActivityLogService"
participant Model as "ActivityLog Model"
participant DB as "Activity Logs Table"
User->>Service : log(action, payload)
Service->>Model : Create activity record
Model->>DB : Persist activity log
DB-->>Service : Confirmation
Service-->>User : Acknowledge
```

**Diagram sources**
- [ActivityLog.php](file://pdf-korektura/app/Models/ActivityLog.php)
- [ActivityLogService.php](file://pdf-korektura/app/Services/ActivityLogService.php)
- [2024_06_10_140000_create_activity_logs_table.php](file://pdf-korektura/database/migrations/2024_06_10_140000_create_activity_logs_table.php)
- [audit-log.blade.php](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php)

**Section sources**
- [ActivityLog.php](file://pdf-korektura/app/Models/ActivityLog.php)
- [ActivityLogService.php](file://pdf-korektura/app/Services/ActivityLogService.php)
- [audit-log.blade.php](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php)

### LDAP Integration for Enterprise Authentication
LDAP integration supports enterprise authentication scenarios by allowing users to authenticate against an external directory server. The system leverages the configured LDAP provider to validate credentials and synchronize user attributes.

```mermaid
sequenceDiagram
participant User as "Enterprise User"
participant Routes as "Web Routes"
participant AuthCtrl as "AuthController"
participant LDAP as "LDAP Provider"
User->>Routes : Submit credentials
Routes->>AuthCtrl : Call login
AuthCtrl->>LDAP : Authenticate against directory
LDAP-->>AuthCtrl : Authentication result
AuthCtrl-->>User : Redirect to application or show error
```

**Diagram sources**
- [auth.php](file://pdf-korektura/config/auth.php)
- [AppServiceProvider.php](file://pdf-korektura/app/Providers/AppServiceProvider.php)

**Section sources**
- [auth.php](file://pdf-korektura/config/auth.php)
- [AppServiceProvider.php](file://pdf-korektura/app/Providers/AppServiceProvider.php)

## Dependency Analysis
The user management subsystem exhibits clear separation of concerns:
- Controllers depend on the User model and ActivityLogService.
- The User model depends on Spatie Permission traits and Eloquent ORM.
- Activity logging depends on the ActivityLog model and service.
- Configuration files define the integration points for authentication and authorization.
- Views provide the presentation layer for login and administrative tasks.

```mermaid
graph TB
AuthController["AuthController"] --> UserModel["User Model"]
AdminController["AdminController"] --> UserModel
UserModel --> ActivityLogModel["ActivityLog Model"]
ActivityLogModel --> ActivityLogService["ActivityLogService"]
AuthController --> AuthConfig["auth.php"]
UserModel --> PermissionConfig["permission.php"]
UserModel --> UsersTable["Users Migration"]
UserModel --> PermissionTables["Permission Tables Migration"]
ActivityLogModel --> ActivityLogsTable["Activity Logs Migration"]
LoginView["Login View"] --> AuthController
AdminUserView["Admin User Management View"] --> AdminController
AuditView["Audit Log View"] --> ActivityLogService
```

**Diagram sources**
- [AuthController.php](file://pdf-korektura/app/Http/Controllers/AuthController.php)
- [AdminController.php](file://pdf-korektura/app/Http/Controllers/AdminController.php)
- [User.php](file://pdf-korektura/app/Models/User.php)
- [ActivityLog.php](file://pdf-korektura/app/Models/ActivityLog.php)
- [ActivityLogService.php](file://pdf-korektura/app/Services/ActivityLogService.php)
- [auth.php](file://pdf-korektura/config/auth.php)
- [permission.php](file://pdf-korektura/config/permission.php)
- [0001_01_01_000000_create_users_table.php](file://pdf-korektura/database/migrations/0001_01_01_000000_create_users_table.php)
- [2024_06_10_100000_create_permission_tables.php](file://pdf-korektura/database/migrations/2024_06_10_100000_create_permission_tables.php)
- [login.blade.php](file://pdf-korektura/resources/views/auth/login.blade.php)
- [user-management.blade.php](file://pdf-korektura/resources/views/livewire/admin/user-management.blade.php)
- [audit-log.blade.php](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php)

**Section sources**
- [AuthController.php](file://pdf-korektura/app/Http/Controllers/AuthController.php)
- [AdminController.php](file://pdf-korektura/app/Http/Controllers/AdminController.php)
- [User.php](file://pdf-korektura/app/Models/User.php)
- [ActivityLog.php](file://pdf-korektura/app/Models/ActivityLog.php)
- [ActivityLogService.php](file://pdf-korektura/app/Services/ActivityLogService.php)
- [auth.php](file://pdf-korektura/config/auth.php)
- [permission.php](file://pdf-korektura/config/permission.php)
- [0001_01_01_000000_create_users_table.php](file://pdf-korektura/database/migrations/0001_01_01_000000_create_users_table.php)
- [2024_06_10_100000_create_permission_tables.php](file://pdf-korektura/database/migrations/2024_06_10_100000_create_permission_tables.php)
- [login.blade.php](file://pdf-korektura/resources/views/auth/login.blade.php)
- [user-management.blade.php](file://pdf-korektura/resources/views/livewire/admin/user-management.blade.php)
- [audit-log.blade.php](file://pdf-korektura/resources/views/livewire/admin/audit-log.blade.php)

## Performance Considerations
- Session Management: Use secure, HTTP-only cookies and regenerate session IDs after login to mitigate session fixation attacks.
- Database Queries: Optimize queries for role and permission checks; consider caching frequently accessed role/permission data.
- Activity Logging: Batch or queue activity log writes to reduce latency during high-volume operations.
- LDAP Authentication: Configure connection pooling and timeouts to handle directory server load and network latency.

## Troubleshooting Guide
Common issues and resolutions:
- Authentication Failures: Verify guard and provider configurations in auth.php; check credential validation and session regeneration logic.
- Permission Denied Errors: Confirm roles and permissions are correctly assigned in the permission tables; ensure middleware is applied to protected routes.
- LDAP Authentication Problems: Validate LDAP provider settings and connectivity; test bind credentials and user search filters.
- Audit Log Gaps: Ensure ActivityLogService is invoked for all critical actions; verify database permissions and migration completeness.

**Section sources**
- [auth.php](file://pdf-korektura/config/auth.php)
- [permission.php](file://pdf-korektura/config/permission.php)
- [ActivityLogService.php](file://pdf-korektura/app/Services/ActivityLogService.php)
- [2024_06_10_140000_create_activity_logs_table.php](file://pdf-korektura/database/migrations/2024_06_10_140000_create_activity_logs_table.php)

## Conclusion
The PDF correction system implements robust user management through integrated authentication, Spatie Laravel Permission-based RBAC, comprehensive activity logging, and optional LDAP support. Administrators can efficiently manage users, assign documents, and monitor system activity. Developers can extend and customize the system by leveraging the modular architecture and configuration files.