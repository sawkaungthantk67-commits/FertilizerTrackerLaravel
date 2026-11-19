# วิธีรันโปรเจกต์และสาเหตุที่เปิดเว็บไม่ได้

## สาเหตุที่พบ

1. `.env` ใช้ MySQL ฐานข้อมูล `center` และ `SESSION_DRIVER=database` แต่ยังไม่ได้รัน migrations จึงไม่มีตาราง `sessions` เมื่อ Laravel เริ่ม session ก่อนแสดงหน้าเว็บจะเกิด HTTP 500 พร้อมข้อความ `Table 'center.sessions' doesn't exist` โดย cache และ queue ก็ถูกตั้งให้ใช้ฐานข้อมูลเช่นกัน
2. หน้า Users เรียก `route('admin.users.create')` แต่ `routes/web.php` ยังไม่ได้ประกาศชื่อนี้ จึงเกิด `Route [admin.users.create] not defined` เมื่อแสดงหน้า
3. ปุ่ม Add user บน Dashboard เดิมใช้ `href="#"` จึงยังไปหน้าเพิ่มผู้ใช้ไม่ได้

## สิ่งที่แก้ไขแล้ว

- รัน `php artisan migrate --no-interaction` สร้างตารางจาก migrations ทั้ง 3 รายการในฐานข้อมูล `center` โดยไม่ได้ลบฐานข้อมูลหรือใช้ `migrate:fresh`
- เพิ่ม GET `/admin/users/create` ชื่อ `admin.users.create` เพื่อแสดง view `backend.add-user`
- เชื่อมปุ่ม Add user บน Dashboard ไปยัง route นี้
- ล้าง cache ด้วย `php artisan optimize:clear`
- เพิ่มการทดสอบการแสดงหน้าและลิงก์เพิ่มผู้ใช้ใน `tests/Feature/PageRoutesTest.php`

## ขั้นตอนรันบนเครื่องนี้

1. เปิด MySQL ใน XAMPP และตรวจสอบว่าค่า `DB_*` ใน `.env` ตรงกับฐานข้อมูลที่ต้องการใช้งาน ปัจจุบันใช้ `center`
2. เปิด PowerShell ในโฟลเดอร์โปรเจกต์ แล้วใช้:

   ```powershell
   cd C:\xampp\htdocs\sample_130
   php artisan migrate
   php artisan optimize:clear
   php artisan serve --host=127.0.0.1 --port=8000
   ```

3. เปิด <http://127.0.0.1:8000> หรือหน้า Dashboard ที่ <http://127.0.0.1:8000/admin/dashboards> เก็บ terminal ที่รัน server ไว้ และกด Ctrl+C เมื่อต้องการหยุด

โปรเจกต์มี `vendor` และ `.env` อยู่แล้ว สำหรับเครื่องใหม่ต้องติดตั้ง PHP >= 8.2 และ Composer จากนั้นใช้ `composer install` คัดลอก `.env.example` เป็น `.env` และรัน `php artisan key:generate` ก่อนตั้งค่าฐานข้อมูลและ migrate อย่ารัน key:generate ซ้ำบนระบบที่ใช้งานอยู่โดยไม่จำเป็น

หน้าเว็บปัจจุบันใช้ styles/scripts จาก Blade โดยตรง จึงเปิดผ่าน `php artisan serve` ได้โดยไม่ต้องเปิด Vite หากต้องพัฒนา assets ใน `resources/css` หรือ `resources/js` ให้ติดตั้ง Node.js แล้วใช้ `npm.cmd install` และ `npm.cmd run dev` บน PowerShell เครื่องนี้คำสั่ง `npm` ถูก execution policy บล็อกไฟล์ `npm.ps1` จึงใช้ `npm.cmd` ได้โดยไม่เปลี่ยน policy

คำสั่ง `composer dev` เดิมเรียก Laravel Pail ซึ่งต้องใช้ extension `pcntl` ที่ PHP บน Windows ไม่มี ให้ใช้ `php artisan serve` ตามขั้นตอนข้างต้น และอ่าน log ได้ด้วย `Get-Content storage\logs\laravel.log -Tail 50 -Wait`

## ผลตรวจสอบหลังแก้

- `php artisan migrate:status`: migrations ทั้ง 3 รายการมีสถานะ Ran
- `php artisan test`: ผ่าน 11 tests, 13 assertions
- ตรวจ HTTP จริงโดยใช้ MySQL session: `/`, `/register`, `/admin/dashboards`, `/admin/users`, `/admin/users/create`, `/admin/students`, `/admin/courses`, `/admin/schedules` ตอบกลับ 200 ทั้งหมด

ขอบเขตนี้แก้การเปิดและแสดงหน้าเว็บ ฟังก์ชันล็อกอินใน `LoginController` ปัจจุบันยังตรวจเพียงข้อมูลที่ส่งมา ส่วนสมัครสมาชิกตรวจข้อมูลโดยยังไม่บันทึก และฟอร์มเพิ่มผู้ใช้ยังเป็น demo จึงยังไม่ใช่ระบบจัดการบัญชีที่สมบูรณ์
