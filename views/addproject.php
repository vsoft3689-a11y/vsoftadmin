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
      background: #06BBCC;
      color: #fff;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      border: none;
      transition: 0.3s;
    }

    #createForm div button:hover {
      background: #06BBCC;
    }

    #createForm div button[type="button"] {
      background: #06BBCC;
    }

    #createForm div button[type="button"]:hover {
      background: #06BBCC;
    }
  </style>
</head>

<body>
  <?php include "./adminnavbar.php" ?>

  <div class="main">
    <section>
      <h2 id="createHeading">Add Project</h2>
      <form id="createForm" enctype="multipart/form-data">
        <div>
          <input type="hidden" name="action" value="create" />

          <input type="text" name="degree" placeholder="Degree"/>
          <input type="text" name="branch" placeholder="Branch"/>
          <select name="type">
            <option value="mini">Mini</option>
            <option value="major">Major</option>
          </select>
          <input type="text" name="domain" placeholder="Domain"/>
          <input type="text" name="title" placeholder="Title"/>
          <textarea name="description" placeholder="Description"></textarea>
        </div>

        <div>
          <input type="text" name="technologies" placeholder="Technologies" />
          <input type="number" name="price" placeholder="Price" />
          <input type="url" name="youtube_url" placeholder="YouTube URL" />
          <input type="file" name="abstract" accept="application/pdf" />
          <input type="file" name="basepaper" accept="application/pdf" />
          <button type="submit">Add</button>
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
      let form = e.target;

      let degree = form.degree.value.trim();
      let branch = form.branch.value.trim();
      let type = form.type.value;
      let domain = form.domain.value.trim();
      let title = form.title.value.trim();

      // Validate Degree
      if (degree.length < 2) {
        alert("Degree must be at least 2 characters long.");
        form.degree.focus();
        e.preventDefault();
        return;
      }

      // Validate Branch
      if (branch.length < 2) {
        alert("Branch must be at least 2 characters long.");
        form.branch.focus();
        e.preventDefault();
        return;
      }

      // Validate Type
      if (type !== "mini" && type !== "major") {
        alert("Please select a valid type.");
        form.type.focus();
        e.preventDefault();
        return;
      }

      // Validate Domain
      if (domain.length < 3) {
        alert("Domain must be at least 3 characters long.");
        form.domain.focus();
        e.preventDefault();
        return;
      }

      // Validate Title
      if (title.length < 3) {
        alert("Title must be at least 3 characters long.");
        form.title.focus();
        e.preventDefault();
        return;
      }

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