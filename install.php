<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تثبيت النظام</title>
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3>🛠️ تثبيت قاعدة بيانات مستشفى الحاج المجاهد ابو جويدة المهندس</h3>
            </div>
            <div class="card-body">
                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $host = 'localhost';
                    $user = 'root';
                    $pass = '';

                    try {
                        // Connect without DB first
                        $pdo = new PDO("mysql:host=$host", $user, $pass);
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        // Read SQL file
                        $sql = file_get_contents(__DIR__ . '/database_setup.sql');
                        
                        if ($sql) {
                            $pdo->exec($sql);
                            echo '<div class="alert alert-success">
                                    <h4>✅ تم التثبيت بنجاح!</h4>
                                    <p>تم إنشاء قاعدة البيانات `smart_hospital` والجداول.</p>
                                    <a href="index.php" class="btn btn-success">الذهاب للبوابة الرئيسية</a>
                                  </div>';
                        } else {
                            echo '<div class="alert alert-danger">خطأ: تعذر قراءة ملف `database_setup.sql`.</div>';
                        }

                    } catch (PDOException $e) {
                        echo '<div class="alert alert-danger">
                                <h4>❌ فشل التثبيت</h4>
                                <p>' . htmlspecialchars($e->getMessage()) . '</p>
                              </div>';
                    }
                } else {
                ?>
                    <p class="lead">اضغط الزر أدناه لإنشاء قاعدة البيانات والجداول تلقائياً.</p>
                    <form method="post">
                        <button type="submit" class="btn btn-primary btn-lg">بدء التثبيت</button>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
