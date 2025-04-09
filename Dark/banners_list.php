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
    <title>banners List - Markha Valley Trek</title>
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
                <h2 class="text-center">banners List</h2>
                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#bannersModal">Add banners</button>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Images</th>
                          
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="banners-list"></tbody>
                </table>
            </div>
        </div>
        <div id="footer"></div>
    </div>

    <!-- Add/Edit banners Modal -->
    <div class="modal fade" id="bannersModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit banners</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="banners-form" enctype="multipart/form-data" method="POST">
                        <input type="hidden" id="banners-id" name="banners_id">
                  
                        
                        <div class="form-group">
                            <label>Upload Banner Images</label>
                            <div class="image-preview mb-2" id="image-preview"></div>
                            <input type="file" id="images" name="images[]" class="form-control" accept="image/*" multiple required>
                            <small class="form-text text-muted">Allowed formats: JPG, PNG, GIF. Max size: 2MB per image.</small>
                        </div>
                        

                    
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

   
    
    <script src="https://cdn.tiny.cloud/1/hyzcg2gd4st7g91ic57xuyp3ta36stslzgkn47watslr8s24/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

    <script>
       
        // Image preview functionality
        function previewImages(input) {
            var preview = $('#image-preview');
            preview.empty();
            
            if (input.files) {
                for (var i = 0; i < input.files.length; i++) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        preview.append('<img src="'+e.target.result+'" class="img-thumbnail mr-2 mb-2" width="100">');
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }
        }

        $(document).ready(function () {
            $('#images').change(function() {
                previewImages(this);
            });
        });


        $(document).ready(function () {
            // Load layouts
            $('#header').load('layouts/header.html');
            $('#sidebar').load('layouts/sidebar.html');
            $('#footer').load('layouts/footer.html');
            $('.loader').fadeOut();

            // Load bannerss
            function loadbannerss() {
                $.get('backend/get_bannerss.php', function (response) {
                    $('#banners-list').html(response);
                });
            }
            loadbannerss();

            // Submit banners form (Add or Edit)
            $('#banners-form').submit(function (e) {
                e.preventDefault();

                // Ensure TinyMCE content is saved
                tinymce.triggerSave();

                var formData = new FormData(this);
                var url = $('#banners-id').val() ? 'backend/update_banners.php' : 'backend/add_banners.php';

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        Swal.fire('Success!', response, 'success').then(() => {
                            $('#bannersModal').modal('hide');
                            loadbannerss();
                        });
                    },
                    error: function () {
                        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            });
            // Edit banners
            $(document).on('click', '.edit-btn', function () {
                var bannersId = $(this).data('id');

                $.get('backend/get_banners.php', { id: bannersId }, function (data) {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                    } else {
                        $('#banners-id').val(data.id);
                      
                        // **Show images preview**
                        var imagePreview = "";
                        if (Array.isArray(data.images) && data.images.length > 0) {
                            data.images.forEach((img, index) => {
                                imagePreview += `
                                    <div class="form-group">
                                        <label>Current Image ${index + 1}</label>
                                        <img src='backend/uploads/bannerss/${img}' width='80' style='margin:5px; border-radius:5px; display:block;'>
                                        <input type="hidden" name="existing_images[]" value="${img}">
                                    </div>`;
                            });
                        } else {
                            imagePreview = '<p>No images uploaded</p>';
                        }

                        $('#image-upload-section').html(imagePreview).show();

                        $('#bannersModal').modal('show');
                    }
                }, 'json').fail(function (jqXHR, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown, jqXHR.responseText);
                    Swal.fire('Error', 'Failed to load banners data.', 'error');
                });
            });

            // Delete banners
            $(document).on('click', '.delete-btn', function () {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will delete the banners permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('backend/delete_banners.php', { id: id }, function (response) {
                            Swal.fire('Deleted!', response, 'success').then(() => {
                                loadbannerss();
                            });
                        });
                    }
                });
            });
            // View banners
            $(document).on('click', '.view-btn', function () {
                var bannersId = $(this).data('id');
                $.get('backend/get_banners.php', { id: bannersId }, function (data) {
                    if (data && data.description) {
                        $('#modal-description').html(data.description);  // Use .html() to show full description with HTML
                        $('#descriptionModal').modal('show');  // Show the modal
                    } else {
                        Swal.fire('Error', 'banners not found', 'error');
                    }
                }, 'json').fail(function () {
                    Swal.fire('Error', 'Something went wrong. Please try again later.', 'error');
                });
            });
        });
    </script>
</body>
</html>
