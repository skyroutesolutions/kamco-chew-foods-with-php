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
    <title>Blog List - Markha Valley Trek</title>
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
                <h2 class="text-center">Blog List</h2>
                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#blogModal">Add Blog</button>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Images</th>
                          
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="blog-list"></tbody>
                </table>
            </div>
        </div>
        <div id="footer"></div>
    </div>

    <!-- Add/Edit Blog Modal -->
    <div class="modal fade" id="blogModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit Blog</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="blog-form" enctype="multipart/form-data" method="POST">
                        <input type="hidden" id="blog-id" name="blog_id">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Blog Image</label>
                            <input type="file" id="image" name="image" class="form-control" accept="image/*">
                        </div>
                        

                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="description" name="description" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <div class="modal fade" id="descriptionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Full Description</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p id="modal-description"></p>
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

            // Load blogs
            function loadBlogs() {
                $.get('backend/get_blogs.php', function (response) {
                    $('#blog-list').html(response);
                });
            }
            loadBlogs();

            // Submit blog form (Add or Edit)
            $('#blog-form').submit(function (e) {
                e.preventDefault();

             

                var formData = new FormData(this);
                var url = $('#blog-id').val() ? 'backend/update_blog.php' : 'backend/add_blog.php';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire('Success!', response, 'success').then(() => {
                            $('#blogModal').modal('hide');
                            loadBlogs();
                        });
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            });
            // Edit blog
            $(document).on('click', '.edit-btn', function () {
                var blogId = $(this).data('id');

                $.get('backend/get_blog.php', { id: blogId }, function (data) {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                    } else {
                        $('#blog-id').val(data.id);
                        $('#name').val(data.name);
                        $('#duration').val(data.duration);
                       
                      


                        // **Show image preview**
                        var imagePreview = "";
                        if (data.image) {
                            imagePreview = `
                                <div class="form-group">
                                    <label>Current Image</label>
                                    <img src='backend/uploads/blogs/${data.image}' width='80' style='margin:5px; border-radius:5px; display:block;'>
                                    <input type="hidden" name="existing_image" value="${data.image}">
                                </div>`;
                        } else {
                            imagePreview = '<p>No image uploaded</p>';
                        }

                        $('#image-upload-section').html(imagePreview).show();

                        $('#blogModal').modal('show');
                    }
                }, 'json').fail(function (jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText);
                    Swal.fire('Error', 'Failed to load blog data.', 'error');
                });
            });

            // Delete blog
            $(document).on('click', '.delete-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete the blog permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('backend/delete_blog.php', { id: id }, function (response) {
                            Swal.fire('Deleted!', response, 'success').then(() => {
                                loadBlogs();
                            });
                        });
                    }
                });
            });
            // View blog
            $(document).on('click', '.view-btn', function () {
                var blogId = $(this).data('id');
                $.get('backend/get_blog.php', { id: blogId }, function (data) {
                    if (data && data.description) {
                        $('#modal-description').html(data.description);  // Use .html() to show full description with HTML
                        $('#descriptionModal').modal('show');  // Show the modal
                    } else {
                        Swal.fire('Error', 'Blog not found', 'error');
                    }
                }, 'json').fail(function () {
                    Swal.fire('Error', 'Something went wrong. Please try again later.', 'error');
                });
            });
        });
    </script>
</body>
</html>
