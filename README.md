# 🏢 Enterprise Employee Portal 

A comprehensive, scalable Employee Management and HR Workflow Portal built with the **TALL Stack** and **FilamentPHP**. 

> **🎓 IHK Final Project (Betriebliche Projektarbeit)**
> This application originated as my official final examination project for my state-recognized degree as a Software Developer (IHK). Since graduating, I have actively maintained and expanded the system with new features and architectural improvements.

## 🎯 The Business Case
Many medium-sized companies struggle with decentralized employee data and analog workflows. The goal of this project was to digitize the HR department, specifically targeting the manual, paper-based "Sick Leave" (Krankmeldung) process. 

By replacing phone calls and paper forms with a digital workflow, automated PDF generation, and centralized dashboards, this system significantly reduces administrative overhead and ensures data integrity.

## 🛠 Tech Stack & Architecture
* **Framework:** Laravel 10/11 (PHP 8+)
* **Admin Panel & UI:** FilamentPHP (v3), Tailwind CSS
* **Frontend Interactivity:** Livewire 3, Alpine.js
* **Database:** MySQL (Structured via Eloquent ORM)
* **PDF Generation:** Spatie Browsershot / Puppeteer
* **Architecture:** MVC Pattern, Service Classes, Action-based logic.

## ✨ Core Features

### 👥 Employee Management (CRUD)
* Centralized management of employee records, departments, and roles.
* Complex Eloquent relationships (Many-to-Many for Department Leaders, One-to-Many for Notifications).
* Secure password handling and authentication.

### 🤒 Digital Sick Leave Workflow (Krankmeldesystem)
* Dynamic Livewire forms that adapt based on user input (e.g., conditionally showing fields if a doctor was visited).
* Validation of specific German HR rules (§5 EntgFG).
* **Automated PDF Generation:** Converts the digital sick leave record into a standardized, downloadable PDF document for official HR records.

### 🌍 Multilingual Support
* Custom-built language switcher component using Livewire and Alpine.js.
* Dynamic translation of the Filament UI and custom pages (English, German, Turkish).

### 📊 Dashboard & Analytics
* Interactive widgets displaying real-time HR statistics and active sick leaves.

## 🧪 Code Quality & Testing
A major focus of this project is reliability and code quality:
* **Feature & Unit Testing:** Extensive test suites written with **PEST** to ensure CRUD operations and authorization rules work flawlessly.
* **Code Coverage:** Monitored using **PCOV** to ensure critical business logic is fully tested.
* **Static Analysis:** Codebase checked with **Qodana** to prevent vulnerabilities and maintain clean code standards.

## 📸 Screenshots


<details>
  <summary>Click to view Screenshots</summary>
     <img width="883" height="668" alt="image" src="https://github.com/user-attachments/assets/3043bad7-9566-4beb-98c3-736f124e1e88" />
<img width="581" height="908" alt="image" src="https://github.com/user-attachments/assets/fd27b470-de64-4098-b6c3-aac0e584855c" />
<img width="568" height="502" alt="image" src="https://github.com/user-attachments/assets/6edf9fef-25a2-40eb-9aad-bfad7005cd4c" />
</details>

## 🚀 Local Setup & Installation

To run this project locally, ensure you have PHP 8+, Composer, and Node.js installed. Note that Puppeteer is required for the PDF generation feature.

1. **Clone the repository:**
   ```bash
   git clone https://github.com/kadirgulec/employee-portal.git
   cd employee-portal
   ```
2. **Install PHP and Node dependencies:**
   ```bash
   composer install
   npm install
   npm run build
   ```
3. **Install Puppeteer (for Spatie Browsershot / PDF Generation):**
   ```bash
   npm install puppeteer
   ```
4. **Environment & Database Setup:**
    ```bash
    cp .env.example .env
    php artisan key:generate
   ```
5. **Run Migrations & Seeders:**
    ```bash
    php artisan migrate --seed
    ```
6. **Link Storage (for Avatars & generated PDFs):**
    ```bash
    php artisan storage:link
    ```
7. **Start the development server:**
    ```bash
    php artisan serve
    ```
    
   
