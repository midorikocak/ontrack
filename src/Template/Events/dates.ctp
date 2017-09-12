<div class="events index large-9 medium-8 columns content">
    <h3><?= __('Dates') ?></h3>
    <div id="results">
        <table cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th scope="col"><?= __('Date')?></th>
                <th scope="col"><?= __('Notes')?></th>
                <th scope="col"><?= __('Total Hours')?></th>
                <th scope="col"><?= __('Total Minutes')?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($days as $day): ?>
                <tr>
                    <?php
                    $dateTime = \DateTime::createFromFormat('F j, Y', $day->activeDate);
                    $eventListStartDate = $dateTime->format('Y-m-d 00:00:00');
                    $eventListEndDate = $dateTime->format('Y-m-d 23:59:59');
                    $reportDate = $dateTime->format('Y-m-d');
                    ?>
                    <td>
                        <?= $this->Html->link(h($day->activeDate), ['action' => 'index', 'from' => $eventListStartDate, 'to' => $eventListEndDate], ['class' => $day->accomplished ? 'success-message' : 'error-message']); ?>
                    </td>
                    <td><?= h($day->notes) ?></td>
                    <td><?= $this->Number->format($day->totalHours) ?></td>
                    <td><?= $this->Number->format($day->totalMinutes) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('Generate Report'), ['action' => 'report', 'date' => $reportDate]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
