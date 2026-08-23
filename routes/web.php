<?php

/*
 * Routes eclatees par module pour limiter les conflits entre sous-agents
 * travaillant en parallele (voir xendaro-fox-plan.json > bonnes_pratiques_dev).
 * Chaque module ne doit toucher QUE son propre fichier de routes.
 */
require __DIR__.'/vitrine.php';
require __DIR__.'/auth.php';
require __DIR__.'/client.php';
require __DIR__.'/trade.php';
