<table class="table table-hover table-bordered table-responsive w-100 d-block d-md-table fix-table">
    <colgroup>
        <col width="2%" />
        <col width="15%" />
        <col width="5%" />
        <col width="5%" />
        <col width="8%" />
        <col width="8%" />
        <col width="8%" />
        <col width="8%" />
        <col width="8%" />
        <col width="8%" />
    </colgroup>
    <thead>
        <tr>
            <th scope="col" rowspan="2">#</th>
            <th scope="col" rowspan="2">Date</th>
            <th scope="col" colspan="2">Working Hour</th>
            <th scope="col" rowspan="2">Clock In</th>
            <th scope="col" rowspan="2">Clock Out</th>
            <th scope="col" rowspan="2">Early Work</th>
            <th scope="col" rowspan="2">Late Work</th>
            <th scope="col" rowspan="2">Early Home</th>
            <th scope="col" rowspan="2">Late Home</th>
            <th scope="col" rowspan="2">Description</th>
        </tr>
        <tr>
            <th scope="col">IN</th>
            <th scope="col">OUT</th>
        </tr>
    </thead>
    <tbody>

        <?php if (count($presence_history) > 0) : ?>
            <?php foreach ($presence_history as $key => $value) : ?>
                <tr class="<?= $value['class_holiday'] ?>">
                    <th scope="row"><?= $key + 0 ?></th>
                    <td><?= $value['date'] ?></td>
                    <td><?= $value['work_in'] ?></td>
                    <td><?= $value['work_out'] ?></td>
                    <td class="<?= ($value['clock_in'] != "00:00" ? "" : "bg-clock") ?>"><?= $value['clock_in'] ?></td>
                    <td class="<?= ($value['clock_out'] != "00:00" ? "" : "bg-clock") ?>"><?= $value['clock_out'] ?></td>
                    <td><?= $value['early_work'] ?></td>
                    <td class="<?= (is_null($value['late_work']) ? "" : "bg-late-early") ?>"><?= $value['late_work'] ?></td>
                    <td class="<?= (is_null($value['early_home']) ? "" : "bg-late-early") ?>"><?= $value['early_home'] ?></td>
                    <td><?= $value['late_home'] ?></td>
                    <td><?= $value['description'] ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>