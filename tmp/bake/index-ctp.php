<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Utility\Inflector;

$fields = collection($fields)
    ->filter(function($field) use ($schema) {
        return !in_array($schema->columnType($field), ['binary', 'text']);
    })
    ->take(7);
?>
<div class="row">
<nav class="col-md-2" id="actions-sidebar">
    <ul class="nav nav-pills nav-stacked">
        <li class="active"><a><CakePHPBakeOpenTag= __('Actions') CakePHPBakeCloseTag></a></li>
        <li><CakePHPBakeOpenTag= $this->Html->link(__('New {0}', ['<?= $singularHumanName ?>']), ['action' => 'add']) CakePHPBakeCloseTag></li>
<?php
    $done = [];
    foreach ($associations as $type => $data):
        foreach ($data as $alias => $details):
            if (!empty($details['navLink']) && $details['controller'] !== $this->name && !in_array($details['controller'], $done)):
?>
        <li><CakePHPBakeOpenTag= $this->Html->link(__('List {0}', ['<?= $this->_pluralHumanName($alias) ?>']), ['controller' => '<?= $details['controller'] ?>', 'action' => 'index']) CakePHPBakeCloseTag></li>
        <li><CakePHPBakeOpenTag= $this->Html->link(__('New {0}', ['<?= $this->_singularHumanName($alias) ?>']), ['controller' => '<?= $details['controller'] ?>', 'action' => 'add']) CakePHPBakeCloseTag></li>
<?php
                $done[] = $details['controller'];
            endif;
        endforeach;
    endforeach;
?>
        <li><CakePHPBakeOpenTag= $this->Html->link(__('Export'), ['_ext' => 'xlsx'], ['class'=>'add']) CakePHPBakeCloseTag></li>
    </ul>
</nav>

<div class="ajax-form">
    <div class="<?= $pluralVar ?> index col-md-10 columns content">
        <h3>
            <CakePHPBakeOpenTag= $this->Html->tag('i', '', array('class' => 'fas fa-chevron-right')) CakePHPBakeCloseTag>
            <?= $pluralHumanName ?>

        </h3>
        <div class="row">
            <CakePHPBakeOpenTagphp echo $this->Form->create(); CakePHPBakeCloseTag>
            <div class="col-md-3">
                <CakePHPBakeOpenTagphp echo $this->Form->input('field', ['type' => 'select', 'options' => $options, 'label' => False, 'value' => $field]); CakePHPBakeCloseTag>
            </div>
            <div class="col-md-8">
                <CakePHPBakeOpenTagphp echo $this->Form->input('q', ['autofocus' => 'autofocus', 'value' => $busca, 'label' => False, 'placeholder' => __('_search')]); CakePHPBakeCloseTag>
            </div>
            <div class="col-md-1">
                <CakePHPBakeOpenTagphp echo $this->Form->button($this->Html->tag('i', '', array('class' => 'fas fa-filter')), ['type' => 'submit']); CakePHPBakeCloseTag>
            </div>
            <CakePHPBakeOpenTagphp echo $this->Form->end(); CakePHPBakeCloseTag>
        </div>
        <table class="table list table-striped table-hover">
            <thead>
                <tr>
    <?php foreach ($fields as $field): ?>
                    <th><CakePHPBakeOpenTag= $this->Paginator->sort('<?= $field ?>') CakePHPBakeCloseTag></th>
    <?php endforeach; ?>
                    <th class="actions"><CakePHPBakeOpenTag= __('Actions') CakePHPBakeCloseTag></th>
                </tr>
            </thead>
            <tbody>
                <CakePHPBakeOpenTagphp foreach ($<?= $pluralVar ?> as $<?= $singularVar ?>): CakePHPBakeCloseTag>
                <tr>
    <?php        foreach ($fields as $field) {
                $isKey = false;
                if (!empty($associations['BelongsTo'])) {
                    foreach ($associations['BelongsTo'] as $alias => $details) {
                        if ($field === $details['foreignKey']) {
                            $isKey = true;
    ?>
                    <td><CakePHPBakeOpenTag= $<?= $singularVar ?>->has('<?= $details['property'] ?>') ? $this->Html->link($<?= $singularVar ?>-><?= $details['property'] ?>-><?= $details['displayField'] ?>, ['controller' => '<?= $details['controller'] ?>', 'action' => 'view', $<?= $singularVar ?>-><?= $details['property'] ?>-><?= $details['primaryKey'][0] ?>]) : '' CakePHPBakeCloseTag></td>
    <?php
                            break;
                        }
                    }
                }
                if ($isKey !== true) {
                    if (in_array($schema->columnType($field), ['boolean'])) {
    ?>
                    <td>
                        <CakePHPBakeOpenTagphp echo $<?= $singularVar ?>-><?= $field ?> 
                            ? $this->Html->tag('i', '', array('class' => 'far fa-check-square')) 
                            : $this->Html->tag('i', '', array('class' => 'far fa-square')) ; CakePHPBakeCloseTag>
                    </td>
    <?php
                    } elseif (!in_array($schema->columnType($field), ['integer', 'biginteger', 'decimal', 'float'])) {
    ?>
                    <td><CakePHPBakeOpenTag= h($<?= $singularVar ?>-><?= $field ?>) CakePHPBakeCloseTag></td>
    <?php
                    } else {
    ?>
                    <td><CakePHPBakeOpenTag= $this->Number->format($<?= $singularVar ?>-><?= $field ?>) CakePHPBakeCloseTag></td>
    <?php
                    }
                }
            }

            $pk = '$' . $singularVar . '->' . $primaryKey[0];
    ?>
                    <td class="actions" style="white-space:nowrap">
                        <CakePHPBakeOpenTag= $this->Html->link(__('View'), ['action' => 'view', <?= $pk ?>], ['class'=>'btn btn-default btn-xs']) CakePHPBakeCloseTag>
                        <CakePHPBakeOpenTag= $this->Html->link(__('Edit'), ['action' => 'edit', <?= $pk ?>], ['class'=>'btn btn-primary btn-xs']) CakePHPBakeCloseTag>
                        <CakePHPBakeOpenTag= $this->Form->postLink(__('Delete'), ['action' => 'delete', <?= $pk ?>], ['class'=>'btn btn-danger btn-xs ajax-delete', 'escapeTitle' => false]) CakePHPBakeCloseTag>
                    </td>
                </tr>
                <CakePHPBakeOpenTagphp endforeach; CakePHPBakeCloseTag>
            </tbody>
        </table>
        <div class="paginator">
            <center>
                <ul class="pagination">
                    <CakePHPBakeOpenTag= $this->Paginator->prev('&laquo; ' . __('previous'), ['escape'=>false]) CakePHPBakeCloseTag>
                    <CakePHPBakeOpenTag= $this->Paginator->numbers(['escape'=>false]) CakePHPBakeCloseTag>
                    <CakePHPBakeOpenTag= $this->Paginator->next(__('next') . ' &raquo;', ['escape'=>false]) CakePHPBakeCloseTag>
                </ul>
                <p><CakePHPBakeOpenTag= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} records out of
            {{count}} total, starting on record {{start}}, ending on {{end}}')) CakePHPBakeCloseTag></p>
            </center>
        </div>
    </div>
</div>
</div>