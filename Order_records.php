<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>客戶訂貨紀錄管理</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

        .btn-add {
            background-color: #28a745; /* 綠色 */
        }

        .btn-refund {
            background-color: #dc3545; /* 紅色 */
        }

        .btn-search {
            background-color: #007bff; /* 藍色 */
        }

        .btn-calculate {
            background-color: #fd7e14; /* 橙色 */
        }

        .btn-sort {
            background-color: #6f42c1; /* 紫色 */
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

    // 處理新增客戶訂貨紀錄
    if (isset($_POST['add_order'])) {
        $customer_id = $_POST['customer_id'];
        $order_date = $_POST['order_date'];
        $expected_delivery_date = $_POST['expected_delivery_date'];
        $actual_delivery_date = $_POST['actual_delivery_date'];
        $product_name = $_POST['product_name'];
        $unit = $_POST['unit'];
        $quantity = $_POST['quantity'];
        $unit_price = $_POST['unit_price'];
        $order_amount = $quantity * $unit_price;
        $supplier_name = $_POST['supplier_name'];
        $supplier_id = $_POST['supplier_id'];

        $sql = "INSERT INTO 客戶訂貨紀錄 (訂貨身分證字號, 訂貨日期, 預計遞交日期, 實際遞交日期, 訂貨品名, 單位, 數量, 單價, 訂貨金額, 客戶訂貨供應商名稱, 訂貨供應商編號) 
                VALUES ('$customer_id', '$order_date', '$expected_delivery_date', '$actual_delivery_date', '$product_name', '$unit', $quantity, $unit_price, $order_amount, '$supplier_name', '$supplier_id')";

        if (mysqli_query($connection, $sql)) {
            echo "<p>新增客戶訂貨紀錄成功。</p>";
        } else {
            echo "新增客戶訂貨紀錄失敗：" . mysqli_error($connection);
        }
    }

    // 處理負值沖銷（退費）
    if (isset($_POST['process_refund'])) {
        $customer_id_refund = $_POST['customer_id_refund'];
        $refund_date = $_POST['refund_date'];
        $product_name_refund = "退費";
        $quantity_refund = -$_POST['quantity_refund'];
        $unit_price_refund = $_POST['unit_price_refund'];
        $unit = $_POST['unit'];
        $refund_amount = $quantity_refund * $unit_price_refund;
        $supplier_name_refund = $_POST['supplier_name_refund'];
        $supplier_id_refund = $_POST['supplier_id_refund'];

        $sql = "INSERT INTO 客戶訂貨紀錄 (訂貨身分證字號, 訂貨日期, 預計遞交日期, 實際遞交日期, 訂貨品名, 單位, 數量, 單價, 訂貨金額, 客戶訂貨供應商名稱, 訂貨供應商編號) 
                VALUES ('$customer_id_refund', '$refund_date', '$refund_date', '$refund_date', '$product_name_refund', '$unit', $quantity_refund, $unit_price_refund, $refund_amount, '$supplier_name_refund', '$supplier_id_refund')";

        if (mysqli_query($connection, $sql)) {
            echo "<p>退費成功。</p>";
        } else {
            echo "退費失敗：" . mysqli_error($connection);
        }
    }

    // 處理查詢客戶訂貨紀錄
    if (isset($_POST['search_records'])) {
        $search_customer_id = $_POST['search_customer_id'];

        $sql = "SELECT * FROM 客戶訂貨紀錄 WHERE 訂貨身分證字號 = '$search_customer_id'";
        $result = mysqli_query($connection, $sql);

        if ($result) {
            echo "<h2>查詢結果：</h2>";
            echo "<table border='1'>";
            echo "<tr><th>訂貨身分證字號</th><th>訂貨日期</th><th>預計遞交日期</th><th>實際遞交日期</th><th>訂貨品名</th><th>單位</th><th>數量</th><th>單價</th><th>訂貨金額</th><th>客戶訂貨供應商名稱</th><th>訂貨供應商編號</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['訂貨身分證字號'] . "</td>";
                echo "<td>" . $row['訂貨日期'] . "</td>";
                echo "<td>" . $row['預計遞交日期'] . "</td>";
                echo "<td>" . $row['實際遞交日期'] . "</td>";
                echo "<td>" . $row['訂貨品名'] . "</td>";
                echo "<td>" . $row['單位'] . "</td>";
                echo "<td>" . $row['數量'] . "</td>";
                echo "<td>" . $row['單價'] . "</td>";
                echo "<td>" . $row['訂貨金額'] . "</td>";
                echo "<td>" . $row['客戶訂貨供應商名稱'] . "</td>";
                echo "<td>" . $row['訂貨供應商編號'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "查詢客戶訂貨紀錄失敗：" . mysqli_error($connection);
        }
    }

    // 處理列印操作
    if (isset($_POST['print_records'])) {
        $search_customer_id = $_POST['search_customer_id'];

        $sql = "SELECT * FROM 客戶訂貨紀錄 WHERE 訂貨身分證字號 = '$search_customer_id'";
        $result = mysqli_query($connection, $sql);

        if ($result) {
            echo "<h2>查詢結果：</h2>";
            echo "<table border='1'>";
            echo "<tr><th>訂貨身分證字號</th><th>訂貨日期</th><th>預計遞交日期</th><th>實際遞交日期</th><th>訂貨品名</th><th>單位</th><th>數量</th><th>單價</th><th>訂貨金額</th><th>客戶訂貨供應商名稱</th><th>訂貨供應商編號</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['訂貨身分證字號'] . "</td>";
                echo "<td>" . $row['訂貨日期'] . "</td>";
                echo "<td>" . $row['預計遞交日期'] . "</td>";
                echo "<td>" . $row['實際遞交日期'] . "</td>";
                echo "<td>" . $row['訂貨品名'] . "</td>";
                echo "<td>" . $row['單位'] . "</td>";
                echo "<td>" . $row['數量'] . "</td>";
                echo "<td>" . $row['單價'] . "</td>";
                echo "<td>" . $row['訂貨金額'] . "</td>";
                echo "<td>" . $row['客戶訂貨供應商名稱'] . "</td>";
                echo "<td>" . $row['訂貨供應商編號'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            // 添加列印按鈕
            echo "<script>";
            echo "function printRecords() {";
            echo "   window.print();";
            echo "}";
            echo "</script>";
            echo "<br><button onclick='printRecords()'>列印</button>"; // 列印按鈕
        } else {
            echo "查詢客戶訂貨紀錄失敗：" . mysqli_error($connection);
        }
    }

    // 處理計算總金額操作
    if (isset($_POST['calculate_total'])) {
        $selected_week = $_POST['selected_week'];
        $customer_id = $_POST['customer_id'];

        // 解析星期和年份
        list($year, $week) = explode("-W", $selected_week);

        // 計算星期的開始和結束日期
        $start_date = date("Y-m-d", strtotime("{$year}-W{$week}"));
        $end_date = date("Y-m-d", strtotime("{$year}-W{$week} + 6 days"));

        $sql = "SELECT SUM(訂貨金額) AS 總金額 FROM 客戶訂貨紀錄 WHERE 訂貨身分證字號 = '$customer_id' 
                AND 訂貨日期 BETWEEN '$start_date' AND '$end_date'";
        
        $result = mysqli_query($connection, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $total_amount = $row['總金額'];
            echo "<p>選擇的星期 {$selected_week}，客戶 {$customer_id} 的訂貨總金額為：{$total_amount} 元。</p>";
        } else {
            echo "計算總金額失敗：" . mysqli_error($connection);
        }
    }

    // 處理計算某一星期全體客戶訂貨總金額操作
    if (isset($_POST['calculate_total_all'])) {
        $selected_week = $_POST['selected_week'];

        // 解析星期和年份
        list($year, $week) = explode("-W", $selected_week);

        // 計算星期的開始和結束日期
        $start_date = date("Y-m-d", strtotime("{$year}-W{$week}"));
        $end_date = date("Y-m-d", strtotime("{$year}-W{$week} + 6 days"));

        $sql = "SELECT SUM(訂貨金額) AS 總金額 FROM 客戶訂貨紀錄 WHERE 訂貨日期 BETWEEN '$start_date' AND '$end_date'";
        
        $result = mysqli_query($connection, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $total_amount = $row['總金額'];
            echo "<p>選擇的星期 {$selected_week}，全體客戶的訂貨總金額為：{$total_amount} 元。</p>";
        } else {
            echo "計算全體客戶訂貨總金額失敗：" . mysqli_error($connection);
        }
    }

    // 處理排序某一星期全體客戶每一人之訂貨總金額並列印操作
    if (isset($_POST['sort_and_print'])) {
        $selected_week_sort = $_POST['selected_week_sort'];

        // 解析星期和年份
        list($year, $week) = explode("-W", $selected_week_sort);

        // 計算星期的開始和結束日期
        $start_date = date("Y-m-d", strtotime("{$year}-W{$week}"));
        $end_date = date("Y-m-d", strtotime("{$year}-W{$week} + 6 days"));

        $sql = "SELECT 訂貨身分證字號, SUM(訂貨金額) AS 總金額 FROM 客戶訂貨紀錄 WHERE 訂貨日期 BETWEEN '$start_date' AND '$end_date' GROUP BY 訂貨身分證字號 ORDER BY 總金額 DESC";
        
        $result = mysqli_query($connection, $sql);

        if ($result) {
            echo "<h2>排序結果：</h2>";
            echo "<table border='1'>";
            echo "<tr><th>身分證字號</th><th>訂貨金額</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                $customer_id = $row['訂貨身分證字號'];
                $total_amount = $row['總金額'];
                
                // 可以根據需要進行進一步的查詢，以獲得客戶的其他信息（例如電話）

                echo "<tr>";
                echo "<td>" . $customer_id . "</td>";
                echo "<td>" . $total_amount . "</td>";
                // 顯示其他信息
                echo "</tr>";
            }

            echo "</table>";

            // 添加列印按鈕
            echo "<script>";
            echo "function printRecords() {";
            echo "   window.print();";
            echo "}";
            echo "</script>";
            echo "<br><button onclick='printRecords()'>列印</button>"; // 列印按鈕
        } else {
            echo "排序和列印失敗：" . mysqli_error($connection);
        }
    }

    mysqli_close($connection);
    ?>

    <div class="container">
        <h1>客戶訂貨紀錄管理</h1>
        <div class="button-container">
            <button class="btn btn-custom btn-add" onclick="showForm('addOrderForm')"><i class="fas fa-plus"></i>新增客戶訂貨紀錄</button>
            <button class="btn btn-custom btn-refund" onclick="showForm('refundForm')"><i class="fas fa-undo"></i>退費</button>
            <button class="btn btn-custom btn-search" onclick="showForm('searchForm')"><i class="fas fa-search"></i>查詢客戶訂貨紀錄</button>
            <button class="btn btn-custom btn-calculate" onclick="showForm('count_one')"><i class="fas fa-calculator"></i>計算某一星期某一位客戶訂貨總金額</button>
            <button class="btn btn-custom btn-calculate" onclick="showForm('count_all')"><i class="fas fa-chart-bar"></i>計算某一星期全體客戶訂貨總金額</button>
            <button class="btn btn-custom btn-sort" onclick="showForm('sort')"><i class="fas fa-sort-amount-down"></i>排序某一星期全體客戶訂貨總金額</button>
        </div>

    <!-- 新增客戶訂貨紀錄表單 -->
    <div id="addOrderForm" class="form-container">
        <div class="form-title">新增客戶訂貨紀錄</div>
        <form method="post">
            <label>訂貨身分證字號：</label>
            <input type="text" name="customer_id" required><br>
            <label>訂貨日期：</label>
            <input type="date" name="order_date" required><br>
            <label>預計遞交日期：</label>
            <input type="date" name="expected_delivery_date" required><br>
            <label>實際遞交日期：</label>
            <input type="date" name="actual_delivery_date" required><br>
            <label>訂貨品名：</label>
            <input type="text" name="product_name" required><br>
            <label>單位：</label>
            <input type="text" name="unit" required><br>
            <label>數量：</label>
            <input type="number" name="quantity" required><br>
            <label>單價：</label>
            <input type="number" name="unit_price" required><br>
            <label>客戶訂貨供應商名稱：</label>
            <input type="text" name="supplier_name" required><br>
            <label>訂貨供應商編號：</label>
            <input type="text" name="supplier_id" required><br>
            <input type="submit" name="add_order" value="新增客戶訂貨紀錄">
        </form>
    </div>

    <!-- 退費表單 -->
    <div id="refundForm" class="form-container">
        <div class="form-title">退費</div>
        <form method="post">
            <label>訂貨身分證字號：</label>
            <input type="text" name="customer_id_refund" required><br>
            <label>退費日期：</label>
            <input type="date" name="refund_date" required><br>
            <label>數量：</label>
            <input type="number" name="quantity_refund" required><br>
            <label>單價：</label>
            <input type="number" name="unit_price_refund" required><br>
            <input type="hidden" name="unit" value=""> <!-- 隱藏的單位字段，將在處理退費時填充 -->
            <label>客戶訂貨供應商名稱：</label>
            <input type="text" name="supplier_name_refund" required><br>
            <label>訂貨供應商編號：</label>
            <input type="text" name="supplier_id_refund" required><br>
            <input type="submit" name="process_refund" value="退費">
        </form>
    </div>

    <!-- 在查詢客戶訂貨紀錄表單中添加列印按鈕 -->
    <div id="searchForm" class="form-container">
        <div class="form-title">查詢客戶訂貨紀錄</div>
        <form method="post">
            <label>身分證字號：</label>
            <input type="text" name="search_customer_id" required><br>
            <input type="submit" name="search_records" value="查詢">
            
            <!-- 新增列印按鈕 -->
            <input type="submit" name="print_records" value="列印查詢結果">
        </form>
    </div>

    <!-- 計算某一星期某一位客戶訂貨總金額 -->
    <div id="count_one" class="form-container">
        <div class="form-title">計算某一星期某一位客戶訂貨總金額</div>
        <form method="post">
            <label>選擇星期：</label>
            <input type="week" name="selected_week" required><br>
            <label>輸入身分證字號：</label>
            <input type="text" name="customer_id" required><br>
            <input type="submit" name="calculate_total" value="計算總金額">
        </form>
    </div>

    <!-- 計算某一星期全體客戶訂貨總金額 -->
    <div id="count_all" class="form-container">
        <div class="form-title">計算某一星期全體客戶訂貨總金額</div>
        <form method="post">
            <label>選擇星期：</label>
            <input type="week" name="selected_week" required><br>
            <input type="submit" name="calculate_total_all" value="計算全體客戶訂貨總金額">
        </form>
    </div>

    <!-- 排序某一星期全體客戶訂貨總金額並列印 -->
    <div id="sort" class="form-container">
        <div class="form-title">排序某一星期全體客戶訂貨總金額</div>
        <form method="post">
            <label>選擇星期：</label>
            <input type="week" name="selected_week_sort" required><br>
            <input type="submit" name="sort_and_print" value="排序和列印">
        </form>
    </div>
    </div>

    <script>
        function showForm(formId) {
            // 隱藏所有表單
            var forms = document.getElementsByClassName('form-container');
            for (var i = 0; i < forms.length; i++) {
                forms[i].style.display = 'none';
            }

            // 顯示指定的表單
            document.getElementById(formId).style.display = 'block';
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>

</html>
