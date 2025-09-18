<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Banner Management</title>
    <style>
        .main {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #createHeading {
            margin: 20px 0;
            color: #333;
        }

        #createForm {
            margin-bottom: 30px;
            padding: 20px;
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

        #createForm input {
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
            border-radius: 5px;
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }

        #btn:hover {
            background: #06BBCC;
        }

        #btn[type="submit"] {
            margin-top: 5px;
        }

        hr {
            margin: 30px 0;
        }

        .load-banners {
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
        <h2 id="createHeading">Add New Banner</h2>
        <form id="createForm" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="create">
            <label>Banner Image:</label>
            <input type="file" name="image" required><br>
            <label>Tagline:</label>
            <input type="text" name="tagline"><br>
            <label>Sub Text:</label>
            <textarea name="sub_text"></textarea><br>
            <label>CTA Button Text:</label>
            <input type="text" name="cta_button_text"><br>
            <label>CTA Button Link:</label>
            <input type="url" name="cta_button_link"><br>
            <label>Display Order:</label>
            <input type="number" name="display_order" required><br>
            <button id="btn" type="submit">Add Banner</button>
        </form>
    </div>

    <hr>

    <section class="load-banners">
        <h2>Banner List</h2>
        <div id="bannerList"></div>
    </section>

    <?php include "./footer.php" ?>

    <script>
        const apiUrl = "../controller/BannerController.php";

        async function loadBanners() {
            let res = await fetch(apiUrl + "?action=read");
            let data = await res.json();
            console.log(data)

            if (data.length > 0) {
                document.getElementById("bannerList").innerHTML = "";
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

                th1.innerHTML = "ID";
                th2.innerHTML = "Image";
                th3.innerHTML = "Tagline";
                th4.innerHTML = "SubText";
                th5.innerHTML = "CTA";
                th6.innerHTML = "Status";
                th7.innerHTML = "Display Order";
                th8.innerHTML = "Actions";

                tr1.append(th1, th2, th3, th4, th5, th6, th7, th8);
                thead.appendChild(tr1);

                table.appendChild(thead);

                let tbody = document.createElement("tbody");
                tbody.innerHTML = "";

                data.forEach((b) => {
                    let tr = document.createElement("tr");
                    tr.innerHTML = `
                                    <td>${b.id}</td>
                                    <td><img src="../${b.image_path}" alt="Banner" width=120></td>
                                    <td>${b.tagline}</td>
                                    <td>${b.sub_text}</td>
                                    <td><a href="${b.cta_button_link || '#'}">${b.cta_button_text || ''}</a></td>
                                    <td>${b.is_active == 1 ? 'Active' : 'Inactive'}</td>
                                    <td>${b.display_order}</td>
                                    <td>
                                        <button id="btn" onclick="deleteBanner(${b.id})">Delete</button>
                                        <button id="btn" onclick="toggleStatus(${b.id}, ${b.is_active})">${b.is_active == 1 ? 'Deactivate' : 'Activate'}</button>
                                    </td>
                                `;
                    tbody.appendChild(tr);
                });
                table.appendChild(tbody);
                document.getElementById("bannerList").appendChild(table);
            } else {
                let para = document.createElement("p");
                para.innerHTML = `No banners list found!`;
                para.style.textAlign = "center";
                para.style.fontWeight = "bold";
                para.style.paddingTop = "40px"
                document.getElementById("bannerList").appendChild(para);
            }
        }

        // Create Banner slides
        document.getElementById("createForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            alert(result.status === "success" ? "Created!" : "Create failed");
            this.reset();
            loadBanners();
        });

        async function deleteBanner(id) {
            if (!confirm("Delete this banner?")) return;
            let formData = new FormData();
            formData.append("action", "delete");
            formData.append("id", id);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            alert(result.message);
            loadBanners();
        }

        async function toggleStatus(id, currentStatus) {
            let newStatus = currentStatus == 1 ? 0 : 1;
            let formData = new FormData();
            formData.append("action", "toggle_status");
            formData.append("id", id);
            formData.append("is_active", newStatus);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            alert(result.message);
            loadBanners();
        }

        loadBanners();
    </script>
</body>

</html>