<p>
    <a href="<?php echo admin_url('/user/create') ?>" class="btn btn-primary">
        <i class="fa fa-user-plus fa-fw"></i>
        Nouvel utilisateur
    </a>
</p>
<?php
if(!$this->list) {
    $this->partial('admin.layouts.components.alert', [
        'type' => 'warning',
        'message' => 'Aucun utilisateur enregistré actuellement',
    ]);
}
else {
    ?>
    <div class="box">
        <div class="box-body table-responsive no-padding">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th width="1">#</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th width="1"></th>
                    <th width="1"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach($this->list as $user) {
                    ?>
                    <tr>
                        <td class="text-muted">
                            <?php echo $user['id'] ?>
                        </td>
                        <td>
                            <?php echo $user['name'] ?>
                        </td>
                        <td>
                            <?php echo $user['email'] ?>
                        </td>
                        <td>
                            <a href="<?php echo admin_url('/user/edit', ['id' => $user['id']]) ?>" class="btn btn-info btn-xs" data-toggle="tooltip" title="Modifier">
                                <i class="fa fa-pencil"></i>
                            </a>
                        </td>
                        <td>
                            <a href="javascript:;" onclick="if(confirm('Supprimer cet utilisateur ?')) { location.href='<?php echo admin_url('/user/delete', ['id' => $user['id']]) ?>'; }" class="btn btn-danger btn-xs" data-toggle="tooltip" title="Supprimer">
                                <i class="fa fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>

<?php
}