
CREATE database 生鮮食品公司;

use 生鮮食品公司;

CREATE TABLE 客戶基本資料
(
    客戶姓名 VARCHAR(12),
    身分證字號 CHAR(10),
    電話 VARCHAR(16),
    住址 VARCHAR(30),
    年齡 INT,
    職業 VARCHAR(12),
    登載日期 DATE,
    照片 LONGBLOB,
    消費狀態 CHAR(6),
    PRIMARY KEY(身分證字號)
);
CREATE TABLE 公司進貨 
-- 小計-derived attribute
(
    公司進貨供應商名稱 CHAR(16),
    供應商編號 CHAR(5),
    供應商負責人 CHAR(12),
    進貨品名 CHAR(16),
    進貨數量 DECIMAL(10, 2),
    進貨單位 CHAR(6),
    進貨單價 DECIMAL(10,2),
    庫存位置 CHAR(16),
    規格 CHAR(16),
    進貨日期 DATE,
    PRIMARY KEY(供應商編號)
);

CREATE TABLE 客戶訂貨紀錄
(
    訂貨身分證字號 CHAR(10),
    訂貨日期 DATE,
    預計遞交日期 DATE,
    實際遞交日期 DATE,
    訂貨品名 CHAR(16),
    單位 CHAR(6),
    數量 DECIMAL(10,2),
    單價 DECIMAL(10,2),
    訂貨金額 DECIMAL(10,2),
    客戶訂貨供應商名稱 CHAR(16),
    訂貨供應商編號 CHAR(5),
    Foreign Key (訂貨身分證字號) REFERENCES 客戶基本資料(身分證字號),
    Foreign Key (訂貨供應商編號) REFERENCES 公司進貨(供應商編號)
);

CREATE TABLE 訂貨產品
(
    訂貨產品ID CHAR(10),
    訂貨身分證字號 CHAR(10),
    產品名稱 CHAR(16),
    數量 DECIMAL(10,2),
    單價 DECIMAL(10,2),
    小計金額 DECIMAL(10,2) AS (數量 * 單價),
    PRIMARY KEY (訂貨產品ID),
    Foreign Key (訂貨身分證字號) REFERENCES 客戶訂貨紀錄(訂貨身分證字號)
);

CREATE TABLE 公司應收帳款
(
    客戶姓名 VARCHAR(12),
    應收帳款身分證字號 CHAR(10),
    應收金額 DECIMAL(10,2),
    應收日期 DATE,
    待催收金額 DECIMAL(10,2),
    PRIMARY KEY (應收帳款身分證字號),
    Foreign Key (應收帳款身分證字號) REFERENCES 客戶基本資料(身分證字號)
);


DESCRIBE 客戶基本資料;