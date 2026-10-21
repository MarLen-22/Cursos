<?php

declare(strict_types=1);

/**
 * Script CLI para crear el primer Superadministrador del sistema.
 * Uso:
 *   php src/cli/crear_superadmin.php "Nombre Completo" "correo@ejemplo.com" "ContrasenaSegura123"
 */

if (PHP_SAPI !== 'cli') {
    echo "Este script solo puede ejecutarse desde la terminal (CLI).\n";
    exit(1);
}

$root = dirname(__DIR__, 2);
require_once $root . '/src/core/Autoloader.php';
\App\Core\Autoloader::register($root . '/src');
\App\Core\Env::load($root . '/.env');
\App\Core\Config::load($root . '/src/config');

use App\Core\Database;
use App\Models\AdministradorRepository;
use App\Models\NivelAdmin;
use App\Models\UsuarioRepository;

$db = Database::connection();
$userRepo = new UsuarioRepository($db);
$adminRepo = new AdministradorRepository($db);

$nombre = $argv[1] ?? null;
$email = $argv[2] ?? null;
$password = $argv[3] ?? null;

if (!$nombre || !$email || !$password) {
    echo "========================================================\n";
    echo " Lógica Visual ABP · Crear Superadministrador\n";
    echo "========================================================\n";
    echo "Uso:\n";
    echo "  php src/cli/crear_superadmin.php \"Nombre\" \"correo@dominio.com\" \"Contrasena123\"\n\n";

    echo "Ingrese Nombre completo: ";
    $nombre = trim((string) fgets(STDIN));
    echo "Ingrese Correo electrónico: ";
    $email = trim((string) fgets(STDIN));
    echo "Ingrese Contraseña (mínimo 8 caracteres): ";
    $password = trim((string) fgets(STDIN));
}

$email = strtolower($email);

if (mb_strlen($nombre) < 3) {
    fwrite(STDERR, "Error: El nombre debe tener al menos 3 caracteres.\n");
    exit(1);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR, "Error: El correo electrónico no es válido.\n");
    exit(1);
}

if (strlen($password) < 8) {
    fwrite(STDERR, "Error: La contraseña debe tener al menos 8 caracteres.\n");
    exit(1);
}

$usuario = $userRepo->findByEmail($email);

if ($usuario === null) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $id = $userRepo->create($nombre, $email, $hash);
    echo "[+] Usuario '{$email}' creado con ID #{$id}.\n";
} else {
    $id = $usuario->id;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $userRepo->updatePasswordHash($id, $hash);
    $userRepo->setActivo($id, true);
    echo "[*] Usuario existente '{$email}' (ID #{$id}) actualizado.\n";
}

$admin = $adminRepo->findByUsuario($id);

if ($admin === null) {
    $adminRepo->grant($id, NivelAdmin::SuperAdmin, null);
    echo "[+] Permiso de Superadministrador OTORGADO con éxito a ID #{$id}.\n";
} else {
    $adminRepo->promote($id, NivelAdmin::SuperAdmin);
    echo "[*] Nivel de permisos actualizado a Superadministrador para ID #{$id}.\n";
}

echo "\n¡Listo! Puedes iniciar sesión en: http://localhost:8080/Cursos/public/login\n";
echo "Usuario: {$email}\n";
