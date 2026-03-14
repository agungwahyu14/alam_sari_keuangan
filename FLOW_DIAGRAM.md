# 🔄 FLOW DIAGRAM - Reset Password Feature

```
┌─────────────────────────────────────────────────────────────────────┐
│                     FORGOT PASSWORD FLOW                             │
└─────────────────────────────────────────────────────────────────────┘

1. USER ACTION
   ┌──────────────┐
   │ User clicks  │
   │"Lupa Password│  → di halaman login
   │   di login"  │
   └──────┬───────┘
          │
          ▼
   ┌──────────────────────────────┐
   │  GET /forgot-password        │
   │  ForgotPasswordController::  │
   │  showLinkRequestForm()       │
   └──────────┬───────────────────┘
          │
          ▼
   ┌──────────────────────────────┐
   │  Show Form Input Email       │
   │  forgot-password.blade.php   │
   └──────────┬───────────────────┘
          │
          │ User input email & submit
          ▼

2. EMAIL SUBMISSION
   ┌──────────────────────────────┐
   │  POST /forgot-password       │
   │  ForgotPasswordController::  │
   │  sendResetLinkEmail()        │
   └──────────┬───────────────────┘
          │
          │ Validate email
          ▼
   ┌──────────────────────────────┐
   │  Laravel Password Facade     │
   │  Password::sendResetLink()   │
   └──────────┬───────────────────┘
          │
          ├─── ❌ Email tidak valid
          │    └─→ Error: "Email tidak terdaftar"
          │
          └─── ✅ Email valid
               │
               ▼
   ┌──────────────────────────────┐
   │  Generate Token              │
   │  - Random 60 char string     │
   │  - Hash dengan bcrypt        │
   └──────────┬───────────────────┘
               │
               ▼
   ┌──────────────────────────────┐
   │  Save to Database            │
   │  password_reset_tokens       │
   │  - email (primary key)       │
   │  - token (hashed)            │
   │  - created_at                │
   └──────────┬───────────────────┘
               │
               ▼
   ┌──────────────────────────────┐
   │  Send Email via              │
   │  ResetPasswordNotification   │
   └──────────┬───────────────────┘
               │
               ├─→ SMTP Server (Gmail/Mailtrap/SendGrid)
               │
               └─→ User's Email Inbox
                   │
                   ▼
   ┌──────────────────────────────┐
   │  ✉️  Email Received          │
   │  Subject: Reset Password     │
   │  Body: Link to reset         │
   │  Link: /reset-password/{token}?email=xxx
   └──────────┬───────────────────┘
               │
               │ User clicks link
               ▼

3. RESET PASSWORD
   ┌──────────────────────────────┐
   │  GET /reset-password/{token} │
   │  ResetPasswordController::   │
   │  showResetForm()             │
   └──────────┬───────────────────┘
               │
               ▼
   ┌──────────────────────────────┐
   │  Show Form Reset Password    │
   │  reset-password.blade.php    │
   │  - Email (readonly)          │
   │  - Password baru             │
   │  - Konfirmasi password       │
   └──────────┬───────────────────┘
               │
               │ User input password & submit
               ▼
   ┌──────────────────────────────┐
   │  POST /reset-password        │
   │  ResetPasswordController::   │
   │  reset()                     │
   └──────────┬───────────────────┘
               │
               │ Validate inputs
               ▼
   ┌──────────────────────────────┐
   │  Laravel Password Facade     │
   │  Password::reset()           │
   └──────────┬───────────────────┘
               │
               ├─── ❌ Token invalid/expired
               │    └─→ Error: "Token tidak valid"
               │
               ├─── ❌ Email tidak match
               │    └─→ Error: "Email tidak cocok"
               │
               └─── ✅ Valid
                    │
                    ▼
   ┌──────────────────────────────┐
   │  Update User Password        │
   │  - Hash password dengan bcrypt
   │  - Update remember_token     │
   │  - Save to database          │
   └──────────┬───────────────────┘
               │
               ▼
   ┌──────────────────────────────┐
   │  Delete Token from DB        │
   │  (cleanup password_reset_tokens)
   └──────────┬───────────────────┘
               │
               ▼
   ┌──────────────────────────────┐
   │  Redirect to /login          │
   │  with success message        │
   └──────────┬───────────────────┘
               │
               ▼
   ┌──────────────────────────────┐
   │  ✅ SUCCESS!                 │
   │  User can login with         │
   │  new password                │
   └──────────────────────────────┘


┌─────────────────────────────────────────────────────────────────────┐
│                     DATABASE INTERACTION                             │
└─────────────────────────────────────────────────────────────────────┘

Tables involved:
┌─────────────────────┐         ┌─────────────────────┐
│       users         │         │password_reset_tokens│
├─────────────────────┤         ├─────────────────────┤
│ id                  │         │ email (PK)          │
│ name                │         │ token (hashed)      │
│ email (unique)      │◄────────│ created_at          │
│ password (hashed)   │         └─────────────────────┘
│ remember_token      │              │
│ created_at          │              │
│ updated_at          │              ▼
└─────────────────────┘         Auto-deleted after:
                                - Successful reset
                                - 60 minutes (expired)


┌─────────────────────────────────────────────────────────────────────┐
│                     FILE STRUCTURE                                   │
└─────────────────────────────────────────────────────────────────────┘

app/
├── Http/Controllers/Auth/
│   ├── ForgotPasswordController.php ──── Handle forgot password
│   └── ResetPasswordController.php ───── Handle reset password
├── Models/
│   └── User.php ─────────────────────── sendPasswordResetNotification()
└── Notifications/
    └── ResetPasswordNotification.php ── Custom email template

resources/views/auth/
├── forgot-password.blade.php ────────── Form input email
└── reset-password.blade.php ─────────── Form reset password

routes/
└── auth.php ─────────────────────────── All password routes


┌─────────────────────────────────────────────────────────────────────┐
│                     SECURITY MEASURES                                │
└─────────────────────────────────────────────────────────────────────┘

✅ Token Security:
   - Token di-hash dengan bcrypt
   - Token tidak bisa dibaca dari database
   - One-time use (deleted after use)

✅ Expiration:
   - Token expired setelah 60 menit
   - Auto-cleanup token lama

✅ Validation:
   - Email must exist in database
   - Password minimal 8 karakter
   - Password confirmation required
   - CSRF token protection

✅ Rate Limiting:
   - Laravel throttle middleware
   - Prevent brute force

✅ Safe Password Reset:
   - Old password otomatis invalid
   - Remember token di-regenerate
   - Secure password hashing


┌─────────────────────────────────────────────────────────────────────┐
│                     ERROR SCENARIOS                                  │
└─────────────────────────────────────────────────────────────────────┘

┌─────────────────────────┐
│ Forgot Password Errors  │
└─────────────────────────┘
❌ Email format invalid
   → "Format email tidak valid"

❌ Email tidak terdaftar
   → "Email tidak terdaftar dalam sistem"

❌ Email field kosong
   → "Email wajib diisi"

❌ Email gagal terkirim
   → Check SMTP config / logs

┌─────────────────────────┐
│ Reset Password Errors   │
└─────────────────────────┘
❌ Token invalid/expired
   → "Token tidak valid atau sudah kedaluwarsa"

❌ Email tidak match
   → "Email tidak cocok"

❌ Password < 8 karakter
   → "Password minimal 8 karakter"

❌ Password tidak match
   → "Konfirmasi password tidak cocok"

❌ Password field kosong
   → "Password wajib diisi"


┌─────────────────────────────────────────────────────────────────────┐
│                     TESTING FLOW                                     │
└─────────────────────────────────────────────────────────────────────┘

Developer Testing:
1. Setup Mailtrap
2. Configure .env
3. Clear cache
4. Access /forgot-password
5. Input test email
6. Check Mailtrap inbox
7. Click link
8. Reset password
9. Login with new password
   ✅ SUCCESS!

User Experience:
1. Lupa password
2. Click "Lupa password?" di login
3. Input email
4. Cek email
5. Click link di email
6. Input password baru (2x)
7. Login dengan password baru
   ✅ Done!


┌─────────────────────────────────────────────────────────────────────┐
│                     CUSTOMIZATION POINTS                             │
└─────────────────────────────────────────────────────────────────────┘

📧 Email Template:
   File: app/Notifications/ResetPasswordNotification.php
   - Change subject
   - Modify greeting
   - Edit button text
   - Add custom content

⏱️ Token Expiry:
   File: config/auth.php
   'expire' => 60,  // Change to desired minutes

🎨 UI/UX:
   Files: resources/views/auth/forgot-password.blade.php
          resources/views/auth/reset-password.blade.php
   - Modify colors
   - Change layout
   - Add custom branding

🔒 Password Rules:
   File: app/Http/Controllers/Auth/ResetPasswordController.php
   Rules\Password::defaults()
   - Change min length
   - Add complexity rules
   - Custom validation


┌─────────────────────────────────────────────────────────────────────┐
│                     MONITORING & LOGS                                │
└─────────────────────────────────────────────────────────────────────┘

📊 What to Monitor:
- Email delivery success rate
- Token usage (how many actually reset)
- Failed attempts (brute force detection)
- Expiry rate (tokens not used)

📝 Log Files:
- storage/logs/laravel.log
  → All errors and exceptions

- Mail logs (if configured)
  → Email sending status

💡 Debug Commands:
php artisan tinker
> DB::table('password_reset_tokens')->get()
> User::where('email', 'xxx')->first()
> config('mail')


═══════════════════════════════════════════════════════════════════════
                           END OF FLOW DIAGRAM
═══════════════════════════════════════════════════════════════════════
```
