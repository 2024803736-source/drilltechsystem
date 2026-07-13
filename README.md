# DrillTech Project & Resource Management System 🔧

## 📌 Overview

A web-based enterprise resource planning (ERP) platform for heavy industry drilling operations.  

Built as part of database system development coursework.



## 🚀 Features

- Multi-Role Authentication Layer (Admin, Client, and Employee)[cite: 1]
- Dynamic Workforce Allocation Dashboard
- Automated Staffing Constraint Verification (Min 5 General Workers)
- Variable Equipment Usage Tracking (Days vs Unit computational metrics)
- Project State Machine Tracking (Pending, On Going, Completed)
- Executive Reporting & Operations Ledger Generation



## 🛠️ Tech Stack

- PHP (Procedural Server Control)
- MySQL Database (Relational Data Models)
- HTML5 / CSS3 / JavaScript (ES6)



## ▶️ How to Run

1. Clone this repository into your local server directory (e.g., `htdocs`).
2. Import the database schema (`.sql`) file inside your local **phpMyAdmin**.
3. Configure your database connectivity credentials inside the connection script.
4. Access the login terminal via your local web browser (`http://localhost/DrillTech/`).



## 📺 System Walkthrough & Live Video Demo

> 🎥 **[CLICK HERE TO WATCH THE 2-MINUTE SYSTEM DEMO](TAMPAL_LINK_GOOGLE_DRIVE_ANDA_DI_SINI)**
> 
> *Click the link above to watch the full system execution. This video demonstrates user workflow loops, database resource allocation, and dynamic server-side validation checks in real-time.*



## 👩‍💻 My Role

- **Situation:** As the developer, I needed to implement an administrative control terminal for managing heavy construction personnel. The system required allocating multiple worker tiers across simultaneous timelines without causing database integrity failures or duplicate entry errors when data was modified.
- **Task:** The core challenge involved enforcing a strict organizational safety rule: **No project deployment can proceed unless it contains a safe baseline of at least 5 General Workers**. Concurrently, the system had to map dynamic equipment metrics across multiple bridging tables in a single operation.
- **Action:** I developed a defensive validation pipeline, combining client-side checks with an array-count evaluation in native PHP on the server. To prevent database duplication-key exceptions during assignment updates, I structured an automated cleanup sweep (`DELETE` cascade) that clears stale records before executing the multi-row data persistence loop.
    - **Multi-Tier Resource Validation & Data Persistence Engine**
      Validates array length parameters before committing mutations, separating multi-tier positions dynamically while computing data entry parameters.
      ```php
      // Server-side constraint verification
      $workers = isset($_POST['workers']) ?$_POST['workers'] : [];
      if (count($workers) < 5) {
          echo "<script>alert('Please select at least 5 General Workers.'); window.history.back();</script>";
          exit();
      }

      // Purge past assignments to ensure absolute integrity on overwrite
      mysqli_query($conn, "DELETE FROM assigned_employee WHERE Project_ID = '$projectID'");
      
      // Execute multi-row batch data insertion
      foreach ($workers as$w) {
          if (!empty($w)) {$query = "INSERT INTO assigned_employee (Project_ID, Employee_ID, ProjectEmp_StartD, ProjectEmp_EndD) VALUES (?, ?, ?, ?)";
              $stmt = mysqli_prepare($conn,$query);
              mysqli_stmt_bind_param($stmt, "ssss", $projectID,$w, $startDate,$endDate);
              mysqli_stmt_execute($stmt);
          }
      }
      ```
- **Result:** This eliminated cross-table data synchronization bugs and primary key collision errors completely. The platform successfully manages site operations, blocking inadequate crew sizes before they reach the database ledger.