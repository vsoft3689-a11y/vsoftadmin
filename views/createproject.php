<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Project CRUD</title>
  <style>
    .main {
      width: 100%;
      height: 100%;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    #createHeading {
      color: #444;
      margin-top: 50px;
      margin-bottom: 10px;
    }

    #createForm {
      max-width: 800px;
      display: flex;
      gap: 30px;
      justify-content: space-around;
      background: #fff;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 10px;
      margin: 6px 0;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    #createForm div input,
    select {
      margin-bottom: 10px;
    }

    #createForm div select {
      margin-bottom: 10px;
    }

    button {
      width: auto;
      padding: 10px;
      margin: 6px 0;
      border-radius: 6px;
      margin-top: 40px;
    }

    textarea {
      resize: vertical;
      min-height: 80px;
    }

    #createForm div button {
      background: #007bff;
      color: #fff;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      border: none;
      transition: 0.3s;
    }

    #createForm div button:hover {
      background: #0056b3;
    }

    #createForm div button[type="button"] {
      background: #6c757d;
    }

    #createForm div button[type="button"]:hover {
      background: #5a6268;
    }

    hr {
      margin: 30px 0;
    }

    #projects a {
      margin: 0 8px;
      text-decoration: none;
      color: #28a745;
      font-weight: bold;
    }

    #projects a:hover {
      text-decoration: underline;
    }

    #projects button {
      margin: 5px;
      padding: 6px 12px;
      font-size: 13px;
    }

    .load-projects {
      margin: 10px 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      background: #fff;
      margin-top: 20px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    th,
    td {
      padding: 12px;
      border-bottom: 1px solid #eee;
      text-align: left;
    }

    th {
      background: #007bff;
      color: #fff;
    }

    tr:hover {
      background: #f1f1f1;
    }

    .actions button {
      width: auto;
      margin-right: 6px;
      padding: 6px 12px;
      font-size: 13px;
    }
  </style>
</head>

<body>
  <?php include "./adminnavbar.php" ?>
  
  <div class="main">
    <section>
      <h2 id="createHeading">Create Project</h2>
      <form id="createForm" enctype="multipart/form-data">
        <div>
          <input type="hidden" name="action" value="create" />

          <input type="text" name="degree" placeholder="Degree" required />
          <input type="text" name="branch" placeholder="Branch" required />
          <select name="type">
            <option value="mini">Mini</option>
            <option value="major">Major</option>
          </select>
          <input type="text" name="domain" placeholder="Domain" required />
          <input type="text" name="title" placeholder="Title" required />
          <textarea name="description" placeholder="Description"></textarea>
        </div>

        <div>
          <input type="text" name="technologies" placeholder="Technologies" />
          <input type="number" name="price" placeholder="Price" />
          <input type="url" name="youtube_url" placeholder="YouTube URL" />
          <input type="file" name="abstract" accept="application/pdf" />
          <input type="file" name="basepaper" accept="application/pdf" />
          <button type="submit">Create</button>
        </div>
      </form>
    </section>
  </div>

  <?php include "./footer.php" ?>

  <script>
    const apiUrl = "../controller/ProjectController.php";

    // Create Project
    document.getElementById("createForm").addEventListener("submit", async function(e) {
      e.preventDefault();
      try {
        let formData = new FormData(this);
        let res = await fetch(apiUrl, {
          method: "POST",
          body: formData
        });
        let result = await res.json();
        alert(result.success ? "Created!" : "Create failed");
        this.reset();
      } catch (err) {
        console.error("Fetch error:", err);
        alert("Update failed. Check console for details.");
      }
    });
  </script>
</body>

</html>