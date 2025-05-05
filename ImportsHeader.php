<?php
// importsHeader.php
?>
<div class="container my-3">
    <div class="row">
        <div class="col-12">
            <div class="btn-group w-100" role="group" aria-label="Page navigation toggle">
                <?php
                $currentPage = basename($_SERVER['PHP_SELF']);
                $pages = [
                    'Import1.php' => 'Import 1',
                    'Import2.php' => 'Import 2',
                    'Import3.php' => 'Import 3'
                ];

                foreach ($pages as $file => $label) {
                    $isActive = $currentPage === $file;
                    $btnClass = $isActive ? 'btn-primary text-white fs-5' : 'btn-outline-primary text-primary-subtle fs-5';
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
        window.location.href = 'Import1.php';
    }
});

  document.getElementById('btnradio2').addEventListener('change', function () {
      if (this.checked) {
          window.location.href = 'Import2.php';
      }
  });

  document.getElementById('btnradio3').addEventListener('change', function () {
      if (this.checked) {
          window.location.href = 'Import3.php';
      }
  });
</script>