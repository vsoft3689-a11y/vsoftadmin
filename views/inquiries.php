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
    <title>User Inquiries</title>
    <style>
        .main {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        #inquiryHeading {
            margin: 20px 0;
            color: #333;
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

        .load-inquiry {
            margin: 10px 10px;
            margin-bottom: 100px;
        }
    </style>
</head>

<body>
    <?php include "./adminnavbar.php"; ?>

    <div class="main">
        <h2 id="inquiryHeading">All User Inquiries</h2>
    </div>
    <section class="load-inquiry">
        <table id="inquiries-table">
            <thead class="table-primary">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Subject</th>
                    <th>Type</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </section>
    <div id="alert-container"></div>

    <?php include "./footer.php"; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetchInquiries();

            function fetchInquiries() {
                fetch('../controller/InquiryController.php?action=read')
                    .then(response => response.json())
                    .then(data => {
                        const tbody = document.querySelector("#inquiries-table tbody");
                        tbody.innerHTML = "";
                        data.forEach(inq => {
                            const tr = document.createElement("tr");
                            tr.innerHTML = `
                                            <td>${inq.name}</td>
                                            <td>${inq.email}</td>
                                            <td>${inq.phone || '-'}</td>
                                            <td>${inq.subject}</td>
                                            <td>${inq.type}</td>
                                            <td>${inq.message}</td>
                                            <td>
                                                <span class="badge ${inq.status === 'resolved' ? 'badge-success' : 'badge-warning'}">
                                                                    ${inq.status}
                                                </span>
                                            </td>
                                            <td>
                                                <form onsubmit="return updateStatus(event, ${inq.id})">
                                                    <select name="status" class="form-select form-select-sm mb-1">
                                                        <option value="pending" ${inq.status === 'pending' ? 'selected' : ''}>Pending</option>
                                                        <option value="resolved" ${inq.status === 'resolved' ? 'selected' : ''}>Resolved</option>
                                                    </select>
                                                    <button id="btn" type="submit" class="btn btn-sm btn-success">Update</button>
                                                </form>
                                            </td>
                                            <td>
                                                <form onsubmit="return deleteInquiry(event, ${inq.id})">
                                                    <button id="btn" type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        `;
                            tbody.appendChild(tr);
                        });
                    });
            }

            window.updateStatus = function(event, id) {
                event.preventDefault();
                const form = event.target;
                const formData = new FormData(form);
                formData.append("id", id);
                formData.append("action", "updateStatus");

                fetch('../controller/InquiryController.php', {
                        method: "POST",
                        body: formData
                    }).then(res => res.json())
                    .then(response => {
                        showAlert("success", "Status updated successfully.");
                        fetchInquiries();
                    });
            };

            window.deleteInquiry = function(event, id) {
                event.preventDefault();
                if (!confirm("Are you sure you want to delete this inquiry?")) return;

                const formData = new FormData();
                formData.append("id", id);
                formData.append("action", "delete");

                fetch('../controller/InquiryController.php', {
                        method: "POST",
                        body: formData
                    }).then(res => res.json())
                    .then(response => {
                        showAlert("success", "Inquiry deleted successfully.");
                        fetchInquiries();
                    });
            };

            function showAlert(type, message) {
                const container = document.getElementById("alert-container");
                container.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
                setTimeout(() => container.innerHTML = "", 3000);
            }
        });
    </script>

</body>

</html>