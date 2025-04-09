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
    <title>Contact List - Markha Valley Trek</title>
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
                <h2 class="text-center mb-4">Contact List</h2>
                
                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#contactModal">
                    Add Contact
                </button>
                <button class="btn btn-success mb-3" id="export-btn">
    <i class="fa fa-file-excel"></i> Export to Excel
</button>

                <table class="table table-bordered">
                <thead>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Enquiry</th>
        <th>Country</th>
        <th>Subject</th>
        <th>Message</th>
        <th>Created Date</th>
        <th>Actions</th>
    </tr>
</thead>

                    <tbody id="contact-list">
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>

        <div id="footer"></div>
    </div>

    <!-- Add/Edit Contact Modal -->
    <div class="modal fade" id="contactModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add/Edit Contact</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                <form id="contact-form">
    <input type="hidden" id="contact-id">
    <div class="form-group">
        <label>Name</label>
        <input type="text" id="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" id="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Enquiry Type</label>
        <select id="enquiry" class="form-control" required>
            <option value="Distributor/Dealership Enquiry">Distributor/Dealership Enquiry</option>
            <option value="Bulk Purchase/Wholesale Order">Bulk Purchase/Wholesale Order</option>
            <option value="Export Order/International Inquiry">Export Order/International Inquiry</option>
            <option value="Product Catalogue Request">Product Catalogue Request</option>
            <option value="Custom Product Requirement">Custom Product Requirement</option>
            <option value="Collaboration/Private Label">Collaboration/Private Label</option>
            <option value="Marketing/Promotional Inquiry">Marketing/Promotional Inquiry</option>
            <option value="General Business Inquiry">General Business Inquiry</option>
            <option value="Others (Please specify in comments)">Others (Please specify in comments)</option>
        </select>
    </div>
    <div class="form-group">
        <label>Country</label>
        <input type="text" id="country" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Subject</label>
        <input type="text" id="subject" class="form-control" required>
    </div>
    <div class="form-group">
        <label>Message</label>
        <textarea id="message" class="form-control" required></textarea>
    </div>
    <button type="submit" class="btn btn-success">Save</button>
</form>

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        $('#export-btn').click(function () {
    window.location.href = 'backend/export_contacts.php';
});

        $(document).ready(function() {
            // Load header, sidebar, and footer
            $('#header').load('layouts/header.html');
            $('#sidebar').load('layouts/sidebar.html');
            $('#footer').load('layouts/footer.html');
            $('.loader').fadeOut();

            // Function to load contacts
            function loadContacts() {
                $.ajax({
                    url: 'backend/get_contacts.php',
                    type: 'GET',
                    success: function(response) {
                        $('#contact-list').html(response);
                    }
                });
            }

            loadContacts();

            // Add or Update Contact
            $('#contact-form').submit(function(e) {
                e.preventDefault();
                var contactId = $('#contact-id').val();
                var formData = {
    id: contactId,
    name: $('#name').val(),
    email: $('#email').val(),
    enquiry: $('#enquiry').val(),
    country: $('#country').val(),
    subject: $('#subject').val(),
    message: $('#message').val()
};


                var url = contactId ? 'backend/update_contact.php' : 'backend/add_contact.php';

                $.post(url, formData, function(response) {
                    Swal.fire({
                        title: 'Success!',
                        text: response,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#contactModal').modal('hide');
                        loadContacts();
                    });
                });
            });

            // Handle Edit Button Click
            $(document).on('click', '.edit-btn', function() {
                var contactId = $(this).data('id'); // Get ID from button
                $.ajax({
                    url: 'backend/get_contact.php',
                    type: 'GET',
                    data: { id: contactId },
                    dataType: 'json',
                    success: function(data) {
    if (data.error) {
        Swal.fire('Error', data.error, 'error');
    } else {
        $('#contact-id').val(data.id);
        $('#name').val(data.name);
        $('#email').val(data.email);
        $('#enquiry').val(data.enquiry);
        $('#country').val(data.country);
        $('#subject').val(data.subject);
        $('#message').val(data.message);
        $('#contactModal').modal('show');
    }


                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to fetch contact data', 'error');
                    }
                });
            });

            // Delete Contact
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
                        $.post('backend/delete_contact.php', { id: id }, function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                loadContacts();
                            });
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>
