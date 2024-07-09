<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>客戶基本資料介面</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet">
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

        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-bottom: 20px;
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
        $servername = "localhost";
        $username = "root";
        $password = "";
        $database_name = "生鮮食品公司";
        $connection = mysqli_connect($servername, $username, $password, $database_name);

        if (!$connection) {
            die("連接失敗: " . mysqli_connect_error());
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['action']) && $_POST['action'] == 'showCustomers') {
            // 處理 AJAX 請求，顯示所有客戶資料
            $sql = "SELECT * FROM 客戶基本資料";
            $result = mysqli_query($connection, $sql);

            if (mysqli_num_rows($result) > 0) {
                echo "<table><tr><th>客戶姓名</th><th>身分證字號</th><th>年齡</th><th>住址</th><th>電話</th><th>職業</th><th>登載日期</th><th>消費狀態</th></tr>";
                while($row = mysqli_fetch_assoc($result)) {
                    echo "<tr><td>" . $row["客戶姓名"]. "</td><td>" . $row["身分證字號"]. "</td><td>" . $row["年齡"]. "</td><td>" . $row["住址"]. "</td><td>" . $row["電話"]. "</td><td>" . $row["職業"]. "</td><td>" . $row["登載日期"]. "</td><td>" . $row["消費狀態"]. "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "未找到客戶資料。";
            }
            mysqli_close($connection);
            exit;
        }
        
        // 創建 "uploads" 目錄
        $target_dir = "uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        // 新增客戶資料
        if (isset($_POST['add_customer'])) {
            $customer_name = $_POST['customer_name'];
            $customer_id = $_POST['customer_id'];
            $customer_age = $_POST['customer_age'];
            $customer_address = $_POST['customer_address'];
            $customer_phone = $_POST['customer_phone'];
            $customer_occupation = $_POST['customer_occupation'];
            $current_date = date("Y-m-d");
        
            // 檢查身份證號碼是否已經存在
            $checkQuery = "SELECT * FROM 客戶基本資料 WHERE 身分證字號 = '$customer_id'";
            $checkResult = mysqli_query($connection, $checkQuery);
            if (mysqli_num_rows($checkResult) > 0) {
                echo "<div class='error-message'>此身分證字號已存在。</div>";
            } else {
                // 獲取上傳的照片
                $customer_photo = $_FILES['customer_photo']['name'];
        
                // 檢查是否有上傳照片
                if (!empty($customer_photo)) {
                    $target_directory = "uploads/"; // 上傳目錄
                    $target_file = $target_directory . basename($customer_photo);
        
                    // 允許的文件類型
                    $allowed_extensions = array("jpg", "jpeg", "png", "gif");
                    $file_extension = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
                    // 檢查文件類型
                    if (in_array($file_extension, $allowed_extensions)) {
                        // 嘗試上傳文件
                        if (move_uploaded_file($_FILES['customer_photo']['tmp_name'], $target_file)) {
                            // 文件上傳成功，保存文件路徑到資料庫
                            $sql = "INSERT INTO 客戶基本資料 (客戶姓名, 身分證字號, 年齡, 住址, 電話, 職業, 登載日期, 消費狀態, 照片) 
                                    VALUES ('$customer_name', '$customer_id', $customer_age, '$customer_address', '$customer_phone', '$customer_occupation', '$current_date', '正常', '$target_file')";
                            if (mysqli_query($connection, $sql)) {
                                echo "<div class='success-message'>新增客戶成功。</div>";
                            } else {
                                echo "<div class='error-message'>新增客戶失敗：" . mysqli_error($connection) . "</div>";
                            }
                        } else {
                            echo "<div class='error-message'>照片上傳失敗。</div>";
                        }
                    } else {
                        echo "<div class='error-message'>只允許上傳 JPG, JPEG, PNG, GIF 格式的圖片。</div>";
                    }
                } else {
                    // 如果沒有上傳照片，執行插入資料庫的操作（不包括照片欄位）
                    $sql = "INSERT INTO 客戶基本資料 (客戶姓名, 身分證字號, 年齡, 住址, 電話, 職業, 登載日期, 消費狀態) 
                            VALUES ('$customer_name', '$customer_id', $customer_age, '$customer_address', '$customer_phone', '$customer_occupation', '$current_date', '正常')";
        
                    if (mysqli_query($connection, $sql)) {
                        echo "<div class='success-message'>新增客戶成功。</div>";
                    } else {
                        echo "<div class='error-message'>新增客戶失敗：" . mysqli_error($connection) . "</div>";
                    }
                }
            }
        }


        // 刪除客戶資料（將消費狀態改成停用）
        if (isset($_POST['deactivate_customer'])) {
            $customer_id_to_deactivate = $_POST['customer_id_to_deactivate'];

            $sql = "UPDATE 客戶基本資料 SET 消費狀態 = '停用' WHERE 身分證字號 = '$customer_id_to_deactivate'";

            if (mysqli_query($connection, $sql)) {
                echo "<div class='success-message'>客戶已停用。</div>";
            } else {
                echo "<div class='error-message'>停用客戶失敗：" . mysqli_error($connection) . "</div>";
            }
        }

        // 修改客戶資料
        if (isset($_POST['update_customer'])) {
            $customer_id_to_update = $_POST['customer_id_to_update'];
            $new_customer_address = $_POST['new_customer_address'];
            $new_customer_phone = $_POST['new_customer_phone'];
            $customer_status = $_POST['customer_status']; // 新增的狀態選項

            // 準備更新SQL語句
            $sql = "UPDATE 客戶基本資料 
                    SET 住址 = '$new_customer_address', 電話 = '$new_customer_phone', 消費狀態 = '$customer_status' 
                    WHERE 身分證字號 = '$customer_id_to_update'";

            // 執行SQL更新
            if (mysqli_query($connection, $sql)) {
                echo "<div class='success-message'>客戶資料已更新。</div>";
            } else {
                echo "<div class='error-message'>更新客戶資料失敗：" . mysqli_error($connection) . "</div>";
            }
        }

        // 查詢客戶資料
        if (isset($_POST['search_customer'])) {
            $search_customer_id = $_POST['search_customer_id']; // 從表單獲取客戶身分證字號

            // 安全處理輸入的數據
            $search_customer_id = mysqli_real_escape_string($connection, $search_customer_id);

            // 準備SQL查詢
            $sql = "SELECT * FROM 客戶基本資料 WHERE 身分證字號 = '$search_customer_id'";

            // 執行查詢
            $result = mysqli_query($connection, $sql);

            // 檢查結果
            if ($result) {
                // 檢查是否有匹配的數據行
                if (mysqli_num_rows($result) > 0) {
                    // 開始表格輸出
                    echo "<div class='table-responsive'>";
                    echo "<table><tr><th>客戶姓名</th><th>身分證字號</th><th>年齡</th><th>住址</th><th>電話</th><th>職業</th><th>登載日期</th><th>消費狀態</th></tr>";

                    // 輸出每行數據
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>" . $row['客戶姓名'] . "</td>";
                        echo "<td>" . $row['身分證字號'] . "</td>";
                        echo "<td>" . $row['年齡'] . "</td>";
                        echo "<td>" . $row['住址'] . "</td>";
                        echo "<td>" . $row['電話'] . "</td>";
                        echo "<td>" . $row['職業'] . "</td>";
                        echo "<td>" . $row['登載日期'] . "</td>";
                        echo "<td>" . $row['消費狀態'] . "</td></tr>";
                    }
                    echo "</table>";
                    echo "</div>";
                } else {
                    echo "<div class='error-message'>未找到相符的客戶。</div>";
                }
            } else {
                echo "<div class='error-message'>查詢客戶資料失敗：" . mysqli_error($connection) . "</div>";
            }
        }

        // 關閉連接
            mysqli_close($connection);
        }
    ?>

    <div class="container">
        <h1>客戶基本資料</h1>
        <div class="d-flex justify-content-center flex-wrap">
        <button class="btn btn-primary btn-custom" onclick="showForm('add')"><i class="fas fa-plus"></i> 新增客戶</button>
            <button class="btn btn-danger btn-custom" onclick="showForm('delete')"><i class="fas fa-user-minus"></i> 刪除客戶</button>
            <button class="btn btn-info btn-custom" onclick="showForm('update')"><i class="fas fa-user-edit"></i> 修改客戶資料</button>
            <button class="btn btn-secondary btn-custom" onclick="showForm('search')"><i class="fas fa-search"></i> 查詢客戶資料</button>
            <button class="btn btn-success btn-custom" id="showCustomersBtn"><i class="fas fa-list"></i> 顯示所有客戶資料</button>
            <div id="customerData"></div>
        </div>


        <!-- 新增客戶表單 -->
        <div id="addForm" class="form-container">
            <div class="form-title">新增客戶</div>
                <form method="post" enctype="multipart/form-data">
                    <label>客戶姓名：</label>
                    <input type="text" name="customer_name" required maxlength="12">
                    <label>身分證字號：</label>
                    <input type="text" name="customer_id" required maxlength="10">
                    <label>年齡：</label>
                    <input type="number" name="customer_age" required >
                    <label>住址：</label>
                    <input type="text" name="customer_address" required maxlength="30">
                    <label>電話：</label>
                    <input type="text" name="customer_phone" required maxlength="16">
                    <label>職業：</label>
                    <input type="text" name="customer_occupation" required maxlength="12">
                    <label>照片：</label>
                    <input type="file" name="customer_photo">
                    <input type="submit" name="add_customer" value="新增">
                </form>
            </div>
        </div>

        <!-- 刪除客戶表單 -->
        <div id="deleteForm" class="form-container">
            <div class="form-title">刪除客戶</div>
                <form method="post">
                    <label>身分證字號：</label>
                    <input type="text" name="customer_id_to_deactivate" required>
                    <input type="submit" name="deactivate_customer" value="停用客戶">
                </form>
            </div>
        </div>

        <!-- 修改客戶資料表單 -->
        <div id="updateForm" class="form-container">
            <div class="form-title">修改客戶資料</div>
                <form method="post">
                    <label>身分證字號：</label>
                    <input type="text" name="customer_id_to_update" required>
                    <label>新住址：</label>
                    <input type="text" name="new_customer_address" required>
                    <label>新電話：</label>
                    <input type="text" name="new_customer_phone" required>
                    <label>狀態：</label>
                    <select name="customer_status">
                        <option value="正常">正常</option>
                        <option value="停用">停用</option>
                    </select>
                    <input type="submit" name="update_customer" value="更新客戶資料">
                </form>
            </div>
        </div>

        <!-- 查詢客戶表單 -->
        <div id="searchForm" class="form-container">
            <div class="form-title">查詢客戶表單</div>
                <form method="post">
                    <label>身分證字號：</label>
                    <input type="text" name="search_customer_id" required>
                    <input type="submit" name="search_customer" value="查詢客戶">
                </form>
            </div>
        </div>

        <script>
            function showForm(formName) {
                var forms = document.getElementsByClassName('form-container');
                for (var i = 0; i < forms.length; i++) {
                    forms[i].classList.remove('visible');
                }

                var formToShow = document.getElementById(formName + 'Form');
                if (formToShow) {
                    formToShow.classList.add('visible');
                    formToShow.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                // 清除顯示所有客戶資料的區域
                document.getElementById("customerData").innerHTML = "";
            }
        </script>

        <script>
            document.getElementById("showCustomersBtn").addEventListener("click", function() {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "Customer_basic.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        document.getElementById("customerData").innerHTML = xhr.responseText;
                    }
                };
                xhr.send("action=showCustomers");
            });
        </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>