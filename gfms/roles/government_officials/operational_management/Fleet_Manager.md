# Fleet Manager - User Role Documentation

## Role Overview
- **Role Title**: Fleet Manager
- **Description**: Responsible for day-to-day fleet operations and user access management
- **System Access Level**: Operations Manager
- **Reports To**: Accounting Officer / Department Head
- **Department**: Individual MDACs (Ministries, Departments, Agencies & Counties)

## System Access and Permissions

### Access Level
Fleet Managers have operations manager access with comprehensive permissions for daily fleet management activities, including:
- Vehicle assignment and tracking
- Maintenance scheduling and coordination
- Driver management and monitoring
- Operational reporting and analytics
- User access management within their department

### Specific Permissions
- ✅ Assign vehicles to authorized drivers
- ✅ Schedule and track vehicle maintenance
- ✅ Manage driver records and certifications
- ✅ Monitor vehicle utilization and performance
- ✅ Generate operational reports
- ✅ Manage user accounts within their department
- ✅ Access real-time vehicle tracking data
- ✅ Approve driver trip requests

### Restrictions
- ❌ Cannot approve major procurement requests
- ❌ Cannot modify system policies or configurations
- ❌ Cannot access financial budget data
- ❌ Cannot override workflow approvals requiring higher authority
- ❌ Cannot modify driver personal information (only view)

## Use Case Stories

### 1. Vehicle Assignment and Management
**As a** Fleet Manager,
**I want to** assign vehicles to authorized drivers based on operational needs,
**So that** government operations can proceed efficiently while maintaining proper vehicle utilization.

**Preconditions**:
- Authorized drivers have been verified in the system
- Vehicles are available and roadworthy
- Operational requirements have been identified

**Main Flow**:
1. Log into the GFMS with operations manager credentials
2. Navigate to the Vehicle Assignment section
3. Review vehicle availability and driver requests
4. Check driver authorization and licensing status
5. Verify vehicle roadworthiness and maintenance status
6. Assign vehicle to driver with specified:
   - Duration of assignment
   - Purpose of use
   - Expected mileage limits
   - Return date
7. Generate assignment documentation
8. Notify driver of vehicle assignment
9. Update vehicle status in the system

**Postconditions**:
- Vehicle is assigned to authorized driver
- Vehicle status is updated in the system
- Driver receives assignment notification
- Assignment is recorded in audit trail

### 2. Maintenance Scheduling and Coordination
**As a** Fleet Manager,
**I want to** schedule and coordinate vehicle maintenance activities,
**So that** government vehicles remain roadworthy and operational costs are controlled.

**Preconditions**:
- Vehicles are registered in the system
- Maintenance schedules are defined by manufacturer recommendations
- Authorized service providers are available

**Main Flow**:
1. Log into the GFMS
2. Navigate to the Maintenance Management section
3. Review vehicle maintenance schedules and alerts
4. Identify vehicles requiring maintenance:
   - Based on mileage thresholds
   - Based on time intervals
   - Based on reported issues
5. Check vehicle availability for maintenance
6. Select appropriate service provider based on:
   - Type of maintenance required
   - Service provider certification
   - Cost considerations
7. Schedule maintenance appointment:
   - Date and time
   - Required services
   - Estimated duration
8. Generate maintenance work order
9. Notify driver and vehicle custodian
10. Update vehicle status to "in maintenance"

**Postconditions**:
- Maintenance appointments are scheduled
- Work orders are generated and distributed
- Vehicle status is updated
- Maintenance history is recorded

### 3. Driver Management and Monitoring
**As a** Fleet Manager,
**I want to** manage driver records and monitor driver performance,
**So that** only qualified and authorized personnel operate government vehicles.

**Preconditions**:
- Drivers are employed by the government
- Driver licensing information is available
- Driver authorization process is established

**Main Flow**:
1. Log into the GFMS
2. Navigate to the Driver Management section
3. Verify driver qualifications:
   - Valid driving license
   - Recent suitability test results
   - Certificate of good conduct
   - First Aid certificate
4. Review driver authorization status
5. Update driver records with:
   - License expiration dates
   - Certification renewals
   - Training completions
   - Incident history
6. Monitor driver performance through:
   - Vehicle tracking data
   - Fuel consumption patterns
   - Reported incidents
   - Maintenance feedback
7. Identify and address performance issues
8. Generate driver performance reports

**Postconditions**:
- Driver records are current and accurate
- Authorization status is verified
- Performance issues are identified and addressed
- Reports are available for management review

### 4. Operational Reporting
**As a** Fleet Manager,
**I want to** generate and submit operational reports,
**So that** management can assess fleet performance and make informed decisions.

**Preconditions**:
- Fleet operations data is available in the system
- Reporting requirements are defined
- Reporting period has ended

**Main Flow**:
1. Log into the GFMS
2. Navigate to the Reporting section
3. Select required report type:
   - Vehicle utilization report
   - Maintenance cost analysis
   - Fuel consumption report
   - Incident summary
4. Define reporting period and filters
5. Generate report with relevant data
6. Review report for accuracy and completeness
7. Add narrative commentary where required
8. Submit report to supervisor
9. Archive report for future reference

**Postconditions**:
- Accurate operational reports are generated
- Management receives timely performance data
- Historical data is maintained for trend analysis
- Compliance requirements are met

## Integration Points with Other Roles

### Accounting Officers
- Receives budget allocation for fleet operations
- Submits maintenance and operational cost reports
- Requests approval for maintenance expenditures above thresholds
- Coordinates on asset management practices

### CMTE Officials
- Requests technical inspections and approvals
- Coordinates on maintenance scheduling
- Receives technical guidance on vehicle care
- Reports on recurring maintenance issues

### Authorized Drivers
- Assigns vehicles to drivers
- Monitors driver compliance with policies
- Receives incident reports from drivers
- Provides guidance on vehicle operation

### GVCU Officers
- Receives compliance violation reports
- Coordinates on vehicle inspections
- Implements corrective actions for violations
- Shares enforcement data for trend analysis

### Department Heads
- Reports on fleet utilization and performance
- Requests vehicle acquisitions when needed
- Receives operational performance reports
- Coordinates on policy implementation

## Security Considerations

### Authentication
- Multi-factor authentication required
- Role-based session timeout (45 minutes of inactivity)
- Password complexity requirements enforced
- Regular credential updates required

### Authorization
- Role-based access control with operations manager privileges
- All actions logged with detailed audit trail
- Vehicle assignment requires electronic approval
- Access to sensitive driver data requires additional verification

### Data Protection
- Operational data encrypted at rest and in transit
- Regular security audits and vulnerability assessments
- Compliance with Data Protection Act and government security standards
- Driver privacy protected in accordance with privacy laws

## Reporting and Analytics

### Available Reports
- Daily vehicle utilization dashboard
- Weekly maintenance activity report
- Monthly fuel consumption analysis
- Quarterly operational performance review
- Annual fleet efficiency assessment
- Incident summary reports
- Driver performance evaluations

### Frequency
- Real-time access to vehicle tracking data
- Daily operational summaries
- Weekly maintenance reports
- Monthly cost analyses
- Quarterly performance reviews
- Annual comprehensive assessments

## System Requirements

### Technical Requirements
- High-speed internet connection
- Compatible web browser (Chrome, Firefox, Safari, Edge)
- Screen resolution of at least 1366x768
- JavaScript enabled
- Mobile device compatibility for field operations

### Performance Expectations
- System response time under 3 seconds for standard operations
- Report generation within 45 seconds for standard reports
- Real-time vehicle tracking data updates
- 99% uptime availability

## Training and Support

### Initial Training
- Comprehensive system orientation (4 hours)
- Operational procedures training (3 hours)
- Security and compliance training (1 hour)
- Hands-on practice sessions with mentor

### Ongoing Support
- Business hours help desk support (8AM-6PM)
- Regular system updates and notifications
- Periodic refresher training sessions
- Access to user manuals and documentation
- Peer support network with other fleet managers

## Change Management

### Process for Updates
- All changes must be documented and approved by supervisor
- User feedback is collected and reviewed monthly
- Major system updates require formal approval process
- Emergency changes require immediate post-action documentation

### Communication
- Changes are communicated through official channels
- Users receive advance notice of planned updates (minimum 24 hours)
- Post-update summaries are distributed to all stakeholders
- Training materials are updated to reflect changes