# Panduan Integrasi SiPintu Gateway - SIPBAR

## Ringkasan Implementasi

Integrasi SiPintu Identity & API Gateway ke SIPBAR menggunakan metode **Server-to-Server Gateway (Header Auth)** untuk pengambilan data siswa dan guru dari SIJUNA.

## 1. Struktur Service/Class

### SipintuService (`app/Services/SipintuService.php`)

Service ini membungkus semua komunikasi dengan SiPintu Gateway:

**Metode Utama:**
- `ping()` - Verifikasi koneksi ke SiPintu Gateway
- `validateClient()` - Validasi kredensial client
- `getStudents(?string $nis = null, ?string $search = null, bool $forceRefresh = false)` - Ambil data siswa dengan caching
- `getTeachers(?string $nip = null, ?string $search = null, bool $forceRefresh = false)` - Ambil data guru dengan caching
- `getAuthorizationUrl(string $state)` - Build URL untuk OAuth SSO
- `exchangeCodeForToken(string $code)` - Tukar authorization code dengan access token
- `getUserProfile(string $accessToken)` - Ambil profil user via Bearer token

**Environment Variables (`.env`):**
```env
SIPINTU_API_URL=http://localhost:8000
SIPINTU_CLIENT_ID=app_yk3qeq4twl7z
SIPINTU_CLIENT_SECRET=sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE
SIPINTU_REDIRECT_URI=http://localhost:8001/oauth/callback
```

## 2. Controller & Routes

### SipintuStatusController (`app/Http/Controllers/SipintuStatusController.php`)

Controller ini menyediakan endpoint internal untuk mengakses data SiPintu:

**Metode:**
- `status()` - GET `/api/internal/sipintu/status` - Cek koneksi SiPintu
- `validate()` - POST `/api/internal/sipintu/validate` - Validasi kredensial
- `students()` - GET `/api/internal/sipintu/students` - Ambil data siswa
- `teachers()` - GET `/api/internal/sipintu/teachers` - Ambil data guru

**Routes (`routes/web.php`):**
```php
Route::prefix('api/internal/sipintu')->name('sipintu.')->group(function () {
    Route::get('status',   [SipintuStatusController::class, 'status'])->name('status');
    Route::post('validate', [SipintuStatusController::class, 'validate'])->name('validate');
    Route::get('students', [SipintuStatusController::class, 'students'])->name('students');
    Route::get('teachers', [SipintuStatusController::class, 'teachers'])->name('teachers');
});
```

## 3. Error Handling Strategy

**Layer 1: Connection Exception Handling**
- Menangani koneksi timeout atau server SiPintu offline
- Mengembalikan pesan error yang user-friendly: "Tidak dapat terhubung ke SiPintu — server mungkin offline."

**Layer 2: HTTP Response Validation**
- Menangani response non-200 dari SiPintu (404, 401, 500, dll)
- Mengembalikan status code dan pesan error dari response SiPintu

**Layer 3: Exception Logging**
- Semua error unexpected di-log ke Laravel Log
- Tidak menampilkan error mentah ke user

**Response Format:**
```php
[
    'success' => bool,      // true jika berhasil, false jika gagal
    'data'    => array,     // data hasil query (array kosong jika gagal)
    'total'   => int,       // jumlah data
    'error'   => string|null, // pesan error jika ada
    'cached'  => bool,      // true jika dari cache, false jika fresh dari API
]
```

## 4. Caching Strategy

**Cache Duration:** 10 menit untuk data siswa dan guru

**Cache Key Generation:**
- Format: `sipintu:students:{md5(params)}` dan `sipintu:teachers:{md5(params)}`
- Key unik berdasarkan parameter query (nis, search, dll)

**Cache Behavior:**
- Default: Mengembalikan data dari cache jika tersedia
- Force Refresh: Set `force_refresh=true` untuk skip cache dan ambil data fresh dari API

**Example Usage:**
```php
// Dengan cache (default)
$result = $service->getStudents(nis: '1234567890');

// Force refresh (skip cache)
$result = $service->getStudents(nis: '1234567890', forceRefresh: true);
```

## 5. curl.exe Examples untuk Testing Manual

### Test Ping (Cek Koneksi)
```powershell
curl.exe -s "http://localhost:8000/api/v1/ping?client_id=app_yk3qeq4twl7z" -H "Accept: application/json"
```

### Test Validate Client
```powershell
curl.exe -s -X POST "http://localhost:8000/api/v1/validate-client" `
  -H "Content-Type: application/json" `
  -H "Accept: application/json" `
  -d '{"client_id": "app_yk3qeq4twl7z", "client_secret": "sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE"}'
```

### Test Get Students (Semua Data)
```powershell
curl.exe -s "http://localhost:8000/api/v1/sijuna/students" `
  -H "X-Client-ID: app_yk3qeq4twl7z" `
  -H "X-Client-Secret: sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE" `
  -H "Accept: application/json"
```

### Test Get Students (Filter by NIS)
```powershell
curl.exe -s "http://localhost:8000/api/v1/sijuna/students?nis=1234567890" `
  -H "X-Client-ID: app_yk3qeq4twl7z" `
  -H "X-Client-Secret: sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE" `
  -H "Accept: application/json"
```

### Test Get Students (Search by Nama)
```powershell
curl.exe -s "http://localhost:8000/api/v1/sijuna/students?search=ahmad" `
  -H "X-Client-ID: app_yk3qeq4twl7z" `
  -H "X-Client-Secret: sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE" `
  -H "Accept: application/json"
```

### Test Get Teachers (Semua Data)
```powershell
curl.exe -s "http://localhost:8000/api/v1/sijuna/teachers" `
  -H "X-Client-ID: app_yk3qeq4twl7z" `
  -H "X-Client-Secret: sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE" `
  -H "Accept: application/json"
```

### Test Get Teachers (Filter by NIP)
```powershell
curl.exe -s "http://localhost:8000/api/v1/sijuna/teachers?nip=198501012010011001" `
  -H "X-Client-ID: app_yk3qeq4twl7z" `
  -H "X-Client-Secret: sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE" `
  -H "Accept: application/json"
```

### Test Get Teachers (Search by Nama)
```powershell
curl.exe -s "http://localhost:8000/api/v1/sijuna/teachers?search=budi" `
  -H "X-Client-ID: app_yk3qeq4twl7z" `
  -H "X-Client-Secret: sec_oTpMf5DU3hOanGDXUHgbddXwJ1L4V9dE" `
  -H "Accept: application/json"
```

## 6. Penggunaan di Controller

### Contoh: Menampilkan Data Siswa di View
```php
use App\Services\SipintuService;

class StudentController extends Controller
{
    public function index(SipintuService $sipintu)
    {
        $result = $sipintu->getStudents();

        if (!$result['success']) {
            return view('students.index', [
                'students' => [],
                'error' => $result['error'],
            ]);
        }

        return view('students.index', [
            'students' => $result['data'],
            'total' => $result['total'],
            'cached' => $result['cached'],
        ]);
    }
}
```

### Contoh: AJAX Call dari Frontend
```javascript
// Ambil data siswa
fetch('/api/internal/sipintu/students')
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            console.log('Total siswa:', data.total);
            console.log('Data:', data.data);
            console.log('Dari cache:', data.cached);
        } else {
            console.error('Error:', data.error);
        }
    });

// Force refresh data
fetch('/api/internal/sipintu/students?force_refresh=1')
    .then(res => res.json())
    .then(data => { /* ... */ });

// Filter by NIS
fetch('/api/internal/sipintu/students?nis=1234567890')
    .then(res => res.json())
    .then(data => { /* ... */ });
```

## 7. Keamanan

- **Tidak ada exposure ke frontend:** Semua pemanggilan ke SiPintu terjadi di backend
- **Credentials dari .env:** Client ID dan Secret tidak di-hardcode
- **Middleware Auth:** Endpoint internal dilindungi oleh middleware auth
- **Header Auth:** Menggunakan X-Client-ID dan X-Client-Secret untuk autentikasi server-to-server
