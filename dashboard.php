<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>生鮮食品公司管理系統</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans TC', sans-serif;
            background: linear-gradient(to right, #6dd5ed, #2193b0);
            color: #333;
            padding-top: 20px;
        }

        .card-container {
            padding: 20px;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .status-bar {
            background-color: #007bff;
            color: white;
            padding: 10px 0;
            margin-bottom: 30px;
        }

        .status-item {
            margin: 0 15px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>

    <div class="status-bar text-center">
        <span class="status-item">管理者：Admin</span>
        <span class="status-item">現在時間：<span id="time"></span></span>
    </div>

    <div class="container">
        <div class="row card-container">
            <div class="col-md-4 mb-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">客戶基本資料</h5>
                        <p class="card-text">管理和查看所有客戶信息。</p>
                        <a href="Customer_basic.php" class="btn btn-primary">前往</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">公司進貨資料</h5>
                        <p class="card-text">查看和管理進貨記錄。</p>
                        <a href="Company_purchase.php" class="btn btn-primary">前往</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">客戶訂貨記錄</h5>
                        <p class="card-text">檢視所有客戶訂單詳情。</p>
                        <a href="Order_records.php" class="btn btn-primary">前往</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">公司應收帳款</h5>
                        <p class="card-text">管理應收帳款資訊。</p>
                        <a href="Receivable.php" class="btn btn-primary">前往</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card h-100 text-center">
                    <div class="card-body">
                        <h5 class="card-title">跨資料庫整合</h5>
                        <p class="card-text">整合和分析多個資料庫信息。</p>
                        <a href="Integration.php" class="btn btn-primary">前往</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // 更新時間的函數
        function updateTime() {
            document.getElementById('time').innerText = new Date().toLocaleTimeString();
        }
        setInterval(updateTime, 1000); // 每秒更新時間
        updateTime(); // 初始化時間
    </script>
</body>
</html>