document.addEventListener("DOMContentLoaded", function () {
    // Asegúrate de que los elementos estén disponibles antes de asignar eventos
    const btnMostrarProductos = document.getElementById("btnMostrarProductos");

    btnMostrarProductos.addEventListener("click", function () {
        cargarProductos();
    });

    function cargarProductos() {
        fetch("../PHP/mostrar_productos.php")
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                const tabla = document.getElementById("productosBody");
                tabla.innerHTML = ""; // Limpiar tabla antes de insertar nuevos datos

                data.forEach((producto) => {
                    const row = `
                        <tr>
                            <td>${producto.id_producto}</td>
                            <td>${producto.nombre_producto}</td>
                            <td>S/. ${producto.precio}</td>
                            <td>${producto.stock}</td>
                            <td>${producto.id_proveedor}</td>
                            <td>${producto.estado == 1 ? "Activo" : "Inactivo"}</td>
                            <td><img src="../Imagenes/${producto.imagen}" alt="${producto.nombre_producto}" style="width: 80px;"></td>
                            <td>
                                <button class="btn btn-warning" onclick="editarProducto(${producto.id_producto})">Editar</button>
                                <button class="btn btn-danger" onclick="eliminarProducto(${producto.id_producto})">Eliminar</button>
                            </td>
                        </tr>
                    `;
                    tabla.innerHTML += row;
                });
            })
            .catch((error) => {
                console.error("Error al cargar los productos:", error);
            });
    }
});
