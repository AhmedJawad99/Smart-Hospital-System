<?php
$id = $_GET['id'] ?? null;
if (!$id) die("يرجى مسح كود QR للدخول لهذه الصفحة.");
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ملفي الطبي - مستشفى الحاج المجاهد ابو جويدة المهندس</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <style>
        body { background: #fff; }
    </style>
</head>
<body>
    <div class="container py-4">
        <h2 class="text-center mb-4">أهلاً بك، <span id="pName">مريض</span></h2>
        
        <div class="card bg-light p-3 mb-4">
            <h5 class="text-muted mb-4">مسار رحلة العلاج</h5>
            <div class="timeline" id="medicalTimeline">
                <div class="timeline-item" id="step_Waiting">
                    <div class="timeline-content">
                        <h6 class="mb-1">🏢 الاستقبال والانتظار</h6>
                        <small class="text-muted">تسجيل الدخول وانتظار الدور</small>
                    </div>
                </div>
                <div class="timeline-item" id="step_With_Doctor">
                    <div class="timeline-content">
                        <h6 class="mb-1">👨‍⚕️ الكشف الطبي</h6>
                        <small class="text-muted">عند الطبيب للتشخيص والعلاج</small>
                    </div>
                </div>
                <!-- Logic: We combine Lab/Pharm into generic steps or dynamic -->
                <div class="timeline-item" id="step_In_Lab">
                    <div class="timeline-content">
                        <h6 class="mb-1">🧪 المختبر والتحاليل</h6>
                        <small class="text-muted">إجراء الفحوصات اللازمة</small>
                    </div>
                </div>
                <div class="timeline-item" id="step_Pharmacy">
                    <div class="timeline-content">
                        <h6 class="mb-1">💊 الصيدلية</h6>
                        <small class="text-muted">استلام الأدوية</small>
                    </div>
                </div>
                <div class="timeline-item" id="step_Discharged">
                    <div class="timeline-content">
                        <h6 class="mb-1">🏠 المغادرة</h6>
                        <small class="text-muted">نتمنى لكم الشفاء العاجل</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-3 mb-5">
            <h5>التفاصيل الطبية الشاملة:</h5>
            <div class="alert alert-light border">
                <strong>البيانات الشخصية:</strong> <span id="pBasicInfo">--</span>
            </div>
            
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>فصيلة الدم / الحساسية:</strong> 
                    <span id="pBlood" class="badge bg-danger">--</span> | <span id="pAllergies" class="text-danger">--</span>
                </li>
                <li class="list-group-item"><strong>التشخيص الطبي:</strong> <span id="pDiag" class="text-muted">--</span></li>
                <li class="list-group-item"><strong>نتائج المختبر:</strong> <span id="pLab" class="text-info">--</span></li>
                <li class="list-group-item"><strong>العلامات الحيوية (عند الخروج):</strong> 
                    ❤️ <span id="pHr">--</span> | 🌡️ <span id="pTemp">--</span>
                </li>
                <li class="list-group-item"><strong>الوصفة الطبية:</strong> <span id="pMeds" class="text-success fw-bold">--</span></li>
                <li class="list-group-item"><strong>تعليمات الصيدلي:</strong> <span id="pInstructions" class="text-primary">--</span></li>
            </ul>
        </div>

        <div class="panic-btn-container">
            <button id="btnNurse" class="btn btn-danger panic-btn">🔔</button>
            <p id="btnNurseLabel" class="mt-2 text-danger fw-bold">نداء الممرض</p>
        </div>
    </div>

    <script src="jquery.min.js"></script>
    <script>
        const pid = <?php echo $id; ?>;
        let isNurseCalled = false;
        
        const statusMap = {
            'Waiting': 'انتظار',
            'With_Doctor': 'عند الطبيب',
            'In_Lab': 'في المختبر',
            'Pharmacy': 'الصيدلية',
            'Discharged': 'مغادرة'
        };

        function updateView() {
            $.getJSON('api.php?action=fetch_patient&id=' + pid, function(p) {
                $('#pName').text(p.name);
                
                let arStatus = statusMap[p.status] || p.status;
                $('#pStatus').text(arStatus);
                
                let genderText = (p.gender === 'Female') ? 'أنثى' : 'ذكر';
                $('#pBasicInfo').text(`${p.age} سنة | ${genderText} ${p.phone ? '| 📞 ' + p.phone : ''}`);
                
                $('#pBlood').text(p.blood_type || 'غير محدد');
                $('#pAllergies').text(p.allergies || 'لا يوجد');
                
                $('#pDiag').text(p.doctor_diagnosis || '--');
                $('#pMeds').text(p.medication || '--');
                $('#pInstructions').text(p.pharmacy_instructions || 'لا توجد تعليمات إضافية');
                $('#pLab').text(p.lab_result || 'لم تجرى تحاليل');
                
                if(p.heart_rate > 0) {
                    $('#pHr').text(p.heart_rate + ' bpm');
                    $('#pTemp').text(p.temperature + ' °C');
                } else {
                    $('#pHr').text('--');
                    $('#pTemp').text('--');
                }

                // Timeline Logic
                $('.timeline-item').removeClass('active completed');
                const steps = ['Waiting', 'With_Doctor', 'In_Lab', 'Pharmacy', 'Discharged'];
                let currentIndex = steps.indexOf(p.status);
                
                if (currentIndex === -1) currentIndex = 0; // Default if unknown

                steps.forEach((step, index) => {
                    let el = $(`#step_${step}`);
                    if (index < currentIndex) {
                        el.addClass('completed');
                        // Add checkmark to content if not present
                        if(el.find('.check-icon').length === 0) el.find('h6').prepend('<span class="check-icon text-success float-end">✅</span>');
                    } else if (index === currentIndex) {
                        el.addClass('active');
                        // Remove checkmark if backtracking
                        el.find('.check-icon').remove();
                    } else {
                         el.find('.check-icon').remove();
                    }
                });

                // Nurse Button State
                isNurseCalled = (p.nurse_call == 1);
                if(isNurseCalled) {
                    $('#btnNurse').removeClass('btn-danger disabled').addClass('btn-warning').css('animation', 'none').text('🔕');
                    $('#btnNurseLabel').text('إلغاء النداء');
                } else {
                    $('#btnNurse').removeClass('btn-warning disabled').addClass('btn-danger').css('animation', 'pulse 2s infinite').text('🔔');
                    $('#btnNurseLabel').text('نداء الممرض');
                }
            });
        }

        $('#btnNurse').click(function() {
            let newVal = isNurseCalled ? 0 : 1;
            let actionText = isNurseCalled ? "تم إلغاء النداء." : "تم إرسال النداء للممرض!";
            
            $.post('api.php?action=toggle_nurse_call', { id: pid, nurse_call: newVal }, function() {
                alert(actionText);
                updateView();
            });
        });

        setInterval(updateView, 3000);
        updateView();
    </script>
</body>
</html>
