$(document).ready(function() {
    // Ocultar la sección de resultados al cargar la página
    $("#resultadoPacientes").hide();

    // Manejador de eventos para el botón Mostrar Todos los Pacientes
    $("#btnMostrarPacientes").click(function() {
        // Realizar una solicitud AJAX para obtener la información de los pacientes
        $.ajax({
            url: "mostrar_pacientes.php",
            type: "GET",
            dataType: "json",
            success: function(data) {
                // Limpiar el contenedor antes de agregar nuevas tarjetas de pacientes
                $("#pacientesDeck").empty();

                // Iterar sobre los pacientes y agregar tarjetas para cada uno
                $.each(data, function(index, paciente) {
                    var card = "<div class='card mb-3'>" +
                        "<img src='" + paciente.imagen_perfil + "' class='card-img-top' alt='Imagen de Perfil'>" +
                        "<div class='card-body'>" +
                        "<h5 class='card-title'>" + paciente.nombre + "</h5>" +
                        "<p class='card-text'>" +
                        "<strong>Edad:</strong> " + paciente.edad + "<br>" +
                        "<strong>Teléfono:</strong> " + paciente.telefono + "<br>" +
                        "<strong>Fecha de Visita:</strong> " + paciente.fecha_visita + "<br>" +
                        "<strong>Diagnóstico:</strong> " + paciente.diagnostico + "<br>" +
                        "<strong>Animal:</strong> " + paciente.animal + "</p>" +
                        "<button class='btn btn-danger btnEliminar' data-id='" + paciente.id + "'>Eliminar</button>" +
                        "</div>" +
                        "</div>";
                    $("#pacientesDeck").append(card);
                });

                // Mostrar la sección de resultados de manera creativa
                $("#resultadoPacientes").show();

                // Asignar eventos a los botones de eliminar
                $(".btnEliminar").click(function() {
                    var idPaciente = $(this).data("id");

                    // Realizar una solicitud AJAX para eliminar el paciente
                    $.ajax({
                        url: "eliminar_paciente.php",
                        type: "POST",
                        data: { id: idPaciente },
                        success: function(response) {
                            alert(response); // Puedes mostrar un mensaje de éxito o manejar la respuesta de otra manera

                            // Volver a cargar la lista de pacientes después de eliminar uno
                            $("#btnMostrarPacientes").trigger("click");
                        },
                        error: function(xhr, status, error) {
                            console.error("Error al eliminar el paciente:", error);
                        }
                    });
                });
            },
            error: function(xhr, status, error) {
                console.error("Error al obtener la información de los pacientes:", error);
            }
        });
    });

    // Manejador de eventos para el formulario de guardar paciente
    $("#formularioPaciente").submit(function(event) {
        event.preventDefault();  // Evitar que el formulario se envíe de manera convencional

        // Obtener los datos del formulario
        var formData = new FormData($(this)[0]);

        // Realizar una solicitud AJAX para guardar el paciente
        $.ajax({
            url: "procesar.php",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(response); // Puedes mostrar un mensaje de éxito o manejar la respuesta de otra manera
                // Puedes agregar más lógica aquí si es necesario

                // Restablecer el formulario después de guardar
                $("#formularioPaciente")[0].reset();

                // Desencadenar la carga de pacientes después de guardar
                $("#btnMostrarPacientes").trigger("click");
            },
            error: function(xhr, status, error) {
                console.error("Error al guardar el paciente:", error);
            }
        });
    });

    // Resto del código sigue igual
});
