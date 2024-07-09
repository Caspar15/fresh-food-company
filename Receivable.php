<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>新增應收帳款資料</title>
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

        .btn-add {
            background-color: #28a745; /* 綠色 */
        }

        .btn-list {
            background-color: #17a2b8; /* 藍綠色 */
        }
    </style>
</head>
<body>
    <?php
    $servername = "localhost"; // MySQL 伺服器位址
    $username = "root";        // MySQL 使用者名稱
    $password = "";            // MySQL 使用者密碼
    $database_name = "生鮮食品公司"; // 資料庫名稱

    // 建立 MySQL 連接
    $connection = mysqli_connect($servername, $username, $password, $database_name);

    // 檢查連接是否成功
    if (!$connection) {
        die("連接失敗: " . mysqli_connect_error());
    }

    // 處理新增應收帳款資料
    if (isset($_POST['add_receivable'])) {
        $customer_name = $_POST['customer_name'];
        $customer_id = $_POST['customer_id'];
        $receivable_amount = $_POST['receivable_amount'];
        $receivable_date = $_POST['receivable_date'];
        $pending_collection_amount = $receivable_amount; // 待催收金額初始值等於應收金額

        $checkSql = "SELECT * FROM 公司應收帳款 WHERE 應收帳款身分證字號 = '$customer_id'";
        $checkResult = mysqli_query($connection, $checkSql);

        if (mysqli_num_rows($checkResult) > 0) {
            echo "<div class='error-message'>此身分證字號已存在。</div>";
        } else {
            // 身份證字號不存在，可以進行插入操作
            $sql = "INSERT INTO 公司應收帳款 (客戶姓名, 應收帳款身分證字號, 應收金額, 應收日期, 待催收金額) 
                    VALUES ('$customer_name', '$customer_id', $receivable_amount, '$receivable_date', $pending_collection_amount)";
            if (mysqli_query($connection, $sql)) {
                echo "<div class='success-message'>新增應收帳款資料成功。</div>";
            } else {
                echo "<div class='error-message'>新增應收帳款資料失敗：" . mysqli_error($connection) . "</div>";
            }
        }
    }

    // 處理刪除應收帳款資料
    if (isset($_POST['delete_receivable'])) {
        $id_to_delete = $_POST['delete_id'];

        $sql = "DELETE FROM 公司應收帳款 WHERE 應收帳款身分證字號 = '$id_to_delete'";

        if (mysqli_query($connection, $sql)) {
            echo "<p>刪除應收帳款資料成功。</p>";
        } else {
            echo "刪除應收帳款資料失敗：" . mysqli_error($connection);
        }
    }


    // 處理編輯應收帳款資料
    if (isset($_POST['edit_receivable'])) {
        $id_to_edit = $_POST['edit_id'];
        $new_amount = $_POST['new_amount'];

        // 計算新的待催收金額
        $sql_select = "SELECT 應收金額, 待催收金額 FROM 公司應收帳款 WHERE 應收帳款身分證字號 = '$id_to_edit'";
        $result_select = mysqli_query($connection, $sql_select);

        if ($result_select) {
            $row_select = mysqli_fetch_assoc($result_select);
            $current_amount = $row_select['應收金額'];
            $current_pending = $row_select['待催收金額'];

            $new_pending = $new_amount;

            // 更新應收金額和待催收金額
            $sql_update = "UPDATE 公司應收帳款 SET 應收金額 = $new_amount, 待催收金額 = $new_pending WHERE 應收帳款身分證字號 = '$id_to_edit'";
            if (mysqli_query($connection, $sql_update)) {
                echo "<p>編輯應收帳款資料成功。</p>";
            } else {
                echo "編輯應收帳款資料失敗：" . mysqli_error($connection);
            }
        }
    }

    // 檢索並列出應收帳款資料
    $sql = "SELECT * FROM 公司應收帳款";
    $result = mysqli_query($connection, $sql);

    if ($result) {
        echo "<h2>應收帳款資料列表</h2>";
        echo "<table border='1'>";
        echo "<tr><th>客戶姓名</th><th>應收帳款身分證字號</th><th>應收金額</th><th>應收日期</th><th>待催收金額</th><th>編輯</th><th>刪除</th></tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['客戶姓名'] . "</td>";
            echo "<td>" . $row['應收帳款身分證字號'] . "</td>";
            echo "<td>" . $row['應收金額'] . "</td>";
            echo "<td>" . $row['應收日期'] . "</td>";
            echo "<td>" . $row['待催收金額'] . "</td>";
            echo "<td><form method='post'><input type='hidden' name='edit_id' value='" . $row['應收帳款身分證字號'] . "'><input type='number' name='new_amount' placeholder='新的應收金額'><input type='submit' name='edit_receivable' value='編輯'></form></td>";
            echo "<td><form method='post'><input type='hidden' name='delete_id' value='" . $row['應收帳款身分證字號'] . "'><input type='submit' name='delete_receivable' value='刪除'></form></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "檢索應收帳款資料失敗：" . mysqli_error($connection);
    }

    // 處理查詢應收帳款資料
    if (isset($_POST['search_receivable'])) {
        $search_id = $_POST['search_id'];

        $sql = "SELECT * FROM 公司應收帳款 WHERE 應收帳款身分證字號 = '$search_id'";
        $result = mysqli_query($connection, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                echo "<h2>查詢結果</h2>";
                echo "<table border='1'>";
                echo "<tr><th>客戶姓名</th><th>應收帳款身分證字號</th><th>應收金額</th><th>應收日期</th><th>待催收金額</th></tr>";

                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['客戶姓名'] . "</td>";
                    echo "<td>" . $row['應收帳款身分證字號'] . "</td>";
                    echo "<td>" . $row['應收金額'] . "</td>";
                    echo "<td>" . $row['應收日期'] . "</td>";
                    echo "<td>" . $row['待催收金額'] . "</td>";
                    echo "</tr>";
                }

                echo "</table>";

                // 添加列印按鈕
                echo "<br>";
                echo "<button onclick='window.print()'>列印查詢結果</button>";
            } else {
                echo "<p>未找到相關資料。</p>";
            }
        } else {
            echo "查詢應收帳款資料失敗：" . mysqli_error($connection);
        }
    }

    mysqli_close($connection);
    ?>
    <div class="container">
        <h1>新增應收帳款資料</h1>
        <div class="button-container">
            <button class="btn btn-custom btn-add" onclick="showForm('add')"><i class="fas fa-plus"></i> 新增應收帳款</button>
            <button class="btn btn-custom btn-list" onclick="showForm('form')"><i class="fas fa-list"></i> 應收帳款列表</button>
        </div>

        <!-- 新增應收帳款資料表單 -->
        <div id="add" class="form-container">
            <form method="post">
                <label>客戶姓名：</label>
                <input type="text" name="customer_name" required><br>
                <label>應收帳款身分證字號：</label>
                <input type="text" name="customer_id" required><br>
                <label>應收金額：</label>
                <input type="number" name="receivable_amount" step="0.01" required><br>
                <label>應收日期：</label>
                <input type="date" name="receivable_date" required><br>
                <input type="submit" name="add_receivable" value="新增應收帳款">
            </form>
        </div>

        <!-- 查詢表單 -->
        <div id="form" class="form-container">
            <form method="post">
                <label>輸入身分證字號：</label>
                <input type="text" name="search_id" required>
                <input type="submit" name="search_receivable" value="查詢">
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
