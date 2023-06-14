<!DOCTYPE html>
<html>

<head>
  <title>Taranmış Fiş Bilgileri</title>
  <meta charset="UTF-8">
  <!-- Include Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f1f1f1;
    }

    h1 {
      text-align: center;
      color: #555;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      padding: 15px;
      background-color: #fff;
      z-index: 999;
      transition: top 0.3s ease-in-out;
    }

    #container {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    #table-container {
      background-color: #fff;
      border-radius: 5px;
      padding: 30px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 10px;
      text-align: left;
      border-bottom: 1px solid #ddd;
      vertical-align: middle;
      /* Center vertically */
    }

    th {
      background-color: #f2f2f2;
    }

    .upload-button {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    .upload-button form {
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .upload-button button {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-family: Arial, sans-serif;
      font-size: 14px;
    }

    /* Buttons */
    .edit-button,
    .delete-button {
      padding: 8px 16px;
      background-color: #4CAF50;
      /* Match the background color with the "Upload" button */
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-family: Arial, sans-serif;
      font-size: 14px;
      margin-right: 8px;
    }

    .edit-button:hover,
    .delete-button:hover {
      background-color: #45A049;
      /* Adjust the hover color if needed */
    }
  </style>
</head>

<body>
  <h1>Taranmış Fiş Bilgileri</h1>
  <div id="container">
    <div id="table-container">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Açıklama</th>
            <th>Tarih</th>
            <th>Saat</th>
            <th>Ödeme Yöntemi</th>
            <th>Toplam</th>
            <th>İşlemler</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $servername = "localhost";
          $username = "root";
          $password = "";
          $dbname = "project";

          // Create connection
          $conn = new mysqli($servername, $username, $password, $dbname);

          // Check connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          $sql = "SELECT id, aciklama, tarih, saat, odeme, toplam FROM result";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $row["id"] . "</td>";
              echo "<td>" . $row["aciklama"] . "</td>";
              echo "<td>" . $row["tarih"] . "</td>";
              echo "<td>" . $row["saat"] . "</td>";
              echo "<td>" . $row["odeme"] . "</td>";
              echo "<td>" . $row["toplam"] . "₺" . "</td>";
              echo "<td>";
              echo "<button class='edit-button' onclick='redirectToEdit(" . $row["id"] . ")'>Güncelle</button>";
              echo "<button class='delete-button' onclick='redirectToDelete(" . $row["id"] . ")'>Sil</button>";
              echo "</td>";
              echo "</tr>";
            }
          } else {
            echo "<tr><td colspan='5'>No data found</td></tr>";
          }

          $conn->close();
          ?>
        </tbody>
      </table>
      <div class="upload-button">
        <form action="upload.php">
          <button id="exportButton" class="btn btn-primary">Kayıt Ekle</button>
        </form>
      </div>
      <div class="upload-button">
        <form id="exportForm" onsubmit="exportTable(event)">
          <button id="exportButton" class="btn btn-primary" type="submit">Dışa Aktar</button>
        </form>
      </div>
    </div>
  </div>
  <!-- Include Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
  <script>
    function exportTable(event) {
      event.preventDefault();

      // Remove the "İşlemler" column from the table
      var table = document.querySelector("table");
      var tableHeaders = table.querySelectorAll("th");
      var removeIndex = -1;

      tableHeaders.forEach(function(header, index) {
        if (header.textContent === "İşlemler") {
          removeIndex = index;
        }
      });

      if (removeIndex > -1) {
        var rows = table.querySelectorAll("tr");
        rows.forEach(function(row) {
          row.removeChild(row.cells[removeIndex]);
        });
      }

      // Get the current date and time
      var currentDate = new Date();
      var fileName = "export_" + currentDate.toLocaleString().replace(/[\/:]/g, "_");

      // Export the table as Excel
      var wb = XLSX.utils.table_to_book(table, {
        sheet: "Sheet 1"
      });
      var wbout = XLSX.write(wb, {
        bookType: 'xlsx',
        type: 'binary'
      });

      function s2ab(s) {
        var buf = new ArrayBuffer(s.length);
        var view = new Uint8Array(buf);
        for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
        return buf;
      }

      var blob = new Blob([s2ab(wbout)], {
        type: 'application/octet-stream'
      });
      var url = URL.createObjectURL(blob);

      var link = document.createElement('a');
      link.href = url;
      link.download = fileName + ".xlsx";

      // Reload the page after the download link is clicked
      link.addEventListener('click', function() {
        window.location.reload();
      });

      link.click();
    }

    function redirectToEdit(id) {
      window.location.href = "edit.php?id=" + id;
    }

    function redirectToDelete(id) {
      window.location.href = "delete.php?id=" + id;
    }

    // Hide or show the header based on the scroll position
    var prevScrollPos = window.pageYOffset;
    window.onscroll = function() {
      var currentScrollPos = window.pageYOffset;
      if (prevScrollPos > currentScrollPos) {
        document.querySelector("h1").style.top = "0";
      } else {
        document.querySelector("h1").style.top = "-100px";
      }
      prevScrollPos = currentScrollPos;
    };
  </script>
</body>

</html>