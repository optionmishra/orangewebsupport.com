<main class="app-content">
    <div class="app-title">
        <div>
            <h1><i class="fa fa-dashboard"></i> Dashboard</h1>
            <p></p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
            <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
            <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                <div class="info p-2">
                    <h4>Student</h4>
                    <p class="font-50"><b><?= $sta_user ?></b></p>
                    <hr />
                    <h4>Teacher</h4>
                    <p class="font-50"><b><?= $tea_user ?></b></p>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="widget-small info coloured-icon"><i class="icon fa fa-thumbs-o-up fa-3x"></i>
                <div class="info">
                    <h4>Boards</h4>
                    <ul>
                        <?php foreach ($sta_boards as $sb) : ?>
                            <li><b class="text-danger"><?= $sb->name ?></b>(<?= $sb->total ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
                <div class="info">
                    <h4>Publication</h4>
                    <ul>
                        <?php foreach ($sta_pub as $sb) : ?>
                            <li><b class="text-danger"><?= $sb->name ?></b>(<?= $sb->total ?>)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>


        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 p-2">
                            <div class="table-responsive">
                                <table class="table w-100 table-bordered table-striped" id="series_users_table">
                                    <thead>
                                        <tr>
                                            <th style="width: 10%;">#</th>
                                            <th style="width: 60%;">Series Name</th>
                                            <th style="width: 30%;">Users</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($series_users as $key => $series_user) : ?>
                                            <tr>
                                                <td><?= ++$key ?></td>
                                                <td><?= $series_user['name'] ?></td>
                                                <td><?= $series_user['user_count'] ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-md-12 col-lg-12">
            <div class="widget-small danger coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
                <div class="info">
                    <div class="col-md-12 p-0 m-0">
                        <div class="tile">
                            <h3 class="tile-title">Subject and Class Wise Statatics</h3>
                            <div class="row">
                                <?php foreach ($subject as $ca) : ?>
                                    <div class="col-lg-3 p-4 text-center bg-white sder" sid="<?= $ca->id ?>"><?= $ca->name ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="add-new-cls" tabindex="-1" role="dialog" aria-labelledby="add-new-cls" aria-hidden="true">
        <div class="modal-dialog modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allowance-deductioan"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="tile">
                            <h3 class="tile-title">Statatics</h3>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Category</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ser">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>