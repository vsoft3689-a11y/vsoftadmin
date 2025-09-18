<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Config Management</title>
    <style>
        .main {
            width: 100%;
            min-height: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        #configHeading {
            color: #444;
            margin: 20px;
        }

        #configForm {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input,
        textarea {
            margin: 8px 0;
            padding: 8px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        #configForm input {
            margin-bottom: 10px;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        #btn {
            width: auto;
            background: #007bff;
            color: #fff;
            padding: 10px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        #btn:hover {
            background: #0056b3;
        }

        #btn[type="submit"] {
            margin-top: 5px;
        }

        .load-siteconfig {
            margin: 10px 10px;
        }

        hr {
            margin: 30px 0;
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
        <h2 id="configHeading">Add Config</h2>
        <form id="configForm">
            <input type="hidden" name="action" value="create">
            <label>Config Key:</label>
            <input type="text" name="config_key" required>
            <label>Config Value:</label>
            <textarea name="config_value" required></textarea>
            <button id="btn" type="submit">Add Config</button>
        </form>
    </div>

    <section class="load-siteconfig">
        <h2>Config List</h2>
        <div id="siteConfig"></div>
    </section>

    <?php include "./footer.php" ?>

</body>

<script>
    const apiUrl = "../controller/SiteConfigController.php";

    async function loadConfigs() {
        let res = await fetch(apiUrl + "?action=read");
        let data = await res.json();

        if (data.length > 0) {
            document.getElementById("siteConfig").innerHTML = "";
            let table = document.createElement("table");
            let thead = document.createElement("thead");
            let tr1 = document.createElement("tr");
            let th1 = document.createElement("th");
            let th2 = document.createElement("th");
            let th3 = document.createElement("th");
            let th4 = document.createElement("th");
            let th5 = document.createElement("th");
            let th6 = document.createElement("th");

            th1.innerHTML = "ID";
            th2.innerHTML = "Key";
            th3.innerHTML = "Value";
            th4.innerHTML = "Created At";
            th5.innerHTML = "Updated At";
            th6.innerHTML = "Actions";


            tr1.append(th1, th2, th3, th4, th5, th6);
            thead.appendChild(tr1);

            table.appendChild(thead);

            let tbody = document.createElement("tbody");
            tbody.innerHTML = "";

            data.forEach((c) => {
                let tr = document.createElement("tr");
                tr.innerHTML = `
                                <tr>
                                    <td>${c.id}</td>
                                    <td>${c.config_key}</td>
                                    <td>${c.config_value}</td>
                                    <td>${c.created_at}</td>
                                    <td>${c.updated_at}</td>
                                <td>
                                    <button id="btn" onclick="editConfig(${c.id}, '${c.config_key}', \`${c.config_value}\`)">Edit</button>
                                    <button id="btn" onclick="deleteConfig(${c.id})">Delete</button>
                                </td>
                                </tr>
                                `;
                tbody.appendChild(tr);
            });
            table.appendChild(tbody);
            document.getElementById("siteConfig").appendChild(table);
        } else {
            let para = document.createElement("p");
            para.innerHTML = `No site configurations list found!`;
            para.style.textAlign = "center";
            para.style.fontWeight = "bold";
            para.style.paddingTop = "40px"
            document.getElementById("siteConfig").appendChild(para);
        }
    }

    document.getElementById("configForm").addEventListener("submit", async function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        let res = await fetch(apiUrl, {
            method: "POST",
            body: formData
        });
        let result = await res.json();
        alert(result.message);
        this.reset();
        loadConfigs();
    });

    async function deleteConfig(id) {
        if (!confirm("Delete this config?")) return;
        let formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", id);
        let res = await fetch(apiUrl, {
            method: "POST",
            body: formData
        });
        let result = await res.json();
        alert(result.message);
        loadConfigs();
    }

    function editConfig(id, key, value) {
        const form = document.getElementById("configForm");

        // fill fields
        form.querySelector("[name='action']").value = "update";
        form.querySelector("[name='config_key']").value = key;
        form.querySelector("[name='config_value']").value = value;

        // create cancel button (only once)
        let button = form.querySelector(".cancel-btn");
        if (!button) {
            button = document.createElement("button");
            button.type = "button";
            button.className = "cancel-btn";
            button.innerHTML = "Cancel";
            button.setAttribute("id", "btn")
            form.appendChild(button);

            button.addEventListener("click", () => {
                form.reset(); // clears all inputs
                form.querySelector("[name='action']").value = "create"; // back to create
                form.querySelector("button[type='submit']").innerText = "Add Config"; // reset button text
                button.remove(); // hide cancel button
            });
        }

        // hidden id
        let hiddenId = form.querySelector("[name='id']");
        if (!hiddenId) {
            hiddenId = document.createElement("input");
            hiddenId.type = "hidden";
            hiddenId.name = "id";
            form.appendChild(hiddenId);
        }
        hiddenId.value = id;

        // change submit text
        form.querySelector("button[type='submit']").innerText = "Update Config";
    }

    loadConfigs();
</script>
</body>

</html>