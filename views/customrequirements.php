<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Projects Management</title>
    <style>
        .main {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #customHeading {
            margin: 20px 0;
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

        #btn:hover {
            background: #06BBCC;
        }

        #btn[type="button"] {
            background: #6c757d;
        }

        #btn[type="button"]:hover {
            background: #5a6268;
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

        .load-custom {
            margin: 10px 10px;
        }
    </style>
</head>

<body>
    <?php include "./adminnavbar.php" ?>

    <div class="main">
        <h2 id="customHeading">Custom Requirements</h2>
    </div>
    <section class="load-custom">
        <table id="customRequirement">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Title</th>
                    <th>Technologies</th>
                    <th>Status</th>
                    <th>Document</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>

    <?php include "./footer.php" ?>

    <script>
        const apiUrl = "../controller/CustomRequirementsController.php";

        // Load all projects
        async function loadProjects() {
            let res = await fetch(apiUrl + "?action=read");
            let data = await res.json();
            let tbody = document.querySelector("#projectTable tbody");
            tbody.innerHTML = "";

            data.forEach(p => {
                let tr = document.createElement("tr");
                tr.innerHTML = `
                                <td>${p.id}</td>
                                <td>${p.user_id}</td>
                                <td>${p.title}</td>
                                <td>${p.technologies || ""}</td>
                                <td>
                                    <select onchange="updateStatus(${p.id}, this.value)">
                                        <option value="pending" ${p.status === "pending" ? "selected" : ""}>Pending</option>
                                        <option value="approved" ${p.status === "approved" ? "selected" : ""}>Approved</option>
                                        <option value="done" ${p.status === "done" ? "selected" : ""}>Done</option>
                                    </select>
                                </td>
                                <td>${p.document_path ? `<a href="../${p.document_path}" target="_blank">Download</a>` : ""}</td>
                                <td>
                                    <button id=btn" onclick="deleteProject(${p.id})">Delete</button>
                                </td>
                            `;
                tbody.appendChild(tr);
            });
        }

        // Update status
        async function updateStatus(id, status) {
            let formData = new FormData();
            formData.append("action", "update_status");
            formData.append("id", id);
            formData.append("status", status);

            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            alert(result.message);
        }

        // Delete project
        async function deleteProject(id) {
            if (!confirm("Are you sure to delete this project?")) return;
            let formData = new FormData();
            formData.append("action", "delete");
            formData.append("id", id);

            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            alert(result.message);
            loadProjects();
        }

        // Initial load
        loadProjects();
    </script>
</body>

</html>