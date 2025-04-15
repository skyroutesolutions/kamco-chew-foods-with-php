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
    <title>subscription List - Markha Valley Trek</title>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- jQuery (Ensure it's before Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS (Make sure it's included after jQuery) -->
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
            <div class="container">
                <h2 class="text-center mb-4">subscription List</h2>
                
                <div class="mb-3">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#subscriptionModal">
                        <i class="fas fa-plus"></i> Add Subscription
                    </button>
                    <a href="backend/export_subscriptions.php" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </a>
                </div>

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            
                            <th>created Date</th>

                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="subscription-list">
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <div id="footer"></div>
    </div>

    <!-- Add/Edit subscription Modal -->
    <div class="modal fade" id="subscriptionModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit subscription</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="subscription-form">
                        <input type="hidden" id="subscription-id">
                      
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="email" class="form-control" required>
                        </div>
                       
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        $(document).ready(function() {
            // Load header, sidebar, and footer
            $('#header').load('layouts/header.html');
            $('#sidebar').load('layouts/sidebar.html');
            $('#footer').load('layouts/footer.html');
            $('.loader').fadeOut();

            // Function to load subscriptions
            function loadsubscriptions() {
                $.ajax({
                    url: 'backend/get_subscriptions.php',
                    type: 'GET',
                    success: function(response) {
                        $('#subscription-list').html(response);
                    }
                });
            }

            loadsubscriptions();

            // Add or Update subscription
            $('#subscription-form').submit(function(e) {
                e.preventDefault();
                var subscriptionId = $('#subscription-id').val();
                var formData = {
                    id: subscriptionId,
                    email: $('#email').val()
                    
                };

                var url = subscriptionId ? 'backend/update_subscription.php' : 'backend/add_subscription.php';

                $.post(url, formData, function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#subscriptionModal').modal('hide');
                        loadsubscriptions();
                    });
                });
            });

            // Handle Edit Button Click
            $(document).on('click', '.edit-btn', function() {
                var subscriptionId = $(this).data('id'); // Get ID from button
                $.ajax({
                    url: 'backend/get_subscription.php',
                    type: 'GET',
                    data: { id: subscriptionId },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            Swal.fire('Error', data.error, 'error');
                        } else {
                            $('#subscription-id').val(data.id);
                            $('#email').val(data.email);

                            $('#subscriptionModal').modal('show');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to fetch subscription data', 'error');
                    }
                });
            });

            // Delete subscription
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.post('backend/delete_subscription.php', { id: id }, function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                loadsubscriptions();
                            });
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
