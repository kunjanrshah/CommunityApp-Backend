<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <?= $user['first_name'] . ' ' . $user['last_name'] ?>'s Booking History
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?= base_url('admin/dashboard') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?= base_url('admin/users/customers') ?>">Customers</a></li>
        <li class="active">Booking History</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#Booking ID</th>
                                    <th>Address</th>
                                    <th>Service Date & Time</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (!empty($bookings)) {
                                    for ($i = 0; $i < sizeof($bookings); $i++) {
                                        $action = '<a title="Booking Details" href="' . base_url() . 'admin/bookings/viewBooking/' . $bookings[$i]['id'] . '" class="ajax-link btn btn-default btn-xs"><i class="fa fa-eye"></i></a>&nbsp;';
                                        // $action .= '<a href="javascript:void()" data-href="'.base_url().'admin/offers/delete/'.$offers[$i]['id'].'" class="btn btn-danger btn-xs delete"><i class="fa fa-trash"></i></a>';
                                        if ($bookings[$i]['request_status'] == "PENDING") {
                                            $bookingStatus = "btn btn-primary";
                                        } elseif ($bookings[$i]['request_status'] == "REJECTED" || $bookings[$i]['request_status'] == "UNANSWERED" || $bookings[$i]['request_status'] == "REPORT") {
                                            $bookingStatus = "btn btn-danger";
                                        } elseif ($bookings[$i]['request_status'] == "ACCEPTED" || $bookings[$i]['request_status'] == "REACHED") {
                                            $bookingStatus = "btn btn-info";
                                        } else {
                                            $bookingStatus = "btn btn-success";
                                        }
                                        ?>

                                        <tr>
                                            <td>#<?= $bookings[$i]['id'] ?></td>
                                            <td>
                                                <?php
                                                echo "<p>" . $bookings[$i]['address'] . "</p>";
                                                echo "<p>Phone: " . $bookings[$i]['mobile'] . "</p>";
                                                echo "<p>Email: " . $bookings[$i]['email'] . "</p>";
                                                ?>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($bookings[$i]['service_date'])) . ' <b>-</b> ' . date('h:i A', strtotime($bookings[$i]['start_time'])) ?></td>
                                            <td>£ <?= number_format($bookings[$i]['total_cost'], 2) ?></td>
                                            <td>
                                                <?= $bookings[$i]['request_status'] ?>
                                            </td>
                                            <td><?= $action ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
</section>