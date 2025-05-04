<?php
// importsHeader.php
?>
<div class="container my-3">
    <div class="row">
        <div class="col-12">
            <div class="btn-group w-100" role="group" aria-label="Page navigation toggle">
                <a href="Import1.php"
                   class="btn btn-outline-primary py-3 <?= basename($_SERVER['PHP_SELF']) == 'Import1.php' ? 'active' : '' ?>">
                    Import 1
                </a>
                <a href="Import2.php"
                   class="btn btn-outline-primary py-3 <?= basename($_SERVER['PHP_SELF']) == 'Import2.php' ? 'active' : '' ?>">
                    Import 2
                </a>
                <a href="Import3.php"
                   class="btn btn-outline-primary py-3 <?= basename($_SERVER['PHP_SELF']) == 'Import3.php' ? 'active' : '' ?>">
                    Import 3
                </a>
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