# HR Module - Flow Diagram & Relationships

## Overview
The HR Module consists of 3 interconnected sections that manage organizational structure and workforce scheduling.

```
┌─────────────────────────────────────────────────────────────┐
│                     HR MODULE ARCHITECTURE                   │
└─────────────────────────────────────────────────────────────┘

  ┌──────────────┐      ┌──────────────┐      ┌──────────────┐
  │  DEPARTMENTS │◄────►│   POSITIONS  │◄────►│    SHIFTS    │
  └──────────────┘      └──────────────┘      └──────────────┘
         │                     │                     │
         ▼                     ▼                     ▼
  ┌──────────────┐      ┌──────────────┐      ┌──────────────┐
  │   Company    │      │  Department  │      │   Employee   │
  │ Organization │      │   Manager    │      │   Schedule   │
  └──────────────┘      └──────────────┘      └──────────────┘
```

## 1. DEPARTMENTS (Department Manager)

### Purpose
Organize company structure into hierarchical units/departments.

### Data Structure
```
Department
├── id (primary key)
├── name (e.g., "Sales Department")
├── code (e.g., "DEPT-001")
├── description
├── company_id (belongs to Company)
├── manager_id (belongs to Employee)
├── parent_id (self-referential for hierarchy)
├── is_active (boolean)
└── relationships
    ├── company (belongsTo)
    ├── manager (belongsTo Employee)
    ├── parent (belongsTo Department)
    ├── children (hasMany Department)
    └── positions (hasMany Position)
```

### UI Components
- **Card Layout**: 3-column grid with image header
- **Visual Identity**: Left panel with background image + badge + code
- **Filters**: Search, Company, Status
- **Actions**: Edit, Delete with SweetAlert confirmation

### Key Features
- Hierarchical structure (parent/child departments)
- Manager assignment
- Company association
- Employee count display

---

## 2. POSITIONS (Position Manager)

### Purpose
Define job roles within departments with salary benchmarks.

### Data Structure
```
Position
├── id (primary key)
├── name (e.g., "Senior Sales Manager")
├── code (e.g., "POS-001")
├── description / responsibilities
├── department_id (belongs to Department)
├── default_salary (benchmark)
├── is_active (boolean)
└── relationships
    ├── department (belongsTo)
    └── employees (hasMany)
```

### UI Components
- **Card Layout**: Same 3-column grid as Departments
- **Visual Identity**: Left panel with image + badge + position code
- **Filters**: Search, Department, Status
- **Actions**: Edit, Delete with SweetAlert confirmation

### Key Features
- Department assignment
- Salary benchmarking
- Employee headcount tracking
- Role description

### Relationship Flow
```
Employee ──belongsTo──► Position ──belongsTo──► Department
```

---

## 3. SHIFTS (Shift Manager)

### Purpose
Manage workforce schedules and operational time windows.

### Data Structure
```
Shift
├── id (primary key)
├── name (e.g., "Morning Shift")
├── code (e.g., "SHIFT-001")
├── start_time (HH:MM format)
├── end_time (HH:MM format)
├── working_hours (calculated)
├── break_start (nullable)
├── break_end (nullable)
├── is_active (boolean)
└── relationships
    └── employees (hasMany)
```

### UI Components
- **Card Layout**: Same 3-column grid
- **Visual Identity**: Left panel with image + badge + shift code
- **Modal Layout**: 
  - Left: Shift Name + Times (Start/End) + Breaks
  - Right: Working Hours (auto-calculate) + Status Toggle + Summary
- **Filters**: Search, Status
- **Actions**: Edit, Delete with SweetAlert confirmation

### Key Features
- Time window management (start/end)
- Break scheduling
- Automatic working hours calculation
- Employee assignment tracking

---

## Relationships & Dependencies

### Hierarchy
```
Company
  └── Departments
        └── Positions
              └── Employees (assigned to Position)
                    └── Shifts (assigned to Employee schedule)
```

### Database Relationships
1. **Departments ↔ Companies**: Many-to-One
2. **Departments ↔ Positions**: One-to-Many  
3. **Positions ↔ Employees**: One-to-Many
4. **Shifts ↔ Employees**: Many-to-Many (via pivot)

### Data Flow
```
┌─────────────┐     ┌─────────────┐     ┌─────────────┐
│   Create    │────►│   Assign    │────►│   Schedule  │
│  Department │     │  Position   │     │    Shift    │
└─────────────┘     └─────────────┘     └─────────────┘
      │                   │                   │
      ▼                   ▼                   ▼
 Company Code        Dept Context       Time Window
 Parent Dept         Manager            Working Hours
```

---

## Common UI Patterns

### Card Design (All 3 Sections)
```
┌─────────────────────────────────┐
│  ┌──────────┐  ┌────────────┐ │
│  │  IMAGE   │  │   TITLE    │ │
│  │  + BADGE │  │  SUBTITLE  │ │
│  │  + CODE  │  │            │ │
│  └──────────┘  │  STATS     │ │
│                │  ───────────│ │
│                │  [EDIT][DEL]│ │
│                └────────────┘ │
└─────────────────────────────────┘
```

### Modal Design (Shift Example)
```
┌─────────────────────────────────────────┐
│  SHIFT DETAILS                    [X]   │
├──────────────────┬──────────────────────┤
│                  │                      │
│  SHIFT NAME      │   WORKING HOURS      │
│  ┌────────────┐  │   ┌──────────────┐   │
│  │ Morning    │  │   │      8       │   │
│  │ SHIFT-001  │  │   └──────────────┘   │
│  └────────────┘  │                      │
│                  │   [Active Toggle]    │
│  START │ END    │                      │
│  09:00 │ 17:00  │   SHIFT SUMMARY      │
│                  │   • Window: 09-17   │
│  BREAK START/END │   • Break: 12-13    │
│  12:00   13:00  │   • Total: 8 hrs    │
│                  │                      │
└──────────────────┴──────────────────────┘
```

---

## Technical Implementation

### Shared Components
- `x-admin-page-standard`: Page header with title + action button
- `x-admin-filter-bar`: Search + filter inputs + reset button
- `x-admin-data-view`: Grid wrapper with empty state
- `x-admin-modal`: Form modal with 2-column layout
- SweetAlert: Delete confirmations
- Toast notifications: Success/error feedback

### Alpine.js Pattern
```javascript
function sectionManager() {
    return {
        // State
        items: [],
        search: '',
        filters: {},
        form: {},
        
        // Methods
        initComponent() { this.fetchData(); },
        fetchData() { /* API call with filters */ },
        resetFilters() { /* Clear + fetch */ },
        openCreate() { /* Reset form + show modal */ },
        openEdit(item) { /* Populate form + show modal */ },
        submit() { /* POST/PUT + refresh */ },
        deleteTask(id) { /* SweetAlert + DELETE */ }
    }
}
```

### Validation Rules
- **Departments**: name, code, company_id, manager_id, parent_id, is_active
- **Positions**: name, code, department_id, default_salary, is_active
- **Shifts**: name, code, start_time, end_time, working_hours, break_start, break_end, is_active

---

## User Workflow

### Typical User Journey
1. **Setup Departments** → Create organizational units
2. **Create Positions** → Define roles within departments
3. **Assign Shifts** → Set work schedules
4. **Add Employees** → Link to positions & shifts

### Quick Actions
- All sections support: Search, Filter, Create, Edit, Delete
- Bulk actions via filters
- Real-time updates (no page refresh)
- Consistent UX across all 3 sections
