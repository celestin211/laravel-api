<?php

/**
 * Script de configuration de la base de données pour les tests.
 *
 * Usage: php tests/database-setup.php
 */
echo "🔍 Vérification de la configuration MySQL pour les tests...\n\n";

// Configuration par défaut (peut être modifiée)
$config = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'database' => 'laravel_test',
    'username' => 'root',
    'password' => '',
];

// Demander les identifiants si nécessaire
echo "Configuration actuelle:\n";
echo "  Host: {$config['host']}\n";
echo "  Port: {$config['port']}\n";
echo "  Database: {$config['database']}\n";
echo "  Username: {$config['username']}\n";
echo '  Password: '.(empty($config['password']) ? '(vide)' : '***')."\n\n";

// Tester la connexion
try {
    $dsn = "mysql:host={$config['host']};port={$config['port']}";
    $pdo = new PDO($dsn, $config['username'], $config['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✅ Connexion MySQL réussie!\n\n";

    // Créer la base de données
    try {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$config['database']}`");
        echo "✅ Base de données '{$config['database']}' créée ou existe déjà.\n\n";
    } catch (PDOException $e) {
        echo '❌ Erreur lors de la création de la base de données: '.$e->getMessage()."\n\n";
        exit(1);
    }

    echo "📝 Instructions:\n";
    echo "1. Si vous avez modifié les identifiants, mettez à jour phpunit.xml:\n";
    echo "   <env name=\"DB_USERNAME\" value=\"{$config['username']}\"/>\n";
    if (! empty($config['password'])) {
        echo "   <env name=\"DB_PASSWORD\" value=\"{$config['password']}\"/>\n";
    }
    echo "\n2. Lancez les migrations:\n";
    echo "   php artisan migrate --database=mysql --env=testing\n\n";
    echo "3. Lancez les tests:\n";
    echo "   php artisan test\n\n";

} catch (PDOException $e) {
    echo '❌ Erreur de connexion MySQL: '.$e->getMessage()."\n\n";
    echo "💡 Solutions possibles:\n";
    echo "1. Vérifiez que MySQL est installé et en cours d'exécution\n";
    echo "2. Vérifiez les identifiants dans phpunit.xml\n";
    echo "3. Si MySQL nécessite un mot de passe, modifiez phpunit.xml:\n";
    echo "   <env name=\"DB_PASSWORD\" value=\"votre_mot_de_passe\"/>\n";
    echo "4. Créez la base de données manuellement:\n";
    echo "   mysql -u {$config['username']} -p -e \"CREATE DATABASE {$config['database']};\"\n\n";
    exit(1);
}
