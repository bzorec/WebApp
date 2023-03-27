<div class="container">
    <h2 class="mb-4">User Management</h2>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">First Name</th>
            <th scope="col">Last Name</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <th scope="row"><?= $user['id'] ?></th>
                <td><?= $user['username'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['first_name'] ?></td>
                <td><?= $user['last_name'] ?></td>
                <td>
                    <button class="btn btn-warning" data-bs-toggle="modal"
                            data-bs-target="#editUserModal<?= $user['id'] ?>" data-user-id="<?= $user['id'] ?>">Edit
                    </button>
                    <button type="button" class="btn btn-danger btn-delete-user" data-user-id="<?= $user['id'] ?>"
                            data-bs-toggle="modal" data-bs-target="#deleteUserModal<?= $user['id'] ?>">Delete
                    </button>
                </td>
            </tr>
            <div class="modal fade" id="deleteUserModal<?= $user['id'] ?>" tabindex="-1"
                 aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteUserModalLabel">Delete User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="deleteUserForm<?= $user['id'] ?>" method="POST"
                              action="/index.php?page=admin&action=delete_user">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <div class="modal-body">
                                Are you sure you want to delete this user?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="modal fade" id="editUserModal<?= $user['id'] ?>" tabindex="-1"
                 aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editUserForm<?= $user['id'] ?>" method="POST"
                              action="/index.php?page=admin&action=edit_user">
                            <div class="modal-body">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <input type="hidden" name="current_user_id" value="<?= $_SESSION['USER_ID'] ?>">
                                <div class="mb-3">
                                    <label for="editUsername" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="editUsername<?= $user['id'] ?>"
                                           name="username" required value="<?= $user['username'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="editEmail" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="editEmail<?= $user['id'] ?>"
                                           name="email" required value="<?= $user['email'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="editFirstName" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="editFirstName<?= $user['id'] ?>"
                                           name="first_name" required value="<?= $user['first_name'] ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="editLastName" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="editLastName<?= $user['id'] ?>"
                                           name="last_name" required value="<?= $user['last_name'] ?>">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $('#editUserForm<?= $user['id'] ?>').submit(function (event) {
                        event.preventDefault();
                        let formData = $(this).serialize();
                        $.ajax({
                            type: 'POST',
                            url: '/index.php?page=admin&action=edit_user',
                            data: formData,
                            dataType: 'json'
                        })
                            .done(function (data) {
                                console.log(data);
                                if (data.success) {
                                    window.location.href = '/index.php?page=admin';
                                } else {
                                    alert('Failed to update user');
                                }
                            });
                    });

                    $('#deleteUserForm<?= $user['id'] ?>').submit(function (event) {
                        event.preventDefault();
                        let formData = $(this).serialize();
                        $.ajax({
                            type: 'POST',
                            url: '/index.php?page=admin&action=delete_user',
                            data: formData,
                            dataType: 'json'
                        })
                            .done(function (data) {
                                console.log(data);
                                if (data.success) {
                                    window.location.href = '/index.php?page=admin';
                                } else {
                                    alert('Failed to update user');
                                }
                            });
                    });
                });

            </script>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/index.php?page=register" class="btn btn-success">Add User</a>
</div>