<!-- Tokens -->    
<?php if(count($data['records']->getResult())): ?>
    <div class="card-pagination">
        <?= $this->include('backend/template/paginationView'); ?>
    </div>
    <div class="card-body table-responsive p-0">

        <?php $icon = ($posts['order'] == 'desc') ? '<i class="fas fa-arrow-circle-down"></i>' : '<i class="fas fa-arrow-circle-up"></i>'; ?>
        <div id="itemlastpage" data-itemlastpage="<?= esc($data['itemLastPage']); ?>"></div>

        <table class="table text-nowrap">
            <thead>
                <tr class="tokensSorting">
                    <th style="width: 5%;" class="text-center text-primary">
                        <a class="tokenSort" href="#" data-column="id" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'id') ? 'asc' : 'desc'); ?>">
                            ID&nbsp;<?= (($posts['column'] == 'id') ? '&nbsp;' . $icon : ''); ?>
                        </a>
                    </th>
                    <th style="width: 17.5%;">
                        <a class="tokenSort" href="#" data-column="identity" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'identity') ? 'asc' : 'desc'); ?>">
                            Identity&nbsp;<?= (($posts['column'] == 'identity') ? '&nbsp;' . $icon : ''); ?>
                        </a>
                    </th>
                    <th style="width: 17.5%;">
                        <a class="tokenSort" href="#" data-column="token_create" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'token_create') ? 'asc' : 'desc'); ?>">
                            Creation Date&nbsp;<?= (($posts['column'] == 'token_create') ? '&nbsp;' . $icon : ''); ?>
                        </a>
                    </th>
                    <th style="width: 15%;">
                        <a class="tokenSort" href="#" data-column="token_expire" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'token_expire') ? 'asc' : 'desc'); ?>">
                            Expire Date&nbsp;<?= (($posts['column'] == 'token_expire') ? '&nbsp;' . $icon : ''); ?>
                        </a>
                    </th>
                    <th style="width: 10%; text-align: center;">
                        <a class="tokenSort" href="#" data-column="token_type" data-order="<?= (($posts['order'] == 'desc' && $posts['column'] == 'token_type') ? 'asc' : 'desc'); ?>">
                            Type&nbsp;<?= (($posts['column'] == 'token_type') ? '&nbsp;' . $icon : ''); ?>
                        </a>
                    </th>
                    <th style="width: 10%; text-align: center;">
                        IP
                    </th>
                    <th style="width: 20%;" class="text-right">
                        <a href="#" id="linkResetTokens">
                            <i class="fas fa-sync-alt"></i> 
                            Reset sorting
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>

            <?php 
                foreach($data['records']->getResult() as $token): 

                switch($token->token_type):
                    case 'session':
                        $time = 86400;
                    break;
                    case 'cookie':
                        $time = 864000;
                    break;
                    case 'activation':
                        $time = 43200;
                    break;
                endswitch;
            ?>
                <tr>
                    <td rowspan="2" class="align-middle text-center border-right font-weight-bold">
                        <span class="badge badge-info"><?= esc($token->id); ?></span>
                    </td>
                    <td class="align-middle text-left font-weight-bold">
                        <?= esc($token->identity); ?>
                    </td>
                    <td class="align-middle text-left font-weight-bold">
                        <?= esc($token->token_create); ?>
                    </td>

                    <?php 
                        $diff = (time() - strtotime($token->token_create));
                        $class = ($diff > $time) ? 'text-danger' : 'text-success';
                    ?>

                    <td class="align-middle text-left font-weight-bold">
                        <span class="<?= $class; ?>"><?= esc($token->token_expire); ?></span>
                    </td>
                    <td class="align-middle text-center font-weight-bold">
                        <?= esc($token->token_type); ?>
                    </td>
                    <td class="align-middle text-center font-weight-bold">
                        <?= esc($token->ip); ?>
                    </td>

                    <!-- Delete -->
                    <td class="text-right align-middle">
                        <form method="post" class="deleteToken" data-message="Do you want to delete this token?">
                            <input type="hidden" name="_method" value="delete">
                            <input type="hidden" class="csrf" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>">
                            <input type="hidden" name="id" value="<?= esc($token->id); ?>">
                            <button type="submit" class="btn btn-link">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                    <!-- End delete -->

                </tr>

                <!-- Bottom row -->

                <tr>
                    <td class="align-middle" colspan="7">
                        User Agent: <span class="font-weight-bold"><?= esc($token->seller_agent); ?></span>
                    </td>
                </tr>

                <!-- End bottom row -->
            <?php endforeach; ?>

            </tbody>
        </table>

    </div>
    <div class="card-pagination">
        <?= $this->include('backend/template/paginationView'); ?>
    </div>
<?php else: ?>
    <div class="card-body">
        <div class="text-center">
            <span class="font-weight-bold">No tokens found!</span>
        </div>
    </div>
<?php endif; ?>
<!-- end Tokens -->