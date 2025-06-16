# 🏥 Health Appointment Request System

A mini full-stack appointment request system built with **Laravel 10 (API)** and **Vue 3 + Vite + Tailwind CSS (SPA)**.

Patients can request health appointments. Doctors can view, approve, or reject them. The system supports authentication, role-based views, validation, and authorization.

---

## 🚀 Tech Stack

### Backend
- Laravel 10
- Sanctum (for API authentication)
- MySQL
- Laravel Policies
- Laravel Notifications (optional)
- Laravel Echo + Pusher (optional)

### Frontend
- Vue 3 (Composition API)
- Vite
- Tailwind CSS
- Vue Router
- Axios
- Pinia (optional)

---

## 🛠️ Local Setup Instructions

### Prerequisites
- PHP 8.2+
- Composer
- Node.js (v16+)
- MySQL or SQLite
- [Pusher account](https://pusher.com/) (optional, for real-time)

---

### 🔧 Backend (Laravel API)

1. **Clone the repo & move into backend folder**
```bash
git clone https://github.com/your-username/health-appointment-system.git
cd health-appointment-system/backend
```

2. **Install dependencies**
```bash
composer install
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure DB in `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=appointment_db
DB_USERNAME=root
DB_PASSWORD=
```

5. **Run migrations**
```bash
php artisan migrate
```

6. **Run the API**
```bash
php artisan serve
```

---

### 🌐 Frontend (Vue 3 SPA)

1. **Navigate to frontend folder**
```bash
cd ../frontend
```

2. **Install dependencies**
```bash
npm install
```

3. **Run the frontend dev server**
```bash
npm run dev
```

> Make sure the backend is running on `http://127.0.0.1:8000`. Update Axios base URL if needed.

---

## 🔐 Authentication

- Uses Laravel Sanctum
- Register/Login via API
- Stores token in localStorage

---

## 🔄 API Endpoints

### Auth Routes (Sanctum)
| Method | Endpoint | Description |
|--------|----------|-------------|
| POST   | `/api/register` | Register new user (patient/doctor) |
| POST   | `/api/login`    | Login and get token |
| POST   | `/api/logout`   | Logout (requires auth) |

### Appointment Request APIs

| Method | Endpoint | Description | Access |
|--------|----------|-------------|--------|
| POST   | `/api/appointments`       | Create a new appointment request | Patient |
| GET    | `/api/appointments`       | Get own requests or assigned ones | Patient / Doctor |
| PUT    | `/api/appointments/{id}`  | Approve/Reject a request | Doctor |

---

## 👤 Roles

- **Patient**
  - Can submit appointment requests
  - Can view status updates on their requests

- **Doctor**
  - Can view assigned requests
  - Can approve/reject with remarks

---

## 🧪 Tests (Bonus)

To run feature tests (optional):

```bash
php artisan test
```

> Includes basic feature tests for appointment submission and approval.

---

## 🔔 Notifications (Bonus)

- Patients are notified via Laravel Notifications when a doctor responds.

---

## 📡 Real-Time Updates (Bonus)

- Patients receive real-time status updates using Laravel Echo and Pusher.
- To enable:
  - Create a free [Pusher](https://pusher.com/) account
  - Configure `.env`:

```env
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=your-cluster
```

---

## 👥 Test Users

| Role    | Email               | Password  |
|---------|---------------------|-----------|
| Patient | patient@example.com | password  |
| Doctor  | doctor@example.com  | password  |

> You can seed these manually or register via the frontend.

---

## 📂 Project Structure

```bash
health-appointment-system/
├── backend/       # Laravel API
│   ├── app/
│   ├── database/
│   └── routes/api.php
├── frontend/      # Vue 3 SPA
│   ├── src/
│   └── vite.config.js
└── README.md
```

---

## ❗ Known Limitations

- No email verification or password reset
- No calendar/scheduling conflict checks
- Real-time updates are optional and may require config tweaks

---

## 📄 License

This project is open for demonstration and assessment purposes.

---

## 🙌 Author

Built by **Cyrus Muchiri** for a technical assessment.
