// funciones.js

// Función para iniciar una misión
function resolverMision(idMision) {
    // Elegimos aleatoriamente el tipo de minijuego
    const tipo = Math.floor(Math.random() * 3); // 0,1,2

    switch (tipo) {
        case 0: // Clic rápido
            iniciarClicRapido(idMision);
            break;
        case 1: // Secuencia de teclas
            iniciarSecuenciaTeclas(idMision);
            break;
        case 2: // Acertijo
            iniciarAcertijo(idMision);
            break;
    }
}

// -------------------- CLIC RÁPIDO --------------------
function iniciarClicRapido(idMision) {
    let clicks = 0;
    const objetivoClicks = Math.floor(Math.random() * 5) + 10; // 10 a 14 clicks
    const tiempoLimite = 5000; // 5 segundos

    const modal = crearModal("¡Haz clic rápido!", `Haz clic ${objetivoClicks} veces en 5 segundos`);
    const boton = modal.querySelector("button");

    boton.addEventListener("click", () => {
        clicks++;
        boton.textContent = `🌱 Clic rápido (${clicks}/${objetivoClicks})`;
    });

    const timer = setTimeout(() => {
        if (clicks >= objetivoClicks) {
            alert("¡Misión completada!");
            completarMision(idMision);
        } else {
            alert("No lo lograste, inténtalo otra vez.");
        }
        modal.remove();
    }, tiempoLimite);
}

// -------------------- SECUENCIA DE TECLAS --------------------
function iniciarSecuenciaTeclas(idMision) {
    const patrones = [
        ["a","s","d","f"],
        ["q","w","e","r"],
        ["⬆️","⬇️","⬅️","➡️"],
        ["z","x","c","v","b"]
    ];

    const patron = patrones[Math.floor(Math.random() * patrones.length)];
    let indice = 0;

    const modal = crearModal("Secuencia de teclas", `Presiona esta secuencia: ${patron.join(" → ")}`);
    
    function keyHandler(e) {
        if (e.key === patron[indice]) {
            indice++;
            if (indice === patron.length) {
                alert("¡Misión completada!");
                document.removeEventListener("keydown", keyHandler);
                modal.remove();
                completarMision(idMision);
            }
        } else {
            alert("Fallaste la secuencia, inténtalo de nuevo.");
            indice = 0;
        }
    }

    document.addEventListener("keydown", keyHandler);
}

// -------------------- ACERTIJOS --------------------
function iniciarAcertijo(idMision) {
    const acertijos = [
        {pregunta: "¿Qué tiene cabeza y no es persona?", respuesta: "clavo"},
        {pregunta: "Blanca por dentro, verde por fuera. ¿Qué es?", respuesta: "pera"},
        {pregunta: "Vuelo de día y de noche duermo. ¿Qué soy?", respuesta: "murcielago"},
        {pregunta: "Cuanto más quitas, más grande se hace. ¿Qué es?", respuesta: "hueco"}
    ];

    const acertijo = acertijos[Math.floor(Math.random() * acertijos.length)];
    const modal = crearModal("Acertijo", acertijo.pregunta);
    const input = document.createElement("input");
    input.type = "text";
    input.style.padding = "8px";
    input.style.fontSize = "16px";
    input.style.marginTop = "10px";
    modal.appendChild(input);

    const boton = modal.querySelector("button");
    boton.textContent = "Responder";
    boton.addEventListener("click", () => {
        if (input.value.trim().toLowerCase() === acertijo.respuesta.toLowerCase()) {
            alert("¡Misión completada!");
            modal.remove();
            completarMision(idMision);
        } else {
            alert("Incorrecto, inténtalo otra vez.");
        }
    });
}

// -------------------- MODAL GENÉRICO --------------------
function crearModal(titulo, descripcion) {
    const modal = document.createElement("div");
    Object.assign(modal.style, {
        position: "fixed",
        top: "0",
        left: "0",
        width: "100%",
        height: "100%",
        backgroundColor: "rgba(0,0,0,0.6)",
        display: "flex",
        justifyContent: "center",
        alignItems: "center",
        flexDirection: "column",
        zIndex: "1000",
        padding: "20px",
        boxSizing: "border-box"
    });

    const contenedor = document.createElement("div");
    Object.assign(contenedor.style, {
        background: "#1e293b",
        padding: "30px",
        borderRadius: "15px",
        textAlign: "center",
        maxWidth: "400px",
        width: "100%",
        boxShadow: "0 6px 15px rgba(0,0,0,0.5)",
        color: "#bbf7d0",
        fontFamily: "'Segoe UI', sans-serif"
    });

    const tituloEl = document.createElement("h2");
    tituloEl.textContent = titulo;
    Object.assign(tituloEl.style, {marginBottom: "15px"});

    const descEl = document.createElement("p");
    descEl.textContent = descripcion;

    const boton = document.createElement("button");
    boton.textContent = "Aceptar";
    Object.assign(boton.style, {
        marginTop: "20px",
        padding: "12px 25px",
        borderRadius: "10px",
        background: "linear-gradient(145deg, #22c55e, #4ade80)",
        border: "none",
        color: "#022c22",
        fontWeight: "bold",
        cursor: "pointer"
    });

    contenedor.appendChild(tituloEl);
    contenedor.appendChild(descEl);
    contenedor.appendChild(boton);
    modal.appendChild(contenedor);
    document.body.appendChild(modal);

    return modal;
}

// -------------------- COMPLETAR MISION --------------------
function completarMision(idMision) {
    // Aquí puedes llamar a tu PHP via fetch para marcar la misión como completada
    fetch(`completar_mision.php`, {
        method: "POST",
        headers: {"Content-Type":"application/x-www-form-urlencoded"},
        body: `mision_id=${idMision}`
    }).then(res => {
        if(res.ok) location.reload(); // Recarga para actualizar ECO
    });
}