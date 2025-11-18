        <?php
        //require_once $_SERVER['DOCUMENT_ROOT'] . '/csoft_proj/app/auth.php';
        if (!defined('APP_INIT')) {
            http_response_code(403);
            exit("Access denied");
        }

        $pdo    = $GLOBALS['pdo'];
        $config = $GLOBALS['config'];
        $BASE   = rtrim($config['base_url'], '/');

        require_auth();  // forces login
        if (!can('admin')) {
            die("You are not authorized to access this page.");
        }

        $query = "SELECT * FROM contact_form ORDER BY created_at ASC";
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
                                    <a href="edit_contact.php?id=<?php echo $row['id']; ?>" class="edit-btn" title="Edit">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                </td>
                                <td>
                                    <a href="delete_contact.php?id=<?php echo $row['id']; ?>" class="delete-btn" title="Delete">
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
        </div>