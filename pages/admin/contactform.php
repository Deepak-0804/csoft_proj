        <?php
        if (!defined('APP_INIT')) {
            http_response_code(403);
            exit("Access denied");
        }

        $pdo    = $GLOBALS['pdo'];
        $config = $GLOBALS['config'];
        $BASE   = rtrim($config['base_url'], '/');

        $query = "SELECT * FROM contact_form where IsArchieved = 1 ORDER BY created_at ASC";
        $result = $pdo->query($query); // $result now contains all rows


        ?>


        <div class="contacttableheading">
            <h2>Contact Form</h2>
            <div class="table-container">
                <table id="contacttable">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>Company</th>
                            <th>Country</th>
                            <th>Industry</th>
                            <th>Service</th>
                            <th>Referred By</th>
                            <th>Message</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sn = 1; // start serial number
                        foreach ($result as $row): ?>
                            <tr>
                                <td><?php echo $sn; ?></td> <!-- S.No -->
                                <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['company_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['country']); ?></td>
                                <td><?php echo htmlspecialchars($row['industry']); ?></td>
                                <td><?php echo htmlspecialchars($row['services']); ?></td>
                                <td><?php echo htmlspecialchars($row['referred_by']); ?></td>
                                <td><?php echo htmlspecialchars($row['message']); ?></td>
                                <td>
                                    <a href="#" class="edit-btn" data-id="<?php echo $row['id']; ?>" title="Edit">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="#"
                                        class="delete-btn"
                                        data-id="<?php echo $row['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?>"
                                        title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>

                                </td>

                            </tr>
                        <?php
                            $sn++; // increment S.No
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Edit Contact Modal -->
            <div class="modal fade" id="editContactModal" tabindex="-1" aria-labelledby="editContactLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="editContactLabel">Edit Contact</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <form id="editContactForm">

                                <input type="hidden" name="id" id="edit_id">

                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="edit_first_name" name="first_name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="edit_last_name" name="last_name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" id="edit_email" name="email">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="tel" class="form-control" id="edit_contact_number" name="contact_number">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" class="form-control" id="edit_company_name" name="company_name">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Message</label>
                                    <textarea class="form-control" id="edit_message" name="message"></textarea>
                                </div>

                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" id="saveEditBtn" class="btn btn-primary">Save Changes</button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Delete Contact</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <p id="deleteMessage">Are you sure you want to delete this record?</p>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                            <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>

        <script>
            $(document).on('click', '.edit-btn', function(e) {
                e.preventDefault();

                let id = $(this).data('id'); // <-- HERE IS WHERE ID GOES

                $.ajax({
                    url: "<?= $BASE ?>/adminpanel/contact_get.php",
                    method: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        let data = JSON.parse(response);

                        // Fill modal fields
                        $('#edit_id').val(data.id);
                        $('#edit_first_name').val(data.first_name);
                        $('#edit_last_name').val(data.last_name);
                        $('#edit_email').val(data.email);
                        $('#edit_contact_number').val(data.contact_number);
                        $('#edit_company_name').val(data.company_name);
                        $('#edit_message').val(data.message);

                        // Show modal
                        let modal = new bootstrap.Modal(document.getElementById('editContactModal'));
                        modal.show();
                    }
                });
            });

            $('#saveEditBtn').on('click', function() {

                let id = $('#edit_id').val().trim();
                let first = $('#edit_first_name').val().trim();
                let last = $('#edit_last_name').val().trim();
                let email = $('#edit_email').val().trim();
                let phone = $('#edit_contact_number').val().trim();
                let company = $('#edit_company_name').val().trim();
                let message = $('#edit_message').val().trim();

                // Required Fields Validation
                if (!first || !last || !email || !phone || !company || !message) {
                    toastr.warning("All fields are required.");
                    return;
                }

                // OPTIONAL: Validate email format
                if (!email.includes("@") || !email.includes(".")) {
                    toastr.error("Enter a valid email address.");
                    return;
                }

                // OPTIONAL: Phone length check
                if (phone.length < 7) {
                    toastr.error("Enter a valid contact number.");
                    return;
                }

                // If validation passes → continue to save
                saveContactChanges();
            });

            function saveContactChanges() {

                $.ajax({
                    url: "<?= $BASE ?>/adminpanel/contact_update.php",
                    method: "POST",
                    data: {
                        id: $('#edit_id').val(),
                        first_name: $('#edit_first_name').val(),
                        last_name: $('#edit_last_name').val(),
                        email: $('#edit_email').val(),
                        contact_number: $('#edit_contact_number').val(),
                        company_name: $('#edit_company_name').val(),
                        message: $('#edit_message').val()
                    },
                    success: function(response) {

                        let res = JSON.parse(response);

                        if (res.success) {
                            toastr.success("Contact updated successfully.");

                            // Close modal
                            let modal = bootstrap.Modal.getInstance(document.getElementById('editContactModal'));
                            modal.hide();

                            // Reload page
                            setTimeout(() => {
                                location.reload();
                            }, 500); // small delay so toastr is visible

                        } else {
                            toastr.error("Failed to update contact.");
                        }
                    }
                });
            }

            // Open delete modal and store id
            $(document).on('click', '.delete-btn', function(e) {
                e.preventDefault();
                const id = $(this).data('id');
                const name = $(this).data('name');

                // optionally include the record title in #deleteMessage
                $('#deleteMessage').text("Are you sure you want to delete \"" + name + "\" ?");

                $('#confirmDeleteBtn').data('id', id);
                const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
                modal.show();
            });

            // Confirm and perform delete
            $(document).on('click', '#confirmDeleteBtn', function(e) {
                e.preventDefault();
                const id = $(this).data('id');

                $.ajax({
                    url: "<?= $BASE ?>/adminpanel/contact_delete.php",
                    method: "POST",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        // If your response may already be an object, guard parse
                        let res = (typeof response === 'string') ? JSON.parse(response) : response;

                        if (res.success) {
                            toastr.success('Record deleted.');
                            // close modal
                            const modalEl = document.getElementById('deleteModal');
                            const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                            modalInstance.hide();

                            // reload after small delay so user sees toastr
                            setTimeout(function() {
                                location.reload();
                            }, 500);
                        } else {
                            toastr.error(res.error || 'Delete failed.');
                        }
                    },
                    error: function(xhr, status, err) {
                        toastr.error('Server error. Check logs.');
                    }
                });
            });
        </script>