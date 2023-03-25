<?php
include('db.php');
$query = mysqli_query($con, "SELECT date(a.create_date) cas_date,time(a.create_date) cas_time,
a.station_id, a.mass_of_gas,b.`stationary_cascade_reorder_point` 
FROM luag_schedular a,luag_station_equipment_master b 
WHERE a.`sl_no` IN (SELECT MAX(`sl_no`) FROM luag_schedular GROUP BY `station_id`) 
and a.station_id=b.`station_id`");
?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Data in PhP</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
</head>

<body>

    <div class="container">
        <br />
        <div class="col-md-2">
        </div>
        <div class="col-md-8">
            <div class="table-responsive">
                <div>
                    <table class="table table-bordered">
                        <tr>
                            <th width="70%">Title</th>
                            <th width="30%">edit</th>
                            <th width="30%">View</th>
                        </tr>
                        <?php while ($row = mysqli_fetch_array($query)) {

                        ?>
                            <tr>
                                <td><?php echo $row['station_id']; ?></td>
                                <td><a type="button" class="btn btn-info btn-xs edit_data" data-toggle="modal" data-keyboard="false" data-backdrop="static" data-target="#update_data" data-id="<?= $row['cas_date']; ?>" data-title="<?= $row['station_id']; ?>" data-description="<?= $row['cas_time']; ?>" data-uploaded_on="<?= $row['mass_of_gas']; ?>">Edit</a></td>
                                <td><input type="button" name="view" value="view" id="<?php echo $row["station_id"]; ?>" class="btn btn-info btn-xs view_data" /></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- ############################################################################################### -->
    <!-- View Data Modal-->
    <div id="dataModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">View Details</h4>
                </div>
                <div class="modal-body" id="employee_detail">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ############################################################################################### -->
    <!-- Update data-->
    <div class="modal fade" id="update_data" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-edit"></i> Update Details</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Title</label>
                        <input type="text" class="form-control" id="title1" name="title1">
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlTextarea1"> Description</label>
                        <textarea class="form-control" id="description1" name="description1" rows="3"></textarea>
                    </div>
                    <input type="hidden" name="id_modal" id="id_modal" class="form-control-sm">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="update_detail" class="btn btn-primary" value="Update">Update</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ############################################################################################### -->
</body>

</html>

<script>
    $(document).ready(function() {
        $(document).on('click', '.view_data', function() {
            var employee_id = $(this).attr("id");
            $.ajax({
                url: "insert.php",
                method: "POST",
                data: {
                    employee_id: employee_id
                },
                success: function(data) {
                    $('#employee_detail').html(data);
                    $('#dataModal').modal('show');
                }
            });
        });
        $(function() {
            $('#update_data').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var title = button.data('title');
                var descripton = button.data('description');
                var modal = $(this);
                modal.find('#title1').val(title);
                modal.find('#description1').val(descripton);
                modal.find('#id_modal').val(id);
            });
        });
        $(document).on('click', '#update_detail', function() {
            var id = $('#id_modal').val();
            var title1 = $('#title1').val();
            var description1 = $('#description1').val();
            $.ajax({
                url: "update.php",
                method: "POST",
                catch: false,
                data: {
                    update: 1,
                    id: id,
                    title: title1,
                    description: description1
                },
                success: function(dataResult) {
                    var dataResult = JSON.parse(dataResult);
                    if (dataResult.statusCode == 1) {
                        $('#update_data').modal().hide();
                        swal("Data Updated!", {
                            icon: "success",
                        }).then((result) => {
                            location.reload();
                        });
                    }
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $(document).ready(function() {
            $('#summary').DataTable({
                dom: 'Blfrtip',
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ]
            });
        });
    });
</script>
</body>

</html>