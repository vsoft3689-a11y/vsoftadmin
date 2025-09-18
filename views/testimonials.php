<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer Reviews Management</title>
    <style>
        .main {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #reviewHeading {
            margin: 30px 0;
            color: #333;
        }

        #reviewForm {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        input,
        textarea {
            width: 100%;
            padding: 10px;
            margin: 6px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        #reviewForm input {
            margin-bottom: 10px;
        }

        #btn {
            width: auto;
            padding: 10px;
            background: #06BBCC;
            color: #fff;
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
            margin-top: 5px;
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

        .load-testimonals {
            margin: 10px 10px;
        }
    </style>
</head>

<body>
    <?php include "./adminnavbar.php" ?>

    <div class="main">
        <h2 id="reviewHeading">Add Review</h2>
        <form id="reviewForm" enctype="multipart/form-data">
            <input type="hidden" name="action" value="create">
            <label>Name:</label>
            <input type="text" name="customer_name">
            <label>Designation:</label>
            <input type="text" name="designation">
            <label>Rating (1-5):</label>
            <input type="number" name="rating" min="1" max="5">
            <label>Review:</label>
            <textarea name="review_text" required></textarea>
            <label>Photo:</label>
            <input type="file" name="customer_photo" accept="image/*">
            <label>Display Order:</label>
            <input type="number" name="display_order">
            <button id="btn" type="submit">Add Review</button>
        </form>
    </div>

    <section class="load-testimonals">
        <h2>Reviews List</h2>
        <table id="reviewsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Designation</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Status</th>
                    <th>Order</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>

    <?php include "./footer.php" ?>

    <script>
        const apiUrl = "../controller/TestimonialController.php";

        function getStars(rating) {
            let stars = "";
            for (let i = 1; i <= 5; i++) {
                stars += i <= rating ? "⭐" : "☆";
            }
            return stars;
        }

        async function loadReviews() {
            let res = await fetch(apiUrl + "?action=read");
            let reviews = await res.json();
            console.log(reviews)
            let rows = "";
            reviews.forEach(r => {
                rows += `
                        <tr>
                        <td>${r.id}</td>
                        <td>${r.customer_photo_path ? `<img src="../${r.customer_photo_path}" width=120>` : ""}</td>
                        <td>${r.customer_name}</td>
                        <td>${r.designation || ""}</td>
                        <td>${getStars(r.rating)}</td>
                        <td>${r.review_text}</td>
                        <td>
                            ${r.is_approved == 1 ? "Approved" : "Not Approved"}
                        </td>
                        <td>${r.display_order}</td>
                        <td>
                        <button id="btn" onclick="toggleApproval(${r.id}, ${r.is_approved})">${r.is_approved == 1 ? "UnApprove" : "Approve"}</button>
                        <button id="btn" onclick="deleteReview(${r.id})">Delete</button>
                        </td>
                        </tr>`;
            });
            document.querySelector("#reviewsTable tbody").innerHTML = rows;
        }

        document.getElementById("reviewForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            let form = e.target;

            let customerName = form.customer_name.value.trim();
            let reviewText = form.review_text.value.trim();
            let displayOrder = form.display_order.value.trim();

            // Validate Customer Name (min 3 characters)
            if (customerName.length < 3) {
                alert("Customer Name must be at least 3 characters long.");
                form.customer_name.focus();
                e.preventDefault();
                return;
            }

            // Validate Review Text (min 5 characters)
            if (reviewText.length < 5) {
                alert("Review must be at least 5 characters long.");
                form.review_text.focus();
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
            this.reset();
            loadReviews();
        });

        async function deleteReview(id) {
            if (!confirm("Delete this review?")) return;
            let formData = new FormData();
            formData.append("action", "delete");
            formData.append("id", id);
            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            alert(result.message);
            loadReviews();
        }

        function editReview(id, name, designation, rating, review_text, display_order, is_approved) {
            document.querySelector("[name='action']").value = "update";
            document.querySelector("[name='customer_name']").value = name;
            document.querySelector("[name='designation']").value = designation;
            document.querySelector("[name='rating']").value = rating;
            document.querySelector("[name='review_text']").value = review_text;
            document.querySelector("[name='display_order']").value = display_order;

            let approvedInput = document.querySelector("[name='is_approved']");
            if (!approvedInput) {
                approvedInput = document.createElement("input");
                approvedInput.type = "hidden";
                approvedInput.name = "is_approved";
                document.getElementById("reviewForm").appendChild(approvedInput);
            }
            approvedInput.value = is_approved;

            let hiddenId = document.querySelector("[name='id']");
            if (!hiddenId) {
                hiddenId = document.createElement("input");
                hiddenId.type = "hidden";
                hiddenId.name = "id";
                document.getElementById("reviewForm").appendChild(hiddenId);
            }
            hiddenId.value = id;

            document.querySelector("button[type='submit']").innerText = "Update Review";
        }

        async function toggleApproval(id, current) {
            let formData = new FormData();
            formData.append("action", "toggle_approval");
            formData.append("id", id);
            formData.append("current", current);

            let res = await fetch(apiUrl, {
                method: "POST",
                body: formData
            });
            let result = await res.json();
            alert(result.message);
            loadReviews();
        }

        loadReviews();
    </script>
</body>

</html>