<?php
// students.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Student Management</title>
    <style>
        /* * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        } */

        .main {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h2 {
            margin: 20px 0;
            color: #333;
        }

        form {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        input,
        button {
            width: 100%;
            padding: 10px;
            margin: 6px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            background: #007bff;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }

        button:hover {
            background: #0056b3;
        }

        button[type="button"] {
            background: #6c757d;
        }

        button[type="button"]:hover {
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

        .load-users {
            margin: 10px 10px;
        }
    </style>
</head>

<body>
    <?php include "./adminnavbar.php"?>

    <div class="main">
        <section>
            <h2 id="createHeading">Add Student</h2>
            <form id="createForm">
                <input type="hidden" name="action" value="create" />
                <input type="text" name="name" placeholder="Name" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <input type="text" name="phone" placeholder="Phone" />
                <input type="text" name="college" placeholder="College" />
                <input type="text" name="branch" placeholder="Branch" />
                <input type="text" name="year" placeholder="Year" />
                <button type="submit">Add Student</button>
            </form>
        </section>

        <section>
            <h2 id="updateHeading" style="display:none;">Update Student</h2>
            <form id="updateForm" style="display:none;">
                <input type="hidden" name="action" value="update" />
                <input type="hidden" name="id" id="update_id" />
                <input type="text" name="name" id="update_name" placeholder="Name" required />
                <input type="email" name="email" id="update_email" placeholder="Email" required />
                <input type="text" name="phone" id="update_phone" placeholder="Phone" />
                <input type="text" name="college" id="update_college" placeholder="College" />
                <input type="text" name="branch" id="update_branch" placeholder="Branch" />
                <input type="text" name="year" id="update_year" placeholder="Year" />
                <button type="submit">Update Student</button>
                <button type="button" onclick="cancelUpdate()">Cancel</button>
            </form>
        </section>
    </div>

    <section class="load-users">
        <h2>Student List</h2>
        <div id="users"></div>
    </section>
    </table>

    <script>
        const apiUrl = "vsoft_students_api.php";

        // Load students
        async function loadStudents() {
            let res = await fetch(apiUrl + "?action=read");
            let data = await res.json();
            console.log(data)

            if (data.length > 0) {
                document.getElementById("users").innerHTML = "";
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

                th1.innerHTML = "ID";
                th2.innerHTML = "Name";
                th3.innerHTML = "Email";
                th4.innerHTML = "Phone";
                th5.innerHTML = "College";
                th6.innerHTML = "Branch";
                th7.innerHTML = "Year";
                th8.innerHTML = "Created At";
                th9.innerHTML = "Actions";

                tr1.append(th1, th2, th3, th4, th5, th6, th7, th8, th9);
                thead.appendChild(tr1);

                table.appendChild(thead);

                let tbody = document.createElement("tbody");
                tbody.innerHTML = "";

                data.forEach((s) => {
                    let tr = document.createElement("tr");
                    tr.innerHTML = `
                <td>${s.id}</td>
                <td>${s.name}</td>
                <td>${s.email}</td>
                <td>${s.phone || "-"}</td>
                <td>${s.college || "-"}</td>
                <td>${s.branch || "-"}</td>
                <td>${s.year || "-"}</td>
                <td>${s.created_at}</td>
                <td class="actions">
                <button onclick="editStudent(${s.id}, '${s.name}', '${s.email}', '${s.phone || ""}', '${s.college || ""}', '${s.branch || ""}', '${s.year || ""}')">Edit</button>
                <button onclick="deleteStudent(${s.id})">Delete</button>
                <button onclick="toggleStatus(${s.id}, '${s.status}')">${s.status === "active" ? "Deactivate" : "Activate"}</button>
                </td>
                `;
                    tbody.appendChild(tr);
                    table.appendChild(tbody);
                });
                document.getElementById("users").appendChild(table);
            } else {
                let para = document.createElement("p");
                para.innerHTML = `No students list found!`;
                para.style.textAlign = "center";
                para.style.fontWeight = "bold";
                para.style.paddingTop = "40px"
                document.getElementById("users").appendChild(para);
            }
        }

        // Create student
        document.getElementById("createForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            alert(result.message);
            // document.getElementById("users").innerHTML = "";
            this.reset();
            loadStudents();
        });

        // Update student
        document.getElementById("updateForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            alert(result.message);
            // document.getElementById("users").innerHTML = "";
            cancelUpdate();
            loadStudents();
        });

        // Edit student (show update form)
        function editStudent(id, name, email, phone, college, branch, year) {
            document.getElementById("createForm").style.display = "none";
            document.getElementById("createHeading").style.display = "none";
            document.getElementById("updateForm").style.display = "block";
            document.getElementById("updateHeading").style.display = "block";

            document.getElementById("update_id").value = id;
            document.getElementById("update_name").value = name;
            document.getElementById("update_email").value = email;
            document.getElementById("update_phone").value = phone;
            document.getElementById("update_college").value = college;
            document.getElementById("update_branch").value = branch;
            document.getElementById("update_year").value = year;
        }

        // Cancel update
        function cancelUpdate() {
            document.getElementById("updateForm").style.display = "none";
            document.getElementById("updateHeading").style.display = "none";
            document.getElementById("createForm").style.display = "block";
            document.getElementById("createHeading").style.display = "block";
        }

        // Delete student
        async function deleteStudent(id) {
            if (!confirm("Are you sure you want to delete this student?")) return;
            let formData = new FormData();
            formData.append("action", "delete");
            formData.append("id", id);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            // document.getElementById("users").innerHTML = "";
            alert(result.message);
            loadStudents();
        }

        async function toggleStatus(id, currentStatus) {
            let newStatus = currentStatus === "active" ? "inactive" : "active";
            let formData = new FormData();
            formData.append("action", "toggle_status");
            formData.append("id", id);
            formData.append("status", newStatus);

            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            // document.getElementById("users").innerHTML = "";
            alert(result.status === "success" ? result.message : result.message);
            loadStudents();
        }

        // Load on page start
        loadStudents();
    </script>
</body>

</html>