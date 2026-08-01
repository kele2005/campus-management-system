# Campus Management System

A console-based Campus Management System built in PHP, covering parking permits, library book loans, and student performance tracking.

## Overview

This project simulates three common administrative functions on a university campus, run entirely from the terminal via PHP's CLI. It was built to practice procedural PHP fundamentals: functions, arrays, input validation, and control flow, without relying on a database or web framework.

## Features

- **Parking Permit Module**: apply for a permit, validate applicant details (name, age, permit type), and calculate permit pricing based on type (student/staff/visitor)
- **Library Module**: borrow books, calculate overdue fines, and view currently borrowed books
- **Student Performance Module**: capture student marks with validation (0–100 range), calculate averages, and classify results (Pass/Fail/Distinction)

## Tech Stack

- **Language**: PHP (procedural)
- **Interface**: Command-line, using `readline()` for input
- **Data**: Held in memory for the session (no database)

## Getting Started

Requires PHP installed locally (e.g. via XAMPP).

```bash
php index.php
```

Follow the on-screen menu to navigate between the Parking, Library, and Student Performance modules.

## What I learned

This project was a first step in structuring PHP logic into reusable functions, validating user input consistently across modules, and organizing a multi-module CLI application with a clear main menu flow.

## Possible extensions

- Persist data to a database (MySQL) instead of in-memory arrays
- Convert to a web-based interface
- Add authentication for different user roles (admin/staff/student)
