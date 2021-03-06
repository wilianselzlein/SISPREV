<%
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

$associations += ['BelongsTo' => [], 'HasOne' => [], 'HasMany' => [], 'BelongsToMany' => []];
$immediateAssociations = $associations['BelongsTo'] + $associations['HasOne'];
$associationFields = collection($fields)
    ->map(function($field) use ($immediateAssociations) {
        foreach ($immediateAssociations as $alias => $details) {
            if ($field === $details['foreignKey']) {
                return [$field => $details];
            }
        }
    })
    ->filter()
    ->reduce(function($fields, $value) {
        return $fields + $value;
    }, []);

$groupedFields = collection($fields)
    ->filter(function($field) use ($schema) {
        return $schema->columnType($field) !== 'binary';
    })
    ->groupBy(function($field) use ($schema, $associationFields) {
        $type = $schema->columnType($field);
        if (isset($associationFields[$field])) {
            return 'string';
        }
        if (in_array($type, ['integer', 'float', 'decimal', 'biginteger'])) {
            return 'number';
        }
        if (in_array($type, ['date', 'time', 'datetime', 'timestamp'])) {
            return 'date';
        }
        return in_array($type, ['text', 'boolean']) ? $type : 'string';
    })
    ->toArray();

$groupedFields += ['number' => [], 'string' => [], 'boolean' => [], 'date' => [], 'text' => []];
$pk = "\$$singularVar->{$primaryKey[0]}";
%>
<nav class="col-lg-2 col-md-3">
    <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href=""><?= __('Actions') ?></a></li>
        <li><?= $this->Html->link(__('Edit {0}', ['<%= $singularHumanName %>']), ['action' => 'edit', <%= $pk %>]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete {0}', ['<%= $singularHumanName %>']), ['action' => 'delete', <%= $pk %>], ['confirm' => __('Are you sure you want to delete # {0}?', <%= $pk %>)]) ?> </li>
        <li><?= $this->Html->link(__('List {0}', ['<%= $pluralHumanName %>']), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New {0}', ['<%= $singularHumanName %>']), ['action' => 'add']) ?> </li>
<%
    $done = [];
    foreach ($associations as $type => $data) {
        foreach ($data as $alias => $details) {
            if ($details['controller'] !== $this->name && !in_array($details['controller'], $done)) {
%>
        <li><?= $this->Html->link(__('List {0}', ['<%= $this->_pluralHumanName($alias) %>']), ['controller' => '<%= $details['controller'] %>', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New {0}', ['<%= Inflector::humanize(Inflector::singularize(Inflector::underscore($alias))) %>']), ['controller' => '<%= $details['controller'] %>', 'action' => 'add']) ?> </li>
<%
                $done[] = $details['controller'];
            }
        }
    }
%>
    </ul>
</nav>
<div class="<%= $pluralVar %> view col-lg-10 col-md-9">
    <h3>
        <?= $this->Html->tag('i', '', array('class' => 'fas fa-eye')) ?>
        <?= h($<%= $singularVar %>-><%= $displayField %>) ?>
    </h3>
    <table class="table table-striped table-hover">
<% if ($groupedFields['string']) : %>
<% foreach ($groupedFields['string'] as $field) : %>
<% if (isset($associationFields[$field])) :
            $details = $associationFields[$field];
%>
        <tr>
            <th><%= Inflector::humanize($details['property']) %></th>
            <td><?= $<%= $singularVar %>->has('<%= $details['property'] %>') ? $this->Html->link($<%= $singularVar %>-><%= $details['property'] %>-><%= $details['displayField'] %>, ['controller' => '<%= $details['controller'] %>', 'action' => 'view', $<%= $singularVar %>-><%= $details['property'] %>-><%= $details['primaryKey'][0] %>]) : '' ?></td>
        </tr>
<% else : %>
<% if ($field != $displayField) : %>
        <tr>
            <th><%= Inflector::humanize($field) %></th>
            <td><?= h($<%= $singularVar %>-><%= $field %>) ?></td>
        </tr>
<% endif; %>
<% endif; %>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['number']) : %>
<% foreach ($groupedFields['number'] as $field) : %>
<% if ($field != 'id') : %>
        <tr>
            <th><%= Inflector::humanize($field) %></th>
            <td><?= $this->Number->format($<%= $singularVar %>-><%= $field %>) ?></td>
        </tr>
<% endif; %>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['date']) : %>
<% foreach ($groupedFields['date'] as $field) : %>
<% if ($field != 'modified' && $field != 'created') : %>
        <tr>
            <th><%= Inflector::humanize($field) %></th>
            <td><?= h($<%= $singularVar %>-><%= $field %>) ?></tr>
        </tr>
<% endif; %>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['boolean']) : %>
<% foreach ($groupedFields['boolean'] as $field) : %>
        <tr>
            <th><%= Inflector::humanize($field) %></th>
            <td>
                <?php echo $<%= $singularVar %>-><%= $field %> 
                    ? $this->Html->tag('i', '', array('class' => 'far fa-check-square')) 
                    : $this->Html->tag('i', '', array('class' => 'far fa-square')) ; ?>
            </td>
         </tr>
<% endforeach; %>
<% endif; %>
<% if ($groupedFields['text']) : %>
<% foreach ($groupedFields['text'] as $field) : %>
    <tr>
        <th><%= Inflector::humanize($field) %></th>
        <td>
            <?= $this->Text->autoParagraph(h($<%= $singularVar %>-><%= $field %>)); ?>
        </td>
    </tr>
<% endforeach; %>
<% endif; %>
    </table>
<%
$relations = $associations['HasMany'] + $associations['BelongsToMany'];
foreach ($relations as $alias => $details):
    $otherSingularVar = Inflector::variable($alias);
    $otherPluralHumanName = Inflector::humanize(Inflector::underscore($details['controller']));
    %>
    <div class="related">
        <?php if (!empty($<%= $singularVar %>-><%= $details['property'] %>)): ?>
            <h4>
                <?= $this->Html->tag('i', '', array('class' => 'fas fa-user')) ?>
                <?= __('Related {0}', ['<%= $otherPluralHumanName %>']) ?>
            </h4>
        <table class="table table-striped table-hover">
            <tr>
<% foreach ($details['fields'] as $field): %>
                <th><%= Inflector::humanize($field) %></th>
<% endforeach; %>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($<%= $singularVar %>-><%= $details['property'] %> as $<%= $otherSingularVar %>): ?>
            <tr>
            <%- foreach ($details['fields'] as $field): %>
                <td><?= h($<%= $otherSingularVar %>-><%= $field %>) ?></td>
            <%- endforeach; %>
            <%- $otherPk = "\${$otherSingularVar}->{$details['primaryKey'][0]}"; %>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => '<%= $details['controller'] %>', 'action' => 'view', <%= $otherPk %>], ['class'=>'btn btn-default btn-xs']) %>
                    <?= $this->Html->link(__('Edit'), ['controller' => '<%= $details['controller'] %>', 'action' => 'edit', <%= $otherPk %>], ['class'=>'btn btn-primary btn-xs']) %>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    </div>
<% endforeach; %>
<table class="table table-striped table-hover">
<tr>
    <th><?= __('Created') ?></th>
    <td>
    <?= $this->Html->tag('i', '', array('class' => 'far fa-edit')) ?>
    <?= h($<%= $singularVar %>->created) ?>
    </tr>
</tr>
<tr>
    <th><?= __('Modified') ?></th>
    <td>
    <?= $this->Html->tag('i', '', array('class' => 'fas fa-edit')) ?>
    <?= h($<%= $singularVar %>->modified) ?>
    </tr>
</tr>
</table>
</div>
