console.log('Guía 1:', requiereGuia1);
console.log('Guía 2:', requiereGuia2);
console.log('Guía 3:', requiereGuia3);
console.log('Id:', id);


function mostrarGuias(requiereGuia1, requiereGuia2, requiereGuia3) {
    const guia1 = document.getElementById('guia1');
    if (requiereGuia1 === 1) {
        guia1.style.display = 'block';
    } else {
        guia1.style.display = 'none';
    }

    // Guía 2
    const guia2 = document.getElementById('guia2');
    if (requiereGuia2 === 1) {
        guia2.style.display = 'block';
    } else {
        guia2.style.display = 'none';
    }

    const guia3 = document.getElementById('guia3');
    if (requiereGuia3 === 1) {
        guia3.style.display = 'block';
    } else {
        guia3.style.display = 'none';
    }
}


function botonradio(radioClass) {
    const radios = document.querySelectorAll(`.${radioClass}`);

    radios.forEach((radio) => {
        radio.addEventListener('change', function() {
            const radiosWithSameName = document.querySelectorAll(`input[name="${this.name}"]`);
            
            radiosWithSameName.forEach(radio => {
                const label = document.querySelector(`label[for="${radio.id}"]`);
                label.classList.remove('selected');
            });
            
            const selectedLabel = document.querySelector(`label[for="${this.id}"]`);
            selectedLabel.classList.add('selected');
        });
    });
}


function guia1() {
    const pregunta1Si = document.getElementById("pregunta1_si");
    const pregunta1No = document.getElementById("pregunta1_no");
    const pregunta2Si = document.getElementById("pregunta2_si").checked;
    const pregunta3Si = document.getElementById("pregunta3_si").checked;
    const pregunta4Si = document.getElementById("pregunta4_si").checked;
    const pregunta5Si = document.getElementById("pregunta5_si").checked;
    const pregunta6Si = document.getElementById("pregunta6_si").checked;
    const pregunta7Si = document.getElementById("pregunta7_si").checked;

    const algunaSi = pregunta2Si || pregunta3Si || pregunta4Si || pregunta5Si || pregunta6Si || pregunta7Si;

    if (algunaSi) {
        pregunta1Si.checked = true;
        pregunta1No.checked = false;
    } 
    else {
        pregunta1No.checked = true;
        pregunta1Si.checked = false;
    }

    if (algunaSi) {
        document.getElementById("seccion2").style.display = "block";
        document.getElementById("seccion3").style.display = "block";
        document.getElementById("seccion4").style.display = "block";
    } else {
        document.getElementById("seccion2").style.display = "none";
        document.getElementById("seccion3").style.display = "none";
        document.getElementById("seccion4").style.display = "none";

        const radiosSecciones = document.querySelectorAll('#seccion2 input[type="radio"], #seccion3 input[type="radio"], #seccion4 input[type="radio"]');
        radiosSecciones.forEach(function(radio) {
            radio.checked = false;
        });
    }
}






function clientesyusuarios() {
    const siSeleccionado = document.getElementById("preguntaadi1_si").checked;

    if (siSeleccionado) {
        document.getElementById("seccion2_2").style.display = "block";
    } else {
        document.getElementById("seccion2_2").style.display = "none";

        const inputsSeccion = document.querySelectorAll('#seccion2_2 input[type="radio"]');
        inputsSeccion.forEach(function(input) {
            if (input.type === "radio" || input.type === "checkbox") {
                input.checked = false;
            } else {
                input.value = "";
            }
        });
    }
}



function jefetrabajadores() {
    const sijefe = document.getElementById("preguntaadi2_si").checked;

    if (sijefe) {
        document.getElementById("seccion4_2").style.display = "block";
    } else {
        document.getElementById("seccion4_2").style.display = "none";

        const seccion4guia2 = document.querySelectorAll('#seccion4_2 input[type="radio"]');
        seccion4guia2.forEach(function(input) {
            if (input.type === "radio" || input.type === "checkbox") {
                input.checked = false;
            } else {
                input.value = "";
            }
        });
    }
}





function clientesyusuariosguia3() {
    const siSeleccionadoguia3 = document.getElementById("preguntaadi1_3si").checked;

    if (siSeleccionadoguia3) {
        document.getElementById("seccion2_3").style.display = "block";
    } else {
        document.getElementById("seccion2_3").style.display = "none";

        const inputsSeccionguia3 = document.querySelectorAll('#seccion2_3 input[type="radio"]');
        inputsSeccionguia3.forEach(function(input) {
            if (input.type === "radio" || input.type === "checkbox") {
                input.checked = false;
            } else {
                input.value = "";
            }
        });
    }
}




function jefetrabajadoresguia3() {
    const sijefeguia3 = document.getElementById("preguntaadi2_3si").checked;

    if (sijefeguia3) {
        document.getElementById("seccion4_3").style.display = "block";
    } else {
        document.getElementById("seccion4_3").style.display = "none";

        const seccion4guia3 = document.querySelectorAll('#seccion4_3 input[type="radio"]');
        seccion4guia3.forEach(function(input) {
            if (input.type === "radio" || input.type === "checkbox") {
                input.checked = false;
            } else {
                input.value = "";
            }
        });
    }
}



function cargarExplicaciones() {

    const iconElements = document.querySelectorAll('i[id]');
    const ids = Array.from(iconElements).map(iconElement => iconElement.getAttribute('id')); 

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "/obtenerExplicaciones",
        data: { 
            ids: ids,
            _token: csrfToken  
        },
        cache: false,
        success: function(data) {
            if (data.explicaciones) {
                iconElements.forEach(function(iconElement) {
                    const iconId = iconElement.getAttribute('id'); 

                    const explicacion = data.explicaciones[iconId];
                    if (explicacion) {
                        iconElement.setAttribute('title', explicacion);
                        $(iconElement).tooltip();
                    }
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener las explicaciones:', error);
        }
    });
}