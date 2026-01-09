<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قسم الصيانة - مستشفى الحاج المجاهد ابو جويدة المهندس</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-secondary mb-4 shadow">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">🛠️ قسم الصيانة - سجل الأجهزة</span>
            <a href="index.php" class="btn btn-outline-light btn-sm">الرئيسية</a>
        </div>
    </nav>

    <div class="container">
        <div class="card shadow p-4">
            <h4 class="mb-4 text-secondary">سجل استخدام الأجهزة الطبية</h4>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>اسم الجهاز</th>
                            <th>وقت الاستخدام</th>
                            <th>اسم المريض</th>
                            <th>التفاصيل</th>
                            <th>النتائج</th>
                        </tr>
                    </thead>
                    <tbody id="logList">
                        <!-- Data via JS -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="jquery.min.js"></script>
    <script>
        function fetchLogs() {
            $.getJSON('api.php?action=fetch_maintenance', function(data) {
                let rows = '';
                if(data.length === 0) {
                    rows = '<tr><td colspan="6" class="text-center">لا توجد سجلات حتى الآن</td></tr>';
                } else {
                    data.forEach(log => {
                        rows += `<tr>
                            <td>${log.id}</td>
                            <td class="fw-bold text-primary">${log.device_name}</td>
                            <td dir="ltr" class="text-end">${log.created_at}</td>
                            <td>${log.patient_name || 'غير معروف'}</td>
                            <td>${log.usage_details}</td>
                            <td>${log.result}</td>
                        </tr>`;
                    });
                }
                $('#logList').html(rows);
            });
        }

        fetchLogs();
        setInterval(fetchLogs, 5000); // Refresh every 5 seconds
    </script>
</body>
</html>
