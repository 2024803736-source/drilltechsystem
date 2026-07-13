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



## 👩‍💻 My Role & Technical Implementation (STAR Breakdown)

- **Situation:** In complex enterprise resource planning (ERP) solutions like DrillTech, managing isolated access control permissions across distinct corporate entities is a critical security and functional requirement. The platform needed to cater to three highly distinct user personas—System Administrators managing payroll, Commercial Clients requesting new infrastructure projects, and Field Employees tracking their workspace profiles—all operating off a unified relational database architecture.

- **Task:** The core engineering challenge was to design a robust, centralized multi-tier login terminal that safely provisions and routes users to their respective, isolated dashboards based on encrypted session tokens. Additionally, the system had to dynamically fetch and cross-reference unique data relationships in real-time (e.g., locking a client to view only their project receipts, and a worker to view only their personal payroll stubs) without causing security leaks or cross-account data exposure.

- **Action:** I engineered a custom Role-Based Access Control (RBAC) routing mechanism powered by procedural PHP state tracking. Upon authentication, server-side code intercepts the input tokens, categorizes the global `$_SESSION` environment vector, and initiates defensive validation checkpoints at the entry of each portal. To ensure absolute data isolation across the system, I utilized multi-table foreign-key relational mappings via precise MySQL query filters to safely isolate data blocks.

    - **Dynamic Multi-Portal Routing & Session Verification Engine**
      Prevents cross-portal access breaches by actively intercepting user sessions and dynamically mapping distinct data tables depending on the user's role identifier.
      ```php
      // 1. Session Defensive Guarding & Role Verification
      session_start();
      if (!isset($_SESSION['user_id']) \vert{}\vert{}$_SESSION['user_role'] !== 'client') {
          // Immediately reject unauthorized administrative or employee bypass attempts
          header("Location: ../mainpage.php?error=unauthorized_access");
          exit();
      }

      $clientID =$_SESSION['user_id'];

      // 2. Data Isolation Execution via Dynamic Relational Mapping
      // Ensures clients can ONLY extract financial records tied directly to their profile
      $query = "SELECT p.Project_Name, pay.Amount_Paid, pay.Payment_Status, pay.Payment_Date 
                FROM project p 
                INNER JOIN payment pay ON p.Project_ID = pay.Project_ID 
                WHERE p.Client_ID = ?";
                
      $stmt = mysqli_prepare($conn,$query);
      mysqli_stmt_bind_param($stmt, "s", $clientID);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      ```

- **Result:** 
  - **Bulletproof Authorization Flow:** Secured zero-leak portal boundaries, completely blocking any cross-role URL manipulation or direct-file access hijacks.
  - **Dynamic Multi-Tenant Experience:** Successfully unified three enterprise workflows into one engine, where administrative data changes (like changing a project to 'Completed') instantaneously reflect as valid downloadable invoice triggers on the client’s portal and shift internal operations on the employee grid.
  - **Enterprise-Grade Data Security:** Maintained structural database normalization while keeping execution speeds optimized through filtered relational querying.