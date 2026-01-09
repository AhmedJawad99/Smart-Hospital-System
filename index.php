<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>البوابة الرئيسية - مستشفى الحاج المجاهد ابو جويدة المهندس</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        .hover-card {
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
            height: 100%;
        }
        .hover-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
        }
        .icon-large {
            font-size: 4rem;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-primary"> مستشفى الحاج المجاهد ابو جويدة المهندس</h1>
            <p class="lead text-secondary">بوابة الدخول الموحدة للأقسام</p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Reception -->
            <div class="col-md-4 col-sm-6">
                <a href="reception.php" class="text-decoration-none">
                    <div class="card hover-card shadow border-primary text-center p-4">
                        <div class="icon-large">🛎️</div>
                        <h3 class="text-primary">قسم الاستقبال</h3>
                        <p class="text-muted">تسجيل المرضى وإدارة الانتظار</p>
                    </div>
                </a>
            </div>

            <!-- Doctor -->
            <div class="col-md-4 col-sm-6">
                <a href="doctor.php" class="text-decoration-none">
                    <div class="card hover-card shadow border-success text-center p-4">
                        <div class="icon-large">🩺</div>
                        <h3 class="text-success">عيادة الطبيب</h3>
                        <p class="text-muted">تشخيص الحالات ووصف العلاج</p>
                    </div>
                </a>
            </div>

            <!-- Lab -->
            <div class="col-md-4 col-sm-6">
                <a href="lab.php" class="text-decoration-none">
                    <div class="card hover-card shadow border-warning text-center p-4">
                        <div class="icon-large">🧪</div>
                        <h3 class="text-warning">المختبر</h3>
                        <p class="text-muted">استلام العينات وإصدار النتائج</p>
                    </div>
                </a>
            </div>

            <!-- Pharmacy -->
            <div class="col-md-4 col-sm-6">
                <a href="pharmacy.php" class="text-decoration-none">
                    <div class="card hover-card shadow border-info text-center p-4">
                        <div class="icon-large">💊</div>
                        <h3 class="text-info">الصيدلية</h3>
                        <p class="text-muted">صرف الأدوية للمرضى</p>
                    </div>
                </a>
            </div>

            <!-- Patient -->
            <div class="col-md-4 col-sm-6">
                <div class="card hover-card shadow border-danger text-center p-4" onclick="enterPatient()">
                    <div class="icon-large">📱</div>
                    <h3 class="text-danger">تطبيق المريض</h3>
                    <p class="text-muted">متابعة الحالة ونداء الممرض</p>
                </div>
            </div>


            <!-- Maintenance -->
            <div class="col-md-4 col-sm-6">
                <a href="maintenance.php" class="text-decoration-none">
                    <div class="card hover-card shadow border-secondary text-center p-4">
                        <div class="icon-large">🛠️</div>
                        <h3 class="text-secondary">قسم الصيانة</h3>
                        <p class="text-muted">سجل استخدام وصيانة الأجهزة</p>
                    </div>
                </a>
            </div>

        </div>
<!--
        <div class="text-center mt-5 mb-5 space-x-2">
            <a href="install.php" class="btn btn-outline-secondary btn-sm">⚙️ إعادة تثبيت قاعدة البيانات</a>
            <button onclick="resetSystem()" class="btn btn-outline-danger btn-sm">⚠️ حذف جميع البيانات (تهيئة النظام)</button>
        </div>
        <div class="text-center text-muted small">نظام إدارة المستشفيات الذكي &copy; 2025</div> -->
    </div>

    <script src="jquery.min.js"></script>
    <script>
        function enterPatient() {
            let id = prompt("أدخل رقم ملف المريض (ID) للدخول:", "1");
            if (id) {
                window.location.href = "patient_view.php?id=" + id;
            }
        }

        function resetSystem() {
            if(confirm('هل أنت متأكد؟ سيتم حذف سجلات جميع المرضى نهائياً!')) {
                if(confirm('تأكيد نهائي: هل تريد حذف كل شيء؟')) {
                    $.post('api.php?action=reset_system', function(res) {
                        alert('تم حذف جميع البيانات بنجاح.');
                        location.reload();
                    }, 'json');
                }
            }
        }
    </script>
</body>
</html>
