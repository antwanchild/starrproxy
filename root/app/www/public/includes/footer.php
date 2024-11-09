<?php

/*
----------------------------------
 ------  Created: 101124   ------
 ------  Austin Best	   ------
----------------------------------
*/

if (!$_SESSION) {
    session_start();
}

if (!$_SESSION['IN_UI']) {
    exit('Invalid session, refresh the page');
}

?>
        </div> <!-- content -->       

        <!-- Toast container -->
        <div class="toast-container bottom-0 end-0 p-3" style="z-index: 10001 !important; position: fixed;"></div>

        <!-- Generic modal -->
        <div id="dialog-modal-container">
            <div class="modal fade" id="dialog-modal" style="z-index: 9999 !important;" data-bs-backdrop="static">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content bg-dark" style="border: grey solid 1px;">
                        <div class="modal-header" style="border: grey solid 1px;">
                            <h5 class="modal-title w-100"></h5>
                            <div class="d-flex text-end">
                                <i class="far fa-window-close fa-2x" data-bs-dismiss="modal" style="cursor: pointer;"></i>
                            </div>
                        </div>
                        <div class="modal-body" data-scrollbar=”true” data-wheel-propagation=”true”></div>
                        <div class="modal-footer"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Javascript Libraries -->
        <script src="libraries/jquery/jquery-3.4.1.min.js"></script>
        <script src="libraries/jquery/jquery-ui-1.13.2.min.js"></script>
        <script src="libraries/bootstrap/bootstrap.bundle.min.js"></script>
        <script src="libraries/select2/select2.min.js"></script>

        <!-- Internal functions -->
        <?php
        $dir = opendir('js');
        while ($file = readdir($dir)) {
            if (!str_contains($file, '.js')) {
                continue;
            }

            ?><script src="js/<?= $file ?>?t=<?= filemtime('js/' . $file) ?>"></script><?php
        }
        closedir($dir);
        ?>
    </body>
</html>
