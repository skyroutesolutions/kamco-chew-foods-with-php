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
    <title>Products List - Markha Valley Trek</title>
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
                <h2 class="text-center">Products List</h2>
                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#productModal">Add Product</button>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Category</th>

                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="product-list"></tbody>
                </table>
            </div>
        </div>
        <div id="footer"></div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="product-form" method="POST">
                        <input type="hidden" id="product-id" name="product_id">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" id="product-name" name="product_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" id="product-description" name="product_description" class="form-control" required>
                        </div>
                       
                        <div class="form-group">
                            <label>Category</label>
                            <select id="category-select" name="category_id" class="form-control" required>
                                <!-- Categories will be populated here -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select id="status" name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Product Image</label>
                            <input type="file" id="product-image" name="product_image" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.tiny.cloud/1/hyzcg2gd4st7g91ic57xuyp3ta36stslzgkn47watslr8s24/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            tinymce.init({
                selector: '#product-description', // Update to match the correct input field ID
                plugins: 'lists link image table',
                toolbar: 'undo redo | bold italic | bullist numlist | link image',
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce.triggerSave();
                    });
                }
            });
        });

        $(document).ready(function () {
            // Load layouts
            $('#header').load('layouts/header.html');
            $('#sidebar').load('layouts/sidebar.html');
            $('#footer').load('layouts/footer.html');
            $('.loader').fadeOut();

            // Load products
            function loadProducts() {
                $.get('backend/get_products.php', function (response) {
                    $('#product-list').html(response);
                });
            }
            loadProducts();

            // Load categories for the dropdown
            function loadCategories() {
                $.get('backend/fetch_category.php', function (response) {
                    response.forEach(function(category) {
                        $('#category-select').append(new Option(category.name, category.id));
                    });
                }, 'json');
            }
            loadCategories();

            // Submit product form (Add or Edit)
            $('#product-form').submit(function (e) {
                e.preventDefault();
                var formData = new FormData(this);
                var url = $('#product-id').val() ? 'backend/update_product.php' : 'backend/add_product.php';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire('Success!', response, 'success').then(() => {
                            $('#productModal').modal('hide');
                            loadProducts();
                        });
                    },
                    error: function (jqXHR) {
                        var errorMessage = jqXHR.responseJSON ? jqXHR.responseJSON.message : 'Something went wrong. Please try again.';
                        Swal.fire('Error!', errorMessage, 'error');
                    }
                });
            });

            // Edit product
            $(document).on('click', '.edit-btn', function () {
                var productId = $(this).data('id');

                $.get('backend/get_product.php', { id: productId }, function (data) {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                    } else {
                        $('#product-id').val(data.id);
                        $('#product-name').val(data.name);
                        $('#product-description').val(data.description);
                        $('#status').val(data.status);
                        $('#category-select').val(data.category_id); // Set selected category
                        $('#productModal').modal('show');
                    }
                }, 'json').fail(function () {
                    Swal.fire('Error', 'Failed to load product data.', 'error');
                });
            });

            // Delete product
            $(document).on('click', '.delete-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete the product permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('backend/delete_product.php', { id: id }, function (response) {
                            Swal.fire('Deleted!', response, 'success').then(() => {
                                loadProducts();
                            });
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
