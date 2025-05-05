<?php

?>
<div class="container my-3">
    <div class="row">
        <div class="col-12">
            <div class="btn-group w-100" role="group" aria-label="Page navigation toggle">
                <?php
                $currentPage = basename($_SERVER['PHP_SELF']);
                $pages = array(
                    'Report1.php' => 'Report 1',
                    'Report2.php' => 'Report 2',
                    'Report3.php' => 'Report 3'
                );

                foreach ($pages as $file => $label) {
                    $isActive = $currentPage === $file;
                    $btnClass = $isActive ? 'btn-info text-white fs-5' : 'btn-outline-info text-info-subtle fs-5';
                    echo "<a href=\"$file\" class=\"btn $btnClass py-3\"><strong>$label</strong></a>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('btnradio1').addEventListener('change', function () {
        if (this.checked) {
            window.location.href = 'Report1.php';
        }
    });

    document.getElementById('btnradio2').addEventListener('change', function () {
        if (this.checked) {
            window.location.href = 'Report2.php';
        }
    });

    document.getElementById('btnradio3').addEventListener('change', function () {
        if (this.checked) {
            window.location.href = 'Report3.php';
        }
    });
</script>
