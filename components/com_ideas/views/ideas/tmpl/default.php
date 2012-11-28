<?php
//защита от прямого доступа
defined('_JEXEC') or die('Restricted access');
?>
<form action="index.php?option=com_ideas" method="post" name="adminForm" id="adminForm">
    <table class="adminlist" width="100%">
        <tr>
            <th>Название</th>
            <th>Автор</th>
            <th>Регион</th>
            <th></th>
        </tr>
        <?php if ($this->rows):
        foreach ($this->rows as $i => $row): ?>
            <tr class="row<?php echo $i % 2; ?>">
                <td><a href="?option=com_content&view=article&id=<?php echo $row->id; ?>"><?php echo $row->title; ?></a></td>
                <td><a href="?option=com_ideas&user=<?php echo $row->created_by; ?>"><?php echo $row->username; ?></a></td>
                <td><?php echo $row->name; ?></td>
                <td><?php
                    $text="Отклонено";
                    if ($row->state==0) {
                        $text = "На модерации";
                    }
                    if ($row->state==1) {
                        $text = "Опубликовано";
                    }
                    echo $text;
                    ?></td>
            </tr>
            <?php
        endforeach;
    else: ?>
        <tr>
            <td colspan="15">Нет данных</td>
			<tr>
		<?php endif; ?>
    </table>

    <input type="hidden" name="task" value="" />
</form>