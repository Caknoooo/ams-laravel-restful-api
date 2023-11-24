# Laravel RESTful API
Repository ini dibuat untuk mengimplementasikan framework ``laravel`` dengan membuat API sederhana.

## Daftar Isi
- [Setup Clone](#setup-clone)
- [Setup From Zero](#setup-from-zero)

### Setup Clone
Pertama lakukan perintah berikut untuk melakukan cloning di github 
```
git clone https://github.com/Caknoooo/ams-laravel-restful-api
```

Jangan lupa untuk menjalankan perintah berikut untuk mendownload dependencies-dependencies yang dibutuhkan
```
composer install
```

Setelah itu lakukan beberapa konfigurasi sebagai berikut
```
cp .env.example .env

SETUP SESUAI DENGAN DATABASE MYSQL pada file .env

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel-api
DB_USERNAME=root
DB_PASSWORD=
```

Lalu nyalakan ``XAMPP`` dan jalankan berikut 

![image](https://github.com/Caknoooo/ams-laravel-restful-api/assets/92671053/0098acc5-0603-4b62-956d-dcb3d0570291)


Setelah itu jalankan perintah berikut 
```
php artisan migrate
php artisan key:generate
php artisan config:clear
php artisan config:cache
php artisan serve
```

Hasilnya adalah sebagai berikut 

![image](https://github.com/Caknoooo/ams-laravel-restful-api/assets/92671053/c2574f9b-7d00-4476-9ced-3cdb32f8642b)

Berikut merupakan postman yang bisa dipakai 

https://documenter.getpostman.com/view/29665461/2s9YeD6s4M

### Setup From Zero

Untuk memulai dari awal anda perlu untuk menjalankan perintah berikut 

```
composer create-project laravel/laravel laravel-api
```

Setelah itu jangan lupa untuk membuka directory ``laravel-api`` di ``text editor``

Setelah itu setup database nya di file ``.env`` dan menjalankan ``XAMPP``

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel-api
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan perintah berikut di terminal 
```
php artisan make:migration create_students_table
php artisan make:model Student
php artisan make:controller Api\StudentController
```

Lalu buka file ``create_students_table`` tadi ke pada folder ``database > migrations > (cari file yang mengandung create_students_table)`` 

Lalu ganti ``public function up`` dengan berikut 
```php
public function up(): void
{
    Schema::create('students', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('course');
        $table->string('email');
        $table->string('phone');
        $table->timestamps();
    });
}
```

Setelah itu buka file ``Student.php`` yang dibuat ketika menjalakan ``php artisan make:model Student`` tadi. Pada folder ``app > Models > Student.php```

Lalu ganti sebagai berikut 
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = "students";

    protected $fillable = [
        'name',
        'course',
        'email',
        'phone',
    ];
}
```

Setelah itu buka file controller yang telah dibuat sebelumnya pada folder ``app > Http > Controllers > Api > StudentController``

Lalu ganti sebagai berikut 
```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    public function index() {
        $student = Student::all();
        if ($student->count() > 0) {
            return response()->json([
                'success' => true,
                'message' => 'List Data Student',
                'data' => $student
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Student Not Found',
                'data' => null
            ], 404);
        }
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'course' => 'required|string|max:191',
            'email' => 'required|string|max:191',
            'phone' => 'required|digits_between:10,12',
        ]);

        if($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages(),
                'data' => $validator->errors()
            ], 400);
        } else {
            $student = Student::create([
                'name' => $request->input('name'),
                'course' => $request->input('course'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
            ]);

            if ($student) {
                return response()->json([
                    'success' => true,
                    'message' => 'Create Student Success',
                    'data' => $student
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Create Student Failed',
                    'data' => null
                ], 400);
            }
        }
    }

    public function show($id) {
        $student = Student::find($id);
        if ($student) {
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Student',
                'data' => $student
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Student Not Found',
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id) {
        $student = Student::find($id);
        if ($student) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:191',
                'course' => 'required|string|max:191',
                'email' => 'required|string|max:191',   
                'phone' => 'required|digits_between:10,12',
            ]);

            if($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->messages(),
                    'data' => $validator->errors()
                ], 400);
            } else {
                $student->update([
                    'name' => $request->input('name'),
                    'course' => $request->input('course'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                ]);

                if ($student) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Update Student Success',
                        'data' => $student
                    ], 201);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Update Student Failed',
                        'data' => null
                    ], 400);
                }
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Student Not Found',
                'data' => null
            ], 404);
        }
    }

    public function destroy($id) {
        $student = Student::find($id);
        if ($student) {
            $student->delete();
            return response()->json([
                'success' => true,
                'message' => 'Delete Student Success',
                'data' => $student
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Delete Student Failed',
                'data' => null
            ], 400);
        }
    }
}
```

Terkhir buka file ``api.php`` yang ada pada folder ``routes``. Lalu ganti kode Berikut

```php
<?php

use App\Http\Controllers\Api\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('students', [StudentController::class, 'index']);
Route::post('students', [StudentController::class, 'store']);
Route::get('students/{id}', [StudentController::class, 'show']);
Route::put('students/{id}', [StudentController::class, 'update']);
Route::delete('students/{id}', [StudentController::class, 'destroy']);
```

Setelah itu jalankan perintah berikut 
```
php artisan migrate
php artisan key:generate
php artisan config:clear
php artisan config:cache
php artisan serve
```

Hasilnya adalah sebagai berikut 

![image](https://github.com/Caknoooo/ams-laravel-restful-api/assets/92671053/c2574f9b-7d00-4476-9ced-3cdb32f8642b)

Berikut merupakan postman yang bisa dipakai 

https://documenter.getpostman.com/view/29665461/2s9YeD6s4M
