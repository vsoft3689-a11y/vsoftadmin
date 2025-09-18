<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .load-projects {
            margin: 10px 50px;
        }

        #projectHeading {
            margin: 20px;
            text-align: center;
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
            background: #06BBCC;
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

        #btn {
            width: auto;
            background: #06BBCC;
            color: #fff;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }
    </style>
</head>

<body>
    <?php include "adminnavbar.php" ?>

    <div class="load-projects">
        <h2 id="projectHeading">Projects</h2>
        <div id="projects"></div>
    </div>

    <?php include "./footer.php" ?>

</body>

<script>
    const apiUrl = "../controller/ProjectController.php";

    async function loadProjects() {
        let res = await fetch(apiUrl + "?action=read");
        let data = await res.json();

        if (data.length > 0) {
            document.getElementById("projects").innerHTML = "";
            let table = document.createElement("table");
            let thead = document.createElement("thead");
            let tr1 = document.createElement("tr");
            let th1 = document.createElement("th");
            let th2 = document.createElement("th");
            let th3 = document.createElement("th");
            let th4 = document.createElement("th");
            let th5 = document.createElement("th");
            let th6 = document.createElement("th");
            let th7 = document.createElement("th");
            let th8 = document.createElement("th");
            let th9 = document.createElement("th");
            let th10 = document.createElement("th");

            th1.innerHTML = "ID";
            th2.innerHTML = "Title";
            th3.innerHTML = "Type";
            th4.innerHTML = "Domain";
            th5.innerHTML = "Degree";
            th6.innerHTML = "Branch";
            th7.innerHTML = "Youtube URL";
            th8.innerHTML = "Abstract Paper";
            th9.innerHTML = "Base Paper";
            th10.innerHTML = "Actions";

            tr1.append(th1, th2, th3, th4, th5, th6, th7, th8, th9, th10);
            thead.appendChild(tr1);

            table.appendChild(thead);

            let tbody = document.createElement("tbody");
            tbody.innerHTML = "";

            data.forEach((p) => {
                let tr = document.createElement("tr");
                tr.innerHTML = `
                                <td>${p.id}</td>
                                <td>${p.title}</td>
                                <td>${p.type}</td>
                                <td>${p.domain}</td>
                                <td>${p.degree}</td>
                                <td>${p.branch}</td>
                                <td><a href="${p.youtube_url}" target="_blank">Watch Video</a></td>
                                <td><a href="${p.file_path_abstract}" target="_blank">Downlaod Abstract</a></td>
                                <td><a href="${p.file_path_basepaper}" target="_blank">Download Basepaper</a></td>
                                <td>
                                    <button id="btn" onclick="window.location='./updateproject.php?id=${p.id}&degree=${p.degree}&branch=${p.branch}&type=${p.type}&domain=${p.domain}&title=${p.title}&description=${p.description}&technologies=${p.technologies}&price=${p.price}&youtube_url=${p.youtube_url}&file_path_abstract=${p.file_path_abstract}&file_path_basepaper=${p.file_path_basepaper}'">
                                    Edit</button>
                                    <button id="btn" onclick="deleteProject(${p.id})">Delete</button>
                                </td>
                            `;
                tbody.appendChild(tr);
            });
            table.appendChild(tbody);
            document.getElementById("projects").appendChild(table);
        } else {
            let para = document.createElement("p");
            para.innerHTML = `No projects list found!`;
            para.style.textAlign = "center";
            para.style.fontWeight = "bold";
            para.style.paddingTop = "40px"
            document.getElementById("projects").appendChild(para);
        }
    }

    // Delete project
    async function deleteProject(id) {
        if (!confirm("Are you sure you want to delete this student?")) return;
        let formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", id);
        let res = await fetch(apiUrl, {
            method: "POST",
            body: formData
        });
        let result = await res.json();
        alert(result.success ? "Deleted!" : "Delete failed");
        loadProjects();
    }

    // Load on page start
    loadProjects();
</script>

</html>