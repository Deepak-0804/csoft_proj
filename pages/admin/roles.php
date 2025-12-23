<?php
if (!defined('APP_INIT')) {
    http_response_code(403);
    exit("Access denied");
}

$pdo    = $GLOBALS['pdo'];
$config = $GLOBALS['config'];
$BASE   = rtrim($config['base_url'], '/');




$rolequery = "SELECT * FROM roles WHERE roleid <> 1 ORDER BY RoleId ASC";
$roleresult = $pdo->query($rolequery); // $result now contains all rows

?>

<div class="child-wrapper">
    <div class="contacttableheading">
        <div class="rolecreation">
            <h2>Roles</h2>
            <button id="createRoleBtn">Create New</button>
            <div class="role-modal" id="roleModal">
                <div class="role-modal-content">
                    <h3>Create New Role</h3>
                    <input type="text" id="roleName" placeholder="Enter role name">
                    <input type="hidden" id="csrf" name="csrf" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">
                    <div class="modal-buttons">
                        <button id="saveRole">Save</button>
                        <button id="cancelRole">Cancel</button>
                    </div>
                </div>
            </div>
            <div class="delete-modal" id="deleteModal">
                <div class="delete-modal-content">
                    <h3>Confirm Delete</h3>
                    <input type="hidden" id="csrf" name="csrf" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">
                    <p id="deleteMessage">Are you sure you want to delete this role?</p>
                    <div class="modal-buttons">
                        <button id="confirmDelete">Yes</button>
                        <button id="cancelDelete">No</button>
                    </div>
                </div>
            </div>

        </div>
        <div class="table-container">
            <table id="roletable" class="table table-hover table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th class="text-center">S.No</th>
                        <th class="text-center">Role Name</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Active</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sn = 1; // start serial number
                    foreach ($roleresult as $row): ?>
                        <tr>
                            <td><?php echo $sn; ?></td> <!-- S.No -->
                            <td><?php echo htmlspecialchars($row['RoleName']); ?></td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </td>
                            <td>
                                <span class="border me-2">
                                    <a href="edit_role.php?id=<?php echo $row['RoleId']; ?>" class="edit-btn" title="Edit">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                </span>
                                <span class="border">
                                    <a href="javascript:void(0);" class="delete-btn" data-roleid="<?php echo $row['RoleId']; ?>" data-rolename="<?php echo htmlspecialchars($row['RoleName'], ENT_QUOTES); ?>" title="Delete">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </span>

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