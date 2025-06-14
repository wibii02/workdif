# Workdiff (Proyek PDT😅)
Repository ini berisi sistem manajemen proyek berbasis PHP dan MySQL yang dirancang untuk mengelola proyek secara efisien. Sistem ini memanfaatkan stored procedure, trigger, transaction, dan stored function untuk memastikan integritas data dan keamanan transaksi.

![Home](assets/img/dashboard.png)

## 📌 Detail Konsep

### ⚠️ Disclaimer

Penerapan stored procedure, trigger, transaction, dan stored function dalam proyek ini disesuaikan dengan kebutuhan workdif. Implementasi dapat berbeda tergantung pada arsitektur dan kebutuhan sistem lainnya.

### 🧠 Stored Procedure 
Stored procedure digunakan untuk menangani operasi penting dalam sistem manajemen proyek, seperti pembuatan proyek, penghapusan proyek, dan pencatatan aktivitas.

![Procedure](assets/img/procedure.png)

Beberapa procedure penting yang digunakan:

`views/tambah_project.php`
* `TambahProjek(p_nama_projek, p_deskripsi, p_user_id, p_deadline)`: Menambah projek baru
  ```php
  try {
        $stmt = $pdo->prepare("CALL TambahProjek(?, ?, ?, ?)");
        $stmt->execute([$nama, $deskripsi, $user_id, $deadline]);

        header("Location: index.php?route=dashboard&success=project_added");
        exit;
    } catch (PDOException $e) {
        error_log("Error adding project: " . $e->getMessage());

        header("Location: index.php?route=tambah_project&error=db_error&msg=" . urlencode($e->getMessage()));
        exit;
    }
  ```
* `HapusProjek(p_project_id, p_user_id)`: Menghapus projek ketika klik button hapus
  ```php
  try {
        $stmt = $pdo->prepare("CALL HapusProjek(?, ?)");
        $stmt->execute([$projectId, $userId]);

        header("Location: index.php?route=dashboard&success=project_deleted");
        exit;
    } catch (PDOException $e) {
        $errorMessage = $e->getMessage();
        error_log("Error deleting project: " . $errorMessage);

        header("Location: index.php?route=dashboard&error=db_error&msg=" . urlencode($errorMessage));
        exit;
    }
  ```


### 🚨 Trigger
Trigger `validate_transaction` berfungsi sebagai sistem pengaman otomatis yang aktif sebelum data masuk ke dalam tabel. Seperti palang pintu yang hanya terbuka jika syarat tertentu terpenuhi, trigger mencegah input data yang tidak valid atau berisiko merusak integritas sistem.

![Trigger](assets/img/trigger.png)

Trigger `validate_transaction` otomatis aktif pada procedure berikut:
* `log_project_delete`
  ```sql
  INSERT INTO activity_logs (user_id, aksi, waktu)
  VALUES (
    IFNULL(OLD.user_id, 0),
    CONCAT('Menghapus proyek "', IFNULL(OLD.nama_projek, 'Tidak diketahui'), '" (ID: ', OLD.id, ')'),
    NOW()
  );
  ```
* `after_task_update`
  ```sql
  INSERT INTO activity_logs(user_id, aksi)
    VALUES (NEW.assigned_to, CONCAT('Menyelesaikan task "', NEW.judul, '"'));
  ```

Beberapa peran trigger di sistem ini:
* Mencatat ke dalam activity_log jika projek dihapus
* Mencatat ke dalam activity_log jika status task diubah menjadi "done"

Dengan adanya trigger di lapisan database, validasi tetap dijalankan secara otomatis, bahkan jika ada celah atau kelalaian dari sisi aplikasi. Ini selaras dengan prinsip reliabilitas pada sistem terdistribusi.

### 🔄 Transaction (Transaksi)
Transaksi digunakan untuk memastikan bahwa operasi yang melibatkan beberapa tabel tetap konsisten. Misalnya, saat menghapus proyek, sistem akan menghapus data terkait dan mencatat log dalam satu transaksi.

`App\Models\Transaction.php`
* Implementasi transaction untuk procedure `deposit_money`
  ```php
  try {
      // Start a transaction
      // This is important to ensure that the deposit is atomic
      $this->conn->beginTransaction();
      // Call the deposit_money stored procedure
      $stmt = $this->conn->prepare("CALL deposit_money(?, ?, ?)");
      $stmt->execute([
          $txId,
          $toAccount['account_number'],
          $amount
      ]);

      $this->conn->commit();
  } catch (PDOException $e) {
      $this->conn->rollBack();
      $errorInfo = $e->errorInfo ?? [];
      $message = $errorInfo[2] ?? $e->getMessage();

      throw new Exception("Deposit failed: SQLSTATE[{$errorInfo[0]}]: {$errorInfo[1]} {$message}");
  }
* Implementasi transaction untuk procedure `transfer_money`
  ```php
  try {
      // Start a transaction
      // This is important to ensure that the transfer is atomic
      $this->conn->beginTransaction();
      // Call the transfer_money stored procedure
      $stmt = $this->conn->prepare("CALL transfer_money(?, ?, ?, ?)");
      $stmt->execute([
          $txId,
          $fromAccount['account_number'],
          $toAccountNumber,
          $amount
      ]);

      $this->conn->commit();
  } catch (PDOException $e) {
      $this->conn->rollBack();
      $errorInfo = $e->errorInfo ?? [];
      $message = $errorInfo[2] ?? $e->getMessage();

      throw new Exception("Transfer failed: SQLSTATE[{$errorInfo[0]}]: {$errorInfo[1]} {$message}");
  }
  ```
Demikian pula saat user melakukan registrasi, sistem tidak hanya menyimpan data user, tetapi juga membuat akun bank sekaligus. Proses ini dijalankan dalam satu transaksi agar semua langkah saling bergantung dan terjamin konsistensinya.

`App\Models\User.php`
```php
try {
    // Start a transaction
    // This is important to ensure that the registration is atomic
    $this->conn->beginTransaction();

    $stmt = $this->conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashedPassword]);
    $userId = $this->conn->lastInsertId();

    $accountNumber = $this->generateUniqueAccountNumber();
    $stmtAcc = $this->conn->prepare("INSERT INTO accounts (user_id, account_number, balance) VALUES (?, ?, 0)");
    $stmtAcc->execute([$userId, $accountNumber]);

    $this->conn->commit();
    return true;
} catch (Exception $e) {
    $this->conn->rollBack();
    throw new Exception("Registration failed due to database error.");
}
```
### 📺 Stored Function 
Stored function digunakan untuk mengambil informasi tanpa mengubah data. Seperti layar monitor: hanya menampilkan data, tidak mengubah apapun.

Contohnya, function  `get_balance(p_account)` mengembalikan saldo terkini dari sebuah akun. 

Function ini dipanggil baik dari aplikasi maupun dari procedure yang ada di database. Dengan begitu, logika pembacaan saldo tetap terpusat dan konsisten, tanpa perlu duplikasi kode atau risiko ketidaksesuaian antara sistem aplikasi dan database.

![Function](assets/img/function.png)

* Aplikasi

  `home.php`
  ```php
  $balance = $accountModel->getBalance($userId);
  ```
  ```html
  <div class="d-flex align-items-center me-2">
      <span class="me-1 text-secondary">Balance:</span>
      <span class="fw-semibold fs-5 ms-1">Rp</span>
      <span id="balance" class="fs-4 fw-semibold ms-1"
          data-real-balance="<?= number_format($balance, 2, ',', '.') ?>">••••••••</span>
  </div>
  ```

  `App/Models/Account.php`
  ```php
  $stmt = $this->conn->prepare("SELECT get_balance(?) AS balance");
  $stmt->execute([$accountNumber]);
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  ```
* Procedure `transfer_money`
  ```sql
  SET v_balance = get_balance(p_from_account);

  IF v_balance < p_amount THEN
      SIGNAL SQLSTATE '45000' 
      SET MESSAGE_TEXT = 'Insufficient balance',
          MYSQL_ERRNO = 1647;
  END IF;
  ```
Penggunaan function seperti ini mencerminkan praktik pemisahan logika bisnis di database layer, yang relevan dalam konteks Pemrosesan Data Terdistribusi — di mana konsistensi dan reliabilitas antar node atau proses sangat krusial.

### 🔄 Backup Otomatis
Untuk menjaga ketersediaan dan keamanan data, sistem dilengkapi fitur backup otomatis menggunakan `mysqldump`dan task scheduler. Backup dilakukan secara berkala dan disimpan dengan nama file yang mencakup timestamp, sehingga mudah ditelusuri. Semua file disimpan di direktori `storage/backups`.

`backup.php`
```php
<?php
require_once __DIR__ . '/init.php';

$date = date('Y-m-d_H-i-s');
$backupFile = __DIR__ . "/storage/backups/pdtbank_backup_$date.sql";
$command = "\"C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe\" -u " . DB_USER . " " . DB_NAME . " > \"$backupFile\"";
exec($command);
```

## 🧩 Relevansi Proyek dengan Pemrosesan Data Terdistribusi
Sistem ini dirancang dengan memperhatikan prinsip-prinsip dasar pemrosesan data terdistribusi:
* **Konsistensi**: Semua transaksi dieksekusi dengan procedure dan validasi terpusat di database.
* **Reliabilitas**: Trigger dan transaction memastikan sistem tetap aman meskipun ada error atau interupsi.
* **Integritas**: Dengan logika disimpan di dalam database, sistem tetap valid walaupun dipanggil dari banyak sumber (web, API, dsb).