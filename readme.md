# Property Portal — Lead Generation Platform
> Internship assessment project for Waqar Siddiqui
<img width="1835" height="927" alt="image" src="https://github.com/user-attachments/assets/18fcf7d0-cddc-4327-bba7-8b48e6dca0c2" />
 <a href="https://youtu.be/MQ6rg831AaU" target="_blank">
    <img src="https://img.youtube.com/vi/MQ6rg831AaU/0.jpg" alt="Property Portal Demo" width="480" style="border:0;">
</a>


---

## Files

| File | Purpose |
|------|---------|
| `index.html` | Landing page (frontend) |
| `submit_lead.php` | Form handler (backend) |
| `database.sql` | Database migration + sample data |
| `README.md` | This file |

> Place all files in the same folder on your server.  
> Put `success.jpg` in the same folder too (used as hero background).

---

## Local Setup (XAMPP / WAMP)

### 1. Copy files
```
htdocs/property-portal/
├── index.html
├── submit_lead.php
├── database.sql
├── success.jpg
└── README.md
```

### 2. Create the database
Open **phpMyAdmin** → SQL tab, then run:
```sql
SOURCE /path/to/database.sql;
```
Or from terminal:
```bash
mysql -u root -p < database.sql
```

### 3. Check DB credentials in submit_lead.php
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'property_portal');
define('DB_USER', 'root');
define('DB_PASS', '');        // your XAMPP password (usually blank)
```

### 4. Open in browser
```
http://localhost/property-portal/index.html
```

---

## Features Checklist

### Part 1 — Frontend ✅
- [x] Hero: "Find Your Perfect Property" heading + subheading
- [x] Benefits section (3 cards: Verified, Quick Response, Personalized)
- [x] Lead form — all 8 required fields (name, phone, email, city, property_type, purpose, budget, message)
- [x] Mobile responsive (Tailwind CSS)
- [x] Clean, professional UI (Plus Jakarta Sans font)
- [x] Fireworks canvas animation on hero
- [x] Scroll reveal animations

### Part 2 — Backend ✅
- [x] POST handler in submit_lead.php
- [x] Server-side validation of all fields
- [x] PDO prepared statements (SQL injection safe)
- [x] Data saved to `property_leads` table
- [x] JSON response returned to frontend

### Part 3 — Core Requirements ✅
- [x] Mobile responsive
- [x] Clean UI
- [x] Client-side form validation (inline errors per field)
- [x] Server-side validation
- [x] Data saved to database
- [x] Success message: "Thank you! Your inquiry has been submitted. An agent will contact you shortly."

### Part 4 — Bonus ✅
- [x] Loading spinner on submit button
- [x] Honeypot spam protection (hidden `website` field)
- [x] IP-based rate limiting (5 per hour)
- [x] Success animation (scale-in card + pulsing ring)
- [x] Reference number shown on success (e.g. PP-M3X1A-4F2C)

---

## Database Table

```
property_leads
├── id             INT AUTO_INCREMENT PK
├── ref            VARCHAR(20) UNIQUE       ← e.g. PP-M3X1A-4F2C
├── name           VARCHAR(100)
├── phone          VARCHAR(20)
├── email          VARCHAR(150)
├── city           VARCHAR(100)
├── property_type  ENUM(House,Apartment,Plot,Commercial)
├── purpose        ENUM(Buy,Rent,Sell)
├── budget         BIGINT UNSIGNED NULL
├── message        TEXT NULL
├── ip_address     VARCHAR(45)
├── status         ENUM(new,contacted,closed) DEFAULT 'new'
├── created_at     DATETIME
└── updated_at     DATETIME
```

---

## Spam Protection (Bonus)

1. **Honeypot** — a hidden `<input name="website">` that real users never see or fill. If it has a value, the submission is silently dropped server-side.
2. **Rate limiting** — max 5 submissions per IP per hour, tracked in `/tmp` files.
3. **Prepared statements** — all DB queries use PDO parameter binding.

---

## Demo Note
If PHP/MySQL is not running, the frontend still shows the success overlay as a fallback — useful for quick client-side demos.

---

*Built for the Property Portal internship assessment — Waqar Siddiqui*
