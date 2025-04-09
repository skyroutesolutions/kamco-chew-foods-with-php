<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>Categories List - Markha Valley Trek</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <style>
        .itinerary-cont{
            color: black;
        }
        h1,h2,h3,h4,h5,h6{
            color: black;
        }
        .modal-dialog {
            max-width: 709px;
            margin: 1.75rem auto;
        }
    </style>
</head>
<body>
    <div class="loader">
        <div class="spinner">
            <img src="assets/images/loader.gif" alt="Loading...">
        </div>
    </div>

    <div class="page-wrapper">
        <div id="header"></div>
        <div id="sidebar"></div>
        <div class="main-content p-4">
            <div class="container mt-4">
                <h2 class="text-center">Categories List</h2>
                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#categoryModal">Add Category</button>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="category-list"></tbody>
                </table>
            </div>
        </div>
        <div id="footer"></div>
    </div>

    <!-- Add/Edit Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit Category</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="category-form" method="POST">
                        <input type="hidden" id="category-id" name="category_id">
                        <div class="form-group">
                            <label>Category Name</label>
                            <input type="text" id="category-name" name="category_name" class="form-control" required>
                        </div>
                      
                        <div class="form-group">
                            <label>Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Load layouts
            $('#header').load('layouts/header.html');
            $('#sidebar').load('layouts/sidebar.html');
            $('#footer').load('layouts/footer.html');
            $('.loader').fadeOut();

            // Load categories
            function loadCategories() {
                $.get('backend/get_categories.php', function (response) {
                    $('#category-list').html(response);
                });
            }
            loadCategories();

            // Submit category form (Add or Edit)
            $('#category-form').submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                var url = $('#category-id').val() ? 'backend/update_category.php' : 'backend/add_category.php';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire('Success!', response, 'success').then(() => {
                            $('#categoryModal').modal('hide');
                            loadCategories();
                        });
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            });

            // Edit category
            $(document).on('click', '.edit-btn', function () {
                var categoryId = $(this).data('id');

                $.get('backend/get_category.php', { id: categoryId }, function (data) {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                    } else {
                        $('#category-id').val(data.id);
                        $('#category-name').val(data.name);
                        $('#status').val(data.status);
                        $('#categoryModal').modal('show');
                    }
                }, 'json').fail(function () {
                    Swal.fire('Error', 'Failed to load category data.', 'error');
                });
            });

            // Delete category
            $(document).on('click', '.delete-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete the category permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('backend/delete_category.php', { id: id }, function (response) {
                            Swal.fire('Deleted!', response, 'success').then(() => {
                                loadCategories();
                            });
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
