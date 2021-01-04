function validar(num) {
	var clase = "formValidar"+num;
	var mensaje = "";
	var mensaje2 = "";
	var mensaje3 = "";
	var mensaje4 = "";
	var inputs = document.getElementsByClassName(clase);
	for (var i = 0; i < inputs.length; i++) {
		if (inputs[i].value == "" || inputs[i].value.length == 0 || /^\s*$/.test(inputs[i].value) || inputs[i].value == "El campo es obligatorio" || inputs[i].value == "23:59") {
			mensaje += "El campo es obligatorio";
			if (inputs[i].type == 'password') {
				inputs[i].placeholder = "El campo es obligatorio";
			} else if (inputs[i].type == 'time') {
				inputs[i].value = "23:59";
			} else {
				inputs[i].placeholder = "El campo es obligatorio";
			}
			
			inputs[i].style.backgroundColor = "#ffaaaa";
			inputs[i].onfocus = function() {
				return limpiar(this);
			}
		}
	}

	if (clase == "formValidar3") {
		var fechaIni = document.getElementById("fechaIni");
		var fechaFin = document.getElementById("fechaFin");
		var horaIni = document.getElementById("horaIni");
		var horaFin = document.getElementById("horaFin");

		if (fechaIni.value != "" && fechaFin.value != "") {
			if (fechaIni.value > fechaFin.value) {
				mensaje2 = "La fecha de inicio no puede ser mayor que la de fin.";
				fechaFin.style.backgroundColor = "#ffaaaa";
				document.getElementById("erroresFormAnyadirReserva").innerHTML = mensaje2;
				document.getElementById("erroresFormAnyadirReserva").style.backgroundColor = "#ffaaaa";
				//alert(mensaje3);
				fechaFin.onclick = function() {
					document.getElementById("erroresFormAnyadirReserva").innerHTML = "";
					document.getElementById("erroresFormAnyadirReserva").style.backgroundColor = "white";
					return limpiar(this);
				}
			} else if (fechaIni.value == fechaFin.value && horaIni.value >= horaFin.value) {
				mensaje2 = "La hora de inicio no puede ser mayor o igual que la de fin.";
				horaFin.style.backgroundColor = "#ffaaaa";
				document.getElementById("erroresFormAnyadirReserva").innerHTML = mensaje2;
				document.getElementById("erroresFormAnyadirReserva").style.backgroundColor = "#ffaaaa";
				//alert(mensaje2);
				horaFin.onclick = function() {
					document.getElementById("erroresFormAnyadirReserva").innerHTML = "";
					document.getElementById("erroresFormAnyadirReserva").style.backgroundColor = "white";
					return limpiar(this);
				}
			}
		}
	}

	if (clase == "formValidar5") {
		var contra5 = document.getElementById("input_contra5");
		var confContra5 = document.getElementById("input_conf_contra5");

		if (confContra5.value != contra5.value) {

			mensaje3 = "Las contraseñas no coinciden. Compruebe que sean iguales.";
			confContra5.style.backgroundColor = "#ffaaaa";
			document.getElementById("erroresFormAnyadirUsuario").innerHTML = mensaje3;
			document.getElementById("erroresFormAnyadirUsuario").style.backgroundColor = "#ffaaaa";
			confContra5.value = "";
			confContra5.onclick = function() {
				document.getElementById("erroresFormAnyadirUsuario").innerHTML = "";
				document.getElementById("erroresFormAnyadirUsuario").style.backgroundColor = "white";
				return limpiar(this);
			}
		}
	}

	if (clase == "formValidar6") {
		var contra6 = document.getElementById("input_contra6");
		var confContra6 = document.getElementById("input_conf_contra6");

		if (contra6.value != "" && (confContra6.value != contra6.value)) {

			mensaje4 = "Las contraseñas no coinciden. Compruebe que sean iguales.";
			confContra6.style.backgroundColor = "#ffaaaa";
			document.getElementById("erroresFormAnyadirUsuario").innerHTML = mensaje3;
			document.getElementById("erroresFormAnyadirUsuario").style.backgroundColor = "#ffaaaa";
			confContra6.value = "";
			confContra6.onclick = function() {
				document.getElementById("erroresFormAnyadirUsuario").innerHTML = "";
				document.getElementById("erroresFormAnyadirUsuario").style.backgroundColor = "white";
				return limpiar(this);
			}
		}
	}

	if (mensaje == "" && mensaje2 == "" && mensaje3 == "" && mensaje4 == "") {
		return true;
	} else {
		return false;
	}
}

function limpiar(target) {
	if (target.value == "El campo es obligatorio" || target.value == "23:59") {
		target.value= "";
		/*target.style.backgroundColor = "white";*/
	}
	target.style.backgroundColor = "white";
}

window.onload = function() {
	try {
		document.formValidar1.onsubmit = function() {
		return validar(1);
		}
	} catch(err) {

	}

	try {
		document.formValidar2.onsubmit = function() {
		return validar(2);
		}
	} catch(err) {

	}

	try {
		document.formValidar3.onsubmit = function() {
		return validar(3);
		}
	} catch(err) {

	}

	try {
		document.formValidar4.onsubmit = function() {
		return validar(4);
		}
	} catch(err) {

	}

	try {
		document.formValidar5.onsubmit = function() {
		return validar(5);
		}
	} catch(err) {

	}

	try {
		document.formValidar6.onsubmit = function() {
		return validar(6);
		}
	} catch(err) {

	}
}