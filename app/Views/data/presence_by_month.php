<table id="table-report-presence" class="table table-hover table-bordered table-responsive w-100 d-block d-md-table fix-table-report">
    <thead>
        <tr>
            <th scope="col" rowspan="3">No</th>
            <th scope="col" rowspan="3" style="min-width: 300px;">Name</th>
            <th scope="col" rowspan="3">Job</th>
            <th scope="col" colspan="<?= (fmod(count($day_in_month) * 2, 2) == 0 ? count($day_in_month) * 2 : (count($day_in_month) - 1) * 2) ?>"><span class="cell-month-name"></span></th>
            <th scope="col" rowspan="2" colspan="4">Total</th>
        </tr>
        <tr>
            <?php if (count($day_in_month) > 0) : ?>
                <?php foreach ($day_in_month as $dates => $date) : ?>
                    <th scope="col" colspan="2"><?= $date ?></th>
                <?php endforeach; ?>
            <?php endif; ?>

        </tr>
        <tr>
            <?php if (count($day_in_month) > 0) : ?>
                <?php foreach ($day_in_month as $dates => $date) : ?>
                    <th scope="col">IN</th>
                    <th scope="col">OUT</th>
                <?php endforeach; ?>
            <?php endif; ?>
            <th scope="col">Early Home</th>
            <th scope="col">Late Home</th>
            <th scope="col">Early Work</th>
            <th scope="col">Late Work</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 0; ?>
        <?php if (count($month_attend) > 0) : ?>
            <?php foreach ($month_attend as $items => $item) : ?>
                <?php $no++; ?>
                <tr>
                    <td><?= $no ?></td>
                    <td class="text-left"><?= $item["person_name"] ?></td>
                    <td><?= $item["job"] ?></td>

                    <?php foreach ($day_in_month as $dates => $date) : ?>
                        <?php foreach ($item["presence"][$date] as $presences => $presence) : ?>
                            <?php $class_off = $presence["is_holiday"] == "1" ? "marked-as-dayoff" : ""; ?>

                            <td class="cell-time <?= "${class_off}" ?>"><?= $presence["clock_in"] ?></td>
                            <td class="cell-time <?= "${class_off}" ?>"><?= $presence["clock_out"] ?></td>
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <td><?= $item["early_home"] ?></td>
                    <td><?= $item["late_home"] ?></td>
                    <td><?= $item["early_work"] ?></td>
                    <td><?= $item["late_work"] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>