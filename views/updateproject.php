<?php

$id = $_GET['id'];
$degree = $_GET['degree'];
$branch = $_GET['branch'];
$title = $_GET['title'];
$type = $_GET['type'];
$domain = $_GET['domain'];
$description = $_GET['description'];
$technologies = $_GET['technologies'];
$price = $_GET['price'];
$youtube_url = $_GET['youtube_url'];
$file_path_abstract = $_GET['file_path_abstract'];
$file_path_abstract = $_GET['file_path_abstract'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .main {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #updateHeading {
            color: #444;
            margin-top: 50px;
            margin-bottom: 10px;
        }

        #updateForm {
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

        #updateForm div input,
        select {
            margin-bottom: 10px;
        }

        #updateForm div select {
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

        #updateForm div button {
            background: #007bff;
            color: #fff;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            border: none;
            transition: 0.3s;
        }

        #updateForm div button:hover {
            background: #0056b3;
        }

        #updateForm div button[type="button"] {
            background: #6c757d;
        }

        #updateForm div button[type="button"]:hover {
            background: #5a6268;
        }
    </style>
</head>

<body>
    <?php include "adminnavbar.php" ?>

    <div class="main">
        <section>
            <!-- UPDATE FORM -->
            <h2 id="updateHeading" style="display: block">Update Project</h2>
            <form id="updateForm" enctype="multipart/form-data" style="display: block">
                <div>
                    <input type="hidden" name="action" value="update" />
                    <input type="hidden" name="id" id="update_id" value="<?php echo htmlspecialchars($id); ?>" />
                    <input type="text" name="degree" id="update_degree" value="<?php echo htmlspecialchars($degree); ?>" placeholder="Degree" />
                    <input type="text" name="branch" id="update_branch" value="<?php echo htmlspecialchars($branch); ?>" placeholder="Branch" />
                    <select name="type" id="update_type">
                        <option value="mini">Mini</option>
                        <option value="major">Major</option>
                    </select>
                    <input type="text" name="domain" id="update_domain" value="<?php echo htmlspecialchars($domain); ?>" placeholder="Domain" />
                    <input type="text" name="title" id="update_title" value="<?php echo htmlspecialchars($title); ?>" placeholder="Title" />
                </div>

                <div>
                    <textarea name="description" id="update_description" placeholder="Description"><?php echo htmlspecialchars($description); ?></textarea>
                    <input type="text" name="technologies" id="update_technologies" value="<?php echo htmlspecialchars($technologies); ?>" placeholder="Technologies" />
                    <input type="number" name="price" id="update_price" value="<?php echo htmlspecialchars($price); ?>" placeholder="Price" />
                    <input type="url" name="youtube_url" id="update_youtube_url" value="<?php echo htmlspecialchars($youtube_url); ?>" placeholder="YouTube URL" />
                    <input type="file" name="abstract" accept="application/pdf" value="<?php echo htmlspecialchars($degree); ?>" />
                    <input type="file" name="basepaper" accept="application/pdf" value="<?php echo htmlspecialchars($degree); ?>" />
                    <button type="submit">Update</button>
                    <button type="button" onclick="window.location='./viewproject.php'">Cancel</button>
                </div>
            </form>
        </section>
    </div>

    <?php include "./footer.php" ?>

    <script>
        const apiUrl = "../controller/ProjectController.php";

        // Update student
        document.getElementById("updateForm").addEventListener("submit", async function(e) {
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

            let formData = new FormData(this);
            try {
                let res = await fetch(apiUrl + "?action=update", {
                    method: "POST",
                    body: formData
                });
                let result = await res.json();
                alert(result.success ? "Updated!" : "Update failed");
                window.location.href = "./viewproject.php";
            } catch (err) {
                console.error("Fetch error:", err);
                alert("Update failed. Check console for details.");
            }
        });
    </script>
</body>

</html>