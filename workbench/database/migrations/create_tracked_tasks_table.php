<?php

// Delegates to the package's publishable stub so the workbench schema always
// matches what consumers actually publish, instead of duplicating it here.
// Registered as a real migration so it runs inside the normal migration
// pipeline and survives the reset performed by LazilyRefreshDatabase.
return include __DIR__.'/../../../database/migrations/create_trackable_tasks_table.php.stub';
