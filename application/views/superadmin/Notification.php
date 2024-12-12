<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-bell"></i> Notifications</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><?= $page ?></li>
            <li class="breadcrumb-item"><a href="<?= base_url('superadmin/dashboard') ?>"><i class="fa fa-home fa-lg"></i> Home</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-lg-12 p-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 p-2">
                            <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#add-new-notification">Add New Notification</button>
                        </div>
                        <div class="col-lg-12 p-2">
                            <div class="table-responsive">
                                <table class="table w-100 table-bordered notificationsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Notification Title</th>
                                            <th>Description</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    <?php if (!empty($notifications)): ?>
        <?php foreach ($notifications as $index => $notification): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?php echo htmlspecialchars($notification['title']); ?></td>
                <td><?php echo htmlspecialchars($notification['description']); ?></td>
                <td><?php echo date('F j, Y, g:i a', strtotime($notification['created_at'])); ?></td>
                <td>
                    <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#edit-notification" 
                        data-id="<?= $notification['id'] ?>" 
                        data-title="<?= htmlspecialchars($notification['title']) ?>" 
                        data-description="<?= htmlspecialchars($notification['description']) ?>">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button class="btn btn-danger btn-sm delete_notification" 
                        notification_id="<?= $notification['id'] ?>">
                        <i class="fa fa-trash"></i> 
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" class="text-center">No notifications available.</td>
        </tr>
    <?php endif; ?>
</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Notification Modal -->
    <div class="modal fade" id="add-new-notification" tabindex="-1" role="dialog" aria-labelledby="add-new-notification" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New Notification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="addNotification" class="smooth-submit" method="post" action="<?= base_url('admin_master/add_notification') ?>">
                    <div class="form-body">
                        <div class="row m-0 p-2">
                            <div class="col-lg-12 p-2">
                                <div class="form-group">
                                    <label for="notification_title">Notification Title</label>
                                    <input type="text" class="form-control" id="notification_title" name="title" required="true">
                                </div>
                            </div>
                            <div class="col-lg-12 p-2">
                                <div class="form-group">
                                    <label for="notification_description">Description</label>
                                    <textarea class="form-control" id="notification_description" name="description" required="true"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer col-lg-12">
                            <button class="btn btn-danger float-right" data-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary float-right">Save</button>
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>


   <!-- Edit Notification Modal -->
<div class="modal fade" id="edit-notification" tabindex="-1" role="dialog" aria-labelledby="edit-notification" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Notification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="updateNotification" class="smooth-submit" method="post" action="<?= base_url('admin_master/update_notification') ?>">
                <div class="form-body">
                    <div class="row m-0 p-2">
                        <div class="col-lg-12 p-2">
                            <div class="form-group">
                                <label for="getnotification_title">Notification Title</label>
                                <input type="hidden" id="notification_id" name="id">
                                <input type="text" class="form-control" id="getnotification_title" name="title" required="true">
                            </div>
                        </div>
                        <div class="col-lg-12 p-2">
                            <div class="form-group">
                                <label for="getnotification_description">Description</label>
                                <textarea class="form-control" id="getnotification_description" name="description" required="true"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer col-lg-12">
                        <button class="btn btn-danger float-right" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary float-right">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>$(document).on('click', '.delete_notification', function() {
    const notificationId = $(this).attr('notification_id'); // Get the notification ID
    if (confirm("Are you sure you want to delete this notification?")) {
        $.ajax({
            url: '<?= base_url('admin_master/delete_notification') ?>',
            type: 'POST',
            data: { id: notificationId },
            success: function(response) {
                // Handle success response
                const data = JSON.parse(response);
                alert(data.message);
                // Optionally refresh the notifications list
                location.reload();
            },
            error: function(xhr, status, error) {
                // Handle error response
                alert('An error occurred: ' + error);
            }
        });
    }
});</script>
<script>$(document).on('click', '.btn-warning', function() {
    const notificationId = $(this).data('id');
    const notificationTitle = $(this).data('title');
    const notificationDescription = $(this).data('description');

    // Set the values in the modal
    $('#notification_id').val(notificationId);
    $('#getnotification_title').val(notificationTitle);
    $('#getnotification_description').val(notificationDescription);
});</script>
<script>
$(document).ready(function() {
    var base_url = $("base").attr('ancher');
    var notificationTable = $('.notificationTables').DataTable({
    serverSide: false,
    ajax: base_url + 'admin_master/notifications',
    "columns": [
        {"data": "sr_no"},
        {"data": "title"},
        {"data": "description"},
        {"data": "action"}
    ]
});

$("#addNotification").on("aftersubmit", function (e, data) {
    console.log("Submission event triggered"); // Debugging line
    console.log("Response data: ", data); // Debugging line
    $('#pleasewait').modal('hide');
    toastr[data.type](data.message);
    if (data.type === 'success') {
        // Refresh the entire page
        location.reload(); // This will reload the page
    }
});

$("#updateNotification").on("aftersubmit", function (e, data) {
    console.log("Submission event triggered"); // Debugging line
    console.log("Response data: ", data); // Debugging line
    $('#pleasewait').modal('hide');
    toastr[data.type](data.message);
    if (data.type === 'success') {
        // Refresh the entire page
        location.reload(); // This will reload the page
    }
});



});
</script>
</main>
