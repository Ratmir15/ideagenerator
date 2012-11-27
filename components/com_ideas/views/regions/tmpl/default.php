<?php
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php?option=com_ideas" method="post" name="adminForm" id="adminForm">
    <table class="adminlist" width="100%">
        <tr>
            <th>Регион</th>
            <th></th>
        </tr>
        <?php if ($this->rows):
        foreach ($this->rows as $i => $row): ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td><a href="?option=com_ideas&region=<?php echo $row->id; ?>"><?php echo $row->name; ?></a></td>
                <td><?php echo $row->cnt; ?></a></td>
            </tr>
            <?php
        endforeach;
    else: ?>
        <tr>
            <td colspan="2">Нет данных</td>
			<tr>
		<?php endif; ?>
    </table>

    <input type="hidden" name="task" value="" />
</form>