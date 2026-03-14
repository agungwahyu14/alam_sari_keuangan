# 📧 PANDUAN KONFIGURASI EMAIL PROVIDERS

## 🎯 PILIHAN EMAIL PROVIDER

### 1. Mailtrap (RECOMMENDED untuk Development)

**✅ Kelebihan:**

-   Gratis untuk testing
-   Tidak mengirim email real (safe untuk testing)
-   UI bagus untuk preview email
-   Mudah setup

**Setup:**

1. Daftar di [mailtrap.io](https://mailtrap.io/)
2. Buat inbox baru
3. Copy credentials dari "SMTP Settings"
4. Paste ke `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=abc123def456  # Dari Mailtrap
MAIL_PASSWORD=xyz789ghi012  # Dari Mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@mancraft.com"
MAIL_FROM_NAME="Mancraft Finance"
```

---

### 2. Gmail (Untuk Testing dengan Email Real)

**⚠️ Catatan:**

-   TIDAK untuk production (ada limit 500 email/day)
-   Harus enable 2FA
-   Harus generate App Password

**Setup:**

#### Step 1: Enable 2-Factor Authentication

1. Buka [Google Account](https://myaccount.google.com/)
2. Klik **Security** di sidebar
3. Cari **2-Step Verification**
4. Klik **Get Started** dan ikuti instruksi

#### Step 2: Generate App Password

1. Setelah 2FA aktif, kembali ke **Security**
2. Cari **App passwords** (di bawah 2-Step Verification)
3. Klik **Select app** → pilih **Mail**
4. Klik **Select device** → pilih **Other** → ketik "Laravel"
5. Klik **Generate**
6. Copy 16-digit password (contoh: `abcd efgh ijkl mnop`)

#### Step 3: Configure .env

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=abcdefghijklmnop  # 16 digit tanpa spasi
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@mancraft.com"
MAIL_FROM_NAME="Mancraft Finance"
```

**Troubleshooting Gmail:**

-   ❌ Error "Username and Password not accepted"
    → Pastikan gunakan App Password, bukan password Gmail biasa
-   ❌ Error "Connection timeout"
    → Cek firewall/antivirus
    → Coba MAIL_PORT=465 dengan MAIL_ENCRYPTION=ssl

---

### 3. SendGrid (RECOMMENDED untuk Production)

**✅ Kelebihan:**

-   Free tier: 100 emails/day (cukup untuk reset password)
-   Reliable & fast
-   Good deliverability
-   API atau SMTP

**Setup:**

#### Step 1: Daftar SendGrid

1. Buka [sendgrid.com](https://sendgrid.com/)
2. Sign up (gratis)
3. Verify email

#### Step 2: Create API Key

1. Dashboard → Settings → API Keys
2. Create API Key
3. Name: "Laravel App"
4. Permissions: Full Access
5. Copy API Key (hanya muncul sekali!)

#### Step 3: Verify Domain (Optional tapi Recommended)

1. Settings → Sender Authentication
2. Authenticate Your Domain
3. Ikuti instruksi DNS setup

#### Step 4: Configure .env

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.xxxx-xxxxx-xxxxxxxx  # API Key dari step 2
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"  # Gunakan domain terverifikasi
MAIL_FROM_NAME="Mancraft Finance"
```

**Tips SendGrid:**

-   Gunakan domain sendiri untuk MAIL_FROM_ADDRESS
-   Verify domain untuk deliverability lebih baik
-   Monitor email statistics di dashboard

---

### 4. Mailgun (Alternatif Production)

**✅ Kelebihan:**

-   Free tier: 5,000 emails/month (3 bulan pertama)
-   Good for transactional emails
-   Detailed logs & analytics

**Setup:**

#### Step 1: Daftar Mailgun

1. Buka [mailgun.com](https://mailgun.com/)
2. Sign up (butuh credit card tapi tidak dicharge)
3. Verify email

#### Step 2: Get Credentials

1. Dashboard → Sending → Domain settings
2. Copy "SMTP credentials"

#### Step 3: Configure .env

**Opsi A: Menggunakan SMTP**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@sandbox-xxxxx.mailgun.org
MAIL_PASSWORD=your-mailgun-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="Mancraft Finance"
```

**Opsi B: Menggunakan Mailgun Driver (Lebih Baik)**

```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=sandbox-xxxxx.mailgun.org
MAILGUN_SECRET=key-xxxxxxxxxxxxxxxx
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="Mancraft Finance"
```

Jika pakai driver Mailgun, install package:

```bash
composer require symfony/mailgun-mailer symfony/http-client
```

---

### 5. Amazon SES (Advanced Production)

**✅ Kelebihan:**

-   Sangat murah ($0.10 per 1,000 emails)
-   Highly scalable
-   Integrated dengan AWS

**⚠️ Kekurangan:**

-   Setup lebih kompleks
-   Butuh AWS account
-   Initially dalam sandbox mode (max 200 email/day)

**Quick Setup:**

```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="Mancraft Finance"
```

Install package:

```bash
composer require aws/aws-sdk-php
```

---

## 🔧 TESTING EMAIL CONFIGURATION

### Test 1: Via Tinker

```bash
php artisan tinker

# Test basic email
> use Illuminate\Support\Facades\Mail;
> Mail::raw('Test email dari Laravel', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});
# Output: null = success
```

### Test 2: Test Password Reset

```bash
php artisan tinker

> use Illuminate\Support\Facades\Password;
> use App\Models\User;

# Get user
> $user = User::first();

# Send reset link
> Password::sendResetLink(['email' => $user->email]);
# Output: "passwords.sent" = success
```

### Test 3: Check Config

```bash
php artisan tinker

> config('mail.mailers.smtp')
# Harus return array dengan config Anda

> config('mail.from')
# Harus return ['address' => '...', 'name' => '...']
```

---

## 🐛 COMMON ERRORS & SOLUTIONS

### Error: "Failed to authenticate on SMTP server"

**Penyebab:**

-   Username/password salah
-   Gmail tanpa App Password

**Solusi:**

```bash
# 1. Verify credentials di .env
# 2. Clear config cache
php artisan config:clear

# 3. Test connection
php artisan tinker
> config('mail.mailers.smtp.username')
> config('mail.mailers.smtp.password')
```

### Error: "Connection could not be established with host"

**Penyebab:**

-   Port/host salah
-   Firewall blocking
-   Encryption mismatch

**Solusi:**

```env
# Coba kombinasi ini:

# Untuk Gmail
MAIL_PORT=587
MAIL_ENCRYPTION=tls

# Atau
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

### Error: "Address in mailbox given does not comply"

**Penyebab:**

-   MAIL_FROM_ADDRESS format salah

**Solusi:**

```env
# Harus valid email format
MAIL_FROM_ADDRESS="noreply@mancraft.com"  # ✅ Good
MAIL_FROM_ADDRESS="Mancraft"               # ❌ Bad
```

### Email masuk spam

**Solusi:**

1. Verify domain di email provider
2. Setup SPF, DKIM, DMARC records
3. Gunakan professional from address
4. Test dengan [mail-tester.com](https://www.mail-tester.com/)

---

## 📊 COMPARISON TABLE

| Provider       | Free Tier         | Best For      | Difficulty    | Speed             |
| -------------- | ----------------- | ------------- | ------------- | ----------------- |
| **Mailtrap**   | ∞ (testing only)  | Development   | ⭐ Easy       | ⚡ Fast           |
| **Gmail**      | 500/day           | Quick testing | ⭐⭐ Medium   | ⚡ Fast           |
| **SendGrid**   | 100/day           | Production    | ⭐⭐ Medium   | ⚡⚡ Very Fast    |
| **Mailgun**    | 5000/month (3mo)  | Production    | ⭐⭐⭐ Medium | ⚡⚡ Very Fast    |
| **Amazon SES** | 200/day (sandbox) | Large scale   | ⭐⭐⭐⭐ Hard | ⚡⚡⚡ Ultra Fast |

---

## 🎯 RECOMMENDATION

### Development/Testing

```
1. Mailtrap ⭐⭐⭐⭐⭐ (Best choice!)
2. Gmail (jika perlu test email real)
```

### Small Production App

```
1. SendGrid ⭐⭐⭐⭐⭐
2. Mailgun ⭐⭐⭐⭐
```

### Large Scale Production

```
1. Amazon SES ⭐⭐⭐⭐⭐
2. SendGrid ⭐⭐⭐⭐
```

---

## 🚀 QUICK START UNTUK PROJECT INI

### Langkah Cepat (5 menit):

1. **Daftar Mailtrap** → [mailtrap.io](https://mailtrap.io/)
2. **Copy credentials** dari inbox
3. **Update .env**:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@mancraft.com"
MAIL_FROM_NAME="Mancraft Finance"
```

4. **Clear cache**: `php artisan config:clear`
5. **Test**: Buka `/forgot-password`

---

## 📞 NEED HELP?

**Check Logs:**

```bash
tail -f storage/logs/laravel.log
```

**Debug Mode:**

```env
# Tambah di .env untuk debug email
LOG_CHANNEL=stack
LOG_LEVEL=debug
```

**Test Email Script:**

```php
// routes/web.php (sementara untuk testing)
Route::get('/test-email', function() {
    Mail::raw('Test dari Mancraft Finance', function($msg) {
        $msg->to('your-email@example.com')
            ->subject('Test Email Configuration');
    });
    return 'Email sent! Check inbox.';
});
```

---

✅ **Setup email sekarang dan mulai testing!**
