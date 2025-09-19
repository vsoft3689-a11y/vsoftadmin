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
    <title>Price Services</title>
    <style>
        .main {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #serviceHeading {
            margin: 20px 0;
            color: #333;
        }

        #serviceForm {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
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

        #serviceForm input {
            margin-bottom: 10px;
        }

        #serviceForm select {
            margin-bottom: 10px;
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
            background: #06BBCC;
        }

        #btn[type="button"]:hover {
            background: #06BBCC;
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

        .load-price {
            margin: 10px 10px;
        }
    </style>
</head>

<body>
    <?php include "./adminnavbar.php" ?>

    <div class="main">
        <h2 id="serviceHeading">Manage Price Packages</h2>
        <form id="serviceForm">
            <input type="hidden" name="id" id="service_id">
            <!-- <label>Service Type</label>
            <select name="service_type" id="service_type">
                <option value="">-- Select --</option>
                <option value="project">Project</option>
                <option value="internship">Internship</option>
                <option value="training">Training</option>
            </select> -->
            <label>Service Type</label>
            <input type="text" name="service_type" id="service_type">

            <label>Package Name</label>
            <input type="text" name="package_name" id="package_name">

            <label>Description</label>
            <textarea name="description" id="description"></textarea>

            <label>Original Price</label>
            <input type="number" step="0.01" name="original_price" id="original_price">

            <label>Discounted Price</label>
            <input type="number" step="0.01" name="discounted_price" id="discounted_price">

            <label>Duration</label>
            <input type="text" name="duration" id="duration" placeholder="e.g., 4 Weeks">

            <label>Button Link</label>
            <input type="text" name="button_link" id="button_link">

            <div style="display:flex; align-items:center;">
                <label for="is_featured">Featured</label>
                <input style="margin-right: 350px;" type="checkbox" name="is_featured" id="is_featured">
            </div>
            <br>
            <button id="btn" type="submit">Save Service</button>
        </form>
    </div>

    <section class="load-price">
        <h2>Package Prices</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Package</th>
                    <th>Description</th>
                    <th>Original</th>
                    <th>Discounted</th>
                    <th>Duration</th>
                    <th>Featured</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="servicesTable"></tbody>
        </table>
    </section>

    <?php include "./footer.php" ?>

    <script>
        const apiUrl = "../controller/PricingPackageController.php";

        async function loadServices() {
            let res = await fetch(apiUrl + "?action=read");
            let data = await res.json();
            let rows = "";
            data.forEach(s => {
                rows += `<tr>
                            <td>${s.id}</td>
                            <td>${s.service_type}</td>
                            <td>${s.package_name}</td>
                            <td>${s.description || ""}</td>
                            <td>${s.original_price}</td>
                            <td>${s.discounted_price || ""}</td>
                            <td>${s.duration || ""}</td>
                            <td>${s.is_featured == 1 ? "Yes" : "No"}</td>
                            <td>
                                <button id="btn" onclick="editService(${s.id}, '${s.service_type}', '${s.package_name}', \`${s.description || ""}\`, '${s.original_price}', '${s.discounted_price || ""}', '${s.duration || ""}', '${s.button_link || ""}', ${s.is_featured})">Edit</button>
                                <button id="btn" onclick="deleteService(${s.id})">Delete</button>
                            </td>
                        </tr>`;
            });
            document.getElementById("servicesTable").innerHTML = rows;
        }

        document.getElementById("serviceForm").addEventListener("submit", async e => {
            e.preventDefault();

            let serviceType = document.getElementById("service_type");
            let packageName = document.getElementById("package_name");
            let originalPrice = document.getElementById("original_price");

            // Validate Service Type
            if (serviceType.value === "") {
                alert("Please select a service type.");
                serviceType.focus();
                e.preventDefault();
                return;
            }

            // Validate Package Name (min 3 chars)
            if (packageName.value.trim().length < 3) {
                alert("Package name must be at least 3 characters long.");
                packageName.focus();
                e.preventDefault();
                return;
            }

            // Validate Original Price (must be positive)
            if (originalPrice.value === "" || parseFloat(originalPrice.value) <= 0) {
                alert("Original price must be a positive number.");
                originalPrice.focus();
                e.preventDefault();
                return;
            }

            let formData = new FormData(e.target);
            formData.append("action", document.getElementById("service_id").value ? "update" : "create");

            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            alert(await res.text());
            e.target.reset();
            document.getElementById("service_id").value = "";
            loadServices();
        });

        function editService(id, type, name, desc, original, discount, duration, link, featured) {
            document.getElementById("service_id").value = id;
            document.getElementById("service_type").value = type;
            document.getElementById("package_name").value = name;
            document.getElementById("description").value = desc;
            document.getElementById("original_price").value = original;
            document.getElementById("discounted_price").value = discount;
            document.getElementById("duration").value = duration;
            document.getElementById("button_link").value = link;
            document.getElementById("is_featured").checked = featured == 1;
        }

        async function deleteService(id) {
            if (!confirm("Delete this service?")) return;
            let formData = new FormData();
            formData.append("action", "delete");
            formData.append("id", id);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            alert(await res.text());
            loadServices();
        }

        loadServices();
    </script>
</body>

</html>