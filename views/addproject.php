<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <title>Add Project</title>
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
      width: 20vw;
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

          <!-- <input type="text" name="degree" placeholder="Degree"/> -->
          <select name="degree" id="degree" onchange="updateBranches()">
            <option value="">Select Degree</option>
            <option value="BTech">B.Tech</option>
            <option value="MTech">M.Tech</option>
            <option value="MCA">MCA</option>
            <option value="MBA">MBA</option>
          </select>
          <!-- <input type="text" name="branch" placeholder="Branch"/> -->
          <select name="branch" id="branch">
            <option value="">Select Branch</option>
          </select>
          <div id="projectTypeDiv">
            <select name="type" id="type">
              <option value="">Select Type</option>
              <option value="Mini">Mini Project</option>
              <option value="Major">Major Project</option>
              <option value="Final">Final Project</option>
            </select>
          </div>

          <select name="domain" id="domain">
            <option value="">Select Domain</option>
          </select>
          <input type="text" name="title" placeholder="Title" />
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

    function updateBranches() {
      let degree = document.getElementById("degree").value;
      let branch = document.getElementById("branch");
      let domain = document.getElementById("domain");
      let projectTypeDiv = document.getElementById("projectTypeDiv");

      branch.innerHTML = "<option value=''>Select Branch</option>";
      domain.innerHTML = "<option value=''>Select Domain</option>";
      projectTypeDiv.style.display = "block";

      if (degree === "BTech") {
        ["CSE", "ECE", "EEE", "Civil", "Mech"].forEach(b => {
          branch.innerHTML += `<option value="${b}">${b}</option>`;
        });
      } else if (degree === "MTech") {
        ["CSE", "ECE", "Power Systems", "Structural Engineering"].forEach(b => {
          branch.innerHTML += `<option value="${b}">${b}</option>`;
        });
      } else if (degree === "MCA") {
        ["Web Development", "AI&ML", "Database Systems", "Mobile Application"].forEach(b => {
          branch.innerHTML += `<option value="${b}">${b}</option>`;
        });
      } else if (degree === "MBA") {
        ["Marketing", "Finance", "Human Resource", "Operations"].forEach(b => {
          branch.innerHTML += `<option value="${b}">${b}</option>`;
        });

      }
    }


    document.getElementById("branch").addEventListener("change", function() {
      let branch = this.value;
      let degree = document.getElementById("degree").value;
      let domain = document.getElementById("domain");

      domain.innerHTML = "<option value=''>Select Domain</option>";

      if (degree === "BTech") {
        if (branch === "CSE") {
          ["Web Development", "AI/ML", "Cloud Computing", "App Development", "Cyber Security"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "ECE") {
          ["VLSI", "Embedded Systems", "IoT", "Robotics"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "EEE") {
          ["Power Electronics", "Renewable Energy", "Smart Grids"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "Civil") {
          ["Structural Analysis", "Construction Management", "Geotechnical"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "Mech") {
          ["Thermal Engineering", "Automobile", "Manufacturing", "Mechatronics"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        }
      } else if (degree === "MTech") {
        if (branch === "CSE") {
          ["Data Mining", "Blockchain", "Network Security"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "ECE") {
          ["Wireless Communication", "Signal Processing", "VLSI Design"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "Power Systems") {
          ["FACTS", "Smart Energy System", "Load Flow Studies"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "Structural Engineering") {
          ["Finite Element", "Concrete Technology", "Seismic Design"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        }
      } else if (degree === "MCA") {
        if (branch === "Database Systems") {
          ["Database Systems", "Web Development", "AI&ML", "Mobile Applications"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "Web Development") {
          ["Database Systems", "Web Development", "AI&ML", "Mobile Applications"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "AI&ML") {
          ["Database Systems", "Web Development", "AI&ML", "Mobile Applications"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "Mobile Application") {
          ["Database Systems", "Web Development", "AI&ML", "Mobile Applications"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        }
      } else if (degree === "MBA") {
        if (branch === "Marketing") {
          ["Marketing", "Finance", "HR", "Operations"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "Finance") {
          ["Marketing", "Finance", "HR", "Operations"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "Human Resource") {
          ["Marketing", "Finance", "HR", "Operations"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        } else if (branch === "Operations") {
          ["Marketing", "Finance", "HR", "Operations"].forEach(d => {
            domain.innerHTML += `<option value="${d}">${d}</option>`;
          });
        }
      }
    });

    // Create Project
    document.getElementById("createForm").addEventListener("submit", async function(e) {
      e.preventDefault();
      let form = e.target;

      let degree = form.degree.value.trim();
      let branch = form.branch.value.trim();
      let type = form.type.value;
      let domain = form.domain.value.trim();
      let title = form.title.value.trim();
      let url = form.youtube_url.value.trim();

      console.log(degree, branch, type, domain, title, url);

      // Validate Degree
      if (degree.length < 2) {
        alert("Degree must be selected.");
        form.degree.focus();
        e.preventDefault();
        return;
      }

      // Validate Branch
      if (branch.length < 2) {
        alert("Branch must be selected.");
        form.branch.focus();
        e.preventDefault();
        return;
      }

      // Validate Type
      if (type !== "Mini" && type !== "Major" && type!=="Final") {
        alert("Please select type.");
        form.type.focus();
        e.preventDefault();
        return;
      }

      // Validate Domain
      if (domain.length < 3) {
        alert("Domain must be selected.");
        form.domain.focus();
        e.preventDefault();
        return;
      }

      // Validate Title
      if (title.length < 2) {
        alert("Title must be at least 3 characters long.");
        form.title.focus();
        e.preventDefault();
        return;
      }

      if (url !== "" && !url.includes("youtube.com") && !url.includes("youtu.be")) {
        e.preventDefault(); // stop form submit
        alert("Please enter a valid YouTube URL.");
        return;
      }

      try {
        let formData = new FormData(this);

        console.log(...formData)
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