# تقرير فحص وتجهيز مشروع POST لاستضافة Alwaysdata (الخطة المجانية)

**ملاحظة مهمة:** هذا تقرير فحص فقط — **لم أعدّل أي ملف بعد**. في آخر التقرير قسم "التعديلات المقترحة" يحتاج موافقتك الصريحة قبل التنفيذ.

---

## 1. تقرير فحص المشروع

| البند | القيمة |
|---|---|
| إصدار Laravel | 12.x |
| إصدار PHP المطلوب | `^8.2` (من `composer.json`) |
| Dependencies الأساسية | `laravel/framework ^12`, `laravel/socialite ^5.29`, `laravel/tinker ^2.10` فقط — لا شيء غريب أو ثقيل |
| Node/NPM أثناء التشغيل؟ | **لا إطلاقاً.** رغم وجود `package.json` و`vite.config.js`، لا يوجد أي استخدام فعلي لـ `@vite()` في أي Blade — الـ JS الوحيد هو `public/app.js` كملف ثابت عادي. Vite غير مُستخدم فعلياً في المشروع. |
| قاعدة البيانات الحالية | SQLite محلياً (`database/database.sqlite`) — PostgreSQL على Render حالياً |
| قاعدة البيانات المطلوبة على Alwaysdata | MySQL (متوفرة بلا حد أقصى لعدد القواعد حتى في الخطة المجانية) |
| Storage/رفع ملفات محلي | **غير مستخدم إطلاقاً.** صور المنتجات كلها روابط URL نصية (`products.image` = string)، لا يوجد `Storage::disk()` ولا `storage:link` في أي مكان بالكود. |
| Queue Jobs | **لا يوجد أي Job مُصرَّح (`ShouldQueue`) في المشروع كله.** إعداد `QUEUE_CONNECTION=database` موجود لكنه غير مُستخدم فعلياً. |
| Scheduled Tasks / Cron | لا يوجد أي جدولة في `routes/console.php` سوى أمر `inspire` الافتراضي — **لا حاجة لـ Cron حالياً.** |
| Session/Cache Driver | `database` لكليهما — الجداول اللازمة (`sessions`, `cache`, `jobs`) موجودة فعلاً ضمن migrations قياسية من Laravel 12 نفسه. |

---

## 2. المشاكل التي تم اكتشافها (بترتيب الأهمية)

### 🔴 مشكلة حقيقية: رابط Google OAuth مُثبَّت على دومين Render
**الملف:** `config/services.php`
```php
'redirect' => env('GOOGLE_REDIRECT_URI', 'https://post-z44n.onrender.com/auth/google/callback'),
```
القيمة الافتراضية (fallback) مكتوبة يدوياً بدومين Render. لو نسيت تحديد `GOOGLE_REDIRECT_URI` في `.env` على Alwaysdata، تسجيل الدخول بجوجل هيحاول يرجع لدومين Render القديم بدل موقعك الجديد ويفشل. **هذا يحتاج إما (أ) ضبط `GOOGLE_REDIRECT_URI` بشكل صريح في `.env` الجديد، أو (ب) تعديل الكود ليعتمد على `APP_URL` تلقائياً بدل قيمة ثابتة.** سأقترح الخيار (ب) لاحقاً كتعديل بسيط ومطلوب فعلياً.

### 🟡 يحتاج انتباه: SSH على الخطة المجانية غير مؤكد 100%
بحثت في توثيق Alwaysdata الرسمي؛ صفحة الأسعار الحالية (2026) تسرد "SSH access included" ضمن مزايا خطة **Plus** (5€/شهر) وليس Free صراحةً، بينما مصادر أخرى تقول SSH متاح في كل الخطط لكن معطّل افتراضياً ويحتاج تفعيل. **لا أستطيع الجزم من هنا** — تأكد بنفسك من لوحة تحكم حسابك (Admin panel → SSH) بعد التسجيل. جهّزت التقرير بطريقتين (بـ SSH وبدونه) لتغطية الحالتين.

### 🟢 لا توجد مشاكل توافق حقيقية في الكود نفسه
- لا يوجد SQL خام (`DB::raw`, `whereRaw`) في أي مكان — كل الاستعلامات عبر Eloquent، متوافقة 100% مع MySQL.
- لا يوجد أي migration يستخدم `->change()` (وهو ما كان سيتطلب تثبيت `doctrine/dbal` وهو غير موجود في `composer.json`).
- لا يوجد أعمدة خاصة بـ PostgreSQL (`jsonb`, custom enums عبر SQL خام، إلخ).
- كل الـ Foreign Keys تُعرَّف بطريقة Laravel القياسية (`foreignId()->constrained()`) المتوافقة مع MySQL دون أي تعديل.

---

## 3. قاعدة البيانات — الإعداد على Alwaysdata

### إنشاء قاعدة البيانات
1. من لوحة تحكم Alwaysdata: **Databases → Add a database** → اختر **MySQL**.
2. سمِّها (مثلاً `post_production`) — Alwaysdata غالباً بيضيف بادئة تلقائية لاسم حسابك (شكلها هيبقى تقريباً `accountname_post_production`).
3. أنشئ مستخدم قاعدة بيانات (أو استخدم المستخدم الافتراضي لحسابك) وحدد كلمة مرور قوية.
4. من نفس الصفحة هتلاقي: **Host** (عادة داخلي زي `mysql-accountname.alwaysdata.net`)، والـ **Port** (غالباً `3306`).

### القيم المطلوبة في `.env` (لن أضع بيانات وهمية — أنت من يملأ القيم الحقيقية بعد إنشاء القاعدة)
```env
DB_CONNECTION=mysql
DB_HOST=<من لوحة تحكم Alwaysdata، مثال: mysql-accountname.alwaysdata.net>
DB_PORT=3306
DB_DATABASE=<اسم القاعدة الكامل بما فيه البادئة>
DB_USERNAME=<اسم مستخدم القاعدة>
DB_PASSWORD=<كلمة المرور>
```

---

## 4. `.env.example` مقترح للنشر (مُعدّ ليُنسخ إلى `.env` الحقيقي ثم تُملأ فيه القيم السرية)

هذا التعديل المقترح الوحيد على `.env.example` — **القيم السرية فاضية عمداً**، ولن يتم رفع أي `.env` حقيقي لـ Git إطلاقاً (`.env` موجود أصلاً في `.gitignore`، تأكدت من هذا):

```env
APP_NAME=POST
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain-on-alwaysdata.com

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=https://your-domain-on-alwaysdata.com/auth/google/callback

CJ_EMAIL=
CJ_API_KEY=

SETUP_TOKEN=
```

ملاحظات:
- غيّرت `LOG_LEVEL` من `debug` إلى `error` للإنتاج (تفادي تسجيل تفاصيل حساسة زيادة عن اللزوم).
- أضفت `GOOGLE_REDIRECT_URI` و`CJ_EMAIL`/`CJ_API_KEY`/`SETUP_TOKEN` صراحة لأنها مستخدمة فعلياً في الكود (`config/services.php`، ومسار `/run-setup`) لكنها لم تكن موجودة في `.env.example` الأصلي أصلاً.
- حذفت أقسام AWS/Redis/Memcached/Slack/Postmark/Resend لأن المشروع لا يستخدم أياً منها فعلياً (كانت من القالب الافتراضي فقط) — **لن أحذفها من `.env.example` بدون موافقتك**، هذه فقط نسخة مقترحة، إبقاءها لا يضر أيضاً إن فضّلت.

---

## 5. الـ Dependencies — لا شيء يحتاج استبدال

فحصت `composer.json` بالكامل. الحزم الثلاث المستخدمة فعلياً (`laravel/framework`, `laravel/socialite`, `laravel/tinker`) قياسية تماماً ولا تحتاج أي PHP Extension غير عادي (لا GD متقدم، لا Imagick، لا Redis extension، لا شيء خاص). المتطلبات القياسية لأي استضافة PHP 8.2+ حديثة (ومنها Alwaysdata):
`BCMath, Ctype, cURL, DOM, Fileinfo, Filter, Hash, Mbstring, OpenSSL, PCRE, PDO, pdo_mysql, Session, Tokenizer, XML`

كلها مفعّلة افتراضياً على أغلب استضافات PHP الحديثة — تحقق فقط من تفعيل `pdo_mysql` تحديداً من لوحة تحكم PHP في Alwaysdata (Site → PHP configuration → Extensions).

**لا يوجد أي Package يحتاج حذفاً أو استبدالاً.**

---

## 6. الـ Frontend — لا حاجة لأي Build

كما ذكرت في القسم 1: المشروع **لا يستخدم Vite فعلياً** رغم وجود ملفاته في `package.json`/`vite.config.js` (بقايا القالب الافتراضي لم تُستخدم قط). كل الـ JS/CSS موجود جاهزاً في `public/`:
```
public/app.js
public/styles.css
public/images/post-logo.png
public/favicon.ico
```
**لا حاجة لـ `npm install` ولا `npm run build` إطلاقاً على السيرفر.** هذه الملفات تُرفع كما هي حرفياً.

---

## 7. `.htaccess` — لا تعديل مطلوب على الملف نفسه

الملف الحالي (`public/.htaccess`) قياسي 100% ومتوافق مع Apache/mod_rewrite (وهو ما توفره Alwaysdata):
- يعيد توجيه كل الطلبات لـ `index.php` (front controller pattern).
- يمرر Header الـ Authorization وX-XSRF-Token بشكل صحيح.

**الشرط الوحيد:** أن يكون Document Root لموقعك على Alwaysdata مُشار به مباشرة إلى مجلد `public/` (وليس لجذر المشروع). Alwaysdata (بخلاف كثير من استضافات cPanel الرخيصة) بتسمح بضبط الـ Document Root من لوحة التحكم مباشرة (Sites → Path)، وده الأسلوب النظيف الموصى به. لو لأي سبب مش متاح تغيير الـ Document Root، هفصّل بديل `public_html` في القسم 11.

---

## 8. Storage — لا ينطبق على مشروعك

طلبت التأكد من `php artisan storage:link`، لكن بعد الفحص: **مشروعك لا يستخدم `Storage::disk('public')` ولا أي رفع ملفات محلي إطلاقاً** — كل صور المنتجات مخزَّنة كروابط URL نصية في قاعدة البيانات (وليست ملفات مرفوعة فعلياً على السيرفر). لذلك:
- **لا حاجة لتنفيذ `storage:link` أصلاً حالياً.**
- إذا أضفت مستقبلاً ميزة "رفع صورة من جهازك" (بدل رابط URL)، وقتها فقط هتحتاج هذه الخطوة، وهطلب SSH أو بديل (شرحته بالأسفل احتياطاً).

---

## 9. أوامر Artisan المطلوبة (مع بديل لكل أمر إذا لم يتوفر SSH)

| الأمر | الغرض | البديل بدون SSH |
|---|---|---|
| `composer install --no-dev --optimize-autoloader` | تثبيت مكتبات PHP (`vendor/`) | **لا بديل حقيقي بدون SSH** — composer محتاج ينفّذ على السيرفر. لو مفيش SSH إطلاقاً، الحل الوحيد العملي هو تشغيل `composer install` **محلياً على جهازك** ثم رفع مجلد `vendor/` كاملاً عبر FTP (بطيء لكنه يعمل — احجم `vendor/` عادة عشرات الميجابايت). |
| `php artisan key:generate` | توليد `APP_KEY` | ولّده محلياً (`php artisan key:generate --show`) وانسخ القيمة يدوياً داخل `.env` على السيرفر عبر File Manager |
| `php artisan migrate --force` | تطبيق الجداول على MySQL | استورد ملف SQL جاهز عبر **phpMyAdmin** المتوفر في Alwaysdata (هشرح إزاي تجهزه بالأسفل) |
| `php artisan config:cache` | تسريع قراءة الإعدادات | **لا تنفّذه بدون SSH** — لو الكاش اتحفظ بمسارات جهازك المحلي (Windows paths) هيكسر الموقع على السيرفر. اتركه بدون Cache، الفرق بالأداء ضئيل لموقع بحجمك |
| `php artisan route:cache` | تسريع الـ Routing | نفس الملاحظة أعلاه — تجنّبه بدون SSH |
| `php artisan view:cache` | تسريع Blade | نفس الملاحظة — Laravel بيولّد الكاش تلقائياً أول ما تُفتح كل صفحة حتى بدونه |
| `php artisan storage:link` | ربط storage بـ public | **غير مطلوب لمشروعك** (راجع القسم 8) |

### إذا كان لديك SSH فعلاً (الأفضل، جرّب أولاً)
```bash
cd ~/www   # أو المسار الفعلي لموقعك حسب Alwaysdata
composer install --no-dev --optimize-autoloader
cp .env.example .env    # ثم عدّل القيم يدوياً
php artisan key:generate
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### إذا لم يتوفر SSH — البديل الكامل خطوة بخطوة
1. على جهازك: شغّل `composer install --no-dev --optimize-autoloader` (بدون `--no-dev` لو عايز تختبر محلياً الأول).
2. ارفع المشروع **كاملاً بما فيه `vendor/`** عبر FTP أو File Manager.
3. جهّز `.env` يدوياً على جهازك بالقيم الصحيحة، وارفعه بالـ FTP (تأكد إنه ملف مخفي `.env` مش `.env.txt`).
4. لتوليد `APP_KEY`: نفّذه محلياً `php artisan key:generate --show` وانسخ الناتج يدوياً جوه `.env` على السيرفر.
5. لتطبيق الـ Migrations بدون SSH: شغّل `php artisan migrate` **محلياً على SQLite أو MySQL محلي مطابق**، ثم صدّر بنية الجداول (Structure Only, بدون بيانات وهمية) كملف `.sql` عبر أي أداة (مثلاً MySQL Workbench أو حتى phpMyAdmin محلي)، وارفعه واستورده في **phpMyAdmin بتاع Alwaysdata** (متاح من لوحة التحكم لكل قاعدة بيانات).
6. اترك Config/Route/View Cache **بدون تفعيل** — مش ضروري وممكن يسبب مشاكل لو اتولّد بمسارات مختلفة عن السيرفر.

---

## 10. الملفات التي تُرفع / لا تُرفع

**أرفع هذه الملفات والمجلدات:**
- `app/`
- `bootstrap/` (بما فيها `bootstrap/cache/` — فاضي أو بملفات `.gitignore` فقط، هيتولّد تلقائياً)
- `config/`
- `database/` (المجلد كله بما فيه `migrations/`، **بدون** `database.sqlite` لو هتستخدم MySQL)
- `public/` (كل المحتوى: `.htaccess`, `index.php`, `app.js`, `styles.css`, `images/`, `favicon.ico`, إلخ)
- `resources/`
- `routes/`
- `storage/` (المجلد بهيكله الفارغ — `app/`, `framework/{cache,sessions,views}`, `logs/` — لازم تكون موجودة وقابلة للكتابة حتى لو فاضية)
- `tests/` (اختياري، لا يؤثر على عمل الموقع لو اتحذف، لكن لا داعي لحذفه)
- `artisan`
- `composer.json`, `composer.lock`
- `.htaccess` (لو هتستخدم أسلوب `public_html` البديل — راجع القسم 11)

**لا أرفع هذه الملفات إطلاقاً:**
- `.env` (فيه أسرارك الحقيقية — يُنشأ يدوياً على السيرفر فقط، لا يُرفع من جهازك عبر Git، لكن **عبر FTP لازم ترفعه يدوياً** لأنه مش هيتولّد لوحده)
- `node_modules/` (غير مستخدم في التشغيل أصلاً)
- `.git/` (لو بترفع بالـ FTP، مفيش داعي ترفع تاريخ الـ Git)
- `database/database.sqlite` (خاص بالتطوير المحلي فقط)
- `tests/` (اختياري تجاهله لتوفير مساحة ضمن حد الـ 100MB بالخطة المجانية)

**أسئلتك المحددة:**
- `vendor/` → **نعم، لازم يُرفع** إذا مفيش SSH (لأنك مش هتقدر تشغّل `composer install` على السيرفر بدون Shell). لو فيه SSH، **لا ترفعه** — شغّل `composer install` على السيرفر مباشرة (أسرع وأنظف).
- `public/build/` → **غير موجود أصلاً في مشروعك** (لأن Vite غير مُستخدم فعلياً) — لا داعي للقلق بشأنه.
- `storage/` → ارفع الهيكل (المجلدات الفارغة) لكن ليس محتوى `logs/*.log` أو أي ملفات مؤقتة.
- `bootstrap/cache/` → ارفع المجلد فاضياً (أو باللي فيه حالياً، `packages.php`/`services.php` بيتولدوا تلقائياً من Laravel وقت الحاجة حتى لو ملفات قديمة، مش هيسببوا مشكلة).

---

## 11. خطوات الرفع الكاملة من الصفر

1. **إنشاء قاعدة البيانات** — راجع القسم 3 أعلاه.
2. **تجهيز الملفات محلياً** — تأكد من نسخة `.env` جاهزة بالقيم الصحيحة (لسه ما ترفعهاش).
3. **رفع الملفات:**
   - لو معاك SSH: `git clone` أو `git pull` مباشرة على السيرفر من نفس الـ GitHub repo (الأسرع والأنظف بما إنك بترفع لـ GitHub أصلاً حالياً لـ Render).
   - لو من غير SSH: ارفع كل الملفات (ماعدا القائمة المستثناة أعلاه) عبر **File Manager** بتاع Alwaysdata أو FTP (FileZilla مثلاً).
4. **ضبط Document Root:** من لوحة تحكم Alwaysdata (Sites → أضف Site جديد أو عدّل الموجود) → حدد الـ Path بحيث يشير مباشرة لمجلد `public/` داخل مشروعك (مثال: `/www/POST/public`). هذا أهم خطوة — لو اتغلطت فيها هيظهر لستة الملفات بدل الموقع.
5. **رفع `.env`:** ارفعه يدوياً عبر FTP/File Manager لجذر المشروع (بجانب `artisan`، مش جوه `public/`).
6. **استيراد قاعدة البيانات / Migrations** — راجع القسم 9 (بـ SSH أو عبر phpMyAdmin).
7. **الصلاحيات:** تأكد إن `storage/` و`bootstrap/cache/` قابلين للكتابة من السيرفر (عادة 755 كافية على Alwaysdata، جرّب 775 لو ظهرت أخطاء "Permission denied").
8. **اختبار الموقع:** افتح الدومين، تأكد إن الصفحة الرئيسية بتظهر بالتصميم الصحيح (مش قائمة ملفات، مش خطأ 500).
9. **اختبار تسجيل الدخول:** بريد إلكتروني عادي + جوجل (تأكد إن `GOOGLE_REDIRECT_URI` بيطابق الدومين الجديد، وإنك ضفت نفس الرابط في Google Cloud Console كـ Authorized Redirect URI — **ده لازم تعمله انت بنفسك من حساب Google Cloud بتاعك، معنديش وصول له**).
10. **اختبار Admin Dashboard:** سجّل دخول بحساب `is_admin=true`، تأكد من ظهور "نظرة عامة" بأرقام حقيقية، وكل صفحات الطلبات/التصنيفات/العملاء.
11. **اختبار الصور:** تأكد إن صور المنتجات (روابط خارجية) بتظهر عادي — دي مش متأثرة بنوع الاستضافة أصلاً.
12. **اختبار Forms:** تسجيل، تسجيل دخول، إضافة للسلة، Checkout.
13. **اختبار قاعدة البيانات:** أضف منتج/تصنيف من لوحة التحكم وتأكد إنه بيتسجل فعلياً في MySQL.
14. **اختبار كل الـ Routes:** استخدم القائمة الكاملة اللي جهزتها في تقرير "التحقق من البيئتين" السابق (10 خطوات محلي + 10 على الإنتاج).

---

## 12. إذا لم تدعم الاستضافة Laravel مباشرة

هذا السيناريو **غير محتمل مع Alwaysdata تحديداً** — عندهم صفحة رسمية مخصصة لاستضافة Laravel ومثبِّت جاهز في الـ Marketplace يؤكد دعماً كاملاً وأصلياً لـ PHP 8.2+ وComposer. لن تحتاج أي حيلة `public_html` بديلة على الأغلب. **لكن احتياطاً**، لو اكتشفت إن لوحة التحكم مش بتسمح بتغيير الـ Document Root لمجلد `public/` مباشرة، البديل الآمن (بدون تغيير أي كود حقيقي في المشروع) هو:
```
public_html/          ← Document Root الإجباري
    index.php          (نسخة معدّلة تُحيل لمجلد Laravel الحقيقي)
    .htaccess           (نفس محتوى public/.htaccess الحالي)
    (نسخ من app.js, styles.css, images/, favicon.ico إلخ)
laravel-app/            ← باقي المشروع الحقيقي (app/, config/, vendor/, إلخ) خارج public_html
```
مع تعديل `public_html/index.php` بحيث يشير لمسارات `laravel-app/vendor/autoload.php` و`laravel-app/bootstrap/app.php` بدل `../vendor` و`../bootstrap` القياسية. **هذا تعديل بسيط لمسارين فقط في ملف واحد، ولن يغيّر أي شيء في تصميم أو وظائف الموقع** — لن أنفّذه إلا لو أكدت إنك فعلاً محتاجه بعد ما تتأكد من لوحة تحكم Alwaysdata.

---

## التعديلات المقترحة (تحتاج موافقتك قبل التنفيذ)

1. **تحديث `.env.example`** بالمحتوى المقترح في القسم 4 (يشمل `GOOGLE_REDIRECT_URI`, `CJ_EMAIL`, `CJ_API_KEY`, `SETUP_TOKEN` التي كانت ناقصة أصلاً).
2. **إصلاح صغير في `config/services.php`**: تغيير القيمة الافتراضية لـ `google.redirect` من رابط Render المُثبَّت يدوياً إلى قيمة مبنية على `config('app.url')` تلقائياً — بحيث يعمل على أي دومين تنشر عليه المشروع مستقبلاً (Render أو Alwaysdata أو غيره) دون تعديل الكود كل مرة. هذا تعديل سطر واحد فقط، ولا يغيّر أي وظيفة أو تصميم.

لن أطبّق أياً من هذين التعديلين حتى تؤكد لي.
