<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');






$userquery = "SELECT u.*, r.RoleName
              FROM users u
              JOIN roles r ON u.roleid = r.RoleId
              WHERE u.roleid <> 1
              ORDER BY u.id ASC";
$userresult = $pdo->query($userquery); // $result now contains all rows



?>


<div class="child-wrapper">
    <div class="contacttableheading">
        <h2>Users</h2>
        <div class="table-container">
            <table id="usertable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sn = 1; // start serial number
                    foreach ($userresult as $row): ?>
                        <tr>
                            <td><?php echo $sn; ?></td> <!-- S.No -->
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['RoleName']); ?></td>

                            <td>
                                <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="edit-btn" title="Edit">
                                    <i class="fa fa-pen"></i>
                                </a>
                            </td>
                            <td>
                                <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="delete-btn" title="Delete">
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
</div>