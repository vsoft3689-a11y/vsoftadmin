<!DOCTYPE html>
<html>

<head>
  <title>Upload Excel</title>
  <style>
    .main {
      width: 100%;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    #uploadHeading {
      margin: 30px 0;
      color: #333;
    }

    #uploadForm {
      margin-bottom: 30px;
      padding: 30px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    input,
    textarea {
      margin: 8px 0;
      padding: 8px;
      width: 100%;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    #btn {
      width: auto;
      background: #06BBCC;
      color: #fff;
      padding: 10px;
      font-weight: bold;
      border-radius: 5px;
      cursor: pointer;
      border: none;
      transition: 0.3s;
    }

    #btn:hover {
      background: #0056b3;
    }

    #btn[type="submit"] {
      margin-top: 5px;
    }

    #response {
      margin-top: 15px;
      font-weight: bold;
      color: green;
    }
  </style>
</head>

<body>
  <?php include "./adminnavbar.php" ?>

  <div class="main">
    <h2 id="uploadHeading">Bulk Upload Projects</h2>
    <form id="uploadForm" enctype="multipart/form-data">
      <input type="file" name="excel_file" accept=".xls,.xlsx" required>
      <button id="btn" type="submit">Upload</button>
    </form>
    <div id="response"></div>
  </div>

  <?php include "./footer.php" ?>

  <script>
    document.getElementById("uploadForm").addEventListener("submit", function(e) {
      e.preventDefault(); // prevent page navigation

      let formData = new FormData(this);

      fetch("../controller/ProjectUploadExcel.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.text())
        .then(data => {
          alert(data);
        })
        .catch(error => {
          alert(error);
        });
    });
  </script>
</body>

</html>