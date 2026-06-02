import React, { useState } from 'react';

export default function FormularioDinamico() {

    const preguntas = [
        '¿Cuantos años tienes?',
        '¿Cuanto pesas?',
        '¿Cuanto mides?',
        '¿Tienes problemas de corazon?',
        '¿Cuantos dias a la semana puedes entrenar?',
        '¿Cuanto tiempo puedes ir a entrenar?',
        '¿Qué objetivo tienes este año?',
    ];

    const [paso, setPaso] = useState(0);

    const [respuestas, setRespuestas] = useState(
        preguntas.map(p => ({
            pregunta: p,
            respuesta: ''
        }))
    );

    const actualizarRespuesta = (valor) => {

        const nuevas = [...respuestas];

        nuevas[paso].respuesta = valor;

        setRespuestas(nuevas);
    };

    const siguiente = () => {

        if (paso < preguntas.length - 1) {
            setPaso(paso + 1);
        }
    };

    const anterior = () => {

        if (paso > 0) {
            setPaso(paso - 1);
        }
    };

    const finalizar = async () => {

    const response = await fetch('/guardar-respuestas', {

        method: 'POST',

        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content
        },

        body: JSON.stringify({
            respuestas
        })
    });

    // Convertir respuesta en PDF
    const blob = await response.blob();

    // Crear URL temporal
    const url = window.URL.createObjectURL(blob);

    // Crear enlace descarga
    const a = document.createElement('a');

    a.href = url;

    a.download = 'resultado.pdf';

    document.body.appendChild(a);

    a.click();

    a.remove();
};

    return (

        <div className="d-flex justify-content-center align-items-center" style={{minHeight: '100vh',background: '#f4f7fb'
            }}
        >

            <div
                className="bg-white p-5 shadow rounded-4"
                style={{
                    width: '100%',
                    maxWidth: '700px'
                }}
            >

                <div className="mb-4">

                    <div className="d-flex justify-content-between mb-2">

                        <span>
                            Pregunta {paso + 1}
                        </span>

                        <span>
                            {preguntas.length}
                        </span>

                    </div>

                    <div className="progress">

                        <div
                            className="progress-bar"
                            style={{
                                width: `${((paso + 1) / preguntas.length) * 100}%`
                            }}
                        />

                    </div>

                </div>

                <div
                    className="text-center"
                    style={{
                        transition: 'all 0.4s ease'
                    }}
                >

                    <h2 className="mb-5 fw-bold">
                        {preguntas[paso]}
                    </h2>

                    <input
                        type="text"
                        className="form-control form-control-lg mb-5"
                        value={respuestas[paso].respuesta}
                        onChange={(e) =>
                            actualizarRespuesta(e.target.value)
                        }
                    />

                    <div className="d-flex justify-content-between">

                        <button
                            className="btn btn-outline-secondary px-4"
                            onClick={anterior}
                            disabled={paso === 0}
                        >
                            Atrás
                        </button>

                        {
                            paso < preguntas.length - 1 ? (

                                <button
                                    className="btn btn-primary px-5"
                                    onClick={siguiente}
                                >
                                    Siguiente
                                </button>

                            ) : (

                                <button
                                    className="btn btn-success px-5"
                                    onClick={finalizar}
                                >
                                    Finalizar
                                </button>
                            )
                        }

                    </div>

                </div>

            </div>

        </div>
    );
}