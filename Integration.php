<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>客戶訂貨金額查詢</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans TC', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 20px;
        }

        button {
            padding: 8px 15px;
            font-size: 0.9em;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease, opacity 0.3s ease;
            margin: 5px;
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
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            opacity: 0.85;
        }

        .btn-query {
            background-color: #17a2b8;
        }

        .btn-count {
            background-color: #ffc107;
        }

        .btn-sort {
            background-color: #28a745;
        }
    </style>
</head>
<body>

    <h1>跨資料庫查詢</h1>
    
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database_name = "生鮮食品公司";

    $connection = mysqli_connect($servername, $username, $password, $database_name);
    if (!$connection) {
        die("連接失敗: " . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['query_customer_orders'])) {
            $customer_name = mysqli_real_escape_string($connection, $_POST['customer_name']);
            $sql = "SELECT 客戶姓名, YEAR(訂貨日期) AS 年份, MONTH(訂貨日期) AS 月份, WEEK(訂貨日期) AS 周次, SUM(訂貨金額) AS 總金額
                    FROM 客戶訂貨紀錄
                    JOIN 客戶基本資料 ON 客戶訂貨紀錄.訂貨身分證字號 = 客戶基本資料.身分證字號
                    WHERE 客戶姓名 = '$customer_name'
                    GROUP BY 客戶姓名, 年份, 月份, 周次";
            $result = mysqli_query($connection, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table border='1'><tr><th>客戶姓名</th><th>年份</th><th>月份</th><th>周次</th><th>總金額</th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>".$row["客戶姓名"]."</td><td>".$row["年份"]."</td><td>".$row["月份"]."</td><td>".$row["周次"]."</td><td>".$row["總金額"]."</td></tr>";
                }
                echo "</table>";
            } else {
                echo "未找到相符的客戶訂貨資料。";
            }
        }

        if (isset($_POST['query_supplier_orders'])) {
            $supplier_name = mysqli_real_escape_string($connection, $_POST['supplier_name']);
            $sql = "SELECT 公司進貨供應商名稱, DATE(進貨日期) AS 日期, WEEK(進貨日期) AS 星期, SUM(進貨數量 * 進貨單價) AS 總金額
                    FROM 公司進貨
                    WHERE 公司進貨供應商名稱 = '$supplier_name'
                    GROUP BY 公司進貨供應商名稱, 日期, 星期";
            $result = mysqli_query($connection, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table border='1'><tr><th>供應商名稱</th><th>日期</th><th>星期</th><th>總金額</th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>".$row["公司進貨供應商名稱"]."</td><td>".$row["日期"]."</td><td>".$row["星期"]."</td><td>".$row["總金額"]."</td></tr>";
                }
                echo "</table>";
            } else {
                echo "未找到相符的供應商進貨資料。";
            }
        }

        if (isset($_POST['query_supplier_item'])) {
            $supplier_name = mysqli_real_escape_string($connection, $_POST['supplier_name']);
            $item_name = mysqli_real_escape_string($connection, $_POST['item_name']);
            $sql = "SELECT 公司進貨供應商名稱, 進貨品名, DATE(進貨日期) AS 日期, WEEK(進貨日期) AS 星期, 進貨數量, SUM(進貨數量 * 進貨單價) AS 總金額
                    FROM 公司進貨
                    WHERE 公司進貨供應商名稱 = '$supplier_name' AND 進貨品名 = '$item_name'
                    GROUP BY 公司進貨供應商名稱, 進貨品名, 日期, 星期";
            $result = mysqli_query($connection, $sql);
            if ($result && mysqli_num_rows($result) > 0) {
                echo "<table border='1'><tr><th>供應商名稱</th><th>進貨物品名稱</th><th>日期</th><th>星期</th><th>進貨數量</th><th>總金額</th></tr>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>".$row["公司進貨供應商名稱"]."</td><td>".$row["進貨品名"]."</td><td>".$row["日期"]."</td><td>".$row["星期"]."</td><td>".$row["進貨數量"]."</td><td>".$row["總金額"]."</td></tr>";
                }
                echo "</table>";
            } else {
                echo "未找到相符的供應商進貨物品資料。";
            }
        }
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['query_weekly_customer_total'])) {
        $weekly_customer_name = mysqli_real_escape_string($connection, $_POST['weekly_customer_name']);
        $week_number = mysqli_real_escape_string($connection, $_POST['week_number']);

        $sql = "SELECT 客戶姓名, SUM(訂貨金額) AS 總金額
                FROM 客戶訂貨紀錄
                JOIN 客戶基本資料 ON 客戶訂貨紀錄.訂貨身分證字號 = 客戶基本資料.身分證字號
                WHERE WEEK(訂貨日期) = $week_number AND 客戶姓名 = '$weekly_customer_name'
                GROUP BY 客戶姓名";

        $result = mysqli_query($connection, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            echo "<p>客戶 <strong>".$row["客戶姓名"]."</strong> 在第 <strong>".$week_number."</strong> 周的訂貨總金額為：<strong>".$row["總金額"]."</strong>。</p>";
        } else {
            echo "未找到客戶在該周的訂貨資料。";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['query_sorted_weekly_total'])) {
        $sorted_week_number = mysqli_real_escape_string($connection, $_POST['sorted_week_number']);

        $sql = "SELECT 客戶基本資料.客戶姓名, 客戶基本資料.電話, SUM(訂貨金額) AS 總金額
                FROM 客戶訂貨紀錄
                JOIN 客戶基本資料 ON 客戶訂貨紀錄.訂貨身分證字號 = 客戶基本資料.身分證字號
                WHERE WEEK(訂貨日期) = $sorted_week_number
                GROUP BY 客戶基本資料.客戶姓名, 客戶基本資料.電話
                ORDER BY 總金額 DESC";

        $result = mysqli_query($connection, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            echo "<table border='1'><tr><th>客戶姓名</th><th>電話</th><th>訂貨總金額</th></tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>".$row["客戶姓名"]."</td><td>".$row["電話"]."</td><td>".$row["總金額"]."</td></tr>";
            }
            echo "</table>";
        } else {
            echo "未找到該星期的客戶訂貨資料。";
        }
    }

    mysqli_close($connection);
    ?>

    <div class="button-container">
        <button class="btn btn-custom btn-query" onclick="showForm('search_client')"><i class="fas fa-search"></i> 客戶訂貨金額查詢</button>
        <button class="btn btn-custom btn-query" onclick="showForm('search_supplier')"><i class="fas fa-truck"></i> 供應商進貨總金額查詢</button>
        <button class="btn btn-custom btn-query" onclick="showForm('search_item')"><i class="fas fa-box"></i> 供應商進貨物品查詢</button>
        <button class="btn btn-custom btn-count" onclick="showForm('count_one')"><i class="fas fa-calculator"></i> 計算某一星期客戶訂貨總金額</button>
        <button class="btn btn-custom btn-sort" onclick="showForm('count_all')"><i class="fas fa-sort-amount-down"></i> 某一星期客戶訂貨總金額排序</button>
    </div>

    <div id="search_client" class="form-container">
        <form method="post">
            <label>客戶姓名：</label>
            <input type="text" name="customer_name" required>
            <input type="submit" name="query_customer_orders" value="查詢">
        </form>
    </div>

    <div id="search_supplier" class="form-container">
        <form method="post">
            <label>供應商名稱：</label>
            <input type="text" name="supplier_name" required>
            <input type="submit" name="query_supplier_orders" value="查詢">
        </form>
    </div>

    <div id="search_item" class="form-container">
        <form method="post">
            <label>供應商名稱：</label>
            <input type="text" name="supplier_name" required>
            <label>進貨物品名稱：</label>
            <input type="text" name="item_name" required>
            <input type="submit" name="query_supplier_item" value="查詢">
        </form>
    </div> 
    
    <div id="count_one" class="form-container">
        <form method="post">
            <label>客戶姓名：</label>
            <input type="text" name="weekly_customer_name" required>
            <label>星期數：</label>
            <select name="week_number" required>
                <?php
                for ($week = 1; $week <= 52; $week++) {
                    echo "<option value='$week'>$week</option>";
                }
                ?>
            </select>
            <input type="submit" name="query_weekly_customer_total" value="查詢">
        </form>
    </div>

    <div id="count_all" class="form-container">
        <form method="post">
            <label>星期數：</label>
            <select name="sorted_week_number" required>
                <?php
                for ($week = 1; $week <= 52; $week++) {
                    echo "<option value='$week'>$week</option>";
                }
                ?>
            </select>
            <input type="submit" name="query_sorted_weekly_total" value="查詢">
        </form>
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
