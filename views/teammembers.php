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
    <meta charset="UTF-8">
    <title>Team Management</title>
    <style>
        .main {
            width: 100%;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }


        #createHeading {
            margin: 20px 0;
            color: #444;
        }

        form {
            width: 500px;
            max-width: 90%;
            padding: 15px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background: #fff;
        }

        input,
        textarea {
            margin: 8px 0;
            padding: 8px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input {
            margin-bottom: 10px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        #btn {
            width: auto;
            background: #06BBCC;
            color: #fff;
            padding: 10px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        #btn:hover {
            background: #06BBCC;
        }

        #btn[type="submit"] {
            margin-top: 10px;
        }

        hr {
            margin: 30px 0;
        }

        .load-teams {
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
            background: #06BBCC;
            color: #fff;
            text-align: center;
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
        <h2 id="createHeading">Add Team Member</h2>
        <form id="createForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="create">

            <label>Name:</label>
            <input type="text" name="name">

            <label>Designation:</label>
            <input type="text" name="designation">

            <label>Bio:</label>
            <textarea name="bio"></textarea>

            <label>Profile Picture:</label>
            <input type="file" name="profile_picture">

            <label>Facebook URL:</label>
            <input type="url" name="social_facebook">

            <label>Twitter URL:</label>
            <input type="url" name="social_twitter">

            <label>LinkedIn URL:</label>
            <input type="url" name="social_linkedin">

            <label>Display Order:</label>
            <input type="number" name="display_order">

            <button id="btn" type="submit">Add Member</button>
        </form>

        <!-- UPDATE FORM -->
        <h2 id="updateHeading" style="display:none;">Update Team Member</h2>
        <form id="updateForm" method="POST" enctype="multipart/form-data" style="display:none;">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" id="update_id">

            <label>Name:</label>
            <input type="text" name="name" id="update_name">

            <label>Designation:</label>
            <input type="text" name="designation" id="update_designation">

            <label>Bio:</label>
            <textarea name="bio" id="update_bio"></textarea>

            <label>Profile Picture:</label>
            <input type="file" name="profile_picture">

            <label>Facebook:</label>
            <input type="url" name="social_facebook" id="update_facebook">

            <label>Twitter:</label>
            <input type="url" name="social_twitter" id="update_twitter">

            <label>LinkedIn:</label>
            <input type="url" name="social_linkedin" id="update_linkedin">

            <label>Display Order:</label>
            <input type="number" name="display_order" id="update_order">

            <button type="submit">Update Member</button>
            <button id="btn" type="button" onclick="cancelUpdate()">Cancel</button>
        </form>
    </div>

    <section class="load-teams">
        <h2>Team Members</h2>
        <div id="teamList"></div>
    </section>

    <?php include "./footer.php" ?>

    <script>
        const apiUrl = "../controller/TeamMemberController.php";

        async function loadMembers() {
            let res = await fetch(apiUrl + "?action=read");
            let data = await res.json();

            if (data.length > 0) {
                document.getElementById("teamList").innerHTML = "";
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
                th2.innerHTML = "Image";
                th3.innerHTML = "Name";
                th4.innerHTML = "Designation";
                th5.innerHTML = "BIO";
                th6.innerHTML = "Social_URL";
                th7.innerHTML = "Status";
                th8.innerHTML = "Display Order";
                th9.innerHTML = "Actions";

                tr1.append(th1, th2, th3, th4, th5, th6, th7, th8, th9);
                thead.appendChild(tr1);

                table.appendChild(thead);

                let tbody = document.createElement("tbody");
                tbody.innerHTML = "";

                data.forEach((m) => {
                    let tr = document.createElement("tr");
                    tr.innerHTML = `
                                    <td>${m.id}</td>
                                    <td><img src="../${m.profile_picture_path}" alt="${m.name}" width=100></td>
                                    <td>${m.name}</td>
                                    <td>${m.designation}</td>
                                    <td>${m.bio}</td>
                                    <td>
                                        <a href="${m.social_facebook || '#'}">Facebook</a> <br>
                                        <a href="${m.social_twitter || '#'}">Twitter</a>  <br>
                                        <a href="${m.social_linkedin || '#'}">LinkedIn</a>
                                    </td>
                                    <td>${m.is_active == 1 ? 'Active' : 'Inactive'}</td>
                                    <td>${m.display_order}</td>
                                    <td>
                                        <button id="btn" onclick="editMember(${m.id}, '${m.name}', '${m.designation}', \`${m.bio || ''}\`, '${m.social_facebook || ''}', '${m.social_twitter || ''}', '${m.social_linkedin || ''}', ${m.display_order})">Edit</button>
                                        <button id="btn" onclick="toggleStatus(${m.id}, ${m.is_active})">${m.is_active == 1 ? 'Deactivate' : 'Activate'}</button>
                                        <button id="btn" onclick="deleteMember(${m.id})">Delete</button>
                                    </td>
                                 `;
                    tbody.appendChild(tr);
                });
                table.appendChild(tbody);
                document.getElementById("teamList").appendChild(table);
            } else {
                let para = document.createElement("p");
                para.innerHTML = `No team members list found!`;
                para.style.textAlign = "center";
                para.style.fontWeight = "bold";
                para.style.paddingTop = "40px"
                document.getElementById("teamList").appendChild(para);
            }
        }

        // Create team member
        document.getElementById("createForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            let form = e.target;

            let name = form.name.value.trim();
            let designation = form.designation.value.trim();
            let displayOrder = form.display_order.value.trim();

            // Validate Name (min 3 characters)
            if (name.length < 3) {
                alert("Name must be at least 3 characters long.");
                form.name.focus();
                e.preventDefault();
                return;
            }

            // Validate Designation (min 2 characters)
            if (designation.length < 2) {
                alert("Designation must be at least 2 characters long.");
                form.designation.focus();
                e.preventDefault();
                return;
            }

            // Validate Display Order (positive number)
            if (displayOrder === "" || parseInt(displayOrder) <= 0) {
                alert("Display Order must be a positive number.");
                form.display_order.focus();
                e.preventDefault();
                return;
            }

            let formData = new FormData(this);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            alert(result.status === "success" ? "Created!" : "Create failed");
            this.reset();
            loadMembers();
        });

        // Update team member
        document.getElementById("updateForm").addEventListener("submit", async function(e) {
            e.preventDefault();

            let form = e.target;

            let name = form.name.value.trim();
            let designation = form.designation.value.trim();
            let displayOrder = form.display_order.value.trim();

            // Validate Name (min 3 characters)
            if (name.length < 3) {
                alert("Name must be at least 3 characters long.");
                form.name.focus();
                e.preventDefault();
                return;
            }

            // Validate Designation (min 2 characters)
            if (designation.length < 2) {
                alert("Designation must be at least 2 characters long.");
                form.designation.focus();
                e.preventDefault();
                return;
            }

            // Validate Display Order (positive number)
            if (displayOrder === "" || parseInt(displayOrder) <= 0) {
                alert("Display Order must be a positive number.");
                form.display_order.focus();
                e.preventDefault();
                return;
            }

            let formData = new FormData(this);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            alert(result.message);
            cancelUpdate();
            loadMembers();
        });

        async function deleteMember(id) {
            if (!confirm("Delete this member?")) return;
            let fd = new FormData();
            fd.append("action", "delete");
            fd.append("id", id);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: fd
            });
            let result = await res.json();
            alert(result.message);
            loadMembers();
        }

        async function toggleStatus(id, currentStatus) {
            let newStatus = currentStatus == 1 ? 0 : 1;
            let fd = new FormData();
            fd.append("action", "toggle_status");
            fd.append("id", id);
            fd.append("is_active", newStatus);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: fd
            });
            let result = await res.json();
            alert(result.message);
            loadMembers();
        }

        function editMember(id, name, designation, bio, facebook, twitter, linkedin, order) {
            document.getElementById("createForm").style.display = "none";
            document.getElementById("createHeading").style.display = "none";
            document.getElementById("updateForm").style.display = "block";
            document.getElementById("updateHeading").style.display = "block";

            document.getElementById("update_id").value = id;
            document.getElementById("update_name").value = name;
            document.getElementById("update_designation").value = designation;
            document.getElementById("update_bio").value = bio;
            document.getElementById("update_facebook").value = facebook;
            document.getElementById("update_twitter").value = twitter;
            document.getElementById("update_linkedin").value = linkedin;
            document.getElementById("update_order").value = order;

            window.scrollTo(0, 0);
        }

        function cancelUpdate() {
            document.getElementById("updateForm").style.display = "none";
            document.getElementById("updateHeading").style.display = "none";
            document.getElementById("createForm").style.display = "block";
            document.getElementById("createHeading").style.display = "block";
        }

        loadMembers();
    </script>
</body>

</html>