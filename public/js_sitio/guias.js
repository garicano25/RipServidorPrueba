console.log('Guía 1:', requiereGuia1);
console.log('Guía 2:', requiereGuia2);
console.log('Guía 3:', requiereGuia3);
console.log('Id:', id);




function guia1() {
    const pregunta1Si = document.getElementById("pregunta1_si").checked;
    const pregunta2Si = document.getElementById("pregunta2_si").checked;
    const pregunta3Si = document.getElementById("pregunta3_si").checked;
    const pregunta4Si = document.getElementById("pregunta4_si").checked;
    const pregunta5Si = document.getElementById("pregunta5_si").checked;
    const pregunta6Si = document.getElementById("pregunta6_si").checked;
    const pregunta7Si = document.getElementById("pregunta7_si").checked;

    if (pregunta1Si || pregunta2Si || pregunta3Si || pregunta4Si || pregunta5Si || pregunta6Si || pregunta7Si) {
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
