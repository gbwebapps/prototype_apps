<header>
    <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="<?= base_url('admin/dashboard'); ?>">
            <img src="<?= base_url('assets/img/logo-xl-dark.png'); ?>" alt="Prototype">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <?php if($currentSeller): ?>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle<?= ((isset($controller) && $controller === 'sellers') || (isset($controller) && $controller === 'account')) ? ' active' : ''; ?>" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-expanded="false">
                        
                        <?php if(is_null($currentSeller->identity->image)): ?>
                            <span><i class="fas fa-user-tie"></i></span>
                        <?php else: ?>
                            <span><img src="<?= base_url('images/sellers/small/' . esc($currentSeller->identity->image)); ?>" width="48" height="27" class="img-thumbnail p-0 border-0"></span>
                        <?php endif; ?>

                        <span><?= esc($currentSeller->identity->firstname . ' ' . $currentSeller->identity->lastname); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">

                        <?php 
                            // Starting a counting to know when the menu-top-right last item is reached for not put the divider
                            $i = 1; 

                            foreach(menuTopRight() as $item): 
                                // Checking if the loop reached the menu-top-right last item for not put divider
                                $divider = (sizeof(menuTopRight()) == $i) ? null : '<li><hr class="dropdown-divider"></li>';
                            ?>

                            <?php if(($currentSeller->identity->master !== '1') && ($item['controller'] === 'sellers')): continue; else:?>
                            <li>
                                <a class="dropdown-item<?= ((isset($controller) && $controller === $item['controller']) ? ' active' : null); ?>" href="<?= base_url($item['route']); ?>"><i class="<?= $item['icon']; ?>"></i> <?= $item['label']; ?></a>
                            </li>
                            <?php endif; ?>

                            <?= $divider; ?>
                        <?php $i++; endforeach; ?>

                    </ul>
                </li>
            </ul>
        </div>

        <?php endif; ?>
        
    </nav>
</header>