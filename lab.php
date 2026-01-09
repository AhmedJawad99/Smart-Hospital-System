<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المختبر - مستشفى الحاج المجاهد ابو جويدة المهندس</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-warning mb-4 shadow">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1 text-dark">🧪 لوحة تحكم المختبر</span>
        </div>
    </nav>

    <div class="container">
        <h3 class="mb-4">طلبات التحليل المعلقة</h3>
        <div class="row" id="labInfo">
            <!-- Dynamic Cards -->
        </div>
    </div>

    <script src="jquery.min.js"></script>
    <script>
        function fetchLab() {
            // Prevent refresh if user is interacting with ANY input
            if ($('textarea:focus, input:focus').length > 0) return;

            $.getJSON('api.php?action=fetch_data', function(data) {
                let html = '';
                let labPatients = data.filter(p => p.status === 'In_Lab');

                if (labPatients.length === 0) {
                    html = '<div class="col-12 text-center text-muted"><h3>لا توجد طلبات معلقة</h3></div>';
                } else {
                    labPatients.forEach(p => {
                        html += `<div class="col-md-4 mb-3">
                            <div class="card p-3 border-warning">
                                <h5>${p.name}</h5>
                                <p class="text-danger"><strong>الطلب:</strong> ${p.lab_request}</p>
                                <hr>
                                <label>نتائج التحليل:</label>
                                <textarea id="res-${p.id}" class="form-control mb-2" rows="2" placeholder="اكتب النتيجة..."></textarea>
                                
                                <label class="fw-bold mb-1">الأجهزة المستخدمة:</label>
                                <div class="device-checkboxes mb-3 border p-2 rounded bg-light">
                                    <div class="form-check">
                                        <input class="form-check-input dev-check-${p.id}" type="checkbox" value="تحليل دم شامل (CBC)" id="dev1-${p.id}">
                                        <label class="form-check-label" for="dev1-${p.id}">تحليل دم شامل (CBC)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input dev-check-${p.id}" type="checkbox" value="أشعة صدر (X-Ray)" id="dev2-${p.id}">
                                        <label class="form-check-label" for="dev2-${p.id}">أشعة صدر (X-Ray)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input dev-check-${p.id}" type="checkbox" value="رنين مغناطيسي (MRI)" id="dev3-${p.id}">
                                        <label class="form-check-label" for="dev3-${p.id}">رنين مغناطيسي (MRI)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input dev-check-${p.id}" type="checkbox" value="تحليل ادرار" id="dev4-${p.id}">
                                        <label class="form-check-label" for="dev4-${p.id}">تحليل ادرار</label>
                                    </div>
                                </div>
                                
                                <button class="btn btn-success w-100" onclick="submitResult(${p.id})">✅ إرسال للطبيب</button>
                            </div>
                        </div>`;
                    });
                }
                
                // Only update HTML if we are NOT editing (double check handled by top condition, but good for safety)
                $('#labInfo').html(html);
            });
        }

        function submitResult(id) {
            let res = $(`#res-${id}`).val();
            
            // Collect checked devices
            let devices = [];
            $(`.dev-check-${id}:checked`).each(function() {
                devices.push($(this).val());
            });
            let devStr = devices.join(', ');

            if (!res) return alert("الرجاء كتابة النتيجة");

            $.post('api.php?action=update_patient', {
                id: id,
                status: 'With_Doctor', 
                lab_result: res,
                device_name: devStr
            }, function(response) {
                // Success callback
                // Manually remove the card for instant feedback
                $(`#res-${id}`).closest('.col-md-4').fadeOut(500, function() {
                    $(this).remove();
                    // Fetch fresh data after animation
                    fetchLab();
                });
            }, 'json').fail(function() {
                alert('حدث خطأ أثناء الإرسال. الرجاء المحاولة مرة أخرى.');
            });
        }

        setInterval(fetchLab, 2000);
        fetchLab();
    </script>
</body>
</html>
