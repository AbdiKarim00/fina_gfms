# Government Fleet Management System - User Roles and Use Cases

This document provides a comprehensive catalog of all user roles and their corresponding use case stories for the Government Fleet Management System (GFMS). The documentation is organized by user type hierarchy as defined in the Government Transport Policy (2024) and FLEET_MANAGEMENT_SYSTEM_ROLES.md.

Detailed documentation for each role can be found in the [roles directory](roles/), organized by user type hierarchy.

## Table of Contents

1. [Government Officials](#government-officials)
   1.1. [National Leadership](#national-leadership)
   1.2. [Senior Administration](#senior-administration)
   1.3. [Department Heads](#department-heads)
   1.4. [County Leadership](#county-leadership)
   1.5. [Operational Management](#operational-management)
   1.6. [Field Operations](#field-operations)
2. [Government Departments](#government-departments)
3. [Policy Implementation Bodies](#policy-implementation-bodies)
4. [Regulatory Bodies](#regulatory-bodies)
5. [Service Providers](#service-providers)
6. [Technology Systems](#technology-systems)

---

## Government Officials

Government officials are individuals with specific positions and responsibilities within the fleet management ecosystem, organized by hierarchical level.

### National Leadership

#### 1. Cabinet Secretary (National Treasury & Economic Planning)
- **Role Title**: Cabinet Secretary
- **Description**: Highest authority for policy decisions and approvals
- **System Access Level**: Full System Administrator
- **Reports To**: President/Cabinet
- **Overseen By**: Parliament

#### 2. Principal Secretaries
- **Role Title**: Principal Secretary
- **Description**: Authorization for major procurement and policy implementation
- **System Access Level**: Department Administrator
- **Reports To**: Cabinet Secretary

### Senior Administration

### Department Heads

#### 1. Heads of MDACs (Ministries, Departments, Agencies & Counties)
- **Role Title**: Head of MDAC
- **Description**: Chief Executives of State Corporations, Commissioners of Independent Offices
- **System Access Level**: Department Administrator
- **Reports To**: Principal Secretaries

#### 2. Chief Executives of State Corporations
- **Role Title**: Chief Executive
- **Description**: Leads state corporations in fleet management
- **System Access Level**: Department Administrator

#### 3. Commissioners of Independent Offices
- **Role Title**: Commissioner
- **Description**: Oversees independent offices' fleet operations
- **System Access Level**: Department Administrator

### County Leadership

#### 1. Governor
- **Role Title**: Governor
- **Description**: Chief executive of county government
- **System Access Level**: County Administrator
- **Reports To**: County Assembly

#### 2. Deputy Governor
- **Role Title**: Deputy Governor
- **Description**: Assists governor in county administration
- **System Access Level**: County Administrator

#### 3. County Executive Committee Members (CECs)
- **Role Title**: CEC Member
- **Description**: Members of county executive committee
- **System Access Level**: Department Administrator

#### 4. Chief Officers
- **Role Title**: Chief Officer
- **Description**: Senior administrative officers in county departments
- **System Access Level**: Operations Manager

### Operational Management

#### 1. Accounting Officers
- **Role Title**: Accounting Officer
- **Description**: Financial controls and asset management authority
- **System Access Level**: Financial Administrator

#### 2. Fleet Managers
- **Role Title**: Fleet Manager
- **Description**: Day-to-day fleet operations and user access management
- **System Access Level**: Operations Manager

#### 3. Chief Mechanical & Transport Engineer (CMTE)
- **Role Title**: CMTE Official
- **Description**: Technical oversight and maintenance standards
- **System Access Level**: Technical Specialist

### Field Operations

#### 1. Authorized Drivers
- **Role Title**: Authorized Driver
- **Description**: Vehicle operators with tracking and monitoring
- **System Access Level**: Basic User

#### 2. GVCU (Government Vehicle Check Unit) Personnel
- **Role Title**: GVCU Officer
- **Description**: Enforcement and compliance monitoring
- **System Access Level**: Compliance Monitor

## Government Departments

Institutional entities responsible for various aspects of fleet management.

### National Level Institutions

#### 1. National Treasury
- **Role Title**: National Treasury
- **Description**: Top-level coordinator for all government fleet operations

#### 2. Government Fleet Management Department (GFMD)
- **Role Title**: GFMD
- **Description**: Operational control over fleet management systems, coordinates with all MDACs

#### 3. Supporting National Institutions
- **Role Title**: Supporting Institution
- **Description**: Various institutions supporting fleet management operations

### County Level Institutions

#### 1. County Governments
- **Role Title**: County Government
- **Description**: Local implementation of national fleet policies

#### 2. County Fleet Management Units (CFMU)
- **Role Title**: CFMU
- **Description**: Parallel structure to GFMD at county level

### External Entities

#### 1. Original Equipment Manufacturers (OEMs)
- **Role Title**: OEM
- **Description**: Vehicle manufacturers and suppliers

#### 2. Insurance Companies
- **Role Title**: Insurance Provider
- **Description**: Providers of vehicle insurance services

#### 3. Authorized Garages and Service Providers
- **Role Title**: Service Provider
- **Description**: Providers of vehicle maintenance and repair services

## Policy Implementation Bodies

Entities responsible for policy execution and oversight.

## Regulatory Bodies

Entities with oversight and compliance functions.

#### 1. National Transport & Safety Authority (NTSA)
- **Role Title**: NTSA
- **Description**: Vehicle registration and inspection authority

#### 2. Judiciary
- **Role Title**: Judiciary
- **Description**: Legal oversight and dispute resolution

#### 3. Parliament/Legislature
- **Role Title**: Legislature
- **Description**: Legislative oversight and policy approval

## Service Providers

External entities providing goods and services.

## Technology Systems

Digital platforms and tools for fleet management.

---

This documentation structure provides a foundation for detailed role-based use case documentation. Each role will have its own detailed document covering:
1. Role title and description
2. System access level
3. Specific use cases they will perform
4. Permissions and restrictions
5. Integration points with other roles