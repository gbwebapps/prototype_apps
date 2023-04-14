        
        <footer>
            <?= $this->include('backend/template/menuBottomView'); ?>
            <button type="button" class="scrollup btn btn-primary btn-sm">
                <i class="fa-solid fa-arrow-circle-up"></i> Back To Top
            </button>
        </footer>

        <!-- Show loader -->
        <div id="showLoader"></div>

        <!-- References for JS variables -->
        <div id="controller" data-controller="<?= esc($controller); ?>"></div>
        <div id="entity" data-entity="<?= esc($entity); ?>"></div>
        <div id="action" data-action="<?= esc($action); ?>"></div>
        <div id="urlbase" data-urlbase="<?= config('App')->baseURL; ?>"></div>

        <?php if((isset($beforeScript)) && ( ! empty($beforeScript))): 
            foreach($beforeScript as $comment => $script):
                echo '<!-- ' . $comment . ' -->' . "\n\t\t";
                echo '<script src="' . base_url($script . '.js') . '"></script>' . "\n\t\t";
            endforeach;
        endif; ?>

        <!-- App JS -->
        <script src="<?= base_url('assets/js/backend/app.js'); ?>"></script>

        <?php if((isset($afterScript)) && ( ! empty($afterScript))): 
            foreach($afterScript as $comment => $script):
                echo '<!-- ' . $comment . ' -->' . "\n\t\t";
                echo '<script src="' . base_url($script . '.js') . '"></script>' . "\n\t\t";
            endforeach;
        endif; ?>

        <!-- Controller JS -->
        <script src="<?= base_url('assets/js/backend/' . esc($controller) . '.js'); ?>"></script>

    </body>
</html>