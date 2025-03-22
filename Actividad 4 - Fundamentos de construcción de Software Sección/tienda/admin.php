<?php
include 'includes/db.php';
include 'includes/auth.php';
redirectIfNotLoggedIn();
redirectIfNotAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $nombre = $_POST['nombre'];
        $stock = $_POST['stock'];
        $precio = $_POST['precio'];

        $stmt = $conn->prepare("INSERT INTO productos (nombre, stock, precio) VALUES (?, ?, ?)");
        $stmt->bind_param("sid", $nombre, $stock, $precio);
        $stmt->execute();
    } elseif (isset($_POST['update'])) {
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $stock = $_POST['stock'];
        $precio = $_POST['precio'];

        $stmt = $conn->prepare("UPDATE productos SET nombre = ?, stock = ?, precio = ? WHERE id = ?");
        $stmt->bind_param("sidi", $nombre, $stock, $precio, $id);
        $stmt->execute();
    } elseif (isset($_POST['delete'])) {
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

$result = $conn->query("SELECT * FROM productos");
$productos = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Panel de Administración</h2>
        <div class="row">
            <div class="col-md-8">
                <h3>Productos</h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?php echo $producto['id']; ?></td>
                                <td><?php echo $producto['nombre']; ?></td>
                                <td><?php echo $producto['stock']; ?></td>
                                <td><?php echo $producto['precio']; ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                                        <input type="text" name="nombre" value="<?php echo $producto['nombre']; ?>">
                                        <input type="number" name="stock" value="<?php echo $producto['stock']; ?>">
                                        <input type="number" step="0.01" name="precio" value="<?php echo $producto['precio']; ?>">
                                        <button type="submit" name="update" class="btn btn-warning btn-sm">Actualizar</button>
                                    </form>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
                                        <button type="submit" name="delete" class="btn btn-danger btn-sm">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-4">
                <h3>Añadir Producto</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio" class="form-label">Precio</label>
                        <input type="number" step="0.01" class="form-control" id="precio" name="precio" required>
                    </div>
                    <button type="submit" name="add" class="btn btn-primary">Añadir</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>