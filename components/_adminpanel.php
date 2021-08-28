<?php require('_nodirectaccess.php'); ?>

<ul class="admin-panel">
    <li><a href="#panel1" id="manage-users">Manage Users</a></li>
    <li><a href="#panel2" id="manage-books">Manage Books</a></li>
    <li><a href="#panel3" id="assign-books">Assign Books</a></li>
    <li><a href="#panel4" id="return-books">Return Books</a></li>
    <li><a href="#panel5" id="check-fines">Check Fines</a></li>
</ul>

<div class="panel" id="panel1">
    <?php require('_manageusers.php') ?>
</div>
<div class="panel" id="panel2">
    <?php require('_managebooks.php'); ?>
</div>
<div class="panel" id="panel3">
    <?php require('_assignbooks.php'); ?>
</div>
<div class="panel" id="panel4">
    <?php require('_returnbooks.php'); ?>
</div>
<div class="panel" id="panel5">
    <?php require('_fine.php'); ?>
</div>