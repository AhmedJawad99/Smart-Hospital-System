<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>الاستقبال - مستشفى الحاج المجاهد ابو جويدة المهندس</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="nurse-alert alert alert-danger shadow-lg border-danger">
        <strong>🚨 نداء ممرض!</strong> <span id="alert-msg">مريض يحتاج للمساعدة!</span>
    </div>

    <nav class="navbar navbar-dark bg-primary mb-4 shadow">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1"> مستشفى الحاج المجاهد ابو جويدة المهندس - الاستقبال</span>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Registration Form -->
            <div class="col-md-4">
                <div class="card p-4">
                    <h4 class="mb-3 text-primary">تسجيل مريض جديد</h4>
                    <form id="regForm">
                        <div class="mb-3">
                            <label class="form-label">الاسم الكامل</label>
                            <input type="text" class="form-control" id="pName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">العمر</label>
                            <input type="number" class="form-control" id="pAge" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الجنس</label>
                            <select class="form-select" id="pGender">
                                <option value="Male">ذكر</option>
                                <option value="Female">أنثى</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">رقم الهاتف</label>
                            <input type="tel" class="form-control" id="pPhone" placeholder="07xxxxxxxxx">
                        </div>
                        <div class="mb-3">
                            <label class="form-label d-block">مستوى الخطورة</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="triage" id="normal" value="Normal" checked>
                                <label class="btn btn-outline-success" for="normal">طبيعي</label>

                                <input type="radio" class="btn-check" name="triage" id="critical" value="Critical">
                                <label class="btn btn-outline-danger" for="critical">حالة حرجة</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">تسجيل</button>
                    </form>
                </div>
            </div>

            <!-- Waiting List -->
            <div class="col-md-8">
                <div class="card p-4">
                    <h4 class="mb-3">قائمة الانتظار</h4>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>رقم الملف</th>
                                    <th>اسم المريض</th>
                                    <th>العمر</th>
                                    <th>الحالة</th>
                                    <th>إجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="patientList">
                                <!-- Data injected via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Audio Player -->
    <!-- Hidden Audio Player -->
    <audio id="alertSound" src="beep.ogg"></audio>

    <script src="jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Localization Map
            const statusMap = {
                'Waiting': 'انتظار',
                'With_Doctor': 'عند الطبيب',
                'In_Lab': 'في المختبر',
                'Pharmacy': 'الصيدلية',
                'Discharged': 'مغادرة'
            };

            function fetchData() {
                $.getJSON('api.php?action=fetch_data', function(data) {
                    let rows = '';
                    let nurseCallActive = false;

                    data.forEach(p => {
                        if (p.nurse_call == 1) {
                            nurseCallActive = true;
                            $('#alert-msg').text(`المريض #${p.id} (${p.name}) يطلب المساعدة!`);
                        }

                        let rowClass = p.triage_level === 'Critical' ? 'critical-row' : '';
                        let badgeClass = `status-${p.status}`;
                        let arStatus = statusMap[p.status] || p.status;
                        
                        let genderIcon = (p.gender === 'Female') ? '👩' : '👨';
                        
                        rows += `<tr class="${rowClass}">
                            <td>${p.id}</td>
                            <td>${genderIcon} ${p.name}</td>
                            <td>${p.age}</td>
                            <td><span class="status-badge ${badgeClass}">${arStatus}</span></td>
                            <td>
                                <a href="patient_view.php?id=${p.id}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    🖨️ طباعة/عرض
                                </a>
                            </td>
                        </tr>`;
                    });

                    $('#patientList').html(rows);

                    if (nurseCallActive) {
                        $('.nurse-alert').fadeIn();
                        document.getElementById('alertSound').play().catch(e => console.log('Audio blocked'));
                    } else {
                        $('.nurse-alert').fadeOut();
                    }
                });
            }

            fetchData();
            setInterval(fetchData, 2000);

            $('#regForm').submit(function(e) {
                e.preventDefault();
                $.post('api.php?action=add_patient', {
                    name: $('#pName').val(),
                    age: $('#pAge').val(),
                    gender: $('#pGender').val(),
                    phone: $('#pPhone').val(),
                    triage_level: $('input[name="triage"]:checked').val()
                }, function(res) {
                    if (res.success) {
                        $('#regForm')[0].reset();
                        fetchData();
                    } else {
                        alert('حدث خطأ: ' + (res.message || 'فشل التسجيل'));
                    }
                }, 'json').fail(function(xhr) {
                    let errMsg = 'خطأ بالسيرفر';
                    if(xhr.responseJSON && xhr.responseJSON.message) errMsg = xhr.responseJSON.message;
                    alert('خطأ فادح: ' + errMsg);
                });
            });
        });
    </script>
</body>
</html>
