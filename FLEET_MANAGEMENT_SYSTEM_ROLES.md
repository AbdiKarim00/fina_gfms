# Fleet Management System - Role-Based Access Control and Hierarchical Structure

## Overview

This document outlines the hierarchical structure and role-based access control system for the Government Fleet Management System (GFMS), derived from the Government Transport Policy (2024). The structure defines user permissions, workflow automation pathways, and reporting mechanisms essential for effective fleet management.

## 1. Entity Types and Classification

### 1.1 Government Officials/Roles
Individuals with specific positions and responsibilities within the fleet management ecosystem.

### 1.2 Government Departments/Organizations
Institutional entities responsible for various aspects of fleet management.

### 1.3 Vehicles/Vehicle Types
Physical assets managed by the system.

### 1.4 Policy Implementation Bodies
Entities responsible for policy execution and oversight.

### 1.5 Regulatory Bodies
Entities with oversight and compliance functions.

### 1.6 Service Providers
External entities providing goods and services.

### 1.7 Technology Systems
Digital platforms and tools for fleet management.

## 2. Organizational Hierarchy

### 2.1 Government Officials Hierarchy (Top-down)

#### Level 1: National Leadership
- **Cabinet Secretary (National Treasury & Economic Planning)**
  - Highest authority for policy decisions and approvals
  - Reports to: President/Cabinet
  - Overseen by: Parliament

#### Level 2: Senior Administration
- **Principal Secretaries**
  - Authorization for major procurement and policy implementation
  - Reports to: Cabinet Secretary

#### Level 3: Department Heads
- **Heads of MDACs** (Ministries, Departments, Agencies & Counties)
  - Chief Executives of State Corporations
  - Commissioners of Independent Offices
  - Reports to: Principal Secretaries

#### Level 4: County Leadership
- **Governor**
- **Deputy Governor**
- **County Executive Committee Members (CECs)**
- **Chief Officers**

#### Level 5: Operational Management
- **Accounting Officers**
  - Financial controls and asset management authority
- **Fleet Managers**
  - Day-to-day fleet operations and user access management
- **Chief Mechanical & Transport Engineer (CMTE)**
  - Technical oversight and maintenance standards

#### Level 6: Field Operations
- **Authorized Drivers**
  - Vehicle operators with tracking and monitoring
- **GVCU (Government Vehicle Check Unit) Personnel**
  - Enforcement and compliance monitoring

### 2.2 Institutional Hierarchy

#### National Level Institutions
1. **National Treasury**
   - Top-level coordinator for all government fleet operations
   
2. **Government Fleet Management Department (GFMD)**
   - Subordinate to National Treasury
   - Operational control over fleet management systems
   - Coordinates with all MDACs
   
3. **Supporting National Institutions**
   - National Police Service
   - National Transport & Safety Authority (NTSA)
   - State Law Office
   - Office of the Director of Public Prosecutions
   - Judiciary
   - Parliament/Legislature
   - Supplies Branch

#### County Level Institutions
1. **County Governments**
   - Local implementation of national fleet policies
   
2. **County Fleet Management Units (CFMU)**
   - Parallel structure to GFMD at county level
   - Reports to County Treasury and GFMD

#### External Entities
- Original Equipment Manufacturers (OEMs)
- Insurance Companies
- Authorized Garages and Service Providers
- Professional Bodies

## 3. Role-Based Access Control System

### 3.1 Administrative Roles

| Role | System Access Level | Key Permissions |
|------|-------------------|-----------------|
| **Cabinet Secretary** | Full System Oversight | Policy governance, budget oversight, system monitoring |
| **Principal Secretary** | Department Administrator | Major procurement approval, policy implementation oversight |
| **Accounting Officer** | Financial Administrator | Asset management, budget allocation, financial reporting |
| **GFMD Director** | Operations Administrator | System operations, user management, policy enforcement |

### 3.2 Operational Roles

| Role | System Access Level | Key Permissions |
|------|-------------------|-----------------|
| **Fleet Manager** | Operations Manager | Vehicle assignment, maintenance scheduling, driver management |
| **CMTE Official** | Technical Specialist | Maintenance approval, technical inspections, service provider management |
| **GVCU Officer** | Compliance Monitor | Vehicle checks, violation reporting, compliance auditing |
| **Authorized Driver** | Basic User | Vehicle reservation, trip logging, incident reporting |

### 3.3 Monitoring and Reporting Roles

| Role | System Access Level | Key Permissions |
|------|-------------------|-----------------|
| **M&E Specialist** | Analytics Viewer | Performance reporting, KPI monitoring, data analysis |
| **Audit Officer** | Audit Viewer | Financial audits, compliance verification, transaction review |
| **Policy Analyst** | Policy Viewer | Policy compliance monitoring, recommendation submission |

## 4. Workflow Automation Pathways

### 4.1 Vehicle Acquisition Workflow
1. **Initiation**: Department Head identifies need
2. **Approval**: Accounting Officer reviews budget
3. **Authorization**: GFMD approves allocation
4. **Procurement**: Supplies Branch executes purchase
5. **Registration**: NTSA registers vehicle
6. **Assignment**: Fleet Manager assigns to user

### 4.2 Maintenance Workflow
1. **Request**: Driver/Operator submits maintenance request
2. **Assessment**: Fleet Manager evaluates need
3. **Authorization**: CMTE approves service (if >KES 100,000)
4. **Execution**: Authorized garage performs service
5. **Verification**: Fleet Manager confirms completion
6. **Documentation**: System updates maintenance records

### 4.3 Incident Reporting Workflow
1. **Notification**: Driver reports incident within 24 hours
2. **Investigation**: GVCU/Police investigate
3. **Documentation**: Driver completes accident form
4. **Review**: Fleet Manager evaluates report
5. **Processing**: Insurance claims handled
6. **Analysis**: GFMD reviews for policy improvements

## 5. Reporting Mechanisms

### 5.1 Real-Time Tracking Reports
- GPS location data
- Fuel consumption monitoring
- Driver behavior analytics
- Vehicle utilization statistics

### 5.2 Periodic Compliance Reports
- Monthly maintenance reports
- Quarterly fleet utilization analysis
- Bi-annual vehicle inspections
- Annual asset inventory

### 5.3 Strategic Policy Reports
- Annual fleet performance dashboard
- 5-year policy implementation review
- Budget vs. actual expenditure analysis
- Risk assessment and mitigation reports

## 6. System Integration Requirements

### 6.1 Internal System Integrations
- IFMIS (Integrated Financial Management Information System)
- FOTIMS (Foreign Travel Information Management System)
- HR Systems for personnel data
- Procurement Systems for acquisition tracking

### 6.2 External System Integrations
- NTSA vehicle registration database
- Insurance provider claim systems
- Fuel card provider transaction systems
- OEM maintenance scheduling platforms

## 7. Security and Access Controls

### 7.1 Authentication
- Multi-factor authentication for administrative roles
- Personal number-based login
- Role-based session timeouts

### 7.2 Authorization
- Least privilege principle implementation
- Separation of duties enforcement
- Audit trail for all system actions

### 7.3 Data Protection
- Encryption for data at rest and in transit
- Regular security audits
- Compliance with Data Protection Act

## 8. Implementation Roadmap

### Phase 1: Core System Deployment
- User role definitions and access controls
- Basic vehicle registration and tracking
- Fundamental reporting capabilities

### Phase 2: Workflow Automation
- Automated approval workflows
- Integrated maintenance scheduling
- Real-time compliance monitoring

### Phase 3: Advanced Analytics
- Predictive maintenance algorithms
- Fleet optimization recommendations
- Comprehensive dashboard reporting

This structure ensures proper governance, accountability, and efficiency in government fleet operations while maintaining compliance with the Government Transport Policy (2024).