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
    <title>User Management</title>
    <style>
        #userHeading {
            margin: 20px 0;
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

        .load-users {
            margin: 10px 10px;
        }
    </style>
</head>

<body>
    <?php include "./adminnavbar.php" ?>

    <section class="load-users">
        <h2 id="userHeading">User List</h2>
        <div id="users"></div>
    </section>

    <?php include "./footer.php" ?>
    <script>
        const apiUrl = "../controller/UserController.php";

        // Load students
        async function loadStudents() {
            let res = await fetch(apiUrl + "?action=read");
            let data = await res.json();

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
                th9.innerHTML = "Status";

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
                                    <td>
                                        ${s.status === "active" ? "Active" : "Deactive"}                                       
                                        </td>
                                `;
                    tbody.appendChild(tr);
                });
                table.appendChild(tbody);
                document.getElementById("users").appendChild(table);
            } else {
                let para = document.createElement("p");
                para.innerHTML = `No users list found!`;
                para.style.textAlign = "center";
                para.style.fontWeight = "bold";
                para.style.paddingTop = "40px"
                document.getElementById("users").appendChild(para);
            }
        }
        // Load on page start
        loadStudents();
    </script>
</body>

</html>