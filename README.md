```markdown
# 生鮮食品公司管理系統

## 目錄

1. [簡介](#簡介)
2. [技術棧](#技術棧)
3. [ER 模型](#ER-模型)
4. [資料庫結構](#資料庫結構)
5. [功能介紹](#功能介紹)
6. [使用手冊](#使用手冊)
7. [安裝步驟](#安裝步驟)

## 簡介

本項目為一個完整的生鮮食品公司管理系統，旨在提供高效的客戶管理、訂單管理及應收帳款管理功能。系統使用 PHP 與 MySQL 實現，並包含用戶友好的前端界面。

## 技術棧

- **HTML**: 用於構建網頁結構
- **CSS**: 用於網頁樣式設計
- **JavaScript**: 用於網頁交互功能
- **PHP**: 用於後端業務邏輯
- **MySQL**: 資料庫管理系統

## ER 模型

本系統的 ER 模型設計如下：
- **客戶基本資料**：包含客戶的身份證字號（主鍵）、姓名、電話等基本信息。
- **訂貨紀錄**：包含訂貨身份證字號（外鍵）、訂貨編號、訂貨日期等信息。
- **公司進貨資料**：包含供應商編號（主鍵）、供應商名稱、進貨數量、單價等信息。
- **應收帳款資料**：包含應收帳款身份證字號（主鍵）、應收金額等信息。
- **訂貨產品資料**：包含訂貨產品ID（主鍵）、訂貨身份證字號（外鍵）等信息。

## 資料庫結構

1. **客戶基本資料**
   - PRIMARY KEY: 身份證字號

2. **訂貨紀錄**
   - PRIMARY KEY: 訂貨身份證字號
   - FOREIGN KEY: 訂貨身份證字號 (參考客戶基本資料的身份證字號)
   - FOREIGN KEY: 訂貨供應商編號 (參考公司進貨資料的供應商編號)

3. **公司進貨資料**
   - PRIMARY KEY: 供應商編號

4. **應收帳款資料**
   - PRIMARY KEY: 應收帳款身份證字號
   - FOREIGN KEY: 應收帳款身份證字號 (參考客戶基本資料的身份證字號)

5. **訂貨產品資料**
   - PRIMARY KEY: 訂貨產品ID
   - FOREIGN KEY: 訂貨身份證字號 (參考訂貨紀錄的訂貨身份證字號)

## 功能介紹

### 登入畫面
- 驗證用戶名和密碼是否正確，並提供登入失敗提示。

### 主畫面（儀表板）
- 提供用戶友好的儀表板，顯示各種統計數據和圖表。

### 客戶基本資料管理
- 新增、修改、刪除和查詢客戶基本資料。

### 訂貨紀錄管理
- 新增、修改、刪除和查詢訂貨紀錄，包含退費處理。

### 公司進貨資料管理
- 查詢每日日進貨總金額和每週進貨總金額。

### 應收帳款管理
- 新增、修改和刪除應收帳款資料。

### 跨資料庫整合
- 提供跨資料庫的查詢和分群功能。

## 使用手冊

1. 安裝 XAMPP，這裡面包含 PHP、PHPMYADMIN 及 APACHE。
2. 安裝 VSCode 作為開發環境。
3. 啟動伺服器 Apache 和 phpMyAdmin。
4. 開始編寫和執行代碼。

## 安裝步驟

1. 克隆此儲存庫到本地：
   ```bash
   git clone https://github.com/Caspar15/fresh-food-company.git
   ```
2. 將 `food.sql` 導入到你的 MySQL 資料庫：
   ```bash
   mysql -u your_username -p your_password freshFoodCompany < food.sql
   ```
3. 啟動 XAMPP，並確保 Apache 和 MySQL 已啟動。
4. 將所有 PHP 文件放置在 XAMPP 的 `htdocs` 目錄下。
5. 在瀏覽器中打開 `http://localhost/your_project_folder` 查看效果。

---

感謝使用生鮮食品公司管理系統！
```