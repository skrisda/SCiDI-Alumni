# สถานะโปรเจกต์ SciDI–TSU AA

อัปเดต: 1 พฤศจิกายน 2569 (2026-11-01) — รวมการลบ qualification และนำช่องอีเมลออกจากแบบสมัครแล้ว; เวลาอ้างอิง Asia/Bangkok

เอกสารนี้ใช้ส่งต่องานไปยังเครื่องอื่นหรือแชตใหม่ สรุปสถานะจากโค้ดและงานในบทสนทนาถึงวันที่ระบุ ไม่ใช่สำเนาประวัติแชตทั้งหมด และไม่บรรจุค่าลับจาก `.env` ไฟล์นี้อัปเดตตามคำขอ ไม่ได้ซิงก์กับโค้ดอัตโนมัติ

## เริ่มอ่านเมื่อรับช่วงงาน

1. อ่าน `AGENTS.md` ก่อนทำงาน และตรวจ `.ai/rules/index.md` หากมีโฟลเดอร์ดังกล่าว
2. อ่านเอกสารนี้ แล้วตรวจโค้ดจริง เนื่องจากอาจมีการแก้ไขภายหลัง
3. ตรวจ `git status --short` ก่อนแก้ไข มีงานจำนวนมากที่ยังไม่ได้ commit รวมทั้งไฟล์ใหม่ที่ยัง untracked อย่า reset หรือทับงานเหล่านี้
4. ยืนยันเวอร์ชันด้วย `composer show --direct` และ `package.json` ก่อนใช้ API ของแพ็กเกจ
5. งานถัดไปที่เสนอคือขั้นตอนรับรองคำขอ ตรวจการชำระเงิน และบันทึกมติรับสมาชิก ยังไม่ได้เริ่มพัฒนาส่วนนี้

## เป้าหมายและขอบเขต

เว็บไซต์สมาคมศิษย์เก่าคณะวิทยาศาสตร์และนวัตกรรมดิจิทัล มหาวิทยาลัยทักษิณ ชื่อย่อไทย สศ.วท.มทษ. และอังกฤษ SciDI–TSU AA

ขอบเขตที่ผู้ใช้ต้องการ:

- สมัครสมาชิกสมาคม ชำระค่าบำรุงด้วยการโอนและแนบสลิป
- ประชาสัมพันธ์ข่าวสาร
- รับบริจาค
- จัดกิจกรรม ลงทะเบียนเข้าร่วม และชำระค่ากิจกรรม
- อัปโหลดและเผยแพร่ภาพกิจกรรม
- จัดเก็บและเผยแพร่รายงานประชุม
- ระบบผู้ดูแล โดยหนึ่งบัญชีเป็นทั้งสมาชิกและผู้ดูแลได้ และถือหลายสิทธิ์พร้อมกันได้
- รูปโปรไฟล์เพิ่มภายหลังลงทะเบียนได้ ไม่บังคับตอนสมัคร (ยังไม่พัฒนา)

## เทคโนโลยี

เวอร์ชันที่ตรวจระหว่างทำงาน: PHP CLI 8.3.30, Laravel 13.39.0, Livewire 4.4.8, Flux 2.21.0, Fortify 1.41.0 และ Pest 4.7.8

- Frontend: Tailwind CSS 4, Vite Plus 1.4.0 ตาม `package.json`
- ฐานข้อมูลใช้งานเดิม: MySQL; ฐานข้อมูลทดสอบ: SQLite in-memory
- ใช้ Laravel/Livewire Starter Kit เป็นฐาน
- หน้า Settings ใช้ Livewire single-file components ใน `resources/views/pages/settings/⚡*.blade.php`
- ส่วนสมัครสมาชิกและ Admin ปัจจุบันใช้ controllers, Form Requests และ Blade ไม่ได้เป็น Livewire forms
- ยังไม่ได้เพิ่ม dependencies สำหรับงานที่พัฒนาในบทสนทนานี้

## การออกแบบที่ตกลงแล้ว

- Modern Corporate ใช้โลโก้จริง `public/images/logo.png`
- โทนฟ้า ขาว เหลืองทอง พร้อมสีเข้มในโหมดมืด
- ฟอนต์หลัก Mitr จาก Google Fonts
- แบ่งเว็บไซต์เป็นหลายหน้า แต่หน้าแรกแสดงข่าวและกิจกรรมด้วย
- ปุ่ม ก− / ก / ก+ ปรับขนาดข้อความและจำค่าผ่าน localStorage (`scidi-font-scale`)
- ค่าแสดงเริ่มต้นคือ 100% แต่ฐานขนาดข้อความขยายจากแบบแรก 10% ผ่าน CSS
- ขยายข้อความชื่อย่อใน brand ด้านบนแยกจากโลโก้
- หน้า Auth ภาษาไทยใช้ธีมเดียวกับหน้าสาธารณะ
- Dashboard, Profile, Security และ Appearance ใช้ workspace ร่วมกันและรองรับธีมผ่าน Flux
- เมนูซ้ายแยกกลุ่มบัญชีผู้ใช้กับกลุ่มผู้ดูแล กลุ่มผู้ดูแลตรวจ Gate ก่อนแสดง

ไฟล์ UI สำคัญ:

- `resources/views/layouts/public.blade.php`: head, header, เมนูสาธารณะ, footer และ appearance stack
- `resources/views/layouts/app.blade.php`: layout พื้นที่ผู้ใช้และ Settings พร้อม Flux appearance/scripts/toasts
- `resources/views/components/account-workspace.blade.php`: workspace และ sidebar ร่วมกัน
- `resources/views/layouts/auth.blade.php`: layout Auth; หน้าสร้างบัญชีมีโครงของตนเองผ่าน public layout
- `resources/views/dashboard.blade.php`: ภาพรวมบัญชีและทางลัด
- `resources/css/landing.css`: styles ของหน้าสาธารณะ บัญชี สมาชิก และ Admin มี override ต่อท้ายหลายช่วง
- `resources/js/app.js`: ตัวควบคุมขนาดฟอนต์ ช่องคำนำหน้าอื่น ๆ และการแสดงช่องตามประเภทย่อยสมาชิกสามัญ
- `resources/views/pages/settings/layout.blade.php`: รับ `showSidebar`; หน้าหลักทั้งสามส่ง `false` เพื่อไม่ให้ sidebar ซ้อน

ข้อผิดพลาดที่เคยแก้: อย่าใช้ `request()->routeIs('profile.edit')` ภายในฟอร์มเพื่อเลือกว่าแสดง sidebar หรือไม่ เพราะหลัง Livewire update route จะเปลี่ยนเป็น update endpoint ให้ใช้ prop ที่ชัดเจนแทน

## เส้นทางหลัก

| URL | สถานะ |
| --- | --- |
| `/` | หน้าแรก พร้อมส่วนข่าวและกิจกรรม |
| `/about`, `/news`, `/events`, `/gallery` | หน้านำเสนอ ยังไม่มีระบบจัดการเนื้อหาจริง |
| `/membership` | ประเภทสมาชิกและลิงก์สมัคร |
| `/support` | หน้ารับบริจาคเบื้องต้น ยังไม่รับรายการจริง |
| `/documents` | ดาวน์โหลดข้อบังคับ และพื้นที่รายงานประชุม |
| `/register`, `/login`, หน้ากู้รหัสผ่าน/ยืนยันอีเมล | ใช้ระบบ Fortify เดิม ปรับหน้าตาแล้ว |
| `/dashboard`, `/settings/*` | พื้นที่ผู้ใช้/ตั้งค่าบัญชี |
| `/membership/apply` | GET แบบสมัคร/สถานะคำขอ; POST ส่งคำขอ |
| `/membership/slip` | POST แนบสลิป; GET ดาวน์โหลดสลิปของตนเอง |
| `/admin` | ภาพรวมผู้ดูแล |
| `/admin/memberships` | รายการ ค้นหา กรอง และแบ่งหน้าคำขอ |
| `/admin/memberships/{application}` | รายละเอียดตามสิทธิ์ |
| `/admin/memberships/{application}/slip` | ดาวน์โหลดสลิปตามสิทธิ์ |

เส้นทางสมัครสมาชิกและ Admin ต้องล็อกอินและยืนยันอีเมล เส้นทาง Admin มี `can:access-admin` เพิ่มเติม

## ระบบสมัครสมาชิกที่ทำแล้ว

ไฟล์: `MembershipApplication` model, `MembershipApplicationController`, `StoreMembershipApplicationRequest`, `StoreMembershipSlipRequest` และ `resources/views/membership-application.blade.php`

- เก็บคำนำหน้า ชื่อ นามสกุล รหัสนิสิตไม่บังคับ หลักสูตรแบบ textbox ตามเงื่อนไขด้านล่าง ชื่อเล่นไม่บังคับ เลขประชาชน ที่อยู่ โทรศัพท์ และอีเมลที่ดึงจากบัญชี
- สมาชิกสามัญต้องเลือก `ordinary_category`: `graduate` ผู้สำเร็จการศึกษา / `lecturer` อาจารย์ / `staff` บุคลากร
- เลือกอาจารย์หรือบุคลากร: ซ่อนและ disable ช่องรหัสนิสิตกับหลักสูตร ไม่บังคับกรอก และฝั่งเซิร์ฟเวอร์ exclude ค่าทั้งสองแม้มีการปลอมส่งมา
- ผู้สำเร็จการศึกษายังต้องระบุหลักสูตร ส่วนรหัสนิสิตไม่บังคับ ประเภทอื่นยังใช้กฎหลักสูตรเดิมและไม่บันทึก ordinary_category
- `program` เปลี่ยนเป็น nullable เพื่อรองรับอาจารย์/บุคลากร ส่วนคำขอเดิมไม่ถูกเดาประเภทย่อยให้
- ยกเลิกช่องและลบคอลัมน์ `qualification` แล้ว รวมทั้งการอ้างอิงใน Model, Factory และหน้า Admin ไม่ต้องเพิ่มกลับ; ข้อมูลเดิมในคอลัมน์นี้ถูกลบตามคำขอผู้ใช้
- คำนำหน้าเลือก นาย/นาง/นางสาว/อื่น ๆ; เลือกอื่น ๆ ต้องกรอกเพิ่ม โดยบันทึกข้อความจริงใน `prefix`
- ชื่อและนามสกุลอยู่แถวเดียวกัน เติมจากชื่อบัญชีโดยแยกตามช่องว่างแรก แก้ไขได้ และข้อมูลที่กรอกไว้มีลำดับเหนือค่าเริ่มต้นเมื่อ validation ไม่ผ่าน
- ไม่มีช่องอีเมลในแบบสมัครแล้ว อีเมลคำขอมาจากบัญชีที่เข้าสู่ระบบและยืนยันแล้ว ไม่รับค่าที่ปลอมส่งมาจากฟอร์ม หลังส่งคำขอจะแสดงอีเมลที่ใช้ยื่นในหน้าสรุป
- ปรับ checkbox รับทราบให้ตรงกับข้อความบรรทัดแรก โดยแก้ขนาด/min-height แยกจาก input ข้อความ
- หนึ่งบัญชีมีหนึ่งคำขอ และเลขประชาชนหนึ่งหมายเลขมีหนึ่งคำขอ
- ตรวจเลขประชาชน 13 หลักและ checksum; เก็บด้วย encrypted cast และ HMAC hash สำหรับตรวจซ้ำ
- `bootstrap/app.php` ป้องกัน flash เลขประชาชนกลับ session เมื่อ validation ไม่ผ่าน
- กำหนดค่าบำรุงจากเซิร์ฟเวอร์: สามัญ 100 บาท วิสามัญ 300 บาท สมทบ `null` รอยืนยัน ห้ามตีความว่าไม่มีค่าบำรุง
- กิตติมศักดิ์เป็นสมาชิกที่สมาคมเชิญ ไม่เปิดให้เลือกสมัครเอง
- สถานะเริ่มต้น `pending_review`; การส่งแบบสมัครหรือแนบสลิปไม่ทำให้สมาชิกภาพสมบูรณ์
- สลิปรองรับ JPG/PNG/PDF ไม่เกิน 5 MB เก็บ disk `local` ใน `storage/app/private/membership-slips/`
- หลังส่งสลิปเป็น `pending_verification`; ปัจจุบันยังไม่เปิดเปลี่ยนสลิปซ้ำหรือแก้ไขคำขอ
- ดาวน์โหลดสลิปผ่าน controller ตรวจเจ้าของหรือสิทธิ์ฝ่ายการเงิน ไม่สร้าง public URL
- เมื่อบัญชีถูกลบ คำขอจะยังอยู่โดย `user_id` เป็น null ตาม foreign key ไม่ cascade ลบประวัติคำขอ

**ข้อมูลธนาคารยังรอผู้ใช้แจ้ง** ผู้ใช้ยืนยันให้เว้นไว้ก่อน หน้าคำขอแจ้งให้รอข้อมูลจากสมาคมก่อนโอน และให้แนบหลักฐานได้กรณีชำระผ่านช่องทางที่สมาคมแจ้งไว้แล้ว ห้ามสร้างบัญชีธนาคารสมมติ

## ระบบสิทธิ์และ Admin ที่ทำแล้ว

- ตาราง `roles`: `name`, `label` และ timestamps
- ตารางเชื่อม `role_user`: role_id/user_id เป็น composite primary key และมี timestamps
- ไม่มี `admin_role` ในตาราง `users` ปัจจุบันแล้ว
- migration ใหม่ย้ายข้อมูลสิทธิ์เดิมก่อนนำคอลัมน์ออก ไม่แก้ migration เก่าที่รันไปแล้ว
- มี `Role` model และ `User::roles()`, `hasRole()`, `hasAnyRole()`
- คง enum `App\AdminRole` เป็นชื่อสิทธิ์มาตรฐาน; `Member` ใช้ในคำสั่งถอนทุกสิทธิ์ ผู้ใช้ทั่วไปไม่จำเป็นต้องมีแถวใน pivot
- บัญชีเดียวถือ admin + registrar + treasurer หรือ registrar + treasurer ได้
- ตาราง `admin_role_changes` เก็บประวัติ พร้อม `action` granted/revoked; ประวัติเก่าคงค่า replaced

| สิทธิ์ | ความสามารถปัจจุบัน |
| --- | --- |
| admin | เข้าหน้าผู้ดูแล ดูรายละเอียดผู้สมัครและดาวน์โหลดสลิป |
| registrar | เข้าหน้าผู้ดูแล ดูรายละเอียดผู้สมัคร แต่ดาวน์โหลดสลิปไม่ได้ |
| treasurer | เข้าหน้าผู้ดูแล ดูข้อมูลการเงิน/สลิป โดยไม่แสดงที่อยู่ เลขประชาชน โทรศัพท์ อีเมล และคุณสมบัติ |

Gate อยู่ใน `app/Providers/AppServiceProvider.php`: `access-admin`, `view-membership-details`, `view-membership-payments`

- ฝ่ายทะเบียนเห็นเลขประชาชนแบบปิดบัง ไม่แสดงเลขเต็ม
- บันทึกการดูคำขอ/ดาวน์โหลดสลิปด้วย Laravel Log โดยใช้ actor ID และ application ID
- ปัจจุบัน Admin เป็นระบบอ่าน/ตรวจข้อมูล ยังไม่มีปุ่มรับรอง ขอแก้ไข อนุมัติ หรือยืนยันการเงิน
- Admin คนแรกคือ `skrisda@tsu.ac.th` แต่งตั้งแล้ว และตรวจยืนยันว่าคงสิทธิ์หลังย้ายตารางแล้ว
- ยังไม่ได้เพิ่ม registrar/treasurer ให้บัญชีนี้โดยอัตโนมัติ

คำสั่งสำคัญ (ต้องเป็นบัญชีที่มีอยู่และยืนยันอีเมลก่อนเพิ่มสิทธิ์):

```bash
php artisan admin:grant user@example.com --no-interaction
php artisan admin:grant user@example.com --role=registrar --no-interaction
php artisan admin:grant user@example.com --role=treasurer --no-interaction
php artisan admin:grant user@example.com --role=treasurer --revoke --no-interaction
php artisan admin:grant user@example.com --role=member --no-interaction
```

เพิ่มสิทธิ์ไม่แทนที่สิทธิ์อื่น คำสั่งซ้ำไม่เพิ่ม pivot ซ้ำ และป้องกันถอนสิทธิ์ Admin คนสุดท้าย `--role=member` ถอนทุกสิทธิ์ผู้ดูแล

## ข้อบังคับและไฟล์อ้างอิง

- ข้อบังคับอยู่ที่ `public/downloads/ข้อบังคับสมาคมศิษย์เก่า SCIDI TSU AA ฉบับที่ 1-2568.docx`
- มีไฟล์รายชื่อกรรมการ XLSX ใน `public/downloads/` ด้วย แต่ยังไม่ได้พัฒนาโมดูลกรรมการจากไฟล์นั้น
- เดิมใช้ `public/documents` แต่ชนกับ route `/documents` ทำให้ PHP development server ตอบ 404 จึงย้ายโฟลเดอร์เป็น `downloads` แล้ว ห้ามสร้าง `public/documents` กลับมาทับ route
- ตรวจ HTTP หน้าเอกสารและดาวน์โหลด DOCX ได้ 200 OK หลังแก้ไข

ข้อกำหนดจากเอกสารที่ใช้วางระบบ:

- สมาชิก 4 ประเภท: กิตติมศักดิ์ สามัญ วิสามัญ สมทบ
- สามัญตลอดชีพ 100 บาท วิสามัญตลอดชีพ 300 บาท กิตติมศักดิ์ยกเว้น ยังไม่พบอัตราสมทบในข้อ 7.4
- คุณสมบัติตามข้อบังคับยังต้องตรวจโดยเจ้าหน้าที่ แม้เอาช่องข้อความคุณสมบัติออกแล้ว
- รับรองโดยนายทะเบียนและเลขานุการ เสนอคณะกรรมการพิจารณา แจ้งผล และชำระค่าบำรุงครบก่อนสมาชิกภาพสมบูรณ์
- รายงานประชุมให้เลขานุการบันทึกและประธานลงนามรับรอง

## ฐานข้อมูลและการตรวจล่าสุด

migration ที่เพิ่มและรันใน MySQL เครื่องเดิมแล้ว:

1. `2026_11_01_115423_create_membership_applications_table.php`
2. `2026_11_01_121822_add_admin_role_to_users_table.php`
3. `2026_11_01_123002_move_admin_roles_to_roles_table.php`
4. `2026_11_01_143722_add_ordinary_category_to_membership_applications_table.php`
5. `2026_11_01_144052_make_qualification_nullable_on_membership_applications_table.php`
6. `2026_11_01_144215_drop_qualification_from_membership_applications_table.php`

migration หมายเลข 5 เป็นขั้นเปลี่ยนผ่าน ก่อนหมายเลข 6 ลบ qualification จริง การ rollback หมายเลข 6 สร้างได้เฉพาะคอลัมน์ว่าง ไม่กู้ข้อมูลที่ลบไปแล้ว ต้องเก็บ migrations เก่าไว้ให้ติดตั้งฐานข้อมูลใหม่ได้ตามลำดับ

migration หมายเลข 2 เป็นประวัติ schema ไม่ใช่ schema ปัจจุบัน หมายเลข 3 ย้ายไป many-to-many และจะปฏิเสธ rollback เมื่อมีผู้ใช้หลายสิทธิ์ เพื่อไม่ให้ข้อมูลสิทธิ์หายจากการลดกลับเป็นหนึ่งสิทธิ์

ผลตรวจล่าสุดก่อนสร้างเอกสารนี้:

- `MembershipApplicationTest` และ `AdminMembershipTest` รวมผ่าน 35 กรณี 195 assertions หลังลบ qualification
- หลังนำช่องอีเมลออก รัน `MembershipApplicationTest` ซ้ำผ่าน 19 กรณี 85 assertions
- `Auth/RegistrationTest` ผ่าน 4 กรณี 13 assertions หลังแก้รหัสผ่าน โดยมีการทดสอบเงื่อนไข Production แบบ mock การตรวจรหัสผ่านรั่วไหล ไม่เรียกบริการภายนอก
- PHPStan ของไฟล์ที่เกี่ยวข้องกับการย้าย roles ผ่าน และ Model/Form Request ที่แก้ประเภทย่อยสมาชิกผ่าน
- Pint และ Blade compilation ผ่าน
- Build frontend ผ่านหลังเพิ่มประเภทย่อยและแก้ checkbox; งานนำช่องอีเมลออกตรวจ Blade compilation แล้ว ไม่มีการเปลี่ยน asset
- ยังไม่ได้รันทดสอบทั้ง suite จึงไม่ควรกล่าวว่าทั้งโปรเจกต์ผ่านทั้งหมด
- งานหน้าตาส่วนใหญ่ยังไม่มีการตรวจภาพจริงผ่านเบราว์เซอร์อย่างครบถ้วน

คำสั่งตรวจที่ใช้:

```bash
php artisan test --compact tests/Feature/AdminMembershipTest.php tests/Feature/MembershipApplicationTest.php
php artisan test --compact tests/Feature/Auth/RegistrationTest.php
vendor/bin/pint --dirty --format agent
vendor/bin/phpstan analyse --debug --no-progress
php artisan view:cache
npm run build
php artisan route:list
```

ข้อจำกัดเดิมที่ทราบ:

- `tests/Pest.php` ยัง comment global `RefreshDatabase` ไว้ `MembershipApplicationTest`, `AdminMembershipTest` และ `Auth/RegistrationTest` ใช้ trait ของตนเองแล้ว ส่วน tests เดิมอื่น ๆ บางไฟล์เคยล้มเพราะไม่มีตาราง users ใน SQLite in-memory
- `AGENTS.md` อ้างถึง skill `testing-best-practices` แต่ไม่พบในตำแหน่งที่ตรวจระหว่างงาน ควรตรวจความพร้อมของ skill นี้บนเครื่องใหม่
- Build มีคำเตือน optional `fontaine` ของ font fallback; build สำเร็จ และยังไม่ได้เพิ่ม dependency
- บางข้อความ validation เกี่ยวกับ Password rule และธีมของ component ภายนอกควรตรวจผ่าน UI จริงเพิ่มเติม

## เงื่อนไขรหัสผ่านล่าสุด

- ความยาวขั้นต่ำ 8 ตัวอักษร เปลี่ยนจาก 12 ใน Production ใน `AppServiceProvider`
- เงื่อนไข Production อื่นยังคงอยู่: ตัวพิมพ์ใหญ่/เล็ก ตัวอักษร ตัวเลข สัญลักษณ์ และตรวจรหัสผ่านรั่วไหล ไม่ใช่การอนุญาตรหัสผ่านทุกแบบที่ยาว 8 ตัว
- `CreateNewUser` ส่งข้อความความยาวเป็นไทย: “รหัสผ่านต้องมีความยาวอย่างน้อย :min ตัวอักษร”
- หน้าสร้างบัญชีมี minlength 8 และ placeholder อธิบายขั้นต่ำ
- Password defaults ใช้ร่วมกับการตั้ง/เปลี่ยนรหัสผ่านในระบบ แต่การเพิ่มข้อความไทยครั้งล่าสุดทำเฉพาะการสร้างบัญชี อย่าสรุปว่า validation ทุกหน้าถูกแปลครบเพราะการเปลี่ยนนี้
## งานถัดไป

1. กระบวนการรับรองโดยนายทะเบียน/เลขานุการ ขอแก้ไขหรือปฏิเสธพร้อมเหตุผล และบันทึกผู้ดำเนินการ/เวลา
2. ตรวจสลิป ยืนยันหรือปฏิเสธยอด และเปิดแนบใหม่หลังถูกขอแก้ไข โดยแยกจากสถานะรับสมาชิก
3. บันทึกมติคณะกรรมการ เลขอ้างอิง วันที่ และสถานะสมาชิกภาพสมบูรณ์ตามเงื่อนไขครบถ้วน ไม่อนุมัติอัตโนมัติจากสลิป
4. ตกลงอัตราสมาชิกสมทบและข้อมูลบัญชีธนาคารก่อนเปิดขั้นตอนชำระเต็มรูปแบบ
5. หน้าจัดการสิทธิ์ผ่านเว็บ หากผู้ใช้ต้องการ ปัจจุบันใช้ Artisan เท่านั้น
6. รูปโปรไฟล์หลังลงทะเบียน: แผนเดิม JPG/PNG/WebP ไม่เกิน 5 MB ครอป/ปรับขนาดและลบ EXIF ยังไม่พัฒนา
7. ระบบข่าว กิจกรรม/ชำระเงิน บริจาค คลังภาพ และรายงานประชุมฉบับใช้งานจริง
8. ตรวจ UI มือถือ โหมดมืด การคืนค่า font และข้อความภาษาไทยให้ครบก่อนใช้งานจริง

## การย้ายไปเครื่องอื่น

- ย้ายทั้งโค้ดและไฟล์ใหม่ที่ยังไม่ได้ commit ตรวจ `git status` ไม่ใช้เฉพาะ repository ที่ยังไม่ได้บันทึกงานล่าสุด
- สำรอง/นำเข้าฐานข้อมูล MySQL ทั้งข้อมูลและตาราง migrations เพื่อรักษาบัญชี สิทธิ์ และคำขอเดิม
- ย้าย `storage/app/private` สำหรับสลิป พร้อม `public/images` และ `public/downloads`; ย้าย uploads อื่นถ้ามีเพิ่มภายหลัง
- นำค่า `.env` ไปตั้งบนเครื่องใหม่อย่างปลอดภัย ปรับฐานข้อมูล/URL/mail ให้ตรงเครื่อง ห้ามใส่ค่าลับลงเอกสารนี้หรือ public repository
- **รักษา APP_KEY เดิม** หากนำเข้าข้อมูลเข้ารหัสเดิม มิฉะนั้นถอดรหัสเลขประชาชนไม่ได้ HMAC สำหรับตรวจซ้ำก็ใช้ key เดียวกัน
- ห้ามใช้ `composer run setup` หรือ `php artisan key:generate` โดยไม่พิจารณาข้างต้น เพราะ setup มีขั้นตอนสร้าง key ใหม่
- ใช้ `composer install` และ `npm ci` ตาม lockfiles โดยไม่เปลี่ยน dependencies โดยไม่จำเป็น
- หลังตั้งค่าฐานข้อมูลแล้วตรวจ `php artisan migrate:status --no-interaction` และรันเฉพาะ migrations ที่ค้าง ห้ามใช้ `migrate:fresh` กับข้อมูลที่ต้องเก็บ
- ล้าง cache ที่ผูกกับเครื่องเดิมตามความจำเป็นด้วย `php artisan optimize:clear` และ build assets ใหม่ด้วย `npm run build`
- `public/hot` อาจชี้ Vite dev server เครื่องเดิม ให้ตรวจหาก CSS/JS ไม่โหลดบนเครื่องใหม่
- ตรวจว่า PHP และ extensions บนเครื่องใหม่รองรับ dependencies จริง การคัดลอกไฟล์อย่างเดียวไม่ย้าย MySQL หรือค่าติดตั้ง runtime ให้
- ไฟล์นี้ไม่ย้ายบทสนทนา Codex แต่ให้ใช้เป็นบริบทเริ่มต้นของแชตใหม่พร้อม `AGENTS.md`

ตัวอย่างข้อความเริ่มงานในเครื่องใหม่:

> อ่าน AGENTS.md และ PROJECT_STATUS.md แล้วตรวจโค้ดและ git status ก่อน เริ่มต่อจากสถานะปัจจุบันโดยรักษางานที่ยังไม่ได้ commit อย่าสร้าง key ใหม่หรือ reset ฐานข้อมูลที่นำเข้ามา งานถัดไปคือระบบตรวจคำขอสมาชิกและการเงินของ Admin
