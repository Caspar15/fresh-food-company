<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>公司進貨記錄</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <title>公司進貨</title>

    <script type="text/javascript">
        function printData() {
            var printContent = document.getElementById("print-table");
            var originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent.innerHTML;
            window.print();
            document.body.innerHTML = originalContent;
        }
        function calculateSubtotal() {
            var quantity = parseFloat(document.getElementById("quantity").value);
            var unitPrice = parseFloat(document.getElementById("unit_price").value);
            var subtotal = quantity * unitPrice;
            document.getElementById("subtotal").innerHTML = "小計：" + subtotal.toFixed(2);
        }
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .form-container.visible {
            animation: fadeIn 0.5s ease-in-out;
        }

        body {
            font-family: 'Noto Sans TC', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .container {
            padding-top: 20px;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .button-container button {
            padding: 8px 10px; /* 從15px減少到10px */
            font-size: 0.85em; /* 輕微減小字體大小 */
            margin: 3px; /* 減少按鈕之間的間距 */
        }

        .button-container button.btn-weekly-detail {
            padding: 8px 5px; /* 減少特定按鈕的內邊距 */
        }
        
        button {
            background-color: #007bff;
            color: white;
            padding: 8px 15px;
            font-size: 0.9em;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
            margin: 5px;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            margin: 20px auto;
            max-width: 500px;
            display: none;
        }

        .form-container.visible {
            display: block;
            opacity: 1;
        }

        .form-title {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        label {
            margin-bottom: 10px;
            display: block;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        select,
        input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        input[type="file"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 8px rgba(0,123,255,.5);
        }

        input[type="submit"] {
            width: 100%;
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .error-message,
        .success-message {
            color: #ffffff;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }

        .error-message {
            background-color: #f44336;
        }

        .success-message {
            background-color: #28a745;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .btn-custom {
            margin: 5px;
            transition: transform 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
        }

        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>

<body>

    <?php
    $servername = "localhost"; // MySQL 伺服器位址
    $username = "root";        // MySQL 使用者名稱
    $password = "";    // MySQL 使用者密碼
    $database_name = "生鮮食品公司"; // 資料庫名稱

    // 建立 MySQL 連接
    $connection = mysqli_connect($servername, $username, $password, $database_name);

    // 檢查連接是否成功
    if (!$connection) {
        die("連接失敗: " . mysqli_connect_error());
    }

    // 新增進貨資料
    if (isset($_POST['add_purchase'])) {
        $supplier_name = $_POST['supplier_name'];
        $supplier_id = $_POST['supplier_id'];
        $supplier_contact = $_POST['supplier_contact'];
        $product_name = $_POST['product_name'];
        $quantity = $_POST['quantity'];
        $unit = $_POST['unit'];
        $unit_price = $_POST['unit_price'];
        $location = $_POST['location'];
        $specification = $_POST['specification'];
        $purchase_date = $_POST['purchase_date'];

        $subtotal = $quantity * $unit_price;

        $checkSql = "SELECT * FROM 公司進貨 WHERE 供應商編號 = '$supplier_id'";
        $checkResult = mysqli_query($connection, $checkSql);
    
        if (mysqli_num_rows($checkResult) > 0) {
            echo "<div class='error-message'>此供應商編號已存在。</div>";
        } else {
            // 供應商編號不存在，可以進行插入操作
            $sql = "INSERT INTO 公司進貨 (公司進貨供應商名稱, 供應商編號, 供應商負責人, 進貨品名, 進貨數量, 進貨單位, 進貨單價, 庫存位置, 規格, 進貨日期) 
            VALUES ('$supplier_name', '$supplier_id', '$supplier_contact', '$product_name', $quantity, '$unit', $unit_price, '$location', '$specification', '$purchase_date')";

            if (mysqli_query($connection, $sql)) {
                echo "<div class='success-message'>新增進貨成功!</div>";
            } else {
                echo "<div class='error-message'>新增進貨失敗：" . mysqli_error($connection) . "</div>";
            }
        }
    }

    // 查詢進貨資料
    if (isset($_POST['search'])) {
        if (isset($_POST['product_name'])) {
            // 只有當它被設置時才訪問 'product_name' 字段
            $product_name = $_POST['product_name'];
            // 用於基於 'product_name' 進行搜索的查詢邏輯
        }
        $query = $_POST['query'];

        $sql = "SELECT * FROM 公司進貨 WHERE 進貨品名 LIKE '%$query%'";

        $result = mysqli_query($connection, $sql);

        if ($result) {
            echo "<h2>查詢結果：</h2>";
            echo "<table border='1'>";
            echo "<tr><th>供應商名稱</th><th>供應商編號</th><th>進貨品名</th><th>進貨數量</th><th>進貨單價</th><th>進貨日期</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['公司進貨供應商名稱'] . "</td>";
                echo "<td>" . $row['供應商編號'] . "</td>";
                echo "<td>" . $row['進貨品名'] . "</td>";
                echo "<td>" . $row['進貨數量'] . "</td>";
                echo "<td>" . $row['進貨單價'] . "</td>";
                echo "<td>" . $row['進貨日期'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "查詢失敗：" . mysqli_error($connection);
        }
    }

    //每日進貨總金額
    if (isset($_POST['search_daily'])) {
        $supplier_id = $_POST['daily_supplier_id'];
        $date = $_POST['daily_date'];
    
        $sql = "SELECT 供應商編號, 進貨日期, SUM(進貨數量 * 進貨單價) AS 日進貨總金額 
                FROM 公司進貨 
                WHERE 供應商編號 = '$supplier_id' AND 進貨日期 = '$date' 
                GROUP BY 供應商編號, 進貨日期";
    
        $result = mysqli_query($connection, $sql);
    
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<h2>每日進貨總金額：</h2>";
                echo "<table border='1'>";
                echo "<tr><th>供應商編號</th><th>進貨日期</th><th>日進貨總金額</th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['供應商編號'] . "</td>";
                    echo "<td>" . $row['進貨日期'] . "</td>";
                    echo "<td>" . $row['日進貨總金額'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "未找到相符的資料。";
            }
        } else {
            echo "查詢失敗：" . mysqli_error($connection);
        }
    }
    

    // 查詢每週進貨總金額
    if (isset($_POST['search_weekly'])) {
        $supplier_id = $_POST['weekly_supplier_id'];
        $weekInput = $_POST['weekly_week'];
    
        // 從週數中提取年份和週數
        $year = substr($weekInput, 0, 4);
        $week = substr($weekInput, 6);
    
        // 計算該週的起始日期和結束日期
        $startOfWeek = date('Y-m-d', strtotime($year."W".$week));
        $endOfWeek = date('Y-m-d', strtotime($startOfWeek . "+6 days"));
    
        // 準備 SQL 查詢
        $sql = "SELECT 供應商編號, SUM(進貨數量 * 進貨單價) AS 週進貨總金額 
                FROM 公司進貨 
                WHERE 供應商編號 = ? AND 進貨日期 >= ? AND 進貨日期 <= ? 
                GROUP BY 供應商編號";
    
        // 使用預處理語句來執行 SQL 查詢
        if ($stmt = mysqli_prepare($connection, $sql)) {
            // 綁定參數
            mysqli_stmt_bind_param($stmt, "sss", $supplier_id, $startOfWeek, $endOfWeek);
    
            // 執行查詢
            mysqli_stmt_execute($stmt);
    
            // 獲取結果
            $result = mysqli_stmt_get_result($stmt);
    
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo "<h2>每周進貨物品詳情：</h2>";
                    echo "<table border='1'>";
                    echo "<tr><th>供應商編號</th><th>週進貨總金額</th></tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['供應商編號'] . "</td>";
                        echo "<td>" . $row['週進貨總金額'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "未找到相符的資料。";
                }
            } else {
                echo "查詢失敗：" . mysqli_error($connection);
            }
    
            // 關閉語句
            mysqli_stmt_close($stmt);
        } else {
            echo "準備語句失敗：" . mysqli_error($connection);
        }
    }
    
    //每日進貨物品詳情
    if (isset($_POST['search_daily_detail'])) {
        $supplier_id = $_POST['daily_detail_supplier_id'];
        $date = $_POST['daily_detail_date'];
        $product_name = $_POST['daily_detail_product_name'];
    
        $sql = "SELECT 供應商編號, 進貨日期, 進貨品名, SUM(進貨數量) AS 日進貨數量, SUM(進貨數量 * 進貨單價) AS 日進貨總金額 
                FROM 公司進貨 
                WHERE 供應商編號 = '$supplier_id' AND 進貨日期 = '$date' AND 進貨品名 = '$product_name' 
                GROUP BY 供應商編號, 進貨日期, 進貨品名";
    
        $result = mysqli_query($connection, $sql);
    
        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<h2>每日進貨物品詳情：</h2>";
                echo "<table border='1'>";
                echo "<tr><th>供應商編號</th><th>進貨日期</th><th>進貨品名</th><th>日進貨數量</th><th>日進貨總金額</th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['供應商編號'] . "</td>";
                    echo "<td>" . $row['進貨日期'] . "</td>";
                    echo "<td>" . $row['進貨品名'] . "</td>";
                    echo "<td>" . $row['日進貨數量'] . "</td>";
                    echo "<td>" . $row['日進貨總金額'] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "未找到相符的資料。";
            }
        } else {
            echo "查詢失敗：" . mysqli_error($connection);
        }
    }
    

    //查詢每一供應商每日與每星期之某一進貨物品、進貨數量與該貨品之總金額
    if (isset($_POST['search_weekly_detail'])) {
        $supplier_id = $_POST['weekly_detail_supplier_id'];
        $weekInput = $_POST['weekly_detail_week'];
        $product_name = $_POST['weekly_detail_product_name'];
    
        // 從週數中提取年份和週數
        $year = substr($weekInput, 0, 4);
        $week = substr($weekInput, 6);
    
        // 計算該週的起始日期和結束日期
        $startOfWeek = date('Y-m-d', strtotime($year."W".$week));
        $endOfWeek = date('Y-m-d', strtotime($startOfWeek . "+6 days"));
    
        // 準備 SQL 查詢
        $sql = "SELECT 供應商編號, 進貨品名, SUM(進貨數量) AS 週進貨數量, SUM(進貨數量 * 進貨單價) AS 週進貨總金額 
                FROM 公司進貨 
                WHERE 供應商編號 = ? AND 進貨日期 >= ? AND 進貨日期 <= ? AND 進貨品名 = ? 
                GROUP BY 供應商編號, 進貨品名";
    
        // 使用預處理語句來執行 SQL 查詢
        if ($stmt = mysqli_prepare($connection, $sql)) {
            // 綁定參數
            mysqli_stmt_bind_param($stmt, "ssss", $supplier_id, $startOfWeek, $endOfWeek, $product_name);
    
            // 執行查詢
            mysqli_stmt_execute($stmt);
    
            // 獲取結果
            $result = mysqli_stmt_get_result($stmt);
    
            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    echo "<h2>每周進貨物品詳情：</h2>";
                    echo "<table border='1'>";
                    echo "<tr><th>供應商編號</th><th>進貨品名</th><th>週進貨數量</th><th>週進貨總金額</th></tr>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['供應商編號'] . "</td>";
                        echo "<td>" . $row['進貨品名'] . "</td>";
                        echo "<td>" . $row['週進貨數量'] . "</td>";
                        echo "<td>" . $row['週進貨總金額'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "未找到相符的資料。";
                }
            } else {
                echo "查詢失敗：" . mysqli_error($connection);
            }
    
            // 關閉語句
            mysqli_stmt_close($stmt);
        } else {
            echo "準備語句失敗：" . mysqli_error($connection);
        }
    }

    // 新增處理顯示所有進貨資料的代碼
    if (isset($_POST['show_all'])) {
        $sql = "SELECT * FROM 公司進貨";
        $result = mysqli_query($connection, $sql);
        if ($result) {
            $output = "<h2>所有進貨資料：</h2>";
            $output .= "<table border='1'>";
            $output .= "<tr><th>供應商名稱</th><th>供應商編號</th><th>進貨品名</th><th>進貨數量</th><th>進貨單價</th><th>進貨日期</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                $output .= "<tr>";
                $output .= "<td>" . $row['公司進貨供應商名稱'] . "</td>";
                $output .= "<td>" . $row['供應商編號'] . "</td>";
                $output .= "<td>" . $row['進貨品名'] . "</td>";
                $output .= "<td>" . $row['進貨數量'] . "</td>";
                $output .= "<td>" . $row['進貨單價'] . "</td>";
                $output .= "<td>" . $row['進貨日期'] . "</td>";
                $output .= "</tr>";
            }
            $output .= "</table>";
            echo $output;
            exit; // 結束 PHP 腳本的執行
        } else {
            echo "查詢失敗：" . mysqli_error($connection);
            exit; // 結束 PHP 腳本的執行
        }
    }
    

    mysqli_close($connection);
    ?>

    <div class="container">
        <h1>公司進貨記錄</h1>
        <div class="d-flex justify-content-center flex-wrap">
            <button class="btn btn-primary btn-custom" onclick="showForm('addPurchaseForm')"><i class="fas fa-plus"></i> 新增進貨資料</button>
            <button class="btn btn-secondary btn-custom" onclick="showForm('searchForm')"><i class="fas fa-search"></i> 查詢進貨資料</button>
            <button class="btn btn-success btn-custom" onclick="showForm('searchForm_daily')"><i class="fas fa-calendar-day"></i> 查詢每日進貨總金額</button>
            <button class="btn btn-info btn-custom" onclick="showForm('searchForm_week')"><i class="fas fa-calendar-week"></i> 查詢每週進貨總金額</button>
            <button class="btn btn-warning btn-custom" onclick="showForm('search_daily')"><i class="fas fa-box-open"></i> 查詢每日進貨物品詳情</button>
            <button class="btn btn-danger btn-custom" onclick="showForm('search_week')"><i class="fas fa-boxes"></i> 查詢每週進貨物品詳情</button>
            <button class="btn btn-dark btn-custom" onclick="showAllPurchases()"><i class="fas fa-list"></i> 顯示所有進貨資料</button>
        </div>

    <!-- 新增進貨資料 -->
    <div id="addPurchaseForm" class="form-container">
        <div class="form-title">新增進貨資料</div>
        <form method="post">
            <label>供應商名稱：</label>
            <input type="text" name="supplier_name" required maxlength="16">
            <label>供應商編號：</label>
            <input type="text" name="supplier_id" required maxlength="5">
            <label>供應商負責人：</label>
            <input type="text" name="supplier_contact" required maxlength="12">
            <label>進貨品名：</label>
            <input type="text" name="product_name" required maxlength="16">
            <label>進貨數量：</label>
            <input type="number" name="quantity" required maxlength="10,2">
            <label>進貨單位：</label>
            <input type="text" name="unit" required>
            <label>進貨單價：</label>
            <input type="number" id="unit_price" name="unit_price" required onchange="calculateSubtotal()">
            <span id="subtotal"></span>
            <label>庫存位置：</label>
            <input type="text" name="location" required maxlength="16">
            <label>規格：</label>
            <input type="text" name="specification" required maxlength="16">
            <label>進貨日期：</label>
            <input type="date" name="purchase_date" required>
            <input type="submit" name="add_purchase" value="新增進貨">
        </form>
    </div>
    
    <!-- 查詢進貨資料 -->
    <div id="searchForm" class="form-container">
        <div class="form-title">查詢進貨資料</div>
        <form method="post">
            <label>查詢進貨品名：</label>
            <input type="text" name="query" required>
            <input type="submit" name="search" value="查詢">
        </form>
    </div>

    <!-- 查詢每日進貨總金額 -->
    <div id="searchForm_daily" class="form-container">
        <div class="form-title">查詢每日進貨總金額</div>
        <form method="post">
            <label>供應商編號：</label>
            <input type="text" name="daily_supplier_id" required>
            <label>日期：</label>
            <input type="date" name="daily_date" required>
            <input type="submit" name="search_daily" value="查詢每日進貨總金額">
        </form>
    </div>

    <!-- 查詢每週進貨總金額 -->
    <div id="searchForm_week" class="form-container">
        <div class="form-title">查詢每週進貨總金額</div>
        <form method="post">
            <label>供應商編號：</label>
            <input type="text" name="weekly_supplier_id" required>
            <label>週數：</label>
            <input type="week" name="weekly_week" required>
            <input type="submit" name="search_weekly" value="查詢每週進貨總金額">
        </form>
    </div>

    <!-- 查詢每日進貨物品詳情 -->
    <div id="search_daily" class="form-container">
        <div class="form-title">查詢每日進貨物品詳情</div>
        <form method="post">
            <label>供應商編號：</label>
            <input type="text" name="daily_detail_supplier_id" required>
            <label>日期：</label>
            <input type="date" name="daily_detail_date" required>
            <label>進貨品名：</label>
            <input type="text" name="daily_detail_product_name" required>
            <input type="submit" name="search_daily_detail" value="查詢每日進貨物品詳情">
        </form>
    </div>

    <!-- 查詢每週進貨物品詳情 -->
    <div id="search_week" class="form-container">
        <div class="form-title">查詢每週進貨物品詳情</div>
        <form method="post">
            <label>供應商編號：</label>
            <input type="text" name="weekly_detail_supplier_id" required>
            <label>週數：</label>
            <input type="week" name="weekly_detail_week" required>
            <label>進貨品名：</label>
            <input type="text" name="weekly_detail_product_name" required>
            <input type="submit" name="search_weekly_detail" value="查詢每週進貨物品詳情">
        </form>
    </div>

        <!-- 新增顯示所有進貨資料的區塊 -->
        <div id="allPurchases"></div>
    </div>

    <script>
        function showForm(formId) {
            // 隱藏所有表單
            var forms = document.getElementsByClassName('form-container');
            for (var i = 0; i < forms.length; i++) {
                forms[i].style.display = 'none';
            }

            // 隱藏或清除「所有進貨資料」的顯示區塊
            var allPurchasesDiv = document.getElementById("allPurchases");
            if (allPurchasesDiv) {
                // 選擇清除內容或隱藏元素
                // allPurchasesDiv.innerHTML = ''; // 清除內容
                allPurchasesDiv.style.display = 'none'; // 隱藏元素
            }

            // 顯示指定的表單
            document.getElementById(formId).style.display = 'block';
        }
        <!-- 新增顯示所有進貨資料的 JavaScript 函數 -->
        function showAllPurchases() {
            // 確保所有表單被隱藏
            var forms = document.getElementsByClassName('form-container');
            for (var i = 0; i < forms.length; i++) {
                forms[i].style.display = 'none';
            }

            // 準備 AJAX 請求
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "Company_purchase.php", true); // 確保 URL 正確
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // 處理 AJAX 響應
            xhr.onreadystatechange = function() {
                if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
                    var allPurchasesDiv = document.getElementById("allPurchases");
                    allPurchasesDiv.innerHTML = this.responseText;
                    allPurchasesDiv.style.display = 'block'; // 確保顯示容器
                }
            }

            // 發送 AJAX 請求
            xhr.send("show_all=1");
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
