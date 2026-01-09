<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>عيادة الطبيب - مستشفى الحاج المجاهد ابو جويدة المهندس</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <div class="nurse-alert alert alert-danger shadow-lg border-danger">
        <strong>🚨 نداء ممرض!</strong> <span id="alert-msg">مريض يحتاج للمساعدة!</span>
    </div>

    <nav class="navbar navbar-dark bg-success mb-4 shadow">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">🩺 عيادة الطبيب</span>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Patient Queue -->
            <div class="col-md-3 border-end">
                <h5 class="text-secondary mb-3">قائمة المرضى</h5>
                <div class="list-group" id="queueList">
                    <!-- Loaded via AJAX -->
                </div>
            </div>

            <!-- Active File -->
            <div class="col-md-9" id="workspace" style="display:none;">
                <div class="row mb-4">
                    <!-- Vital Signs Simulator -->
                    <div class="col-md-12">
                        <div class="monitor-panel d-flex justify-content-between align-items-center">
                            <div>
                                <div class="monitor-label">HEART RATE (BPM)</div>
                                <div class="monitor-value text-danger">❤️ <span id="sim-hr">--</span></div>
                            </div>
                            <div>
                                <div class="monitor-label">TEMP (°C)</div>
                                <div class="monitor-value text-warning">🌡️ <span id="sim-temp">--</span></div>
                            </div>
                            <div>
                                <div class="monitor-label">SPO2 (%)</div>
                                <div class="monitor-value text-info">💧 <span id="sim-spo2">--</span></div>
                            </div>
                            <div class="text-end">
                                <small class="text-muted">LIVE MONITORING</small><br>
                                <span class="badge bg-success animate-blink">ONLINE</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <h2 id="pNameDisplay">اسم المريض</h2>
                        <span class="badge bg-secondary fs-6" id="pAgeDisplay">العمر: --</span>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">فصيلة الدم</label>
                            <select id="bloodType" class="form-select">
                                <option value="">غير محدد</option>
                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-danger">⚠️ الحساسية</label>
                            <input type="text" id="allergies" class="form-control" placeholder="مثل: البنسلين، الأسبرين...">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">التشخيص / الملاحظات</label>
                            <textarea id="diagnosis" class="form-control" rows="4" placeholder="اكتب التشخيص هنا..."></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">نتائج المختبر</label>
                            <div id="labResultDisplay" class="alert alert-info" style="min-height: 120px;">
                                <em>لا توجد نتائج حتى الآن...</em>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">طلب تحليل</span>
                                <div class="form-control" style="height: auto; max-height: 150px; overflow-y: auto;">
                                    <div class="form-check">
                                        <input class="form-check-input lab-check" type="checkbox" value="CBC Blood Test" id="lab1">
                                        <label class="form-check-label" for="lab1">تحليل دم شامل (CBC)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input lab-check" type="checkbox" value="X-Ray Chest" id="lab2">
                                        <label class="form-check-label" for="lab2">أشعة صدر (X-Ray)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input lab-check" type="checkbox" value="MRI Scan" id="lab3">
                                        <label class="form-check-label" for="lab3">رنين مغناطيسي (MRI)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input lab-check" type="checkbox" value="Urinalysis" id="lab4">
                                        <label class="form-check-label" for="lab4">تحليل ادرار</label>
                                    </div>
                                </div>
                                <button class="btn btn-warning" onclick="sendToLab()">طلب</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">وصفة</span>
                                <input type="text" class="form-control" id="medication" placeholder="اسم الدواء">
                                <button class="btn btn-success" onclick="prescribe()">صرف</button>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <button class="btn btn-primary w-100" onclick="discharge()">إنهاء ومغادرة</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-9 text-center mt-5" id="emptyState">
                <h3 class="text-muted">اختر مريضاً من القائمة للبدء</h3>
            </div>
        </div>
    </div>

    <script src="jquery.min.js"></script>
    <script>
        let currentPatientId = null;

        function refreshQueue() {
            $.getJSON('api.php?action=fetch_data', function(data) {
                let html = '';
                let nurseCallActive = false;
                
                let relevant = data.filter(p => ['Waiting', 'With_Doctor', 'In_Lab'].includes(p.status));

                relevant.forEach(p => {
                    if (p.nurse_call == 1) {
                        nurseCallActive = true;
                        $('#alert-msg').text(`المريض #${p.id} يحتاج مساعدة!`);
                    }

                    let activeClass = (currentPatientId == p.id) ? 'active' : '';
                    let badge = '';
                    if (p.status === 'In_Lab') badge = '<span class="badge bg-warning float-end">في المختبر</span>';
                    
                    let triageBadge = '';
                    if (p.triage_level === 'Critical') {
                        triageBadge = '<span class="badge bg-danger ms-2">حالة حرجة</span>';
                    }

                    html += `<a href="#" class="list-group-item list-group-item-action ${activeClass}" onclick="loadPatient(${p.id})">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">${p.name} ${triageBadge}</h5>
                            <small>#${p.id}</small>
                        </div>
                        <p class="mb-1">العمر: ${p.age}</p>
                        ${badge}
                    </a>`;
                });
                $('#queueList').html(html);

                if (currentPatientId) {
                    let activeData = data.find(p => p.id == currentPatientId);
                    if (activeData && activeData.lab_result) {
                        $('#labResultDisplay').html(`<strong>النتيجة:</strong> ${activeData.lab_result}`);
                    }
                }

                if (nurseCallActive) $('.nurse-alert').fadeIn();
                else $('.nurse-alert').fadeOut();
            });
        }

        function loadPatient(id) {
            currentPatientId = id;
            $('#workspace').show();
            $('#emptyState').hide();
            
            // Mobile: Scroll to workspace
            if ($(window).width() < 768) {
                $('html, body').animate({
                    scrollTop: $("#workspace").offset().top - 20
                }, 500);
            }
            
            $.getJSON('api.php?action=fetch_patient&id='+id, function(p) {
                let triageInfo = (p.triage_level === 'Critical') 
                    ? ' <span class="badge bg-danger">حالة حرجة</span>' 
                    : ' <span class="badge bg-success">طبيعي</span>';

                $('#pNameDisplay').html(p.name + triageInfo);
                
                let genderText = (p.gender === 'Female') ? 'أنثى' : 'ذكر';
                let phoneText = p.phone ? ` | 📞 ${p.phone}` : '';
                $('#pAgeDisplay').html(`العمر: ${p.age} | الجنس: ${genderText}${phoneText}`);
                
                $('#diagnosis').val(p.doctor_diagnosis);
                $('#bloodType').val(p.blood_type || '');
                $('#allergies').val(p.allergies || '');
                
                $('#labResultDisplay').html(p.lab_result ? `<strong>النتيجة:</strong> ${p.lab_result}` : '<em>لا توجد نتائج حتى الآن...</em>');
                
                if(p.status === 'Waiting') {
                    updateStatus('With_Doctor');
                }
            });
        }

        function updateStatus(status, extraData = {}) {
            let data = { id: currentPatientId, status: status, ...extraData };
            $.post('api.php?action=update_patient', data, function() {
                refreshQueue();
            });
        }

        function sendToLab() {
            let selectedTests = [];
            $('.lab-check:checked').each(function() {
                selectedTests.push($(this).val());
            });

            if(selectedTests.length === 0) return alert('الرجاء اختيار تحليل واحد على الأقل!');
            
            let req = selectedTests.join(' + '); // Join with + for readability
            let diag = $('#diagnosis').val();
            let blood = $('#bloodType').val();
            let allergy = $('#allergies').val();
            
            updateStatus('In_Lab', { 
                lab_request: req, 
                doctor_diagnosis: diag,
                blood_type: blood,
                allergies: allergy
            });
            
            // Uncheck after sending
            $('.lab-check').prop('checked', false);
            alert('تم إرسال الطلبات للمختبر!');
        }

        function prescribe() {
            let meds = $('#medication').val();
            let diag = $('#diagnosis').val();
            let blood = $('#bloodType').val();
            let allergy = $('#allergies').val();

            if(!meds) return alert('الرجاء كتابة الدواء!');
            updateStatus('Pharmacy', { 
                medication: meds, 
                doctor_diagnosis: diag,
                blood_type: blood,
                allergies: allergy 
            });
            alert('تم الإرسال للصيدلة!');
            currentPatientId = null;
            $('#workspace').hide();
            $('#emptyState').show();
        }

        function discharge() {
            // Capture current vitals from the simulator
            let currentHR = $('#sim-hr').text();
            let currentTemp = $('#sim-temp').text();

            // Store details including vitals
            updateStatus('Discharged', { 
                heart_rate: currentHR, 
                temperature: currentTemp 
            });
            
            alert('تم حفظ السجل الطبي والمغادرة.');
            currentPatientId = null;
            $('#workspace').hide();
            $('#emptyState').show();
        }

        setInterval(() => {
            if ($('#workspace').is(':visible')) {
                let hr = 70 + Math.floor(Math.random() * 15);
                let temp = (36.5 + Math.random() * 1).toFixed(1);
                let spo2 = 96 + Math.floor(Math.random() * 4);

                $('#sim-hr').text(hr);
                $('#sim-temp').text(temp);
                $('#sim-spo2').text(spo2);
            }
        }, 1000);

        setInterval(refreshQueue, 2000);
        refreshQueue();
    </script>
</body>
</html>
