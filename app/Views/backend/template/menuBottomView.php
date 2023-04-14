    <nav class="navbar fixed-bottom navbar-light bg-light">

        <?php if($currentSeller): ?>

        <div class="mr-auto">
            <div class="btn-group dropup">
                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                    Modules
                </button>
                <ul class="dropdown-menu">
                    
                    <?php 
                        // Starting a counting to know when the menu-top-right last item is reached for not put the divider
                        $i = 1; 

                        foreach(menuBottomLeft() as $item): 
                            // Checking if the loop reached the menu-top-right last item for not put divider
                            $divider = (sizeof(menuBottomLeft()) == $i) ? null : '<li><hr class="dropdown-divider"></li>';
                        ?>
                        <li>
                            <a class="dropdown-item<?= (($controller === $item['controller']) ? ' active' : null); ?>" href="<?= base_url($item['route']); ?>"><i class="<?= $item['icon']; ?>"></i> <?= $item['label']; ?></a>
                        </li>
                        <?= $divider; ?>
                    <?php $i++; endforeach; ?>

                </ul>
            </div>
        </div>

        <?= showButtons($controller, $action); ?>

        <?php if($currentSeller->identity->master === '1'): ?>
        <div class="ml-auto">
            <div class="btn-group dropup">
                <button type="button" class="btn btn-secondary dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">
                    Options
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    
                    <?php 
                        // Starting a counting to know when the menu-top-right last item is reached for not put the divider
                        $i = 1; 

                        foreach(menuBottomRight() as $item): 
                            // Checking if the loop reached the menu-top-right last item for not put divider
                            $divider = (sizeof(menuBottomRight()) == $i) ? null : '<li><hr class="dropdown-divider"></li>';
                        ?>
                        <li>
                            <a class="dropdown-item<?= (($controller === $item['controller']) ? ' active' : null); ?>" href="<?= base_url($item['route']); ?>"><i class="<?= $item['icon']; ?>"></i> <?= $item['label']; ?></a>
                        </li>
                        <?= $divider; ?>
                    <?php $i++; endforeach; ?>

                </ul>
            </div>
        </div>
        <?php endif; ?>

        <?php else: ?>

            <div class="mx-auto">
                <i class="fa-solid fa-diagram-project"></i> <b>p</b>roto<b>TYPE</b>
            </div>

        <?php endif; ?>

    </nav>