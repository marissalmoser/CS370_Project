<?php
include 'header.php';
?>

    <!DOCTYPE html><br>

    <div class="container">
        <h1 id="body">Welcome to My Website</h1>
        <br>
        <p>Here you can import data and view their reports. This is the home page. </p>
        <p><strong> We love everything news!</strong></p>
    </div>

<br><br>

    <div id="cont" class="container">
        <h3>1. Reset Database for Import Process</h3>
        <p>First, reset the database by pressing the button below. This will drop and re-create all the tables in the
            schema for a fresh experience. </p>
        <p><small>Note: This functionality is only implemented here as this is a test environment. Please do not make a button
                that deletes all user data in production. <strong>Don't try this at home. </strong> </small></p>

        <!-- Reset Button -->
        <button id="resetButton" class="btn btn-lg btn-secondary" data-bs-toggle="modal" data-bs-target="#resetModal">
            Reset Database
        </button>

        <!-- Modal HTML -->
        <div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resetModalLabel">Are you sure?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Click "Yes" to reset the database.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="confirmReset" class="btn btn-danger" data-bs-dismiss="modal">Yes, Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <br>
        <!-- Reset result message -->
        <div id="resetResult"></div>

        <!-- Reset Handler -->
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const confirmBtn = document.getElementById('confirmReset');
                const resultContainer = document.getElementById('resetResult');
                const container = document.getElementById('cont');
                const modalEl = document.getElementById('resetModal');

                confirmBtn.addEventListener('click', () => {
                    fetch('resetDB.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'reset=1'
                    })
                        .then(response => response.text())
                        .then(data => {
                            // Show success message
                            resultContainer.innerHTML = `
                                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                                    <strong>Success!</strong> ${data}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>`;

                            // Focus back to main container
                            container?.focus();
                        })
                        .catch(error => {
                            resultContainer.innerHTML = `<div class="alert alert-danger mt-3">Error: ${error}</div>`;
                        });
                });
            });
        </script>
    </div>

<br> <br>

    <div class="container">
        <h3>2. Begin Import Process</h3>
        <p>Now that the database is ready to import, we can gather our files. Use the nav bar above or the buttons below
            to navigate to the import pages</p>
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
            <div class="btn-group me-2" role="group" aria-label="First group">
                <a type="button" class="btn btn-secondary" href="./Import1.php">Import Page 1</a>
                <a type="button" class="btn btn-secondary" href="./Import2.php">Import Page 2</a>
                <a type="button" class="btn btn-secondary" href="./Import3.php">Import Page 3</a>
            </div>
        </div>
    </div>

    <br> <br>

    <div class="container">
        <h3>3. View Reports</h3>
        <p>Now that the data has been imported, you can view their reports! Use the nav bar above or the buttons below
            to navigate to the report pages</p>
        <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
            <div class="btn-group me-2" role="group" aria-label="First group">
                <a type="button" class="btn btn-secondary" href="./Report1.php">Report Page 1</a>
                <a type="button" class="btn btn-secondary" href="./Report2.php">Report Page 2</a>
                <a type="button" class="btn btn-secondary" href="./Report3.php">Report Page 3</a>
            </div>
        </div>
    </div>


<br>
<?php include 'footer.php'; ?>