<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصيدلية - مستشفى الحاج المجاهد ابو جويدة المهندس</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-dark bg-success mb-4 shadow">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">💊 صرف الأدوية</span>
        </div>
    </nav>

    <div class="container">
        <h3 class="mb-4">الوصفات الطبية</h3>
        <div class="row" id="pharmaInfo">
            <!-- Dynamic Cards -->
        </div>
    </div>

    <!-- Dispense Modal -->
    <div class="modal fade" id="dispenseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">صرف الدواء</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="dispenseId">
                    <div class="mb-3">
                        <label class="form-label">تعليمات الاستخدام (للصيدلي)</label>
                        <textarea id="pharmInst" class="form-control" rows="3" placeholder="مثال: حبة قبل الأكل..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-success" onclick="confirmDispense()">تأكيد الصرف</button>
                </div>
            </div>
        </div>
    </div>

    <script src="jquery.min.js"></script>
    <script src="bootstrap.bundle.min.js"></script>
    <script>
        function fetchPharma() {
            $.getJSON('api.php?action=fetch_data', function(data) {
                let html = '';
                let list = data.filter(p => p.status === 'Pharmacy');

                if (list.length === 0) {
                    html = '<div class="col-12 text-center text-muted"><h3>لا توجد وصفات طبية</h3></div>';
                } else {
                    list.forEach(p => {
                        html += `<div class="col-md-6 mb-3">
                            <div class="card p-4 border-success">
                                <div class="d-flex justify-content-between">
                                    <h3>${p.name}</h3>
                                    <span class="badge bg-secondary">العمر: ${p.age}</span>
                                </div>
                                <hr>
                                <h4 class="text-primary">الدواء: ${p.medication}</h4>
                                <br>
                                <button class="btn btn-lg btn-success w-100" onclick="dispense(${p.id})">
                                    📦 تسليم وإنهاء
                                </button>
                            </div>
                        </div>`;
                    });
                }
                
                $('#pharmaInfo').html(html);
            });
        }

        function dispense(id) {
            $('#dispenseId').val(id);
            $('#pharmInst').val(''); // Reset
            var modal = new bootstrap.Modal(document.getElementById('dispenseModal'));
            modal.show();
        }

        function confirmDispense() {
            let id = $('#dispenseId').val();
            let inst = $('#pharmInst').val();
            
            $.post('api.php?action=update_patient', {
                id: id,
                status: 'Discharged',
                pharmacy_instructions: inst
            }, function() {
                // Manually hide modal by removing class/backdrop because we instantiated it via JS
                // Or simplified: location.reload() or just fetchPharma
                $('#dispenseModal').modal('hide'); // Requires Bootstrap 5 JS or jQuery wrapper
                // Force close if issues
                $('.modal-backdrop').remove();
                
                fetchPharma();
            });
        }

        setInterval(fetchPharma, 2000);
        fetchPharma();
    </script>
</body>
</html>
