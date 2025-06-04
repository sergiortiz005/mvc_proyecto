document.addEventListener("DOMContentLoaded", function () {
    const input = document.getElementById("buscador");
    const sugerencias = document.getElementById("sugerencias");

    input.addEventListener("input", function () {
        const query = this.value.trim();

        if (query.length < 2) {
            sugerencias.innerHTML = "";
            return;
        }

        fetch(`${RUTA_URL}/buscar/sugerencias?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                sugerencias.innerHTML = "";
                data.forEach(item => {
                    const li = document.createElement("li");
                    li.textContent = item.nombre;
                    li.addEventListener("click", () => {
                        if (item.tipo === "jugador") {
                            window.location.href = `${RUTA_URL}/jugador/ver/${item.id}`;
                        } else if (item.tipo === "equipo") {
                            window.location.href = `${RUTA_URL}/equipos/ver/${item.id}`;
                        }
                    });
                    sugerencias.appendChild(li);
                });
            });
    });
});
