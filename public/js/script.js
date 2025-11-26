document.addEventListener("DOMContentLoaded", function() {
    const departamentoSelect = document.getElementById("region");
    const provinciaSelect = document.getElementById("province");
    const distritoSelect = document.getElementById("district");

    let peruData = {};

    // Cargar el JSON con los datos
    fetch('/data/todo.json')
        .then(response => response.json())
        .then(data => {
            peruData = data;
            loadDepartamentos();
        });

    // Función para cargar departamentos
    function loadDepartamentos() {
        for (let departamento in peruData) {
            const option = document.createElement("option");
            option.value = departamento;
            option.textContent = departamento;
            departamentoSelect.appendChild(option);
        }
    }

    // Función para cargar provincias según el departamento seleccionado
    function loadProvinces(departamento) {
        provinciaSelect.innerHTML = '<option value="">Selecciona una provincia</option>';
        distritoSelect.innerHTML = '<option value="">Selecciona un distrito</option>';
        distritoSelect.disabled = true;

        if (departamento) {
            const provincias = peruData[departamento];
            for (let provincia in provincias) {
                const option = document.createElement("option");
                option.value = provincia;
                option.textContent = provincia;
                provinciaSelect.appendChild(option);
            }
            provinciaSelect.disabled = false;
        } else {
            provinciaSelect.disabled = true;
        }
    }

    // Función para cargar distritos según la provincia seleccionada
    function loadDistricts(departamento, provincia) {
        distritoSelect.innerHTML = '<option value="">Selecciona un distrito</option>';

        if (provincia) {
            const distritos = peruData[departamento][provincia];
            for (let id_distrito in distritos) {
                const option = document.createElement("option");
                option.value = distritos[id_distrito];
                option.textContent = distritos[id_distrito];
                distritoSelect.appendChild(option);
            }
            distritoSelect.disabled = false;
        } else {
            distritoSelect.disabled = true;
        }
    }

    // Eventos para cambiar las opciones según la selección
    departamentoSelect.addEventListener("change", function() {
        const selectedDepartamento = this.value;
        loadProvinces(selectedDepartamento);
    });

    provinciaSelect.addEventListener("change", function() {
        const selectedDepartamento = departamentoSelect.value;
        const selectedProvincia = this.value;
        loadDistricts(selectedDepartamento, selectedProvincia);
    });
});
